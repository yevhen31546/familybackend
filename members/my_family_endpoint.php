<?php

$logged_id = $_SESSION['user_id'];

/*
 * Init pagination variables
 */
$page = 1;
$pageLimit = 10;
$next_page = 1;
$prev_page = 1;
$totalPages = 1;

// Handle pagination
if (isset($_GET) && isset($_GET['page_num'])) {
    $page = $_GET['page_num'];
    if ($page < 1) {
        $page = 1;
    }
}

/*
 * Get category lists
 */
$db = getDbInstance();
$category_lists = $db->get('tbl_group_note_cat');

/*
 * Approve or delete note
 */

if(isset($_GET) && isset($_GET['note_id'])){
    if (empty($_POST) && (empty($_POST['update_category']) || empty($_POST['view_category']))) {
        $note_id = $_GET['note_id']; // note id
        $from = $_GET['from'];
        $note_to = $_GET['note_to'];

        //    Get sender data
        $db = getDbInstance();
        $db->where('id', $from);
        $sender = $db->getOne('tbl_users');

        //    Get receiver data
        $db = getDbInstance();
        $db->where('id', $note_to);
        $receiver = $db->getOne('tbl_users');

        $to = $sender['user_email']; // sender's email
        if ($_GET['stat'] == 'approved') {
            $data_to_db['status'] = 1; // update status

            $db = getDbInstance();
            $db->where('id', $note_id);
            $status = $db->getValue('tbl_fam_notes', 'status');
            if ($status) {
                header('Location: ' . BASE_URL . '/members/activity-fam.php');
            } else {
                $db->where('id', $note_id);
                $last_id = $db->update('tbl_fam_notes', $data_to_db);  // Update tbl_notes's status
                if ($last_id) {
                    //            echo "successfully added";
                    //
                    //            $body = generateApprovedNoteMessageBody($sender, $receiver);
                    //            $stat = sendNoteEmail($to, $body);
                    //            if ($stat) {
                    //                $_SESSION['success'] = $sender['user_name'].' has posted something on your profile successfully';
                    //            }
                    $_SESSION['success'] = $sender['user_name'] . ' has posted something on your profile successfully';

                } else {
                    $_SESSION['failure'] = 'error: approve to notes';
                    //            echo "error: save to notes";
                }
            }
        } else if ($_GET['stat'] == 'delete') {
            //        $body = generateDeleteNoteMessageBody($sender, $receiver);
            //        $stat = sendNoteEmail($to, $body);
            $data_to_db['status'] = -1; // update status

            $db = getDbInstance();
            $db->where('id', $note_id);
            $status = $db->getValue('tbl_fam_notes', 'status');
            if ($status) {
                header('Location: ' . BASE_URL . '/members/activity-fam.php');
            } else {
                $db->where('id', $note_id);
                $last_id = $db->update('tbl_fam_notes', $data_to_db);  // Update tbl_notes's status
                if ($last_id) {
                    $_SESSION['success'] = 'Disapproved note!';
                } else {
                    $_SESSION['failure'] = 'Disapproving note is failed!';
                }
            }
        }
    }
}

/*
 * Approve or delete group member request
 */

if(isset($_GET) && isset($_GET['group_id'])) {
    if (empty($_POST) && (empty($_POST['update_category']) || empty($_POST['view_category']))) {
        $stat = $_GET['stat'];
        $group_id = $_GET['group_id'];
        $member_id = $_GET['member_id'];

        if ($stat == 'approved') {
            $data_to_db = array(
                'stat' => 1
            );
            $db = getDbInstance();
            $db->where('id', $member_id);
            $status = $db->getValue('tbl_fam_groups_members', 'stat');
            if ($status) {
                header('Location: ' . BASE_URL . '/members/activity-fam.php');
            } else {
                $db->where('id', $member_id);
                $result = $db->update('tbl_fam_groups_members', $data_to_db);
                if ($result) {
                    $_SESSION['success'] = 'Approved as group member!';
                } else {
                    $_SESSION['failure'] = 'Approving as group member is failed!';
                }
            }

        } else if ($stat == 'delete') {
            $data_to_db = array(
                'stat' => -1
            );
            $db = getDbInstance();
            $db->where('id', $member_id);
            $status = $db->getValue('tbl_fam_groups_members', 'stat');
            if ($status) {
                header('Location: ' . BASE_URL . '/members/activity-fam.php');
            } else {
                $db->where('id', $member_id);
                $last_id = $db->update('tbl_fam_groups_members', $data_to_db);
                if ($last_id) {
                    $_SESSION['success'] = 'Disapproved as group member!';
                } else {
                    $_SESSION['failure'] = 'Disapproving as group member is failed!';
                }
            }
        }
    }
}

