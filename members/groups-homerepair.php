<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';

$db = getDbInstance();
$db->join('tbl_homerepair', 'tbl_users.id = tbl_homerepair.homerepairsubmitby');
$db->orderBy('homerepairdate');

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
    $db->join('tbl_homerepair', 'tbl_users.id = tbl_homerepair.homerepairsubmitby');
    if (isset($_GET['groupfilter']) && $_GET['groupfilter'] === 'all') {
        if (isset($_GET['letter']) && $_GET['letter']) {
            $db->where('homerepairgroup', $_GET['letter'].'%', 'LIKE');
        } else {
            $db->where(1);
        }
    } elseif (isset($_GET['letter']) && $_GET['letter'] && isset($_GET['groupfilter']) && $_GET['groupfilter']) {
        $db->where('homerepairgroup', $_GET['groupfilter'].'%', 'LIKE');
        $db->where('homerepairgroup', $_GET['letter'].'%', 'LIKE');
    } elseif (isset($_GET['groupfilter']) && $_GET['groupfilter'] && empty($_GET['letter'])) {
        $db->where('homerepairgroup', $_GET['groupfilter'].'%', 'LIKE');
    } elseif(isset($_GET['letter']) && $_GET['letter'] && empty($_GET['groupfilter'])) {
        $db->where('homerepairgroup', $_GET['letter'].'%', 'LIKE');
    }
    $db->orderBy('homerepairdate');
    $rows = $db->get('tbl_users');
}

?>

<?php include BASE_PATH . '/members/includes/header.php' ?>

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/repair.png" data-overlay="0.45">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Home Remodeling &amp; Repair Group</h2>
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
                            <h2 class="h4"><a href="groups-homerepair-add.php">Add a remodel or repair memory (+)</a></h2>
                        </div>

                        <div class="filter--options float--right">
                            <label style="display: flex;">
                                <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                <form action="" method="GET" id="groupfilterform">

                                    <select name="groupfilter" id="groupfilter" class="input-medium" onchange="this.form.submit();" data-trigger="selectmenu">
                                        <option value="all" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'all') echo 'selected'; ?> >Most Current Added</option>
                                        <option value="Bathroom" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Bathroom') echo 'selected'; ?> >Bathroom</option>
                                        <option value="Bedroom" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Bedroom') echo 'selected'; ?> >Bedroom</option>
                                        <option value="Entertainment" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == "Entertainment") echo 'selected'; ?> >Entertainment Room</option>
                                        <option value="Kitchen" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Kitchen') echo 'selected'; ?> >Kitchen</option>
                                        <option value="Living" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Living') echo 'selected'; ?> >Living Room</option>
                                        <option value="Nursery" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Nursery') echo 'selected'; ?> >Nursery Room</option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                    </select>

                                </form>
                            </label>


                            <div>
                                <form action="" method="post" name="search" onclick="submit">
                                <?php
                                    foreach (range('A', 'Z') as $char) {
                                        echo '<a href='.BASE_URL.'/members/groups-homerepair.php?letter='.$char.'> '.$char.'</a> |';
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
                                <a href="groups-homerepair-large.php?userid=<?php echo $row['homerepairsubmitby'];?>&&homerepairid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                    <?php if($row['homerepairphoto'] !='') { 
                                        $img_arr = explode(",", $row['homerepairphoto']);  
                                        ?>
                                        <img src="<?php echo $img_arr[0]; ?>" alt="">
                                    <?php } else { ?>
                                        <img src="../members/img/add_photo.png" alt="">
                                    <?php } ?>
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-paint-brush"></i>
                                    </div>

                                    <div class="title">
                                        <h4 class="color">Remodel &amp; Repair Group</h4>
                                        <p><h6>Group Name: <?php echo $row['homerepairgroup'] ?></h6></p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                        <p>Date Submited: <?php echo $row['homerepairdate'];?></p>
                                        <p><?php echo $row['homerepaircomment'];?></p>
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

                                <a href="<?php echo BASE_URL.'/members/groups-homerepair.php?page_num='.$prev_page; ?>"
                                   class="btn-link"><i class="fa fa-caret-left"></i></a>
                                <input type="number" name="page_num" value="<?php echo $page; ?>"
                                       class="form-control form-sm">
                                <a href="<?php echo BASE_URL.'/members/groups-homerepair.php?page_num='.$next_page; ?>"
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