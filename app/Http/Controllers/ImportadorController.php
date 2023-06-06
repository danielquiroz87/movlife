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
use App\Models\Vehiculo;
use App\Models\Propietario;
use App\Models\Documentos;
use App\Models\TipoDocumentos;

use App\Models\User;
use App\Models\Direccion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


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
            case 'vehiculos':
                $this->setVehiculos($file);
                break;
            case 'propietarios':
                $this->setPropietario($file);
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

        DB::beginTransaction();


        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
           
            $documento=$importData[0];
            $documento=trim($documento);
            
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

             if($documento!=""){
                $existec=Cliente::where('documento','=',$documento)->get()->first();
                if($existec){
                    $error=true;
                    throw new \Exception("Ya existe un cliente con el número de documento ".$documento);
                    break;
                }
            }else{
               $error=true;
                throw new \Exception("El documento es requerido.");
                break; 
            }

            if($departamento_id==""){
                    $error=true;
                    throw new \Exception("El campo departamento es requerido");
                    break;
            }
            if($ciudad_id==""){
                    $error=true;
                    throw new \Exception("El campo ciudad es requerido");
                    break;
            }

             if($telefono=="" || $celular=="" || $whatsapp==""){
                $error=true;
                throw new \Exception("El teléfono,celular o whatsapp son requeridos");
                break;
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
            $arr_clientes[]=$row_cliente;
            } catch (\Exception $e) {
                $error=true;
                $message='Error en la fila '.($j+1).' ';
                $message.=($e->getMessage());
                DB::rollBack();
               
            }
        }
        if(!$error){
            DB::commit();
            \Session::flash('flash_message','Clientes importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los clientes!. '.$message);
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

        DB::beginTransaction();


        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
           
            $documento_cliente=$importData[0];
            $documento=$importData[1];
            $documento=trim($documento);
            $nombres=$importData[2];
            $nombres=trim($nombres);
            $nombres=filter_var($nombres,FILTER_SANITIZE_STRING);
            
            $apellidos=$importData[3];
            $apellidos=trim($apellidos);
            $apellidos=filter_var($apellidos,FILTER_SANITIZE_STRING);
            
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
            $existep=false;
            $existepa=false;

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
            else{
                if($departamento_id==""){
                    $error=true;
                    throw new \Exception("El campo departamento es requerido");
                    break;
                }
                if($ciudad_id==""){
                    $error=true;
                    throw new \Exception("El campo ciudad es requerido");
                    break;
                }
            }
            if($documento!=""){
                $existep=Pasajero::where('documento','=',$documento)->get()->first();
            }

            $existepa=Pasajero::where('nombres','=',$nombres)->where('apellidos',$apellidos)->get()->first();
            if($existepa){
                    $error=true;
                    throw new \Exception("Ya existe un pasajero con los nombres ".$nombres.' '.$apellidos);
                    break;
            }
            if($existep){
                    $error=true;
                    throw new \Exception("Ya existe un pasajero con el número de documento ".$documento);
                    break;
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
            $arr_pasajeros[]=$row_pasajero;
            } catch (\Exception $e) {
                $error=true;
                $message='Error en la fila '.($j+1).' ';
                $message.=($e->getMessage());
                DB::rollBack();
           
            }
        }
        if(!$error){
            DB::commit();
            \Session::flash('flash_message','Pasajeros importados exitosamente!.');
        }else{
             DB::rollBack();
             \Session::flash('flash_bad_message','Error al tratar de importar los pasajeros!. '.$message);
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

        DB::beginTransaction();


        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
           
            $documento=$importData[0];
            $documento=trim($documento); 
            $documento=filter_var($documento,FILTER_SANITIZE_STRING);
            $nombres=$importData[1];
            $nombres=trim($nombres);
            $nombres=filter_var($nombres,FILTER_SANITIZE_STRING);
            $apellidos=$importData[2];
            $apellidos=trim($apellidos);
            $apellidos=filter_var($apellidos,FILTER_SANITIZE_STRING);
            
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
            $placa=$importData[21];
           
            $nro_licencia=trim($importData[22]);//1
            $str_fecha_licencia=trim($importData[23]);
            $fecha_licencia="";
            if($str_fecha_licencia!=""){
                $exp_fecha_licencia=explode("/", $str_fecha_licencia);
                $fecha_licencia=$exp_fecha_licencia[2].'-'.$exp_fecha_licencia[1].'-'.$exp_fecha_licencia[0];
            }
            $estado_licencia=trim($importData[24]);
            
            $planilla_ss=trim($importData[25]); //2
            $planilla_ss=strtoupper($planilla_ss);

            $rut=trim($importData[26]); //7
            $rut=strtoupper($rut); //7
            
            $antecedentes=trim($importData[27]); // 18
            $antecedentes=strtoupper($antecedentes); // 18
            
            $cursos=trim($importData[28]); //19
            $cursos=strtoupper($cursos); // 19
            

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

            if($documento==""){
                $error=true;
                throw new \Exception("El documento es requerido.");
                break;
            }
            else{
                $existec=Conductor::where('documento','=',$documento)->get()->first();
                if($existec){
                    $error=true;
                    throw new \Exception("Ya existe un conductor con el número de documento ".$documento);
                    break;
                }
            }

            if($telefono=="" || $celular=="" || $whatsapp==""){
                $error=true;
                throw new \Exception("El teléfono,celular o whatsapp son requeridos");
                break;
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
            if($nro_licencia!=""){

                $doc=new Documentos();
                $doc->id_tipo_documento=1;
                $doc->id_registro=$row_conductor->id;
                
                if($fecha_licencia!=""){
                    $doc->fecha_final=$fecha_licencia;
                }
                if($nro_licencia!=""){
                    $doc->numero_documento=$nro_licencia;
                }
                
                $doc->save();
            }

            if($planilla_ss=="SI"){
                $doc=new Documentos();
                $doc->id_tipo_documento=2;
                $doc->id_registro=$row_conductor->id;
                $doc->save();
            }

            if($rut=="SI"){
                $doc=new Documentos();
                $doc->id_tipo_documento=7;
                $doc->id_registro=$row_conductor->id;
                $doc->save();
            }
            if($antecedentes=="SI"){
                $doc=new Documentos();
                $doc->id_tipo_documento=18;
                $doc->id_registro=$row_conductor->id;
                $doc->save();
            }
            if($cursos=="SI"){
                $doc=new Documentos();
                $doc->id_tipo_documento=19;
                $doc->id_registro=$row_conductor->id;
                $doc->save();
            }

            $arr_conductores[]=$row_conductor;
            } catch (\Exception $e) {
                $error=true;
                $message='Error en la fila '.($j+1).' ';
                $message.=($e->getMessage());
                DB::rollBack();
               
            }
        }
        if(!$error){
            DB::commit();
            \Session::flash('flash_message','Conductores importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los conductores!.'.$message);
        }
        
    }


    public function setPropietario($file){

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
        $arr_propietarios=array();

        DB::beginTransaction();


        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
           
            $documento=$importData[0];
            $documento=trim($documento); 
            $documento=filter_var($documento,FILTER_SANITIZE_STRING);
            $nombres=$importData[1];
            $nombres=trim($nombres);
            $nombres=filter_var($nombres,FILTER_SANITIZE_STRING);
            $apellidos=$importData[2];
            $apellidos=trim($apellidos);
            $apellidos=filter_var($apellidos,FILTER_SANITIZE_STRING);
            $razon_social=$importData[3];
            $razon_social=trim($razon_social);
            $razon_social=filter_var($razon_social,FILTER_SANITIZE_STRING);
            
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
                //$direccion->barrio=$barrio;
                $direccion->direccion1=$direccion_str;
                $direccion->direccion2=$detalle_direccion;
                $direccion->tipo_usuario=4;
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

            if($documento==""){
                $error=true;
                throw new \Exception("El documento es requerido.");
                break;
            }
            else{
                $existec=Propietario::where('documento','=',$documento)->get()->first();
                if($existec){
                    $error=true;
                    throw new \Exception("Ya existe un propietario con el número de documento ".$documento);
                    break;
                }
            }
            
            /*
            if($telefono=="" || $celular=="" || $whatsapp==""){
                $error=true;
                throw new \Exception("El teléfono,celular o whatsapp son requeridos");
                break;
            }   
            */


            $row_propietario=Propietario::create([
            'documento' => $documento,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'razon_social'=>$razon_social,
            'telefono' => $telefono,
            'celular' => $celular,
            'whatsapp'=>$whatsapp,
            'activo'=>1,
            'user_id'=>$user_id,
            'direccion_id'=>$direccion_id,
            
            
            ]);
            
            
            $arr_propietarios[]=$row_propietario;

            } catch (\Exception $e) {
                $error=true;
                $message='Error en la fila '.($j+1).' ';
                $message.=($e->getMessage());
                DB::rollBack();
               
            }
        }
        if(!$error){
            DB::commit();
            \Session::flash('flash_message','Propietarios importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los propietarios!.'.$message);
        }
        
    }



    public function setVehiculos($file){

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
        
        DB::beginTransaction();

        foreach ($importData_arr as $importData) {
        $j++;
        
        try {
           
            $placa=trim($importData[0]);
            
            if($placa=="" || empty($placa)){
                $error=true;
                $message="La placa es requerida.";
                //throw new \Exception("La placa es requerida");
                break;
            }

            $existev=Vehiculo::where('placa','=',$placa)->get()->first();

            if($existev){
                  throw new \Exception("La placa #$placa,ya existe en el sistema.");
            }

            $modelo=trim($importData[1]);
            $clase=trim($importData[2]);
            $marca=trim($importData[3]);
            $departamento_id=trim($importData[4]);
            $ciudad_id=trim($importData[5]);
            $propietario=trim($importData[6]);
            $id_clase=NULL;


            
            if($clase){
               $obclase=DB::table('vehiculos_clase')->where('nombre', $clase)->first();
               if($obclase){
                $id_clase=$obclase->id;
               }
            }
            if($marca){
               $obmarca=DB::table('vehiculos_marcas')->where('nombre', $marca)->first();
               if($obmarca){
                $id_marca=$obmarca->id;
               }
            }
            if($propietario){
                $obpropietario=DB::table('propietarios')->where('documento', $propietario)->first();
                if($obpropietario){
                    $propietario_id=$obpropietario->id;
                }
            }else{
                $error=true;
                $message="No se encontro propietario para el vehiculo.";
                //throw new \Exception("La placa es requerida");
                break; 
            }

            $row_vehiculo=new Vehiculo();
            $row_vehiculo->placa=$placa;
            $row_vehiculo->modelo=$modelo;
            $row_vehiculo->id_vehiculo_clase=$id_clase;
            $row_vehiculo->id_vehiculo_marca=$id_marca;
            $row_vehiculo->departamento_id=$departamento_id;
            $row_vehiculo->ciudad_id=$ciudad_id;
            $row_vehiculo->propietario_id=$propietario_id;

            $row_vehiculo->save();
            
            } catch (\Exception $e) {  
                $error=true;
                $message='Error en la fila '.($j+1).' ';
                $message.=($e->getMessage());
                DB::rollBack();
            }
        }
        if(!$error){
            DB::commit();
            \Session::flash('flash_message','Clientes importados exitosamente!.');
        }else{
             DB::rollBack();
             \Session::flash('flash_bad_message','Error al tratar de impotar los vehiculos!. '.$message);
        }
        
    }


}
