<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_notes', 'tbl_notes.user_id = tbl_users.id')->join('tbl_categories','tbl_notes.cat_id = tbl_categories.id');
$db->where('tbl_users.id', $_GET['user']);
$db->orderBy('tbl_notes.id');

$rows = $db->get('tbl_users');

$userdb = getDbInstance();
$userdb->where('id', $_GET['user']);
$userrow = $userdb->getOne('tbl_users');
//echo '<pre>';
//print_r($rows);
//echo '</pre>';
//exit;
?>
<?php include BASE_PATH.'/members/includes/header.php'?>

        <!-- Cover Header Start -->
        <?php if(isset($userrow['cover_photo'])) { ?>
            <div class="cover--header pt--80 text-center" data-bg-img="<?php echo substr($userrow['cover_photo'],2) ?>" data-overlay="0.6" data-overlay-color="white">
        <?php } else { ?>
            <div class="cover--header pt--80 text-center" data-bg-img="img/cover-header-img/bg-01.jpg" data-overlay="0.6" data-overlay-color="white">
        <?php } ?>
            <div class="container">
                <div class="cover--avatar online" data-overlay="0.3" data-overlay-color="primary">
                    <?php if(isset($userrow['avatar'])) { ?>
                        <img src="<?php echo substr($userrow['avatar'],2) ?>" alt="">
                    <?php } else { ?>
                        <img src="img/cover-header-img/avatar-01.jpg" alt="">
                    <?php } ?>
                </div>

                <div class="cover--user-name">
                    <h2 class="h3 fw--600"><?php echo $userrow['first_name'].' '. $userrow['last_name'];?></h2>
                </div>

                <div class="cover--user-activity">
                    <p><i class="fa mr--8 fa-clock-o"></i>Active 1 year 9 monts ago</p>
                </div>

                <div class="cover--user-desc fw--400 fs--18 fstyle--i text-darkest">
                    <p>Hello everyone ! There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                </div>
            </div>
        </div>
        <!-- Cover Header End -->

        <!-- Page Wrapper Start -->
        <section class="page--wrapper pt--80 pb--20">
            <div class="container">
                <div class="row">
                    <!-- Main Content Start -->
                    <div class="main--content col-md-8 pb--60" data-trigger="stickyScroll">
                        <div class="main--content-inner drop--shadow">
                            <!-- Content Nav Start -->
                            <div class="content--nav pb--30">
                                <ul class="nav ff--primary fs--14 fw--500 bg-lighter">
                                    <li class="active"><a href="member-activity-personal.php">Activity</a></li>
                                    <li><a href="member-profile.php">Profile</a></li>
                                    <li><a href="member-friends.html">Friends</a></li>
                                    <li><a href="member-groups.html">Groups</a></li>
                                    <li><a href="member-forum-topics.html">Forum</a></li>
                                    <li><a href="member-media-all.html">Media</a></li>
                                </ul>
                            </div>
                            <!-- Content Nav End -->

                            <!-- Filter Nav Start -->
                            <div class="filter--nav pb--60 clearfix">
                                <div class="filter--links float--left">
                                    <ul class="nav ff--primary">
                                        <li class="active"><a href="member-activity-persoanl.html">Personal</a></li>
                                        <li><a href="member-activity-mentions.html">Mentions</a></li>
                                        <li><a href="member-activity-favorites.html">Favorites</a></li>
                                        <li><a href="member-activity-friends.html">Friends</a></li>
                                        <li><a href="member-activity-groups.html">Groups</a></li>
                                    </ul>
                                </div>

                                <div class="filter--options float--right">
                                    <label>
                                        <span class="fs--14 ff--primary fw--500 text-darker">Show By :</span>

                                        <select name="activityfilter" class="form-control form-sm" data-trigger="selectmenu">
                                            <option value="updates" selected>Updates</option>
                                            <option value="friendships">Friendships</option>
                                            <option value="group-updates">Group Updats</option>
                                            <option value="membership">Membership</option>
                                            <option value="topics">Topics</option>
                                            <option value="replies">Replies</option>
                                            <option value="posts">Posts</option>
                                            <option value="comments">Comments</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- Filter Nav End -->

                            <!-- Activity List Start -->
                            <div class="activity--list">
                                <!-- Activity Items Start -->
                                <ul class="activity--items nav">
                                    <?php foreach ($rows as $row):?>
                                        <li>
                                            <!-- Activity Item Start -->
                                            <div class="activity--item">
                                                <div class="activity--avatar">
                                                    <a href="member-activity-personal.php">
                                                        <img src="img/activity-img/avatar-01.jpg" alt="">
                                                    </a>
                                                </div>

                                                <div class="activity--info fs--14">
                                                    <div class="activity--header">
                                                        <p><a href="member-activity-personal.php"><?php echo $row['first_name'].$row['last_name']?></a> posted an <?php echo $row['note_media'];?> on <?php echo $row['cat_name']?></p>
                                                    </div>

                                                    <div class="activity--meta fs--12">
                                                        <p><i class="fa mr--8 fa-clock-o"></i><?php echo $row['note_date']?></p>
                                                    </div>

                                                    <div class="activity--content">
                                                        <?php if ($row['note_media'] == 'text'):?>
                                                            <p><?php echo $row['note_value']?></p>
                                                        <?php elseif ($row['note_media'] == 'photo'):?>
                                                            <img src="<?php echo $row['note_value']; ?>">
                                                        <?php elseif ($row['note_media'] == 'video'):?>
