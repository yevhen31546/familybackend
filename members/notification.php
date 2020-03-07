<?php

// Generate friend request notification message
function genFriReqNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = SMTP_ENDPOINT."?type=friend&&from=".$param['who']."&&to=".$param['with_who']."&&friend_id=".$param['friend_id']."&&status=approved";
        $disapprove_url = SMTP_ENDPOINT."?type=friend&&from=".$param['who']."&&to=".$param['with_who']."&&friend_id=".$param['friend_id']."&&status=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .=$sender_name.' has sent a friend request. '.$approve.' or '.$disapprove.'<hr>';
    endforeach;

    return $message;
}

// Generate family request notification message
function genFamReqNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = SMTP_ENDPOINT."?type=family&&from=".$param['who']."&&to=".$param['with_who']."&&relation=".$param['relation']."&&family_id=".$param['family_id']."&&status=approved";
        $disapprove_url = SMTP_ENDPOINT."?type=family&&from=".$param['who']."&&to=".$param['with_who']."&&relation=".$param['relation']."&&family_id=".$param['family_id']."&&status=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .=$sender_name.' has sent a family request. '.$approve.' or '.$disapprove.'<hr>';
    endforeach;

    return $message;
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

        $message .='You have a request from '.$who.' to join the '.$group_name.'.'.$approve.' or '.$disapprove.'<hr>';
    endforeach;

    return $message;
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

        $message .='You have a request from '.$who.' to join the '.$group_name.'.'.$approve.' or '.$disapprove.'<hr>';
    endforeach;

    return $message;
}

// Generate family note notification message
function genFamNoteNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = BASE_URL."/members/activity-fam.php?from=".$param['user_id']."&&note_to=".$param['note_to']."&&note_id=".$param['note_id']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/activity-fam.php?from=".$param['user_id']."&&note_to=".$param['note_to']."&&note_id=".$param['note_id']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .= $sender_name.' has posted something on your profile. '.$approve.' / '.$disapprove.'<hr>';
    endforeach;

    return $message;
}

// Generate family group note notification message
function genFamGroupNoteNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $group_name = $param['group_name'];

        $approve_url = BASE_URL."/members/group-fam.php?group_id=".$param['group_id']."&&notification_id=".$param['not_id']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/group-fam.php?group_id=".$param['group_id']."&&notification_id=".$param['not_id']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .=$group_name.' has posted a note '.$approve.' / '.$disapprove.'<hr>';
    endforeach;

    return $message;
}

// Generate friend group note notification message
function genFriNoteNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $sender_name = $param['user_name'];

        $approve_url = BASE_URL."/members/activity-frd.php?from=".$param['user_id']."&&note_to=".$param['note_to']."&&note_id=".$param['note_id']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/activity-frd.php?from=".$param['user_id']."&&note_to=".$param['note_to']."&&note_id=".$param['note_id']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .= $sender_name.' has posted something on your profile. '.$approve.' / '.$disapprove.'<hr>';
    endforeach;

    return $message;
}

// Generate friend group note notification message
function genFriGroupNoteNotMsg($params) {

    $message = "";

    foreach ($params as $param):
        $group_name = $param['group_name'];

        $approve_url = BASE_URL."/members/group-frd.php?group_id=".$param['group_id']."&&notification_id=".$param['not_id']."&&stat=approved";
        $disapprove_url = BASE_URL."/members/group-frd.php?group_id=".$param['group_id']."&&notification_id=".$param['not_id']."&&stat=delete";

        $approve = '<a href="'.$approve_url.'">Approve</a>';
        $disapprove = '<a href="'.$disapprove_url.'">Disapprove</a>';

        $message .= $group_name.' has posted a note '.$approve.' / '.$disapprove.'<hr>';
    endforeach;

    return $message;
}







///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Check notification ///
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





// Checking family member request
function checkFamilyRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT users.*, family.id AS family_id, family.who, family.with_who, family.relation, family.stat
              FROM tbl_users AS users JOIN
              (SELECT * FROM tbl_family WHERE with_who = '.$user_id.' AND stat = 0) AS family
              ON users.id = family.who';
    $family_requests = $db->rawQuery($query);
    if(count($family_requests) > 0) {
        $notification_msg = genFamReqNotMsg($family_requests);
        $_SESSION['family_request_msg'] = $notification_msg;
        return count($family_requests);
    } else {
        $_SESSION['family_request_msg'] = '';
        return 0;
    }
}

