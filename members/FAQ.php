<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

include BASE_PATH.'/members/includes/header.php';

?>


    <!-- Page Header Start -->
    <div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/faq3.png" >
        <div class="container">
            <div class="title">
                <h2 class="h1 text-white"></h2>
            </div>

            <!-- <ul class="breadcrumb text-gray ff--primary">
                 <li><a href="../members/home.html" class="btn-link"></a></li>
                 <li class="active"><span class="text-primary"></span></li>
             </ul>-->
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Contact Section Start -->
    <div class="contact--section pt--80 pb--20">
        <div class="container">
            <!-- Map Start -->
            <!--<div class="map mb--80" data-trigger="map" data-map-options='{"latitude": "23.790546", "longitude": "90.375583", "zoom": "16", "api_key": "AIzaSyBK9f7sXWmqQ1E-ufRXV3VpXOn_ifKsDuc"}'></div>-->
            <!-- Map End -->

            <div class="row">
                <div class="col-md-3 pb--60">
                    <!-- Contact Info Items Start -->
                    <div class="contact-info--items" data-scroll-reveal="group">
                        <!-- Contact Info Item Start -->
                        <div class="contact-info--Item">
                            <div class="title">
                                <h3 class="h4">Frequently Asked Questions</h3>
                                <p>Visit our step-by-step tutorials displayed on the right to resolve your question. If you need further assistance, drop us a line at the email address below and we will get back to you.</p>
                            </div>


                        </div>
                        <!-- Contact Info Item End -->

                        <!-- Contact Info Item Start -->
                        <div class="contact-info--Item">
                            <div class="title">
                                <h3 class="h4"><i class="mr--10 fa fa-envelope-o"></i>E-mail :</h3>
                            </div>

                            <div class="content fs--14 text-darker mt--4">
                                <p><a href="mailto:support@mynotes4u.com" class="btn-link">support@mynotes4u.com</a></p>
                            </div>
                        </div>
                        <!-- Contact Info Item End -->

                        <!-- Contact Info Item Start -->
                        <!--<div class="contact-info--Item">
                            <div class="title">
                                <h3 class="h4"><i class="mr--10 fa fa-phone"></i>Telephone :</h3>
                            </div>

                            <div class="content fs--14 text-darker mt--4">
                                <p><a href="tel:(+00)123123456" class="btn-link">(+00) 123123456</a>, <a href="tel:(+00)123123456" class="btn-link">(+00) 123123456</a></p>
                            </div>
                        </div>-->
                        <!-- Contact Info Item End -->
                    </div>
                    <!-- Contact Info Items End -->
                </div>

                <div class="col-md-9 pb--60">
                    <ul><h3><a href="<?php echo BASE_URL.'/FAQ/AddNote/story_html5.html'; ?>" target=_blank/>How do I add a note?</a></h3></ul>
                    <ul><h3><a href="<?php echo BASE_URL.'/FAQ/UpdateNote/story_html5.html'; ?>" target=_blank/>How do I update a note?</a></h3></ul>
                    <ul><h3><a href="<?php echo BASE_URL.'/FAQ/InvFriend/story_html5.html'; ?>" target=_blank/>How do I invite a friend?</a></h3></ul>
                    <ul><h3><a href="<?php echo BASE_URL.'/FAQ/InvFamily/story_html5.html'; ?>" target=_blank/>How do I invite a family member?</a></h3></ul>
                    <ul><h3><a href="<?php echo BASE_URL.'/FAQ/Password/story_html5.html'; ?>" target=_blank/>How do I change my password?</a></h3></ul>
                    <ul><h3><a href="<?php echo BASE_URL.'/FAQ/cancel/story_html5.html'; ?>" target=_blank/>How do I cancel my subscription?</a></h3></ul>
                    <!-- Contact Form Start -->
                    <!-- <div class="contact--form" data-form="ajax">
                         <div class="contact--title">
                             <h3 class="h4">Drop Us A Line</h3>
                         </div>

                         <div class="contact--subtitle pt--15">
                             <h4 class="h6 fw--400 text-darkest">Don’t worry ! your e-mail address will not be shared or publishe.</h4>
                         </div>

                         <div class="contact--notes ff--primary mt--2">
                             <p>(Required field are marked *)</p>
                         </div>

                         <form action="forms/contact-form.php" method="post">
                             <div class="row gutter--20">
                                 <div class="col-xs-6 col-xxs-12">
                                     <div class="form-group">
                                         <input type="text" name="name" placeholder="Name *" class="form-control" required>
                                     </div>
                                 </div>

                                 <div class="col-xs-6 col-xxs-12">
                                     <div class="form-group">
                                         <input type="email" name="email" placeholder="E-mail *" class="form-control" required>
                                     </div>
                                 </div>

                                 <div class="col-xs-12">
                                     <div class="form-group">
                                         <input type="text" name="subject" placeholder="Subject *" class="form-control" required>
                                     </div>
                                 </div>

                                 <div class="col-xs-12">
                                     <div class="form-group">
                                         <textarea name="message" placeholder="Message *" class="form-control" required></textarea>
                                     </div>
                                 </div>

                                 <div class="col-xs-12">
                             <button type="submit" class="btn btn-primary mt--10">Send Message</button>
                                 </div>
                             </div>

                             <div class="status"></div>
                         </form>
                     </div>-->
                    <!-- Contact Form End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Section End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>