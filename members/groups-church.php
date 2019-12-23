<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_church', 'tbl_users.id = tbl_church.churchsubmitby');
$db->orderBy('churchdate');
$rows = $db->get('tbl_users');

if(isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
    if(isset($_GET['groupfilter'])){
        $filter_val = $_GET['groupfilter'];        
        $db = getDbInstance();
        $db->join('tbl_church', 'tbl_users.id = tbl_church.churchsubmitby');
        $db->where('churchgroup', '%'.$filter_val.'%', 'LIKE');
        $db->orderBy('churchdate');
        $rows = $db->get('tbl_users');
    }

    if(isset($_GET['letter'])) {
        $search_param = $_GET['letter'];
        $db = getDbInstance();
        $db->join('tbl_church', 'tbl_users.id = tbl_church.churchsubmitby');
        $db->where('churchname', $search_param.'%', 'LIKE');
        $db->orderBy('churchdate');
        $rows = $db->get('tbl_users');
    }
}
?>


<?php include BASE_PATH.'/members/includes/header.php'?>

    <!-- Page Header Start -->
    <div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/jesus3.png" data-overlay="0.15">
        <div class="container">
            <div class="title">
                <h2 class="h1 text-white">Church Groups</h2>
            </div>

            <ul class="breadcrumb text-gray ff--primary">
                <li><a href="../members/home.php" class="btn-link">Home</a></li>
                <li class="active"><span class="text-primary">Groups</span></li>
            </ul>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Wrapper Start -->
    <section class="page--wrapper pt--80 pb--20">
        <div class="container">
            <div class="row">
                <!-- Main Content Start -->
                <div class="main--content col-md-12 pb--60">
                    <div class="main--content-inner">
                        <!-- Filter Nav Start -->
                        <div class="filter--nav pb--30 clearfix">
                            <div class="filter--link float--left">
                                <h2 class="h4"><a href="groups-church-add.php">Add a memory to a group (+)</a></h2>
                            </div>

                            <div class="filter--options float--right">
                                <label style="display: flex;">
                                    <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                    <form action="" method="GET" id="groupfilterform">
                                        <select name="groupfilter" id="groupfilter" class="form-control form-sm" onchange="this.form.submit();" data-trigger="selectmenu">
                                            <option value="Adults/Family" selected>Adults/Family</option>
                                            <option value="Bible Study" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Bible Study') echo 'selected'; ?> >Bible Study</option>
                                            <option value="Children's Church" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == "Children's Church") echo 'selected'; ?> >Children's Church</option>
                                            <option value="Choir/Worship" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Choir/Worship') echo 'selected'; ?> >Choir/Worship</option>
                                            <option value="College/Career Group" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'College/Career Group') echo 'selected'; ?> >College/Career Group</option>
                                            <option value="Home Group" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Home Group') echo 'selected'; ?> >Home Group</option>
                                            <option value="Prayer" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Prayer') echo 'selected'; ?> >Prayer</option>
                                            <option value="Seniors" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Seniors') echo 'selected'; ?> >Seniors</option>
                                            <option value="Singles" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Singles') echo 'selected'; ?> >Singles</option>
                                            <option value="Youth Group" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Youth Group') echo 'selected'; ?> >Youth Group</option>
                                            <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                        </select>

                                    </form>
                                </label>


                                <div>
                                    <form action="" method="post" name="search" onclick="submit">
                                    <?php
                                        foreach (range('A', 'Z') as $char) {
                                            echo '<a href='.BASE_URL.'/members/groups-church.php?letter='.$char.'> '.$char.'</a> |';
                                        }
                                    ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Nav End -->

                    <!-- Box Items Start -->
                    <div class="box--items-h">
                        <div class="row gutter--15 AdjustRow">
                            <?php foreach ($rows as $row):?>
                            <div class="col-md-4 col-xs-6 col-xxs-12">
                                <!-- Box Item Start -->
                                <div class="box--item text-center">
                                    <a href="groups-church-large.php?userid=<?php echo $row['churchsubmitby'];?>&&churchid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                        <?php if($row['churchphoto'] !='') { 
                                            $img_arr = explode(",", $row['churchphoto']);  
                                            ?>
                                            <img src="<?php echo $img_arr[0]; ?>" alt="">
                                        <?php } else { ?>
                                            <img src="../members/img/add_photo.png" alt="">
                                        <?php } ?>
                                    </a>

                                    <div class="info">
                                        <div class="icon fs--18 text-lightest bg-primary">
                                            <i class="fa fa-music"></i>
                                        </div>

                                        <div class="title">
                                            <h4 class="color"><?php echo $row['churchname'] ?></h4>
                                            <p><h6>Group Name: <?php echo $row['churchgroup'] ?></h6></p>
                                        </div>

                                        <div class="desc text-darker">
                                            <p>Church Name: <?php echo $row['churchname'] ?></p>
                                            <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                            <p>Date Submited: <?php echo $row['churchdate'];?></p>
                                            <p>Church Comment: <?php echo $row['churchcomment'];?></p>
                                            <?php if ($row['utubelink'] != '') {?>
                                                <p><a href="<?php echo $row['utubelink'] ?>" target="_blank"> Youtube link </a></p>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Box Item End -->
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Page Count Start -->
                        <div class="page--count pt--30">
                            <label class="ff--primary fs--14 fw--500 text-darker">
                                <span>Viewing</span>

                                <a href="#" class="btn-link"><i class="fa fa-caret-left"></i></a>
                                <input type="number" name="page-count" value="01" class="form-control form-sm">
                                <a href="#" class="btn-link"><i class="fa fa-caret-right"></i></a>

                                <span>of 28</span>
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