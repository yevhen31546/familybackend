<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

$db = getDbInstance();
$rows = $db->get('tbl_users');
$submitted_by_id = $_SESSION['user_id'];
$db->where('id', $submitted_by_id);
$submitted_by_user = $db->getOne('tbl_users')['first_name'].' '.$db->getOne('tbl_users')['last_name'];

$db = getDbInstance();
// $query = 'SELECT COUNT(rec_submit_by) cnt FROM tbl_recipes WHERE rec_submit_by = '.$submitted_by_id.' GROUP BY rec_submit_by';
// $available_photo = $db->rawQuery($query);
// $remain = $criteria_photos-$available_photo[0]['cnt'];
//print_r($available_photo[0]['cnt']);
//exit;

//Array ( [rec_title] => [rec_date] => 2019-10-21 [rec_submit_by] => 1 [rec_create_by] => [rec_type] => Array ( [0] => Breakfast [1] => Lunch [2] => Dinner ) [rec_ingredient] => [rec_instruction] => )
if(isset($_POST) && isset($_POST['rec_date']) && $_POST['rec_date'] != '') {
    $data_to_db = $_POST;
//    $data_to_db['rec_type'] = '';
//    if(isset($_POST['rec_type'])) {
//        foreach ($_POST['rec_type'] as $key => $item) {
//            if($key == 0) {
//                $data_to_db['rec_type'] .= $item;
//            } else {
//                $data_to_db['rec_type'] .= ','.$item;
//            }
//        }
//    }
    if(isset($_POST) && $_FILES["file"]["name"][0] !='') {
        $j = 0;     // Variable for indexing uploaded image.
        $target_path = "./uploads/".$_SESSION['user_id']."/"."recipes/";
        if (!file_exists($target_path)) {
            mkdir($target_path, 0777, true);  //create directory if not exist
        }
        $insertValuesSQL = '';

        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $target_path = "./uploads/".$_SESSION['user_id']."/"."recipes/";
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
                    // If file moved to uploads folder.
                    $insertValuesSQL .= $target_path.",";
                    // echo $j . ').<span id="noerror">Image uploaded successfully!.</span><br/><br/>';
                    $_SESSION['success'] = "Image uploaded successfully!.";
                } else {     //  If File Was Not Moved.
                    // echo $j . ').<span id="error">please try again!.</span><br/><br/>';
                    $_SESSION['failure'] = "***".$j." ) please try again!.***";
                }
            } else {     //   If File Size And File Type Was Incorrect.
                // echo $j . ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
                $_SESSION['failure'] = "***".$j." ) image is invalid size or type***";
            }
        }
        // echo $insertValuesSQL;exit;
        $data_to_db['rec_photo'] = rtrim($insertValuesSQL, ",");
    }


    $db = getDbInstance();
    $last_id = $db->insert('tbl_recipes', $data_to_db);

    if ($last_id)
    {
        $_SESSION['success'] = 'Recipe added successfully!';
        // Redirect to the Members page
        header('Location: '. BASE_URL .'/members/groups-recipes.php');
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
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/food.png" data-overlay="0.45">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Favorite Recipes</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">Groups</span></li>
        </ul>
    </div>
</div>
<!-- Page Header End -->
<?php include BASE_PATH . '/includes/flash_messages.php'; ?>
<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">
            <!-- Main Content Start -->
            <div class="main--content col-md-12 pb--60">
                <div class="main--content-inner">
                    <div><h2>Add Your Recipe to the Group</h2></div>
                    <form name="recipe-add-form" action="" method="post" enctype="multipart/form-data">
                        <div class="box--items-h">
                            <div class="row gutter--15 AdjustRow">
                                <div class="box--item text-center w-100">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="box--item text-left">
                                            <div><label><h3>Recipe Title:&nbsp;&nbsp;&nbsp;<input type="text" name="rec_title" required></h3></label></div></div>

                                        <div class="box--item text-left">
                                            <div><label><h6>Date:&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d');?></h6></label></div></div>
                                            <input type="hidden" name="rec_date" value="<?php echo date('Y-m-d');?>">

                                        <div class="box--item text-left">
                                            <div><label><h6>Submitted by:&nbsp;&nbsp;&nbsp;<?php echo $submitted_by_user;?></h6></label></div>
                                            <input type="hidden" name="rec_submit_by" value="<?php echo $submitted_by_id;?>"></div>

                                        <div class="box--item text-left">
                                            <p><h6>Enter the name of the person who created the recipe in the <strong>"Created by"</strong> box.</h6></p>
                                            <div><label><h6>Created by:&nbsp;&nbsp;&nbsp;<input type="text" name="rec_create_by" >&nbsp;&nbsp;&nbsp;</h6></label></div></div>

                                        <div class="box--item text-left">
                                            <p><label><h6>Select the applicable the type of recipe you are adding.</h6></label></p>

                                            <label>
                                                <select class="input-large" name="rec_type">
                                                    <option value="Breakfast">Breakfast</option>
                                                    <option value="Lunch">Lunch</option>
                                                    <option value="Dinner">Dinner</option>
                                                    <option value="Family Favorite">Family Favorite</option>
                                                    <option value="Gluten Free">Gluten Free</option>
                                                    <option value="Vegetarian">Vegetarian</option>
                                                </select>
<!--                                                <input type="checkbox" name="rec_type[]" value="Breakfast" id="RecipeType_0" >Breakfast-->
<!--                                                <input type="checkbox" name="rec_type[]" value="Lunch" id="RecipeType_1" >Lunch-->
<!--                                                <input type="checkbox" name="rec_type[]" value="Dinner" id="RecipeType_2" >Dinner-->
<!--                                                <input type="checkbox" name="rec_type[]" value="Dessert" id="RecipeType_3" >Dessert-->
<!--                                                <input type="checkbox" name="rec_type[]" value="Family Favorite" id="RecipeType_4" >Family Favorite-->
<!--                                                <input type="checkbox" name="rec_type[]" value="Gluten Free" id="RecipeType_5" >Gluten Free-->
<!--                                                <input type="checkbox" name="rec_type[]" value="Vegetarian" id="RecipeType_6" >Vegetarian-->
                                                <br>
                                            </label></div>

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
                                            <div><label><h6>Add the recipe ingredients.&nbsp;&nbsp;&nbsp;</h6></label>
                                                <textarea rows="4" cols="100%" name="rec_ingredient" placeholder="Enter text here..."></textarea>
                                            </div>
                                        </div>


                                        <div class="box--item text-left textareaw">
                                            <div><label><h6>Add the recipe instructions.&nbsp;&nbsp;&nbsp;</h6></label>
                                                <textarea rows="4" cols="100%" name="rec_instruction" placeholder="Enter text here..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a class="btn btn-primary" href="../members/groups-recipes.php">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>