<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once 'fam_group_note_endpoint.php';
require_once 'notification.php';

include BASE_PATH.'/members/includes/header.php';

?>
<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="img/fambanner.png"
     data-overlay="0.35">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">My Family Group</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">My Family Group</span></li>
        </ul>
    </div>
</div>
<!-- Page Header End -->

<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">
            <!-- Main Content Start -->
            <div class="main--content col-md-8 pb--60" data-trigger="stickyScroll">
                <div class="main--content-inner drop--shadow">
                    <!-- Filter Nav Start -->
                    <div class="filter--nav pb--60 clearfix">
                        <div class="filter--link float--left">
                            <?php if (count($group) > 0) { ?>
                                <h2>Our Family Group – <?php echo $group['group_name']; ?></h2>
                            <?php } else { ?>
                                <h2>Our Family Group</h2>
                            <?php }?>

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
                                                <a href="<?php echo BASE_URL . '/members/member-activity-personal.php?user=' . $row['user_id']; ?>">
                                                    <?php if (isset($row['avatar'])) { ?>
                                                        <img src="<?php echo substr($row['avatar'], 2) ?>" alt="">
                                                    <?php } else { ?>
                                                        <img src="img/activity-img/avatar-05.jpg" alt="">
                                                    <?php } ?>
                                                </a>
                                            </div>

                                            <div class="activity--info fs--14">
                                                <div class="activity--header">
                                                    <p>
                                                        <a href="<?php echo BASE_URL . '/members/member-activity-personal.php?user=' . $row['user_id']; ?>">
                                                            <?php echo $row['first_name']; ?>
                                                            &nbsp;<?php echo $row['last_name'];
                                                            ?>
                                                        </a>
                                                        posted
                                                        a <?php echo $row['note_media']; ?>
                                                        on <?php echo $row['cat_name'] ?> </p>
                                                    </p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p>
                                                        <i class="fa mr--8 fa-clock-o"></i>
                                                        <?php echo $row['note_date']; ?>
                                                    </p>
                                                </div>

                                                <div class="activity--content">
                                                    <?php if ($row['note_media'] == 'text'): ?>
                                                        <p id="note_text_edit"><?php echo $row['note_value'] ?></p>
                                                        <input type="button"
                                                               id="<?php echo $row['note_id']; ?>_note_<?php echo $row['note_media']; ?>"
                                                               style="display: none;"
                                                               class="btn btn-primary note_edit pull-right"
                                                               value="Edit">
                                                    <?php elseif ($row['note_media'] == 'photo'): ?>
                                                        <div class="note_photo_style">
                                                            <img id="note_photo_edit"
                                                                 src="<?php echo $row['note_value']; ?>">
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
                                                    <?php elseif ($row['note_media'] == 'video'): ?>
                                                        <!-- Youtube video -->
                                                        <?php if (strpos($row['note_value'], 'youtube') > 0) { ?>
                                                            <div class="link--video">
                                                                <a class="link--url"
                                                                   href="<?php echo $row['note_value']; ?>"
                                                                   data-trigger="video_popup"></a>
                                                                <?php
                                                                $video_url = $row['note_value'];
                                                                $find = "=";
                                                                $pos = strpos($video_url, $find) + 1;
                                                                $video_img = substr($video_url, $pos);
                                                                $thumb_video_img = "http://img.youtube.com/vi/".$video_img."/mqdefault.jpg";
                                                                ?>
                                                                <img src="<?php echo $thumb_video_img; ?>" alt=""
                                                                     style="width: 800px; height: 291px;">
                                                                <!--                                                            <iframe src="http://www.youtube.com/embed/--><?php //echo $video_img;?><!--"-->
                                                                <!--                                                                    width="800" height="291" frameborder="0" allowfullscreen></iframe>-->

                                                            </div>
                                                            <!-- Facebook video -->
                                                        <?php } ?>
                                                        <?php if (strpos($row['note_value'], 'facebook') > 0) { ?>
                                                            <!-- Your embedded video player code -->
                                                            <div class="fb-video"
                                                                 data-href="<?php echo $row['note_value']; ?>"
                                                                 data-width="800" data-allowfullscreen="true">
                                                                <div class="fb-xfbml-parse-ignore"></div>
                                                            </div>
                                                        <?php } ?>
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
                                                    <?php endif; ?>
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
                        <label class="ff--primary fs--14 fw--500 text-darker" style="text-align: center">
                            <span>Viewing</span>

                            <a href="<?php echo BASE_URL.'/members/group-fam.php?group_id='.$group_id.'&page_num='.$prev_page; ?>"
                               class="btn-link"><i class="fa fa-caret-left"></i></a>
                            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>" >
                            <input type="number" name="page_num" value="<?php echo $page; ?>"
                                   class="form-control form-sm">
                            <a href="<?php echo BASE_URL.'/members/group-fam.php?group_id='.$group_id.'&page_num='.$next_page; ?>"
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
                <!-- Widget Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Add a Note</h2>
                    <!-- Buddy Finder Widget Start -->
                    <div class="buddy-finder--widget">
                        <form id="group_note_add_form" action="" method="post">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="group_note_add_date" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <select name="group_category" class="form-control form-sm group_category"
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
                                                <option value="addmedia">Select Note Type</option>
                                                <option value="text">Add Text</option>
                                                <option value="photo">Add a Photo</option>
                                                <option value="video">Add a Video</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <?php if (count($group)>0) { ?>
                                    <input type="hidden" id="add_note_group_id" value="<?php echo $group['id']; ?>">
                                <?php } else { ?>
                                    <input type="hidden" id="add_note_group_id" value="0">
                                <?php } ?>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary activity-group-note-add">Add</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                            </div>
                        </form>
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
                                                       placeholder="Choose date">
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
                                                       placeholder="Choose date">
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
                        <img src="img/widgets-img/ThriveAD.jpg" alt="" class="center-block">
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
<?php include BASE_PATH . '/members/forms/group_note_add_modal.php';?>
<?php include BASE_PATH . '/members/forms/fam_group_note_update_modal.php';?>

<?php include BASE_PATH.'/members/includes/footer.php'?>

