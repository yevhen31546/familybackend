<?php
session_start();
require_once 'config/config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));

// If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE)
{
    header('Location: members/home.php');
}
if(isset($_POST['user_name']) && $_POST['user_name'] != '') {
    // check password is patched with confirm password
    if($_POST['password'] != $_POST['confirm_password']) {
        $_SESSION['register_failure'] = 'Password does not match confirm password';
    } else {
        $data_to_db = array_filter($_POST);

        // Insert user and timestamp
        unset($data_to_db['confirm_password']);
        $data_to_db['created_date'] = date('Y-m-d');
        $data_to_db['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $db = getDbInstance();
        $db->where('user_name', $data_to_db['user_name']);
        $db->orWhere('user_email', $data_to_db['user_email']);
        $row = $db->getOne('tbl_users');
        if (!empty($row['user_name']) || !empty($row['user_email'])){
            $_SESSION['register_failure'] = 'Username already exists';
        } else {
            $last_id = $db->insert('tbl_users', $data_to_db);

            if ($last_id)
            {
                $_SESSION['success'] = 'User added successfully!';
                // Redirect to the Members page
                header('Location: members/home.php');
                // Important! Don't execute the rest put the exit/die.
                exit();
            }
            else
            {
                $_SESSION['register_failure'] = 'Inert DB error'.$db->getLastError();
            }
        }
    }
}
?>
<?php include BASE_PATH.'/includes/header.php'; ?>
    <section id="demos" class="pt--70">
        <div id="page-" class="col-md-4 col-md-offset-4">
            <form class="form loginform" method="POST" action="">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">Welcome to register</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label">Username</label>
                            <input type="text" name="user_name" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label class="control-label">User Email</label>
                            <input type="email" name="user_email" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label class="control-label">First Name</label>
                            <input type="text" name="last_name" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Last Name</label>
                            <input type="text" name="first_name" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password</label>
                            <input type="password" name="password" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Phone number</label>
                            <input type="text" name="phone_no" class="form-control">
                        </div>
                        <?php if (isset($_SESSION['register_failure'])): ?>
                            <div class="alert alert-danger alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php
                                echo $_SESSION['register_failure'];
                                unset($_SESSION['register_failure']);
                                ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-success loginField">Register</button>
                        <a href="login.php" class="btn btn-success loginField pull-right">Login</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
<?php include BASE_PATH.'/includes/footer.php'; ?>