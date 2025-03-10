<?php

namespace App\Services;

use App\Models\Lot;
use App\Models\Notification;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PaymentPlan;
use App\Models\Property;
use App\Models\Transaction;

class PaymentService extends Service
{
    use FileUploadTrait;

    protected $fileService;
    public function __construct(PaymentRepository $repo, FileService $fileService)
    {
        parent::__construct($repo);
        $this->fileService = $fileService;
    }

    public function userPayment(): Collection
    {
        return $this->repo->getUserPayment(Auth::user()->id);
    }

    public function doCreate(array $data): Model
    {
        $amountPaid = $data['amount'];
        $lot = Lot::with('lotGroup')->where('id', $data['lot_id'])->first();

        // Get the latest payment for this lot
        $lastPayment = Payment::where('lot_id', $data['lot_id'])->latest()->first();
        $remainingBalance = $lot->lotGroup->monthly_amortizations - ($lastPayment->amount ?? 0);

        while ($amountPaid > 0) {
            $isPartialPaid = false;

            if ($lastPayment && $lastPayment->amount < $lot->lotGroup->monthly_amortizations) {
                // Adjust last payment to match expected amount
                $lastPayment->amount += $remainingBalance;
                $lastPayment->save();
                $isPartialPaid = true;
            } else {
                // Create a new payment entry
                $data['amount'] = min($amountPaid, $lot->lotGroup->monthly_amortizations);
                $payment = parent::doCreate($data);

                // Send notification
                Notification::create([
                    'text' => 'There is a payment made by a client',
                    'type' => 'payment',
                    'is_read' => 0,
                    'is_admin' => 1,
                ]);

                // Attach to a payment plan if available
                $paymentPlan = PaymentPlan::where('lot_id', $payment->lot_id)->whereNull('payment_id')->first();
                if ($paymentPlan) {
                    $paymentPlan->payment_id = $payment->id;
                    $paymentPlan->save();
                }

                // Handle file upload if a file exists
                if (!empty($data['file']['value'])) {
                    $fileUpload = $data['file']['value'];
                    $path = $this->uploadFile($fileUpload, 'files/payments');

                    $file = $this->fileService->doCreate([
                        'filename' => $fileUpload->getClientOriginalName(),
                        'url' => $path
                    ]);
                    $payment->files()->save($file);
                }
            }

            // Deduct the amount paid
            if ($isPartialPaid) {
                $amountPaid -= $remainingBalance;
            } else {
                $amountPaid -= $data['amount'];
            }

            // Update last payment reference
            $lastPayment = isset($payment) ? $payment : $lastPayment;
        }

        return $lastPayment;

    }

    public function doUpdate(array $data, $id): Model
    {
        $payment = parent::doUpdate($data, $id);
        Notification::create([
            'text' => 'Your payment has been acknowledged',
            'type' => 'payment',
            'is_read' => 0,
            'is_admin' => 0,
            'user_id' => $payment->user_id
        ]);

        $lot = Lot::with('lotGroup')->where('id', $payment->lot_id)->first();

        $transaction = Transaction::where('property_id', $lot->property_id)->where('user_id', $payment->user_id)->first();

        $condition = true;
        $monthOffset = 0;

        while ($condition) {
            $startOfMonth = Carbon::now()->addMonths($monthOffset)->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::now()->addMonths($monthOffset)->endOfMonth()->format('Y-m-d');

            $paymentPlans = PaymentPlan::where('user_id', $payment->user_id)
                ->where('lot_id', $payment->lot_id)
                ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
                ->first();

            if (!$paymentPlans) {
                $condition = false;
            } elseif (!$paymentPlans->payment_id) {
                $paymentPlans->payment_id = $payment->id;
                $paymentPlans->save();
                $condition = false;
            } else {
                $monthOffset++;
            }
        }



        if ($transaction) {
            $total_amount = $transaction->total_amount + $payment->amount;
            $transaction->total_amount = $total_amount;
            $transaction->balance = $transaction->balance - $payment->amount;
            $transaction->save();
        } else {
            $property = Property::where('id', $lot->property_id)->first();
            Transaction::create([
                'property_id' => $property->id,
                'user_id' => $payment->user_id,
                'total_amount' => $payment->amount,
                'tcp' => $lot->lotGroup->sqr_meter * $lot->lotGroup->amount_per_sqr_meter,
                'balance' => ($lot->lotGroup->sqr_meter * $lot->lotGroup->amount_per_sqr_meter) - $payment->amount
            ]);
        }
        $fileUpload = $data['file']['value'];
        $path = $this->uploadFile($fileUpload, 'files/payments/invoice');
        $file = $this->fileService->doCreate([
            'filename' => $fileUpload->getClientOriginalName(),
            'url' => $path
        ]);
        $payment->files()->save($file);
        return $payment;
    }
}
