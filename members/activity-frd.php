<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once 'fri_group_note_endpoint.php';
require_once 'notification.php';

/**
 * Check notification
 */
// Check friend group invitation exist
checkFriGroupInvitation($logged_id);
if($_SESSION['friend_group_request']) {
    $db = getDbInstance();
    $query = 'SELECT us.`first_name`, us.`last_name`, gg.`group_name`, gg.`id` AS member_id, gg.`group_id`
            FROM tbl_users us, (
            SELECT g.`by_who`, g.`group_name`, m.`id`, m.`group_id`
            FROM tbl_fri_groups g, (SELECT group_id, id
            FROM tbl_fri_groups_members
            WHERE who='.$logged_id.' AND stat=0) m
            WHERE g.`id`=m.group_id) gg
            WHERE gg.by_who = us.`id`';
    $fri_group_requests = $db->rawQuery($query);

    $notification_msg = genFriGroupNotMsg($fri_group_requests);
    $_SESSION['fri_group_requests_msg'] = $notification_msg;
}

// Check friend group invitation exist
checkFriNoteRequest($logged_id);
if($_SESSION['friend_note_request']) {
    $db = getDbInstance();
    $query = 'SELECT tbl_fri_group_notes.*, tbl_fri_groups.`group_name`
              FROM tbl_fri_group_notes, tbl_fri_groups
              WHERE note_to = '.$logged_id.' AND status = 0';
    $note_requests = $db->rawQuery($query);

    $notification_msg = genFriNoteNotMsg($note_requests);
    $_SESSION['fri_note_request_msg'] = $notification_msg;
}

// Get saved note lists for current user
$rows = get_fri_group_note_lists();

include BASE_PATH.'/members/includes/header.php'

?>

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="img/frebanner.png"
     data-overlay="0.35">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">My Friends</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">My Friends</span></li>
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
                            <h2>Our Friends Collection of Notes</h2>
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
<!--                    <h5><a href="#add-friend-modal" data-toggle="modal">Add a Friend (+)</a></h5>-->
                    <?php
                    if (count($checkGroup) > 0) { ?>
                        <div class="filter--link float--left">
                            <a href="forms/edit_friend_group.php?group_id=<?php echo $checkGroup[0]['id']; ?>"><h5>Edit <?php echo $checkGroup[0]['group_name']; ?></h5></a>
                        </div>
                    <?php }
                    else { ?>
                        <div class="filter--link float--left">
                            <a href="forms/create_friend_group.php"><h5>Create a new friend group (+)</h5></a>
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
                                            <select name="friends" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="pickfriend">*Pick a Friend</option>
                                                <option value="">Jack Sparrow</option>
                                                <option value="">Lucille Ball</option>
                                                <option value="">Billy Graham</option>
                                                <option value="">Brad Pitt</option>
                                                <option value="">Betsy Ross</option>
                                                <option value="more">More Options</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="friends category" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="category">*Select a Category</option>
                                                <option value="fave">Favorite Stories</option>
                                                <option value="fromheart">From the Heart</option>
                                                <option value="ourtraditions">Our Traditions</option>
                                                <option value="ourtime">Our Moments in Time</option>
                                                <option value="ourachievements">Our Achievements</option>
                                                <option value="ourchallenges">Our Challenges</option>
                                                <option value="ourrecipes">Our Recipes</option>
                                                <option value="pets">Our Pets</option>
                                                <option value="ourtestimonies">Our Testimonies</option>
                                                <option value="ourclubs">Our Affiliations/Clubs</option>
                                                <option value="special">Our Special Events</option>
                                                <option value="oursports">Our Sports</option>
                                                <option value="mentors">Our Mentors</option>
                                                <option value="memoryof">In Memory Of</option>
                                                <option value="other">Other</option>
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
                                            <select name="friends category" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="category">*Select a Category</option>
                                                <option value="fave">Favorite Stories</option>
                                                <option value="fromheart">From the Heart</option>
                                                <option value="ourtraditions">Our Traditions</option>
                                                <option value="ourtime">Our Moments in Time</option>
                                                <option value="ourachievements">Our Achievements</option>
                                                <option value="ourchallenges">Our Challenges</option>
                                                <option value="ourrecipes">Our Recipes</option>
                                                <option value="pets">Our Pets</option>
                                                <option value="ourtestimonies">Our Testimonies</option>
                                                <option value="ourclubs">Our Affiliations/Clubs</option>
                                                <option value="special">Our Special Events</option>
                                                <option value="oursports">Our Sports</option>
                                                <option value="mentors">Our Mentors</option>
                                                <option value="memoryof">In Memory Of</option>
                                                <option value="other">Other</option>
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