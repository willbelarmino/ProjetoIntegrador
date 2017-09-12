<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="../img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="../css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="../css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/css.css" />
</head>

<body>
<nav class="navbar navbar-primary navbar-transparent navbar-absolute">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    @yield('link')
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="wrapper wrapper-full-page">

        @yield('content')

</div>
</body>
<!--   Core JS Files   -->
<script src="../js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="../js/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/material.min.js" type="text/javascript"></script>
<script src="../js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="../js/jquery.validate.min.js"></script>
<script src="../js/additional-methods.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="../js/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="../js/chartist.min.js"></script>
<!--  Plugin for the Wizard -->
<script src="../js/jquery.bootstrap-wizard.js"></script>
<!--  Notifications Plugin    -->
<script src="../js/bootstrap-notify.js"></script>
<!-- DateTimePicker Plugin -->
<script src="../js/bootstrap-datetimepicker.js"></script>
<!-- Vector Map plugin -->
<script src="../js/jquery-jvectormap.js"></script>
<!-- Sliders Plugin -->
<script src="../js/nouislider.min.js"></script>
<!-- Select Plugin -->
<script src="../js/jquery.select-bootstrap.js"></script>
<!--  DataTables.net Plugin    -->
<script src="../js/jquery.datatables.js"></script>
<!-- Sweet Alert 2 plugin -->
<script src="../js/sweetalert2.js"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="../js/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin    -->
<script src="../js/fullcalendar.min.js"></script>
<!-- TagsInput Plugin -->
<script src="../js/jquery.tagsinput.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="../js/material-dashboard.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="../js/demo.js"></script>
<!-- My functions! -->
<script src="../js/functions.js"></script>
<!-- Jquery Mask Money! -->
<script src="../js/jquery.maskMoney.js"></script>
<script type="text/javascript">
    $().ready(function() {
        demo.checkFullPageBackgroundImage();

        setTimeout(function() {
            // after 1000 ms we add the class animated to the login/register card
            $('.card').removeClass('card-hidden');
        }, 700)
    });
</script>

@yield('scripts')

</html>