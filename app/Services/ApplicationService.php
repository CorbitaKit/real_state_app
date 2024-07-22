<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApplicationService extends Service
{
    public function __construct(ApplicationRepository $repo)
    {
        parent::__construct($repo);
    }

    public function getByUser(): Collection
    {
        $userId = Auth::user()->id;
        return $this->repo->doGetByUser($userId);
    }
}
