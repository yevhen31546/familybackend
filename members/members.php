<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$rows = $db->get('tbl_users');
?>
<?php include BASE_PATH.'/members/includes/header.php'?>

        <!-- Page Header Start -->
        <div class="page--header pt--60 pb--60 text-center" data-bg-img="img/page-header-img/bg.jpg" data-overlay="0.85">
            <div class="container">
                <div class="title">
                    <h2 class="h1 text-white">Members</h2>
                </div>

                <ul class="breadcrumb text-gray ff--primary">
                    <li><a href="../members/home.php" class="btn-link">Home</a></li>
                    <li class="active"><span class="text-primary">Members</span></li>
                </ul>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Page Wrapper Start -->
        <section class="page--wrapper pt--80 pb--20">
            <div class="container">
                <div class="row">
                    <!-- Main Content Start -->
                    <div class="main--content col-md-12 pb--60" data-trigger="stickyScroll">
                        <div class="main--content-inner">
                            <!-- Filter Nav Start -->
                            <div class="filter--nav pb--30 clearfix">
                                <div class="filter--link float--left">
                                    <h2 class="h4">Total My Notes Members : Hari can this be a count?</h2>
                                </div>

                                <div class="filter--options float--right">
                                    <label>
                                        <span class="fs--14 ff--primary fw--500 text-darker">Show By :</span>

                                        <select name="membersfilter" class="form-control form-sm" data-trigger="selectmenu">
                                            <option value="last-active" selected>Last Active</option>
                                            <option value="new-registered">New Registerd</option>
                                            <option value="alphabetical">Alphabetical</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- Filter Nav End -->

                            <!-- Member Items Start -->
                            <div class="member--items">
                                <div class="row gutter--15 AdjustRow">
                                    <?php foreach ($rows as $row):?>
                                        <div class="col-md-3 col-xs-6 col-xxs-12">
                                            <!-- Member Item Start -->
                                            <div class="member--item online">
                                                <div class="img img-circle">
                                                    <a href="member-activity-personal.php?user=<?php echo $row['id'];?>" class="btn-link">
                                                        <?php if(isset($row['avatar'])) { ?>
                                                            <img src="<?php echo substr($row['avatar'], 2) ?>" alt="">
                                                        <?php } else { ?>
                                                            <img src="img/members-img/member-01.jpg" alt="">
                                                        <?php } ?>
                                                    </a>
                                                </div>

                                                <div class="name">
                                                    <h3 class="h6 fs--12">
                                                        <a href="member-activity-personal.php?user=<?php echo $row['id'];?>" class="btn-link"><?php echo $row['first_name'].$row['last_name'];?></a>
                                                    </h3>
                                                </div>



                                                <div class="actions">
                                                    <ul class="nav">
                                                        <li>
                                                            <a href="#" title="Send Message" class="btn-link" data-toggle="tooltip" data-placement="bottom">
                                                                <i class="fa fa-envelope-o"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add Friend" class="btn-link" data-toggle="tooltip" data-placement="bottom">
                                                                <i class="fa fa-user-plus"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- Member Item End -->
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <!-- Member Items End -->

                            <!-- Page Count Start -->
                            <div class="page--count pt--30">
                                <label class="ff--primary fs--14 fw--500 text-darker">
                                    <span>Viewing</span>

                                    <a href="#" class="btn-link"><i class="fa fa-caret-left"></i></a>
                                    <input type="number" name="page-count" value="01" class="form-control form-sm">
                                    <a href="#" class="btn-link"><i class="fa fa-caret-right"></i></a>

                                    <span>of 2,500</span>
                                </label>
                            </div>
                            <!-- Page Count End -->
                        </div>
                    </div>
                    <!-- Main Content End -->


                </div>
            </div>
        </section>
        <!-- Page Wrapper End -->

<?php include BASE_PATH.'/members/includes/footer.php'?>