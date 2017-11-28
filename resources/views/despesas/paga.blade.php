@extends('template/layout2')

@section('title')
    <title>Despesas Pagas - MoneyCash</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="mdi mdi-credit-card"></i>
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
                                            <th>Data de vencimento </th>
                                            <th>Data de pagamento </th>
                                            <th>Valor </th>
                                            <th>Conta/Cartão </th>
                                            <th class="disabled-sorting text-right"></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($parcelas as $parcela)

                                            <tr>
                                                <td>
                                                    {{ $parcela->parcelaPendente->despesa->nome }}
                                                    @if (!empty($parcela->parcelaPendente->despesa->id_cartao_credito))
                                                        <span class='btn btn-warning btn-xs' style='cursor: default !important;padding: 0px 10px !important;'> Cartão </span>
                                                    @endif
                                                </td>

                                                <td>{{ date('d/m/Y', strtotime($parcela->parcelaPendente->dt_vencimento)) }}</td>

                                                <td>{{ date('d/m/Y', strtotime($parcela->dt_pagamento)) }}</td>

                                                <td>{{ 'R$ '.number_format($parcela->valor, 2, ',', '.') }}</td>

                                                <td>{{ $parcela->conta->nome }}</td>

                                                <td class="td-actions text-right">

                                                    @if(!empty($parcela->comprovante))
                                                        <button type="button" rel="tooltip" class="btn btn-simple"
                                                                onclick="window.open(' {{ route('view.comprovante',$parcela->comprovante) }}');">
                                                            <i class="material-icons">attach_file</i>
                                                        </button>
                                                    @endif

                                                    <button type="button" rel="tooltip" class="btn btn-info btn-simple"                                                    
                                                            onclick="visualizar(
                                                                    '{{$parcela->parcelaPendente->despesa->nome}}',
                                                                    '{{ 'R$ '.number_format($parcela->valor, 2, ',', '.')}}',
                                                                    '{{$parcela->parcelaPendente->despesa->categoria->nome}}',
                                                                    '{{ date('d/m/Y', strtotime($parcela->dt_pagamento)) }}'


                                                            )">
                                                        <i class="material-icons">assignment</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-success btn-simple"
                                                            onclick="alterar(
                                                                    '{{$parcela->id}}',
                                                                    '{{$parcela->parcelaPendente->despesa->nome}}',
                                                                    '{{ 'R$ '.number_format($parcela->valor, 2, ',', '.')}}',
                                                                    '{{ date('d/m/Y', strtotime($parcela->dt_pagamento)) }}'

                                                            );">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-danger btn-simple" onclick="deletar(
                                                            {{$parcela->id}}
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
                                        <button type="button"  class="btn btn-danger btn-xs" onclick="window.open('{{ route('generate.relPaga') }}','_blank');"  style="float:right">
                                            <i class="material-icons">print</i> IMPRIMIR
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

    <!-- MODAL FORM -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-panel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="card">
                    <form id="formDespesa" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Despesa Paga</h4>

                            <div class="form-group label-floating">
                                <label class="control-label">Nome
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="nome" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de pagamento
                                    <star>*</star>
                                </label>
                                <input class="form-control datepicker" id="pagamento" name="pagamento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Valor
                                    <star>*</star>
                                </label>
                                <input class="form-control money-format" id="valor" name="valor" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Parcelas
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="parcela" onChange="temComprovante()" name="parcela" required="true" type="number" min="1" max="320" step="1" value ="1"/>
                            </div>                            

                            <div class="form-group label-floating">
                                <label class="control-label">Categoria
                                    <star>*</star>
                                </label>
                                <select id="categoria" name="categoria"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="input-comprovante">    
                                <label class="control-label">Comprovante</label>
                                <span class="btn btn-round btn-file btn-primary btn-xs" style="margin-left: 13%;" >
                                    <i class="material-icons">attach_file</i>
                                    <input  id="comprovante" name="comprovante" onChange="validationFile(this.form,this)" type="file"  />
                                </span>
                                <div class="text-center">
                                     <div id="file-error"></div>
                                </div>
                            </div>

                            <div class="togglebutton">
                                <label style="color: #AAAAAA;">
                                    <input type="checkbox" id="check-credito" onchange="validaInputConta();"> Cartão de crédito
                                </label>
                            </div>

                            <div class="form-group label-floating" id="input-conta">
                                <label class="control-label">Conta
                                    <star>*</star>
                                </label>
                                <select id="conta" name="conta"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group label-floating" id="input-credito" style="display:none;">
                                <label class="control-label">Cartão de crédito
                                    <star>*</star>
                                </label>
                                <select id="credito" name="credito" class="selectpicker" data-style="select-with-transition" title="Selecione cartão" data-size="7">
                                    @foreach ($cartoes as $cartao)
                                        <option value="{{$cartao->id}}">{{$cartao->conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="category form-category">
                                <star>*</star> Campos obrigatórios
                            </div>
                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal">Salvar</button>
                            </div>
                            <div class="text-center">
                                <button type="button" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
                    <form id="formDespesa-edit" enctype="multipart/form-data" style="display:none;">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Despesa Paga</h4>
                            <input id="id-edit" name="id" type="hidden"/>
                            <div class="form-group label-floating">
                                <label class="control-label">Nome
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="nome-edit" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de pagamento
                                    <star>*</star>
                                </label>
                                <input class="form-control datepicker" id="pagamento-edit" name="pagamento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Valor
                                    <star>*</star>
                                </label>
                                <input class="form-control money-format" id="valor-edit" name="valor" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Parcelas
                                    <star>*</star>
                                </label>
                                <input class="form-control" disabled id="parcela-edit" type="number" min="1" max="320" step="1" value ="1"/>
                            </div>


                            <div class="form-group label-floating" >
                                <label class="control-label">Conta
                                    <star>*</star>
                                </label>
                                <select id="conta-edit" name="conta" class="selectpicker" title="Selecionar" data-style="select-with-transition" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="togglebutton">
                                <label>
                                    <input type="checkbox" id="check-credito-edit" > Cartão de crédito
                                </label>
                            </div>

                            <div class="form-group label-floating" id="input-credito-edit" style="display:none;">
                                <label class="control-label">Cartão de crédito
                                    <star>*</star>
                                </label>
                                <select id="credito-edit" name="credito" class="selectpicker" data-style="select-with-transition" title="Selecione" data-size="7">
                                    @foreach ($cartoes as $cartao)
                                        <option value="{{$cartao->id}}">{{$cartao->conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="category form-category">
                                <star>*</star> Campos obrigatórios
                            </div>
                            <div class="text-center">
                                <button type="submit" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal">Alterar</button>
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
                        <div class="card-header card-header-text" data-background-color="purple">
                            <h4 class="card-title" id="view-despesa-nome"></h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Valor</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-valor"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Data de pagamento</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-pagamento"></p>
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Parcela</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-parcela"></p>
                                    </div>
                                </div>
                            </div>
                        -->
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Categoria</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-categoria"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="box-credito" style="display:none;">
                                <label class="col-sm-3 label-on-left">Cartão de crédito</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-credito"></p>
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

        function validaInputConta() {
            if ($("#check-credito").is(':checked')==true) {   
                $("#input-conta").css("display", "none");
                
                var selects = $(".btn.dropdown-toggle.select-with-transition")[1];
                $(selects).each (function(){
                   $(this).addClass("bs-placeholder");
                   $(this).attr('title','Selecione')
                });
                
                var textSelect = $('.filter-option.pull-left')[1];
                $(textSelect).each (function(){
                    $(this).text('Selecione')
                });

            } else {
                $("#input-conta").css("display", "block");
            }
        }

        function temComprovante() {
            var parcelas = $("#parcela").val();
            if (parcelas!=1) {
                $("#input-comprovante").css("display", "none");
                $("#comprovante").val("");
                $("#file-error").html("");
            } else {
                $("#input-comprovante").css("display", "block");
            }
        }

        function validationFile(form, input) {
            file = $(input).val();
            extensoes_permitidas = new Array(".pdf");
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
                    $("#file-error").html("Formato inválido.");
                }else{
                    // verifica tamanho da imagem
                    var arquivo = input.files[0];
                    if (arquivo.size>500999) {
                        $(input).val("");
                        $("#file-error").html("Tamanho do arquivo inválido.");
                    } else {
                        $("#file-error").html($("#comprovante").val().split("\\").pop());
                        return true;
                    }
                }
            }
            return false;
        }

        function validacaoExtraForm() {
            var messages = new Array();
            if ($("#categoria").val()=="" || $("#categoria").val()=="Selecione") {
                messages.push("Favor, informar categoria");
            } else if ($("#check-credito").is(':checked')==true && ($("#credito").val()=="" || $("#credito").val()=="Selecione")) {
                messages.push("Favor, informar cartão de credito");
            }
            if ($(messages).length > 0) {
                console.log("erros: "+$(messages).length);
                for(i = 0; i < $(messages).length; i++) {
                    showErrorNotification($( messages )[i]);
                }
                messages = [];
                return false;
            }
            return true;
        }

        function visualizar(nome,valor,categoria,pagamento) {
            $("#view-despesa-pagamento").html(pagamento);
            $("#view-despesa-nome").html(nome);
            $("#view-despesa-valor").html(valor);
            $("#view-despesa-categoria").html(categoria);
            //$("#view-despesa-parcela").html(parcela);           
            $("#modal-panel-view").modal("toggle");
        }


        function deletar(id) {
            $.ajax({
                type: "POST",
                url: '{{route('deletar.paga')}}',
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
                        console.log(data.message);
                    } else {
                        console.log(data.message);
                        setTimeout(function(){ showErrorNotification(data.message); }, 2500);
                    }
                },
                error: function (request, status, error) {
                    setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                    setTimeout(function(){ showErrorNotification(error); }, 2500);
                }
            });
        }


        function alterar(id,nome,valor,pagamento,categoria,credito,parcela,nomeCategoria,nomeCredito) {
            $("#formDespesa-edit").css("display","block");
            $("#formDespesa").css("display","none");
            $("#id-edit").val(id);

            $("#nome-edit").val(nome);
            var inputNome = $("#nome-edit").parent()[0];
            $(inputNome).removeClass('is-empty');
            $(inputNome).addClass('label-floating');

            if (valor!="R$ 0,00") {
                $("#valor-edit").val(valor);
                var inputValor = $("#valor-edit").parent()[0];
                $(inputValor).removeClass('is-empty');
                $(inputValor).addClass('label-floating');

                if (credito==null || credito=="") {
                    $("#check-credito-edit").prop("checked", false);
                    $("#credito-edit").attr('disabled','disabled');
                    $("#input-credito-edit").css("display", "none");
                    var selectCreditoEdit = $(".btn.dropdown-toggle.select-with-transition")[3];
                    $(selectCreditoEdit).addClass("bs-placeholder");
                    $(selectCreditoEdit).attr('title','Selecione');
                    var textSelect = $('.filter-option.pull-left')[3];
                    $(textSelect).text('Selecione');
                } else {
                    $("#check-credito-edit").prop("checked", true);
                    $("#credito-edit").removeAttr('disabled');
                    $("#credito-edit").val(credito);
                    var selectCreditoEdit = $(".btn.dropdown-toggle.select-with-transition")[3];
                    $(selectCreditoEdit).removeClass("bs-placeholder");
                    $(selectCreditoEdit).attr('title',nomeCredito);
                    var textCreditoSelect = $('.filter-option.pull-left')[3];
                    $(textCreditoSelect).text(nomeCredito);
                    $("#input-credito-edit").css("display", "block");
                }

            }

            $("#categoria-edit").val(categoria);
            var selectCategoriaEdit = $(".btn.dropdown-toggle.select-with-transition")[2];
            $(selectCategoriaEdit).removeClass("bs-placeholder");
            $(selectCategoriaEdit).attr('title',nomeCategoria);
            var textCategoriaSelect = $('.filter-option.pull-left')[2];
            $(textCategoriaSelect).text(nomeCategoria);
            $("#parcela-edit").val(parcela);
            $("#pagamento-edit").val(pagamento);

            $("#modal-panel").modal("toggle");
        }

        function loadTable() {
            $('.content-table-view').load("{{route('pagas')}} .content-table-view2", function() {
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
            });
        }

        $(document).ready(function() {

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

            /* Habilitar input do credito*/
            $( "#check-credito" ).click(function() {
                setInterval(function(){
                    if ($("#check-credito").is(':checked')==true) {                       
                        $("#input-credito").css( "display", "block" );

                    }else {                        
                        $("#input-credito").css( "display", "none" );
                    }
                }, 300);
            });

            $( "#check-credito-edit" ).click(function() {
                setInterval(function(){
                    if ($("#check-credito-edit").is(':checked')==true) {                       
                        $("#input-credito-edit").css( "display", "block" );

                    }else {                        
                        $("#input-credito-edit").css( "display", "none" );
                    }
                }, 300);
            });

            /* Limpar formulário */
            $("#modal-panel").on("hide.bs.modal", function () {
                $("#input-conta").css("display", "block");
                $("#formDespesa-edit").css("display","none");
                $("#formDespesa").css("display","block");                
                $("#categoria").val($("#categoria option:first").val());
                $("#select-credito").css("display", "none");               
                $("#select-credito-edit").css("display", "none");
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
                $("#file-error").html("");
            });

            /* Regras de validação do formulário*/
            var form = $("#modal-panel").find("form")
            $(form).each (function(){
                $(this).validate({
                    messages: {
                        nome: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        pagamento: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        valor: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        parcela: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        categoria: {
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
            $( "#formDespesa" ).submit(function( e ) {
                if ($("#formDespesa" ).valid()) {
                    var formData = new FormData($("#formDespesa")[0]);
                    if ($("#check-credito").is(':checked')==true) {
                        formData.append("hasCredito","true"); 
                    } else {
                        formData.append("hasCredito","false"); 
                    }
                    if (validacaoExtraForm()) {
                        $.ajax({
                            type: "POST",
                            url: '{{route('criar.paga')}}',
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
                                    console.log(data.message);
                                    setTimeout(function(){ showErrorNotification(data.message); }, 2500);
                                }
                            },
                            error: function (request, status, error) {
                                setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                                setTimeout(function(){ showErrorNotification(error); }, 2500);
                            }
                        });
                    }
                }
                e.preventDefault(); // avoid to execute the actual submit of the form.
            });

            $( "#formDespesa-edit" ).submit(function( e ) {
                if ($("#formDespesa-edit" ).valid()) {
                    var formData = new FormData($("#formDespesa-edit")[0]);
                    if ($("#check-credito-edit").is(':checked')==true) {
                        formData.append("hasCredito","true"); 
                    } else {
                        formData.append("hasCredito","false"); 
                    }
                    $.ajax({
                        type: "POST",
                        url: '{{route('alterar.paga')}}',
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
        })

    </script>
@endsection