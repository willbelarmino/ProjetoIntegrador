@extends('template/layout2')

@section('title')
    <title>Categorias - MoneyCash</title>
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
                                            <th>Nome</th>
                                            <th>Limite</th>
                                            <th class="disabled-sorting text-right"></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($categorias as $categoria)
                                            <tr>
                                                <td>{{ $categoria->nome }}</td>

                                                @if (empty($categoria->limite))
                                                    <td> -//- </td>
                                                @else
                                                    <td>{{ 'R$ '.number_format($categoria->limite, 2, ',', '.') }}</td>
                                                @endif

                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" class="btn btn-info btn-simple" title="visualizar"
                                                            onclick="visualizar(
                                                                '{{$categoria->id}}',
                                                                '{{$categoria->nome}}',
                                                                '{{ 'R$ '.number_format($categoria->limite, 2, ',', '.')}}'
                                                            );">
                                                        <i class="material-icons">assignment</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-success btn-simple" title="alterar"
                                                            onclick="alterar(
                                                                '{{$categoria->id}}',
                                                                '{{$categoria->nome}}',
                                                                '{{ 'R$ '.number_format($categoria->limite, 2, ',', '.')}}'
                                                            );">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn btn-danger btn-simple" title="deletar" onclick="deletar({{$categoria->id}});">
                                                        <i class='mdi mdi-delete' style='font-size: 17px !important;'></i>
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
                    <form id="formCategoria" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card-multiple"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Categoria</h4>
                            <div class="form-group label-floating">
                                <label class="control-label">Nome
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="nome" name="nome" type="text" required="true" />
                            </div>

                            <div class="checkbox">
                                <label class="label-check">
                                    <input type="checkbox" id="habilitar-limite" name="optionsCheckboxes">
                                    <a style="font-size:12px" class="label-check"  >Inserir limite</a>.
                                </label>
                            </div>



                            <div class="form-group label-floating" id="input-limite" style="display:none;">
                                <label class="control-label">Limite</label>
                                <input class="form-control money-format" disabled id="limite" name="limite" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
                            </div>
                            <div class="category form-category">
                                <star>*</star> Campos obrigatórios
                            </div>
                            <div class="text-center">
                                <button type="submit" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal">Salvar</button>
                            </div>
                            <div class="text-center">
                                <button type="button" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
                    <form id="formCategoria-edit" enctype="multipart/form-data" style="display:none;">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card-multiple"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Categoria</h4>
                            <input id="id-edit" name="id" type="hidden"/>
                            <div class="form-group label-floating">
                                <label class="control-label">Nome
                                    <star>*</star>
                                </label>
                                <input class="form-control" id="nome-edit" name="nome" type="text" required="true" />
                            </div>

                            <div class="checkbox">
                                <label class="label-check">
                                    <input type="checkbox" id="habilitar-limite-edit" name="optionsCheckboxes">
                                    <a style="font-size:12px" class="label-check"  >Inserir limite</a>.
                                </label>
                            </div>

                            <div class="form-group label-floating" id="input-limite-edit" style="display:none;">
                                <label class="control-label">Limite</label>
                                <input class="form-control money-format" disabled id="limite-edit" name="limite" required="true" data-thousands="." data-decimal="," data-prefix="R$ " />
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
    <div class="modal fade" id="modal-panel-view" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="left: 16% !important;">
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title" id="view-categoria-nome"></h4>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <span class="btn btn-primary btn-xs">
                                <p class="form-control-static" id="view-categoria-limite">Limite: R$ 187,45</p>
                            </span>
                        </div>
                        <div class="table-responsive">                            
                            <table id="extratoCategoriatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Data de movimento</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Status</th>                                           
                                    </tr>
                                </thead>                                    
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="button" style="margin: 3px 1px;" class="btn btn-primary btn-fill btn-sm button-modal" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                    <!-- end content-->
                </div>
            </div>
        </div>
    </div>
    <!-- /MODAL VIEW -->

@endsection

