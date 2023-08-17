<?php

namespace App\Http\Controllers;

use App\Models\Villes;
use Illuminate\Http\Request;

class AutreparametrageController extends Controller
{
    //

    public function list(){
        return view('back/autre_parametrage/list');
    }




}
