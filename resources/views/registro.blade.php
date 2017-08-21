@extends('template/layout')

@section('link')
    <a href="{{route('login')}}">
        <i class="material-icons">lock</i> Login
    </a>
@endsection


@section('content')
    <div class="full-page register-page" filter-color="black" data-image="../img/register.jpeg">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="card card-signup">
                        <h2 class="card-title text-center">Criar cadastro</h2>
                        <div class="row">
                            <div class="col-md-5 col-md-offset-1">
                                <div class="card-content">
                                    <div class="info info-horizontal">
                                        <div class="icon icon-rose">
                                            <i class="material-icons">assignment</i>
                                        </div>
                                        <div class="description">
                                            <h4 class="info-title">Planejamento Financeiro</h4>
                                            <p class="description">
                                                Você pode planejar suas finanças através de metas de gastos por categoria, acompanhando a evolução das despesas durante o mês.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="info info-horizontal">
                                        <div class="icon icon-primary">
                                            <i class="material-icons">insert_chart</i>
                                        </div>
                                        <div class="description">
                                            <h4 class="info-title">Saiba para onde vai seu dinheiro</h4>
                                            <p class="description">
                                                Mais completo que planilhas financeiras, com o MoneyCash você poderá analisar seus dados com gráficos e relatórios.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="info info-horizontal">
                                        <div class="icon icon-info">
                                            <i class="material-icons">notifications</i>
                                        </div>
                                        <div class="description">
                                            <h4 class="info-title">Não esqueça seus prazos</h4>
                                            <p class="description">
                                                Com MoneyCash você receberá lembretes e notificações no sistema de suas despesas a vencer.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <form id ="loginForm" class="form" enctype="multipart/form-data" >
                                    <div class="card-content">

                                        <div class="footer text-center">
                                            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail img-circle">
                                                    <img src="../img/placeholder.jpg" alt="...">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                                                <div>
                                                        <span class="btn btn-round btn-primary btn-file btn-xs">
                                                            <span class="fileinput-new">Adicionar Foto</span>
                                                            <span class="fileinput-exists">Alterar</span>
                                                            <input type="file" id="avatar" name="avatar" />
                                                        </span>
                                                    <br />
                                                    <a class="btn btn-danger btn-round fileinput-exists btn-xs" data-dismiss="fileinput">Remover</a>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif



                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">face</i>
                                                </span>
                                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" required="true">
                                        </div>
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>
                                            <input type="text" class="form-control" email="true" required="true" name="email" id="email" placeholder="E-mail">
                                        </div>
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock_outline</i>
                                                </span>
                                            <input type="password" placeholder="Senha" class="form-control" name="senha" id="senha" required="true" />
                                        </div>
                                        <!-- If you want to add a checkbox to this form, uncomment this code -->
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="optionsCheckboxes" checked> <span style="font-size:10px">Eu aceito os</span>
                                                <a href="#something" style="font-size:10px">Termos de Uso e Política de Privacidade</a>.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        <button  type="submit" class="btn btn-primary btn-round">Começar agora</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('template/include/footer')
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#loginForm" ).validate({
                errorPlacement: function(error, element) {
                    $(element).parent('div').addClass('has-error');
                    error.insertAfter(element);

                }
            });

            $( "#loginForm" ).submit(function( e ) {
                var formData = new FormData($("#loginForm")[0]);
                $.ajax({
                    type: "POST",
                    url: '{{route('criar.cadastro')}}',
                    data: formData,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend : function() {

                    },
                    success: function (data) {
                        if (data.status=="success") {
                            showSucessNotification(data.message);
                        } else if (data.status=="error-form") {
                            showErrorNotification(data.message);
                        }
                    },
                    error: function (request, status, error) {
                        showErrorNotification(error)
                    }
                });
                e.preventDefault(); // avoid to execute the actual submit of the form.
            });
        });
    </script>
@endsection