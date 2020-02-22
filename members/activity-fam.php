<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once 'fam_group_note_endpoint.php';
require_once 'notification.php';

/**
 * Check notification
 */
// Check family group invitation exist
checkFamGroupInvitation($logged_id);
if($_SESSION['family_group_request']) {
    $db = getDbInstance();
    $query = 'SELECT us.`first_name`, us.`last_name`, gg.`group_name`, gg.`id` AS member_id, gg.`group_id`
            FROM tbl_users us, (
            SELECT g.`by_who`, g.`group_name`, m.`id`, m.`group_id`
            FROM tbl_fam_groups g, (SELECT group_id, id
            FROM tbl_fam_groups_members
            WHERE who='.$logged_id.' AND stat=0) m
            WHERE g.`id`=m.group_id) gg
            WHERE gg.by_who = us.`id`';
    $fri_group_requests = $db->rawQuery($query);

    $notification_msg = genFamGroupNotMsg($fri_group_requests);
    $_SESSION['fam_group_requests_msg'] = $notification_msg;
}

// Check family group invitation exist
checkFamNoteRequest($logged_id);
if($_SESSION['family_note_request']) {
    $db = getDbInstance();
    $query = 'SELECT tbl_fam_group_notes.*, tbl_fam_groups.`group_name`
              FROM tbl_fam_group_notes, tbl_fam_groups
              WHERE note_to = '.$logged_id.' AND status = 0';
    $note_requests = $db->rawQuery($query);

    $notification_msg = genFamNoteNotMsg($note_requests);
    $_SESSION['fam_note_request_msg'] = $notification_msg;
}

// Get saved note lists for current user
$rows = get_fam_group_note_lists();

include BASE_PATH.'/members/includes/header.php'

?>



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
                            <h2>Our Family Collection of Notes</h2>
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

                            <?php foreach ($rows as $row):?>
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
                                            <?php elseif ($row['note_media'] == 'photo'):?>
                                                <img id="note_photo_edit" src="<?php echo $row['note_value']; ?>"
                                                     style="padding-bottom: 10px;">
                                                <input type="button" id="<?php echo $row['id'];?>_note_<?php echo $row['note_media'];?>"
                                                       style="display: none;" class="btn btn-primary note_edit pull-right"
                                                       value="Edit">
                                            <?php elseif ($row['note_media'] == 'video'):?>
                                                <a class="link--url"
                                                   href="<?php echo $row['note_value']; ?>"
                                                   data-trigger="video_popup"></a>

                                                <div class="link--video">
                                                    <img src="img/activity-img/link-video-poster.jpg" alt="">
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <!-- Activity List End -->
                </div>

                <!-- Load More Button Start -->
                <div class="load-more--btn pt--30 text-center">
                    <a href="#" class="btn btn-animate">
                        <span>See More Activities<i class="fa ml--10 fa-caret-right"></i></span>
                    </a>
                </div>
                <!-- Load More Button End -->
            </div>
            <!-- Main Content End -->

            <!-- Main Sidebar Start -->
            <div class="main--sidebar col-md-4 pb--60" data-trigger="stickyScroll">
                <?php
                if (count($checkGroup) > 0) { ?>
                    <div class="filter--link float--left">
                        <a href="forms/edit_family_group.php?group_id=<?php echo $checkGroup[0]['id']; ?>"><h5>Edit <?php echo $checkGroup[0]['group_name']; ?></h5></a>
                    </div>
                <?php }
                else { ?>
                    <div class="filter--link float--left">
                        <a href="forms/create_family_group.php"><h5>Create a new family group (+)</h5></a>
                    </div>
                <?php }
                ?>
                <br>
                <br>

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
                                            <option value="category">*Select a Category</option>
                                            <option value="1">Favorite Stories</option>
                                            <option value="2">From the Heart</option>
                                            <option value="3">Our Sports/Teams</option>
                                            <option value="4">Our Traditions</option>
                                            <option value="5">Our Moments in Time</option>
                                            <option value="6">Our Achievements</option>
                                            <option value="7">Our Challenges</option>
                                            <option value="8">Our Recipes</option>
                                            <option value="9">Our Testimonies</option>
                                            <option value="10">Our Affiliations/Clubs</option>
                                            <option value="11">Our Special Events</option>
                                            <option value="12">Our Family Medical History</option>
                                            <option value="13">In Memory Of</option>
                                            <option value="14">Other</option>
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
                                <input type="hidden" id="add_note_group_id" value="<?php echo $checkGroup[0]['id']; ?>">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary activity-group-note-add">Add</button>
                                    <button type="button" class="btn btn-primary add_cancel_button">Cancel</button>
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
                        <form action="#">
                            <div class="row">

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="note_view_date" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="our category" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="category">*Select a Category</option>
                                                <option value="stories">Favorite Stories</option>
                                                <option value="heart">From the Heart</option>
                                                <option value="teams">Our Sports/Teams</option>
                                                <option value="traditions">Our Traditions</option>
                                                <option value="time">Our Moments in Time</option>
                                                <option value="achievements">Our Achievements</option>
                                                <option value="challenges">Our Challenges</option>
                                                <option value="recipes">Our Recipes</option>
                                                <option value="testimonies">Our Testimonies</option>
                                                <option value="affiliations">Our Affiliations/Clubs</option>
                                                <option value="events">Our Special Events</option>
                                                <option value="medical">Our Family Medical History</option>
                                                <option value="memory">In Memory Of</option>
                                                <option value="our-other">Other</option>

                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="text--widget">

                                </div>
                                <div class="col-xs-12">
                                    <button type="post" class="btn btn-primary">Search</button>
                                    &NonBreakingSpace;&NonBreakingSpace;<button type="cancel"
                                        class="btn btn-primary">Cancel</button>
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
                        <form action="#">
                            <div class="row">

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="note_update_date" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="our category" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="category">*Select a Category</option>
                                                <option value="stories">Favorite Stories</option>
                                                <option value="heart">From the Heart</option>
                                                <option value="teams">Our Sports/Teams</option>
                                                <option value="traditions">Our Traditions</option>
                                                <option value="time">Our Moments in Time</option>
                                                <option value="achievements">Our Achievements</option>
                                                <option value="challenges">Our Challenges</option>
                                                <option value="recipes">Our Recipes</option>
                                                <option value="testimonies">Our Testimonies</option>
                                                <option value="affiliations">Our Affiliations/Clubs</option>
                                                <option value="events">Our Special Events</option>
                                                <option value="medical">Our Family Medical History</option>
                                                <option value="memory">In Memory Of</option>
                                                <option value="our-other">Other</option>


                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="text--widget">

                                </div>

                                <div class="col-xs-12">
                                    <button type="post" class="btn btn-primary">Search</button>
                                    &NonBreakingSpace;&NonBreakingSpace;<button type="cancel"
                                        class="btn btn-primary">Cancel</button>
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
<?php include BASE_PATH . '/members/forms/group_note_add_modal.php';?>
    
<?php include BASE_PATH.'/members/includes/footer.php'?>