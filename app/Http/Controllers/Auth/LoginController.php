<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Usuari;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function authenticate(Request $request)
    {
        $validator = \Validator::make(request()->all(), [
        'email' => 'required',
        'password' => 'required'
        ]);

        if ($validator->fails()) {
            //mostrar errores, o en json o lo que necesites
            return redirect()->back()->withErrors($validator->errors());

        }
      
        $userData = Usuari::where('USUARI_Correo___b',$request->get('email'))->first();
                   // dd($userData);

        if ($userData && \Hash::check($request->get('password'), $userData->password))
        {
            
            auth()->loginUsingId($userData->id);
            //log que necesites
            return redirect()->route('home');

        }else{

            \Session::flash('flash_message_error','Usuario o contraseña invalida!.');

            return redirect()->back();

        }
    }
}
