<?php
    $db = getDbInstance();
    $rows = $db->get('tbl_users');
?>
<!-- Footer Section Start -->
<footer class="footer--section">
    <!-- Footer Widgets Start -->
    <div class="footer--widgets pt--70 pb--20 bg-lightdark" data-bg-img="img/footer-img/footer-widgets-bg.png">
        <div class="container">
            <div class="row AdjustRow">
                <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                    <!-- Widget Start -->
                    <div class="widget">
                        <h2 class="h4 fw--700 widget--title">About Us</h2>

                        <!-- Text Widget Start -->
                        <div class="text--widget">
                            <p> MyNotes4U is a collection of notes, pictures and videos capturing a lifetime of your adventures, thoughts and experiences in an album. We makes it easy for you to share with family and friends.</p>

                            <p>A place were families can grow closer, save life moments and pass the family legacy onto future generations. Just image . . . now your great, great, great grandchildren can know their grandparent intimately. From your own words.  </p>
                        </div>
                        <!-- Text Widget End -->
                    </div>
                    <!-- Widget End -->
                </div>

                <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">
                    <!-- Widget Start -->
                    <div class="widget">
                        <h2 class="h4 fw--700 widget--title">Favorite Groups</h2>

                        <!-- Nav Widget Start -->
                        <div class="nav--widget">
                            <ul class="nav">
                                <li>
                                    <a href="<?php echo BASE_URL;?>/members/groups-church.php">
                                        <i class="fa fa-folder-o"></i>
                                        <span class="text">Church</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL;?>/members/groups-recipes.php">
                                        <i class="fa fa-folder-o"></i>
                                        <span class="text">Recipes</span>

                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL;?>/members/groups-homerepair.php">
                                        <i class="fa fa-folder-o"></i>
                                        <span class="text">Home Repair / Remodeling</span>

                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL;?>/members/groups-sports.php">
                                        <i class="fa fa-folder-o"></i>
                                        <span class="text">Sports</span>

                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL;?>/members/groups-pets.php">
                                        <i class="fa fa-folder-o"></i>
                                        <span class="text">Pets</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL;?>/members/groups-travel.php">
                                        <i class="fa fa-folder-o"></i>
                                        <span class="text">Travel</span>

                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Nav Widget End -->
                    </div>
                    <!-- Widget End -->

                </div>

                <div class="col-md-4 col-xs-6 col-xxs-12 pb--60">

                    <!-- Widget Start -->
                    <div class="widget">
                        <h2 class="h4 fw--700 widget--title">Useful Links</h2>

                        <!-- Links Widget Start -->
                        <div class="links--widget">
                            <ul class="nav">
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">Join a Group</a></li>
                                <li><a href="#">FAQ</a></li>
                                <li><a href="pp.pdf" target="_blank">Privacy Policy</a></li>
                                <li><a href="tc.pdf" target="_blank">Terms and Conditions</a></li>
                                <li><a href="cr.pdf" target="_blank">Community Rules</a></li>
                                <li><a href="<?php echo BASE_URL;?>/members/contact.php">Contact</a></li>
                            </ul>
                        </div>
                        <!-- Links Widget End -->
                    </div>
                    <!-- Widget End -->
                </div>
            </div>
        </div>

    </div>
    <!-- Footer Widgets End -->

    <!-- Footer Extra Start -->
    <div class="footer--extra bg-darker pt--30 pb--40 text-center">
        <div class="container">
            <!-- Widget Start -->
            <div class="widget">
                <h2 class="h4 fw--700 widget--title">Recent Active Members-Hari this section should autopopulate</h2>

                <!-- Recent Active Members Widget Start -->
                <div class="recent-active-members--widget style--2">
                    <div class="owl-carousel" data-owl-items="12" data-owl-nav="true" data-owl-speed="1200" data-owl-responsive='{"0": {"items": "3"}, "481": {"items": "6"}, "768": {"items": "8"}, "992": {"items": "12"}}'>
                        <?php foreach ($rows as $row):?>
                            <div class="img">
                                    <a href="member-activity-personal.php?user=<?php echo $row['id'] ?>">
                                        <?php if(isset($row['avatar'])) { ?>
                                            <img src="<?php echo BASE_URL.'/members/'.substr($row['avatar'], 2) ?>" alt="" style="width: 30px; height: 30px;">
                                        <?php } else { ?>
                                            <img src="img/widgets-img/recent-active-members/01.jpg" alt="">
                                        <?php } ?>
                                    </a>
                            </div>
                        <?php endforeach;?>

                    </div>
                </div>
                <!-- Recent Active Members Widget End -->
            </div>
            <!-- Widget End -->
        </div>
    </div>
    <!-- Footer Extra End -->

    <!-- Footer Copyright Start -->
    <div class="footer--copyright pt--30 pb--30 bg-darkest">
        <div class="container">
            <div class="text fw--500 fs--14 text-center">
                <p>Copyright &copy; My<span>Notes</span>4U. All Rights Reserved.</p>
            </div>
        </div>
    </div>
    <!-- Footer Copyright End -->
</footer>
<!-- Footer Section End -->
</div>
<!-- Wrapper End -->

<!-- Back To Top Button Start -->
<div id="backToTop">
    <a href="#" class="btn"><i class="fa fa-caret-up"></i></a>
</div>
<!-- Back To Top Button End -->

<!-- ==== Plugins Bundle ==== -->
<script src="<?php echo BASE_URL;?>/members/js/plugins.min.js"></script>

<!-- ==== Main Script ==== -->
<script src="<?php echo BASE_URL;?>/members/js/main.js"></script>
<script src="<?php echo BASE_URL;?>/members/js/custom.js"></script>
<script>
    $('.header--nav-links li').click(function () {
       console.log("clicked");
    });
</script>
</body>
</html>