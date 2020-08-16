<?php
if (empty($this->session->userdata('is_login_verify'))) {
    redirect('/admin');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Bubble - Admin</title>

        <!-- Bootstrap core CSS -->
        <link href="<?= base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="<?= base_url(); ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
              type="text/css">

        <!-- Custom fonts for this template -->
        <link href="<?= base_url(); ?>assets/plugins/themify/css/themify.css" rel="stylesheet" type="text/css">

        <!-- Angular Tooltip Css -->
        <link href="<?= base_url(); ?>assets/plugins/angular-tooltip/angular-tooltips.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="<?= base_url(); ?>assets/plugins/morris.js/morris.css" rel="stylesheet">

        <!-- Sweet Alert CSS -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert/css/sweetalert.css">

        <!-- Page level plugin CSS -->
        <link href="<?= base_url(); ?>assets/dist/css/animate.css" rel="stylesheet">


        <!-- daterange picker -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/daterangepicker/daterangepicker-bs3.css">

        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datepicker/datepicker3.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">


        <!-- Custom styles for this template -->
        <link href="<?= base_url(); ?>assets/dist/css/glovia.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/dist/css/glovia-responsive.css" rel="stylesheet">
        <!-- Select2 -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/select2/select2.min.css">

        <!-- Custom styles for Color -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/skins/default.css">

        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/custom.css">
        <link rel="stylesheet" type="text/css"
              href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css"/>
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/jquery.toast.css" type="text/css">
        <script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>


    </head>

    <body class="fixed-nav sticky-footer" id="page-top">

        <!-- ===============================
                Navigation Start
        ====================================-->
        <nav class="navbar navbar-expand-lg bb-1 navbar-light br-full-dark bg-dark fixed-top" id="mainNav">

            <!-- Start Header -->
            <header class="header-logo bg-dark bb-1 br-1 br-light-dark">
                <a class="nav-link text-center mr-lg-3 hidden-xs" id="sidenavToggler"><i class="ti-align-left"></i></a>
                <a class="navbar-brand" href="<?= base_url(); ?>admin/dashboard"><img
                        src="<?= base_url(); ?>assets/dist/img/logo-impilo.png"/></a>
            </header>
            <!-- End Header -->

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="ti-align-left"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">

                <!-- =============== Start Side Menu ============== -->
                <div class="navbar-side">
                    <ul class="navbar-nav navbar-sidenav bg-light-dark" id="exampleAccordion">

                            <li class="nav-item active" data-toggle="tooltip" data-placement="right" title="Dashboard">
                                <a class="nav-link" href="<?= base_url(); ?>admin/dashboard">
                                    <i class="ti i-cl-3 ti-dashboard"></i>
                                    <span class="nav-link-text">Dashboard</span>
                                </a>
                            </li>

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="User List">
                                <a class="nav-link" href="<?= base_url(); ?>admin/user_list">
                                    <i class="ti i-cl-3 ti-user"></i>
                                    <span class="nav-link-text">User List</span>
                                </a>
                            </li>

                             <!-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Referral Setting">
                                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#reports"
                                   data-parent="#exampleAccordion">
                                    <i class="ti i-cl-6 fa fa-tree"></i>
                                    <span class="nav-link-text">Referral Setting</span>
                                </a>
                                <ul class="sidenav-second-level collapse" id="reports">

                                    <li>
                                        <a href="<?= base_url(); ?>admin/add_referral_level">Add Referral Level</a>
                                    </li>

                                    <li>
                                        <a href="<?= base_url(); ?>admin/referral_level_manage">Referral Level Manage</a>
                                    </li>
                                </ul>
                            </li> -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Notifications">
                                <a class="nav-link" href="<?= base_url(); ?>admin/security">
                                    <i class="ti i-cl-8 ti-lock"></i>
                                    <span class="nav-link-text">Security</span>
                                </a>
                            </li>

                    </ul>
                </div>
                <!-- =============== End Side Menu ============== -->

                <!-- =============== Header Rightside Menu ============== -->
                <ul class="navbar-nav ml-auto">


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mr-lg-0 user-img a-topbar__nav a-nav" id="userDropdown" href="#"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <img src="<?=base_url();?>uploads/<?=admin_profile();?>" alt="user-img" width="36"
                                        class="img-circle">

                        </a>

                        <ul class="dropdown-menu dropdown-user animated flipInX" aria-labelledby="userDropdown">
                            <li class="dropdown-header green-bg">
                                <div class="header-user-pic">

                                    <img src="<?=base_url();?>uploads/<?=admin_profile();?>" alt="user-img" width="36"
                                         class="img-circle">


                                </div>
                                <div class="header-user-det">
                                    <span class="a-dropdown__header-title"><?= $this->session->userdata('admin_name'); ?></span>
                                    <span class="a-dropdown__header-subtitle"><?= $this->session->userdata('admin_email'); ?></span>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url(); ?>admin/admin_profile"><i class="ti-user"></i> My
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url(); ?>admin/Login/jadminlogout"><i
                                        class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- =============== End Header Rightside Menu ============== -->
            </div>
            <button class="w3-button w3-teal w3-xlarge w3-right" onclick="openRightMenu()"><i class="spin fa fa-cog" hidden="true"></i></button>
        </nav>
        <!-- =====================================================
                            End Navigations
        ======================================================= -->
