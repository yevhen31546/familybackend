<?php

// Get current user
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$row = $db->getOne('tbl_users');


// Create referral code function
function create_referral_code($ref_length = 10) {
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $ref_length);
}

/*
 * Approve/Disapprove friend/family request
 */
if(isset($_GET) && isset($_GET['type'])) {
    if($_GET['type']=='family') {
        $status = $_GET['status'];
        $sender_id = $_GET['from'];
        $receiver_id = $_GET['to'];
        $family_relation = $_GET['relation']; // Family relationship
        $family_id = $_GET['family_id'];

        // Sender info
        $db = getDbInstance();
        $db->where('id', $sender_id);
        $sender = $db->getOne('tbl_users');

        // Receiver info
        $db = getDbInstance();
        $db->where('id', $receiver_id);
        $receiver = $db->getOne('tbl_users');

        if($status == 'approved') {
            $data_to_db = array(
                'stat'=> 1
            );
            $db = getDbInstance();
            $db->where('id', $family_id);
            $stat = $db->update('tbl_family', $data_to_db);

            $to = $sender['user_email']; // sender's email
            $body = generateApprovedFamMessageBody($sender, $receiver, $family_relation);
            $stat = sendEmail($to, $body);
            if ($stat) {
                $bell_count++;
                $_SESSION['success'] = $sender['user_name'].' has added you as family!<hr>';
            }
        }

        if($status == 'delete') {
            $data_to_db['stat'] = -1; // update status

            $db = getDbInstance();
            $db->where('id', $family_id);
            $last_id = $db->update('tbl_family', $data_to_db);  // Update tbl_family's status
            $bell_count++;
            $_SESSION['success'] = $sender['user_name'].' has disapproved!<hr>';
        }
    }
    if($_GET['type']=='friend') {
        $status = $_GET['status'];
        $sender_id = $_GET['from'];
        $receiver_id = $_GET['to'];
        $friend_id = $_GET['friend_id'];

        // Sender info
        $db = getDbInstance();
        $db->where('id', $sender_id);
        $sender = $db->getOne('tbl_users');

        // Receiver info
        $db = getDbInstance();
        $db->where('id', $receiver_id);
        $receiver = $db->getOne('tbl_users');

        if($status == 'approved') {
            $to = $sender['user_email']; // sender's email
            $body = generateApprovedFriMessageBody($sender, $receiver);

            $data_to_db = array(
                'stat' => 1
            );
            $db = getDbInstance();
            $db->where('id', $friend_id);
            $re = $db->update('tbl_friend', $data_to_db);

            $stat = sendEmail($to, $body);
            if ($stat) {
                $bell_count++;
                $_SESSION['success'] = $sender['user_name'].' has added you a friend!<hr>';
            }
        }

        if($status == 'delete') {
            $data_to_db['stat'] = -1; // update status

            $db = getDbInstance();
            $db->where('id', $friend_id);
            $last_id = $db->update('tbl_friend', $data_to_db);  // Update tbl_friend's status

            $bell_count++;
            $_SESSION['success'] = $sender['user_name'].' has disapproved!<hr>';
        }
    }
}

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
    $myfriend = $_POST['myfriend']; // friend name
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


// Invite outside friend
if(isset($_POST) && isset($_POST['friend_name'])) {
    $db = getDbInstance();
    $myfriend = $_POST['friend_name']; // friend name
    $db->where('user_name', $myfriend);
    $friend_user = $db->getOne('tbl_users');
    // check the friend exists in site by username
    if ($friend_user) {
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
    } else {
        // If user doesn't exists on site
        $ref_code = create_referral_code(); // create referral code
        $data_to_db = array(
            'ref_code' => $ref_code,
            'who' => $logged_id
        );
        $to = $_POST['friend_email']; // friend email

        // Save to tbl_friend_ref
        $db = getDbInstance();

        $ref_id = $db->insert('tbl_friend_ref', $data_to_db);

        if ($ref_id) {
            $body = generateInviteFriMessageBody($row, $myfriend, $ref_code);
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


// Invite outside family
if(isset($_POST) && isset($_POST['family_name'])) {
    $db = getDbInstance();
    $relation = $_POST['relation_type'];
    $myfamily = $_POST['family_name']; // family name
    $db->where('user_name', $myfamily);
    $family_user = $db->getOne('tbl_users');
    // check the family exists in site by username
    if ($family_user) {
        // Pending check
        $db->where('who', $logged_id);
        $db->where('with_who', $family_user['id']);
        $db->where('relation', $relation);
        $stat = $db->getValue('tbl_family', 'stat');
        if ($stat === 0) {
            $bell_count++;
            $_SESSION['failure'] = 'Already send, pending status...!<hr>';
        } else {
            $to = $family_user['user_email'];
            $data_to_db = array(
                'who' => $logged_id,
                'with_who' => $family_user['id'],
                'relation' => $relation
            );
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
    } else {
        // If user doesn't exists on site
        $ref_code = create_referral_code(); // create referral code
        $data_to_db = array(
            'ref_code' => $ref_code,
            'who' => $logged_id,
            'relation' => $relation
        );
        $to = $_POST['family_email']; // friend email

        // Save to tbl_family_ref
        $db = getDbInstance();
        $ref_id = $db->insert('tbl_family_ref', $data_to_db);

        if ($ref_id) {
            $body = generateInviteFamMessageBody($row, $myfamily, $ref_code, $relation);
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
$get_family_query = 'SELECT user_name from tbl_users';
$members = $db->rawQuery($get_family_query);
$users = [];
foreach($members as $member):
    array_push($users, $member['user_name']);
endforeach;

// Filter option
$order_val = 1;
if (isset($_POST) && isset($_POST['membersfilter'])) {
    $order_val = $_POST['membersfilter'];
}

// Get member lists
$page = 1;
$page_per_num = 12;
$tbl_name = 'tbl_users';

$db = getDbInstance();
$db->pageLimit = $page_per_num;
if (isset($_GET) && isset($_GET['page_num'])) {
    $page = $_GET['page_num'];
    if ($page < 1) {
        $page = 1;
    }
}
if ($order_val == 1) {
    $db->orderBy('created_date', 'DESC');
    $rows = $db->paginate($tbl_name, $page);    // newly registered first
}
if ($order_val == 2) {
    $db->orderBy('first_name', 'ASC');
    $rows = $db->paginate($tbl_name, $page); // Alphabetical
}

$total = $db->totalCount;
$pages = $db->totalPages;

if ($page >= $pages) {
    $next_page = $pages;
} else {
    $next_page = $page + 1;
}
if ($page > 1) {
    $prev_page = $page - 1;
} else {
    $prev_page = $page;
}
