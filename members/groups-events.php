<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';

$db = getDbInstance();
$db->join('tbl_event', 'tbl_users.id = tbl_event.eventsubmitby');
$db->orderBy('eventdate');

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
    $db->join('tbl_event', 'tbl_users.id = tbl_event.eventsubmitby');
    if (isset($_GET['groupfilter']) && $_GET['groupfilter'] === 'all') {
        if (isset($_GET['letter']) && $_GET['letter']) {
            $db->where('eventname', $_GET['letter'].'%', 'LIKE');
        } else {
            $db->where(1);
        }
    } elseif (isset($_GET['letter']) && $_GET['letter'] && isset($_GET['groupfilter']) && $_GET['groupfilter']) {
        $db->where('eventgroup', $_GET['groupfilter'].'%', 'LIKE');
        $db->where('eventname', $_GET['letter'].'%', 'LIKE');
    } elseif (isset($_GET['groupfilter']) && $_GET['groupfilter'] && empty($_GET['letter'])) {
        $db->where('eventgroup', $_GET['groupfilter'].'%', 'LIKE');
    } elseif(isset($_GET['letter']) && $_GET['letter'] && empty($_GET['groupfilter'])) {
        $db->where('eventname', $_GET['letter'].'%', 'LIKE');
    }
    $db->orderBy('eventdate');
    $rows = $db->get('tbl_users');
}

?>

<?php include BASE_PATH.'/members/includes/header.php'?>
<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/bg3.png" data-overlay="0.45">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Special Events</h2>
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
                            <h2 class="h4"><a href="groups-events-add.php">Add an Event (+)</a></h2>
                        </div>

                        <div class="filter--options float--right">
                            <form action="" method="GET" id="groupfilterform">
                                <label style="display: flex;">
                                    <span class="fs--14 ff--primary fw--500 text-darker">Find an Event :</span>

                                    <select name="groupfilter" id="groupfilter" class="form-control form-sm"  onchange="this.form.submit();" data-trigger="selectmenu">
                                        <option value="all" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'all') echo 'selected'; ?> >Most Current Added</option>
                                        <option value="Anniversaries" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Anniversaries') echo 'selected'; ?> >Anniversaries</option>
                                        <option value="Baby" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Baby') echo 'selected'; ?> >Baby Showers</option>
                                        <option value="Bachelor" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Bachelor') echo 'selected'; ?> >Bachelor Parties</option>
                                        <option value="Birthday" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Birthday') echo 'selected'; ?> >Birthday</option>
                                        <option value="Bridal" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Bridal') echo 'selected'; ?> >Bridal Showers</option>
                                        <option value="Concerts" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Concerts') echo 'selected'; ?> >Concerts</option>
                                        <option value="Graduations" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Graduations') echo 'selected'; ?> >Graduations</option>
                                        <option value="Parties" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Parties') echo 'selected'; ?> >Parties</option>
                                        <option value="Weddings" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Weddings') echo 'selected'; ?> >Weddings</option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                    </select>
                                </label>
                                <div>
                                    <?php
                                    foreach (range('A', 'Z') as $char) {
                                        if (isset($_GET['groupfilter'])) {
                                            echo '<a href='.BASE_URL.'/members/groups-events.php?groupfilter='.
                                                $_GET['groupfilter'].'&&letter='
                                                .$char.'> '.$char.'</a> |';
                                        } else {
                                            echo '<a href='.BASE_URL.'/members/groups-events.php?letter='.$char.'> '.$char.'</a> |';
                                        }
                                    }
                                    ?>
                                </div>
                            </form>
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
                                    <a href="groups-events-large.php?userid=<?php echo $row['eventsubmitby'];?>&&eventid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                        <?php if($row['eventphoto'] !='') { 
                                            $img_arr = explode(",", $row['eventphoto']);  
                                            ?>
                                            <img src="<?php echo $img_arr[0]; ?>" alt="">
                                        <?php } else { ?>
                                            <img src="../members/img/add_photo.png" alt="">
                                        <?php } ?>
                                    </a>

                                    <div class="info">
                                        <div class="icon fs--18 text-lightest bg-primary">
                                            <i class="fa fa-birthday-cake"></i>
                                        </div>

                                        <div class="title">
                                            <h4 class="color"><?php echo $row['eventgroup'] ?></h4>
                                            <p><h6>Event Theme: <?php echo $row['eventname'] ?></h6></p>
                                        </div>

                                        <div class="desc text-darker">
                                            <p>Event Held For: <?php echo $row['eventgroup'] ?></p>
                                            <!-- <p>Actual Event Date: xxxxxxxxxxxxxxxxxxxx</p> -->
                                            <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                            <p>Date Submited: <?php echo $row['eventdate'];?></p>
                                            <p><?php echo $row['eventcomment'];?></p>
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

                                    <a href="<?php echo BASE_URL.'/members/groups-events.php?page_num='.$prev_page; ?>"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>
                                    <input type="number" name="page_num" value="<?php echo $page; ?>"
                                           class="form-control form-sm">
                                    <a href="<?php echo BASE_URL.'/members/groups-events.php?page_num='.$next_page; ?>"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>

                                    <span>of <?php echo $pages; ?></span>
                                </label>
                            </form>
                        </div>
                        <!-- Page Count End -->
                            
                    </div>
                    <!-- Main Content End -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Wrapper End -->
<?php include BASE_PATH.'/members/includes/footer.php'?>