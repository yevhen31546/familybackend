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

/**
 * Get friend for auto fill box
 */
$db = getDbInstance();
$get_friend_query = 'SELECT us.id, us.user_name, us.user_email, us.first_name, us.last_name
                     FROM tbl_users us JOIN
                     (SELECT with_who, who  
                         FROM tbl_friend WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa
                     ON us.id=fa.with_who OR us.id=fa.who WHERE us.id!='.$logged_id;
$friends = $db->rawQuery($get_friend_query);

/**
 * Save friend group
 */
// generate invitation message body for family group
function genFriGroupMsgBody($from, $group_name, $group_id, $fri_group_members_id) {
    $who = $from['first_name']." ".$from['last_name'];
    $approve_url = BASE_URL."/members/activity-frd.php?group_id=".$group_id."&&member_id=".$fri_group_members_id."&&stat=approved";
    $delete_url = BASE_URL."/members/activity-frd.php?group_id=".$group_id."&&member_id=".$fri_group_members_id."&&stat=delete";

    $message = "";

    $message .="<html><head><title></title></head><body><p>Invitation is arrived from ".$group_name." that created by ".$who."</p><p><span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $message;
}
if(isset($_POST) && isset($_POST['group_name'])) {
    //    Create the friend group
    $data_to_db = array(
        'group_name' => $_POST['group_name'],
        'description' => $_POST['description'],
        'by_who' => $logged_id
    );
    $db = getDbInstance();
    $fri_group_id = $db->insert('tbl_fri_groups', $data_to_db); // Group Id

    //    Get family member's id/email
    $friend_lists = $_POST['friend_lists'];
    $friend_arr = explode (",", $friend_lists);
    $friend_data = []; // selected family member's data: id, email...
    for ($i=0; $i<count($friend_arr); $i++) {
        foreach ($friends as $friend):
            if ($friend['user_name'] === $friend_arr[$i]) {
                $friend_data[$i] = $friend;
                continue;
            }
        endforeach;
    }

    //    Save to friend group member
    $fri_group_members_id = [];
    for ($i=0; $i<count($friend_arr); $i++) {
        $data_to_db = array(
            'who' => $friend_data[$i]['id'],
            'group_id' => $fri_group_id
        );
        $db = getDbInstance();
        $fri_group_members_id[$i] = $db->insert('tbl_fri_groups_members', $data_to_db);
    }

    //    Send friend group invitation
    // $user: group creator
    for ($i = 0; $i < count($friend_arr); $i++) {
        $body = genFriGroupMsgBody($user, $_POST['group_name'], $fri_group_id, $fri_group_members_id[$i]);
        $stat = sendEmail($friend_data[$i]['user_email'], $body);
    }
    if ($stat) {
        $_SESSION['success'] = 'Invitation email is sent successfully!';
        header('Location: '. BASE_URL .'/members/activity-frd.php');
        $_POST = array();
    } else {
        $_SESSION['failure'] = 'Sending invitation email is failed!';
        $_POST = array();
    }
}

?>


<?php include BASE_PATH.'/members/includes/header.php'?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/plugins/multi-select/style.css">

    <!-- Page Header Start -->
    <div class="page--header pt--60 pb--60 text-center" data-bg-img="../img/fambanner.png"
         data-overlay="0.35">
        <div class="container">
            <div class="title">
                <h2 class="h1 text-white">My Family</h2>
            </div>

            <ul class="breadcrumb text-gray ff--primary">
                <li><a href="../../members/home.php" class="btn-link">Home</a></li>
                <li class="active"><span class="text-primary">My Family</span></li>
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

                        <?php include BASE_PATH . '/includes/flash_messages.php'; ?>
                        <form name="create-family-group-form" action="" method="post" onsubmit="return checkFriForm();">
                            <h2>Create a Friend Group</h2>
                            <div class="box--items-h">
                                <div class="row gutter--15 AdjustRow">
                                    <div class="box--item text-center w-100">
                                        <div class="col-md-12 col-xs-12 left-right-auto">

                                            <div class="box--item text-left">
                                                <div>
                                                    <label>
                                                        <h6>Please provide the name of the friend group :&nbsp;&nbsp;&nbsp;
                                                            <input type="text" class="form-control" name="group_name" required>&nbsp;&nbsp;&nbsp;</h6>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="box--item text-left textareaw">
                                                <div>
                                                    <label>
                                                        <h6>Please provide the Group description: </h6>
                                                    </label>
                                                    <textarea class="w-100" rows="4" cols="100%" name="description"></textarea>
                                                </div>
                                            </div>

                                            <div class="box--item text-left">
                                                <label>
                                                    <h6>Select group members  :</h6>
                                                </label>
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
                                            </div>
                                            <input type="hidden" id="friend_lists" name="friend_lists">

                                            <br/>
                                            <div class="row text-right" style="padding-right: 16px;">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <a class="btn btn-primary" href="../../members/activity-frd.php">Cancel</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Wrapper End -->

    <script src="../plugins/multi-select/multi-input.js"></script>
    <script src="../plugins/multi-select/script.js"></script>

<?php include BASE_PATH.'/members/includes/footer.php'?>