<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once 'smtp_endpoint.php';

$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$row = $db->getOne('tbl_users');

//Get User list
$db = getDbInstance();
$user_list = $db->get('tbl_users');
/**
 * Family
 */
// Get all family member lists
$db = getDbInstance();
$get_family_query = 'SELECT us.user_name, us.first_name, us.last_name, fa.relation FROM tbl_users us JOIN (SELECT with_who, who, relation  FROM tbl_family WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who WHERE us.id!='.$logged_id;
$family_lists = $db->rawQuery($get_family_query);

// Get family members for auto fill box
$db = getDbInstance();
//$get_family_query = 'SELECT DISTINCT us.user_name,us.id FROM tbl_users us JOIN (SELECT DISTINCT with_who, who  FROM tbl_family WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who WHERE us.id != '.$logged_id;
$get_family_query = 'SELECT user_name from tbl_users';
$family_members = $db->rawQuery($get_family_query);
$families = [];
foreach($family_members as $family_member):
    array_push($families, $family_member['user_name']);
endforeach;

/**
 * Friend
 */
// Get all friend lists
$db = getDbInstance();
$get_friend_query = 'SELECT us.user_name, us.first_name, us.last_name FROM tbl_users us JOIN (SELECT with_who, who  FROM tbl_friend WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who WHERE us.id!='.$logged_id;
$friend_lists = $db->rawQuery($get_friend_query);

// Get friends for auto fill box
$db = getDbInstance();
//$get_friend_query = 'SELECT DISTINCT us.user_name,us.id FROM tbl_users us JOIN (SELECT DISTINCT with_who, who  FROM tbl_friend WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who WHERE us.id !='.$logged_id;
$get_friend_query = 'SELECT user_name FROM tbl_users';
$friends_ = $db->rawQuery($get_family_query);
$friends = [];
foreach($friends_ as $friend):
    array_push($friends, $friend['user_name']);
endforeach;

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
        $_POST = array();
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update profile: ' . $db->getLastError();
        $_POST = array();
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
        $_POST = array();
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update credentials: ' . $db->getLastError();
        $_POST = array();
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
        $_POST = array();
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update biography: ' . $db->getLastError();
        $_POST = array();
    }
}

// Invite family member
if(isset($_POST) && isset($_POST['family_member'])) {
    $myfamily = $_POST['myfamily']; // family user id
    $db = getDbInstance();
    $db->where('user_name', $myfamily);
    $family_user = $db->getOne('tbl_users');

    $relation = $_POST['family_member'];
    $to = $family_user['user_email'];

//    Request Save to tbl_family
    $data_to_db = array(
        'who' => $logged_id,
        'with_who' => $family_user['id'],
        'relation' => $relation
    );
    $db = getDbInstance();
    $family_id = $db->insert('tbl_family', $data_to_db);

    $body = generateFamMessageBody($row, $family_user, $relation, $family_id); // $row: from, $family_user: to, $relation: family relationship

    $stat = sendEmail($to, $body);
    if ($stat) {
        $_SESSION['success'] = 'Invitation email is sent successfully!';
        $_POST = array();
    } else {
        $_SESSION['failure'] = 'Sending invitation email is failed!';
        $_POST = array();
    }
}

// Invite friend
if(isset($_POST) && isset($_POST['myfriend'])) {
    $myfriend = $_POST['myfriend']; // friend id
    $db = getDbInstance();
    $db->where('user_name', $myfriend);
    $friend_user = $db->getOne('tbl_users');
    $to = $friend_user['user_email'];

    //    Request Save to tbl_family
    $data_to_db = array(
        'who' => $logged_id,
        'with_who' => $friend_user['id']
    );
    $db = getDbInstance();
    $friend_id = $db->insert('tbl_friend', $data_to_db);

    $body = generateFriMessageBody($row, $friend_user, $friend_id);
    $stat = sendEmail($to, $body);
    if ($stat) {
        $_SESSION['success'] = 'Invitation email is sent successfully!';
        $_POST = array();
    } else {
        $_SESSION['failure'] = 'Sending invitation email is failed!';
        $_POST = array();
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
                    $_POST = array();
                } else {
                    $_SESSION['failure'] = 'Insert failed!';
                    $_POST = array();
                }
            } else {
//                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
                $_POST = array();

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
                    $_POST = array();
                } else {
                    $_SESSION['failure'] = 'Insert failed!';
                    $_POST = array();
                }
            } else {
//                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
                $_POST = array();

            }
        }
    }
}

?>

