@extends('template/layout2')

@section('title')
    <title>Cartões - MoneyCash</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="mdi mdi-credit-card-multiple"></i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">{{$page}}</h4>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                        </div>
                        <div class="material-datatables">
                            <div class="content-table-view">
                                <div class="content-table-view2">
                                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th class="disabled-sorting text-right"></th>
                                            <th>Nome</th>
                                            <th>Limite</th>
                                            <th>Data de fechamento</th>
                                            <th>Data de vencimento</th>
                                            <th class="disabled-sorting text-right"></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($cartoes as $cartao)
                                            <tr>
                                                <td>{{ $cartao->conta->nome }}</td>

                                                <td>{{ 'R$ '.number_format($cartao->limite, 2, ',', '.') }}</td>

                                                <td> {{ date('d/m/Y', strtotime($cartao->dt_fechamento)) }} </td>

                                                <td> {{ date('d/m/Y', strtotime($cartao->dt_vencimento)) }} </td>

                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" class="btn btn-info"
                                                            onclick="visualizar(
                                                                '{{$cartao->conta->nome}}',
                                                                '{{ 'R$ '.number_format($cartao->limite, 2, ',', '.')}}',
                                                                '{{ date('d/m/Y', strtotime($cartao->dt_fechamento)) }}',
                                                                '{{ date('d/m/Y', strtotime($cartao->dt_vencimento)) }}'
                                                            );">
                                                        <i class="material-icons">assignment</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-success"
                                                            onclick="alterar(
                                                                    '{{ 'R$ '.number_format($cartao->limite, 2, ',', '.')}}',
                                                                    '{{ date('d/m/Y', strtotime($cartao->dt_fechamento)) }}',
                                                                    '{{ date('d/m/Y', strtotime($cartao->dt_vencimento)) }}'
                                                            );">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-danger"
                                                            onclick="deletar(
                                                                {{$cartao->id}}
                                                            );">
                                                        <i class="material-icons">close</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end content-->
                </div>
                <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
        </div>
        <!-- end row -->
    </div>
@endsection

@section('modal')

    <!-- Loading Modal -->
    <div style="margin-top: 10%;" class="modal fade" data-backdrop="static" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLoading">
        <div class="modal-dialog">
            <div class="col-md-10 col-md-offset-5">
                <img src="../img/loading.gif" alt="..." style="width: 70px; height: 40px;">
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-panel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="card">
                    <form id="formCartao" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card-multiple"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Cartão</h4>

                            <div class="togglebutton">
                                <label style="color: #AAAAAA;">
                                    <input type="checkbox" id="check-tipo" name="tipo"> Cartão independente
                                </label>
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Nome</label>
                                <input class="form-control" id="nome" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Conta</label>
                                <select id="categoria" name="categoria"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de fechamento</label>
                                <input class="form-control datepicker" id="fechamento" name="fechamento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de vencimento</label>
                                <input class="form-control datepicker" id="vencimento" name="vencimento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Limite</label>
                                <input class="form-control money-format" id="limite" name="limite" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>


                            <div class="text-center">
                                <button type="submit" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal">Salvar</button>
                            </div>
                            <div class="text-center">
                                <button type="button" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- /MODAL -->


    <!-- MODAL VIEW -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-panel-view">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <form class="form-horizontal">
                        <div class="card-header card-header-text" data-background-color="gray">
                            <h4 class="card-title" id="view-conta-image">

                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Nome</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-conta-nome">R$ 187,45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Saldo</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-conta-saldo">R$ 187,45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Tipo de conta</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-conta-tipo">1/4</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Exibir indicador</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-conta-indicador">Alimentação</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Último movimento</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-conta-movimento">20/09/2017</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /MODAL -->
@endsection

