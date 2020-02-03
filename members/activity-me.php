<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Get current user
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$user = $db->getOne('tbl_users');

//Get user list
$db = getDbInstance();
$query = 'SELECT users.id, users.user_name, tmp.who, tmp.with_who
FROM
(SELECT users.id, users.user_name, family.with_who, family.who
FROM tbl_users AS users
JOIN tbl_family AS family
ON users.id = family.who
UNION
SELECT users.id, users.user_name, friend.with_who, friend.who
FROM tbl_users AS users
JOIN tbl_friend AS friend
ON users.id = friend.who) tmp, tbl_users AS users
WHERE tmp.with_who = users.id AND tmp.who = '.$logged_id;
$friend_and_family_list = $db->rawQuery($query);

require_once 'note_email_endpoint.php';
require_once 'my_album_endpoint.php';

// Get saved note lists for current user
$rows = get_note_lists();

include BASE_PATH.'/members/includes/header.php'
?>

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="img/myalbum6.png"
    data-overlay="0.35">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">My Album</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">My Album</span></li>
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
                            <h2>Your Collection of Notes</h2>
                        </div>

                    </div>
                    <!-- Filter Nav End -->
                    <h4>**Hari, this info will need to be auto-populated by the note activity and the activity
                        in the various groups, etc. The activity items below are directly from the template to
                        show some examples. One of us will need to remove this content before production.**</h4>
                    <!-- Activity List Start -->
                    <?php include BASE_PATH . '/includes/flash_messages.php'; ?>
                    <div class="activity--list">
                        <!-- Activity Items Start -->
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
                                            <a href="member-activity-personal.php">
                                                <img src="img/activity-img/avatar-08.jpg" alt="">
                                            </a>
                                        </div>

                                        <div class="activity--info fs--14">
                                            <div class="activity--header">
                                                <p><a href="member-activity-personal.php?user=<?php echo $_SESSION['user_id']?>"><?php echo $row['first_name'].$row['last_name']?></a> posted
                                                    an <?php echo $row['note_media'];?> on <?php echo $row['cat_name']?> </p>
                                            </div>

                                            <div class="activity--meta fs--12">
                                                <p><i class="fa mr--8 fa-clock-o"></i><?php echo $row['note_date']?></p>
                                            </div>

                                            <div class="activity--content">
                                                <?php if ($row['note_media'] == 'text'):?>
                                                    <p id="note_text_edit"><?php echo $row['note_value']?></p>
                                                    <input type="button" id="<?php echo $row['id'];?>_note_<?php echo $row['note_media'];?>" style="display: none;" class="btn btn-primary note_edit pull-right" value="Edit">
                                                <?php elseif ($row['note_media'] == 'photo'):?>
                                                    <img id="note_photo_edit" src="<?php echo $row['note_value']; ?>" style="padding-bottom: 10px;">
                                                    <input type="button" id="<?php echo $row['id'];?>_note_<?php echo $row['note_media'];?>" style="display: none;" class="btn btn-primary note_edit pull-right" value="Edit">
                                                <?php elseif ($row['note_media'] == 'video'):?>
                                                    <iframe id="note_video_edit" width="100%" height="100%"
                                                            src="<?php echo $row['note_value']?>" style="padding-bottom: 10px;">
                                                    </iframe>
                                                    <input type="button" id="<?php echo $row['id'];?>_note_<?php echo $row['note_media'];?>" style="display: none;" class="btn btn-primary note_edit pull-right" value="Edit">
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Activity Item End -->
                                </li>
                            <?php endforeach; ?>

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
                <!-- Widget Add a Note Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Add a Note</h2>
                    <!-- Buddy Finder Widget Start -->
                    <div class="buddy-finder--widget">
                        <form id="add_note_form" action="#" method="post">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                            <input type="date" name="note_add_date">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <select name="category" class="form-control form-sm category"
                                            data-trigger="selectmenu">
                                            <option value="category">*Select a Category</option>
                                            <option value="1">My Story</option>
                                            <option value="2">My Message from the Heart</option>
                                            <option value="3">My Likes and Dislikes</option>
                                            <option value="4">My Hobbies</option>
                                            <option value="5">My Sports</option>
                                            <option value="6">My Fun Facts</option>
                                            <option value="7">My Adventures</option>
                                            <option value="8">My Testimonies</option>
                                            <option value="9">My Education</option>
                                            <option value="10">My Affiliations</option>
                                            <option value="11">My Thoughts</option>
                                            <option value="12">Other Notes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
