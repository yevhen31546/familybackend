<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';

$db = getDbInstance();
$db->join('tbl_sport', 'tbl_users.id = tbl_sport.sportsubmitby');
$db->orderBy('sportdate');

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
    $db->join('tbl_sport', 'tbl_users.id = tbl_sport.sportsubmitby');
    if(isset($_GET['groupfilter'])){
        $filter_val = $_GET['groupfilter'];
        if ($filter_val === 'all') {
            $db->where(1);
        } else {
            $db->where('sportname', '%'.$filter_val.'%', 'LIKE');
        }
        if (isset($_GET['team_name_filter']) && $_GET['team_name_filter']) {
            $db->where('sportteamname', '%'.$_GET['team_name_filter'].'%', 'LIKE');
        }
        if (isset($_GET['player_name_filter']) && $_GET['player_name_filter']) {
            $db->where('sportperson', '%'.$_GET['player_name_filter'].'%', 'LIKE');
        }
    }
    if(isset($_GET['letter'])) {
        $search_param = $_GET['letter'];
        $db->where('sportname', $search_param.'%', 'LIKE');
    }
    $db->orderBy('sportdate');
    $rows = $db->get('tbl_users');
}
?>

<?php include BASE_PATH . '/members/includes/header.php' ?>

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/sport.png" data-overlay="0.25">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Sports</h2>
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
                            <h2 class="h4"><a href="groups-sports-add.php">Add a Sports Memory (+)</a></h2>
                        </div>

                        <div class="filter--options float--right">
                            <form method="GET">
                                <label style="display: flex;">
                                    <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Sport :</span>
                                    <select name="groupfilter" class="form-control form-sm" data-trigger="selectmenu">
                                        <option value="all" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'all') echo 'selected'; ?> >
                                            All
                                        </option>
                                        <option value="Aerobics" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Aerobics') echo 'selected'; ?> >
                                            Aerobics
                                        </option>
                                        <option value="Badminton" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Badminton') echo 'selected'; ?> >
                                            Badminton
                                        </option>
                                        <option value="Ballet/Dance" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Ballet/Dance') echo 'selected'; ?> >
                                            Ballet/Dance
                                        </option>
                                        <option value="Baseball" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Baseball') echo 'selected'; ?> >
                                            Baseball
                                        </option>
                                        <option value="Basketball" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Basketball') echo 'selected'; ?> >
                                            Basketball
                                        </option>
                                        <option value="Bowling" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Bowling') echo 'selected'; ?> >
                                            Bowling
                                        </option>
                                        <option value="Boxing" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Boxing') echo 'selected'; ?> >
                                            Boxing
                                        </option>
                                        <option value="Cheerleading" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Cheerleading') echo 'selected'; ?> >
                                            Cheerleading
                                        </option>
                                        <option value="Cross" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Cross') echo 'selected'; ?> >
                                            Cross Fit
                                        </option>
                                        <option value="Cycling" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Cycling') echo 'selected'; ?> >
                                            Cycling
                                        </option>
                                        <option value="Diving" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Diving') echo 'selected'; ?> >
                                            Diving
                                        </option>
                                        <option value="Equestrian" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Equestrian') echo 'selected'; ?> >
                                            Equestrian
                                        </option>
                                        <option value="Fishing" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Fishing') echo 'selected'; ?> >
                                            Fishing
                                        </option>
                                        <option value="Football" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Football') echo 'selected'; ?> >
                                            Football
                                        </option>
                                        <option value="Golf" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Golf') echo 'selected'; ?> >
                                            Golf
                                        </option>
                                        <option value="Gymnastics" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Gymnastics') echo 'selected'; ?> >
                                            Gymnastics
                                        </option>
                                        <option value="Hockey" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Hockey') echo 'selected'; ?> >
                                            Hockey
                                        </option>
                                        <option value="Hunting" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Hunting') echo 'selected'; ?> >
                                            Hunting
                                        </option>
                                        <option value="Jump" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Jump') echo 'selected'; ?> >
                                            Jump Roping
                                        </option>
                                        <option value="Karate" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Karate') echo 'selected'; ?> >
                                            Karate
                                        </option>
                                        <option value="Lacrosse" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Lacrosse') echo 'selected'; ?> >
                                            Lacrosse
                                        </option>
                                        <option value="Marathons" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Marathons') echo 'selected'; ?> >
                                            Marathons
                                        </option>
                                        <option value="Martial" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Martial') echo 'selected'; ?> >
                                            Martial Arts
                                        </option>
                                        <option value="Motor" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Motor') echo 'selected'; ?> >
                                            Motor Sports
                                        </option>
                                        <option value="Parachuting" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Parachuting') echo 'selected'; ?> >
                                            Parachuting
                                        </option>
                                        <option value="Running" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Running') echo 'selected'; ?> >
                                            Running
                                        </option>
                                        <option value="Skating" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Skating') echo 'selected'; ?> >
                                            Skating
                                        </option>
                                        <option value="Skiing" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Skiing') echo 'selected'; ?> >
                                            Skiing
                                        </option>
                                        <option value="Snow" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Snow') echo 'selected'; ?> >
                                            Snow Boarding
                                        </option>
                                        <option value="Soccer" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Soccer') echo 'selected'; ?> >
                                            Soccer
                                        </option>
                                        <option value="Softball" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Softball') echo 'selected'; ?> >
                                            Softball
                                        </option>
                                        <option value="Swimming" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Swimming') echo 'selected'; ?> >
                                            Swimming
                                        </option>
                                        <option value="Target" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Target') echo 'selected'; ?> >
                                            Target Shooting
                                        </option>
                                        <option value="Tee-Ball" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Tee-Ball') echo 'selected'; ?> >
                                            Tee-Ball
                                        </option>
                                        <option value="Tennis" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Tennis') echo 'selected'; ?> >
                                            Tennis
                                        </option>
                                        <option value="Track" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Track') echo 'selected'; ?> >
                                            Track and Field
                                        </option>
                                        <option value="Triathlon" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Triathlon') echo 'selected'; ?> >
                                            Triathlon
                                        </option>
                                        <option value="Volleyball" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Volleyball') echo 'selected'; ?> >
                                            Volleyball
                                        </option>
                                        <option value="Weightlifting" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Weightlifting') echo 'selected'; ?> >
                                            Weightlifting
                                        </option>
                                        <option value="Wrestling" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Wrestling') echo 'selected'; ?> >
                                            Wrestling
                                        </option>
                                        <option value="Yoga" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Yoga') echo 'selected'; ?> >
                                            Yoga
                                        </option>
                                        <option value="Zumba" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Zumba') echo 'selected'; ?> >
                                            Zumba
                                        </option>
                                        <option value="Other" <?php if(isset($_GET['sport_name_filter']) &&
                                            $_GET['sport_name_filter'] == 'Other') echo 'selected'; ?> >
                                            Other
                                        </option>
                                    </select>
                                    <span class="h4 fs--14 ff--primary fw--500 text-darker" style="padding-left: 10px;">
                                        Find a Team :
                                    </span>
                                    <input type="text" name="team_name_filter"
                                           value="<?php if (isset($_GET['team_name_filter']))
                                               echo $_GET['team_name_filter']; ?>">
                                    <span class="h4 fs--14 ff--primary fw--500 text-darker" style="padding-left: 10px;">
                                        Find a Player :
                                    </span>
                                    <input type="text" name="player_name_filter"
                                           value="<?php if (isset($_GET['player_name_filter']))
                                               echo $_GET['player_name_filter']; ?>">
                                    <button type="submit" class="btn btn-primary" style="padding-left: 10px;
                                        margin-left: 10px;padding-top: 1px; padding-bottom: 1px; padding-right: 10px;">
                                        Filter
                                    </button>
                                </label>
                            </form>

                            <div style="margin-top: 10px; float: right;">
                                <form action="" method="post" name="search" onclick="submit">
                                <?php
                                    foreach (range('A', 'Z') as $char) {
                                        echo '<a href='.BASE_URL.'/members/groups-sports.php?letter='.$char.'> '.$char.'</a> |';
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
                                <a href="groups-sports-large.php?userid=<?php echo $row['sportsubmitby'];?>&&sportid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                    <?php if($row['sportphoto'] !='') { 
                                        $img_arr = explode(",", $row['sportphoto']);  
                                        ?>
                                        <img src="<?php echo $img_arr[0]; ?>" alt="">
                                    <?php } else { ?>
                                        <img src="../members/img/add_photo.png" alt="">
                                    <?php } ?>
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-soccer-ball-o"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h4"><a href="group-home.php">Sport Group</a></h2>
                                        <p><h6>Sport Name: <?php echo $row['sportname'] ?></h6></p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Team Name: <?php echo $row['sportteamname'];?></p>
                                        <p>Player's Name: <?php echo $row['sportperson'];?></p>
                                        <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                        <p>Date Submited: <?php echo $row['sportdate'];?></p>
                                        <p><?php echo $row['sportcomment'];?></p>
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

                                <a href="<?php echo BASE_URL.'/members/groups-sports.php?page_num='.$prev_page; ?>"
                                   class="btn-link"><i class="fa fa-caret-left"></i></a>
                                <input type="number" name="page_num" value="<?php echo $page; ?>"
                                       class="form-control form-sm">
                                <a href="<?php echo BASE_URL.'/members/groups-sports.php?page_num='.$next_page; ?>"
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