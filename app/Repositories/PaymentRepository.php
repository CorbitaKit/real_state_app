<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends Repository
{
    protected $relations = ['user.personal_info', 'files', 'lot.property'];
    public function __construct(Payment $payment)
    {
        parent::__construct($payment, $this->relations);
    }

    public function getUserPayment(int $userId): Collection
    {
        return $this->model->with($this->relations)->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }
}