<!--                                            <select name="multimedia" class="form-control form-sm multimedia"-->
<!--                                                data-trigger="selectmenu">-->
<!--                                                <option value="addmedia">Add Comment, Photo or Video</option>-->
<!--                                                <option value="text">Add Text</option>-->
<!--                                                <option value="photo">Add a Photo</option>-->
<!--                                                <option value="video">Add a Video Link</option>-->
<!--                                            </select>-->
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
                                    <div class="form-group">
                                        <label for="sel_profile">
                                            Choose a profile
                                        </label>
                                           <select name="sel_profile" id="add_note_profile" class="form-control form-sm sel_profile">
                                               <option value="<?php echo $logged_id; ?>">Myself</option>
                                               <?php
                                               foreach ($friend_and_family_list as $item):
                                               ?>
                                               <option value="<?php echo $item['id']; ?>">
                                                   <?php echo $item['user_name']; ?>
                                               </option>
                                               <?php
                                               endforeach;
                                               ?>
                                            </select>
                                        <br/>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary activity-note-add">Save</button>
                                    <button type="button" class="btn btn-primary add_cancel_button">Cancel</button>
                                </div>
                        </form>

                    </div>
                    <!-- Buddy Finder Widget End -->
                </div>
                <!-- Widget End -->

                <!-- Widget View Notes Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">View Notes</h2>

                    <!-- Text Widget Start -->
                    <div class="buddy-finder--widget">
                        <form action="#" method="post" id="view_note_form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="note_view_date">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>

                                            <select name="view_category" class="form-control form-sm category"
                                                    data-trigger="selectmenu">
                                                <option value="category">*Select a Category</option>
                                                <option value="1">My Story</option>
                                                <option value="2">My Message from the Heart</option>
                                                <option value="3">My Likes and Dislikes</option>
                                                <option value="4">My Hobbies</option>
                                                <option value="5">My Sports</option>
                                                <option value="6">My Fun Facts</option>
                                                <option value="7">My Adventures</option>
                                                <option value="8">My Testimonies</option>
                                                <option value="9">My Education</option>
                                                <option value="10">My Affiliations</option>
                                                <option value="11">My Thoughts</option>
                                                <option value="12">Other Notes</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="text--widget">

                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary view_note_submit">Search</button>
                                    <button type="button" class="btn btn-primary view_cancel_button">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Text Widget End -->
                </div>
                <!-- Widget End -->

                <!-- Widget Update a Note Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Update a Note</h2>
                    <!-- Text Widget Start -->
                    <div class="buddy-finder--widget">
                        <form action="#" method="post" id="update_note_form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <input type="date" name="note_update_date">
                                        <!--                                        <label>-->
<!--                                            <select name="update_date" class="form-control form-sm"-->
<!--                                                data-trigger="selectmenu">-->
<!--                                                <option value="date">Select a Date</option>-->
<!--                                                <option value="today">Today</option>-->
<!--                                                <option value="anotherdate">Another Date</option>-->
<!--                                            </select>-->
<!--                                        </label>-->
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="update_category" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <option value="category">*Select a Category</option>
                                                <option value="1">My Story</option>
                                                <option value="2">My Message from the Heart</option>
                                                <option value="3">My Likes and Dislikes</option>
                                                <option value="4">My Hobbies</option>
                                                <option value="5">My Sports</option>
                                                <option value="6">My Fun Facts</option>
                                                <option value="7">My Adventures</option>
                                                <option value="8">My Testimonies</option>
                                                <option value="9">My Education</option>
                                                <option value="10">My Affiliations</option>
                                                <option value="11">My Thoughts</option>
                                                <option value="12">Other Notes</option>

                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="text--widget">

                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary update_note_submit">Search</button>
                                    <button type="button" class="btn btn-primary update_cancel_button">Cancel</button>
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
    <?php include BASE_PATH . '/members/forms/note_add_modal.php';?>
</div>
</section>
<!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>