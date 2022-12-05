<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


use App\Models\Cliente;
use App\Models\Pasajero;
use App\Models\Conductor;
use App\Models\ConductorHojaDeVida;


use App\Models\User;
use App\Models\Direccion;
use Illuminate\Support\Facades\Hash;


class ImportadorController extends Controller
{
   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {   

        

        $file = $request->file('file');
        $model=$request->get('model');
        switch ($model) {
            case 'clientes':
                $this->setCliente($file);
                break;
            case 'pasajeros':
                $this->setPasajero($file);
                break;
            case 'conductores':
                $this->setConductor($file);
                 break;
            default:
                # code...
                break;
        }

        return redirect('/'.$model);
        

        abort(500, 'Error inesperado al tratar de importar el archivo');


    }

    public function setCliente($file){

        $fopen=fopen($file->getRealPath(),'r');
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        $error=false;
        //Read the contents of the uploaded file 
        while (($filedata = fgetcsv($fopen, 1000, ";")) !== FALSE) {
            $num = count($filedata);
            // Skip first row (Remove below comment if you want to skip the first row)
            if ($i == 0) {
                $i++;
                continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($fopen); //Close after reading

        $j = 0;
        $arr_clientes=array();

        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
            DB::beginTransaction();
           
            $documento=$importData[0];
            $nombres=$importData[1];
            $apellidos=$importData[2];
            $razon_social=$importData[3];
            $telefono=$importData[4];
            $celular=$importData[5];
            $whatsapp=$importData[6];
            $departamento_id=$importData[7];
            $ciudad_id=$importData[8];
            $direccion_str=$importData[9];
            $detalle_direccion=$importData[10];
            $email=$importData[11];
            $password=$importData[12];
            $direccion_id=null;
            $user_id=null;

            if($departamento_id!="" && $ciudad_id!="" ){
                $direccion=new Direccion();
                $direccion->departamento_id=$departamento_id;
                $direccion->ciudad_id=$ciudad_id;
                $direccion->direccion1=$direccion_str;
                $direccion->direccion2=$detalle_direccion;
                $direccion->tipo_usuario=1;
                $direccion->save();

                $direccion_id=$direccion->id;
            }
            if($email!="" && $password!=""){
                $user=new User();
                $user->name=$nombres;
                $user->email=$email;
                //Si el password es diferente de vacio lo cambiamos
                if($password!=""){
                    $user->password=Hash::make($password);
                }
                $user->save();
                $user_id=$user->id;
            }

            $row_cliente=Cliente::create([
            'documento' => $documento,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'razon_social' => $razon_social,
            'telefono' => $telefono,
            'celular' => $celular,
            'whatsapp'=>$whatsapp,
            'activo'=>1,
            'email_contacto'=>$email,
            'direccion_id'=>$direccion_id,
            'user_id'=>$user_id

            ]);

            if($direccion_id>0){
                $direccion->parent_id=$row_cliente->id;
                $direccion->save();
            }
            DB::commit();
            $arr_clientes[]=$row_cliente;
            } catch (\Exception $e) {
               $error=true;
                DB::rollBack();
            }
        }
        if(!$error){
            \Session::flash('flash_message','Clientes importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los clientes!.');
        }
        
    }


    public function setPasajero($file){

        $fopen=fopen($file->getRealPath(),'r');
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        $error=false;
        //Read the contents of the uploaded file 
        while (($filedata = fgetcsv($fopen, 1000, ";")) !== FALSE) {
            $num = count($filedata);
            // Skip first row (Remove below comment if you want to skip the first row)
            if ($i == 0) {
                $i++;
                continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($fopen); //Close after reading

        $j = 0;
        $arr_pasajeros=array();

        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
            DB::beginTransaction();
           
            $documento_cliente=$importData[0];
            $documento=$importData[1];
            $nombres=$importData[2];
            $apellidos=$importData[3];
            $codigo=$importData[4];
            $telefono=$importData[5];
            $celular=$importData[6];
            $whatsapp=$importData[7];
            $departamento_id=$importData[8];
            $ciudad_id=$importData[9];
            $direccion_str=$importData[10];
            $detalle_direccion=$importData[11];
            $nombre_contacto=$importData[12];
            $telefono_contacto=$importData[13];
            $email=$importData[14];
            $password=$importData[15];
            $direccion_id=null;
            $user_id=null;

            if($departamento_id!="" && $ciudad_id!=""){
                $direccion=new Direccion();
                $direccion->departamento_id=$departamento_id;
                $direccion->ciudad_id=$ciudad_id;
                $direccion->direccion1=$direccion_str;
                $direccion->direccion2=$detalle_direccion;
                $direccion->tipo_usuario=3;
                $direccion->save();

                $direccion_id=$direccion->id;
            }
            if($email!="" && $password!=""){
                $user=new User();
                $user->name=$nombres;
                $user->email=$email;
                //Si el password es diferente de vacio lo cambiamos
                if($password!=""){
                    $user->password=Hash::make($password);
                }
                $user->save();
                $user_id=$user->id;
            }

            $row_pasajero=Pasajero::create([
            'documento' => $documento,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'codigo' => $codigo,
            'telefono' => $telefono,
            'celular' => $celular,
            'whatsapp'=>$whatsapp,
            'activo'=>1,
            'email_contacto'=>$email,
            'nombre_contacto'=>$nombre_contacto,
            'telefono_contacto'=>$telefono_contacto,
            'direccion_id'=>$direccion_id,
            'user_id'=>$user_id

            ]);

            if($direccion_id>0){
                $direccion->parent_id=$row_pasajero->id;
                $direccion->save();
            }
            //Send Email
            DB::commit();
            $arr_pasajeros[]=$row_pasajero;
            } catch (\Exception $e) {
               $error=true;
                DB::rollBack();
            }
        }
        if(!$error){
            \Session::flash('flash_message','Pasajeros importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los clientes!.');
        }
        
    }


