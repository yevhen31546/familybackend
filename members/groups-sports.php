<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Init variables
$user_id = $_SESSION['user_id'];
$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';
$upload_path_seg = '/sport';
$update_field = 'sportphoto';
$target_tbl = 'tbl_sport';
$join_field = 'sportsubmitby';
$filter_letter = 'sportname';
$group_filter = 'sportname';
$order_field = 'sportdate';
$order_field2 = 'id';

$db = getDbInstance();
$db->pageLimit = $page_per_num;

// Add additional photo
if (isset($_POST) && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $origin_photos = $_POST['origin_photos'];

    // Add photo
    $target_path = "./uploads/".$_SESSION['user_id'].$upload_path_seg."/image/";
    if (!file_exists($target_path)) {
        mkdir($target_path, 0777, true);
    }
    $valid_extensions = array("jpeg", "jpg", "png");      // Extensions which are allowed.
    $ext = explode('.', basename($_FILES['post_photo']['name']));   // Explode file name from dot(.)
    $file_extension = end($ext); // Store extensions in the variable.
    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1]; // Set the target path with a new name of image.
    if (($_FILES["post_photo"]["size"] < 2000000)     // Approx. 2MB files can be uploaded.
        && in_array($file_extension, $valid_extensions)
    ) {
        if (move_uploaded_file($_FILES['post_photo']['tmp_name'], $target_path)) {
            if ($origin_photos === '') {
                $value = $target_path;
            } else {
                $value = $origin_photos.','.$target_path;
            }

            // Update photos url
            $update_value = array($update_field => $value);
            $db->where('id', $post_id);
            $result = $db->update($target_tbl, $update_value);

            if ($result) {
                $bell_count++;
                $_SESSION['success'] = "Additional photo is added successfully!.<hr>";
            } else {
                $bell_count++;
                $_SESSION['failure'] = 'Update error'.$db->getLastError().'<hr>';
            }

        } else {     //  If File Was Not Moved.
            $bell_count++;
            $_SESSION['failure'] = "Please try again!<hr>";
        }
    } else {     //   If File Size And File Type Was Incorrect.
        $bell_count++;
        $_SESSION['failure'] = "Photo is invalid size or type(jpg, png, jpeg)<hr>";
    }
}

if (isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
    $page = $_GET['page_num'];
    $db->join($target_tbl, $tbl_name.'.id = '.$target_tbl.'.'.$join_field);
    if (isset($_GET['groupfilter']) && $_GET['groupfilter'] === 'all') {
        if (isset($_GET['letter']) && $_GET['letter']) {
            $db->where($filter_letter, $_GET['letter'].'%', 'LIKE');
        } else {
            $db->where(1);
        }
    } elseif (isset($_GET['letter']) && $_GET['letter'] && isset($_GET['groupfilter']) && $_GET['groupfilter']) {
        $db->where($group_filter, $_GET['groupfilter'].'%', 'LIKE');
        $db->where($filter_letter, $_GET['letter'].'%', 'LIKE');
    } elseif (isset($_GET['groupfilter']) && $_GET['groupfilter'] && empty($_GET['letter'])) {
        $db->where($group_filter, $_GET['groupfilter'].'%', 'LIKE');
    } elseif(isset($_GET['letter']) && $_GET['letter'] && empty($_GET['groupfilter'])) {
        $db->where($filter_letter, $_GET['letter'].'%', 'LIKE');
    }

    if (isset($_GET['team_name_filter']) && $_GET['team_name_filter']) {
        $db->where('sportteamname', '%'.$_GET['team_name_filter'].'%', 'LIKE');
    }
    if (isset($_GET['player_name_filter']) && $_GET['player_name_filter']) {
        $db->where('sportperson', '%'.$_GET['player_name_filter'].'%', 'LIKE');
    }
    $db->orderBy($order_field)->orderBy($target_tbl.'.'.$order_field2);

    $rows = $db->paginate($tbl_name, $page);
    $total = $db->totalCount;
    $pages = $db->totalPages;
} else {
    $db->join($target_tbl, $tbl_name.'.id = '.$target_tbl.'.'.$join_field);
    $db->orderBy($order_field)->orderBy($target_tbl.'.'.$order_field2);

    if (isset($_GET['page_num']) && $_GET['page_num']) {
        $page = $_GET['page_num'];
        if ($page < 1) {
            $page = 1;
        }
    }

    $rows = $db->paginate($tbl_name, $page);
    $total = $db->totalCount;
    $pages = $db->totalPages;
}

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

