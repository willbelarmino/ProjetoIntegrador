<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <title>Despesas Pendentes - MoneyCash</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="../css/material-dashboard.css" rel="stylesheet" />
    <!--  Material Design Dashboard CSS    -->
    <link href="../css/materialdesignicons.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="../css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/css.css" />
</head>
<?php flush(); ?>
<body>
<div class="wrapper">
    <div class="sidebar" data-active-color="purple" data-background-color="black" data-image="../img/sidebar-1.jpg">
        <!--
    Tip 1: You can change the color of active element of the sidebar using: data-active-color="purple | blue | green | orange | red | rose"
    Tip 2: you can also add an image using data-image tag
    Tip 3: you can change the color of the sidebar with data-background-color="white | black"
-->
        <div class="logo">
            <a href="#" class="simple-text">
                <!-- Money Cash --> <img src="../img/login.png" style="width:100px; height:50px;"/>
            </a>
        </div>
        <div class="logo logo-mini">
            <a href="http://www.creative-tim.com" class="simple-text">
                <!-- MC --> <img src="../img/favicon_white.png" style="width:30px; height:30px;"/>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <div class="user">
                <div class="photo">
                    <img src="{{ asset('storage/avatars/'.$usuario->image) }}" />
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                        {{ $usuario->nome }}
                        <b class="caret"></b>
                    </a>
                    <div class="collapse" id="collapseExample">
                        <ul class="nav">
                            <li class="@if ($menuView == 'minhaconta') active @endif">
                                <a href="{{route('minha.conta')}}">Minha conta</a>
                            </li>
                            <li class="@if ($menuView == 'config') active @endif">
                                <a href="#">Configurações</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav">
                <li class="@if ($menuView == 'dashboard') active @endif">
                    <a href="{{route('home')}}">
                        <i class="mdi mdi-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="@if ($menuView == 'rendas') active @endif">
                    <a href="{{route('rendas')}}">
                        <i class="mdi mdi-square-inc-cash"></i>
                        <p>Rendas</p>
                    </a>
                </li>
                <li class="@if ($menuView == 'pendentes') active @endif">
                    <a href="{{route('pendentes')}}">
                        <i class="mdi mdi-credit-card-multiple"></i>
                        <p>Despesas Pendentes</p>
                    </a>
                </li>
                <li class="@if ($menuView == 'pagas') active @endif">
                    <a href="{{route('pagas')}}">
                        <i class="mdi mdi-credit-card"></i>
                        <p>Despesas Pagas</p>
                    </a>
                </li>
                <li class="@if ($menuView == 'contas') active @endif">
                    <a href="{{route('contas')}}">
                        <i class="mdi mdi-account-card-details"></i>
                        <p>Contas</p>
                    </a>
                </li>
                <li class="@if ($menuView == 'cartoes') active @endif">
                    <a href="{{route('cartoes')}}">
                        <i class="mdi mdi-cards-outline"></i>
                        <p>Cartões</p>
                    </a>
                </li>
                <li class="@if ($menuView == 'categorias') active @endif">
                    <a href="{{route('categorias')}}">
                        <i class="mdi mdi-format-line-weight"></i>
                        <p>Categorias</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                        <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                        <i class="material-icons visible-on-sidebar-mini">view_list</i>
                    </button>
                </div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">  </a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">

                        <li>
                            <a href="{{url('sair')}}"  title="Sair">
                                <i class="material-icons">exit_to_app</i>
                                <p class="hidden-lg hidden-md">Profile</p>
                            </a>
                        </li>
                        <li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">

            <div class="row" style="margin-bottom: 20px;">
                <div class="periodo-bar text-center">
                    <span onclick="alteraPeriodo('previous');" class="btn btn-primary btn-sm"> <i class="material-icons">keyboard_arrow_left</i> </span>
                    <span id="nomeMes" data-toggle="modal" data-target="#modal-periodo" class="btn btn-primary btn-fill btn-sm button-modal" style="width: {{$resize}}px !important;"> {{$nomeMes}} </span>
                    <span onclick="alteraPeriodo('next');" class="btn btn-primary btn-sm"> <i class="material-icons">keyboard_arrow_right</i> </span>
                </div>
            </div>

            @yield('content')


        </div>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy;
                    <script>
                        document.write(new Date().getFullYear())
                    </script>
                    <a href="http://www.creative-tim.com">Controle de finanças</a>, desenvolvido por William Albuquerque
                </p>
            </div>
        </footer>

         
    </div>   
