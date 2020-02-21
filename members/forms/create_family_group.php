<?php
session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '../includes/auth_validate.php';
require_once '../../vendor/autoload.php';
require_once '../smtp_endpoint.php';

$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->get('tbl_users');
$db->where('id', $logged_id);
$user = $db->getOne('tbl_users');

/**
 * Get family members for auto fill box
 */
$db = getDbInstance();
$get_family_query = 'SELECT us.id, us.user_name, us.user_email, us.first_name, us.last_name, fa.relation 
                     FROM tbl_users us JOIN 
                     (SELECT with_who, who, relation  
                         FROM tbl_family WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa
                     ON us.id=fa.with_who OR us.id=fa.who WHERE us.id!='.$logged_id;
$family_members = $db->rawQuery($get_family_query);

/**
 * Save family group
 */
// generate invitation message body for family group
function genFamGroupMsgBody($from, $group_name, $group_id, $fam_group_members_id) {
    $who = $from['first_name']." ".$from['last_name'];
    $approve_url = BASE_URL."/members/activity-fam.php?group_id=".$group_id."&&member_id=".$fam_group_members_id."&&stat=approved";
    $delete_url = BASE_URL."/members/activity-fam.php?group_id=".$group_id."&&member_id=".$fam_group_members_id."&&stat=delete";

    $message = "";

    $message .="<html><head><title></title></head><body><p>Invitation is arrived from ".$group_name." that created by ".$who."</p><p><span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $message;
}
if(isset($_POST) && isset($_POST['group_name'])) {

//    Create the family group
    $data_to_db = array(
        'group_name' => $_POST['group_name'],
        'description' => $_POST['description'],
        'by_who' => $logged_id
    );
    $db = getDbInstance();
    $fam_group_id = $db->insert('tbl_fam_groups', $data_to_db); // Group Id

//    Get family member's id/email
    $family_lists = $_POST['family_lists'];
    $family_arr = explode (",", $family_lists);
    $family_members_data = []; // selected family member's data: id, email...
    for ($i=0; $i<count($family_arr); $i++) {
        foreach ($family_members as $family_member):
            if ($family_member['user_name'] === $family_arr[$i]) {
                $family_members_data[$i] = $family_member;
                continue;
            }
        endforeach;
    }

    //    Save to family group member
    $fam_group_members_id = [];
    for ($i=0; $i<count($family_arr); $i++) {
        $data_to_db = array(
            'who' => $family_members_data[$i]['id'],
            'group_id' => $fam_group_id
        );
        $db = getDbInstance();
        $fam_group_members_id[$i] = $db->insert('tbl_fam_groups_members', $data_to_db);
    }

//    print_r($family_emails); exit;
//    Send family group member invitation
    // $user: group creator
    for ($i = 0; $i < count($family_arr); $i++) {
        $body = genFamGroupMsgBody($user, $_POST['group_name'], $fam_group_id, $fam_group_members_id[$i]);
        $stat = sendEmail($family_members_data[$i]['user_email'], $body);
    }
    if ($stat) {
        $_SESSION['success'] = 'Invitation email is sent successfully!';
        header('Location: '. BASE_URL .'/members/activity-fam.php');
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
                        <form name="create-family-group-form" action="" method="post" onsubmit="return checkForm();">
                            <h2>Create a Family Group</h2>
                            <div class="box--items-h">
                                <div class="row gutter--15 AdjustRow">
                                    <div class="box--item text-center w-100">
                                        <div class="col-md-12 col-xs-12 left-right-auto">

                                            <div class="box--item text-left">
                                                <div>
                                                    <label>
                                                        <h6>Please provide the name of the family group :&nbsp;&nbsp;&nbsp;
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
                                                        foreach ($family_members as $family):
                                                        ?>
                                                            <option value="<?php echo $family['user_name']; ?>"></option>
                                                        <?php
                                                        endforeach;
                                                        ?>
                                                    </datalist>
                                                </multi-input>
                                            </div>
                                            <input type="hidden" id="family_lists" name="family_lists">

                                            <br/>
                                            <div class="row text-right" style="padding-right: 16px;">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <a class="btn btn-primary" href="../../members/activity-fam.php">Cancel</a>
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

<?php include BASE_PATH.'../includes/footer.php'?>