@extends('template/layout')

@section('link')
    <a href="{{route('registro')}}">
        <i class="material-icons">person_add</i> Registrar
    </a>
@endsection


@section('content')
    <div class="full-page login-page" filter-color="black" data-image="../img/gestao-financeira.jpg">
        <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                        <form method="#" action="#">
                            <div class="card card-login card-hidden">
                                <div class="card-header text-center" data-background-color="purple">
                                    <h4 class="card-title"> <img style="width:150px;height100px;" src="../img/login.png"/></h4>
                                </div>
                                <div class="card-content">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">email</i>
                                            </span>
                                        <div class="form-group label-floating">
                                            <label class="control-label">E-mail</label>
                                            <input type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Senha</label>
                                            <input type="password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="footer text-center">
                                    <button type="submit" class="btn btn-primary btn-simple btn-wd btn-lg">Entrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('template/include/footer')
    </div>
@endsection
