<?php

namespace Asistencias\Http\Controllers;

use Illuminate\Http\Request;

use Asistencias\Http\Requests;
use Asistencias\Http\Controllers\Controller;
use Asistencias\usuario2;
use Asistencias\unidadadmin;
use Asistencias\Controllers\LdapController;
use PDO;
use Session;
use DB;

class SigespController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCedSigesp(Request $request,$ced)
    {
      if ($request->ajax()) {
            $db = DB::connection('vive_2016');

            $datos = $db->getPdo()->prepare("SELECT * FROM fn_usuarios_carnets(?,?)");

            $datos->execute(['cedula', $ced]);

            $result_emp = $datos->fetchAll(PDO::FETCH_CLASS, 'stdClass');

            /*foreach ($result_emp as $value) { 
                mb_detect_encoding($value->apellidos , 'ASCII,UTF-8,ISO-8859-1'); 
            }

            $resultado =mb_detect_encoding($result_emp, 'ASCII,UTF-8,ISO-8859-1');*/

            return response()->json($result_emp);
        }
    }
    /*function utf8_encode_deep($input) {
        if (is_string($input)) { 
            $input = utf8_encode($input); 
        } 
        else if (is_array($input)) { 
            foreach ($input as $value) { 
                self::utf8_encode_deep($value); 
            }
            return $value; unset($value); 
        } 
        else if (is_object($input)) { 
            $vars = array_keys(get_object_vars($input)); 
            foreach ($vars as $var) { 
                self::utf8_encode_deep($input->$var); 
            } 
        } 
    } */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
