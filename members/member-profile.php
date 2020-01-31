<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$row = $db->getOne('tbl_users');

function sendInviteEmail($to, $body) {

    // Create the Transport
    $transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT, SMTP_ENC))
        ->setUsername(SMTP_FROM)
        ->setPassword(SMTP_PASS)
    ;

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

// Create a message
    $message = (new Swift_Message('Invitation from MyNotes4U!'))
        ->setFrom([SMTP_FROM => 'MyNotes4U'])
        ->setTo([$to => $to])
        ->setContentType("text/html")
        ->setBody($body)
    ;

// Send the message
    $result = $mailer->send($message);
    return $result;
}

// Generate family message body
function generateFamMessageBody($user, $content) {
    $message = "";
    $message .="<html><head><title>HTML email</title></head><body><p>Hello ".$content['firstname']." ".$content['lastname']."!</p><p>Invite family member request with ".$content['family_member']." is arrived from ".$user['first_name']." ".$user['last_name']."</p></body></html>";
    return $message;
}

// Generate friend message body
function generateFriMessageBody($user, $content) {
    $message = "";
    $message .="<html><head><title>HTML email</title></head><body><p>Hello ".$content['fri_firstname']." ".$content['fri_lastname']."!</p><p>Invite friend request is arrived from ".$user['first_name']." ".$user['last_name']."</p></body></html>";
    return $message;
}

// Update about me
if(isset($_POST) && isset($_POST['first_name'])) {
    $db = getDbInstance();
    $db->where('id', $logged_id);
    $stat = $db->update('tbl_users', $_POST);
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
// Update credentials
if(isset($_POST) && isset($_POST['user_name'])) {
    $db = getDbInstance();
    $db->where('id', $logged_id);
    $updated_arr = array(
        "user_name" => $_POST['user_name'],
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT)
    );
    $stat = $db->update('tbl_users', $updated_arr);
    if ($stat)
    {
        $_SESSION['success'] = 'Credentials has been updated successfully';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $row = $db->getOne('tbl_users');
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update credentials: ' . $db->getLastError();
    }
}

// Biography credentials
if(isset($_POST) && isset($_POST['biography'])) {
    $db = getDbInstance();
    $db->where('id', $logged_id);
    $stat = $db->update('tbl_users', $_POST);
    if ($stat)
    {
        $_SESSION['success'] = 'Biography has been updated successfully';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $row = $db->getOne('tbl_users');
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update biography: ' . $db->getLastError();
    }
}

// Invite family member
if(isset($_POST) && isset($_POST['family_email'])) {
    $to = $_POST['family_email'];
    $body = generateFamMessageBody($row, $_POST);

    $stat = sendInviteEmail($to, $body);
    if ($stat) {
        $_SESSION['success'] = 'Invitation email is sent successfully!';
    } else {
        $_SESSION['failure'] = 'Sending invitation email is failed!';
    }
}

// Invite friend
if(isset($_POST) && isset($_POST['friend_email'])) {
    $to = $_POST['friend_email'];
    $body = generateFriMessageBody($row, $_POST);

    $stat = sendInviteEmail($to, $body);
    if ($stat) {
        $_SESSION['success'] = 'Invitation email is sent successfully!';
    } else {
        $_SESSION['failure'] = 'Sending invitation email is failed!';
    }
}

