<?php
session_start();
require_once '../config/config.php';

require_once BASE_PATH.'/includes/auth_validate.php';
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$row = $db->getOne('tbl_users');


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
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <link rel="stylesheet" href="css/plugins.min.css">
    
    <!-- ==== Main Stylesheet ==== -->
    <link rel="stylesheet" href="style.css">
    
    <!-- ==== Responsive Stylesheet ==== -->
    <link rel="stylesheet" href="css/responsive-style.css">
    
    <!-- ==== Color Scheme Stylesheet ==== -->
    <link rel="stylesheet" href="css/colors/color-1.css" id="changeColorScheme">
    
    <!-- ==== Custom Stylesheet ==== -->
    <link rel="stylesheet" href="css/custom.css">

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
                       <!-- <li class="dropdown">
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
                      <!--  <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-rss"></i></a></li>
                        <li><a href="#"><i class="fa fa-youtube"></i></a></li>-->
                    </ul>
                    <!-- Header Topbar Social End -->

                    <!-- Header Topbar Links Start -->
                    <ul class="header--topbar-links nav ff--primary float--right">
                       <!-- <li>
                            <a href="../cart.html" title="Cart" data-toggle="tooltip" data-placement="bottom">
                                <i class="fa fa-shopping-basket"></i>
                                <span class="badge">3</span>
                            </a>
                        </li>-->
                        <li>
                            <a href="#" class="btn-link">
                                <i class="fa mr--8 fa-user-o"></i>
                                <span>My Account</span>
                            </a>
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
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#headerNav">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- Header Navbar Logo Start -->
                        <div class="header--navbar-logo navbar-brand">
                            <a href="../members/home.php">
                                <img src="../members/blacklogosm.png" class="normal" alt="">
                                <img src="../members/whitelogosm.png" class="sticky" alt="">
                            </a>
                        </div>
                        <!-- Header Navbar Logo End -->
                    </div>

                    <div id="headerNav" class="navbar-collapse collapse float--right">
                       
                        <!-- Header Nav Links Start -->
                        <div id="headerNav" class="navbar-collapse collapse float--right">
                        <!-- Header Nav Links Start -->
                        <ul class="header--nav-links style--1 nav ff--primary">
                           
                            <li><a href="../members/home.php"><span>Home</span></a></li>

                          
                            
                            <li class="dropdown active">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span>Albums</span>
                                    <i class="fa fa-caret-down"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li class="active">
                                        <a href="activity-me.php"><span>My Album</span></a></li>
                                    <li><a href="activity-fam.html"><span>My Family</span></a></li>
                                    <li><a href="activity-frd.html"><span>My Friends</span></a></li>
                                  
                                </ul>
                            
                            
                            </li>
                                
                            <li><a href="members.php"><span>Members</span></a></li>
                                
                               <!-- </ul>-->
                            </li>
                        
                        
                        <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span>Groups</span>
                                    <i class="fa fa-caret-down"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a href="groups-church.html"><span>Church</span></a></li>
                                    <li><a href="groups-events.html"><span>Events</span></a></li>
                                    <li><a href="groups-homerepair.html"><span>Home Repairs</span></a></li>
                                    <li><a href="groups-pets.html"><span>Pets</span></a></li>
                                    <li><a href="groups-recipes.php"><span>Recipes</span></a></li>
                                    <li><a href="groups-sports.html"><span>Sports</span></a></li>
                                    <li><a href="groups-travel.html"><span>Travel</span></a></li>
                                
                                </ul>
                            </li>
                            
                            </li>
                            <li><a href="contact.html"><span>Contact</span></a></li>
                        </ul>
                        <!-- Header Nav Links End -->
                    </div>
                </div>
            </div>
            <!-- Header Navbar End -->
        </header>
        <!-- Header Section End -->

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
                    <h2 class="h3 fw--600">This should default to the person's name once they become a member.</h2>
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

                                    <div class="profile--info">
                                        <table class="table">
                                            <tr>
                                                <th class="fw--700 text-darkest">First Name</th>
                                                <td><input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']?>"></td>
                                            </tr>
                                            <tr>
                                                <th class="fw--700 text-darkest">Last Name</th>
                                                <td><input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']?>"></td>
                                            </tr>
                                            <tr>
                                                <th class="fw--700 text-darkest">Date of Birth</th>
                                                <td><input type="text"></td>
                                            </tr>
                                        </table>
                                    </div>
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

                                    <div class="profile--info">
                                        <table class="table">
                                            <tr>
                                                <th class="fw--700 text-darkest">User ID</th>
                                                <td><input type="text" name="user_name" class="form-control" required="required" value="<?php echo $row['user_name']?>"></td>
                                            </tr>
                                            <tr>
                                                <th class="fw--700 text-darkest">Password</th>
                                                <td><input type="text"></td>
                                            </tr>
                                            <tr>
                                                <th class="fw--700 text-darkest">Confirm Password</th>
                                                <td><input type="text"></td>
                                            </tr>
                                        </table>
                                    </div>
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
                                        <p>Optional - Add a brief personal description, such as hobbies, likes or dislikes, etc. It can be anything about yourself that you want to share with family and friends.</p>
                                        
                                        <p><textarea class="col-xs-12"></textarea></p></class>
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
                                                <td><div class="col-xs-6">
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
                                        </div></td>
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
                            
                             <div class="profile--item">
                                    <div class="profile--heading">
                                        <h3 class="h4 fw--700">
                                            <span class="mr--4">Add a Family Relationship</span>
                                            <i class="ml--10 text-primary fa fa-caret-right"></i>
                                        </h3>
                                    </div>

                                    <div class="profile--info">
                                        <p>Optional - Add a brief personal description, such as hobbies, likes or dislikes, etc. It can be anything you want to share with family and friends.</p>
                                        <table class="table">
                                            <tr>
                                                <th class="fw--700 text-darkest">Relationship</th>
                                                <td><div class="col-xs-6">
                                            <div class="form-group">
                                                <label>

                                                    <select name="family-member" class="form-control form-sm"
                                                        data-trigger="selectmenu">
                                                        <option value="family-member">*Select Family Relationship</option>
                                                        <option value="spouse1">Husband</option>
                                                        <option value="spouse2">Wife</option>
                                                        <option value="spouse3">Significant Other</option>
                                                        <option value="mother">Mother</option>
                                                        <option value="father">Father</option>
                                                        <option value="sister">Sister</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="aunt">Aunt</option>
                                                        <option value="uncle">Uncle</option>
                                                        <option value="niece">Niece</option>
                                                        <option value="nephew">Nephew</option>
                                                        <option value="cousin">Cousin</option>
                                                        <option value="maternal-grandmother">Grandmother
                                                        </option>
                                                        <option value="maternal-grandfather">Grandfather
                                                        </option>
                                                        <option value="more">Other</option>

                                                    </select>
                                                </label>
                                            </div>
                                        </div></td>
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
                            
                            <div class="profile--item">
                                    <div class="profile--heading">
                                        <h3 class="h4 fw--700">
                                            <span class="mr--4">Add a Family Relationship</span>
                                            <i class="ml--10 text-primary fa fa-caret-right"></i>
                                        </h3>
                                    </div>

                                    <div class="profile--info">
                                        <p>Optional - Add a brief personal description, such as hobbies, likes or dislikes, etc. It can be anything you want to share with family and friends.</p>
                                        <table class="table">
                                            <tr>
                                                <th class="fw--700 text-darkest">Relationship</th>
                                                <td><div class="col-xs-6">
                                            <div class="form-group">
                                                <label>

                                                    <select name="family-member" class="form-control form-sm"
                                                        data-trigger="selectmenu">
                                                        <option value="family-member">*Select Family Relationship</option>
                                                        <option value="spouse1">Husband</option>
                                                        <option value="spouse2">Wife</option>
                                                        <option value="spouse3">Significant Other</option>
                                                        <option value="mother">Mother</option>
                                                        <option value="father">Father</option>
                                                        <option value="sister">Sister</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="aunt">Aunt</option>
                                                        <option value="uncle">Uncle</option>
                                                        <option value="niece">Niece</option>
                                                        <option value="nephew">Nephew</option>
                                                        <option value="cousin">Cousin</option>
                                                        <option value="maternal-grandmother">Grandmother
                                                        </option>
                                                        <option value="maternal-grandfather">Grandfather
                                                        </option>
                                                        <option value="more">Other</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div></td>
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
                                <p>First Name: <input type="text"></p>
                                <p>Last Name: <input type="text"></p>
                                <p>Email Address: <input type="email"></p>
                                            <div class="form-group">
                                                <label>

                                                    <select name="family-member" class="form-control form-sm"
                                                        data-trigger="selectmenu">
                                                        <option value="family-member">*Select Family Relationship</option>
                                                        <option value="spouse1">Husband</option>
                                                        <option value="spouse2">Wife</option>
                                                        <option value="spouse3">Significant Other</option>
                                                        <option value="mother">Mother</option>
                                                        <option value="father">Father</option>
                                                        <option value="sister">Sister</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="aunt">Aunt</option>
                                                        <option value="uncle">Uncle</option>
                                                        <option value="niece">Niece</option>
                                                        <option value="nephew">Nephew</option>
                                                        <option value="cousin">Cousin</option>
                                                        <option value="maternal-grandmother">Grandmother
                                                        </option>
                                                        <option value="maternal-grandfather">Grandfather
                                                        </option>
                                                        <option value="more">Other</option>

                                                    </select>
                                                </label>
                                            </div>
                                <a href="#" class="btn btn-sm btn-google btn btn-primary"><i class="fa mr--8 fa-play"></i>Send</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(+) Invite another family member. 
                          
                        </div>
                         <div class="widget">
                        <h2 class="h4 fw--700 widget--title">Invite a Family Member</h2>
                                <p>First Name: <input type="text"></p>
                                <p>Last Name: <input type="text"></p>
                                <p>Email Address: <input type="email"></p>
                                <a href="#" class="btn btn-sm btn-google btn btn-primary"><i class="fa mr--8 fa-play"></i>Send</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(+) Invite another friend. 
                    </div>
                </div>
                
                    
                        <!-- Widget End -->
                
            
                
                
                    
                    <!-- Main Sidebar End -->
                </div>
            </div>
        </section>
        <!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>