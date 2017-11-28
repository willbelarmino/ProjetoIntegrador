@extends('template/layout2')

@section('title')
    <title>Home - MoneyCash</title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="content-table-view">
        <div class="content-table-view2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="blue" style="margin: -20px 5px 0px">
                                <i class="material-icons">monetization_on</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Saldo Atual</p>
                                <h3 class="card-title">{{ 'R$ '.number_format($saldoAtual, 2, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="green" style="margin: -20px 5px 0px">
                                <i class="material-icons">monetization_on</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Saldo Estimado</p>
                                <h3 class="card-title">{{ 'R$ '.number_format($saldoEstimado, 2, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="red" style="margin: -20px 5px 0px">
                                <i class="material-icons">payment</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Despesas Pendentes</p>
                                <h3 class="card-title">{{ 'R$ '.number_format($totalDespesaPendente, 2, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="orange" style="margin: -20px 5px 0px">
                                <i class="material-icons">payment</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Despesas Pagas</p>
                                <h3 class="card-title">{{ 'R$ '.number_format($totalDespesaPaga, 2, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="yellow" style="margin: -20px 5px 0px">
                                <i class="material-icons">payment</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Rendas</p>
                                <h3 class="card-title">{{ 'R$ '.number_format($totalRenda, 2, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!empty($contas) && $contas!='[]')

                    <div class="row">
                        <div class="card">
                            <div class="card-header card-header-icon" data-background-color="grey" style="cursor:pointer;" data-toggle="collapse" data-target="#panel-contas">
                                <i class="mdi mdi-account-card-details"></i>
                            </div>

                            <div class="card-content">
                                <h4 class="card-title">
                                    Contas
                                    <i class="mdi mdi-settings" title="Configurações" style="cursor: pointer; float: right" data-toggle="modal" data-target="#modal-config"></i>
                                </h4>
                                    <div id="panel-contas" class="collapse-conta in">

                                        @foreach ($contas->slice(0, 4) as $conta)

                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="card card-stats">
                                                    <div class="card-header-conta" data-background-color="transparent">
                                                        <div class="header-conta">
                                                            <div class="title-conta-default">
                                                                <img  style="width: 30px; height: 25px; margin-right: 25px;"
                                                                      src="{{ asset('storage/contas/'.$conta->image) }}" alt="...">
                                                                <span class="title-conta">{{ $conta->nome }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-content">
                                                        <div class="card-content-conta">
                                                            <h3 class="card-title-saldo">Saldo: {{ 'R$ '.number_format($conta->saldo, 2, ',', '.') }}</h3>
                                                            <h3 class="card-title-movimenta">Última movimentação: {{ date('d/m/Y', strtotime($conta->dt_movimento)) }}</h3>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="stats" onclick="verExtrato({{$conta->id}});" style="cursor: pointer;" title="Visualizar extrato">
                                                            <i class="material-icons">list</i> Ver extrato
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach

                                    </div>
                            </div>
                        </div>
                    </div>

                @endif

                <div class="row" id="panel-chart" style="display: none;">
                    <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header card-header-icon" data-background-color="red">
                                        <i class="material-icons">pie_chart</i>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">Despesas / Categoria</h4>
                                    </div>
                                    <div id="chartPreferences" class="ct-chart" ></div>
                                    <div class="card-footer">
                                        <h6>Legenda</h6>
                                        <div id="legenda-label">

                                        </div>
                                    </div>
                                </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header card-header-icon" data-background-color="rose">
                                <i class="material-icons">insert_chart</i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Rendas / Despesas
                                    <small>- Anual</small>
                                </h4>
                            </div>
                            <div id="multipleBarsChart" style="height: 320px;" class="ct-chart"></div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>    
@endsection


@section('modal')

    <!-- MODAL CONFIG -->
    <div class="modal fade" id="modal-config" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content" style="left: 16% !important;">
                <div class="card">
                    <form id="formCconfig">
                        <div class="card-header card-header-icon" data-background-color="purple">
                            <i class="mdi mdi-account-card-details"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Selecionar contas</h4>


                            <select class="selectpicker" data-style="select-with-transition" multiple title="Selecione" data-size="4">
                                <option disabled> Selecione</option>
                                @foreach ($contas->slice(0, 4) as $conta)

                                    <option value="{{$conta->id}}">{{$conta->nome}} </option>

                                @endforeach
                            </select>

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
    <!-- /MODAL CONFIG -->

    <!-- MODAL EXTRATO CONTA -->
    <div class="modal fade" id="modal-extrato-conta" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="left: 16% !important;">
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">Extrato</h4>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->

                        </div>
                        <div class="table-responsive">                            
                            <table id="extratotables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead class="text-primary">
                                    <tr>
                                        <th class="disabled-sorting">Data</th>
                                        <th class="disabled-sorting">Lançamento</th>
                                        <th class="disabled-sorting">Valor</th>                                           
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
    <!-- /MODAL EXTRATO CONTA -->

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

        function loadTable() {
            $('.content-table-view').load("{{route('home')}} .content-table-view2", function() {
                $('#datatables').DataTable({
                    "pagingType": "full_numbers",
                    "deferRender": true,
                    "processing": true,
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

        function verExtrato(id) {           

            $('#extratotables').DataTable({
                ajax: {
                    url: '{{ route('extrato-conta') }}',
                    data: { id : id },
                    cache: false,
                    beforeSend: function () {
                        setTimeout(function(){ $("#loading").modal('toggle'); }, 500);
                    },
                    dataSrc: function ( json ) {
                        setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                        if (json.data!='error') {
                            setTimeout(function(){ $("#modal-extrato-conta").modal('toggle'); }, 2500);
                            return json.data;
                        } else {
                            setTimeout(function(){ showErrorNotification('Erro ao gerar extrato. Tente novamente mais tarde.'); }, 2500);
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


       $(document).ready(function() {




            /*  **************** Graficos despesas por categoria ******************** */

            $.ajax({
                        type: "GET",
                        url: '{{route('gerar.grafico')}}',                        
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        cache: false,                       
                        success: function (json) {                            
                            if (json.categoria!='error') {
                                //console.log(json.categoria);
                                if (json.categoria!=null && json.categoria.length!=0) {

                                    // CHART PIE //

                                    var labels = [];
                                    var series = [];
                                    var colors = ['text-info','text-danger','text-warning','text-primary','text-success','text-gray'];
                                    var legenda_contend = "";
                                    for (var i = 0; i < json.categoria.length; i++) {
                                        labels.push(json.categoria[i].porcentagem + "%");
                                        series.push(json.categoria[i].porcentagem);
                                        legenda_contend = legenda_contend + "<i class='fa fa-circle "+colors[i]+"'></i>"+json.categoria[i].nome;
                                        $("#legenda-label").html(legenda_contend);
                                        //console.log(legenda_contend);

                                    }

                                    var dataPreferences = {
                                        labels: labels,
                                        series: series
                                    };

                                    var optionsPreferences = {
                                        height: '230px',
                                        donut: true,
                                        labelDirection: 'explode'
                                    };

                                    Chartist.Pie('#chartPreferences', dataPreferences, optionsPreferences);

                                    // CHART BAR //

                                    var dataMultipleBarsChart = {
                                        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                        series: [
                                            [3500, 443, 320, 780, 553, 453, 326, 434, 568, 610, 756, 895],
                                            [2300, 243, 280, 580, 453, 353, 300, 364, 368, 410, 636, 695]
                                        ]
                                    };

                                    var optionsMultipleBarsChart = {
                                        seriesBarDistance: 10,
                                        axisX: {
                                            showGrid: false,
                                        },
                                        axisY: {
                                            offset: 80,
                                            labelInterpolationFnc: function(value) {
                                                return 'R$ ' +value
                                            },
                                            scaleMinSpace: 15
                                        },
                                        height: '300px'
                                    };

                                    var responsiveOptionsMultipleBarsChart = [
                                        ['screen and (max-width: 640px)', {
                                            seriesBarDistance: 5,
                                            axisX: {
                                                labelInterpolationFnc: function(value) {
                                                    return value[0];
                                                }
                                            }
                                        }]
                                    ];

                                    var multipleBarsChart = Chartist.Bar('#multipleBarsChart', dataMultipleBarsChart, optionsMultipleBarsChart, responsiveOptionsMultipleBarsChart);

                                    //start animation for the Emails Subscription Chart
                                    md.startAnimationForBarChart(multipleBarsChart);

                                    //console.log(json.categoria.length);
                                    $("#panel-chart").css("display", "block");
                                }
                            } else {
                                console.log("ERRO");
                            }
                        }
                        
            });
            
        });
    </script>
@endsection