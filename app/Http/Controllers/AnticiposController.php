<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anticipos;
use App\Models\Propietarios;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AnticiposController extends Controller
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
    public function index()
    {   
        $anticipos=$this->getRepository();
        return view('anticipos.index')->with(['anticipos'=>$anticipos]);
    }
   
    public function new()
    { 
       
        return view('anticipos.new');
    }

    

     public function edit($id)
    {   
        $anticipo=Anticipos::find($id);
        return view('anticipos.edit')->with(['anticipo'=>$anticipo]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $anticipo=new Anticipos();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $anticipo=Anticipos::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'conductor_id' => 'required',
                'valor' => 'required',
            ]);   

          
        }else{

           $v = Validator::make($request->all(), [
                'conductor_id' => 'required',
                'valor' => 'required',
            ]);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

           
         if($is_new){

            $anticipo->create($request->all());
            \Session::flash('flash_message','Anticipo agregado exitosamente!.');

             return redirect()->route('anticipos');

         }else{

            $anticipo->update($request->all());

            \Session::flash('flash_message','Anticipo actualizado exitosamente!.');

             return redirect()->route('anticipos');

         }


    }
   
    public function update()
    { 
       
    }
     public function delete()
    { 
       
    }
    private function getRepository(){
        return Anticipos::paginate(25);
    }
}
