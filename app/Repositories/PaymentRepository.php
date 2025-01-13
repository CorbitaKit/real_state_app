<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends Repository
{
    protected $relations = ['lots.property', 'user.personal_info', 'files'];
    public function __construct(Payment $payment)
    {
        parent::__construct($payment, $this->relations);
    }

    public function getUserPayment(int $userId): Collection
    {
        return $this->model->with($this->relations)->where('user_id', $userId)->get();
    }
}
