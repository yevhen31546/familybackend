<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Init variables
$user_id = $_SESSION['user_id'];
$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';
$upload_path_seg = '/church';
$update_field = 'churchphoto';
$target_tbl = 'tbl_church';
$join_field = 'churchsubmitby';
$filter_letter = 'churchname';
$group_filter = 'churchgroup';
$order_field = 'churchdate';

$db = getDbInstance();
$db->pageLimit = $page_per_num;

// Add additional photo
if (isset($_POST) && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $origin_photos = $_POST['origin_photos'];

    // Add photo
    $target_path = "./uploads/".$_SESSION['user_id'].$upload_path_seg."/image/";
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
    $db->orderBy($order_field);

    $rows = $db->paginate($tbl_name, $page);
    $total = $db->totalCount;
    $pages = $db->totalPages;
} else {
    $db->join($target_tbl, $tbl_name.'.id = '.$target_tbl.'.'.$join_field);
    $db->orderBy($order_field);

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

?>


<?php include BASE_PATH.'/members/includes/header.php'?>

    <!-- Page Header Start -->
    <div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/jesus3.png" data-overlay="0.15">
        <div class="container">
            <div class="title">
                <h2 class="h1 text-white">Church Groups</h2>
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
                                <h2 class="h4"><a href="groups-church-add.php">Add a memory to a group (+)</a></h2>
                            </div>

                            <div class="filter--options float--right">
                                <form action="" method="GET" id="groupfilterform">
                                <label style="display: flex;">
                                    <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                    <select name="groupfilter" id="groupfilter" class="form-control form-sm" onchange="this.form.submit();" data-trigger="selectmenu">
                                        <option value="all" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'all') echo 'selected'; ?> >Most Current Added</option>
                                        <option value="Adults/Family" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Adults/Family') echo 'selected'; ?> >Adults/Family</option>
                                        <option value="Bible" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Bible') echo 'selected'; ?> >Bible Study</option>
                                        <option value="Children" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == "Children") echo 'selected'; ?> >Children's Church</option>
                                        <option value="Choir/Worship" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Choir/Worship') echo 'selected'; ?> >Choir/Worship</option>
                                        <option value="College/Career Group" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'College/Career Group') echo 'selected'; ?> >College/Career Group</option>
                                        <option value="Home" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Home') echo 'selected'; ?> >Home Group</option>
                                        <option value="Prayer" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Prayer') echo 'selected'; ?> >Prayer</option>
                                        <option value="Seniors" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Seniors') echo 'selected'; ?> >Seniors</option>
                                        <option value="Singles" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Singles') echo 'selected'; ?> >Singles</option>
                                        <option value="Youth" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Youth') echo 'selected'; ?> >Youth Group</option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                    </select>
                                </label>

                                <div>
                                    <?php
                                    foreach (range('A', 'Z') as $char) {
                                        if (isset($_GET['groupfilter'])) {
                                            if (isset($_GET['page_num'])) {
                                                echo '<a href='.BASE_URL.'/members/groups-church.php?groupfilter='.
                                                    $_GET['groupfilter'].'&letter='
                                                    .$char.'&page_num='.$_GET['page_num'].'> '.$char.'</a> |';
                                            } else {
                                                echo '<a href='.BASE_URL.'/members/groups-church.php?groupfilter='.
                                                    $_GET['groupfilter'].'&&letter='
                                                    .$char.'> '.$char.'</a> |';
                                            }

                                        } else {
                                            if (isset($_GET['page_num'])) {
                                                echo '<a href='.BASE_URL.'/members/groups-church.php?letter='.
                                                    $char.'&page_num='.$_GET['page_num'].'> '.$char.'</a> |';
                                            } else {
                                                echo '<a href='.BASE_URL.'/members/groups-church.php?letter='.$char.'> '.$char.'</a> |';
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

                                    <a href="groups-church-large.php?userid=<?php echo $row['churchsubmitby'];?>&&churchid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                        <?php if($row['churchphoto'] !='') { 
                                            $img_arr = explode(",", $row['churchphoto']);  
                                            ?>
                                            <img src="<?php echo $img_arr[0]; ?>" alt="">
                                        <?php } else { ?>
                                            <img src="../members/img/add_photo.png" alt="">
                                        <?php } ?>
                                        <?php if ($user_id === $row[$join_field]) { ?>
                                        <a data-control-name="edit_top_card"
                                           data-post-id="<?php echo $row['id']; ?>"
                                           data-post-photos="<?php echo $row['churchphoto']; ?>"
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
                                            <i class="fa fa-music"></i>
                                        </div>

                                        <div class="title">
                                            <h4 class="color"><?php echo $row['churchname'] ?></h4>
                                            <p><h6>Group Name: <?php echo $row['churchgroup'] ?></h6></p>
                                        </div>

                                        <div class="desc text-darker">
                                            <p>Church Name: <?php echo $row['churchname'] ?></p>
                                            <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                            <p>Date Submited: <?php echo $row['churchdate'];?></p>
                                            <p>Church Comment: <?php echo $row['churchcomment'];?></p>
                                            <?php if ($row['utubelink'] != '') {?>
                                                <p><a href="<?php echo $row['utubelink'] ?>" target="_blank">See More </a></p>
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
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?groupfilter='.
                                        $_GET['groupfilter'].'&page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                    } elseif (isset($_GET['letter']) && empty($_GET['groupfilter'])) {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?letter='.
                                        $_GET['letter'].'&page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                    } elseif (isset($_GET['groupfilter']) && isset($_GET['letter'])) {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?groupfilter='.
                                        $_GET['groupfilter'].'&letter='.$_GET['letter'].'&page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                    } else {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?page_num='.$prev_page.'"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>';
                                    }
                                    ?>


                                    <input type="number" name="page_num" value="<?php echo $page; ?>"
                                           class="form-control form-sm">

                                    <?php
                                    if (isset($_GET['groupfilter']) && empty($_GET['letter'])) {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?groupfilter='.
                                            $_GET['groupfilter'].'&page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                    } elseif (isset($_GET['letter']) && empty($_GET['groupfilter'])) {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?letter='.
                                            $_GET['letter'].'&page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                    } elseif (isset($_GET['groupfilter']) && isset($_GET['letter'])) {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?groupfilter='.
                                            $_GET['groupfilter'].'&letter='.$_GET['letter'].'&page_num='.$next_page.'"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>';
                                    } else {
                                        echo '<a href="'.BASE_URL.'/members/groups-church.php?page_num='.$next_page.'"
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

<?php include BASE_PATH.'/members/includes/footer.php'?>