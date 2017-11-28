<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{ $title }} - MoneyCash</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="C:\GitHub\ProjetoIntegrador\public\css\bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="C:\GitHub\ProjetoIntegrador\public\css\material-dashboard.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="<?php  base_path('public/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="C:\GitHub\ProjetoIntegrador\public\css\css.css" />


</head>

<body>
<div class="wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon">
                            <img src="C:\GitHub\ProjetoIntegrador\public\img\icon-rel.png" style="width: 50px; height: 50px;">
                        </div>
                        <div class="card-content">
                            <h4 class="card-title" style="margin-top: 15px;">{{ $title }}</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-primary">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Data de recebimento </th>
                                        <th>Valor </th>
                                        <th>Conta </th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>