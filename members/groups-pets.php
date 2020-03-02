<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->join('tbl_pet', 'tbl_users.id = tbl_pet.petsubmitby');
$db->orderBy('petdate');
$rows = $db->get('tbl_users');

if(isset($_GET) && (isset($_GET['groupfilter']) || isset($_GET['letter']))) {
    $db = getDbInstance();
    $db->join('tbl_pet', 'tbl_users.id = tbl_pet.petsubmitby');
    if (isset($_GET['groupfilter']) && $_GET['groupfilter'] === 'all') {
        if (isset($_GET['letter']) && $_GET['letter']) {
            $db->where('petbreed', $_GET['letter'].'%', 'LIKE');
        } else {
            $db->where(1);
        }
    } elseif (isset($_GET['letter']) && $_GET['letter'] && isset($_GET['groupfilter']) && $_GET['groupfilter']) {
        $db->where('petgroup', $_GET['groupfilter'].'%', 'LIKE');
        $db->where('petbreed', $_GET['letter'].'%', 'LIKE');
    } elseif (isset($_GET['groupfilter']) && $_GET['groupfilter'] && empty($_GET['letter'])) {
        $db->where('petgroup', $_GET['groupfilter'].'%', 'LIKE');
    } elseif(isset($_GET['letter']) && $_GET['letter'] && empty($_GET['groupfilter'])) {
        $db->where('petbreed', $_GET['letter'].'%', 'LIKE');
    }
    $db->orderBy('petdate');
    $rows = $db->get('tbl_users');
}

?>

<?php include BASE_PATH . '/members/includes/header.php' ?>

<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="../members/img/page-header-img/pet.png" data-overlay="0.25">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">Our Adorable Pets</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">Groups</span></li>
        </ul>
    </div>
</div>
<!-- Page Header End -->
<?php include BASE_PATH . '/includes/flash_messages.php'; ?>
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
                            <h2 class="h4"><a href="groups-pets-add.php">Add an Pet (+)</a></h2>
                        </div>

                        <div class="filter--options float--right">
                            <form action="" method="GET" id="groupfilterform">
                                <label style="display: flex;">
                                    <span class="h4 fs--14 ff--primary fw--500 text-darker">Find a Group :</span>
                                    <select name="groupfilter" id="groupfilter" class="form-control form-sm" onchange="this.form.submit();" data-trigger="selectmenu">
                                        <option value="all" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'all') echo 'selected'; ?> >Most Current Added</option>
                                        <option value="Birds" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Birds') echo 'selected'; ?> >Birds</option>
                                        <option value="Cats" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Cats') echo 'selected'; ?> >Cats</option>
                                        <option value="Dogs" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Dogs') echo 'selected'; ?> >Dogs</option>
                                        <option value="Horses" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Horses') echo 'selected'; ?> >Horses</option>
                                        <option value="Other" <?php if(isset($_GET['groupfilter']) && $_GET['groupfilter'] == 'Other') echo 'selected'; ?> >Other</option>
                                    </select>
                                </label>

                                <div>
                                    <?php
                                    foreach (range('A', 'Z') as $char) {
                                        if (isset($_GET['groupfilter'])) {
                                            echo '<a href='.BASE_URL.'/members/groups-pets.php?groupfilter='.
                                                $_GET['groupfilter'].'&&letter='
                                                .$char.'> '.$char.'</a> |';
                                        } else {
                                            echo '<a href='.BASE_URL.'/members/groups-pets.php?letter='.$char.'> '.$char.'</a> |';
                                        }
                                    }
                                    ?>
                                </div>
                            </form>
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
                                <a href="groups-pets-large.php?userid=<?php echo $row['petsubmitby'];?>&&petid=<?php echo $row['id']?>" class="img" data-overlay="0.1">
                                    <?php if($row['petphoto'] !='') { 
                                        $img_arr = explode(",", $row['petphoto']);  
                                        ?>
                                        <img src="<?php echo $img_arr[0]; ?>" alt="">
                                    <?php } else { ?>
                                        <img src="../members/img/add_photo.png" alt="">
                                    <?php } ?>
                                </a>

                                <div class="info">
                                    <div class="icon fs--18 text-lightest bg-primary">
                                        <i class="fa fa-paw"></i>
                                    </div>

                                    <div class="title">
                                        <h4 class="color">Pet Group : <?php echo $row['petgroup'] ?></h4>
                                        <p><h6>Pet Name: <?php echo $row['petname'] ?></h6></p>
                                    </div>

                                    <div class="desc text-darker">
                                        <p>Breed: <?php echo $row['petbreed'];?></p>
										<p>Birthdate/Age: <?php echo $row['petbirthage'];?></p>
                                        <p>Submitted by: <?php echo $row['first_name'].$row['last_name'];?></p>
                                        <p>Date Submited: <?php echo $row['petdate'];?></p>
                                        <p><?php echo $row['petcomment'];?></p>
                                        <?php if ($row['utubelink'] != '') {?>
                                            <p><a href="<?php echo $row['utubelink'] ?>" target="_blank"> See More </a></p>
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