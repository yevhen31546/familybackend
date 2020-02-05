<?php


// Checking family request
function checkFamilyRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_family WHERE with_who = '.$user_id.' AND stat = 0';
    $family_requests = $db->rawQuery($query);
    if(count($family_requests) > 0) {
        $_SESSION['family_request'] = 1;
    } else {
        $_SESSION['family_request'] = 0;
    }
}


// Checking friend request
function checkFriendRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_friend WHERE with_who = '.$user_id.' AND stat = 0';
    $friend_requests = $db->rawQuery($query);
    if(count($friend_requests) > 0) {
        $_SESSION['friend_request'] = 1;
    } else {
        $_SESSION['friend_request'] = 0;
    }
}

// Checking posted note request
function checkNoteRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_notes WHERE note_to = '.$user_id.' AND status = 0';
    $friend_requests = $db->rawQuery($query);
    if(count($friend_requests) > 0) {
        $_SESSION['note_request'] = 1;
    } else {
        $_SESSION['note_request'] = 0;
    }
}


// Generate friend request notification message
function genFriReqNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = SMTP_ENDPOINT."?type=friend&&from=".$param['who']."&&to=".$param['with_who']."&&friend_id=".$param['friend_id']."&&status=approved";
        $disapprove_url = SMTP_ENDPOINT."?type=friend&&from=".$param['who']."&&to=".$param['with_who']."&&friend_id=".$param['friend_id']."&&status=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            '.$sender_name.' has sent a friend request. '.$approve.' or '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}


// Generate friend request notification message
function genFamReqNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = SMTP_ENDPOINT."?type=family&&from=".$param['who']."&&to=".$param['with_who']."&&relation=".$param['relation']."&&family_id=".$param['family_id']."&&status=approved";
        $disapprove_url = SMTP_ENDPOINT."?type=family&&from=".$param['who']."&&to=".$param['with_who']."&&relation=".$param['relation']."&&family_id=".$param['family_id']."&&status=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            '.$sender_name.' has sent a family request. '.$approve.' or '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}


// Generate note post notification message
function genNotePostNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = BASE_URL."/members/activity-me.php?from=".$param['user_id']."&&note_to=".$param['note_to']."&&note_id=".$param['note_id']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/activity-me.php?from=".$param['user_id']."&&note_to=".$param['note_to']."&&note_id=".$param['note_id']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            '.$sender_name.' has posted something on your profile. '.$approve.' / '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}
