<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
$db = getDbInstance();
$rows = $db->get('tbl_users');
$submitted_by_id = $_SESSION['user_id'];
$db->where('id', $submitted_by_id);
$submitted_by_user = $db->getOne('tbl_users')['first_name'] . ' ' . $db->getOne('tbl_users')['last_name'];

if(isset($_POST) && isset($_POST['churchname']) && $_POST['churchname'] != '') {
    $data_to_db = $_POST;
    if ($data_to_db['churchgroup']=='')
        $data_to_db['churchgroup'] = 'Adults/Family';
    // // Multi image upload
    if(isset($_POST) && $_FILES["file"]["name"][0] != '') {
        $j = 0;     // Variable for indexing uploaded image.
        $target_path = "./uploads/".$_SESSION['user_id']."/church/image/";
        if (!file_exists($target_path)) {
            mkdir($target_path, 0777, true);  //create directory if not exist
        }
        $insertValuesSQL = '';

        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $target_path = "./uploads/".$_SESSION['user_id']."/church/image/";
            // Loop to get individual element from the array
            $validextensions = array("jpeg", "jpg", "png");      // Extensions which are allowed.
            $ext = explode('.', basename($_FILES['file']['name'][$i]));   // Explode file name from dot(.)
            $file_extension = end($ext); // Store extensions in the variable.
            $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];     // Set the target path with a new name of image.
            $j = $j + 1;      // Increment the number of uploaded images according to the files in array.
            if (($_FILES["file"]["size"][$i] < 2000000)     // Approx. 2MB files can be uploaded.
                && in_array($file_extension, $validextensions)
            ) {
                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
                    $insertValuesSQL .= $target_path.",";
                    $_SESSION['success'] = "Image uploaded successfully!.";
                } else {     //  If File Was Not Moved.
                    $_SESSION['failure'] = "***".$j." ) please try again!.***";
                }
            } else {     //   If File Size And File Type Was Incorrect.
                $_SESSION['failure'] = "***".$j." ) image is invalid size or type***";
            }
        }
        // echo $insertValuesSQL;exit;
        $data_to_db['churchphoto'] = rtrim($insertValuesSQL, ",");
    }

    
    // Video Upload
    
    if(isset($_POST) && $_FILES["videourl"]["name"] != '') {
        $target_path = "./uploads/".$_SESSION['user_id']."/church/video/";
        echo $_FILES["videourl"]["name"]; exit;
        if (!file_exists($target_path)) {
            mkdir($target_path, 0777, true);  //create directory if not exist
        }
        
        $allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
        $extension = pathinfo($_FILES['videourl']['name'], PATHINFO_EXTENSION);
        $target_path = $target_path . md5(uniqid()).".".$extension;

        if ((($_FILES["videourl"]["type"] == "video/mp4")
        || ($_FILES["videourl"]["type"] == "audio/mp3")
        || ($_FILES["videourl"]["type"] == "audio/wma")
        || ($_FILES["videourl"]["type"] == "image/pjpeg")
        || ($_FILES["videourl"]["type"] == "image/gif")
        || ($_FILES["videourl"]["type"] == "image/jpeg")
        || ($_FILES["videourl"]["type"] == "image/png"))

        && ($_FILES["videourl"]["size"] < 2000000)
        && in_array($extension, $allowedExts))
        {
            if ($_FILES["videourl"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["videourl"]["error"] . "<br />";
            }
            else
            {
                // echo "Upload: " . $_FILES["videourl"]["name"] . "<br />";
                // echo "Type: " . $_FILES["videourl"]["type"] . "<br />";
                // echo "Size: " . ($_FILES["videourl"]["size"] / 1024) . " Kb<br />";
                // echo "Temp file: " . $_FILES["videourl"]["tmp_name"] . "<br />";

                if (move_uploaded_file($_FILES['videourl']['tmp_name'], $target_path)) {
                    $data_to_db['videourl'] = $target_path;
                    $_SESSION['success'] = "Image uploaded successfully!.";
                }
            }
        }
        else
        {
            $_SESSION['failure'] = "Invalid file!.";
        }
    }


    $db = getDbInstance();
    $last_id = $db->insert('tbl_church', $data_to_db);

    if ($last_id)
    {
        $_SESSION['success'] = 'Church added successfully!';
        // Redirect to the Members page
        header('Location: '. BASE_URL .'/members/groups-church.php');
        // Important! Don't execute the rest put the exit/die.
    }
    else
    {
        $_SESSION['failure'] = 'Inert DB error'.$db->getLastError();
    }

    
}
?>


