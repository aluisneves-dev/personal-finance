@extends('template')
@section('content')
<div class="containerLogin">
    <div class="container">
        <div class="loginContainer">
            <h1><i class="fas fa-lock"></i></h1>
            <h2>{{ env('APP_NAME') }}</h2>
            <h3>Acesso Restrito</h3>
            <form class="formLogin" method="post" action="/login">
                @csrf
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" name="username" id="username" placeholder="Digite seu usuário" autocomplete="off" maxlength="30">
                    <span class="error-text username_error"></span>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" autocomplete="current-password">
                    <span class="error-text password_error"></span>
                </div>
                <div class="form-group">
                    <div id="recaptcha" class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                    <span class="error-text g-recaptcha-response_error"></span>
                </div>            
                <button class="btnLogin" type="submit"><i class="fas fa-sign-in-alt"></i> &nbsp; Acessar</button>
            </form>
            <div id="login-error" class="error-message"></div>
        </div>
    </div>
</div>
@endsection