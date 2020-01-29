<?php
session_start();
require_once 'config/config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));

// If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE)
{
    header('Location: members/home.php');
}
?>
<?php include BASE_PATH.'/includes/header.php'; ?>

        <!-- Banner Section 1 Start -->
        <section class="banner--section">
            <!-- Banner Slider Start -->
            <div class="banner--slider owl-carousel" data-owl-dots="true" data-owl-dots-style="2">
                <!-- Banner Item Start -->
                <div class="banner--item" data-bg-img="img/banner-img/home-version-1/Banner_img_1.gif" data-overlay="0.35">
                    <div class="vc--parent">
                        <div class="vc--child">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <!-- Banner Content Start -->
                                        <div class="banner--content pt--70 pb--80 text-center">
                                            

                                            <div class="sub-title">
                                              <h2 class="h2 text-lightgray"></h2>
                                            </div>

                                            <div class="desc text-gray fs--16">
                                              <p></p>
                                            </div>

                                          <div class="action text-uppercase">
                                                </div>
										    <div>&nbsp;</div>
					<div class="title">
                      <h1 class="h1 text-lightgray">Your Memoirs, Her Memories</h1>
                     </div>
										<div class="sub-title">
                                              <h2 class="h2 text-lightgray">Save them, share them, pass them on!</h2>
                                            </div>
											<div class="action text-uppercase">
                                                <a href="#how" class="btn btn-white">How it works</a>
                                                <a href="coming-soon.html" class="btn btn-primary">Get Started</a>
                                            </div>
											
											
                                      </div>
                                        <!-- Banner Content End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Banner Item End -->

                <!-- Banner Item Start -->
                <div class="banner--item" data-bg-img="img/banner-img/home-version-2/bannerimg2b.gif" data-overlay="0.35">
                    <div class="vc--parent">
                        <div class="vc--child">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <!-- Banner Content Start -->
                                        <div class="banner--content pt--70 pb--80 text-center">
                                            <div class="title">
                                                <h1 class="h1 text-lightgray">Capture Memories as you live them!</h1>
                                            </div>

                                            <div class="sub-title">
                                                <h2 class="h2 text-lightgray">Build your legacy one moment at a time.</h2>
                                            </div>

                                            <div class="desc text-gray fs--16">
                                                <p>&nbsp;</p>
                                            </div>

                                            <div class="action text-uppercase">
                                                <a href="#how" class="btn btn-white">How it works</a>
                                                <a href="coming-soon.html" class="btn btn-primary">Get Started</a>
                                            </div>
                                        </div>
                                        <!-- Banner Content End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Banner Item End -->

                <!-- Banner Item Start -->
                <div class="banner--item" data-bg-img="img/banner-img/home-version-3/bannerimg3.gif" data-overlay="0.35">
                    <div class="vc--parent">
                        <div class="vc--child">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <!-- Banner Content Start -->
                                        <div class="banner--content pt--70 pb--80 text-center">
                                            <div class="title">
                                                <h1 class="h1 text-lightgray">Cherish your childhood memories.</h1>
                                            </div>

                                            <div class="sub-title">
                                                <h2 class="h2 text-lightgray">Safe, Secure &amp; Private</h2>
                                            </div>

                                            <div class="desc text-gray fs--16">
                                                <p>&nbsp;</p>
                                            </div>

                                            <div class="action text-uppercase">
                                                <a href="#how" class="btn btn-white">How it works</a>
                                                <a href="coming-soon.html" class="btn btn-primary">Get Started</a>
                                            </div>
                                        </div>
                                        <!-- Banner Content End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Banner Item End -->
			
			<!-- Banner Item Start -->
                <div class="banner--item" data-bg-img="img/banner-img/home-version-4/bannerimg4.gif" data-overlay="0.35">
                    <div class="vc--parent">
                        <div class="vc--child">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <!-- Banner Content Start -->
                                        <div class="banner--content pt--70 pb--80 text-center">
                                            <div class="title">
                                                <h1 class="h1 text-lightgray">Build, Keep &amp; Share Life Seasons.</h1>
                                            </div>

                                            <div class="sub-title">
                                                <h2 class="h2 text-lightgray">Relive your favorite college memories, start saving today! </h2>
                                            </div>

                                            <div class="desc text-gray fs--16">
                                                <p>&nbsp;</p>
                                            </div>

                                            <div class="action text-uppercase">
                                                <a href="#how" class="btn btn-white">How it works</a>
                                                <a href="coming-soon.html" class="btn btn-primary">Get Started</a>
                                            </div>
                                        </div>
                                        <!-- Banner Content End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Banner Item End -->
			
            </div>
            <!-- Banner Slider End -->
        </section>
        <!-- Banner Section End -->

        
        <!-- How It Works Section Start -->
       <p id="how"></p> <section class="section pt--70 pb--40">
            <div class="container">
                <!-- Section Title Start -->
                <div class="section--title pb--50 text-center">
                    <div class="title">
                        <h2 class="h2">How It Works?</h2>
                    </div>

                    <div>
                        <h4>My<strong>Notes</strong>4U is a powerful, easy to use tool. It's made up of a collection of personal comments, pictures and videos capturing a lifetime of your adventures, thoughts, achievements, celebrations and more! My<strong>Notes</strong>4U will preserve your fondest memories, funniest stories, personal testimonies, and favorite moments with family and friends. It gets even better, My<strong>Notes</strong>4U will preserve your memories for future generations to discover.</h4>
                    </div>
                </div>
                <!-- Section Title End -->

                <div class="row">
                    <div class="col-md-7 pb--40">
                        <div class="row gutter--15 AdjustRow" data-scroll-reveal="group">
                            <div class="col-xs-4 pb--15">
                                <img src="img/how1.png" alt="">
                            </div>

                            <div class="col-xs-4 pb--15">
                                <img src="img/how2.png" alt="">
                            </div>

                            <div class="col-xs-4 pb--15">
                                <img src="img/how3.png" alt="">
                            </div>

                            <div class="col-xs-12">
                                <img src="img/how4.gif" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 pb--40">
                        <!-- Info Items Start -->
                        <div class="info--items" data-scroll-reveal="group">
                            <!-- Info Item Start -->
                            <div class="info--item clearfix">
                                <div class="icon">
                                    <img src="img/how-it-works-img/icon-01.png" alt="">
                                </div>

                                <div class="info">
                                    <div class="title">
                                        <h3 class="h4 fw--700">Subscribe</h3>
                                    </div>

                                    <div class="desc">
                                        <p>Your subscription ensures your information is only shared with the family and friends you choose. Your information is not sold and securily hosted in the Cloud.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Info Item End -->

                            <!-- Info Item Start -->
                            <div class="info--item clearfix">
                                <div class="icon">
                                    <img src="img/how-it-works-img/icon-02.png" alt="">
                                </div>

                                <div class="info">
                                    <div class="title">
                                        <h3 class="h4 fw--700">Invite Friends &amp; Family</h3>
                                    </div>

                                    <div class="desc">
                                        <p>Once a member, you can easily invite family &amp; friends to also become members. As members you can share your memories with one another.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Info Item End -->

                            <!-- Info Item Start -->
                            <div class="info--item clearfix">
                                <div class="icon">
                                    <img src="img/how-it-works-img/icon-03.png" alt="">
                                </div>

                                <div class="info">
                                    <div class="title">
                                        <h3 class="h4 fw--700">Add a Memory to an Album</h3>
                                    </div>

                                    <div class="desc">
                                        <p>Select an Album; My Album, My Family Album or My Friends Album, and simply begin adding your stories, pictures, etc. </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Info Item End -->

                            <!-- Info Item Start -->
                            <div class="info--item clearfix">
                                <div class="icon">
                                    <img src="img/how-it-works-img/icon-04.png" alt="">
                                </div>

                                <div class="info">
                                    <div class="title">
                                        <h3 class="h4 fw--700">Share with other Members</h3>
                                    </div>

                                    <div class="desc">
                                        <p>Share your Albums with family &amp; friends or view any Album that has been shared with you.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Info Item End -->
                        </div>
                        <!-- Info Items End -->
                    </div>
                </div>
            </div>
        </section>
        <!-- How It Works Section End -->

         <!-- Why Choose Us Section Start -->
       <p id="why"></p> <section class="section bg-lighter pt--80 pb--20">
            <div class="container">
                <div class="row row--md-vc">
                    <div class="col-md-6 pb--50">
                        <!-- Text Block Start -->
                        <div class="text--block pb--10">
                            <div class="title">
                                <h2 class="h2 fw--600">Why Choose Us?</h2>
                            </div>

                            <div class="content fs--14">
								<h4>We're not just another social media community.</h4>
                                <p>MyNotes4U was created to foster closer relationships with family and friends by capturing and sharing meaningful moments. These moments can then be preserved and passed on to future generations. Imagine your great great-grandchildren knowing the real you from your heart and life experences. Not just from your DNA.</p>
                            </div>
                        </div>
                        <!-- Text Block End -->

                        <div class="row AdjustRow">
                            <div class="col-xs-6 col-xxs-12 pb--10">
                                <!-- Feature Block Start -->
                                <div class="feature--block mb--6 clearfix">
                                    <div class="icon fs--18 text-primary mr--20 float--left">
                                        <i class="fa fa-comments-o"></i>
                                    </div>

                                    <div class="info ov--h">
                                        <div class="title">
                                            <h2 class="h6 fw--700">Secure Location</h2>
                                        </div>

                                        <div class="desc mt--8">
                                            <p>We use only the highest web hosting standards supported by <em>(GridIron, Superb Internet) technology</em>.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Feature Block End -->
                            </div>
                            
                            <div class="col-xs-6 col-xxs-12 pb--10">
                                <!-- Feature Block Start -->
                                <div class="feature--block mb--6 clearfix">
                                    <div class="icon fs--18 text-primary mr--20 float--left">
                                        <i class="fa fa-wrench"></i>
                                    </div>

                                    <div class="info ov--h">
                                        <div class="title">
                                            <h2 class="h6 fw--700">Secure Access</h2>
                                        </div>

                                        <div class="desc mt--8">
                                            <p>Every member has their own secure user ID and password. Only you can invite family and friends to join your Albums.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Feature Block End -->
                            </div>
                            
                            <div class="col-xs-6 col-xxs-12 pb--10">
                                <!-- Feature Block Start -->
                                <div class="feature--block mb--6 clearfix">
                                    <div class="icon fs--18 text-primary mr--20 float--left">
                                        <i class="fa fa-group"></i>
                                    </div>

                                    <div class="info ov--h">
                                        <div class="title">
                                            <h2 class="h6 fw--700">Secure Information</h2>
                                        </div>

                                        <div class="desc mt--8">
                                            <p>We promise NOT to sell your personal and private information or bother you with unwanted advertisements. We are subscription based.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Feature Block End -->
                            </div>

                            <div class="col-xs-6 col-xxs-12 pb--15">
                                <!-- Feature Block Start -->
                                <div class="feature--block mb--6 clearfix">
                                    <div class="icon fs--18 text-primary mr--20 float--left">
                                        <i class="fa fa-clock-o"></i>
                                    </div>

                                    <div class="info ov--h">
                                        <div class="title">
                                            <h2 class="h6 fw--700">Preserved Over Time</h2>
                                        </div>

                                        <div class="desc mt--8">
                                            <p>Your memories will be preserved long after you are gone. Family and friends will be able to search and discover the legacy you left behind.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Feature Block End -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 pb--40">
                        <!-- Video Popup Start -->
                        <div data-overlay="0.3">
                            <img src="img/why1.gif" alt="">

                            <!--<a href="https://www.youtube.com/watch?v=YE7VzlLtp-4" class="btn-link" data-trigger="video_popup">
                                <span><i class="fa fa-play"></i></span>
                            </a>-->
                        </div>
                        <!-- Video Popup End -->
                    </div>
                </div>
            </div>
        </section>
        <!-- Why Choose Us Section End -->



