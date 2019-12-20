<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
include BASE_PATH.'/members/includes/header.php';
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$row = $db->getOne('tbl_users');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input post if we want
    $data_to_db = filter_input_array(INPUT_POST);

    // Check whether the user name already exists
    $db->where('user_name', $data_to_db['user_name']);
    $db->where('id', $logged_id, '!=');
    $row = $db->getOne('tbl_users');

    if (!empty($row['user_name']))
    {
        $_SESSION['profile_update_failure'] = 'Username/Email already exists';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $row = $db->getOne('tbl_users');
    }
//    else if (password_verify($data_to_db['pre_password'], $old_password)) {
//        if($data_to_db['password'] == $data_to_db['confirm_password']) {
//            $db = getDbInstance();
//            $db->where('id', $logged_id);
//            unset($data_to_db['pre_password']);
//            unset($data_to_db['confirm_password']);
//            $data_to_db['password'] = password_hash($data_to_db['password'], PASSWORD_DEFAULT);
//            $stat = $db->update('tbl_users', $data_to_db);
//            if ($stat)
//            {
//                $_SESSION['success'] = 'Profile has been updated successfully';
//            } else {
//                $_SESSION['profile_update_failure'] = 'Failed to update profile: ' . $db->getLastError();
//            }
//            $row = $db->getOne('tbl_users');
//        } else {
//            $_SESSION['profile_update_failure'] = 'Password is not matched with confirm password';
//            $db = getDbInstance();
//            $db->where('id', $logged_id);
//            $row = $db->getOne('tbl_users');
//        }
//    }
    else {
//        $_SESSION['profile_update_failure'] = 'Password is not correct';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $stat = $db->update('tbl_users', $data_to_db);
        if ($stat)
        {
            $_SESSION['success'] = 'Profile has been updated successfully';
            $db = getDbInstance();
            $db->where('id', $logged_id);
            $row = $db->getOne('tbl_users');
        } else {
            $_SESSION['profile_update_failure'] = 'Failed to update profile: ' . $db->getLastError();
        }

    }
}

?>
<section  class="pt--70">
    <div class="container">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form class="form " method="POST" action="">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">Update Profile</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label">Username</label>
                            <input type="text" name="user_name" class="form-control" required="required" value="<?php echo $row['user_name']?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">User Email</label>
                            <input type="text" name="user_email" class="form-control" value="<?php echo $row['user_email']?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Phone Number</label>
                            <input type="text" name="phone_no" class="form-control" value="<?php echo $row['phone_no']?>">
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label class="control-label">Previous Password</label>-->
<!--                            <input type="password" name="pre_password" class="form-control">-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label class="control-label">New Password</label>-->
<!--                            <input type="password" name="password" class="form-control">-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label class="control-label">Password Confirm</label>-->
<!--                            <input type="password" name="confirm_password" class="form-control">-->
<!--                        </div>-->
                        <?php if (isset($_SESSION['profile_update_failure'])): ?>
                            <div class="alert alert-danger alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php
                                echo $_SESSION['profile_update_failure'];
                                unset($_SESSION['profile_update_failure']);
                                ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-success pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
</section>
<?php include BASE_PATH.'/members/includes/footer.php'?>