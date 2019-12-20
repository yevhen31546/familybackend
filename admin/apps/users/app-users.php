<?php
session_start();
require_once '../../dbconfig.php';
// Costumers class
$db = getDbInstance();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['add_user']) && $_POST['add_user'] == 'add_user') {
        $data_to_db = array_filter($_POST);

        unset($data_to_db['add_user']);

        // Insert user and timestamp
        $data_to_db['password'] = password_hash($data_to_db['password'], PASSWORD_DEFAULT);
        $data_to_db['created_date'] = date('Y-m-d');

        $db = getDbInstance();
        $last_id = $db->insert('tbl_users', $data_to_db);

        if ($last_id)
        {
            $_SESSION['success'] = 'User added successfully!';
        }
        else
        {
            echo 'Insert failed: ' . $db->getLastError();
            $_SESSION['failure'] = 'Insert Failed';
        }
    } else if(isset($_POST['edit_user']) && $_POST['edit_user'] == 'edit_user') {
        // Get input data
        $data_to_db = array_filter($_POST);
        unset($data_to_db['edit_user']);
        unset($data_to_db['edit_id']);

        // Insert user and timestamp
//        $data_to_db['updated_by'] = 1;
//        $data_to_db['updated_at'] = date('Y-m-d');

        $db = getDbInstance();
        $db->where('id', $_POST['edit_id']);
        $stat = $db->update('tbl_users', $data_to_db);

        if ($stat)
        {
            $_SESSION['success'] = 'User updated successfully!';
            // Redirect to the listing page
//            header('Location: apps/users/app-users.php');
//            // Important! Don't execute the rest put the exit/die.
//            exit();
        }
    } else if(isset($_POST['del_id']) && $_POST['del_id'] != 0) {
        $db->where('id', $_POST['del_id']);
        $status = $db->delete('tbl_users');

        if ($status)
        {
            $_SESSION['info'] = "User deleted successfully!";
        }
        else
        {
            $_SESSION['failure'] = "Unable to delete user";
        }
    }

}
$query = 'SELECT * FROM tbl_users;';
$rows = $db->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo BASE_URL;?>/images/favicon.ico">

    <title>Fab Admin - Dashboard  App Users </title>

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Data Table-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL;?>/assets/vendor_components/datatable/datatables.min.css"/>

    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/css/bootstrap-extend.css">

    <!-- Theme style -->
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
        <a href="<?php echo BASE_URL;?>/index.html" class="logo">
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
                    <a href="href="<?php echo BASE_URL;?>/index.php"">
                    <img src="<?php echo BASE_URL;?>/images/user5-128x128.jpg" alt="user">
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
                    <a href="<?php echo BASE_URL;?>/index.php">
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
                        <li><a href="<?php echo BASE_URL;?>/apps/users/app-users.php"><i class="fa fa-circle-thin"></i>Users</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Users
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="#">App</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php include BASE_PATH . '/includes/flash_messages.php'; ?>
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Users List</h4>
                            <div class="page-action-links text-right">
                                <a data-toggle="modal" data-target=".user-add-modal" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add new</a>
                            </div>
                        </div>
                        <div class="col-lg-12">

                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="tickets" class="table mt-0 table-hover no-wrap table-striped table-bordered" data-page-size="10">
                                    <thead>
                                    <tr class="bg-dark">
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                                        <td><?php echo htmlspecialchars($row['created_date']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger-outline" data-target=".user-edit-<?php echo $row['id']; ?>" data-toggle="modal" data-original-title="Edit"><i class="ion-edit" aria-hidden="true"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger-outline" data-target="#confirm-delete-<?php echo $row['id']; ?>" data-toggle="modal" data-original-title="Delete"><i class="ti-trash" aria-hidden="true"></i></button>
                                        </td>
                                        <?php include BASE_PATH . '/apps/users/forms/user_del_modal.php';?>
                                        <?php include BASE_PATH . '/apps/users/forms/user_edit_modal.php'; ?>
                                    </tr>

                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <?php include BASE_PATH . '/apps/users/forms/user_add_modal.php'; ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<footer class="main-footer">
    &copy; 2019 All Rights Reserved.
</footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo BASE_URL;?>/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js"></script>

<!-- popper -->
<script src="<?php echo BASE_URL;?>/assets/vendor_components/popper/dist/popper.min.js"></script>

<!-- Bootstrap 4.0-->
<script src="<?php echo BASE_URL;?>/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- This is data table -->
<script src="<?php echo BASE_URL;?>/assets/vendor_components/datatable/datatables.min.js"></script>

<!-- SlimScroll -->
<script src="<?php echo BASE_URL;?>/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<!-- FastClick -->
<script src="<?php echo BASE_URL;?>/assets/vendor_components/fastclick/lib/fastclick.js"></script>

<!-- Fab Admin App -->
<script src="<?php echo BASE_URL;?>/js/template.js"></script>
<!---->
<!-- Fab Admin for demo purposes -->
<script src="<?php echo BASE_URL;?>/js/demo.js"></script>

<!-- Fab Admin for Data Table -->
<script src="<?php echo BASE_URL;?>/js/pages/data-table.js"></script>


</body>
</html>
