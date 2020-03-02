<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
$db = getDbInstance();
$rows = $db->get('tbl_users');
$submitted_by_id = $_SESSION['user_id'];
$db->where('id', $submitted_by_id);
$submitted_by_user = $db->getOne('tbl_users')['first_name'] . ' ' . $db->getOne('tbl_users')['last_name'];

if(isset($_POST) && isset($_POST['sportdate']) && $_POST['sportdate'] != '') {
    $data_to_db = $_POST;
    if ($data_to_db['sportname']=='')
        $data_to_db['sportname'] = 'Aerobics';
    // // Multi image upload
    if(isset($_POST) && $_FILES["file"]["name"] != '') {
        $j = 0;     // Variable for indexing uploaded image.
        $target_path = "./uploads/".$_SESSION['user_id']."/sport/image/";
        if (!file_exists($target_path)) {
            mkdir($target_path, 0777, true);  //create directory if not exist
        }
        $insertValuesSQL = '';

        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $target_path = "./uploads/".$_SESSION['user_id']."/sport/image/";
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
        $data_to_db['sportphoto'] = rtrim($insertValuesSQL, ",");
    }

    
    // Video Upload
    
    if(isset($_POST) && $_FILES["videourl"]["name"] != '') {
        $target_path = "./uploads/".$_SESSION['user_id']."/sport/video/";
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
                    // $_SESSION['success'] = "Image uploaded successfully!.";

                }
            }
        }
        else
        {
            $_SESSION['failure'] = "Invalid file!.";
        }
    }

    $db = getDbInstance();
    $last_id = $db->insert('tbl_sport', $data_to_db);

    if ($last_id)
    {
        $_SESSION['success'] = 'sport added successfully!';
        // Redirect to the Members page
        header('Location: '. BASE_URL .'/members/groups-sports.php');
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
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/sport.png" data-overlay="0.25">
    <div class="container">
        <div class="title">
        <h2 class="h1 text-white">Sports Group</h2>
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
                        <h2>Add Your Sport Memories</h2>
                    </div>
                    <form name="recipe-add-form" action="" method="post" enctype="multipart/form-data">
                        <div class="box--items-h">
                            <div class="row gutter--15 AdjustRow">
                                <div class="box--item text-center w-100">
                                    <div class="col-md-9 col-xs-12">
                                        <div class="box--item text-left">

                                            <label>
                                                <span class="h4 fs--14 ff--primary fw--500 text-darker"><strong>Select a Sport :</strong></span>
								
                                                <select name="sportname" class="form-control form-sm" data-trigger="selectmenu">
                                                    <option value="Aerobics" selected>Aerobics</option>
                                                    <option value="Badminton">Badminton</option>
                                                    <option value="Ballet/Dance">Ballet/Dance</option>
                                                    <option value="Baseball">Baseball</option>
                                                    <option value="Basketball">Basketball</option>
                                                    <option value="Bowling">Bowling</option>
                                                    <option value="Boxing">Boxing</option>
                                                    <option value="Cheerleading">Cheerleading</option>
                                                    <option value="Cross Fit">Cross Fit</option>
                                                    <option value="Cycling">Cycling</option>
                                                    <option value="Diving">Diving</option>
                                                    <option value="Equestrian">Equestrian</option>
                                                    <option value="Fishing">Fishing</option>
                                                    <option value="Football">Football</option>
                                                    <option value="Golf">Golf</option>
                                                    <option value="Gymnastics">Gymnastics</option>
                                                    <option value="Hockey">Hockey</option>
                                                    <option value="Hunting">Hunting</option>
                                                    <option value="Jump Roping">Jump Roping</option>
                                                    <option value="Karate">Karate</option>
                                                    <option value="Lacrosse">Lacrosse</option>
                                                    <option value="Marathons">Marathons</option>
                                                    <option value="Martial Arts">Martial Arts</option>
                                                    <option value="Motor Sports">Motor Sports</option>
                                                    <option value="Other">Other</option>
                                                    <option value="Parachuting">Parachuting</option>
                                                    <option value="Running">Running</option>
                                                    <option value="Skating">Skating</option>
                                                    <option value="Skiing">Skiing</option>
                                                    <option value="Snow Boarding">Snow Boarding</option>
                                                    <option value="Soccer">Soccer</option>
                                                    <option value="Softball">Softball</option>
                                                    <option value="Swimming">Swimming</option>
                                                    <option value="Target Shooting">Target Shooting</option>
                                                    <option value="Tee-Ball">Tee-Ball</option>
                                                    <option value="Tennis">Tennis</option>
                                                    <option value="Track and Field">Track and Field</option>
                                                    <option value="Triathlon">Triathlon</option>
                                                    <option value="Volleyball">Volleyball</option>
                                                    <option value="Weightlifting">Weightlifting</option>
                                                    <option value="Wrestling">Wrestling</option>
                                                    <option value="Yoga">Yoga</option>
                                                    <option value="Zumba">Zumba</option>
                                                </select>
											</label>

                                        </div>

                                        <div class="box--item text-left">
                                            <p><h6>Enter the name of the team, if applicable.</h6></p>
                                            <div><label><h6>Team Name:&nbsp;&nbsp;&nbsp;<input type="text" name="sportteamname">&nbsp;&nbsp;&nbsp;</h6></label></div></div>

                                        <div class="box--item text-left">
											<p><h6>Enter the name of the person involved in the sport in the box below.</h6></p>
                                            <div><label><h6>Player's Name:&nbsp;&nbsp;&nbsp;<input type="text" name="sportperson">&nbsp;&nbsp;&nbsp;</h6></label></div></div>

                                        <div class="box--item text-left">
                                            <label>
                                                <h6>Date Submitted:&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d');?></h6>
                                                <input type="hidden" name="sportdate" value="<?php echo date('Y-m-d');?>">
                                            </label>
                                        </div>

                                        <div class="box--item text-left">
                                            <div><label>
                                                    <div><label><h6>Submitted by:&nbsp;&nbsp;&nbsp;<?php echo $submitted_by_user;?></h6></label></div>
                                                    <input type="hidden" name="sportsubmitby" value="<?php echo $submitted_by_id;?>">
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
                                                <textarea rows="4" cols="100%" name="sportcomment" placeholder="Enter text here..."></textarea>
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
                            <a class="btn btn-primary" href="../members/groups-sports.php">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>