    public function setConductor($file){

        $fopen=fopen($file->getRealPath(),'r');
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        $error=false;
        //Read the contents of the uploaded file 
        while (($filedata = fgetcsv($fopen, 1000, ";")) !== FALSE) {
            $num = count($filedata);
            // Skip first row (Remove below comment if you want to skip the first row)
            if ($i == 0) {
                $i++;
                continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($fopen); //Close after reading

        $j = 0;
        $arr_conductores=array();

        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
            DB::beginTransaction();
           
            $documento=$importData[0];
            $nombres=$importData[1];
            $apellidos=$importData[2];
            $lugar_exp_documento=$importData[3];
            $telefono=$importData[4];
            $celular=$importData[5];
            $whatsapp=$importData[6];
            $departamento_id=$importData[7];
            $ciudad_id=$importData[8];
            $barrio=$importData[9];
            $direccion_str=$importData[10];
            $detalle_direccion=$importData[11];
            $nombre_contacto=$importData[12];
            $telefono_contacto=$importData[13];
            $email=$importData[14];
            $password=$importData[15];
            $lugar_nacimiento=$importData[16];
            $estado_civil=$importData[17];
            $grupo_sanguineo=$importData[18];
            $numero_hijos=$importData[19];
            $estrato=$importData[20];

            $direccion_id=null;
            $user_id=null;

            if($departamento_id!="" && $ciudad_id!="" ){
                $direccion=new Direccion();
                $direccion->departamento_id=$departamento_id;
                $direccion->ciudad_id=$ciudad_id;
                $direccion->barrio=$barrio;
                $direccion->direccion1=$direccion_str;
                $direccion->direccion2=$detalle_direccion;
                $direccion->tipo_usuario=5;
                $direccion->save();
                $direccion_id=$direccion->id;
            }
            if($email!="" && $password!=""){
                $user=new User();
                $user->name=$nombres;
                $user->email=$email;
                //Si el password es diferente de vacio lo cambiamos
                if($password!=""){
                    $user->password=Hash::make($password);
                }
                $user->save();
                $user_id=$user->id;
            }

            $row_conductor=Conductor::create([
            'documento' => $documento,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'lugar_expedicion_documento' => $lugar_exp_documento,
            'telefono' => $telefono,
            'celular' => $celular,
            'whatsapp'=>$whatsapp,
            'activo'=>1,
            'email_contacto'=>$email,
            'nombre_contacto'=>$nombre_contacto,
            'telefono_contacto'=>$telefono_contacto,
            'direccion_id'=>$direccion_id,
            'estado_civil'=>$estado_civil,
            'grupo_sanguineo'=>$grupo_sanguineo,
            'user_id'=>$user_id,
            'lugar_de_nacimiento'=>$lugar_nacimiento
            
            ]);
            
            $hoja_vida=new ConductorHojaDeVida();
            $hoja_vida->conductor_id=$row_conductor->id;
            if((int) $estrato>0){
                $hoja_vida->estrato=$estrato;
            }
            if((int) $numero_hijos>0){
                $hoja_vida->numero_hijos=$numero_hijos;
            }
            $hoja_vida->save();
            if($direccion_id>0){
                $direccion->parent_id=$row_conductor->id;
                $direccion->save();
            }
            DB::commit();
            $arr_conductores[]=$row_conductor;
            } catch (\Exception $e) {
               $error=true;
               var_dump($e);
                DB::rollBack();
                die();
            }
        }
        if(!$error){
            \Session::flash('flash_message','Clientes importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los clientes!.');
        }
        
    }


}
