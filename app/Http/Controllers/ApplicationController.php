<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    protected $service;

    public function __construct(ApplicationService $applicationService)
    {
        $this->service = $applicationService;
    }

    public function index()
    {  
        $applications = Application::with('user.personal_info', 'user.address', 'user.workDetails', 'lot.property')->get();
        return Inertia::render('application/index', [
            'applications' => $applications
        ]);
    }
    public function store(Request $request)
    {
        $this->service->doCreate($request->all());
    }

    public function update(Request $request, $id)
    {
        $this->service->doUpdate($request->all(), $id);
    }

    public function getUserApplication()
    {
        return Inertia::render('application/index', [
            'applications' => $this->service->getByUser()
        ]);
    }
}
