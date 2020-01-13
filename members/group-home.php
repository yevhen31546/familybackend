<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
include BASE_PATH.'/members/includes/header.php'

?>


        <!-- Cover Header Start -->
        <div class="cover--header pt--80 text-center" data-bg-img="img/cover-header-img/bg-02.jpg" data-overlay="0.6" data-overlay-color="white">
            <div class="container">
                <div class="cover--avatar" data-overlay="0.3" data-overlay-color="primary">
                    <img src="img/cover-header-img/avatar-02.jpg" alt="">
                </div>

                <div class="cover--user-name">
                    <h2 class="h3 fw--600">Food Recipes</h2>
                </div>

                <div class="cover--user-activity">
                    <p><i class="fa mr--8 fa-clock-o"></i>Active 1 year 9 monts ago</p>
                </div>

                <div class="cover--avatars">
                    <ul class="nav">
                        <li>
                            <a href="member-activity-personal.php" data-overlay="0.3" data-overlay-color="primary">
                                <img src="img/group-img/admin-avatar-01.jpg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="member-activity-personal.php" data-overlay="0.3" data-overlay-color="primary">
                                <img src="img/group-img/admin-avatar-02.jpg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="member-activity-personal.php" data-overlay="0.3" data-overlay-color="primary">
                                <img src="img/group-img/admin-avatar-03.jpg" alt="">
                            </a>
                        </li>
                    </ul>

                    <p>Group Admins</p>
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
                                    <li class="active"><a href="group-home.html">Home</a></li>
                                    <li><a href="group-forum.html">Forum</a></li>
                                    <li><a href="group-members.php">Members</a></li>
                                    <li><a href="group-media.html">Media</a></li>
                                </ul>
                            </div>
                            <!-- Content Nav End -->

                            <!-- Filter Nav Start -->
                            <div class="filter--nav pb--60 clearfix">
                                <div class="filter--options float--right">
                                    <label>
                                        <span class="fs--14 ff--primary fw--500 text-darker">Show By :</span>

                                        <select name="activityfilter" class="form-control form-sm" data-trigger="selectmenu">
                                            <option value="everything" selected>- Everything -</option>
                                            <option value="updates">updates</option>
                                            <option value="group-updates">Group Updates</option>
                                            <option value="group-memberships">Group Memberships</option>
                                            <option value="group-topics">Topics</option>
                                            <option value="group-replies">Replies</option>
                                            <option value="group-comments">Comments</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- Filter Nav End -->

                            <!-- Activity List Start -->
                            <div class="activity--list">
                                <!-- Activity Items Start -->
                                <ul class="activity--items nav">
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
                                                    <p><a href="member-activity-personal.php">Eileen K. Ruiz</a> joined the group <a href="group-home.html">Food Recipes</a></p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p><i class="fa mr--8 fa-clock-o"></i>2 days ago</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Activity Item End -->
                                    </li>
                                    <li>
                                        <!-- Activity Item Start -->
                                        <div class="activity--item">
                                            <div class="activity--avatar">
                                                <a href="member-activity-personal.php">
                                                    <img src="img/activity-img/avatar-02.jpg" alt="">
                                                </a>
                                            </div>

                                            <div class="activity--info fs--14">
                                                <div class="activity--header">
                                                    <p><a href="member-activity-personal.php">Samuel C. Azevedo</a> posted an update in the group <a href="group-home.html">Food Recipes</a></p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p><i class="fa mr--8 fa-clock-o"></i>1 month ago</p>
                                                </div>

                                                <div class="activity--content">
                                                    <p>It was very tasty.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Activity Item End -->
                                    </li>
                                    <li>
                                        <!-- Activity Item Start -->
                                        <div class="activity--item">
                                            <div class="activity--avatar">
                                                <a href="member-activity-personal.php">
                                                    <img src="img/activity-img/avatar-03.jpg" alt="">
                                                </a>
                                            </div>

                                            <div class="activity--info fs--14">
                                                <div class="activity--header">
                                                    <p><a href="member-activity-personal.php">Denise R. Sherman</a> shared her new experience about recipes in the group <a href="group-home.html">Food Recipes</a></p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p><i class="fa mr--8 fa-clock-o"></i>2 months ago</p>
                                                </div>

                                                <div class="activity--content">
                                                    <p>Here's what one recipe reviewer had to say: "I made this recipe for the first time for my Italian mom, and she loved it! It was full of flavor. I would definitely make this again. I know it was good because my mom asked me for the recipe."</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Activity Item End -->
                                    </li>
                                    <li>
                                        <!-- Activity Item Start -->
                                        <div class="activity--item">
                                            <div class="activity--avatar">
                                                <a href="member-activity-personal.php">
                                                    <img src="img/activity-img/avatar-04.jpg" alt="">
                                                </a>
                                            </div>

                                            <div class="activity--info fs--14">
                                                <div class="activity--header">
                                                    <p><a href="member-activity-personal.php">Leticia J. Espinosa</a> posted an update in the group <a href="group-home.html">Food Recipes</a></p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p><i class="fa mr--8 fa-clock-o"></i>2 months ago</p>
                                                </div>

                                                <div class="activity--content">
                                                    <p>This is one of Ina's all-time favorite recipes. It looks fancy, but it really is easy. Use crisp, tart apples like Granny Smith for the best flavor.</p>
                                                </div>

                                                <div class="activity--comments fs--12">
                                                    <ul class="acomment--items nav">
                                                        <li>
                                                            <div class="acomment--item clearfix">
                                                                <div class="acomment--avatar">
                                                                    <a href="member-activity-personal.php">
                                                                        <img src="img/activity-img/avatar-05.jpg" alt="">
                                                                    </a>
                                                                </div>

                                                                <div class="acomment--info">
                                                                    <div class="acomment--header">
                                                                        <p><a href="member-activity-personal.php">Lee E. Jones</a> Replied</p>
                                                                    </div>

                                                                    <div class="acomment--meta">
                                                                        <p><i class="fa mr--8 fa-clock-o"></i>2 months ago</p>
                                                                    </div>

                                                                    <div class="acomment--content">
                                                                        <p>Yes I’m agree with you. I was also made it. it’’s really very easy and tasty recipe.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Activity Item End -->
                                    </li>
                                    <li>
                                        <!-- Activity Item Start -->
                                        <div class="activity--item">
                                            <div class="activity--avatar">
                                                <a href="member-activity-personal.php">
                                                    <img src="img/activity-img/avatar-06.jpg" alt="">
                                                </a>
                                            </div>

                                            <div class="activity--info fs--14">
                                                <div class="activity--header">
                                                    <p><a href="member-activity-personal.php">Bonnie P. Rock</a> shared a link in the group <a href="group-home.html">Food Recipes</a></p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p><i class="fa mr--8 fa-clock-o"></i>2 months ago</p>
                                                </div>

                                                <div class="activity--content">
                                                    <p>With more than 4,300 reviews and a five-star rating, Alton's classic turkey is the go-to foolproof recipe every cook can use.</p>

                                                    <p><a href="#" class="btn-link">https://www.google.com/demofoodcorner</a></p>
                                                </div>
                                            </div>
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
                                                    <p><a href="member-activity-personal.php">Anita J. Lilley</a> posted an update in the group <a href="group-home.html">Food Recipes</a></p>
                                                </div>

                                                <div class="activity--meta fs--12">
                                                    <p><i class="fa mr--8 fa-clock-o"></i>2 months ago</p>
                                                </div>

                                                <div class="activity--content">
                                                    <p>here are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration</p>

                                                    <div class="gallery--embed" data-trigger="gallery_popup">
                                                        <ul class="nav AdjustRow">
                                                            <li>
                                                                <a href="img/activity-img/gallery-embed-01.jpg">
                                                                    <img src="img/activity-img/gallery-embed-01.jpg" alt="">
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="img/activity-img/gallery-embed-02.jpg">
                                                                    <img src="img/activity-img/gallery-embed-02.jpg" alt="">
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="img/activity-img/gallery-embed-03.jpg">
                                                                    <img src="img/activity-img/gallery-embed-03.jpg" alt="">
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="img/activity-img/gallery-embed-04.jpg" data-overlay="0.5">
                                                                    <img src="img/activity-img/gallery-embed-04.jpg" alt="">
                                                                    <span>24+ More</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
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