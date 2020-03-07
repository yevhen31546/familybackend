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

/*
 * Get friend group id
 */
$group_id = 0;
if (isset($_GET) && isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];
}


/*
 * Get friends for auto fill box
 */
$db = getDbInstance();
$get_friend_query = 'SELECT us.id, us.user_name, us.user_email, us.first_name, us.last_name
FROM tbl_users us
WHERE id NOT IN (
SELECT fri_gp_mems.who
FROM tbl_fri_groups_members fri_gp_mems
WHERE fri_gp_mems.group_id = '.$group_id.') AND us.`id` != '.$logged_id;
$friends = $db->rawQuery($get_friend_query);

/*
 * update friend group
 */
if(isset($_POST) && isset($_POST['group_name'])) {

    $data_to_db = array(
        'group_name' => $_POST['group_name'],
        'description' => trim($_POST['description'])
    );
    $db = getDbInstance();
    $db->where('id', $group_id);
    $update_result = $db->update('tbl_fri_groups', $data_to_db); // Group Id

    $friend_lists = $_POST['friend_lists'];
    if ($friend_lists != '') {
        //    Get friend's id/email
        $friend_lists = $_POST['friend_lists'];
        $friend_arr = explode(",", $friend_lists);
        $friend_data = []; // selected family member's data: id, email...

        for ($i = 0; $i < count($friend_arr); $i++) {
            foreach ($friends as $friend):
                if ($friend['user_name'] === $friend_arr[$i]) {
                    $friend_data[$i] = $friend;
                    continue;
                }
            endforeach;
        }

        //    Save to friend group member
        $fri_group_members_id = [];
        for ($i = 0; $i < count($friend_arr); $i++) {
            $data_to_db = array(
                'who' => $friend_data[$i]['id'],
                'group_id' => $group_id
            );
            $db = getDbInstance();
            $fri_group_members_id[$i] = $db->insert('tbl_fri_groups_members', $data_to_db);
        }

        //    Send friend group invitation
        //$user: group creator
        for ($i = 0; $i < count($friend_arr); $i++) {
            $body = genFriGroupMsgBody($user, $_POST['group_name'], $group_id, $fri_group_members_id[$i]);
            $stat = sendEmail($friend_data[$i]['user_email'], $body);
        }
        if ($stat) {
            $bell_count++;
            $_SESSION['success'] = 'Invitation email is sent successfully!<hr>';
        } else {
            $bell_count++;
            $_SESSION['failure'] = 'Sending invitation email is failed!<hr>';
        }
    }
}


/*
 * Remove group member
 */
if(isset($_POST) && isset($_POST['del_member_id'])) {
    $db->where('id', $_POST['del_member_id']);
    $result = $db->delete('tbl_fri_groups_members');
    if ($result) {
        $bell_count++;
        $_SESSION['success'] = 'Removed successfully<hr>';
    } else {
        $bell_count++;
        $_SESSION['failure'] = 'Oops... removing fail!<hr>';
    }
}


/*
 * Get group member lists
 */
$query = 'SELECT us.`id` AS user_id, us.`avatar`, us.`first_name`, us.`last_name`, tmp.member_id
FROM tbl_users us
RIGHT JOIN (SELECT gp_mems.`who`, gp_mems.`id` AS member_id
FROM tbl_fri_groups_members gp_mems
WHERE gp_mems.group_id = '.$group_id.' AND gp_mems.stat = 1) tmp
ON us.`id` = tmp.who';
$rows = $db->rawQuery($query);

/*
 * Get friends for auto fill box
 */
$friends = [];
$db = getDbInstance();
$get_friend_query = 'SELECT us.id, us.user_name, us.user_email, us.first_name, us.last_name
FROM tbl_users us
WHERE id NOT IN (
SELECT fri_gp_mems.who
FROM tbl_fri_groups_members fri_gp_mems
WHERE fri_gp_mems.group_id = '.$group_id.') AND us.`id` != '.$logged_id;
$friends = $db->rawQuery($get_friend_query);

/*
 * Get group info
 */
$group = '';
if ($group_id != 0) {
    $db = getDbInstance();
    $db->where('id', $group_id);
    $group = $db->getOne('tbl_fri_groups');
}

?>