@section('scripts')
    <script type="text/javascript">

        function validationFile(form, input) {
            file = $(input).val();
            extensoes_permitidas = new Array(".gif", ".jpg", ".png");
            if (file) {
                //recupera a extensão do arquivo
                extensao = (file.substring(file.lastIndexOf("."))).toLowerCase();
                // verifica se a extensão é permitida
                valid_file = false;
                for (var i = 0; i < extensoes_permitidas.length; i++) {
                    if (extensoes_permitidas[i] == extensao) {
                        valid_file = true;
                        break;
                    }
                }
                if (!valid_file) {
                    // se a extensao for invalida mostra mensagem de erro
                    $(input).val("");
                    $("#avatar-error").html("Formato inválido.");
                }else{
                    // verifica tamanho da imagem
                    var arquivo = input.files[0];
                    if (arquivo.size>500999) {
                        $(input).val("");
                        $("#avatar-error").html("Tamanho da imagem inválido.");
                    } else {
                        $("#avatar-error").html("");
                        return true;
                    }
                }
            }
            return false;
        }

        function visualizar(imagem,nome,saldo,tipo,indicador,movimento) {
            $("#view-conta-image").html('<img  style="width: 50px; height: 40px" src="'+imagem+'" alt="...">');
            $("#view-conta-nome").html(nome);
            $("#view-conta-saldo").html(saldo);
            $("#view-conta-tipo").html(tipo);
            $("#view-conta-indicador").html(indicador);
            $("#view-conta-movimento").html(movimento);
            $("#modal-panel-view").modal("toggle");
        }

        function deletar(id) {
            $.ajax({
                type: "POST",
                url: '{{route('deletar.conta')}}',
                data: { id : id },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    setTimeout(function(){ $("#loading").modal('toggle'); }, 500);
                },
                success: function (data) {
                    setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                    if (data.status == "success") {
                        setTimeout(function(){ loadTable(); }, 2000);
                        setTimeout(function(){ showSucessNotification(data.message); }, 2500);
                    } else {
                        setTimeout(function(){ showErrorNotification(data.message); }, 2500);
                    }
                },
                error: function (request, status, error) {
                    setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                    setTimeout(function(){ showErrorNotification(error); }, 2500);
                }
            });
        }

        '{{$conta->id}}',
            '{{$conta->nome}}',
            '{{$conta->tipo}}',
            '{{$conta->exibir_indicador}}'


        function alterar(id,nome,tipo,indicador,imagem) {
            $("#formConta-edit").css("display","block");
            $("#formConta").css("display","none");
            $("#id-edit").val(id);
            $("#imagem-edit").val(imagem);

            $("#nome-edit").val(nome);
            var inputNome = $("#nome-edit").parent()[0];
            $(inputNome).removeClass('is-empty');
            $(inputNome).addClass('label-floating');

            var nomeTipo = 'Outros';
            if(tipo=='C') {
                nomeTipo = 'Corrente';
            } else if (tipo=='P') {
                nomeTipo = 'Poupança';
            }

            $("#tipo-edit").val(tipo);
            var selectTipoEdit = $(".btn.dropdown-toggle.select-with-transition")[1];
            $(selectTipoEdit).removeClass("bs-placeholder");
            $(selectTipoEdit).attr('title',nomeTipo);
            var textTipoSelect = $('.filter-option.pull-left')[1];
            $(textTipoSelect).text(nomeTipo);

            if (indicador!=null && indicador=='S') {
                $("#check-indicador-edit").prop("checked", true);
            } else if (indicador!=null && indicador=='N') {
                $("#check-indicador-edit").prop("checked", false);
            }

            $("#modal-panel").modal("toggle");
        }

        function loadTable() {
            $('.content-table-view').load("{{route('contas')}} .content-table-view2", function() {
                $('#datatables').DataTable({
                    "pagingType": "full_numbers",
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Filtrar",
                        lengthMenu: "Exibindo _MENU_ registros por página",
                        zeroRecords: "Nenhum registro encontrado",
                        info: "Visualizando página _PAGE_ de _PAGES_",
                        infoEmpty: "Nenhum registro encontrado",
                        infoFiltered: "(filtered from _MAX_ total records)",
                        paginate: {
                            "first":      "Primeiro",
                            "last":       "Último",
                            "next":       "Próximo",
                            "previous":   "Anterior"
                        }
                    }
                });
                $(".pagination").prepend('<li class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-panel">Adicionar</li>');
            });
        }

        $(document).ready(function() {

            
            /* Limpar formulário */
            $("#modal-panel").on("hide.bs.modal", function () {
                $("#formConta-edit").css("display","none");
                $("#formConta").css("display","block");
                $("#remove-image").click();
                var selects = $(".btn.dropdown-toggle.select-with-transition");
                $(selects).each (function(){
                    $(this).addClass("bs-placeholder");
                    $(this).attr('title','Selecione')
                });
                var textSelect = $('.filter-option.pull-left');
                $(textSelect).each (function(){
                    $(this).text('Selecione')
                });
                var form = $("#modal-panel").find("form")
                $(form).each (function(){
                    var formID = $(this).attr("id");
                    $("#"+formID).each (function(){
                        this.reset();
                    });
                });
            });

            /* Regras de validação do formulário*/
            var form = $("#modal-panel").find("form")
            $(form).each (function(){
                $(this).validate({
                    messages: {
                        nome: {
                            required: "Campo de preenchimento obrigatório."
                        }
                    },
                    errorPlacement: function(error, element) {
                        $(element).parent('div').addClass('has-error');
                        error.insertAfter(element);
                    }
                });
            });

            /* Submita o formualário via Ajax*/
            $( "#formConta" ).submit(function( e ) {
                if ($("#formConta" ).valid()) {
                    var formData = new FormData($("#formConta")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('criar.conta')}}',
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $("#modal-panel").modal('toggle');
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 500);

                        },
                        success: function (data) {
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                            if (data.status == "success") {
                                setTimeout(function(){ loadTable(); }, 2000);
                                setTimeout(function(){ showSucessNotification(data.message); }, 2500);
                            } else {
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

            $( "#formConta-edit" ).submit(function( e ) {
                if ($("#formConta-edit" ).valid()) {
                    var formData = new FormData($("#formConta-edit")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('alterar.conta')}}',
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $("#modal-panel").modal('toggle');
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 500);

                        },
                        success: function (data) {
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                            if (data.status == "success") {
                                setTimeout(function(){ loadTable(); }, 2000);
                                setTimeout(function(){ showSucessNotification(data.message); }, 2500);
                            } else {
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