<!-- Most Popular Groups Section Start -->
      <p id="group"></p>  <section class="section pt--70 pb--70">
            <div class="container">
                <!-- Box Nav Start -->
                <div class="box--nav clearfix">
                    <h2 class="h2 fw--600 float--left">Most Popular Groups</h2>

                   <ul class="nav ff--primary float--right">
                        <!--<li class="active">
							<a href="#boxItemsTab01" class="btn btn-default" data-toggle="tab">Newest</a></li>
                        <li><a href="#boxItemsTab02" class="btn btn-default" data-toggle="tab">Active</a></li>
                        <li><a href="#boxItemsTab03" class="btn btn-default" data-toggle="tab">Popular</a></li>
                        <li><a href="#boxItemsTab04" class="btn btn-default" data-toggle="tab">Alphabetic</a></li>-->
                    </ul>
                </div>
                <!-- Box Nav End -->

                <!-- Tab Content Start -->
                <div class="tab-content">
                    <!-- Tab Pane Start -->
                    <div class="tab-pane fade in active" id="boxItemsTab01">
                        <!-- Box Items Start -->
                        <div class="box--items owl-carousel" data-owl-items="4" data-owl-margin="30" data-owl-autoplay="false" data-owl-responsive='{"0": {"items": "1"}, "481": {"items": "2"}, "768": {"items": "3"}, "992": {"items": "4"}}'>
							
                            <!-- Box Item Start -->
                            <div class="box--item text-center">
                                <a href="coming-soon.html" class="img" data-overlay="0.1">
                                    <img src="img/travel.png" alt="">
                                </a>
                                

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-plane"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h6"><!--<a href="group-home.html">-->Travel Adventures</h2>
                                    </div>

                                    <div class="meta">
                                        <p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                        <p><i class="fa mr--8 fa-user-o"></i>Family &amp;Friend Group</p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Share your traveling adventures, family vacations and more ....</p>
                                    </div>

                                   <!-- <div class="action">
                                        <a href="group-home.html">Group Details<i class="fa ml--10 fa-caret-right"></i></a>
                                    </div>-->
                                </div>
                            </div>

                            <!-- Box Item Start -->
                            <div class="box--item text-center">
                                <a href="coming-soon.html" class="img" data-overlay="0.1">
                                    <img src="img/events.png" alt="">
                                </a>
                                

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-music"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h6"><!--<a href="group-home.html">-->Special Events</h2>
                                    </div>

                                    <div class="meta">
                                        <p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                        <p><i class="fa mr--8 fa-user-o"></i>Family &amp; Friend Group</p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Keepsake memories from concerts, weddings, parties, graduations &amp; more...</p>
                                    </div>

                                    <!--<div class="action">
                                        <a href="group-home.html">Group Details<i class="fa ml--10 fa-caret-right"></i></a>
                                    </div>-->
                                </div>
                            </div>
                            <!-- Box Item End -->

                            <!-- Box Item Start -->
                            <div class="box--item text-center">
                                <a href="coming-soon.html" class="img" data-overlay="0.1">
                                    <img src="img/sports.png" alt="">
                                </a>
                                

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-camera"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h6"><!--<a href="group-home.html">-->Sports</a></h2>
                                    </div>

                                    <div class="meta">
                                        <p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                        <p><i class="fa mr--8 fa-user-o"></i>Family &amp; Friend Group</p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Capture those great sport moments in time, add your favorite team photo, and more ...</p>
                                    </div>

                                    <!--<div class="action">
                                        <a href="group-home.html">Group Details<i class="fa ml--10 fa-caret-right"></i></a>
                                    </div>-->
                                </div>
                            </div>
                            <!-- Box Item End -->

                            

                            <!-- Box Item Start -->
                            <div class="box--item text-center">
                                <a href="coming-soon.html" class="img" data-overlay="0.1">
                                    <img src="img/church.png" alt="">
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-laptop"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h6"><!--<a href="group-home.html">-->Church</h2>
                                    </div>

                                    <div class="meta">
                                        <p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                        <p><i class="fa mr--8 fa-user-o"></i>Family and Friend Group</p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Share testimonies, scriptures, outreach programs, challenges, miracles and more ...</p>
                                    </div>

                                    <!--<div class="action">
                                        <a href="group-home.html">Group Details<i class="fa ml--10 fa-caret-right"></i></a>
                                    </div>-->
                                </div>
                            </div>
                            <!-- Box Item End -->

                            <!-- Box Item Start -->
                            <div class="box--item text-center">
                                <a href="coming-soon.html" class="img" data-overlay="0.1">
                                    <img src="img/recipe.png" alt="">
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-cutlery"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h6"><!--<a href="group-home.html">-->Favorite Recipes</a></h2>
                                    </div>

                                    <div class="meta">
                                        <p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                        <p><i class="fa mr--8 fa-user-o"></i>Family &amp; Friend Group</p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Preserve Grandma's favorite recipes, add you own, or share your holiday favorites.</p>
                                    </div>

                                    <!--<div class="action">
                                        <a href="group-home.html">Group Details<i class="fa ml--10 fa-caret-right"></i></a>
                                    </div>-->
                                </div>
                            </div>
                            <!-- Box Item End -->
							<!-- Box Item Start -->
                            <div class="box--item text-center">
                                <a href="coming-soon.html" class="img" data-overlay="0.1">
                                    <img src="img/pets.png" alt="">
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-paw"></i>
                                    </div>

                                    <div class="title">
                                        <h2 class="h6"><!--<a href="group-home.html">-->Our Pets</a></h2>
                                    </div>

                                    <div class="meta">
                                        <p><i class="fa mr--8 fa-clock-o"></i>Active 8 days ago</p>
                                        <p><i class="fa mr--8 fa-user-o"></i>Family &amp; Friend Group</p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>A special place for your furry, feather and other animals friends.</p>
                                    </div>

                                    <!--<div class="action">
                                        <a href="group-home.html">Group Details<i class="fa ml--10 fa-caret-right"></i></a>
                                    </div>-->
                                </div>
                            </div>
                            <!-- Box Item End -->
							
                            <!-- Box Item End -->
                        </div>
                       

                        <!-- Box Controls Start -->
                        <div class="box--controls text-center">
                            <a href="#" class="btn fs--16 btn-default" data-action="prev">
                                <i class="fa fa-caret-left"></i>
                            </a>

							<p class="btn ff--primary fw--500 btn btn-primary">View All Groups</p>
							<!--class="btn btn-primary"-->

                            <a href="#" class="btn fs--16 btn-default" data-action="next">
                                <i class="fa fa-caret-right"></i>
                            </a>
                        </div>
                        <!-- Box Controls End -->
                    </div>
                    <!-- Tab Pane End -->

                    <!-- Tab Pane Start -->
                    <div class="tab-pane fade" id="boxItemsTab02">
                        <!-- Box Items Start -->
                        <div class="box--items owl-carousel" data-owl-items="4" data-owl-margin="30" data-owl-autoplay="false">                         
                        </div>
                    </div>
                    <!-- Tab Pane End -->

                    <!-- Tab Pane Start -->
                    <div class="tab-pane fade" id="boxItemsTab03">
                        <!-- Box Items Start -->
                        <div class="box--items owl-carousel" data-owl-items="4" data-owl-margin="30" data-owl-autoplay="false">
                        </div>        
                    </div>
                    <!-- Tab Pane End -->

                    <!-- Tab Pane Start -->
                    <div class="tab-pane fade" id="boxItemsTab04">
                        
                    </div>
                    <!-- Tab Pane End -->
                </div>

                <!-- Tab Content End -->
            </div>
        </section>
        <!-- Most Popular Groups Section End -->

      

       

        <!-- FAQ and Download Section Start -->
        <section class="section bg-lighter pt--70 pb--20">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 pb--60">
                        <!-- FAQ Items Start -->
                        <div class="faq--items" id="faqItems" data-scroll-reveal="group">
                            <div class="title pb--20">
                                <h2 class="h2 fw--600">Frequently Asked Question</h2>
                            </div>

                            <!-- FAQ Item Start -->
                            <div class="faq--item style--1 panel">
                                <div class="title">
                                    <h3 class="h6 fw--700 text-darker">
                                        <a href="#faqItem01" data-parent="#faqItems" data-toggle="collapse" class="collapsed">
                                            <span>Will there be an APP?</span>
                                        </a>
                                    </h3>
                                </div>

                                <div id="faqItem01" class="content collapse">
                                    <div class="content--inner">
                                        <p>Yes and all your information added from this site will also be available using the APP. So don't wait, start capturing your memories today. </p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End --> 

                            <!-- FAQ Item Start -->
                            <div class="faq--item style--1 panel">
                                <div class="title">
                                    <h3 class="h6 fw--700 text-darker">
                                        <a href="#faqItem02" data-parent="#faqItems" data-toggle="collapse" class="collapsed">
                                            <span>How much does it costs?</span>
                                        </a>
                                    </h3>
                                </div>

                                <div id="faqItem02" class="content collapse">
                                    <div class="content--inner">
                                        <p>Prices will be announced on Opening Day. You will have the opportunity to select the plan that best fits you before becoming a member. Selecting the "Register" button will put you on the notification list for opening day. There is no obligation to purchase a subscription.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="faq--item style--1 panel">
                                <div class="title">
                                    <h3 class="h6 fw--700 text-darker">
                                        <a href="#faqItem03" data-parent="#faqItems" data-toggle="collapse" class="collapsed">
                                            <span>When is Opening Day?</span>
                                        </a>
                                    </h3>
                                </div>

                                <div id="faqItem03" class="content collapse">
                                    <div class="content--inner">
                                        <p>We are targeting fall of 2019! Stay in the "know" and register today!</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->
                        </div>
                        <!-- FAQ Items End -->
                    </div>

                    <div class="col-md-7 pb--20">
                        <!-- Download Block Start -->
                        <div class="download--block" data-scroll-reveal="group">
                            <div class="img">
                                <img src="img/register.png" alt="">
                            </div>

                            <div class="info">
                                <div class="title">
                                    <h2 class="h2 fw--600">Register for Opening Day!</h2>
                                </div>

                                <div class="content fs--12">
                                    <h4>Be one of the first 100 members to get started and receive your first 60 days free. Select the "Register" button to receive your private invitation to OPENING DAY this fall!</h4>
                                </div>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
								
					<div class="action text-uppercase">
                  <a href="coming-soon.html" class="btn btn-sm btn-google btn btn-primary"><i class="fa mr--8 fa-play"></i>Register</a>
                  <!--<a href="/comingsoon.html" class="btn btn-sm btn-apple"><i class="fa mr--8 fa-apple"></i>App Store</a>-->
                </div>
                            </div>
                        </div>
                        <!-- Download Block End -->
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ and Download Section End -->

<!-- Footer Section Start -->
<?php include BASE_PATH.'/includes/footer.php'; ?>