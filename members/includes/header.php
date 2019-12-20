<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewpordt" content="width=device-width, initial-scale=1">

    <!-- ==== Document Title ==== -->
    <title>MyNotes4U</title>

    <!-- ==== Document Meta ==== -->
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- ==== Favicon ==== -->
    <link rel="icon" href="favicon.png" type="image/png">

    <!-- ==== Google Font ==== -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700%7CRoboto:300,400,400i,500,700">

    <!-- ==== Plugins Bundle ==== -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/plugins.min.css">

    <!-- ==== Main Stylesheet ==== -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/style.css">

    <!-- ==== Responsive Stylesheet ==== -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/responsive-style.css">

    <!-- ==== Color Scheme Stylesheet ==== -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/colors/color-1.css" id="changeColorScheme">

    <!-- ==== Custom Stylesheet ==== -->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/custom.css">

    <!-- ==== HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries ==== -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<!-- Preloader Start -->
<div id="preloader">
    <div class="preloader--inner"></div>
</div>
<!-- Preloader End -->

<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Header Section Start -->
    <header class="header--section style--1">
        <!-- Header Topbar Start -->
        <div class="header--topbar bg-black">
            <div class="container">
                <!-- Header Topbar Links Start -->
                <ul class="header--topbar-links nav ff--primary float--left">
                    <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span>En</span>
                            <i class="fa fa-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li class="active"><a href="#">En</a></li>
                            <li><a href="#">Bn</a></li>
                            <li><a href="#">In</a></li>
                        </ul>
                    </li>-->
                </ul>
                <!-- Header Topbar Links End -->

                <!-- Header Topbar Social Start -->
                <ul class="header--topbar-social nav float--left hidden-xs">
                    <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                     <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                     <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                     <li><a href="#"><i class="fa fa-rss"></i></a></li>
                     <li><a href="#"><i class="fa fa-youtube"></i></a></li>-->
                </ul>
                <!-- Header Topbar Social End -->

                <!-- Header Topbar Links Start -->
                <ul class="header--topbar-links nav ff--primary float--right">
                    <!--<li>
                        <a href="../cart.html" title="Cart" data-toggle="tooltip" data-placement="bottom">
                            <i class="fa fa-shopping-basket"></i>
                            <span class="badge">3</span>
                        </a>
                    </li>-->
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa mr--8 fa-user-o"></i>
                            <span>My Account</span>
                            <i class="fa fa-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li class="active">
                                <a href="<?php echo BASE_URL;?>/members/member-profile.php"><span>My Profile</span></a></li>
                            <li><a href="<?php echo BASE_URL;?>/members/logout.php"><span>Log out</span></a></li>
                        </ul>
                    </li>
                </ul>
                <!-- Header Topbar Links End -->
            </div>
        </div>
        <!-- Header Topbar End -->

        <!-- Header Navbar Start -->
        <div class="header--navbar navbar bg-black" data-trigger="sticky">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle style--1 collapsed" data-toggle="collapse" data-target="#headerNav">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Header Navbar Logo Start -->
                    <div class="header--navbar-logo navbar-brand">
                        <a href="<?php echo BASE_URL?>/members/home.php">
                            <img src="<?php echo BASE_URL?>/members/blacklogosm.png" class="normal" alt="">
                            <img src="<?php echo BASE_URL?>/members/whitelogosm.png" class="sticky" alt="">
                        </a>
                    </div>
                    <!-- Header Navbar Logo End -->
                </div>

                <div id="headerNav" class="navbar-collapse collapse float--right">



                    <!-- Header Nav Links Start -->
                    <ul class="header--nav-links style--1 nav ff--primary">

                        <li><a href="<?php echo BASE_URL?>/members/home.php"><span>Home</span></a></li>

                        <li class="dropdown active">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span>Albums</span>
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li class="active">
                                    <a href="<?php echo BASE_URL;?>/members/activity-me.php"><span>My Album</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/activity-fam.html"><span>My Family</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/activity-frd.html"><span>My Friends</span></a></li>

                            </ul>
                        </li>




                        <!-- <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <span>Members</span>
                                  <i class="fa fa-caret-down"></i>
                              </a>

                              <ul class="dropdown-menu"><li>
                                  <a href="members.html"><span>Members</span></a></li>-->

                        <li><a href="<?php echo BASE_URL;?>/members/members.php"/><span>Members</span></a></li>

                        <!-- </ul>-->
                        </li>




                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span>Groups</span>
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li><a href="<?php echo BASE_URL;?>/members/groups-church.php"><span>Church</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/groups-events.php"><span>Events</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/groups-homerepair.php"><span>Home Repairs</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/groups-pets.php"><span>Pets</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/groups-recipes.php"><span>Recipes</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/groups-sports.php"><span>Sports</span></a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/groups-travel.php"><span>Travel</span></a></li>

                            </ul>
                        </li>

                        </li>
                        <li><a href="<?php echo BASE_URL;?>/members/contact.php"><span>Contact</span></a></li>
                    </ul>
                    <!-- Header Nav Links End -->
                </div>
            </div>
        </div>
        <!-- Header Navbar End -->
    </header>
    <!-- Header Section End -->