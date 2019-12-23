<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_travel', 'tbl_users.id = tbl_travel.travelsubmitby');
$db->orderBy('traveldate');
$rows = $db->get('tbl_users');

if(isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
    if(isset($_GET['groupfilter'])){
        $filter_val = $_GET['groupfilter'];        
        $db = getDbInstance();
        $db->join('tbl_travel', 'tbl_users.id = tbl_travel.travelsubmitby');
        $db->where('travelgroup', '%'.$filter_val.'%', 'LIKE');
        $db->orderBy('traveldate');
        $rows = $db->get('tbl_users');
    }

    if(isset($_GET['letter'])) {
        $search_param = $_GET['letter'];
        $db = getDbInstance();
        $db->join('tbl_travel', 'tbl_users.id = tbl_travel.travelsubmitby');
        $db->where('travelname', $search_param.'%', 'LIKE');
        $db->orderBy('traveldate');
        $rows = $db->get('tbl_users');
    }
}
?>

<?php include BASE_PATH . '/members/includes/header.php' ?>

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/travel.png" data-overlay="0.25">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Travel Group</h2>
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
                            <h2 class="h4"><a href="groups-travel-add.php">Add a travels Memory (+)</a></h2>
                        </div>

                        <div class="filter--options float--right">
                            <label style="display: flex;">
                                <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                <form action="" method="GET" id="groupfilterform">
                                    <select name="groupfilter" id="groupfilter" class="form-control form-sm" onchange="this.form.submit();" data-trigger="selectmenu">
                                        <option value="Family Vacation" selected>Family Vacation</option>
                                        <option value="Just-4-Fun" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Just-4-Fun') echo 'selected'; ?> >Just-4-Fun</option>
                                        <option value="Special Occasion" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Special Occasion') echo 'selected'; ?> >Special Occasion</option>
                                        <option value="Weekend Getaway" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Weekend Getaway') echo 'selected'; ?> >Weekend Getaway</option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                    </select>

                                </form>
                            </label>


                            <div>
                                <form action="" method="post" name="search" onclick="submit">
                                <?php
                                    foreach (range('A', 'Z') as $char) {
                                        echo '<a href='.BASE_URL.'/members/groups-travel.php?letter='.$char.'> '.$char.'</a> |';
                                    }
                                ?>
                                </form>
                            </div>
                        </div>
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
                                <a href="groups-travel-large.php?userid=<?php echo $row['travelsubmitby'];?>&&travelid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                    <?php if($row['travelphoto'] !='') { 
                                        $img_arr = explode(",", $row['travelphoto']);  
                                        ?>
                                        <img src="<?php echo $img_arr[0]; ?>" alt="">
                                    <?php } else { ?>
                                        <img src="../members/img/add_photo.png" alt="">
                                    <?php } ?>
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-plane"></i>
                                    </div>

                                    <div class="title">
                                        <h4 class="color">Travel Group</h4>
                                        <p><h6>Travel Group Name: <?php echo $row['travelgroup'] ?></h6></p>
                                    </div>

                                    <div class="desc text-darker">
                                        <!-- <p>Date of Travel: xxxxxxxxxxxxxxxxxxxx</p> -->
                                        <p>Name of Travelers: <?php echo $row['travelernames'] ?></p>
                                        <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                        <p>Date Submited: <?php echo $row['traveldate'];?></p>
                                        <p><?php echo $row['travelcomment'];?></p>
                                        <?php if ($row['utubelink'] != '') {?>
                                            <p><a href="<?php echo $row['utubelink'] ?>" target="_blank"> Youtube link </a></p>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <!-- Box Item End -->
                        </div>
                        <?php endforeach; ?>
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