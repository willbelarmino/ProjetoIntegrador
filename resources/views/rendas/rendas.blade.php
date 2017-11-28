@extends('template/layout2')

@section('title')
    <title>Rendas - MoneyCash</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="mdi mdi-square-inc-cash"></i>
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
                                            <th>Data de recebimento </th>
                                            <th>Valor </th>
                                            <th>Conta </th>
                                            <th class="disabled-sorting text-right"></th>
                                        </tr>
                                        </thead>

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

    <!-- MODAL FORM -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-panel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="card">
                    <form id="formRenda" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-square-inc-cash"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Renda</h4>

                            <div class="form-group label-floating">
                                <label class="control-label">Nome
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="nome" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de recebimento
                                    <star>*</star>
                                </label>
                                <input class="form-control datepicker" id="recebimento" name="recebimento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Valor
                                    <star>*</star>
                                </label>
                                <input class="form-control money-format" id="valor" name="valor" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Conta
                                    <star>*</star>
                                </label>
                                <select id="conta" name="conta"  class="selectpicker" data-style="select-with-transition" title="Selecionar" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="togglebutton">
                                <label style="color: #AAAAAA;">
                                    <input type="checkbox" id="check-fixa" name="fixa"> Renda fixa
                                </label>
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
                    <form id="formRenda-edit" enctype="multipart/form-data" style="display:none;">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-square-inc-cash"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Renda</h4>
                            <input id="id-edit" name="id" type="hidden"/>
                            <div class="form-group label-floating">
                                <label class="control-label">Nome
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="nome-edit" name="nome" type="text" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Data de recebimento
                                    <star>*</star>
                                </label>
                                <input class="form-control datepicker" id="recebimento-edit" name="recebimento" value="{{date('d/m/Y')}}" required="true" />
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Valor
                                    <star>*</star>
                                </label>
                                <input class="form-control money-format" id="valor-edit" name="valor" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>


                            <div class="form-group label-floating">
                                <label class="control-label">Conta
                                    <star>*</star>
                                </label>
                                <select id="conta-edit" name="conta" class="selectpicker" title="Selecionar" data-style="select-with-transition" data-size="7">
                                    @foreach ($contas as $conta)
                                        <option value="{{$conta->id}}">{{$conta->nome}}</option>
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

    <!-- MODAL CANCEL -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-cancel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="card">
                    <form id="formCancel" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-square-inc-cash"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Cancelar renda fixa</h4>

                            <p>O cancelamento da renda não pode ser revertido. Deseja cancelar renda fixa?</p>

                            <input id="id-cancel" name="id-cancel" type="hidden" />

                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal">Sim</button>
                            </div>
                            <div class="text-center">
                                <button type="button" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal" data-dismiss="modal">Não</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /MODAL CANCEL -->

    <!-- MODAL VIEW -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-panel-view">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <form class="form-horizontal">
                        <div class="card-header card-header-text" data-background-color="purple">
                            <h4 class="card-title" id="view-renda-nome">Stock Center</h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Valor</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-renda-valor">R$ 187,45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Data de recebimento</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-renda-recebimento">20/09/2017</p>
                                    </div>
                                </div>
                            </div>                            
                            <div class="row">
                                <label class="col-sm-3 label-on-left">Conta</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <p class="form-control-static" id="view-renda-conta">Alimentação</p>
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
            if ($("#conta").val()=="" || $("#conta").val()=="Selecione") {
                messages.push("Favor, informar conta");
            } 
            if ($("#valor").val()=="" || $("#valor").val()=="R$ 0,00") {
                messages.push("Favor, informar valor");
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

        function validacaoExtraFormEdit() {
            var messages = new Array();
            if ($("#conta-edit").val()=="" || $("#conta-edit").val()=="Selecione") {
                messages.push("Favor, informar conta");
            } 
            if ($("#valor-edit").val()=="" || $("#valor-edit").val()=="R$ 0,00") {
                messages.push("Favor, informar valor");
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

        function cancelar(id) {
            $("#id-cancel").val(id);
            $("#modal-cancel").modal("toggle");
        }

        function visualizar(nome,valor,dt_recebimento,conta) {
            $("#view-renda-nome").html(nome);
            $("#view-renda-valor").html(valor);
            $("#view-renda-conta").html(conta);
            $("#view-renda-recebimento").html(dt_recebimento);            
            $("#modal-panel-view").modal("toggle");
        }

        function deletar(id, isFixa) {
            $.ajax({
                type: "POST",
                url: '{{route('deletar.renda')}}',
                data: { id : id, isFixa : isFixa  },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    setTimeout(function(){ $("#loading").modal('toggle'); }, 500);
                },
                success: function (data) {
                    setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                    if (data.status == "success") {
                        setTimeout(function(){ carregaDataTables(); }, 2000);
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



        function alterar(id,nome,valor,recebimento,conta,nomeConta) {
            $("#formRenda-edit").css("display","block");
            $("#formRenda").css("display","none");
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
            }

            $("#conta-edit").val(conta);
            var selectContaEdit = $(".btn.dropdown-toggle.select-with-transition")[1];
            $(selectContaEdit).removeClass("bs-placeholder");
            $(selectContaEdit).attr('title',nomeConta);
            var textContaSelect = $('.filter-option.pull-left')[1];
            $(textContaSelect).text(nomeConta);            
            $("#recebimento-edit").val(recebimento);
            $("#modal-panel").modal("toggle");
        }

        function loadTable() {
            $('#datatables').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                    $('td:eq(4)', nRow).addClass('td-actions text-right');
                    $('td:eq(4)', nRow).css("width","200px");
                    return nRow;
                },
                ajax: {
                    url: '{{ route('popula.rendas') }}',
                    cache: false,
                    dataSrc: function ( json ) {
                        if (json.data!='error') {
                            return json.data;
                        } else {
                            window.location.href = "{{url('page.error')}}";
                        }
                    },
                },
                responsive: true,
                destroy: true,
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
        }

        function carregaDataTables() {
            $('#datatables').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                    $('td:eq(4)', nRow).addClass('td-actions text-right');
                    $('td:eq(4)', nRow).css("width","200px");
                    return nRow;
                },
                ajax: {
                    url: '{{ route('popula.rendas') }}',
                    cache: false,
                    dataSrc: function ( json ) {
                        if (json.data!='error') {
                            return json.data;
                        } else {
                            console.log("ERRO: "+json.message);
                            //window.location.href = "{{url('page.error')}}";
                        }
                    },
                },
                responsive: true,
                destroy: true,
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
        }

        $(document).ready(function() {

            carregaDataTables();

            /* Limpar formulário */
            $("#modal-panel").on("hide.bs.modal", function () {
                $("#formRenda-edit").css("display","none");
                $("#formRenda").css("display","block");               
                $("#conta").val($("#conta option:first").val());               
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
                        recebimento: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        valor: {
                            required: "Campo de preenchimento obrigatório."
                        },
                        conta: {
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
            $( "#formRenda" ).submit(function( e ) {
                if ($("#formRenda" ).valid()) {
                    var formData = new FormData($("#formRenda")[0]);
                    if ($("#check-fixa").is(':checked')==false) {
                        formData.append("isFixa","false");
                    }  else {
                        formData.append("isFixa","true");
                    }
                    if (validacaoExtraForm()) {
                        $.ajax({
                            type: "POST",
                            url: '{{route('criar.renda')}}',
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
                                    setTimeout(function(){ carregaDataTables(); }, 2000);
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

            $( "#formRenda-edit" ).submit(function( e ) {
                if ($("#formRenda-edit" ).valid()) {
                    if (validacaoExtraFormEdit()) {                        

                        var formData = new FormData($("#formRenda-edit")[0]);
                        $.ajax({
                            type: "POST",
                            url: '{{route('alterar.renda')}}',
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
                                    setTimeout(function(){ carregaDataTables(); }, 2000);
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



            $( "#formCancel" ).submit(function( e ) {
                    var formData = new FormData($("#formCancel")[0]);

                        $.ajax({
                            type: "POST",
                            url: '{{route('cancelar.renda')}}',
                            data: formData,
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            cache: false,
                            beforeSend: function () {
                                $("#modal-cancel").modal('toggle');
                                setTimeout(function(){ $("#loading").modal('toggle'); }, 500);

                            },
                            success: function (data) {
                                setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                                if (data.status == "success") {
                                    setTimeout(function(){ carregaDataTables(); }, 2000);
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

                e.preventDefault(); // avoid to execute the actual submit of the form.
            });


        })

    </script>
@endsection