<?php include BASE_PATH.'/members/includes/header.php'?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/plugins/multi-select/style.css">

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
                <!-- Main Content Start -->
                <div class="main--content col-md-8 pb--60">
                    <div class="main--content-inner drop--shadow">

                        <form name="create-family-group-form" action="" method="post" onsubmit="return checkEditFriForm();">
                            <h2>Edit <?php echo $group['group_name']; ?></h2>
                            <div class="box--items-h">
                                <div class="row gutter--15 AdjustRow">
                                    <div class="box--item text-center w-100">
                                        <div class="col-md-12 col-xs-12 left-right-auto">

                                            <div class="box--item text-left">
                                                <div>
                                                    <label>
                                                        <h6>Please provide the name of the friend group :&nbsp;&nbsp;&nbsp;
                                                            <input type="text" class="form-control" name="group_name"
                                                                   value="<?php echo $group['group_name']; ?>"
                                                                   required>&nbsp;&nbsp;&nbsp;</h6>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="box--item text-left textareaw">
                                                <div>
                                                    <label>
                                                        <h6>Please provide the group description: </h6>
                                                    </label>
                                                    <textarea class="w-100" rows="4" cols="100%" name="description">
                                                        <?php echo trim($group['description']); ?>
                                                    </textarea>
                                                </div>
                                            </div>

                                            <div class="box--item text-left">
                                                <label>
                                                    <h6>Select group members (You can add more friends here) :</h6>
                                                </label>
                                                <?php if (count($friends) > 0) { ?>
                                                <multi-input>
                                                    <input list="speakers">
                                                    <datalist id="speakers">
                                                        <?php
                                                        foreach ($friends as $friend):
                                                            ?>
                                                            <option value="<?php echo $friend['user_name']; ?>"></option>
                                                            <?php
                                                        endforeach;
                                                        ?>
                                                    </datalist>
                                                </multi-input>
                                                <?php } else { ?>
                                                    <p>There is no profile to select or you selected all.</p>
                                                <?php } ?>
                                            </div>
                                            <input type="hidden" id="friend_lists" name="friend_lists">

                                            <br/>
                                            <div class="row text-right" style="padding-right: 16px;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <a class="btn btn-primary" href="view_fri_group.php">Back</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Main Content End -->

                <!-- Main Sidebar Start -->
                <div class="main--sidebar col-md-4 pb--60" data-trigger="stickyScroll">
                    <!-- Widget Start -->
                    <div class="widget">
                        <h2 class="h6 fw--700 widget--title">Existing member lists</h2>
                        <hr>
                        <!-- Buddy Finder Widget Start -->
                        <div class="activity--list">
                            <ul class="activity--items nav">
                                <?php if (count($rows) > 0) {
                                    foreach ($rows as $row):?>
                                        <li>
                                            <form action="" method="post">
                                                <!-- Activity Item Start -->
                                                <div class="activity--item">
                                                    <div class="activity--avatar">
                                                        <a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$row['user_id']; ?>" >
                                                            <?php if(isset($row['avatar'])) { ?>
                                                                <img src="<?php echo '../'.substr($row['avatar'],2) ?>" alt="">
                                                            <?php } else { ?>
                                                                <img src="../img/activity-img/avatar-05.jpg" alt="">
                                                            <?php } ?>
                                                        </a>
                                                    </div>

                                                    <div class="activity--info fs--14">
                                                        <div class="activity--header">
                                                            <a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$row['user_id']; ?>" >
                                                                <strong>
                                                                    <?php echo $row['first_name']; ?>&nbsp;<?php echo $row['last_name'];
                                                                    ?>
                                                                </strong>
                                                            </a>
                                                            <button type="submit"
                                                                    id="<?php echo $row['member_id']; ?>_member; ?>"
                                                                    class="btn btn-primary remove_member pull-right"
                                                                    value="Remove">
                                                                <i class="fa fa-trash"></i>&nbsp; Remove
                                                            </button>
                                                        </div>

                                                    </div>
                                                    <input type="hidden" name="del_member_id" value="<?php echo $row['member_id']; ?>" >
                                                </div>
                                                <!-- Activity Item End -->
                                            </form>
                                        </li>
                                    <?php endforeach;
                                } else { ?>
                                    <li style="text-align: center;">
                                        There isn't any user
                                    </li>
                                <?php }
                                ?>
                            </ul>
                        </div>
                        <!-- Buddy Finder Widget End -->
                    </div>
                    <!-- Widget End -->
                </div>

            </div>
        </div>
    </section>
    <!-- Page Wrapper End -->

    <script src="../plugins/multi-select/multi-input.js"></script>
    <script src="../plugins/multi-select/script.js"></script>

<?php include BASE_PATH.'/members/includes/footer.php'?>