@extends('template/layout')

@section('title')
    <title>Entrar - MoneyCash</title>
@endsection

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
                        <form id ="loginForm" class="form">
                            <div class="card card-login card-hidden">
                                <div class="card-header text-center" data-background-color="purple">
                                    <h4 class="card-title"> <img style="width:150px;height:80px;" src="../img/login.png"/></h4>
                                </div>
                                <div class="card-content">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">email</i>
                                            </span>
                                        <div class="form-group label-floating">
                                            <label class="control-label">E-mail</label>
                                            <input type="email" name="email" id="email" email="true" required="true" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Senha</label>
                                            <input type="password" name="senha" id="senha" required="true" class="form-control">
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

    <!-- Loading Modal -->
    <div style="margin-top: 10%;" class="modal fade" data-backdrop="static" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLoading">
        <div class="modal-dialog">
            <div class="col-md-10 col-md-offset-5">
                <img src="../img/loading.gif" alt="..." style="width: 70px; height: 40px;">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function(){

            /* Regras de validação do formulário*/
            $("#loginForm" ).validate({
                messages: {
                    email: {
                        required: "Campo de preenchimento obrigatório.",
                        email: "Insira um e-mail válido."
                    },
                    senha: {
                        required: "Campo de preenchimento obrigatório."
                    }
                },
                errorPlacement: function(error, element) {
                    $(element).parent('div').addClass('has-error');
                    error.insertAfter(element);
                }
            });

            /* Submita o formualário via Ajax*/
            $( "#loginForm" ).submit(function( e ) {
                if ($("#loginForm" ).valid()) {
                    var formData = new FormData($("#loginForm")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('autenticar.usuario')}}',
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $("#loading").modal('toggle');
                        },
                        success: function (data) {
                            if (data.status == "success") {
                                window.location.href = "{{route('home')}}";
                            } else {
                                setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                                setTimeout(function(){ showErrorNotification(data.message); }, 2500);
                            }
                        },
                        error: function (request, status, error) {
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                            setTimeout(function(){ showErrorNotification(error); }, 2500);
                        }
                    });
                }
                e.preventDefault(); // avoid to execute the actual submit of the form.
            });
        });
    </script>
@endsection
