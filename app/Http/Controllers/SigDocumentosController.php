<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\SigFiles;

use Config;
use File;

use Illuminate\Support\Facades\Route;


class SigDocumentosController extends Controller
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

    public function index(Request $request,$id=""){   
        
        $items=DB::table('sig_mastercategorias');
        $q=$request->get('q');
        $mastercategoria="";
        $categoria="";
        $slug="";

        if(Route::currentRouteName()=='sigdocumentos.categorias'){
            $slug='categorias';
        }

        if(Route::currentRouteName()=='sigdocumentos.subcategorias'){
            $slug='subcategorias';
        }
        
        if($slug=='categorias'){

            $objmastercategoria=DB::table('sig_mastercategorias')->where('id',$id)->get()->first();
            $mastercategoria=$objmastercategoria->nombre;
            $items=DB::table('sig_categorias')->where('mastercategoriasig_id',$id);
        }
        if($slug=='subcategorias'){

            $objcategoria=DB::table('sig_categorias')->where('id',$id)->get()->first();
            $categoria=$objcategoria->nombre;
            $objmastercategoria=DB::table('sig_mastercategorias')->where('id',$objcategoria->mastercategoriasig_id)->get()->first();
            $mastercategoria=$objmastercategoria->nombre;
            $items=DB::table('sig_subcategorias')->where('categoriasig_id',$id);
            
        }
        $items=$items->orderBy('id','Asc');
        $items=$items->paginate(Config::get('global_settings.paginate'));                           
        $migapan=['master'=>$mastercategoria,'categoria'=>$categoria];
        return view('sig_documentos.index')->with(['items'=>$items,'q'=>$q,'migapan'=>$migapan]);
    }

    public function subcategoriasFiles(Request $request,$id){
        
        $q=$request->get('q');
        $mastercategoria="";
        $categoria="";
        $subcategoria="";
        $slug="";
        $q=$request->get('q');


        $items=DB::table('sig_files')->where('subcategoriasig_id',$id);
        if($q!=""){
            $items->where('nombre', 'LIKE', '%'.$q.'%');
        }
        $objsubcategoria=DB::table('sig_subcategorias')->where('id',$id)->get()->first();
        $objcategoria=DB::table('sig_categorias')->where('id',$objsubcategoria->categoriasig_id)->get()->first();
        $objmastercategoria=DB::table('sig_mastercategorias')->where('id',$objcategoria->mastercategoriasig_id)->get()->first();

        $items=$items->orderBy('id','Asc');
        $items=$items->paginate(Config::get('global_settings.paginate'));        
        $data=[
            'items'=>$items,
            'mastercategoria'=>$objmastercategoria,
            'categoria'=>$objcategoria,
            'subcategoria'=>$objsubcategoria,
            'q'=>$q
        ];

        return view('sig_documentos.files')->with($data);
       
    }

    public function subcategoriasFilesUpload(Request $request){

        $id=$request->get('subcategoria');
        $file = $request->file('file');
        $extension=$file->getClientOriginalExtension();
        $directory = public_path('uploads').'/sig/documentos/files/';
        $newName = sha1(time().time()).".{$extension}";
        $file->move($directory, $newName);

        $sigFile=new SigFiles();
        $sigFile->subcategoriasig_id=$id;
        $sigFile->file='uploads/sig/documentos/files/'.$newName;
        $sigFile->nombre=$file->getClientOriginalName();
        $sigFile->extension=$extension;
        $guardo=$sigFile->save();
        if($guardo){
            return response()->json(['success' => true,200]);
        }

    }

    public function deleteFile(Request $request){
        
        $sigFile=SigFiles::find($request->get('id'));
        $sigFile->delete();
        \Session::flash('flash_message','Registro eliminado exitosamente!.');
        return redirect()->back();
    }
    
}
