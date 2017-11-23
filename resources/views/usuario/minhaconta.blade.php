@extends('template/layout2')

@section('title')
    <title>Minha Conta - MoneyCash</title>
@endsection

@section('content')
    <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="card">
                                <div class="card-header card-header-icon" data-background-color="purple">
                                    <i class="material-icons">perm_identity</i>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">Meus dados                                        
                                    </h4>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group label-floating">
                                                    <label class="control-label">E-mail</label>
                                                    <input type="text" class="form-control" value="admin@admin.com" >
                                                </div>
                                            </div> 
                                        </div>  
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group label-floating">
                                                    <label class="control-label">Nome</label>
                                                    <input type="text" class="form-control" value="Aministrador" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group label-floating">
                                                    <label class="control-label">Senha</label>
                                                    <input type="password" class="form-control" value="*****" disabled>
                                                </div>
                                            </div>
                                        </div>                                      
                                        <div class="text-center">
                                            <button type="button" style="margin: 3px 1px; width: 145px !important;" class="btn btn-primary btn-fill btn-sm button-modal" data-toggle="modal" data-target="#modal-panel">Alterar dados</button>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" style="margin: 3px 1px; width: 145px !important;" class="btn btn-primary btn-fill btn-sm button-modal">Encerar cadastro</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    </div>
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
                    <form id="formUsuario" enctype="multipart/form-data">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-credit-card-multiple"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Meus dados</h4>

                            <div class="footer text-center">
                                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail img-circle">
                                    <img title="A imagem deve ser no formato jpg, png ou gif e ser menor que 500Kb!" src="../img/placeholder.jpg" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                                    <div>
                                        <span class="btn btn-round btn-primary btn-file btn-xs">
                                            <span class="fileinput-new">Adicionar Foto</span>
                                            <span class="fileinput-exists">Alterar</span>
                                            <input type="file" onChange="validationFile(this.form,this)" accept="image/*" id="avatar" name="avatar" />
                                        </span>
                                        <br />
                                        <a class="btn btn-danger btn-round fileinput-exists btn-xs" data-dismiss="fileinput">Remover</a>
                                    </div>
                                    <label id="avatar-error"></label>
                                </div>
                            </div>

                            <div class="input-group space-error">
                                <span class="input-group-addon">
                                    <i class="material-icons">face</i>
                                </span>
                                <input type="text" title="O nome deve conter até 20 caracteres" class="form-control" name="nome" id="nome" placeholder="Nome" required="true">
                            </div>
                        
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                    <input type="password" placeholder="Senha" class="form-control" name="senha" id="senha" required="true" />
                                    <label id="senha-error"></label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">lock</i>
                                    </span>
                                    <input type="password" placeholder="Confirma Senha" class="form-control" name="senha2" id="senha2" required="true" equalTo="#senha" />
                                    <label id="senha2-error"></label>
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

    

@endsection

@section('scripts')
    <script type="text/javascript">  

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
      

        $(document).ready(function() {
            

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
            $( "#formUsuario" ).submit(function( e ) {
                if ($("#formUsuario" ).valid()) {
                    var formData = new FormData($("#formUsuario")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('alterar.dados')}}',
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