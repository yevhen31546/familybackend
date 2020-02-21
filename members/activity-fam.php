<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once 'notification.php';

$logged_id = $_SESSION['user_id'];

/**
 * Approve or not
 */

if(isset($_GET) && isset($_GET['group_id'])) {
    $stat = $_GET['stat'];
    $group_id = $_GET['group_id'];
    $member_id = $_GET['member_id'];

    if($stat == 'approved') {
        $data_to_db = array(
            'stat'=> 1
        );
        $db = getDbInstance();
        $db->where('id', $member_id);
        $db->update('tbl_fam_groups_members', $data_to_db);
    }

    else if($stat == 'delete') {
        $db = getDbInstance();
        $db->where('id', $member_id);
        $db->delete('tbl_fam_groups_members');
    }
}

/**
 * Notification for friend/family request
 */
// Check family group invitation exist
checkFamGroupInvitation($logged_id);
//checkGroupExists

$db = getDbInstance();
$db->where('by_who', $logged_id);
$checkGroup = $db->get('tbl_fam_groups');
//print_r($checkGroup);exit;

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
    $fam_group_requests = $db->rawQuery($query);

    $notification_msg = genFamGroupNotMsg($fam_group_requests);
    $_SESSION['fam_group_requests_msg'] = $notification_msg;
}

?>

<?php include BASE_PATH.'/members/includes/header.php'?>

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

                        <!-- <div class="filter--options float--right">
                            <label>
                                <span class="fs--14 ff--primary fw--500 text-darker">Show By :</span>

                                <select name="activityfilter" class="form-control form-sm"
                                    data-trigger="selectmenu">
                                    <option value="everything" selected>— Everything —</option>
                                    <option value="new-members">New Members</option>
                                    <option value="profile-updates">Profile Updates</option>
                                    <option value="updates">Updates</option>
                                    <option value="friendships">Friendships</option>
                                    <option value="new-groups">New Groups</option>
                                    <option value="group-memberships">Group Memberships</option>
                                    <option value="group-updates">Group Updates</option>
                                    <option value="topics">Topics</option>
                                    <option value="replies">Replies</option>
                                </select>
                            </label>
                        </div>-->
                    </div>

                    <!-- Filter Nav End -->

                    <!-- Activity List Start -->
                    <div class="activity--list">
                        
                                <!-- Activity Item Start -->
                                <div class="activity--item">
                                    <div class="activity">
                                        <a href="member-activity-personal.html">

                                        </a>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            
                                <!-- Activity Item Start -->
                                <div class="activity--item">
                                    <div class="activity">
                                        <a href="member-activity-personal.html">
                                            
                                        </a>
                                    </div>

                                    <div class="activity--info fs--14">
                                        

                                        <div class="activity--comments fs--12">
                                            <ul class="acomment--items nav">
                                                <li>
                                                    <div class="acomment--item clearfix">
                                                        <div class="acomment">
                                                            <a href="member-activity-personal.html">
                                                                
                                                            </a>
                                                        </div>

                                                        <div class="acomment--info">
                                                            

                                                            

                                                            <div class="acomment--content">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            
                            
                                <!-- Activity Item Start -->
                                <div class="activity--item">
                                    <div class="activity--avatar">
                                        <a href="member-activity-personal.html">
                                            <img src="img/activity-img/avatar-05.jpg" alt="">
                                        </a>
                                    </div>

                                    <div class="activity--info fs--14">
                                        <div class="activity--header">
                                            <p><a href="member-activity-personal.html">Lee E. Jones</a> Shared a
                                                link</p>
                                        </div>

                                        <div class="activity--meta fs--12">
                                            <p><i class="fa mr--8 fa-clock-o"></i>yesterday at 08:20 am</p>
                                        </div>

                                        <div class="activity--content">
                                            <div class="link--embed">
                                                <a class="link--url"
                                                    href="https://www.youtube.com/watch?v=YE7VzlLtp-4"
                                                    data-trigger="video_popup"></a>

                                                <div class="link--video">
                                                    <img src="img/activity-img/link-video-poster.jpg" alt="">
                                                </div>

                                                <div class="link--info fs--12">
                                                    <div class="link--title">
                                                        <h4 class="h6">There are many variations of passages of
                                                            Lorem Ipsum available, but the majority have
                                                            suffered</h4>
                                                    </div>

                                                    <div class="link--desc">
                                                        <p>There are many variations of passages of Lorem Ipsum
                                                            available, but the majority have suffered alteration
                                                            in some form, by injected humour, or randomised
                                                            words which don't look even slightly believable. If
                                                            you are going to use a passage of Lorem Ipsum, you
                                                            need to be sure there isn't anything embarrassing
                                                        </p>
                                                    </div>

                                                    <div class="link--rel ff--primary text-uppercase">
                                                        <p>www.unknownneonnettle.com</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            
                                
                            
                            
                                <!-- Activity Item Start -->
                                <div class="activity--item">
                                    

                                    
                                </div>
                                <!-- Activity Item End -->

                            </li>
                        </ul>
                        <!-- Activity Items End -->
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
                        <form action="#">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="note_add_date" value="<?php echo date("Y-m-d") ?>">
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

