<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once '../vendor/autoload.php';
require_once 'my_family_endpoint.php';
require_once 'notification.php';
include BASE_PATH.'/members/includes/header.php';
// Check family group invitation exist
checkFamGroupInvitation($logged_id);
// Check family note exist
//checkFamNoteRequest($logged_id);
?>
<link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/auto_fill.css">
<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="img/fambanner.png"
    data-overlay="0.35">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">My Family</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">My Family</span></li>
        </ul>
    </div>
</div>
<!-- Page Header End -->

<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">
            <?php include BASE_PATH . '/includes/flash_messages.php'; ?>
            <!-- Main Content Start -->
            <div class="main--content col-md-8 pb--60" data-trigger="stickyScroll">
                <div class="main--content-inner drop--shadow">
                    <!-- Filter Nav Start -->
                    <div class="filter--nav pb--60 clearfix">
                        <div class="filter--link float--left">
                            <h2>My Family Notes</h2>
                        </div>
                    </div>
                    <!-- Filter Nav End -->
                    <!-- Activity List Start -->
                    <div class="activity--list">
                        <ul class="activity--items nav">
                            <li>
                                <!-- Activity Item Start -->
                                <div class="activity--item">

                                    <div class="activity--info fs--14">

                                        <div class="activity--content">

                                        </div>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            </li>
                            <?php if (count($rows) > 0) {
                                foreach ($rows as $row):?>
                                <li>
                                    <!-- Activity Item Start -->
                                    <div class="activity--item">
                                        <div class="activity--avatar">
                                            <a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$row['user_id']; ?>" >
                                            <?php if(isset($row['avatar'])) { ?>
                                                <img src="<?php echo substr($row['avatar'],2) ?>" alt="">
                                            <?php } else { ?>
                                                <img src="img/activity-img/avatar-05.jpg" alt="">
                                            <?php } ?>
                                            </a>
                                        </div>

                                        <div class="activity--info fs--14">
                                            <div class="activity--header">
                                                <p><a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$row['user_id']; ?>" >
                                                        <?php echo $row['first_name']; ?>&nbsp;<?php echo $row['last_name'];
                                                            ?>
                                                    </a>
                                                    Shared a link
                                                </p>
                                            </div>

                                            <div class="activity--meta fs--12">
                                                <p>
                                                    <i class="fa mr--8 fa-clock-o"></i>
                                                    <?php echo $row['note_date']; ?>
                                                </p>
                                            </div>

                                            <div class="activity--content">
                                                <?php if ($row['note_media'] == 'text'):?>
                                                    <p id="note_text_edit"><?php echo $row['note_value']?></p>
                                                    <input type="button"
                                                           id="<?php echo $row['note_id']; ?>_note_<?php echo $row['note_media']; ?>"
                                                           style="display: none;"
                                                           class="btn btn-primary note_edit pull-right"
                                                           value="Edit">
                                                <?php elseif ($row['note_media'] == 'photo'):?>
                                                    <img id="note_photo_edit" src="<?php echo $row['note_value']; ?>"
                                                         style="padding-bottom: 10px;">
                                                    <?php if (isset($row['note_comment']) &&
                                                        $row['note_comment'] !== '') { ?>
                                                        <div class="comment_content">
                                                            <i class="fa mr--8 fa-comment-o"></i>
                                                            <?php echo $row['note_comment'] ?>
                                                        </div>
                                                    <?php } ?>
                                                    <input type="button" id="<?php echo $row['note_id'];?>_note_<?php echo $row['note_media'];?>"
                                                           style="display: none;" class="btn btn-primary note_edit pull-right"
                                                           value="Edit">
                                                <?php elseif ($row['note_media'] == 'video'):?>
                                                    <div class="link--video">
                                                        <a class="link--url"
                                                           href="<?php echo $row['note_value']; ?>"
                                                           data-trigger="video_popup"></a>
                                                        <img src="img/activity-img/link-video-poster.jpg" alt="">
                                                    </div>
                                                    <?php if (isset($row['note_comment']) &&
                                                        $row['note_comment'] !== '') { ?>
                                                        <div class="comment_content">
                                                            <i class="fa mr--8 fa-comment-o"></i>
                                                            <?php echo $row['note_comment'] ?>
                                                        </div>
                                                    <?php } ?>
                                                    <input type="button"
                                                           id="<?php echo $row['note_id']; ?>_note_<?php echo $row['note_media']; ?>"
                                                           style="display: none;"
                                                           class="btn btn-primary note_edit pull-right"
                                                           value="Edit">
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Activity Item End -->
                                </li>
                                <?php endforeach;
                            } else { ?>
                                <li style="text-align: center;">
                                    There isn't any notes
                                </li>
                            <?php }
                            ?>
                        </ul>
                    </div>
                    <!-- Activity List End -->
                </div>

                <!-- Load More Button Start -->
                <div class="page--count pt--30">
                    <form method="get">
                        <label class="ff--primary fs--14 fw--500 text-darker" style="text-align: center;">
                            <span>Viewing</span>

                            <a href="<?php echo BASE_URL.'/members/activity-fam.php?page_num='.$prev_page; ?>"
                               class="btn-link"><i class="fa fa-caret-left"></i></a>
                            <input type="number" name="page_num" value="<?php echo $page; ?>"
                                   class="form-control form-sm">
                            <a href="<?php echo BASE_URL.'/members/activity-fam.php?page_num='.$next_page; ?>"
                               class="btn-link"><i class="fa fa-caret-right"></i></a>

                            <span>of <?php echo $totalPages; ?></span>
                        </label>
                    </form>
                </div>
                <!-- Load More Button End -->
            </div>
            <!-- Main Content End -->

            <!-- Main Sidebar Start -->
            <div class="main--sidebar col-md-4 pb--60" data-trigger="stickyScroll">
                <?php
                if (count($create_group_lists) < 15) { ?>
                    <div class="filter--link">
                        <a href="forms/create_family_group.php"><h5>Create a new family group (+)</h5></a>
                    </div>
                <?php }
                ?>
                <?php
                if (count($create_group_lists) > 0 || count($belongs_group_lists) > 0) { ?>
                    <div class="filter--link">
                        <a href="forms/view_fam_group.php"><h5>View Groups</h5></a>
                    </div>
                <?php }
                ?>
                <!-- Widget Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Add a Note</h2>
                    <!-- Buddy Finder Widget Start -->
                    <div class="buddy-finder--widget">
                        <form id="add_note_form" action="" method="post">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="note_add_date" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <select name="category" class="form-control form-sm category"
                                                data-trigger="selectmenu">
                                            <?php
                                            foreach ($category_lists as $category_list): ?>
                                                <option value="<?php echo $category_list['id']; ?>">
                                                    <?php echo $category_list['cat_name']; ?>
                                                </option>
                                                <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="multimedia" class="form-control form-sm multimedia"
                                                    data-trigger="selectmenu">
                                                <option value="addmedia">Add Comment, Photo or Video</option>
                                                <option value="text">Add Text</option>
                                                <option value="photo">Add a Photo</option>
                                                <option value="video">Add a Video Link</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary activity-note-add">Add</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                        </form>
                    </div>
                    <!-- Buddy Finder Widget End -->
                </div>
                <!-- Widget End -->

                <!-- Widget Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">View Notes</h2>

                    <!-- Text Widget Start -->
                    <div class="buddy-finder--widget">
                        <form action="" method="post" id="view_note_form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <?php if (isset($_POST['note_view_date'])) { ?>
                                            <input type="date" name="note_view_date"
                                                   value="<?php echo $_POST['note_view_date'] ?>">
                                        <?php } else { ?>
                                            <input type="date" name="note_view_date"
                                                   value="<?php echo date("Y-m-d") ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="view_category" class="form-control form-sm category"
                                                    data-trigger="selectmenu">
                                                <?php
                                                foreach ($category_lists as $category_list): ?>
                                                    <option value="<?php echo $category_list['id']; ?>"
                                                        <?php if(isset($_POST['view_category']) &&
                                                            ($_POST['view_category'] == $category_list['id']))
                                                            echo 'selected'; ?> >
                                                        <?php echo $category_list['cat_name']; ?>
                                                    </option>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="text--widget">

                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary view_note_submit">Search</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Text Widget End -->
                </div>
                <!-- Widget End -->

                <!-- Widget Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Update a Note</h2>
                    <!-- Text Widget Start -->
                    <div class="buddy-finder--widget">
                        <form action="" method="post" id="update_note_form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <?php if (isset($_POST['note_update_date'])) { ?>
                                            <input type="date" name="note_update_date"
                                                   value="<?php echo $_POST['note_update_date'] ?>">
                                        <?php } else { ?>
                                            <input type="date" name="note_update_date"
                                                   value="<?php echo date("Y-m-d") ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="update_category" class="form-control form-sm"
                                                    data-trigger="selectmenu">
                                                <?php
                                                foreach ($category_lists as $category_list): ?>
                                                    <option value="<?php echo $category_list['id']; ?>"
                                                        <?php if(isset($_POST['update_category']) &&
                                                            ($_POST['update_category'] == $category_list['id']))
                                                            echo 'selected'; ?> >
                                                        <?php echo $category_list['cat_name']; ?>
                                                    </option>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="text--widget">

                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary update_note_submit">Search</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <!-- Widget End -->

                <div class="ad--widget">
                    <a href="#">
                        <img src="img/widgets-img/ad.jpg" alt="" class="center-block">
                    </a>
                </div>
                <!-- Ad Widget End -->
            </div>
            <!-- Widget End -->
        </div>
        <!-- Main Sidebar End -->
    </div>
</div>
</section>
<!-- Page Wrapper End -->

<?php include BASE_PATH . '/members/forms/fam_note_add_modal.php';?>
<?php include BASE_PATH . '/members/forms/fam_note_update_modal.php';?>

<?php include BASE_PATH.'/members/includes/footer.php'?>
