<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_recipes', 'tbl_users.id = tbl_recipes.rec_submit_by');
$db->orderBy('rec_date');
$rows = $db->get('tbl_users');

// $photo_arr = $rows[0]['rec_photo'];
// $photo_arr = rtrim($photo_arr, ",");
// $str_arr = explode(",", $photo_arr);  
// print_r($str_arr);
// exit;

if(isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
    if(isset($_GET['groupfilter'])){
        $filter_val = $_GET['groupfilter'];
        if($filter_val == 'last-active') {
            $db = getDbInstance();
            $db->join('tbl_recipes', 'tbl_users.id = tbl_recipes.rec_submit_by');
            $db->orderBy('rec_date');
            $rows = $db->get('tbl_users');
        }
        else {
            $db = getDbInstance();
            $db->join('tbl_recipes', 'tbl_users.id = tbl_recipes.rec_submit_by');
            $db->where('rec_type', '%'.$filter_val.'%', 'LIKE');
            $db->orderBy('rec_date');
            $rows = $db->get('tbl_users');
        }
    }

    if(isset($_GET['letter'])) {
        $search_param = $_GET['letter'];
        $db = getDbInstance();
        $db->join('tbl_recipes', 'tbl_users.id = tbl_recipes.rec_submit_by');
        $db->where('rec_type', $search_param.'%', 'LIKE');
        $db->orWhere('rec_title', $search_param.'%', 'LIKE');
        $db->orderBy('rec_date');
        $rows = $db->get('tbl_users');
    }
}


?>



<?php include BASE_PATH.'/members/includes/header.php'?>

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

<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">
            <!-- Main Content Start -->
            <div class="main--content col-md-12 pb--60">
                <div class="main--content-inner">
                    <!-- Filter Nav Start -->
                    <div class="filter--nav pb--30 clearfix">
                        <div class="filter--link float--left">
                            <h2 class="h4"><a href="groups-recipes-add.php">Add a Recipe (+)</a></h2>
                        </div>

                        <div class="filter--options float--right">
                            <label style="display: flex;">
                                <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                <form action="" method="GET" id="groupfilterform">
                                    <select name="groupfilter" id="groupfilter" class="form-control form-sm" onchange="this.form.submit();" data-trigger="selectmenu">
                                        <option value="last-active" selected>Most Current Added</option>
                                        <option value="Breakfast" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Breakfast') echo 'selected'; ?> >Breakfast</option>
                                        <option value="Lunch" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Lunch') echo 'selected'; ?> >Lunch</option>
                                        <option value="Dinner" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Dinner') echo 'selected'; ?> >Dinner</option>
                                        <option value="Dessert" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Dessert') echo 'selected'; ?> >Dessert</option>
                                        <option value="Family Favorite" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Family Favorite') echo 'selected'; ?> >Family Favorite</option>
                                        <option value="Gluten Free" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Gluten Free') echo 'selected'; ?> >Gluten Free</option>
                                        <option value="Vegetarian" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Vegetarian') echo 'selected'; ?> >Vegetarian</option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                    </select>

                                </form>
                            </label>

                            <div>
                                <form action="" method="post" name="search" onclick="submit">
                                <?php

                                foreach (range('A', 'Z') as $char) {
                                    echo '<a href='.BASE_URL.'/members/groups-recipes.php?letter='.$char.'> '.$char.'</a> |';
                                }
                                ?>
                                </form>
                            </div> Hari - a user can select to sort by recipe type, or the title alphabetically
                        </div>
                    </div>
                    <!-- Filter Nav End -->
                    
                    <!-- Box Items Start -->
                    <div class="box--items-h">
                        <div class="row gutter--15 AdjustRow">
                            <?php foreach ($rows as $row):?>
                                <div class="col-md-4 col-xs-6 col-xxs-12">
                                    <!-- Box Item Start -->
                                    <div class="box--item text-center">
                                        <a href="groups-recipes-large.php?userid=<?php echo $row['rec_submit_by'];?>&&receipeid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                            <?php if($row['rec_photo'] !='') { 
                                                $img_arr = explode(",", $row['rec_photo']);  
                                                ?>
                                                <img src="<?php echo $img_arr[0]; ?>" width="800px;" height="418px;" alt="">
                                            <?php } else { ?>
                                                <img src="../members/img/recipe800x419.png" alt="">
                                            <?php } ?>
                                        </a>

                                        <div class="info">
                                            <div class="icon fs--18 text-lightest bg-primary">
                                                <i class="fa fa-cutlery"></i>
                                            </div>

                                            <div class="title">
                                                <h2 class="h4"><a href="groups-recipes-large.php?recipe=<?php echo $row['id'];?>"><?php echo $row['rec_title'];?></a></h2>
                                                <p><h6>Recipe Type: <?php echo $row['rec_type'];?></h6></p>
                                            </div>

                                            <div class="desc text-darker">
                                                <p>Created by: <?php echo $row['rec_create_by'];?></p>
                                                <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                                <p>Date: <?php echo $row['rec_date'];?></p>
                                                <p>Recipe Ingredients: <?php echo $row['rec_ingredient'];?></p>
                                                <p>Recipe Instructions: <?php echo $row['rec_instruction'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Box Item End -->
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <!-- Page Count Start -->
                    <div class="page--count pt--30">
                        <label class="ff--primary fs--14 fw--500 text-darker">
                            <span>Viewing</span>

                            <a href="#" class="btn-link"><i class="fa fa-caret-left"></i></a>
                            <input type="number" name="page-count" value="01" class="form-control form-sm">
                            <a href="#" class="btn-link"><i class="fa fa-caret-right"></i></a>

                            <span>of 28</span>
                        </label>
                    </div>
                    <!-- Page Count End -->
                </div>
            </div>
            <!-- Main Content End -->
        </div>
    </div>
</section>
<!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>