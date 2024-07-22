<?php

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Collection;

class ApplicationRepository extends Repository
{
    protected $relations = ['lot.property', 'user.personal_info'];
    public function __construct(Application $application)
    {
        parent::__construct($application, $this->relations);
    }

    public function doGetByUser(int $userId): Collection
    {
        return $this->model->with($this->relations)->where('user_id', $userId)->get();
    }
}
