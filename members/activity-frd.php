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
        $db->update('tbl_fri_groups_members', $data_to_db);
    }

    else if($stat == 'delete') {
        $db = getDbInstance();
        $db->where('id', $member_id);
        $db->delete('tbl_fri_groups_members');
    }
}

/**
 * Notification for friend/family request
 */
// Check family group invitation exist
checkFriGroupInvitation($logged_id);
//checkGroupExists

$db = getDbInstance();
$db->where('by_who', $logged_id);
$checkGroup = $db->get('tbl_fri_groups');
//print_r($checkGroup);exit;

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

?>

<?php include BASE_PATH.'/members/includes/header.php'?>

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
                        <!-- Activity Items Start -->
                        <ul class="activity--items nav">
                            <!-- Activity Item Start -->
                            <div class="activity--item">
                                <div class="activity">
                                    <a href="member-activity-personal.php">

                                    </a>
                                </div>
                            </div>
                            <!-- Activity Item End -->
                        
                        
                            <!-- Activity Item Start -->
                            <div class="activity--item">
                                <div class="activity">
                                    <a href="member-activity-personal.php">
                                        
                                    </a>
                                </div>

                                <div class="activity--info fs--14">
                                    <div class="activity--comments fs--12">
                                        <ul class="acomment--items nav">
                                            <li>
                                                
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Activity Item End -->
                        
                        
                            <!-- Activity Item Start -->
                            <div class="activity--item">
                                <div class="activity">
                                    <a href="member-activity-personal.php">
                                </div>
                                    
                                    <div class="activity--content">
                                        <div class="link--embed">
                                            
                                        </div>
                                    </div>
                                
                            </div>
                            <!-- Activity Item End -->
                            
                            <li>
                                <!-- Activity Item Start -->
                                <div class="activity--item">

                                </div>
                                <!-- Activity Item End -->
                            </li>

                            <li>
                                <!-- Activity Item Start -->
                                <div class="activity--item">
                                    <div class="activity--avatar">
                                        <a href="member-activity-personal.php">
                                            <img src="img/activity-img/avatar-07.jpg" alt="">
                                        </a>
                                    </div>

                                    <div class="activity--info fs--14">
                                        <div class="activity--header">
                                            <p><a href="member-activity-personal.php">Anita J. Lilley</a>
                                                posted an update in the group <a
                                                    href="group-home.php">Lens-bians Photography</a></p>
                                        </div>

                                        <div class="activity--meta fs--12">
                                            <p><i class="fa mr--8 fa-clock-o"></i>yesterday at 08:20 am</p>
                                        </div>

                                        <div class="activity--content">
                                            <div class="gallery--embed" data-trigger="gallery_popup">
                                                <ul class="nav AdjustRow">
                                                
                                                        
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            </li>

                            <li>
                                <!-- Activity Item Start -->
                                <div class="activity--item">
                                    <div class="activity--info fs--14">
                                       
                                    </div>
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
                        <form action="#">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="birtdate" value="<?php echo date("Y-m-d") ?>" placeholder="select Birth date" >
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
<?php include BASE_PATH . '/members/forms/add_friend_modal.php';?>
<!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>