/*
 * Exit from group
 */

if(isset($_GET) && isset($_GET['exit_group'])) {
    if (empty($_POST) && (empty($_POST['update_category']) || empty($_POST['view_category']))) {
        $stat = $_GET['exit_group'];
        $group_id = $_GET['gp_id']; // Group Id
        $member_id = $_GET['who']; // who

        $db = getDbInstance();
        $db->where('group_id', $group_id);
        $db->where('who', $member_id);
        $checkExistInGroup = $db->getOne('tbl_fam_groups_members');

        if (count($checkExistInGroup) > 0) {
            $db->where('group_id', $group_id);
            $db->where('who', $member_id);
            $last_id = $db->delete('tbl_fam_groups_members');
            if ($last_id) {
                $_SESSION['success'] = 'Exit from group successfully!';
            } else {
                $_SESSION['failure'] = 'Exit group failed!';
            }
        } else {
            header('Location: ' . BASE_URL . '/members/activity-fam.php');
        }
    }
}

/*
 * Get approved family lists
 */
    $query = 'SELECT DISTINCT us.user_name,us.id
                FROM tbl_users us JOIN
                (SELECT DISTINCT with_who, who 
                FROM tbl_family
                WHERE (who='.$logged_id.' OR with_who='.$logged_id.') 
                AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who';
    $families = $db->rawQuery($query);
    $familyLists = [];
    foreach ($families as $family):
        array_push($familyLists, $family['user_name']);
    endforeach;

/*
 * Check family group exists or not
 */
    $query = 'SELECT tbl_fam_groups.`id`, tbl_fam_groups.`group_name`
            FROM
            (SELECT DISTINCT fam_gp_mems.group_id
            FROM tbl_fam_groups_members fam_gp_mems
            WHERE fam_gp_mems.`who` = '.$logged_id.' AND stat = 1) tmp, tbl_fam_groups
            WHERE tmp.group_id = tbl_fam_groups.`id`
            ORDER BY tmp.group_id';
    $belongs_group_lists = $db->rawQuery($query);

    $query = 'SELECT tbl_fam_groups.`id`, tbl_fam_groups.`group_name`
            FROM
            (
            SELECT DISTINCT gp.`id` AS group_id
            FROM tbl_fam_groups gp
            WHERE gp.`by_who` = '.$logged_id.'
            ) tmp, tbl_fam_groups
            WHERE tmp.group_id = tbl_fam_groups.`id`
            ORDER BY tmp.group_id';
    $create_group_lists = $db->rawQuery($query);


// Function to fetch saved note lists
function get_fam_note_lists($cat, $note_date, $page, $pageLimit) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $offset = $pageLimit * ($page - 1);
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, us.`last_name`, tmp.`note_date`, tmp.note_media,
                      tmp.`note_value`, tmp.note_comment, tmp.note_id, tmp.cat_id
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, notes.`user_id`, 
                      notes.`id` AS note_id, notes.`note_media`, notes.note_comment
            FROM tbl_fam_notes notes
            WHERE notes.`user_id` = '.$user_id.'
            UNION
            SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, 
                    notes.`user_id`, notes.`id` AS note_id, notes.`note_media`, notes.note_comment
            FROM tbl_fam_notes notes
            WHERE notes.`user_id` IN (
                        SELECT DISTINCT fff.fam_id
                        FROM (
                            SELECT fam.with_who AS fam_id
                            FROM tbl_family fam
                            WHERE fam.who = '.$user_id.' AND fam.stat = 1
                            UNION
                            SELECT fam.who AS fam_id
                            FROM tbl_family fam
                            WHERE fam.with_who = '.$user_id.' AND fam.stat = 1
                        ) fff
                    )
            ) tmp, tbl_users us
            WHERE us.`id`= tmp.user_id';

    if ($cat != '' && $note_date != '') {
        $query .=' AND tmp.cat_id ='.$cat.' AND tmp.note_date = "'.$note_date.'"';
    }

    $totalCount = count($db->rawQuery($query));
    $query .=' ORDER BY tmp.note_date DESC LIMIT '.$offset.', '.$pageLimit;
    $rows = $db->rawQuery($query);

    $result = array();
    $result['totalPages'] = ceil($totalCount / $pageLimit);
    $result['rows'] = $rows;
    return $result;
}

