<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Services\LotService;

class ApplicationService extends Service
{
    protected $lotService;
    public function __construct(ApplicationRepository $repo, LotService $lotService)
    {
        parent::__construct($repo);
        $this->lotService = $lotService;
    }

    public function getByUser(): Collection
    {
        $userId = Auth::user()->id;
        return $this->repo->doGetByUser($userId);
    }

    public function doUpdate(array $data, int $id): Model
    {
        $application = parent::doUpdate($data, $id);

        $this->lotService->doUpdate(['status' => 'Occupied'], $application->lot->id);
        return $application;
    }
}