// origin filter
//if(isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
//    $db = getDbInstance();
//    $db->join('tbl_sport', 'tbl_users.id = tbl_sport.sportsubmitby');
//    if(isset($_GET['groupfilter'])){
//        $filter_val = $_GET['groupfilter'];
//        if ($filter_val === 'all') {
//            $db->where(1);
//        } else {
//            $db->where('sportname', '%'.$filter_val.'%', 'LIKE');
//        }
//        if (isset($_GET['team_name_filter']) && $_GET['team_name_filter']) {
//            $db->where('sportteamname', '%'.$_GET['team_name_filter'].'%', 'LIKE');
//        }
//        if (isset($_GET['player_name_filter']) && $_GET['player_name_filter']) {
//            $db->where('sportperson', '%'.$_GET['player_name_filter'].'%', 'LIKE');
//        }
//    }
//    if(isset($_GET['letter'])) {
//        $search_param = $_GET['letter'];
//        $db->where('sportname', $search_param.'%', 'LIKE');
//    }
//    $db->orderBy('sportdate');
//    $rows = $db->get('tbl_users');
//}

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
                                        <option value="all" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'all') echo 'selected'; ?> >
                                            All
                                        </option>
                                        <option value="Aerobics" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Aerobics') echo 'selected'; ?> >
                                            Aerobics
                                        </option>
                                        <option value="Badminton" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Badminton') echo 'selected'; ?> >
                                            Badminton
                                        </option>
                                        <option value="Ballet/Dance" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Ballet/Dance') echo 'selected'; ?> >
                                            Ballet/Dance
                                        </option>
                                        <option value="Baseball" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Baseball') echo 'selected'; ?> >
                                            Baseball
                                        </option>
                                        <option value="Basketball" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Basketball') echo 'selected'; ?> >
                                            Basketball
                                        </option>
                                        <option value="Bowling" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Bowling') echo 'selected'; ?> >
                                            Bowling
                                        </option>
                                        <option value="Boxing" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Boxing') echo 'selected'; ?> >
                                            Boxing
                                        </option>
                                        <option value="Cheerleading" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Cheerleading') echo 'selected'; ?> >
                                            Cheerleading
                                        </option>
                                        <option value="Cross" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Cross') echo 'selected'; ?> >
                                            Cross Fit
                                        </option>
                                        <option value="Cycling" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Cycling') echo 'selected'; ?> >
                                            Cycling
                                        </option>
                                        <option value="Diving" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Diving') echo 'selected'; ?> >
                                            Diving
                                        </option>
                                        <option value="Equestrian" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Equestrian') echo 'selected'; ?> >
                                            Equestrian
                                        </option>
                                        <option value="Fishing" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Fishing') echo 'selected'; ?> >
                                            Fishing
                                        </option>
                                        <option value="Football" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Football') echo 'selected'; ?> >
                                            Football
                                        </option>
                                        <option value="Golf" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Golf') echo 'selected'; ?> >
                                            Golf
                                        </option>
                                        <option value="Gymnastics" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Gymnastics') echo 'selected'; ?> >
                                            Gymnastics
                                        </option>
                                        <option value="Hockey" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Hockey') echo 'selected'; ?> >
                                            Hockey
                                        </option>
                                        <option value="Hunting" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Hunting') echo 'selected'; ?> >
                                            Hunting
                                        </option>
                                        <option value="Jump" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Jump') echo 'selected'; ?> >
                                            Jump Roping
                                        </option>
                                        <option value="Karate" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Karate') echo 'selected'; ?> >
                                            Karate
                                        </option>
                                        <option value="Lacrosse" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Lacrosse') echo 'selected'; ?> >
                                            Lacrosse
                                        </option>
                                        <option value="Marathons" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Marathons') echo 'selected'; ?> >
                                            Marathons
                                        </option>
                                        <option value="Martial" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Martial') echo 'selected'; ?> >
                                            Martial Arts
                                        </option>
                                        <option value="Motor" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Motor') echo 'selected'; ?> >
                                            Motor Sports
                                        </option>
                                        <option value="Parachuting" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Parachuting') echo 'selected'; ?> >
                                            Parachuting
                                        </option>
                                        <option value="Running" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Running') echo 'selected'; ?> >
                                            Running
                                        </option>
                                        <option value="Skating" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Skating') echo 'selected'; ?> >
                                            Skating
                                        </option>
                                        <option value="Skiing" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Skiing') echo 'selected'; ?> >
                                            Skiing
                                        </option>
                                        <option value="Snow" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Snow') echo 'selected'; ?> >
                                            Snow Boarding
                                        </option>
                                        <option value="Soccer" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Soccer') echo 'selected'; ?> >
                                            Soccer
                                        </option>
                                        <option value="Softball" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Softball') echo 'selected'; ?> >
                                            Softball
                                        </option>
                                        <option value="Swimming" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Swimming') echo 'selected'; ?> >
                                            Swimming
                                        </option>
                                        <option value="Target" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Target') echo 'selected'; ?> >
                                            Target Shooting
                                        </option>
                                        <option value="Tee-Ball" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Tee-Ball') echo 'selected'; ?> >
                                            Tee-Ball
                                        </option>
                                        <option value="Tennis" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Tennis') echo 'selected'; ?> >
                                            Tennis
                                        </option>
                                        <option value="Track" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Track') echo 'selected'; ?> >
                                            Track and Field
                                        </option>
                                        <option value="Triathlon" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Triathlon') echo 'selected'; ?> >
                                            Triathlon
                                        </option>
                                        <option value="Volleyball" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Volleyball') echo 'selected'; ?> >
                                            Volleyball
                                        </option>
                                        <option value="Weightlifting" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Weightlifting') echo 'selected'; ?> >
                                            Weightlifting
                                        </option>
                                        <option value="Wrestling" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Wrestling') echo 'selected'; ?> >
                                            Wrestling
                                        </option>
                                        <option value="Yoga" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Yoga') echo 'selected'; ?> >
                                            Yoga
                                        </option>
                                        <option value="Zumba" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Zumba') echo 'selected'; ?> >
                                            Zumba
                                        </option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) &&
                                            $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >
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
                                        Go
                                    </button>
                                    <button type="button" onclick="clear_all();"
                                            class="btn btn-primary" style="padding-left: 10px;
                                        margin-left: 10px;padding-top: 1px; padding-bottom: 1px; padding-right: 10px;">
                                        Clear All
                                    </button>
                                </label>

                                <div>
                                    <?php
                                    foreach (range('A', 'Z') as $char) {
                                        if (isset($_GET['groupfilter'])) {
                                            if (isset($_GET['page_num'])) {
                                                echo '<a href='.BASE_URL.'/members/groups-sports.php?groupfilter='.
                                                    $_GET['groupfilter'].'&letter='
                                                    .$char.'&page_num='.$_GET['page_num'].'> '.$char.'</a> |';
                                            } else {
                                                echo '<a href='.BASE_URL.'/members/groups-sports.php?groupfilter='.
                                                    $_GET['groupfilter'].'&&letter='
                                                    .$char.'&page_num=1> '.$char.'</a> |';
                                            }

                                        } else {
                                            if (isset($_GET['page_num'])) {
                                                echo '<a href='.BASE_URL.'/members/groups-sports.php?letter='.
                                                    $char.'&page_num='.$_GET['page_num'].'> '.$char.'</a> |';
                                            } else {
                                                echo '<a href='.BASE_URL.'/members/groups-sports.php?letter='.$char.'&page_num=1> '.$char.'</a> |';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <input type="hidden" value="<?php echo $page; ?>" name="page_num">
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
                                <a href="groups-sports-large.php?userid=<?php echo $row['sportsubmitby'];?>&&sportid=<?php echo $row['id']?>"
                                   class="img" style="height: 158px;" data-overlay="0.1">
                                    <?php if($row['sportphoto'] !='') { 
                                        $img_arr = explode(",", $row['sportphoto']);  
                                        ?>
                                        <div class="custom-background-img" style="background-image: url(<?php echo $img_arr[0]; ?>)"></div>
                                    <?php } else { ?>
                                        <img src="../members/img/add_photo.png" alt="">
                                    <?php } ?>
                                    <?php if ($user_id === $row[$join_field]) { ?>
                                        <a data-control-name="edit_top_card"
                                           data-post-id="<?php echo $row['id']; ?>"
                                           data-post-photos="<?php echo $row['sportphoto']; ?>"
                                           title="Add additional photo"
                                           class="pv-top-card-section__edit artdeco-button artdeco-button--tertiary
                                           artdeco-button--circle ml1 pv-top-card-v2-section__edit ember-view group_edit">
                                            <li-icon type="pencil-icon" role="img" aria-label="Edit Profile">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     data-supported-dps="24x24"fill="currentColor" focusable="false">
                                                    <path d="M21.71 5L19 2.29a1 1 0 00-.71-.29 1 1 0 00-.7.29L4 15.85 2 22l6.15-2L21.71 6.45a1 1 0 00.29-.74 1 1 0 00-.29-.71zM6.87 18.64l-1.5-1.5L15.92 6.57l1.5 1.5zM18.09 7.41l-1.5-1.5 1.67-1.67 1.5 1.5z">
                                                    </path>
                                                </svg>
                                            </li-icon>
                                        </a>
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
                                            <p><a href="<?php echo $row['utubelink'] ?>" target="_blank"> Video </a></p>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <!-- Box Item End -->
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php include BASE_PATH . '/members/forms/group_edit_modal.php';?>
                    <!-- Page Count Start -->
                    <div class="page--count pt--30">
                        <form method="get">
                            <label class="ff--primary fs--14 fw--500 text-darker">
                                <span>Viewing</span>

                                <?php
                                if (isset($_GET['groupfilter']) && empty($_GET['letter'])) {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?groupfilter='.
                                        $_GET['groupfilter'].'&page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                } elseif (isset($_GET['letter']) && empty($_GET['groupfilter'])) {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?letter='.
                                        $_GET['letter'].'&page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                } elseif (isset($_GET['groupfilter']) && isset($_GET['letter'])) {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?groupfilter='.
                                        $_GET['groupfilter'].'&letter='.$_GET['letter'].'&page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                } else {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                }
                                ?>


                                <input type="number" name="page_num" value="<?php echo $page; ?>"
                                       class="form-control form-sm">

                                <?php
                                if (isset($_GET['groupfilter']) && empty($_GET['letter'])) {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?groupfilter='.
                                        $_GET['groupfilter'].'&page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                } elseif (isset($_GET['letter']) && empty($_GET['groupfilter'])) {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?letter='.
                                        $_GET['letter'].'&page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                } elseif (isset($_GET['groupfilter']) && isset($_GET['letter'])) {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?groupfilter='.
                                        $_GET['groupfilter'].'&letter='.$_GET['letter'].'&page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                } else {
                                    echo '<a href="'.BASE_URL.'/members/groups-sports.php?page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                }
                                ?>

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
<script>
    function clear_all() {
        window.location = window.location.href.split("?")[0];
        reload();
    }
</script>
<?php include BASE_PATH.'/members/includes/footer.php'?>