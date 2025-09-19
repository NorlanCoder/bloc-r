<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Circonscription;
use App\Http\Controllers\Controller;

class CirconscriptionController extends Controller
{
    public function getCirconscriptions()
    {
        return response()->json(Circonscription::all());
    }
}
