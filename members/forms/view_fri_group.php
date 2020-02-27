<?php
session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
require_once '../../vendor/autoload.php';
require_once '../smtp_endpoint.php';

$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->get('tbl_users');
$db->where('id', $logged_id);
$user = $db->getOne('tbl_users');
//print_r($user);exit;
/*
 * Get group lists
 */
$query = 'SELECT us.`avatar`, us.first_name, us.last_name, temp.*
        FROM (SELECT gp.id AS group_id, gp.group_name, gp.`description`, gp.`by_who`
            FROM
            (SELECT DISTINCT fri_gp_mems.group_id
            FROM tbl_fri_groups_members fri_gp_mems
            WHERE fri_gp_mems.`who` = '.$logged_id.' AND stat = 1) tmp, tbl_fri_groups gp
            WHERE tmp.group_id = gp.`id`) temp, tbl_users us
        WHERE us.`id` = temp.by_who';
$belongs_groups = $db->rawQuery($query);
//print_r($belongs_groups); exit;

$query = 'SELECT gp.*
        FROM tbl_fri_groups gp
        WHERE gp.`by_who` = '.$logged_id;
$my_groups = $db->rawQuery($query);
//print_r($my_groups); exit;

include BASE_PATH.'/members/includes/header.php';
?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- Page Header Start -->
    <div class="page--header pt--60 pb--60 text-center" data-bg-img="../img/frebanner.png"
         data-overlay="0.35">
        <div class="container">
            <div class="title">
                <h2 class="h1 text-white">My Friends</h2>
            </div>

            <ul class="breadcrumb text-gray ff--primary">
                <li><a href="../members/home.php" class="btn-link">Home</a></li>
                <li class="active"><span class="text-primary">My Friends</span></li>
            </ul>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Wrapper Start -->
    <section class="page--wrapper pt--80 pb--20">
        <div class="container">
            <div class="row">

                <?php include BASE_PATH . '/includes/flash_messages.php'; ?>
                <a href="../activity-frd.php">

                    <h5><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Back to My Friend page</h5>
                </a>

                <div class="main--content col-md-10 pb--60" data-trigger="stickyScroll">
                    <div class="main--content-inner drop--shadow">
                        <!-- My Group Start -->
                        <div class="filter--nav pb--20 clearfix">
                            <div class="filter--link float--left">
                                <h2>My Groups</h2>
                            </div>
                        </div>
                        <hr>
                        <!-- My Group End -->
                        <!-- Activity List Start -->
                        <div class="activity--list">
                            <ul class="activity--items nav">

                                <?php if (count($my_groups) > 0) {
                                    foreach ($my_groups as $row):?>
                                        <li>
                                            <!-- Activity Item Start -->
                                            <div class="activity--item">
                                                <div class="activity--avatar">
                                                    <a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$user['id']; ?>" >
                                                        <?php if(isset($user['avatar'])) { ?>
                                                            <img src="<?php echo '../'.substr($user['avatar'],2) ?>" alt="">
                                                        <?php } else { ?>
                                                            <img src="../img/activity-img/avatar-05.jpg" alt="">
                                                        <?php } ?>
                                                    </a>
                                                </div>

                                                <div class="activity--info fs--14">
                                                    <div class="activity--header">
                                                        <p>
                                                            <a href="../group-frd.php?group_id=<?php echo $row['id'] ?>">
                                                                <strong>
                                                                    <?php echo $row['group_name']; ?>
                                                                </strong>
                                                            </a>
                                                        </p>
                                                    </div>

                                                    <div class="activity--meta fs--12">
                                                        <p>
                                                            <i class="fa mr--8 fa-user-o"></i>
                                                            <?php echo $user['first_name']; ?>&nbsp;
                                                            <?php echo $user['last_name']; ?>
                                                        </p>
                                                    </div>

                                                    <div class="activity--content">
                                                        <div>
                                                            <p>
                                                                <i class="fa mr--8 fa-pencil-square-o"></i>
                                                                <?php echo $row['description']; ?>
                                                            </p>
                                                        </div>
                                                        <a href="edit_friend_group.php?group_id=<?php echo $row['id']; ?>"
                                                           class="btn btn-primary">
                                                            Edit <?php echo $row['group_name']; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Activity Item End -->
                                        </li>
                                    <?php endforeach;
                                } else { ?>
                                    <li style="text-align: center;">
                                        There isn't group you created
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <!-- Activity List End -->
                        <hr>

                        <!-- The group you are member of Start -->
                        <div class="filter--nav pb--20 clearfix">
                            <div class="filter--link float--left">
                                <h2>The groups I am a member of</h2>
                            </div>
                        </div>
                        <!-- The group you are member of -->
                        <hr>
                        <!-- Activity List Start -->
                        <div class="activity--list">
                            <ul class="activity--items nav">

                                <?php if (count($belongs_groups) > 0) {
                                    foreach ($belongs_groups as $row):?>
                                        <li>
                                            <!-- Activity Item Start -->
                                            <div class="activity--item">
                                                <div class="activity--avatar">
                                                    <a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$row['by_who']; ?>" >
                                                        <?php if(isset($row['avatar'])) { ?>
                                                            <img src="<?php echo '../'.substr($row['avatar'],2) ?>" alt="">
                                                        <?php } else { ?>
                                                            <img src="../img/activity-img/avatar-05.jpg" alt="">
                                                        <?php } ?>
                                                    </a>
                                                </div>

                                                <div class="activity--info fs--14">
                                                    <div class="activity--header">
                                                        <p>
                                                            <a href="../group-frd.php?group_id=<?php echo $row['group_id'] ?>">
                                                            <strong>
                                                                <?php echo $row['group_name']; ?>
                                                            </strong>
                                                            </a>
                                                        </p>
                                                    </div>

                                                    <div class="activity--meta fs--12">
                                                        <p>
                                                            <i class="fa mr--8 fa-user-o"></i>
                                                            <?php echo $row['first_name']; ?>&nbsp;
                                                            <?php echo $row['last_name']; ?>
                                                        </p>
                                                    </div>

                                                    <div class="activity--content">
                                                        <div>
                                                            <p>
                                                                <i class="fa mr--8 fa-pencil-square-o"></i>
                                                                <?php echo $row['description']; ?>
                                                            </p>
                                                        </div>
                                                        <a href="../activity-frd.php?exit_group=1&&gp_id=<?php echo $row['group_id']; ?>&&who=<?php echo $logged_id; ?>"
                                                           class="btn btn-primary">
                                                            My Friends
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Activity Item End -->
                                        </li>
                                    <?php endforeach;
                                } else { ?>
                                    <li style="text-align: center;">
                                        There isn't group you are member of
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <!-- Activity List End -->

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Page Wrapper End -->


<?php include BASE_PATH.'/members/includes/footer.php'?>