// Checking friend request
function checkFriendRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT users.*, friend.id AS friend_id, friend.who, friend.with_who, friend.stat
              FROM tbl_users AS users JOIN
              (SELECT * FROM tbl_friend WHERE with_who = '.$user_id.' AND stat = 0) AS friend
              ON users.id = friend.who';
    $friend_requests = $db->rawQuery($query);
    if(count($friend_requests) > 0) {
        $notification_msg = genFriReqNotMsg($friend_requests);
        $_SESSION['friend_request_msg'] = $notification_msg;
        return count($friend_requests);
    } else {
        $_SESSION['friend_request_msg'] = '';
        return 0;
    }
}

// Check family group invitation exist
function checkFamGroupInvitation($user_id) {
    $db = getDbInstance();
    $query = 'SELECT us.`first_name`, us.`last_name`, gg.`group_name`, gg.`id` AS member_id, gg.`group_id`
            FROM tbl_users us, (
            SELECT g.`by_who`, g.`group_name`, m.`id`, m.`group_id`
            FROM tbl_fam_groups g, (SELECT group_id, id
            FROM tbl_fam_groups_members
            WHERE who='.$user_id.' AND stat=0) m
            WHERE g.`id`=m.group_id) gg
            WHERE gg.by_who = us.`id`';
    $fam_group_requests = $db->rawQuery($query);
    if(count($fam_group_requests) > 0) {
        $notification_msg = genFamGroupNotMsg($fam_group_requests);
        $_SESSION['fam_group_requests_msg'] = $notification_msg;
        return count($fam_group_requests);
    } else {
        $_SESSION['fam_group_requests_msg'] = '';
        return 0;
    }
}

// Check friend group invitation exist
function checkFriGroupInvitation($user_id) {
    $db = getDbInstance();
    $query = 'SELECT us.`first_name`, us.`last_name`, gg.`group_name`, gg.`id` AS member_id, gg.`group_id`
            FROM tbl_users us, (
            SELECT g.`by_who`, g.`group_name`, m.`id`, m.`group_id`
            FROM tbl_fri_groups g, (SELECT group_id, id
            FROM tbl_fri_groups_members
            WHERE who='.$user_id.' AND stat=0) m
            WHERE g.`id`=m.group_id) gg
            WHERE gg.by_who = us.`id`';
    $friend_group_requests = $db->rawQuery($query);
    if(count($friend_group_requests) > 0) {
        $notification_msg = genFriGroupNotMsg($friend_group_requests);
        $_SESSION['fri_group_requests_msg'] = $notification_msg;
        return count($friend_group_requests);
    } else {
        $_SESSION['fri_group_requests_msg'] = '';
        return 0;
    }
}

// Check family note exist
function checkFamNoteRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT users.*, notes.id AS note_id, notes.user_id, notes.note_to, notes.status
              FROM tbl_users AS users JOIN
              (SELECT * FROM tbl_fam_notes WHERE note_to = '.$user_id.' AND status = 0 AND user_id != '.$user_id.') AS notes
              ON users.id = notes.user_id';
    $note_requests = $db->rawQuery($query);

    if(count($note_requests) > 0) {
        $notification_msg = genFamNoteNotMsg($note_requests);
        $_SESSION['fam_note_request_msg'] = $notification_msg;
        return count($note_requests);
    } else {
        $_SESSION['fam_note_request_msg'] = '';
        return 0;
    }
}

// Check friend note exist
function checkFriNoteRequest($user_id) {
    $db = getDbInstance();
    $query = 'SELECT users.*, notes.id AS note_id, notes.user_id, notes.note_to, notes.status
              FROM tbl_users AS users JOIN
              (SELECT * FROM tbl_fri_notes WHERE note_to = '.$user_id.' AND status = 0) AS notes
              ON users.id = notes.user_id';
    $note_requests = $db->rawQuery($query);

    if(count($note_requests) > 0) {
        $notification_msg = genFriNoteNotMsg($note_requests);
        $_SESSION['fri_note_request_msg'] = $notification_msg;
        return count($note_requests);
    } else {
        $_SESSION['fri_note_request_msg'] = '';
        return 0;
    }
}