function get_update_note_lists($cat, $note_date, $page, $pageLimit) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $offset = $pageLimit * ($page - 1);
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`,
             us.`last_name`, tmp.`note_date`, tmp.note_media, tmp.`note_value`, tmp.note_comment, tmp.note_id, tmp.cat_id
            FROM (SELECT notes.`note_date`, notes.`cat_id`, 
            notes.`note_value`, notes.`user_id`, notes.`id` AS note_id, notes.`note_media`, notes.note_comment
            FROM tbl_fam_notes notes
            WHERE notes.`user_id` = '.$user_id.'
            ) tmp, tbl_users us
            WHERE us.`id`= tmp.user_id';

    if ($cat != '' && $note_date != '') {
        $query .=' AND tmp.cat_id ='.$cat.' AND tmp.note_date = "'.$note_date.'"';
    }

    $totalCount = count($db->rawQuery($query));
    $query .=' ORDER BY tmp.note_date DESC LIMIT '.$offset.', '.$pageLimit;
    $rows = $db->rawQuery($query);

    $result = array();
    $result['totalPages'] = ceil($totalCount / $pageLimit);
    $result['rows'] = $rows;
    return $result;
}

// Add && View && Update Notes
if(isset($_POST) && $_POST) {
    if (isset($_POST['cat_id']) && $_POST['cat_id'] && $_POST['mode'] == 'add') {
        $db = getDbInstance();
        $log_user_id = $_SESSION['user_id'];
        $media_type = $_POST['note_media'];
        $note_date = $_POST['note_date'];
        $note_comment = $_POST['note_comment'];
        $cat_id = $_POST['cat_id'];
        $note_value = '';

        // If media type is photo, then get img_url after upload that photo
        if($media_type == 'photo'){
            $target_dir = "./uploads/".$_SESSION['user_id']."/fam_notes/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);  // create directory if not exist
            }
            $target_file = $target_dir . uniqid('notes_', true) . basename($_FILES["note_photo"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["note_photo"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0; // File is not an image.
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                $_SESSION['failure'] = "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["note_photo"]["size"] > 2000000) {
                $_SESSION['failure'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                //            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an failure
            if ($uploadOk == 0) {
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["note_photo"]["tmp_name"], $target_file)) {
                    $note_value = $target_file;
                } else {
                    $_SESSION['failure'] =  "Sorry, there was an failure uploading your file.";
                }
            }
        }
        else if ($media_type == 'text' ){
            $note_value = $_POST['note_value'];
        }
        else if ($media_type == 'video') {
            $note_value = $_POST['note_video'];
        }

        if ($note_value != '') {
            // data to save to db
            $data_to_db = [];
            $data_to_db['note_value'] = $note_value; // text, video link, photo url
            $data_to_db['note_media'] = $media_type; // video, text, photo
            $data_to_db['cat_id'] = $cat_id;   // category id => 1: My Story, 2: ...
            $data_to_db['note_date'] = $note_date; // note post date
            $data_to_db['user_id'] = $log_user_id; // sender id
            $data_to_db['note_comment'] = $note_comment; // sender id

            $db = getDbInstance();
            $note_id = $db->insert('tbl_fam_notes', $data_to_db);

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

        $result = get_fam_note_lists('', '', $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_view_date']) && $_POST['view_category']) {
        $view_date = $_POST['note_view_date'];
        $view_cat = $_POST['view_category'];
        $newDate = date("Y-m-d", strtotime($view_date));

        $result = get_fam_note_lists($view_cat, $newDate, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_update_date']) && $_POST['update_category'] && !isset($_POST['mode'])) {
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
        $newDate = date("Y-m-d", strtotime($update_date));

        $result = get_update_note_lists($update_cat, $newDate, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['mode']) && isset($_POST['note_id']) && $_POST['note_id'] != '' && $_POST['mode'] == 'edit') {
        $media_type = $_POST['note_media'];
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
        // Update text
        if($media_type == 'text' && isset($_POST['note_value'])){
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_value'];
            $db = getDbInstance();
            $db->where('id', $_POST['note_id']);
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $last_id = $db->update('tbl_fam_notes', $data_to_db);
            if ($last_id)
            {
                $_SESSION['success'] = 'Successfully updated';
            }
            else
            {
                $_SESSION['failure'] = 'Update failed!';
            }
        }
        // Update photo
        else if($media_type == 'photo' && isset($_FILES["note_photo"]["name"]) && $_FILES["note_photo"]["name"]) {
            $target_dir = "./uploads/".$_SESSION['user_id']."/fam_notes/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);  //create directory if not exist
            }
            $target_file = $target_dir . uniqid('notes_', true) . basename($_FILES["note_photo"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["note_photo"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0; // File is not an image.
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                $_SESSION['failure'] = "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["note_photo"]["size"] > 500000) {
                $_SESSION['failure'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an failure
            if ($uploadOk == 0) {
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["note_photo"]["tmp_name"], $target_file)) {
                    $data_to_db = array();
                    $data_to_db['note_value'] = $target_file;
                    $data_to_db['note_comment'] = $_POST['note_comment'];
                    $db = getDbInstance();
                    $db->where('id', $_POST['note_id']);
                    $last_id = $db->update('tbl_fam_notes', $data_to_db);

                    if ($last_id)
                    {
                        $_SESSION['success'] = 'Successfully updated';
                    }
                    else
                    {
                        $_SESSION['failure'] = 'Update failed!';
                    }
                } else {
                    $_SESSION['failure'] = "Sorry, your file was not uploaded.";
                }
            }
        }
        else if($media_type == 'photo' && empty($_FILES["note_photo"]["name"])) {
            $db = getDbInstance();
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['update_note_photo'];
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fam_notes', $data_to_db);

            if ($last_id)
            {
                $_SESSION['success'] = 'Successfully updated';
            }
            else
            {
                $_SESSION['failure'] = 'Update failed!';
            }
        }
        // Update video
        else if($media_type == 'video' && isset($_POST['note_video'])) {
            $db = getDbInstance();
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_video'];
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fam_notes', $data_to_db);

            if ($last_id)
            {
                $_SESSION['success'] = 'Successfully updated';
            }
            else
            {
                $_SESSION['failure'] = 'Update failed!';
            }
        }

        $result = get_update_note_lists($update_cat, $update_date, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
} else {
    $result = get_fam_note_lists('', '', $page, $pageLimit);
    $totalPages = $result['totalPages'];
    $rows = $result['rows'];
}

if ($page >= $totalPages) {
    $next_page = $totalPages;
} else {
    $next_page = $page + 1;
}
if ($page > 1) {
    $prev_page = $page - 1;
} else {
    $prev_page = $page;
}
