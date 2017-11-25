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
                        <i class="mdi mdi-cards-outline"></i>
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
                                            <th>Nome</th>
                                            <th>Limite</th>
                                            <th>Dia de fechamento</th>
                                            <th>Dia de vencimento</th>
                                            <th class="disabled-sorting text-right"></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($cartoes as $cartao)
                                            <tr>
                                                <td>{{ $cartao->conta->nome }}</td>

                                                <td>{{ 'R$ '.number_format($cartao->limite, 2, ',', '.') }}</td>

                                                <td> {{ $cartao->dt_fechamento }} </td>

                                                <td> {{ $cartao->dt_vencimento }} </td>

                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" class="btn btn-info"
                                                            onclick="visualizar(
                                                                '{{$cartao->conta->nome}}',
                                                                '{{ 'R$ '.number_format($cartao->limite, 2, ',', '.')}}',
                                                                '{{ $cartao->dt_fechamento }}',
                                                                '{{ $cartao->dt_vencimento }}'
                                                            );">
                                                        <i class="material-icons">assignment</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-success"
                                                            onclick="alterar(
                                                                    '{{ $cartao->id }}',
                                                                    '{{ 'R$ '.number_format($cartao->limite, 2, ',', '.')}}',
                                                                    '{{ $cartao->dt_fechamento }}',
                                                                    '{{ $cartao->dt_vencimento }}'
                                                            );">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-danger"
                                                            onclick="deletar(
                                                                '{{$cartao->id}}',
                                                                '{{$cartao->conta->id}}',
                                                                '{{$cartao->cartao_independente}}'
                                                            );">
                                                        <i class="material-icons">close</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <button type="button"  class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-panel"  style="float:right">
                                        <i class="material-icons">add</i> ADICIONAR
                                    </button>
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
                            <i class="mdi mdi-cards-outline"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Cartão</h4>

                            <div class="togglebutton">
                                <label style="color: #AAAAAA;">
                                    <input type="checkbox" id="check-tipo"  name="tipo"> Cartão independente
                                </label>
                            </div>

                            <div class="form-group label-floating" id="nome-form" style="display:none;">
                                <label class="control-label">Nome</label>
                                <input class="form-control" id="nome" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating" id="conta-form">
                                <label class="control-label">Conta</label>
                                <select id="conta" name="conta"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Dia de fechamento</label>
                                <input type="number" min="1" max="30" class="form-control" id="fechamento" name="fechamento" value="8" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Dia de vencimento</label>
                                <input type="number" min="1" max="30" class="form-control" id="vencimento" name="vencimento" value="15" required="true" />
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
                    <form id="formCartao-edit" enctype="multipart/form-data" style="display:none;">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-cards-outline"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Cartão</h4>
                            <input id="id-edit" name="id" type="hidden"/>
                            
                            <div class="form-group label-floating">
                                <label class="control-label">Dia de fechamento</label>
                                <input type="number" min="1" max="30" class="form-control" id="fechamento-edit" name="fechamento" value="8" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Dia de vencimento</label>
                                <input type="number" min="1" max="30" class="form-control" id="vencimento-edit" name="vencimento" value="15" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Limite</label>
                                <input class="form-control money-format" id="limite-edit" name="limite" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
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
                                <i class="mdi mdi-cards-outline"></i>
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Nome</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-cartao-nome">R$ 187,45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Limite</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-cartao-limite">R$ 187,45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Data de fechamento</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-cartao-fechamento">20/09/2017</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Data de vencimento</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-cartao-vencimento">20/09/2017</p>
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

        function limpaSelect() {
            var selects = $(".btn.dropdown-toggle.select-with-transition");
            $(selects).each (function(){
                $(this).addClass("bs-placeholder");
                $(this).attr('title','Selecione')
            });
            var textSelect = $('.filter-option.pull-left');
            $(textSelect).each (function(){
                $(this).text('Selecione')
            });
            $("#nome").val("")
            $("#nome-form").removeClass("valid");
            $("#nome-form").addClass("label-floating")
            $("#nome-form").addClass("is-empty")

        }

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

        function visualizar(nome,limite,fechamento,vencimento) {
            $("#view-cartao-nome").html(nome);
            $("#view-cartao-limite").html(limite);
            $("#view-cartao-fechamento").html(fechamento);
            $("#view-cartao-vencimento").html(vencimento);
            $("#modal-panel-view").modal("toggle");
        }

        function deletar(id,conta,independente) {
            $.ajax({
                type: "POST",
                url: '{{route('deletar.cartao')}}',
                data: { id : id, conta : conta, independente: independente },
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

        function alterar(id,limite,fechamento,vencimento) {
            $("#formCartao-edit").css("display","block");
            $("#formCartao").css("display","none");
            $("#id-edit").val(id);
            $("#limite-edit").val(limite);
            $("#fechamento-edit").val(fechamento);
            $("#vencimento-edit").val(vencimento);
            var inputLimite = $("#limite-edit").parent()[0];
            $(inputLimite).removeClass('is-empty');
            $(inputLimite).addClass('label-floating');
            $("#modal-panel").modal("toggle");
        }

        function loadTable() {
            $('.content-table-view').load("{{route('cartoes')}} .content-table-view2", function() {
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

            /* Habilitar input do nome*/
            $( ".toggle" ).click(function() {
                setTimeout(function(){
                    if ($("#check-tipo").is(':checked')==true) {
                        $("#nome-form").css( "display", "block" );
                        $("#conta-form").css( "display", "none" );
                        limpaSelect();
                    }else {
                        $("#nome-form").css( "display", "none" );
                        $("#conta-form").css( "display", "block" );
                        limpaSelect();
                    }


                }, 300);
            });

            
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
            $( "#formCartao" ).submit(function( e ) {
                if ($("#formCartao" ).valid()) {
                    var formData = new FormData($("#formCartao")[0]);
                    if ($("#check-tipo").is(':checked')==true) {
                        formData.append("independente", "true");
                    } else {
                        formData.append("independente", "false");
                    }
                    $.ajax({
                        type: "POST",
                        url: '{{route('criar.cartao')}}',
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

            $( "#formCartao-edit" ).submit(function( e ) {
                if ($("#formCartao-edit" ).valid()) {
                    var formData = new FormData($("#formCartao-edit")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('alterar.cartao')}}',
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