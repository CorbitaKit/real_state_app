<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends Repository
{
    protected $relations = ['role', 'personal_info', 'address', 'workDetails', 'profilePicture'];
    public function __construct(User $user)
    {
        parent::__construct($user, $this->relations);
    }

    public function getStaffs(): Collection
    {
        return $this->model->with($this->relations)->where('role_id', 2)->get();
    }
}
