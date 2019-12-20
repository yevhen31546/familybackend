<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_recipes', 'tbl_users.id = tbl_recipes.rec_submit_by');
$rows = $db->get('tbl_users');
?>
<?php include BASE_PATH . '/members/includes/header.php' ?>
        

<!-- Demos Section Start -->
<section id="demos" class="pt--70">
    <div class="container">
        <!-- Section Title Start -->
        <div class="section--title pb--50 text-center">
            <div class="title lined">
                <h1 class="h1">Select an Album</h1>
            </div>
        </div>
        <!-- Section Title End -->

     <div class="row AdjustRow">
            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Image Block Start -->
                <div class="img--block style--2">
                    <a href="activity-me.php" class="btn-link text-darkest text-center" target="">
                        <span><h2>My Album</h2></span>
                        <img src="grandma480x480.png" alt="">

                        <span>My memoirs, her memories.</span>
                    </a>
                </div>
                <!-- Image Block End -->
            </div>

                <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Image Block Start -->
                <div class="img--block style--2">
                    <a href="activity-fam.php" class="btn-link text-darkest text-center" target="">
                        <span><h2>My Family Album</h2></span>
                        <img src="father480x480.png" alt="">

                        <span>Capture your memories as you live them!</span>
                    </a>
                </div>
                <!-- Image Block End -->
            </div>


            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Image Block Start -->
                <div class="img--block style--2">
                    <a href="activity-frd.php" class="btn-link text-darkest text-center" target="">
                        <span><h2>My Friends Album</h2></span>
                        <img src="boys480x480.png" alt="">

                        <span>Fun that's safe, secure and private.</span>
                    </a>
                </div>
                <!-- Image Block End -->
            </div>

            <div class="col-md-4 col-md-offset-4 col-xs-6 col-xs-offset-0 col-xxs-12 pb--60">

            </div>
        </div>
    </div>
</section>
<!-- Demos Section End -->

<!-- Demos Section Start -->
<section class="pt--150 pb--20 bg-primary section--arrow">
    <div class="container">
        <!-- Section Title Start -->
        <div class="section--title pb--50 text-center">
            <div class="title lined lined-white">
                <h2 class="h2 text-white">Learn how to best use each album.</h2>
            </div>
        </div>
        <!-- Section Title End -->
        <div class="row AdjustRow">
            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Feature Block Start -->
                <div class="feature--block clearfix">
                    <div class="icon icon-block fs--24 mr--20 text-white bg-primary float--left">
                        <i class="fa fa-check-square-o"></i>
                    </div>

                    <div class="content ov--h mt--8">
                        <p class="text-white fs--22 fw--500">My Album</p>Use this album to preserve your personal thoughts, testimonies, special moments by adding your own notes, pictures, or videos. <strong>Be the author of your own story.</strong></p>
                    </div>
                </div>
                <!-- Feature Block End -->
            </div>

            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Feature Block Start -->
                <div class="feature--block clearfix">
                    <div class="icon icon-block fs--24 mr--20 text-white bg-primary float--left">
                        <i class="fa fa-check-square-o"></i>
                    </div>

                    <div class="content ov--h mt--8">
                        <p class="text-white fs--22 fw--500">My Family Album<p>Create your family history by sharing notes, pictures & videos with immediate and extended family. This space is used to <strong>build a lasting family legacy.</strong></p>
                    </div>
                </div>
                <!-- Feature Block End -->
            </div>

            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Feature Block Start -->
                <div class="feature--block clearfix">
                    <div class="icon icon-block fs--24 mr--20 text-white bg-primary float--left">
                        <i class="fa fa-check-square-o"></i>
                    </div>

                    <div class="content ov--h mt--8">
                        <p class="text-white fs--22 fw--500">My Friends Album<p>Use this album to capture your advertures, special moments, good times and dreams shared with friends. <strong>Keeping the memories alive.</strong></p>
                    </div>
                </div>
                <!-- Feature Block End -->

    </div>
</section>
<!-- Demos Section End -->

<!-- Features Section Start -->
<section class="pt--150 pb--20 section--arrow section--arrow-primary">
    <div class="container">
        <!-- Section Title Start -->
        <div class="section--title pb--50 text-center">
            <div class="title lined">
                <h2 class="h2">Our Guarantee</h2>
            </div>
        </div>
        <!-- Section Title End -->

        <div class="row AdjustRow">
            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Feature Block Start -->
                <div class="feature--block clearfix">
                    <div class="icon icon-block fs--16 mr--20 text-white bg-primary float--left">
                        <i class="fa fa-check-square-o"></i>
                    </div>

                    <div class="content ov--h mt--8">
                        <p class="text-black fs--14 fw--500">YOUR PRIVACY IS PROTECTED. A SAFE PLACE TO CAPTURE & SHARE MEMORIES.</p>
                    </div>
                </div>
                <!-- Feature Block End -->
            </div>

            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Feature Block Start -->
                <div class="feature--block clearfix">
                    <div class="icon icon-block fs--16 mr--20 text-white bg-primary float--left">
                        <i class="fa fa-check-square-o"></i>
                    </div>

                    <div class="content ov--h mt--8">
                        <p class="text-black fs--14 fw--500">YOUR PERSONAL INFORMATION WILL NOT BE SOLD</p>
                    </div>
                </div>
                <!-- Feature Block End -->
            </div>

            <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                <!-- Feature Block Start -->
                <div class="feature--block clearfix">
                    <div class="icon icon-block fs--16 mr--20 text-white bg-primary float--left">
                        <i class="fa fa-check-square-o"></i>
                    </div>

                    <div class="content ov--h mt--8">
                        <p class="text-black fs--14 fw--500">SECURE MEMBER SIGN-IN AUTHENTICATION IS REQUIRED</italic></p>
                    </div>
                </div>
                <!-- Feature Block End -->
            </div>

        </div>
    </div>
</section>
<!-- Features Section End -->
<?php include BASE_PATH.'/members/includes/footer.php'?>