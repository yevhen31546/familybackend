<?php

// Get current user
$logged_id = $_SESSION['user_id'];

// Invite family member
if(isset($_POST) && isset($_POST['family_member'])) {
    $myfamily = $_POST['myfamily']; // family user id
    $db = getDbInstance();
    $db->where('user_name', $myfamily);
    $family_user = $db->getOne('tbl_users');

    $relation = $_POST['family_member'];
    $to = $family_user['user_email'];

    $data_to_db = array(
        'who' => $logged_id,
        'with_who' => $family_user['id'],
        'relation' => $relation
    );

    $db->where('who', $logged_id);
    $db->where('with_who', $family_user['id']);
    $db->where('relation', $relation);
    $stat = $db->getValue('tbl_family', 'stat');
    if ($stat === 0) {
        $bell_count++;
        $_SESSION['failure'] = 'Already send, pending status...!<hr>';
    } else {
        $family_id = $db->insert('tbl_family', $data_to_db);

        if ($family_id) {
            $body = generateFamMessageBody($row, $family_user, $relation, $family_id); // $row: from, $family_user: to,
            $stat = sendEmail($to, $body);
            if ($stat) {
                $bell_count++;
                $_SESSION['success'] = 'Invitation email is sent successfully!<hr>';
            } else {
                $bell_count++;
                $_SESSION['failure'] = 'Sending invitation email is failed!<hr>';
            }
        } else {
            $bell_count++;
            $_SESSION['failure'] = 'Sending invitation email is failed!<hr>';
        }
    }
}

// Invite friend
if(isset($_POST) && isset($_POST['myfriend'])) {
    $myfriend = $_POST['myfriend']; // friend id
    $db = getDbInstance();
    $db->where('user_name', $myfriend);
    $friend_user = $db->getOne('tbl_users');
    $to = $friend_user['user_email'];

    $data_to_db = array(
        'who' => $logged_id,
        'with_who' => $friend_user['id']
    );
    $db->where('who', $logged_id);
    $db->where('with_who', $friend_user['id']);
    $stat = $db->getValue('tbl_friend', 'stat');
    if ($stat === 0) {
        $bell_count++;
        $_SESSION['failure'] = 'Already send, pending status...!';
    } else {
        $friend_id = $db->insert('tbl_friend', $data_to_db);

        if ($friend_id) {
            $body = generateFriMessageBody($row, $friend_user, $friend_id);
            $stat = sendEmail($to, $body);
            if ($stat) {
                $bell_count++;
                $_SESSION['success'] = 'Invitation email is sent successfully!<hr>';
                $_POST = array();
            } else {
                $bell_count++;
                $_SESSION['failure'] = 'Sending invitation email is failed!<hr>';
                $_POST = array();
            }
        } else {
            $bell_count++;
            $_SESSION['failure'] = 'Sending invitation email is failed!<hr>';
            $_POST = array();
        }
    }
}

// Get all users for auto fill box
$db = getDbInstance();
//$get_family_query = 'SELECT DISTINCT us.user_name,us.id FROM tbl_users us JOIN (SELECT DISTINCT with_who, who  FROM tbl_family WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who WHERE us.id != '.$logged_id;
$get_family_query = 'SELECT user_name from tbl_users';
$members = $db->rawQuery($get_family_query);
$users = [];
foreach($members as $member):
    array_push($users, $member['user_name']);
endforeach;