</div>

@yield('modal')

<!-- MODAL ALTERA PERIODO FORM -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-periodo">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="card">
                <form id="formPeriodo">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">date_range</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">Período</h4>

                         <div class="form-group label-floating">
                            <label class="control-label">Data inicial</label>
                            <input class="form-control datepicker" id="inicio" name="inicio" value="{{date('01/m/Y')}}" required="true" />
                        </div>

                        <div class="form-group label-floating">
                            <label class="control-label">Data final</label>
                            <input class="form-control datepicker" id="final" name="final" value="{{date('t/m/Y')}}" required="true" />
                        </div>


                        <div class="text-center" style="margin-top: 20px;">
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


</body>
<!--   Core JS Files   -->
<script src="../js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="../js/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/material.min.js" type="text/javascript"></script>
<script src="../js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="../js/jquery.validate.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="../js/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="../js/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../js/bootstrap-notify.js"></script>
<!-- DateTimePicker Plugin JS -->
<script src="../js/pt-br.js"></script>
<!-- DateTimePicker Plugin -->
<script src="../js/bootstrap-datetimepicker.js"></script>
<!-- Select Plugin -->
<script src="../js/jquery.select-bootstrap.js"></script>
<!--  DataTables.net Plugin    -->
<script src="../js/jquery.datatables.js"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="../js/jasny-bootstrap.min.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="../js/material-dashboard.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="../js/demo.js"></script>
<!-- My functions! -->
<script src="../js/functions.js"></script>
<!-- Jquery Mask Money -->
<script src="../js/jquery.maskMoney.js" type="text/javascript"></script>

@yield('scripts')

<script type="text/javascript">
    function setFormValidation(id) {
        $(id).validate({
            errorPlacement: function(error, element) {
                $(element).parent('div').addClass('has-error');
            }
        });
    }

    function alteraPeriodo(id) {
        $.ajax({
            type: "POST",
            url: '{{route('periodo.alteraMes')}}',
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
                    setTimeout(function(){
                        loadTable();
                        $("#nomeMes").html(data.nomeMes);
                        $("#nomeMes").css('cssText', 'width: 130px !important');
                    }, 2000);
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

    $(document).ready(function() {
        $(".money-format").maskMoney();

        /*
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
        */

        $('.datepicker').datetimepicker({
            format: 'DD/MM/YYYY',
            locale: 'pt-br',
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove',
                inline: true
            }

        });

        $('.card .material-datatables label').addClass('form-group');

        /* Limpar modal periodo */
        $("#modal-periodo").on("hide.bs.modal", function () {
            var form = $("#modal-panel").find("form")
            $(form).each (function(){
                var formID = $(this).attr("id");
                $("#"+formID).each (function(){
                    this.reset();
                });
            });
            //$("#inicio").val("");
            //$("#final").val("");
        });

        /* Submita o formualário via Ajax*/
        $( "#formPeriodo" ).submit(function( e ) {
            if ($("#formPeriodo" ).valid()) {
                var formData = new FormData($("#formPeriodo")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('periodo.alteraData')}}',
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $("#modal-periodo").modal('toggle');
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 500);

                        },
                        success: function (data) {
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                            if (data.status == "success") {
                                setTimeout(function(){
                                    loadTable();
                                    $("#nomeMes").html(data.nomeMes);
                                    $("#nomeMes").css('cssText', 'width: 165px !important');
                                }, 2000);
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



</html>