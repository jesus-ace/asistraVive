<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;
use Asistencias\Usuarios;
use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FrontControllers extends Controller
{
    public function login()
    {
        return view('login');
        
    }
    
}
       
    /*public function showWelcome()
    {
        return view('inicio');
    }*/

