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

if (isset($_POST) && isset($_POST['user_email'])) {
    $user_email = $_POST['user_email'];
    $db = getDbInstance();
    // find user data
    $db->where('user_email', $user_email);
    $user_id = $db->getValue('tbl_users', 'id');
    if ($user_id) {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $timestamp = time();
        $data_arr = array(
            'user_id' => $user_id,
            'token' => $token,
            'timestamp' => $timestamp
        );
        $db->insert('tbl_forgot_pwd', $data_arr);

        $body = generateForgotPassMsgBody($token); //
        $stat = sendEmail($user_email, $body);

        $_SESSION['success'] = 'Reset password email will be arrived.';
        $bell_count++;
    } else {
        $_SESSION['invalid_email'] = 'Sorry, email is not exists in site.';
        $bell_count++;
    }
}

?>
<?php include BASE_PATH.'/includes/header.php'; ?>
<section id="demos" class="pt--70">
    <div id="page-" class="col-md-4 col-md-offset-4">
        <form class="form" method="POST">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">Forgot password</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">Please enter your email address. We'll email instructions on
                        how to reset your password.</label>
                        <input type="email" name="user_email" class="form-control" required="required">
                    </div>
                    <?php if (isset($_SESSION['invalid_email'])): ?>
                        <div class="alert alert-danger alert-dismissable fade in" style="margin-bottom: 10px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right: 6px !important;">&times;</a>
                            <?php
                            echo $_SESSION['invalid_email'];
                            unset($_SESSION['invalid_email']);
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
