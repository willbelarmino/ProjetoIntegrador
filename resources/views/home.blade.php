@extends('template/layout2')

@section('title')
    <title>Home - MoneyCash</title>
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
                <div class="row">
                    <div class="card">
                        <div class="card-header card-header-icon" data-background-color="grey" style="cursor:pointer;" data-toggle="collapse" data-target="#panel-contas">
                            <i class="mdi mdi-account-card-details"></i>
                        </div>

                        <div class="card-content">
                            <h4 class="card-title">
                                Contas
                                <i class="mdi mdi-settings" title="Configurações" style="cursor: pointer; float: right" onclick="alert('Config')"></i>
                            </h4>

                            <div id="panel-contas" class="collapse-conta in">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header-conta" data-background-color="transparent">
                                            <div class="header-conta">
                                                <div class="title-conta-default">
                                                    <img  style="width: 30px; height: 25px; margin-right: 25px;"
                                                          src="{{ asset('storage/contas/321507811744.jpg') }}" alt="...">
                                                    <span class="title-conta">Bradesco</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-content-conta">
                                                <h3 class="card-title-saldo">Saldo: R$ 80.654,65</h3>
                                                <h3 class="card-title-movimenta">Última movimentação: 10/07/2017</h3>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                                <i class="material-icons">list</i> Ver extrato
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header-conta" data-background-color="transparent">
                                            <div class="header-conta">
                                                <div class="title-conta">Itaú</div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-content-conta">
                                                <h3 class="card-title-saldo">Saldo: R$ 80.654,65</h3>
                                                <h3 class="card-title-movimenta">Última movimentação: 10/07/2017</h3>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                                <i class="material-icons">list</i> Ver extrato
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header-conta" data-background-color="transparent">
                                            <div class="header-conta">
                                                <div class="title-conta">Caixa</div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-content-conta">
                                                <h3 class="card-title-saldo">Saldo: R$ 80.654,65</h3>
                                                <h3 class="card-title-movimenta">Última movimentação: 10/07/2017</h3>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                                <i class="material-icons">list</i> Ver extrato
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header-conta" data-background-color="transparent">
                                            <div class="header-conta">
                                                <div class="title-conta">Santander</div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-content-conta">
                                                <h3 class="card-title-saldo">Saldo: R$ 80.654,65</h3>
                                                <h3 class="card-title-movimenta">Última movimentação: 10/07/2017</h3>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                                <i class="material-icons">list</i> Ver extrato
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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


        $(document).ready(function() {


        });

    </script>
@endsection