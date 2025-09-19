<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Communes;
use Illuminate\Http\Request;

class CommuneController extends Controller
{
    public function getCommunes()
    {
        return response()->json(Communes::all());
    }
}
