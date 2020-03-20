<?php
session_start();
require_once 'config/config.php';
require_once 'vendor/autoload.php';
require_once 'members/smtp_endpoint.php';
require_once 'members/notification.php';

// If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE)
{
    header('Location: members/home.php');
}

if (!isset($_GET) && !isset($_GET['token'])) {
    header('Location: forgot_password.php');
}

if (isset($_POST) && isset($_POST['token'])) {
    $password = $_POST['password'];
    $rpassword = $_POST['rpassword'];
    $token = $_POST['token'];
    if ($password === $rpassword) { // checking password is matched
        $db = getDbInstance();
        // find user data
        $db->where('token', $token);
        $val = $db->getOne('tbl_forgot_pwd');
        $user_id = $val['user_id'];
        $timestamp = $val['timestamp'];
        $difference = abs(time() - $timestamp)/3600;

        if ($user_id && $difference > 3) { // check token is expired
            $_SESSION['failure'] = 'Sorry, token was expired. Please send again.';
        } else {
            $new_password = password_hash($password, PASSWORD_DEFAULT);
            $data = array(
                'password' => $new_password
            );
            $db->where('id', $user_id);
            $db->update('tbl_users', $data);

            $_SESSION['success'] = 'Password updated successfully. Please try login again.';
        }
    } else {
        $_SESSION['failure'] = 'Passwords should match on both fields';
    }
}



?>
<?php include BASE_PATH.'/includes/header.php'; ?>
<section id="demos" class="pt--70">
    <div id="page-" class="col-md-4 col-md-offset-4">
        <form class="form" method="POST">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">Reset password</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">Please enter your new password.</label>
                        <input type="password" name="password" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Please re-enter your new password.</label>
                        <input type="password" name="rpassword" class="form-control" required="required">
                    </div>
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                    <?php if (isset($_SESSION['failure'])): ?>
                        <div class="alert alert-danger alert-dismissable fade in" style="margin-bottom: 10px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right: 6px !important;">&times;</a>
                            <?php
                            echo $_SESSION['failure'];
                            unset($_SESSION['failure']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissable fade in" style="margin-bottom: 10px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right: 6px !important;">&times;</a>
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn" style="background: #7398aa; color: white;">
                        Reset Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
<?php include BASE_PATH.'/includes/footer.php'; ?>
