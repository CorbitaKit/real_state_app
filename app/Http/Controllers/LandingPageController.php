<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PropertyService;
use Inertia\Inertia;
class LandingPageController extends Controller
{
    protected $service;

    public function __construct(PropertyService $propertyService)
    {
        $this->service = $propertyService;
    }

    public function index()
    {
        $properties = $this->service->doGet();
        return Inertia::render('landing/index', [
            'properties' => $properties
        ]);
    }

    public function show($id)
    {
        return Inertia::render('landing/details', [
            'property' => $this->service->doFindById($id)
        ]);
    }
}
