<?php

namespace App\Http\Controllers\Api;

use App\Models\Departement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartementController extends Controller
{
    public function getDepartements()
    {
        return response()->json(Departement::all());
    }
}
