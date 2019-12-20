<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_recipes', 'tbl_users.id = tbl_recipes.rec_submit_by');
$db->orderBy('rec_date');
$rows = $db->get('tbl_users');

if(isset($_GET) && (isset($_GET['recipefilter']) || isset($_GET['letter']))) {
    if(isset($_GET['recipefilter'])){
        $filter_val = $_GET['recipefilter'];
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
        $db->where('rec_title', $search_param.'%', 'LIKE');
        $db->orderBy('rec_date');
        $rows = $db->get('tbl_users');
    }
}


?>



<?php include BASE_PATH.'/members/includes/header.php'?>
<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="img/page-header-img/bg.jpg" data-overlay="0.85">
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
                            <label>
                                <span class="h4 ""fs--14 ff--primary fw--500 text-darker">Find a Recipe :</span>
                                <form action="" method="GET">

                                    <select name="recipefilter" id="recipefilter" class="input-medium" onchange="this.form.submit();">
                                        <option value="last-active" selected>Most Current Added</option>
                                        <option value="Breakfast" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Breakfast') echo 'selected'; ?> >Breakfast</option>
                                        <option value="Lunch" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Lunch') echo 'selected'; ?> >Lunch</option>
                                        <option value="Dinner" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Dinner') echo 'selected'; ?> >Dinner</option>
                                        <option value="Dessert" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Dessert') echo 'selected'; ?> >Dessert</option>
                                        <option value="Family Favorite" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Family Favorite') echo 'selected'; ?> >Family Favorite</option>
                                        <option value="Gluten Free" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Gluten Free') echo 'selected'; ?> >Gluten Free</option>
                                        <option value="Vegetarian" <?php if(isset($_GET['recipefilter']) && $_GET['recipefilter'] == 'Vegetarian') echo 'selected'; ?> >Vegetarian</option>
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
                            <div class="col-md-12 col-xs-12 col-xxs-12">
                                <!-- Box Item Start -->
                               <div class="box--item text-center">
                                   <!--  <a href="group-home.html" class="img" data-overlay="0.1">
                                        <img src="img/group-img/01.jpg" alt="">
                                    </a>

                                    <div class="info">
                                        <div class="icon fs--18 text-lightest bg-primary">
                                            <i class="fa fa-cutlery"></i>
                                        </div>

                                        <div class="title">
                                            <h2><a href="#">Title of Receipt</a></h2>
                                        </div>

                                        <div class="meta">
                                            <!--<p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                            <p><i class="fa mr--8 fa-user-o"></i>Public Group / 2500 Members</p>
                                          <p><h4>Submitted by&nbsp;:&nbsp;Auto Populate Name</h4> </p>
                                        <p><strong>Receipt Type(s)&nbsp;:&nbsp;</strong>auto populate from add receipt type function, can be more than one receipt type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Added&nbsp;:&nbsp;auto populate from add receipt page</p>
                                        </div>-->

        <!--    <div class="desc text-darker">
              <p>HARI - FUNCTIONALITY - This is the most current receipt added according to the Receipt Type drop down box at the top. Most current added is the default value for the dropdown. Values are: Breakfast, Lunch, Dinner, Desserts, Family Favorite, Vegetarian and Gluten Free. The other receipts displayed on the page with show in the smaller boxes. When a receipt box is selected from below, it will replace the receipt that is displayed in this box.</p>
                                        </div>-->


                                    </div>
                                </div>
                                <!-- Box Item End -->


                            <?php foreach ($rows as $row):?>
                                <div class="col-md-4 col-xs-6 col-xxs-12">
                                    <!-- Box Item Start -->
                                    <div class="box--item text-center">
                                        <a href="groups-recipes-large.php?userid=<?php echo $row['rec_submit_by'];?>" class="img" data-overlay="0.1">
                                            <img src="<?php echo $row['rec_photo']; ?>" width="800px;" height="419px;" alt="">
                                        </a>

                                        <div class="info">
                                            <div class="icon fs--18 text-lightest bg-primary">
                                                <i class="fa fa-cutlery"></i>
                                            </div>

                                            <div class="title">
                                                <h2 class="h4"><a href="groups-recipes-large.php?recipe=<?php echo $row['id'];?>"><?php echo $row['rec_title'];?></a></h2>
                                                <p><h6><?php echo $row['rec_type'];?></h6></p>
                                            </div>

                                            <div class="desc text-darker">
                                                <p><?php echo $row['rec_create_by'];?></p>
                                                <p><?php echo $row['first_name'].$row['last_name'];?></p>
                                                <p><?php echo $row['rec_date'];?></p>
                                                <p><?php echo $row['rec_ingredient'];?></p>
                                                <p><?php echo $row['rec_instruction'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Box Item End -->
                                </div>
                            <?php endforeach;?>
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