if(isset($_POST) && isset($_POST['cover_photo_fg']) && isset($_FILES["cover_photo"]["name"])) {
    $db = getDbInstance();
    $data_to_db = array();
    if(isset($_FILES["cover_photo"]["name"])) {
        $target_dir = "./uploads/" . $_SESSION['user_id'] . "/covers/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  //create directory if not exist
        }
        $target_file = $target_dir . basename($_FILES["cover_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["cover_photo"]["tmp_name"]);

        if ($check !== false) {
//                echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
//                echo "File is not an image.";
            $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
//            echo "Sorry, file already exists.";
            $_SESSION['failure'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
//        // Check file size
        if ($_FILES["cover_photo"]["size"] > 500000) {
//            echo "Sorry, your file is too large.";
            $_SESSION['failure'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
//         Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
//            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an failure
        if ($uploadOk == 0) {
//            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
//            echo "Sorry, your file was not uploaded.";
            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file)) {
//                echo "The file ". basename( $_FILES["cover_photo"]["name"]). " has been uploaded.";
                $data_to_db['cover_photo'] = $target_file;
                $db->where('id', $logged_id);
                $last_id = $db->update('tbl_users', $data_to_db);
                $db = getDbInstance();
                $db->where('id', $logged_id);
                $row = $db->getOne('tbl_users');
                if ($last_id) {
                    $_SESSION['success'] = 'Successfully uploaded';
                } else {
                    $_SESSION['failure'] = 'Insert failed!';
                }
            } else {
//                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";

            }
        }
    }
}

if(isset($_POST) && isset($_POST['avatar_fg']) && isset($_FILES["avatar_photo"]["name"])) {
    $db = getDbInstance();
    $data_to_db = array();
    if(isset($_FILES["avatar_photo"]["name"])) {
        $target_dir = "./uploads/" . $_SESSION['user_id'] . "/avatars/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  //create directory if not exist
        }
        $target_file = $target_dir . basename($_FILES["avatar_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["avatar_photo"]["tmp_name"]);

        if ($check !== false) {
//                echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
//                echo "File is not an image.";
            $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
//            echo "Sorry, file already exists.";
            $_SESSION['failure'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
//        // Check file size
        if ($_FILES["avatar_photo"]["size"] > 500000) {
//            echo "Sorry, your file is too large.";
            $_SESSION['failure'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
//         Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
//            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an failure
        if ($uploadOk == 0) {
//            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
//            echo "Sorry, your file was not uploaded.";
//            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["avatar_photo"]["tmp_name"], $target_file)) {
//                echo "The file ". basename( $_FILES["cover_photo"]["name"]). " has been uploaded.";
                $data_to_db['avatar'] = $target_file;
                $db->where('id', $logged_id);
                $last_id = $db->update('tbl_users', $data_to_db);
                $db = getDbInstance();
                $db->where('id', $logged_id);
                $row = $db->getOne('tbl_users');
                if ($last_id) {
                    $_SESSION['success'] = 'Successfully uploaded';
                } else {
                    $_SESSION['failure'] = 'Insert failed!';
                }
            } else {
//                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";

            }
        }
    }
}

?>

<?php include BASE_PATH.'/members/includes/header.php'?>

<!-- Cover Header Start -->
<?php if(isset($row['cover_photo'])) { ?>
<div class="cover--header pt--80 text-center" data-bg-img="<?php echo substr($row['cover_photo'],2) ?>" data-overlay="0.6" data-overlay-color="white">
<?php } else { ?>
    <div class="cover--header pt--80 text-center" data-bg-img="img/cover-header-img/bg-01.jpg" data-overlay="0.6" data-overlay-color="white">
<?php } ?>
    <div class="container">
        <div id="cover_photo_id_wrapper">
            <a data-control-name="edit_top_card" id="cover_photo_id" class="pv-top-card-section__edit artdeco-button artdeco-button--tertiary artdeco-button--circle ml1 pv-top-card-v2-section__edit ember-view">    <li-icon type="pencil-icon" role="img" aria-label="Edit Profile"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" data-supported-dps="24x24" fill="currentColor" focusable="false">
                        <path d="M21.71 5L19 2.29a1 1 0 00-.71-.29 1 1 0 00-.7.29L4 15.85 2 22l6.15-2L21.71 6.45a1 1 0 00.29-.74 1 1 0 00-.29-.71zM6.87 18.64l-1.5-1.5L15.92 6.57l1.5 1.5zM18.09 7.41l-1.5-1.5 1.67-1.67 1.5 1.5z"></path>
                    </svg></li-icon>
            </a>
            <div class="cover--avatar online" data-overlay="0.3" data-overlay-color="primary">
                <li-icon id="avatar_edit_btn" aria-hidden="true" type="pencil-icon" class="profile-photo-edit__edit-icon profile-photo-edit__edit-icon--for-top-card-v2" size="small"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" data-supported-dps="16x16" fill="currentColor" focusable="false">
                        <path d="M14.71 4L12 1.29a1 1 0 00-1.41 0L3 8.85 1 15l6.15-2 7.55-7.55a1 1 0 00.3-.74 1 1 0 00-.29-.71zm-8.84 7.6l-1.5-1.5 5.05-5.03 1.5 1.5zm5.72-5.72l-1.5-1.5 1.17-1.17 1.5 1.5z"></path>
                    </svg></li-icon>
                    <?php if(isset($row['avatar'])) { ?>
                        <img src="<?php echo substr($row['avatar'],2) ?>" alt="">
                    <?php } else { ?>
                        <img src="img/cover-header-img/avatar-01.jpg" alt="">
                    <?php } ?>

            </div>
        </div>

        <div class="cover--user-name">
            <h2 class="h3 fw--600"><?php echo $row['first_name'];?> <?php echo $row['last_name'] ?></h2>
        </div>

        <div class="cover--user-activity">
            <p><i class="fa mr--8 fa-clock-o"></i>Active 1 year 9 monts ago</p>
        </div>
        <?php include BASE_PATH . '/includes/flash_messages.php'; ?>

        <!--<div class="cover--user-desc fw--400 fs--18 fstyle--i text-darkest">
            <p>Hello everyone ! There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
        </div>-->
    </div>
</div>
<!-- Cover Header End -->
<?php include BASE_PATH . '/members/forms/profile_cover_modal.php';?>
<?php include BASE_PATH . '/members/forms/avatar_upload_modal.php';?>
<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">
            <!-- Main Content Start -->
            <div class="main--content col-md-8 pb--60" data-trigger="stickyScroll">
                <div class="main--content-inner drop--shadow">
                    <!-- Content Nav Start -->
                    <div class="content--nav pb--30">
                        <ul class="nav ff--primary fs--14 fw--500 bg-lighter">
                            <!--<li><a href="member-activity-personal.php">Activity</a></li>-->
                            <li class="active"><a href="member-profile.php">Profile</a></li>
                            <!-- <li><a href="member-friends.html">Friends</a></li>
                            <li><a href="member-groups.html">Groups</a></li>
                            <li><a href="member-forum-topics.html">Forum</a></li>
                            <li><a href="member-media-all.html">Media</a></li>-->
                        </ul>
                    </div>
                    <!-- Content Nav End -->

                    <!-- Profile Details Start -->
                    <div class="profile--details fs--14">
                        <!-- Profile Item Start -->
                        <div class="profile--item">
                            <div class="profile--heading">
                                <h3 class="h4 fw--700">
                                    <span class="mr--4">About Me</span>
                                    <i class="ml--10 text-primary fa fa-caret-right"></i>
                                </h3>
                            </div>
                            <form action="#" method="post">
                                <div class="profile--info">
                                    <table class="table">
                                        <tr>
                                            <th class="fw--700 text-darkest">First Name</th>
                                            <td><input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']?>" required></td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">Last Name</th>
                                            <td><input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']?>" required></td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">Date of Birth</th>
                                            <td><input type="date" name="birthday" value="<?php echo $row['birthday']?>" required>&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary">Update</button> </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <!-- Profile Item End -->
                        
                        <!-- Password Item Start -->
                        <div class="profile--item">
                            <div class="profile--heading">
                                <h3 class="h4 fw--700">
                                    <span class="mr--4">Create Credentials</span>
                                    <i class="ml--10 text-primary fa fa-caret-right"></i>
                                </h3>
                            </div>

                            <form action="#" method="post" onsubmit="return checkForm(this);">
                                <div class="profile--info">
                                    <table class="table">
                                        <tr>
                                            <th class="fw--700 text-darkest">User ID</th>
                                            <td><input type="text" name="user_name" class="form-control" required="required" value="<?php echo $row['user_name']?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">Password</th>
                                            <td><input type="password" name="password" required></td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">Confirm Password</th>
                                            <td><input type="password" name="rpassword" required>&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary">Update</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <!-- Password Item End -->
                        
                        <!-- Profile Item Start -->
                        <div class="profile--item">
                            <div class="profile--heading">
                                <h3 class="h4 fw--700">
                                    <span class="mr--4">Biography</span>
                                    <i class="ml--10 text-primary fa fa-caret-right"></i>
                                </h3>
                            </div>

                            <div class="profile--info">
                                <form action="#" method="post">
                                    <p>Optional - Add a brief personal description, such as hobbies, likes or dislikes, etc. It can be anything about yourself that you want to share with family and friends.</p>

                                    <p>
                                        <textarea class="col-xs-12" name="biography" required><?php echo $row['biography']; ?></textarea>
                                    </p>

                                    <button type="submit" class="btn btn-primary" style="float: right; margin: 5px;">Update</button>

                                </form>
                            </div><div>&nbsp;</div><div>&nbsp;</div>
                        </div>
                        <!-- Profile Item End -->
                        

                        <!-- Profile Item Start -->
                        <div class="profile--item">
                            <div class="profile--heading">
                                <h3 class="h4 fw--700">
                                    <span class="mr--4">Add a Family Relationship</span>
                                    <i class="ml--10 text-primary fa fa-caret-right"></i>
                                </h3>
                            </div>

                            <div class="profile--info">
                                <p>Optional - Add up to three family members now, or add family members to your Family Album later.</p>
                                <table class="table">
                                    <tr>
                                        <th class="fw--700 text-darkest">Relationship</th>
                                        <td>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>
                                                        <select name="family-member" class="form-control form-sm"
                                                            data-trigger="selectmenu">
                                                            <option value="family-member">*Select Family Relationship</option>
                                                            <option value="spouse">Husband</option>
                                                            <option value="spouse">Wife</option>
                                                            <option value="spouse">Significant Other</option>
                                                            <option value="mother">Mother</option>
                                                            <option value="father">Father</option>
                                                            <option value="sister">Sister</option>
                                                            <option value="brother">Brother</option>
                                                            <option value="brother">Aunt</option>
                                                            <option value="brother">Uncle</option>
                                                            <option value="brother">Niece</option>
                                                            <option value="brother">Nephew</option>
                                                            <option value="brother">Cousin</option>
                                                            <option value="maternal-grandmother">Grandmother
                                                            </option>
                                                            <option value="maternal-grandfather">Grandfather
                                                            </option>
                                                            <option value="more">Other</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw--700 text-darkest">First Name</th>
                                        <td><input type="text"></td>
                                    </tr>
                                    <tr>
                                        <th class="fw--700 text-darkest">Last Name</th>
                                        <td><input type="text"></td>
                                    </tr>
                                    </table>
                            </div>
                        </div>
                        <!-- Profile Item End -->

                        <!-- Profile Item Start -->
                        <div class="profile--item">
                            <div class="profile--heading">
                                <h3 class="h4 fw--700">
                                    <span class="mr--4">Add a Family Relationship</span>
                                    <i class="ml--10 text-primary fa fa-caret-right"></i>
                                </h3>
                            </div>

                            <div class="profile--info">
                                <p>Optional - Add up to three family members now, or add family members to your Family Album later.</p>
                                <table class="table">
                                    <tr>
                                        <th class="fw--700 text-darkest">Relationship</th>
                                        <td>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>
                                                        <select name="family-member" class="form-control form-sm"
                                                                data-trigger="selectmenu">
                                                            <option value="family-member">*Select Family Relationship</option>
                                                            <option value="spouse">Husband</option>
                                                            <option value="spouse">Wife</option>
                                                            <option value="spouse">Significant Other</option>
                                                            <option value="mother">Mother</option>
                                                            <option value="father">Father</option>
                                                            <option value="sister">Sister</option>
                                                            <option value="brother">Brother</option>
                                                            <option value="brother">Aunt</option>
                                                            <option value="brother">Uncle</option>
                                                            <option value="brother">Niece</option>
                                                            <option value="brother">Nephew</option>
                                                            <option value="brother">Cousin</option>
                                                            <option value="maternal-grandmother">Grandmother
                                                            </option>
                                                            <option value="maternal-grandfather">Grandfather
                                                            </option>
                                                            <option value="more">Other</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw--700 text-darkest">First Name</th>
                                        <td><input type="text"></td>
                                    </tr>
                                    <tr>
                                        <th class="fw--700 text-darkest">Last Name</th>
                                        <td><input type="text"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Profile Item End -->

                        <!-- Profile Item Start -->
                        <div class="profile--item">
                            <div class="profile--heading">
                                <h3 class="h4 fw--700">
                                    <span class="mr--4">Add a Family Relationship</span>
                                    <i class="ml--10 text-primary fa fa-caret-right"></i>
                                </h3>
                            </div>

                            <div class="profile--info">
                                <p>Optional - Add up to three family members now, or add family members to your Family Album later.</p>
                                <table class="table">
                                    <tr>
                                        <th class="fw--700 text-darkest">Relationship</th>
                                        <td>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>
                                                        <select name="family-member" class="form-control form-sm"
                                                                data-trigger="selectmenu">
                                                            <option value="family-member">*Select Family Relationship</option>
                                                            <option value="spouse">Husband</option>
                                                            <option value="spouse">Wife</option>
                                                            <option value="spouse">Significant Other</option>
                                                            <option value="mother">Mother</option>
                                                            <option value="father">Father</option>
                                                            <option value="sister">Sister</option>
                                                            <option value="brother">Brother</option>
                                                            <option value="brother">Aunt</option>
                                                            <option value="brother">Uncle</option>
                                                            <option value="brother">Niece</option>
                                                            <option value="brother">Nephew</option>
                                                            <option value="brother">Cousin</option>
                                                            <option value="maternal-grandmother">Grandmother
                                                            </option>
                                                            <option value="maternal-grandfather">Grandfather
                                                            </option>
                                                            <option value="more">Other</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw--700 text-darkest">First Name</th>
                                        <td><input type="text"></td>
                                    </tr>
                                    <tr>
                                        <th class="fw--700 text-darkest">Last Name</th>
                                        <td><input type="text"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Profile Item End -->

                    </div>
                    <!-- Profile Details End -->
                </div>
            </div> 
            <!-- Main Content End -->

            <!-- Main Sidebar Start -->
            
            <div class="main--sidebar col-md-4 pb--60" data-trigger="stickyScroll">
                <!-- Widget Start -->
                <div class="widget">
                    <h2 class="h4 fw--700 widget--title">Invite a Family Member</h2>
                    <form action="#" method="post" onsubmit="return checkFamilyForm(this);">
                        <p>First Name: <input type="text" name="firstname" required></p>
                        <p>Last Name: <input type="text" name="lastname" required></p>
                        <p>Email Address: <input type="email" name="family_email" required></p>
                        <div class="form-group">
                            <select name="family_member" class="form-control form-sm">
                                <option value="family_member">*Select Family Relationship</option>
                                <option value="Husband">Husband</option>
                                <option value="Wife">Wife</option>
                                <option value="Significant Other">Significant Other</option>
                                <option value="Mother">Mother</option>
                                <option value="Father">Father</option>
                                <option value="Sister">Sister</option>
                                <option value="Brother">Brother</option>
                                <option value="Aunt">Aunt</option>
                                <option value="Uncle">Uncle</option>
                                <option value="Niece">Niece</option>
                                <option value="Nephew">Nephew</option>
                                <option value="Cousin">Cousin</option>
                                <option value="Grandmother">Grandmother</option>
                                <option value="Grandfather">Grandfather</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-google btn btn-primary"><i class="fa mr--8 fa-play"></i>Send</button>
                        <!--                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(+) Invite another family member. -->
                    </form>
                </div>


                <div class="widget">
                    <form action="#" method="post" onsubmit="return checkFriendForm(this);">
                        <h2 class="h4 fw--700 widget--title">Invite a Friend</h2>
                        <p>First Name: <input type="text" name="fri_firstname" required></p>
                        <p>Last Name: <input type="text" name="fri_lastname" required></p>
                        <p>Email Address: <input type="email" name="friend_email" required></p>
                        <button type="submit" class="btn btn-sm btn-google btn btn-primary"><i class="fa mr--8 fa-play"></i>Send</button>
                            <!--                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(+) Invite another friend. -->
                    </form>
                </div>

                <!-- Widget End -->
            </div>

            <!-- Main Sidebar End -->
        </div>
    </div>
</section>
<!-- Page Wrapper End -->

<script>
    function checkForm(form)
    {
        if(form.user_name.value == "") {
            alert("Error: UserID cannot be blank!");
            form.user_name.focus();
            return false;
        }
//        re = /^\w+$/;
//        if(!re.test(form.user_name.value)) {
//            alert("Error: Username must contain only letters, numbers and underscores!");
//            form.username.focus();
//            return false;
//        }
        if(form.password.value != "" && form.password.value == form.rpassword.value) {
            if(!checkPassword(form.password.value)) {
                alert("The password you have entered is not valid!");
                form.password.focus();
                return false;
            }
        } else {
            alert("Error: Please check that you've entered and confirmed your password!");
            form.password.focus();
            return false;
        }
        return true;
    }

    function checkFamilyForm(form)
    {
        if(form.firstname.value == "") {
            alert("Error: first name cannot be blank!");
            form.firstname.focus();
            return false;
        }

        if(form.lastname.value == "") {
            alert("Error: last name cannot be blank!");
            form.lastname.focus();
            return false;
        }

        if(form.family_member.value == "family_member") {
            alert("Error: Please select family relationship!");
            form.family_member.focus();
            return false;
        }

        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(form.family_email.value)) {
            alert("Error: Email is not valid!");
            form.family_email.focus();
            return false;
        }
        return true;
    }

    function checkFriendForm(form)
    {
        if(form.fri_firstname.value == "") {
            alert("Error: first name cannot be blank!");
            form.firstname.focus();
            return false;
        }

        if(form.fri_lastname.value == "") {
            alert("Error: last name cannot be blank!");
            form.lastname.focus();
            return false;
        }

        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(form.friend_email.value)) {
            alert("Error: Email is not valid!");
            form.friend_email.focus();
            return false;
        }
        return true;
    }

</script>

<?php include BASE_PATH.'/members/includes/footer.php'?>