<!--                                                            <iframe width="100%" height="100%"-->
<!--                                                                    src="--><?php //echo $row['note_value']?><!--">-->
<!--                                                            </iframe>-->
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
                                    <?php endforeach;?>
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
                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Find A Buddy</h2>

                            <!-- Buddy Finder Widget Start -->
                            <div class="buddy-finder--widget">
                                <form action="#">
                                    <div class="row">
                                        <div class="col-xs-6 col-xxs-12">
                                            <div class="form-group">
                                                <label>
                                                    <span class="text-darker ff--primary fw--500">I Am</span>

                                                    <select name="gender" class="form-control form-sm" data-trigger="selectmenu">
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-xxs-12">
                                            <div class="form-group">
                                                <label>
                                                    <span class="text-darker ff--primary fw--500">Looking For</span>

                                                    <select name="lookingfor" class="form-control form-sm" data-trigger="selectmenu">
                                                        <option value="female">Female</option>
                                                        <option value="male">Male</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-xxs-12">
                                            <div class="form-group">
                                                <label>
                                                    <span class="text-darker ff--primary fw--500">Age</span>

                                                    <select name="age" class="form-control form-sm" data-trigger="selectmenu">
                                                        <option value="18to25">18 to 25</option>
                                                        <option value="25to30">25 to 30</option>
                                                        <option value="30to35">30 to 35</option>
                                                        <option value="35to40">35 to 40</option>
                                                        <option value="40plus">40+</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-xxs-12">
                                            <div class="form-group">
                                                <label>
                                                    <span class="text-darker ff--primary fw--500">City</span>

                                                    <select name="city" class="form-control form-sm" data-trigger="selectmenu">
                                                        <option value="newyork">New York</option>
                                                        <option value="California">California</option>
                                                        <option value="Atlanta">Atlanta</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <span class="text-darker ff--primary fw--500">Filter Country</span>

                                                    <select name="city" class="form-control form-sm" data-trigger="selectmenu">
                                                        <option value="unitedstates">United States</option>
                                                        <option value="australia">Australia</option>
                                                        <option value="turkey">Turkey</option>
                                                        <option value="vietnam">Vietnam</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Buddy Finder Widget End -->
                        </div>
                        <!-- Widget End -->

                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Notice</h2>

                            <!-- Text Widget Start -->
                            <div class="text--widget">
                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some  look even slightly believable.</p>
                            </div>
                            <!-- Text Widget End -->
                        </div>
                        <!-- Widget End -->

                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Forums</h2>

                            <!-- Links Widget Start -->
                            <div class="links--widget">
                                <ul class="nav">
                                    <li><a href="sub-forums.html">User Interface Design<span>(12)</span></a></li>
                                    <li><a href="sub-forums.html">Front-End Engineering<span>(07)</span></a></li>
                                    <li><a href="sub-forums.html">Web Development<span>(37)</span></a></li>
                                    <li><a href="sub-forums.html">Social Media Marketing<span>(13)</span></a></li>
                                    <li><a href="sub-forums.html">Content Marketing<span>(28)</span></a></li>
                                </ul>
                            </div>
                            <!-- Links Widget End -->
                        </div>
                        <!-- Widget End -->

                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Archives</h2>

                            <!-- Nav Widget Start -->
                            <div class="nav--widget">
                                <ul class="nav">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="text">Jan - July 2017</span>
                                            <span class="count">(86)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="text">Jan - Dce 2016</span>
                                            <span class="count">(328)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="text">Jan - Dec 2015</span>
                                            <span class="count">(427)</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Nav Widget End -->
                        </div>
                        <!-- Widget End -->

                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Advertisements</h2>

                            <!-- Ad Widget Start -->
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