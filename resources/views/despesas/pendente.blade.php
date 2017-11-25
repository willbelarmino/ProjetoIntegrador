@extends('template/layout2')

@section('title')
    <title>Despesas Pendentes - MoneyCash</title>
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
                            <button type="button" rel="tooltip" class="btn btn-primary" title="Pagar">                                   
                                    <i class="material-icons">attach_money</i> Pagar Fatura
                            </button>
                        </div>
                        <div class="material-datatables">
                            <div class="content-table-view">
                                <div class="content-table-view2">
                                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Data de vencimento </th>
                                            <th>Valor </th>
                                            <th>Categoria </th>
                                            <th class="disabled-sorting text-right"></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($parcelas as $parcela)
                                            @php ($credito = null)
                                            @if (!empty($parcela->despesa->id_cartao_credito))
                                                $credito = '{{$parcela->despesa->cartao->conta->nome}}'
                                            @endif
                                            <tr>
                                                <td>{{ $parcela->despesa->nome }}</td>

                                                <td>{{ date('d/m/Y', strtotime($parcela->dt_vencimento)) }}</td>

                                                <td>{{ 'R$ '.number_format($parcela->valor, 2, ',', '.') }}</td>

                                                <td>{{ $parcela->despesa->categoria->nome }}</td>

                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" class="btn btn-primary" title="Pagar"
                                                            onclick="pagar(
                                                                    '{{$parcela->id}}'
                                                                    )">
                                                        <i class="material-icons">attach_money</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-info"
                                                            onclick="visualizar(
                                                                    '{{$parcela->despesa->nome}}',
                                                                    '{{ 'R$ '.number_format($parcela->valor, 2, ',', '.')}}',
                                                                    '{{$parcela->despesa->categoria->nome}}',
                                                                    '{{$parcela->referencia}}',
                                                                    '{{$credito}}'
                                                            )">
                                                        <i class="material-icons">assignment</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-success"
                                                            onclick="alterar(
                                                                    '{{$parcela->id}}',
                                                                    '{{$parcela->despesa->nome}}',
                                                                    '{{ 'R$ '.number_format($parcela->valor, 2, ',', '.')}}',
                                                                    '{{ date('d/m/Y', strtotime($parcela->dt_vencimento)) }}',
                                                                    '{{$parcela->despesa->id_categoria}}',
                                                                    '{{$parcela->despesa->id_cartao_credito}}',
                                                                    '{{$parcela->despesa->parcelas}}',
                                                                    '{{$parcela->despesa->categoria->nome}}',
                                                                    '{{$credito}}'
                                                            );">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-danger" onclick="deletar({{$parcela->despesa->id}});">
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
                                        <button type="button"  class="btn btn-danger btn-xs" onclick="gerarPDF();"  style="float:right">
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

    <!-- MODAL PAGAMENTO -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-pagamento">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="card">
                    <form id="formPagamento" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="material-icons">attach_money</i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Pagamento</h4>

                            <input id="id-pagamento" name="id" type="hidden"/>

                            <div class="form-group label-floating">
                                <label class="control-label">Conta</label>
                                <select id="conta" name="conta"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal">Pagar</button>
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

    <!-- MODAL FORM -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-panel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="card">
                    <form id="formDespesa" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card-multiple"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Despesa Pendente</h4>

                            <div class="form-group label-floating">
                                <label class="control-label">Nome</label>
                                <input class="form-control" id="nome" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de vencimento</label>
                                <input class="form-control datepicker" id="vencimento" name="vencimento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Valor</label>
                                <input class="form-control money-format" id="valor" name="valor" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Parcelas</label>
                                <input class="form-control" id="parcela" name="parcela" required="true" type="number" min="1" max="320" step="1" value ="1"/>
                            </div>


                            <div class="form-group label-floating">
                                <label class="control-label">Categoria</label>
                                <select id="categoria" name="categoria"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="togglebutton">
                                <label style="color: #AAAAAA;">
                                    <input type="checkbox" id="check-credito"> Cartão de crédito
                                </label>
                            </div>

                            <div class="form-group label-floating" id="input-credito" style="display:none;">
                                <select id="credito" name="credito" class="selectpicker" data-style="select-with-transition" title="Selecione cartão" data-size="7">
                                    @foreach ($cartoes as $cartao)
                                        <option value="{{$cartao->id}}">{{$cartao->conta->nome}}</option>
                                    @endforeach
                                </select>
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
                            <i class="mdi mdi-credit-card-multiple"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Despesa Pendente</h4>
                            <input id="id-edit" name="id" type="hidden"/>
                            <div class="form-group label-floating">
                                <label class="control-label">Nome</label>
                                <input class="form-control" id="nome-edit" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de vencimento
                                    <star>*</star>
                                </label>
                                <input class="form-control datepicker" id="vencimento-edit" name="vencimento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Valor</label>
                                <input class="form-control money-format" id="valor-edit" name="valor" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Parcelas</label>
                                <input class="form-control" disabled id="parcela-edit" type="number" min="1" max="320" step="1" value ="1"/>
                            </div>


                            <div class="form-group label-floating">
                                <label class="control-label">Categoria</label>
                                <select id="categoria-edit" name="categoria" class="selectpicker" title="Selecionar" data-style="select-with-transition" data-size="7">
                                    @foreach ($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="togglebutton">
                                <label>
                                    <input type="checkbox" id="check-credito-edit"> Cartão de crédito
                                </label>
                            </div>

                            <div class="form-group label-floating" id="input-credito-edit" style="display:none;">
                                <label class="control-label">Cartão de crédito</label>
                                <select id="credito-edit" name="credito" class="selectpicker" disabled data-style="select-with-transition" title="Selecione" data-size="7">
                                    @foreach ($cartoes as $cartao)
                                        <option value="{{$cartao->id}}">{{$cartao->conta->nome}}</option>
                                    @endforeach
                                </select>
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
                            <h4 class="card-title" id="view-despesa-nome">Stock Center</h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Valor</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-valor">R$ 187,45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Data de vencimento</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-vencimento">20/09/2017</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Parcela</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-parcela">1/4</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Categoria</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-categoria">Alimentação</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="box-credito" style="display:none;">
                                <label class="col-sm-3 label-on-left">Cartão de crédito</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-despesa-credito">Itaú</p>
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

        function gerarPDF() {
            try  {
                window.open('{{ route('generate.relPendente') }}','_blank');     
            } catch(err) {
                setTimeout(function(){ showErrorNotification('Erro ao gerar PDF!'); }, 2500);
            }      
        }

        function visualizar(nome,valor,categoria,parcela,credito) {
            $("#view-despesa-nome").html(nome);
            $("#view-despesa-valor").html(valor);
            $("#view-despesa-categoria").html(categoria);
            $("#view-despesa-parcela").html(parcela);
            if (credito!=null && credito!="") {
                $("#view-despesa-credito").html(credito);
                $("#box-credito").css("display", "block");
            } else {
                $("#box-credito").css("display", "none");
            }
            $("#modal-panel-view").modal("toggle");
        }

        function pagar(id) {
            $("#id-pagamento").val(id);
            $("#modal-pagamento").modal("toggle");
        }

        function deletar(id) {
            $.ajax({
                type: "POST",
                url: '{{route('deletar.pendente')}}',
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


        function alterar(id,nome,valor,vencimento,categoria,credito,parcela,nomeCategoria,nomeCredito) {
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
            $("#vencimento-edit").val(vencimento);

            $("#modal-panel").modal("toggle");
        }

        function loadTable() {
            $('.content-table-view').load("{{route('pendentes')}} .content-table-view2", function() {
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

            /* Habilitar input do limite*/
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
                $("#formDespesa-edit").css("display","none");
                $("#formDespesa").css("display","block");
                $("#credito").attr('disabled','disabled');
                $("#categoria").val($("#categoria option:first").val());
                $("#select-credito").css("display", "none");
                $("#credito-edit").attr('disabled','disabled');
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
            });

            /* Regras de validação do formulário*/
            var form = $("#modal-panel").find("form")
            $(form).each (function(){
                $(this).validate({
                    messages: {
                        nome: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        vencimento: {
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
            $( "#formPagamento" ).submit(function( e ) {
                if ($("#formPagamento" ).valid()) {
                    var formData = new FormData($("#formPagamento")[0]);

                    if ($("#check-credito").is(':checked')==false) {
                        formData.append("isCredito","false");
                    }  else {
                        formData.append("isCredito","true");
                    }    
                        $.ajax({
                            type: "POST",
                            url: '{{route('pagar')}}',
                            data: formData,
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            cache: false,
                            beforeSend: function () {
                                $("#modal-pagamento").modal('toggle');
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


            /* Submita o formualário via Ajax*/
            $( "#formDespesa" ).submit(function( e ) {
                if ($("#formDespesa" ).valid()) {
                    var formData = new FormData($("#formDespesa")[0]);
                    
                    if ($("#check-credito").is(':checked')==false) {
                        formData.append("isCredito","false");
                    }  else {
                        formData.append("isCredito","true");
                    } 
                    if (validacaoExtraForm()) {
                        $.ajax({
                            type: "POST",
                            url: '{{route('criar.pendente')}}',
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
                }
                e.preventDefault(); // avoid to execute the actual submit of the form.
            });

            $( "#formDespesa-edit" ).submit(function( e ) {
                if ($("#formDespesa-edit" ).valid()) {
                    var formData = new FormData($("#formDespesa-edit")[0]);
                    if ($("#check-credito-edit").is(':checked')==false) {
                        formData.append("isCredito","false");
                    }  else {
                        formData.append("isCredito","true");
                    } 
                    $.ajax({
                        type: "POST",
                        url: '{{route('alterar.pendente')}}',
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