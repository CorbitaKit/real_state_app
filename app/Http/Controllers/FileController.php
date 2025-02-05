<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
 
class FileController extends Controller
{
    public function viewUserDocuments()
    {
        return Inertia::render('user/documents');
    }
}