<?php include BASE_PATH.'/members/includes/header.php'?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="./js/multiimage.js"></script>
<!------- Including CSS File ------>
<link rel="stylesheet" type="text/css" href="./css/multiimage.css">

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/jesus3.png" data-overlay="0.15">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Church Groups</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">Groups</span></li>
        </ul>
    </div>
</div>
<!-- Page Header End -->

<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">
            <!-- Main Content Start -->
            <div class="main--content col-md-12 pb--60">
                <div class="main--content-inner">

                    <div>
                        <h2>Add Your Church Group Memory</h2>
                    </div>
                    <form name="recipe-add-form" action="" method="post" enctype="multipart/form-data">
                        <div class="box--items-h">
                            <div class="row gutter--15 AdjustRow">
                                <div class="box--item text-center w-100" id="image-add">
                                    <div class="col-md-9 col-xs-12 left-right-auto">
                                        <div class="box--item text-left">
                                            <label>
                                                <span class="h4 fs--14 ff--primary fw--500 text-darker"><strong>Select a Group :</strong></span>

                                                <select name="churchgroup" id="churchgroup" class="input-medium" data-trigger="selectmenu">
                                                    <option value="Adults/Family" selected>Adults/Family</option>
                                                    <option value="Bible Study">Bible Study</option>
                                                    <option value="Children's Church">Children's Church</option>
                                                    <option value="Choir/Worship">Choir/Worship</option>
                                                    <option value="College/Career Group">College/Career Group</option>
                                                    <option value="Home Group">Home Group</option>
                                                    <option value="Prayer">Prayer</option>
                                                    <option value="Seniors">Seniors</option>
                                                    <option value="Singles">Singles</option>
                                                    <option value="Youth Group">Youth Group</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </label>
                                        </div>

                                        <div class="box--item text-left">
                                            <div>
                                                <label>
                                                    <h6>If your group name was not listed in the drop down, enter it here:&nbsp;&nbsp;&nbsp;
                                                        <input type="text" name="churchgroup">
                                                        &nbsp;&nbsp;&nbsp;
                                                    </h6>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="box--item text-left">
                                            <div>
                                                <label>
                                                    <h6>Enter the name of your church :&nbsp;&nbsp;&nbsp;
                                                        <input type="text" name="churchname" required>&nbsp;&nbsp;&nbsp;</h6>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="box--item text-left">
                                            <label>
                                                <h6>Date Submitted:&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d');?></h6>
                                                <input type="hidden" name="churchdate" value="<?php echo date('Y-m-d');?>">
                                            </label>
                                        </div>

                                        <div class="box--item text-left">
                                            <div><label>
                                                    <div><label><h6>Submitted by:&nbsp;&nbsp;&nbsp;<?php echo $submitted_by_user;?></h6></label></div>
                                                    <input type="hidden" name="churchsubmitby" value="<?php echo $submitted_by_id;?>">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="box--item text-left row">
                                            <div id="maindiv">
                                                <div id="formdiv">
                                                    First Field is Compulsory. Only JPEG,PNG,JPG Type Image Uploaded. Image Size Should Be Less Than 100KB.
                                                    <div id="filediv">
                                                        <input name="file[]" type="file" id="file" />
                                                        <input type="button" id="add_more" class="upload" value="Add More Files" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php include BASE_PATH . '/includes/flash_messages.php'; ?>

                                        <div class="box--item text-left textareaw">
                                            <div>
                                                <label>
                                                    <h6>Add a Comment.&nbsp;&nbsp;&nbsp;</h6>
                                                </label>
                                                <textarea class="w-100" rows="4" cols="100%" name="churchcomment" placeholder="Enter text here..."></textarea>
                                            </div>
                                        </div>


                                        <div class="box--item text-left textareaw">
                                            <div>
                                                <label>
                                                    <h6>Add a video.&nbsp;&nbsp;&nbsp;</h6>
                                                </label>
                                                <input type="file" name="videourl">
                                            </div>
                                        </div>

                                        <div class="box--item text-left textareaw">
                                            <div><label>
                                                    <h6>Add a link such as, YouTube, Facebook, Twitter, etc.&nbsp;&nbsp;&nbsp;</h6>
                                                </label>
                                                <input type="text" class="form-control" name="utubelink" placeholder="Enter the link here">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a class="btn btn-primary" href="../members/groups-church.php">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>