<!--                                <div class="col-xs-12">-->
<!--                                    <div class="form-group">-->
<!--                                        <label>-->
<!---->
<!--                                            <select name="family-member" class="form-control form-sm"-->
<!--                                                data-trigger="selectmenu">-->
<!--                                                <option value="family-member">*Pick Family Member</option>-->
<!--                                                <option value="spouse1">Husband</option>-->
<!--                                                <option value="spouse2">Wife</option>-->
<!--                                                <option value="spouse3">Significant Other</option>-->
<!--                                                <option value="mother">Mother</option>-->
<!--                                                <option value="father">Father</option>-->
<!--                                                <option value="sister">Sister</option>-->
<!--                                                <option value="brother">Brother</option>-->
<!--                                                <option value="aunt">Aunt</option>-->
<!--                                                <option value="uncle">Uncle</option>-->
<!--                                                <option value="niece">Niece</option>-->
<!--                                                <option value="nephew">Nephew</option>-->
<!--                                                <option value="cousin">Cousin</option>-->
<!--                                                <option value="maternal-grandmother">Grandmother-->
<!--                                                </option>-->
<!--                                                <option value="maternal-grandfather">Grandfather-->
<!--                                                </option>-->
<!--                                                <option value="more">Other</option>-->
<!---->
<!--                                            </select>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->


                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>

                                            <select name="multimedia" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="addmedia">Add Comment, Photo or Video</option>
                                                <option value="addtext">Add Text</option>
                                                <option value="addphoto">Add a Photo</option>
                                                <option value="addvideo">Add a Video Link</option>


                                            </select>
                                        </label>

                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    &NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;&NonBreakingSpace;<button
                                        type="post" class="btn btn-primary">Add</button>
                                    &NonBreakingSpace;&NonBreakingSpace;<button type="cancel"
                                        class="btn btn-primary">Cancel</button>
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

<!--                                <div class="col-xs-12">-->
<!--                                    <div class="form-group">-->
<!--                                        <label>-->
<!--                                            <select name="family-member" class="form-control form-sm"-->
<!--                                                data-trigger="selectmenu">-->
<!--                                                <option value="family-member">*Pick Family Member</option>-->
<!--                                                <option value="spouse1">Husband</option>-->
<!--                                                <option value="spouse2">Wife</option>-->
<!--                                                <option value="spouse3">Significant Other</option>-->
<!--                                                <option value="mother">Mother</option>-->
<!--                                                <option value="father">Father</option>-->
<!--                                                <option value="sister">Sister</option>-->
<!--                                                <option value="brother">Brother</option>-->
<!--                                                <option value="aunt">Aunt</option>-->
<!--                                                <option value="uncle">Uncle</option>-->
<!--                                                <option value="niece">Niece</option>-->
<!--                                                <option value="nephew">Nephew</option>-->
<!--                                                <option value="cousin">Cousin</option>-->
<!--                                                <option value="maternal-grandmother">Grandmother-->
<!--                                                </option>-->
<!--                                                <option value="maternal-grandfather">Grandfather-->
<!--                                                </option>-->
<!--                                                <option value="more">Other</option>-->
<!--                                            </select>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->

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

    
<?php include BASE_PATH.'/members/includes/footer.php'?>