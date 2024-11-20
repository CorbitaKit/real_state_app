<?php

namespace App\Http\Controllers;

use App\Services\PropertyService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    protected $service;

    public function __construct(PropertyService $propertyService)
    {
        $this->service = $propertyService;
    }

    public function index()
    {
        return Inertia::render(
            'property/index',
            [
                'properties' => $this->service->doGet()
            ]
        );
    }


    public function create()
    {
        return Inertia::render('property/create');
    }

    public function show($id)
    {
        return Inertia::render('property/show', [
            'property' => $this->service->doFindById($id)
        ]);
    }

    public function store(Request $request)
    {
        $this->service->doStore($request);
    }

    public function destroy($property_id)
    {
        $this->service->doDestroy($property_id);
        return Inertia::render(
            'property/index',
            [
                'properties' => $this->service->doGet()
            ]
        );
    }
}
