<?php

$logged_id = $_SESSION['user_id'];

/**
 * Check family group exists or not
 */
$db = getDbInstance();
$db->where('by_who', $logged_id);
$checkGroup = $db->get('tbl_fam_groups');

/**
 * Approve or delete group note
 */

if(isset($_GET) && isset($_GET['fam_group_note_id'])){
    $note_id = $_GET['fam_group_note_id']; // group note id
    $from = $_GET['from']; // Group creator
    $note_to = $_GET['note_to']; // Group member

//    Get sender data
    $db = getDbInstance();
    $db->where('id', $from);
    $sender = $db->getOne('tbl_users');

//    Get receiver data
    $db = getDbInstance();
    $db->where('id', $note_to);
    $receiver = $db->getOne('tbl_users');

    $to = $sender['user_email']; // sender's email
    if($_GET['stat'] == 'approved') {
        $data_to_db['status'] = 1; // update status

        $db = getDbInstance();
        $db->where('id', $note_id);
        $last_id = $db->update('tbl_fam_group_notes', $data_to_db);  // Update tbl_notes's status
        if ($last_id) {
//            echo "successfully added";

//            $body = generateApprovedNoteMessageBody($sender, $receiver);
//            $stat = sendNoteEmail($to, $body);
//            if ($stat) {
//                $_SESSION['success'] = $sender['user_name'].' has posted something on your profile successfully';
//            }
            $_SESSION['success'] = $sender['user_name'].' has posted something on your profile successfully';

        } else {
            $_SESSION['failure'] = 'error: approve to notes';
//            echo "error: save to notes";
        }
    } else if($_GET['stat'] == 'delete') {
//        $body = generateDeleteNoteMessageBody($sender, $receiver);
//        $stat = sendNoteEmail($to, $body);
        $db = getDbInstance();
        $db->where('id', $note_id);
        $db->delete('tbl_fam_group_notes');  // Delete posted note
    }
}

/**
 * Approve or delete group member request
 */

if(isset($_GET) && isset($_GET['group_id'])) {
    $stat = $_GET['stat'];
    $group_id = $_GET['group_id'];
    $member_id = $_GET['member_id'];

    if($stat == 'approved') {
        $data_to_db = array(
            'stat'=> 1
        );
        $db = getDbInstance();
        $db->where('id', $member_id);
        $db->update('tbl_fam_groups_members', $data_to_db);
    }

    else if($stat == 'delete') {
        $db = getDbInstance();
        $db->where('id', $member_id);
        $db->delete('tbl_fam_groups_members');
    }
}

/**
 * Save and send email notification for group note
 */
if(isset($_POST) && isset($_POST['cat_id']) && $_POST['cat_id'] && $_POST['mode'] == 'add') {
    $db = getDbInstance();
    $log_user_id = $_SESSION['user_id'];
    $media_type = $_POST['note_media'];
    $group_id = $_POST['note_to'];
    $note_date = $_POST['note_date'];
    $cat_id = $_POST['cat_id'];
    $note_value = '';

    //    get family group member's id
    $db = getDbInstance();
    $db->where('group_id', $group_id);
    $db->where('stat', 1);
    $to = $db->get('tbl_fam_groups_members', null, 'who');

    //    If media type is photo, then get img_url after upload that photo
    if ($media_type == 'photo') {
        $target_dir = "./uploads/" . $group_id . "/group_notes/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  //create directory if not exist
        }
        $target_file = $target_dir . uniqid('notes_', true) . basename($_FILES["note_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["note_photo"]["tmp_name"]);
        if ($check !== false) {
            //                echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //                echo "File is not an image.";
            $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            //            echo "Sorry, file already exists.";
            $_SESSION['failure'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["note_photo"]["size"] > 2000000) {
            //            echo "Sorry, your file is too large.";
            $_SESSION['failure'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        //         Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            //            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an failure
        if ($uploadOk == 0) {
            //            echo "Sorry, your file was not uploaded.";
            //            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["note_photo"]["tmp_name"], $target_file)) {
                //                echo "The file ". basename( $_FILES["note_photo"]["name"]). " has been uploaded.";
                $note_value = $target_file;
            } else {
                //                echo "Sorry, there was an failure uploading your file.";
            }
        }
    } else if ($media_type == 'text') {
        $note_value = $_POST['note_value'];
    } else if ($media_type == 'video') {
        $note_value = $_POST['note_video'];
    }

    if ($note_value != '') {
        // data to save to db
        $note_id = [];
        for ($i = 0; $i < count($to); $i++) {

            $data_to_db = [];
            $data_to_db['note_value'] = $note_value; // text, video link, photo url
            $data_to_db['note_media'] = $media_type; // video, text, photo
            $data_to_db['cat_id'] = $cat_id;   // category id => 1: My Story, 2: ...
            $data_to_db['note_date'] = $note_date; // note post date
            $data_to_db['note_to'] = $to[$i]['who']; // receiver profile id
            $data_to_db['user_id'] = $log_user_id; // sender id
            $data_to_db['group_id'] = $group_id; // sender id

            $db = getDbInstance();
            $note_id[$i] = $db->insert('tbl_fam_group_notes', $data_to_db);
        }

        if ($note_id) {
            $_SESSION['success'] = 'Note added successfully. ';
        } else {
            $_SESSION['failure'] = 'Oops, failure... ';
        }

//    // data to email
//    $email_param = array(
//        'who' => $log_user_id,
//        'to_who' => $to,
//        'note_id' => ''
//    );

        //    Send email to user
//        $email_param['note_id'] = $note_id;
//        $result = sendAddNoteEmail($email_param);
//        if($result) {
//            $_SESSION['success'] = "Note posted!";
//            $_POST = array();
//        } else {
//            $_SESSION['success'] = "Note isn't posted :(";
//        }

    } else {
        $_SESSION['failure'] = "Sorry, error occur in photo uploading!";
    }
}


// Function to fetch saved note lists
function get_fam_group_note_lists() {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $query = 'SELECT *
                FROM
                (SELECT *
                    FROM tbl_fam_group_notes notes
                    LEFT JOIN (
                    SELECT
                    tbl_group_note_cat.id AS categoryId,
                    tbl_group_note_cat.cat_name AS cat_name
                    FROM tbl_group_note_cat
                    ) categories ON notes.cat_id = categories.categoryId
                    LEFT JOIN (
                    SELECT
                    tbl_users.id AS userid,
                    tbl_users.first_name AS first_name,
                    tbl_users.last_name AS last_name,
                    tbl_users.`avatar` AS avatar
                    FROM tbl_users
                    ) users ON notes.user_id = users.userid) tmp
                    WHERE tmp.user_id = '.$user_id.' OR (tmp.note_to = '.$user_id.' AND tmp.status = 1)';
    $rows = $db->rawQuery($query);
    return $rows;
}