@section('scripts')
    <script type="text/javascript">

        function visualizar(id,nome,limite) {
            $("#view-categoria-nome").html(nome);
            $("#view-categoria-limite").html("Limite: "+limite); 
            gerarModalView(id);           
        }

        function gerarModalView(id) {           

            $('#extratoCategoriatables').DataTable({
                ajax: {
                    url: '{{ route('view-extrato-categoria') }}',
                    data: { id : id },
                    cache: true,
                    beforeSend: function () {
                        setTimeout(function(){ $("#loading").modal('toggle'); }, 500);
                    },
                    dataSrc: function ( json ) {
                        setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                        if (json.data!='error') {
                            setTimeout(function(){ $("#modal-panel-view").modal("toggle"); }, 2500);
                            return json.data;
                        } else {
                            setTimeout(function(){ showErrorNotification('Erro ao visualizar categoria. Tente novamente mais tarde.'); }, 2500);
                        }
                    },
                    error: function (request, status, error) {
                        setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                        setTimeout(function(){ showErrorNotification(error); }, 2500);
                    },
                },                              
                bPaginate: true,
                bLengthChange: false,
                bFilter: false,
                bInfo: true,   
                destroy: true,            
                iDisplayLength: 10, 
                searching: false,           
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

        function deletar(id) {
            $.ajax({
                type: "POST",
                url: '{{route('deletar.categoria')}}',
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


        function alterar(id,nome,limite) {
            $("#formCategoria-edit").css("display","block");
            $("#formCategoria").css("display","none");
            $("#id-edit").val(id);

            $("#nome-edit").val(nome);
            var inputNome = $("#nome-edit").parent()[0];
            $(inputNome).removeClass('is-empty');
            $(inputNome).addClass('label-floating');

            if (limite!="R$ 0,00") {
                $("#limite-edit").val(limite);
                var inputLimite = $("#limite-edit").parent()[0];
                $(inputLimite).removeClass('is-empty');
                $(inputLimite).addClass('label-floating');

                $("#habilitar-limite-edit").prop("checked", true);
                $("#limite-edit").removeAttr('disabled');
                $("#input-limite-edit").css("display", "block");
            }

            $("#modal-panel").modal("toggle");
        }

        function loadTable() {
            $('.content-table-view').load("{{route('categorias')}} .content-table-view2", function() {
                $('#datatables').DataTable({
                    "pagingType": "full_numbers",
                    "deferRender": true,
                    "processing": true,
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    responsive: true,
                    iDisplayLength: 10,
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
                    },
                });
            });
        }

        $(document).ready(function() {

            $('#datatables').DataTable({
                "pagingType": "full_numbers",
                "deferRender": true,
                "processing": true,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                responsive: true,
                iDisplayLength: 10,
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
                },
            });

            /* Habilitar input do limite*/
            $( ".check, .checkbox-material, .check, .label-check" ).click(function() {

                setTimeout(function(){
                    if ($("#habilitar-limite").is(':checked')==true) {
                        $("#limite").removeAttr('disabled');
                        $("#input-limite").css( "display", "block" );

                    }else {
                        $("#limite").attr('disabled','disabled');
                        $("#input-limite").css( "display", "none" );
                    }

                    if ($("#habilitar-limite-edit").is(':checked')==true) {
                        $("#limite-edit").removeAttr('disabled');
                        $("#input-limite-edit").css( "display", "block" );

                    }else {
                        $("#limite-edit").attr('disabled','disabled');
                        $("#input-limite-edit").css( "display", "none" );
                    }
                }, 300);

            });

            /* Limpar formulário */
            $("#modal-panel").on("hide.bs.modal", function () {
                $("#formCategoria-edit").css("display","none");
                $("#formCategoria").css("display","block");
                $("#limite").attr('disabled','disabled');
                $("#input-limite").css("display", "none");
                $("#limite-edit").attr('disabled','disabled');
                $("#input-limite-edit").css("display", "none");
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
                        limite: {
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
            $( "#formCategoria" ).submit(function( e ) {
                if ($("#formCategoria" ).valid()) {
                    var formData = new FormData($("#formCategoria")[0]);
                    if ($("#habilitar-limite").is(':checked')==false) {
                        formData.append("hasLimite","false");
                    }  else {
                        formData.append("hasLimite","true");
                    }

                    $.ajax({
                        type: "POST",
                        url: '{{route('criar.categoria')}}',
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

            $( "#formCategoria-edit" ).submit(function( e ) {
                if ($("#formCategoria-edit" ).valid()) {
                    var formData = new FormData($("#formCategoria-edit")[0]);
                    if ($("#habilitar-limite-edit").is(':checked')==false) {
                        formData.append("hasLimite",false);
                    }  else {
                        formData.append("hasLimite",true);
                    }
                    $.ajax({
                        type: "POST",
                        url: '{{route('alterar.categoria')}}',
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