<?php

namespace App\Observers;

use App\Mail\ApplicationApproved;
use App\Mail\ApplicationRejected;
use App\Models\Application;
use App\Models\Lot;
use App\Models\Notification;
use App\Services\LotService;
use Illuminate\Support\Facades\Mail;

class ApplicationObserver
{
    protected $lotService;

    public function __construct(LotService $lotService)
    {
        $this->lotService = $lotService;
    }
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        $status = 'Available';

        if ($application->application_type === 'Lot Application') {
            $status = 'Pending';
            $this->lotService->doUpdate(['status' => $status, 'user_id' => $application->user_id], $application->lot_id);

        }
        Notification::create([
            'text' => 'New Lot Application Added',
            'type' => 'application',
            'is_read' => 0,
            'is_admin' => 1
        ]);
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        $user = $application->user;
        if ($application->status === 'Rejected') {

            Mail::to($user->email)->queue(new ApplicationRejected($application, $user));
        } else {
            $lot = Lot::with('property')->where('id', $application->lot_id)->first();
            Mail::to($user->email)->queue(new ApplicationApproved($lot));
        }
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
