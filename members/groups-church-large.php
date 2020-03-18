<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
$db = getDbInstance();
$db->where('tbl_church.id', $_GET['churchid']);
$churches = $db->get('tbl_church');

$photo_arr = $churches[0]['churchphoto'];
$photo_arr = rtrim($photo_arr, ",");
$photo_arr = explode(",", $photo_arr);  


$db = getDbInstance();
$db->where('tbl_users.id', $_GET['userid']);
$user = $db->get('tbl_users');

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
            <div class="main--content col-md-12 pb--60">
                <div class="main--content-inner">

                    <a href="groups-church.php">
                        <h5><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Back</h5>
                    </a>
                    <!-- Box Items Start -->
                    <div class="box--items-h">
                        <div class="row gutter--15 AdjustRow">
                        
                            <div class="myslideshow-container col-md-12 col-xs-12 col-xxs-12">

                                <?php foreach ($photo_arr as $photo):?>
                                <div class="mySlides myslidefade">
                                    <div class="col-md-12 col-xs-12 col-xxs-12">
                                        
                                        <!-- Box Item Start -->
                                        <div class="box--item text-center">
                                            <a href="groups-church.php" class="img" data-overlay="0.1">
                                                <?php if ($photo=='') { ?>
                                                    <img src="img/group-img/01.jpg" width="800px" height="418px" alt="" class="imgslider">
                                                <?php } else { ?>
<!--                                                    <img src="--><?php //echo $photo;?><!--" width="800px" height="418px" alt="" class="imgslider">-->
                                                    <div class="group-large-photo"
                                                         style="background-image:url('<?php echo $photo; ?>');">
                                                    </div>
                                                <?php } ?>                                            
                                            </a>

                                            <div class="info">
                                                <div class="icon fs--18 text-lightest bg-primary">
                                                    <i class="fa fa-music"></i>
                                                </div>

                                                <div class="title">
                                                    <h2 class="h2"><a href="group-home.php">Church Groups</a></h2>
                                                    <?php if($churches[0]['churchgroup'] !='') { ?>
                                                        <p><h4>Group Name: <?php echo $churches[0]['churchgroup'] ?></h4></p>
                                                    <?php }?>
                                                </div>
                                            
                                                <div class="title">
                                                    <?php if($churches[0]['churchgroup'] !='') { ?>
                                                        <p><h4>Church Name: <?php echo $churches[0]['churchname'] ?></h4></p>
                                                    <?php }?>
                                                </div>
                                        
                                    

                                                <div class="desc text-darker">
                                                    <p>Submitted by: <?php echo $user[0]['first_name'].' '.$user[0]['last_name'];?> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                     Date Submitted: <?php echo $churches[0]['churchdate'] ?></p>
                                                </div>
                                        
                                            
                                                <p><h4>Church comment: <?php echo $churches[0]['churchcomment'] ?></h4></p>
                                                
                                            </div>
                                        </div>
                                        <!-- Box Item End -->

                                    </div>
                                </div>
                                <?php endforeach;?>

                                <a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
                                <a class="mynext" onclick="plusSlides(1)">&#10095;</a>
                            </div>
                        </div>
                    </div>
                    <!-- Box Items End -->

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Wrapper End -->

<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "block";
    }
</script>


<!-- Page Wrapper End -->
<?php include BASE_PATH.'/members/includes/footer.php'?>