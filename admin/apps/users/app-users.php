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

include_once('../../includes/header.php');
?>

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

    <?php include_once('../../includes/footer.php'); ?>
