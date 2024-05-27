<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\User;
use App\Mail\newMail;
use App\Models\Pessoafisica;
use Illuminate\Http\Request;
use App\Rules\GoogleRecaptcha;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function create() {
        return view('login.index');
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'username' => ['required', 'string', 'email'],
            'password' => ['required', 'string','min:6'],
            'g-recaptcha-response' => ['required', new GoogleRecaptcha],
        ],
        [
            'username.required' => '* Usuário é obrigatório',
            'username.string' => '* Usuário não aceita caracteres estranhos',
            'username.email' => '* Usuário no formato inválido',
            'password.required' => '* Senha é obrigatória',
            'password.string' => '* Senha não aceita caracteres estranhos',
            'password.min' => '* São necessários pelo menos 6 caracteres',
            'g-recaptcha-response.required' => '* reCAPTCHA requerido'
        ]);

        if($validator->fails()){
            return response()->json(['status'=> 0, 'error'=>$validator->errors()->toArray()]);
        }else{
            $data = User::query()->where('email','=', $request->username)->first();
            if(!isset($data)){
                return response()->json(['status' => 'error', 'comment' => 'Acesso Negado: usuário não cadastrado']);
            }
            if(isset($data)){
                if (!Auth::attempt(['email' => $data->email, 'password' => $request->password])) {
                    return response()->json(['status' => 'error', 'comment' => 'Acesso Negado: senha incorreta']);
                }
                return response()->json(['status' => 'success', 'comment' => ' Olá '.$data->name.', seja bem vindo']);
            }
        }
    }

}