<?php include BASE_PATH.'/members/includes/header.php'?>
    <style>
        /*the container must be positioned relative:*/
        .autocomplete {
            position: relative;
            display: inline-block;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
    </style>

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
                            <li class="active"><a href="member-profile.php">Profile</a></li>
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
                            <form action="" method="post">
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

                            <form action="" method="post" onsubmit="return checkForm(this);">
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
                                <form action="" method="post">
                                        <p>Optional - Add a brief personal description, such as hobbies, likes or dislikes, etc. It can be anything about yourself that you want to share with family and friends.</p>

                                    <div class="row">
                                        <textarea class="col-xs-12" name="biography" required><?php echo $row['biography']; ?></textarea>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary" style="float: right; margin: 5px;">Update</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <!-- Profile Item End -->

                        

                        <!-- Profile Item Start -->
<!--                        <div class="profile--item">-->
<!--                            <div class="profile--heading">-->
<!--                                <h3 class="h4 fw--700">-->
<!--                                    <span class="mr--4">Add a Family Relationship</span>-->
<!--                                    <i class="ml--10 text-primary fa fa-caret-right"></i>-->
<!--                                </h3>-->
<!--                            </div>-->
<!---->
<!--                            <div class="profile--info">-->
<!--                                <p>Optional - Add up to three family members now, or add family members to your Family Album later.</p>-->
<!--                                <table class="table">-->
<!--                                    <tr>-->
<!--                                        <th class="fw--700 text-darkest">Relationship</th>-->
<!--                                        <td>-->
<!--                                            <div class="col-xs-6">-->
<!--                                                <div class="form-group">-->
<!--                                                    <label>-->
<!--                                                        <select name="family-member" class="form-control form-sm"-->
<!--                                                            data-trigger="selectmenu">-->
<!--                                                            <option value="family-member">*Select Family Relationship</option>-->
<!--                                                            <option value="spouse">Husband</option>-->
<!--                                                            <option value="spouse">Wife</option>-->
<!--                                                            <option value="spouse">Significant Other</option>-->
<!--                                                            <option value="mother">Mother</option>-->
<!--                                                            <option value="father">Father</option>-->
<!--                                                            <option value="sister">Sister</option>-->
<!--                                                            <option value="brother">Brother</option>-->
<!--                                                            <option value="brother">Aunt</option>-->
<!--                                                            <option value="brother">Uncle</option>-->
<!--                                                            <option value="brother">Niece</option>-->
<!--                                                            <option value="brother">Nephew</option>-->
<!--                                                            <option value="brother">Cousin</option>-->
<!--                                                            <option value="maternal-grandmother">Grandmother-->
<!--                                                            </option>-->
<!--                                                            <option value="maternal-grandfather">Grandfather-->
<!--                                                            </option>-->
<!--                                                            <option value="more">Other</option>-->
<!--                                                        </select>-->
<!--                                                    </label>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <th class="fw--700 text-darkest">First Name</th>-->
<!--                                        <td><input type="text"></td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <th class="fw--700 text-darkest">Last Name</th>-->
<!--                                        <td><input type="text"></td>-->
<!--                                    </tr>-->
<!--                                    </table>-->
<!--                            </div>-->
<!--                        </div>-->
                        <!-- Profile Item End -->

                        <!-- Profile Item Start -->
                        <?php
                        foreach ($family_lists as $family):?>
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
                                                <?php echo $family['relation']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">First Name</th>
                                            <td><?php echo $family['first_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">Last Name</th>
                                            <td><?php echo $family['last_name']; ?></td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                        <?php
                        endforeach;
                        ?>
                        <!-- Profile Item End -->

                        <!-- Profile Item Start -->
                        <?php
                        foreach ($friend_lists as $friend):?>
                            <div class="profile--item">
                                <div class="profile--heading">
                                    <h3 class="h4 fw--700">
                                        <span class="mr--4">Friend Lists</span>
                                        <i class="ml--10 text-primary fa fa-caret-right"></i>
                                    </h3>
                                </div>
                                <div class="profile--info">
                                    <p>Optional - Add up to three friend now, or add friend to your friend Album later.</p>
                                    <table class="table">
                                        <tr>
                                            <th class="fw--700 text-darkest">First Name</th>
                                            <td><?php echo $friend['first_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="fw--700 text-darkest">Last Name</th>
                                            <td><?php echo $friend['last_name']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php
                        endforeach;
                        ?>
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
                    <form action="" autocomplete="off" method="post" onsubmit="return checkFamilyForm(this);">
                        <div class="autocomplete" style="width: 100%;">
                            <input id="myfamily" type="text" class="form-control" name="myfamily" placeholder="Family member's Name">
                        </div>
                        <div class="form-group" style="margin-top: 10px;">
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
                    </form>
                </div>


                <div class="widget">
                    <form action="" method="post" onsubmit="return checkFriendForm(this);">
                        <h2 class="h4 fw--700 widget--title">Invite a Friend</h2>
                        <div class="autocomplete" style="width: 100%;">
                            <input id="myfriend" type="text" class="form-control" name="myfriend" placeholder="Friend's Name">
                        </div>
                        <button type="submit" class="btn btn-sm btn-google btn btn-primary" style="margin-top: 20px;"><i class="fa mr--8 fa-play"></i>Send</button>
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

        if(form.myfamily.value === '') {
            alert("Error: Please type family member's name :(");
            form.myfamily.focus();
            return false;
        }

        if(form.family_member.value === "family_member") {
            alert("Error: Please select family relationship!");
            form.family_member.focus();
            return false;
        }

        return true;
    }

    function checkFriendForm(form)
    {
        if(form.myfriend.value === "") {
            alert("Error: Please type friend's name :(");
            form.myfriend.focus();
            return false;
        }

        return true;
    }

</script>

<script>
    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    var families = <?php print_r(json_encode($families)); ?>;
    var friends = <?php print_r(json_encode($friends)); ?>;
    console.log("families: ", families);
    console.log("friends: ", friends);

    autocomplete(document.getElementById("myfamily"), families);
    autocomplete(document.getElementById("myfriend"), friends);
</script>

<?php include BASE_PATH.'/members/includes/footer.php'?>