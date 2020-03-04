<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';

$db = getDbInstance();
$db->join('tbl_travel', 'tbl_users.id = tbl_travel.travelsubmitby');
$db->orderBy('traveldate');

$db->pageLimit = $page_per_num;
if (isset($_GET) && isset($_GET['page_num'])) {
    $page = $_GET['page_num'];
    if ($page < 1) {
        $page = 1;
    }
}
$rows = $db->paginate($tbl_name, $page);
$total = $db->totalCount;
$pages = $db->totalPages;

if ($page >= $pages) {
    $next_page = $pages;
} else {
    $next_page = $page + 1;
}
if ($page > 1) {
    $prev_page = $page - 1;
} else {
    $prev_page = $page;
}

if(isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
    $db = getDbInstance();
    $db->join('tbl_travel', 'tbl_users.id = tbl_travel.travelsubmitby');
    if (isset($_GET['groupfilter']) && $_GET['groupfilter'] === 'all') {
        if (isset($_GET['letter']) && $_GET['letter']) {
            $db->where('travelernames', $_GET['letter'].'%', 'LIKE');
        } else {
            $db->where(1);
        }
    } elseif (isset($_GET['letter']) && $_GET['letter'] && isset($_GET['groupfilter']) && $_GET['groupfilter']) {
        $db->where('travelgroup', $_GET['groupfilter'].'%', 'LIKE');
        $db->where('travelernames', $_GET['letter'].'%', 'LIKE');
    } elseif (isset($_GET['groupfilter']) && $_GET['groupfilter'] && empty($_GET['letter'])) {
        $db->where('travelgroup', $_GET['groupfilter'].'%', 'LIKE');
    } elseif(isset($_GET['letter']) && $_GET['letter'] && empty($_GET['groupfilter']) && !$_GET['groupfilter']) {
        $db->where('travelernames', $_GET['letter'].'%', 'LIKE');
    }
    $db->orderBy('traveldate');
    $rows = $db->get('tbl_users');
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
                            <form action="" method="GET" id="groupfilterform">
                            <label style="display: flex;">
                                <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                <select name="groupfilter" id="groupfilter" class="form-control form-sm" onchange="this.form.submit();" data-trigger="selectmenu">
                                    <option value="all" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'all') echo 'selected'; ?> >Most Current Added</option>
                                    <option value="Family" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Family') echo 'selected'; ?> >Family Vacation</option>
                                    <option value="Just-4-Fun" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Just-4-Fun') echo 'selected'; ?> >Just-4-Fun</option>
                                    <option value="Special" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Special') echo 'selected'; ?> >Special Occasion</option>
                                    <option value="Weekend" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Weekend') echo 'selected'; ?> >Weekend Getaway</option>
                                    <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                </select>
                            </label>

                            <div>
                                <?php
                                    foreach (range('A', 'Z') as $char) {
                                        if (isset($_GET['groupfilter'])) {
                                            echo '<a href='.BASE_URL.'/members/groups-travel.php?groupfilter='.
                                                $_GET['groupfilter'].'&&letter='
                                                .$char.'> '.$char.'</a> |';
                                        } else {
                                            echo '<a href='.BASE_URL.'/members/groups-travel.php?letter='.$char.'> '.$char.'</a> |';
                                        }
                                    }
                                ?>
                            </div>

                            </form>
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
                                            <p><a href="<?php echo $row['utubelink'] ?>" target="_blank"> See More </a></p>
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
                        <form method="get">
                            <label class="ff--primary fs--14 fw--500 text-darker">
                                <span>Viewing</span>

                                <a href="<?php echo BASE_URL.'/members/groups-travel.php?page_num='.$prev_page; ?>"
                                   class="btn-link"><i class="fa fa-caret-left"></i></a>
                                <input type="number" name="page_num" value="<?php echo $page; ?>"
                                       class="form-control form-sm">
                                <a href="<?php echo BASE_URL.'/members/groups-travel.php?page_num='.$next_page; ?>"
                                   class="btn-link"><i class="fa fa-caret-right"></i></a>

                                <span>of <?php echo $pages; ?></span>
                            </label>
                        </form>
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