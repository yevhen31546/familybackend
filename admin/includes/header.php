<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo BASE_URL;?>images/favicon.ico">

    <title>Fab Admin - Dashboard</title>

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/vendor_components/bootstrap/dist/css/bootstrap.css">

    <!-- Morris charts -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/vendor_components/morris.js/morris.css">

    <!-- Bootstrap switch-->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/vendor_components/bootstrap-switch/switch.css">

    <!-- fullCalendar -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/vendor_components/fullcalendar/fullcalendar.print.min.css" media="print">

    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/bootstrap-extend.css">

    <!-- theme style -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/master_style.css">

    <!-- Fab Admin skins -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/skins/_all-skins.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body class="hold-transition sidebar-mini fixed skin-blue">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo BASE_URL;?>index.php" class="logo">
            <!-- mini logo -->
            <b class="logo-mini">
               <img src="<?php echo BASE_URL;?>images/blacklogosm.png" alt="logo">
               <img src="<?php echo BASE_URL;?>images/whitelogosm.png" alt="logo">
            </b>
            <!-- logo-->
            <span class="logo-lg">
          <img src="<?php echo BASE_URL;?>images/blacklogosm.png" alt="logo" class="light-logo">
          <img src="<?php echo BASE_URL;?>images/whitelogosm.png" alt="logo" class="dark-logo">
        </span>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div>
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar-->
        <section class="sidebar">

            <!-- sidebar menu-->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="user-profile treeview">
                    <a href="href="<?php echo BASE_URL;?>index.php"">
                        <img src="<?php echo BASE_URL;?>images/user5-128x128.jpg" alt="user">
                        <span>Juliya Brus</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-right pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="javascript:void()"><i class="fa fa-user mr-5"></i>My Profile </a></li>
                        <li><a href="javascript:void()"><i class="fa fa-money mr-5"></i>My Balance</a></li>
                        <li><a href="javascript:void()"><i class="fa fa-envelope-open mr-5"></i>Inbox</a></li>
                        <li><a href="javascript:void()"><i class="fa fa-cog mr-5"></i>Account Setting</a></li>
                        <li><a href="<?php echo BASE_URL;?>logout.php"><i class="fa fa-power-off mr-5"></i>Logout</a></li>
                    </ul>
                </li>
                <li class="header nav-small-cap">PERSONAL</li>
                <li class="active">
                    <a href="<?php echo BASE_URL;?>index.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-right pull-right"></i>
              </span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-th"></i>
                        <span>App</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-right pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo BASE_URL;?>apps/users/app-users.php"><i class="fa fa-circle-thin"></i>Users</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>