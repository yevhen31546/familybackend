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

// Check family group invitation exist
function checkFamGroupInvitation($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_fam_groups_members WHERE who = '.$user_id.' AND stat = 0';
    $family_group_requests = $db->rawQuery($query);
    if(count($family_group_requests) > 0) {
        $_SESSION['family_group_request'] = 1;
    } else {
        $_SESSION['family_group_request'] = 0;
    }
}

// Generate family group invitation notification message
function genFamGroupNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $who = $param['first_name']." ".$param['last_name'];
        $group_name = $param['group_name'];
        $group_id = $param['group_id'];
        $member_id = $param['member_id'];

        $approve_url = BASE_URL."/members/activity-fam.php?group_id=".$group_id."&&member_id=".$member_id."&&stat=approved";
        $delete_url = BASE_URL."/members/activity-fam.php?group_id=".$group_id."&&member_id=".$member_id."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$delete_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            Invitation is arrived from '.$group_name.' that created by '.$who.'.     
                             '.$approve.' or '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}

// Check family group invitation exist
function checkFriGroupInvitation($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_fri_groups_members WHERE who = '.$user_id.' AND stat = 0';
    $friend_group_requests = $db->rawQuery($query);
    if(count($friend_group_requests) > 0) {
        $_SESSION['friend_group_request'] = 1;
    } else {
        $_SESSION['friend_group_request'] = 0;
    }
}

// Generate family group invitation notification message
function genFriGroupNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $who = $param['first_name']." ".$param['last_name'];
        $group_name = $param['group_name'];
        $group_id = $param['group_id'];
        $member_id = $param['member_id'];

        $approve_url = BASE_URL."/members/activity-frd.php?group_id=".$group_id."&&member_id=".$member_id."&&stat=approved";
        $delete_url = BASE_URL."/members/activity-frd.php?group_id=".$group_id."&&member_id=".$member_id."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$delete_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            Invitation is arrived from '.$group_name.' that created by '.$who.'.     
                             '.$approve.' or '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}

// Check family group note exist
function checkFamNoteRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_fam_group_notes gp, tbl_fam_groups_members gm
              WHERE (gp.note_to = '.$user_id.' AND gp.status = 0) AND (gm.who='.$user_id.' AND stat = 1)';
    $family_group_requests = $db->rawQuery($query);
    if(count($family_group_requests) > 0) {
        $_SESSION['family_note_request'] = 1;
    } else {
        $_SESSION['family_note_request'] = 0;
    }
}

// Generate family group note notification message
function genFamNoteNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $group_name = $param['group_name'];

        $approve_url = BASE_URL."/members/activity-fam.php?from=".$param['user_id']."&&fam_group_note_id=".$param['id']."&&note_to=".$param['note_to']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/activity-fam.php?from=".$param['user_id']."&&fam_group_note_id=".$param['id']."&&note_to=".$param['note_to']."&&stat=delete";

//        $approve_url = BASE_URL."/members/activity-fam.php?fam_group_note_id=".$param['id']."&&stat=approved";
//        $disapprove_url = BASE_URL."/members/activity-fam.php?fam_group_note_id=".$param['id']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            '.$group_name.' has posted a note '.$approve.' / '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}

// Check friend group note exist
function checkFriNoteRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT * FROM tbl_fri_group_notes gp, tbl_fri_groups_members gm
              WHERE (gp.note_to = '.$user_id.' AND gp.status = 0) AND (gm.who='.$user_id.' AND stat = 1)';
    $friend_group_requests = $db->rawQuery($query);
    if(count($friend_group_requests) > 0) {
        $_SESSION['friend_note_request'] = 1;
    } else {
        $_SESSION['friend_note_request'] = 0;
    }
}

// Generate friend group note notification message
function genFriNoteNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $group_name = $param['group_name'];

        $approve_url = BASE_URL."/members/activity-frd.php?from=".$param['user_id']."&&fri_group_note_id=".$param['id']."&&note_to=".$param['note_to']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/activity-frd.php?from=".$param['user_id']."&&fri_group_note_id=".$param['id']."&&note_to=".$param['note_to']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .='<div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            '.$group_name.' has posted a note '.$approve.' / '.$disapprove.'
                      </div>';
    endforeach;

    return $message;
}

