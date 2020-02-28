<?php

$logged_id = $_SESSION['user_id'];

/*
 * Check group exists or not
 */
if (isset($_GET) && isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];
    $db = getDbInstance();
    $db->where('id', $group_id);
    $group = $db->getOne('tbl_fri_groups');
} else {
    header('Location: '.BASE_URL.'/members/activity-frd.php');
}

/*
 * Get category lists
 */
$db = getDbInstance();
$category_lists = $db->get('tbl_group_note_cat');

/*
 * Get Group members
 */
$db = getDbInstance();
$query = 'SELECT tmp.who
		FROM (SELECT fri_gp.by_who AS who
                FROM tbl_fri_groups fri_gp
                WHERE fri_gp.id='.$group['id'].'
                UNION
                SELECT fri_gp_mems.who FROM tbl_fri_groups_members fri_gp_mems
		WHERE fri_gp_mems.group_id='.$group['id'].' AND fri_gp_mems.stat=1) tmp
                WHERE tmp.who!='.$logged_id;
$members = $db->rawQuery($query);
//print_r($members); exit;

/*
 * Approve or delete group note
 */
if(isset($_GET) && isset($_GET['notification_id'])){
    if (empty($_POST) && (empty($_POST['update_category']) || empty($_POST['view_category']))) {
        $not_id = $_GET['notification_id']; // group_notification id
        $db = getDbInstance();
        if($_GET['stat'] == 'approved') {
            $db->where('id', $not_id);
            $status = $db->getValue('tbl_fri_gp_not', 'status');
            if($status) {
                header('Location: ' . BASE_URL . '/members/group-frd.php?group_id='.$group['id']);
            }
            else {
                $data_to_db['status'] = 1; // update status
                $db->where('id', $not_id);
                $last_id = $db->update('tbl_fri_gp_not', $data_to_db);
                if ($last_id) {
                    $_SESSION['success'] = 'Group note has approved successfully';

                } else {
                    $_SESSION['failure'] = 'error: Approve to notes';
                }
            }
        } else if($_GET['stat'] == 'delete') {
            $db->where('id', $not_id);
            $status = $db->getValue('tbl_fri_gp_not', 'status');
            if($status === -1) {
                header('Location: ' . BASE_URL . '/members/group-frd.php?group_id='.$group['id']);
            }
            else {
                $data_to_db['status'] = -1; // update status

                $db->where('id', $not_id);
                $last_id = $db->update('tbl_fri_gp_not', $data_to_db);  // Update tbl_notes's status
                if ($last_id) {
                    $_SESSION['success'] = 'Group note has disapproved successfully';

                } else {
                    $_SESSION['failure'] = 'error: disapprove to notes';
                }
            }
        }
    }
}

// Function to fetch saved note lists
function get_fri_group_note_lists($cat, $note_date, $group_id) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, us.`last_name`, tmp.`note_date`, tmp.note_media, tmp.`note_value`, tmp.note_id, tmp.cat_id
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, notes.`user_id`, notes.`id` AS note_id, notes.`note_media`
            FROM tbl_fri_group_notes notes
            WHERE notes.group_id = '.$group_id.') tmp, tbl_users us
            WHERE us.`id`= tmp.user_id';
    if ($cat != '' && $note_date != '') {
        $query .=' AND tmp.cat_id ='.$cat.' AND tmp.note_date = "'.$note_date.'"';
    }
    $query .=' ORDER BY tmp.note_date DESC';

    $rows = $db->rawQuery($query);

    return $rows;
}

function get_fri_group_note__update_lists($cat, $note_date, $group_id) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, us.`last_name`, tmp.`note_date`, tmp.note_media, tmp.`note_value`, tmp.note_id, tmp.cat_id
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, notes.`user_id`, notes.`id` AS note_id, notes.`note_media`
            FROM tbl_fri_group_notes notes
            WHERE notes.`user_id` = '.$user_id.' AND notes.group_id = '.$group_id.') tmp, tbl_users us
            WHERE us.`id`= tmp.user_id';

    if ($cat != '' && $note_date != '') {
        $query .=' AND tmp.cat_id ='.$cat.' AND tmp.note_date = "'.$note_date.'"';
    }
    $query .=' ORDER BY tmp.note_date DESC';

    $rows = $db->rawQuery($query);
    return $rows;
}

// Add && View && Update Notes
if(isset($_POST) && $_POST) {
    if (isset($_POST['cat_id']) && $_POST['cat_id'] && $_POST['mode'] == 'add') {
        $db = getDbInstance();
        $media_type = $_POST['note_media'];
        $group_id = $group['id'];
        $note_date = $_POST['note_date'];
        $cat_id = $_POST['cat_id'];
        $note_value = '';

        //    If media type is photo, then get img_url after upload that photo
        if ($media_type == 'photo') {
            $target_dir = "./uploads/" . $logged_id . "/fri_group_notes/";
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
        }
        else if ($media_type == 'text') {
            $note_value = $_POST['note_value'];
        } else if ($media_type == 'video') {
            $note_value = $_POST['note_video'];
        }

        if ($note_value != '') {
            $data_to_db = [];
            $data_to_db['note_value'] = $note_value; // text, video link, photo url
            $data_to_db['note_media'] = $media_type; // video, text, photo
            $data_to_db['cat_id'] = $cat_id;   // category id => 1: My Story, 2: ...
            $data_to_db['note_date'] = $note_date; // note post date
            $data_to_db['user_id'] = $logged_id; // sender id
            $data_to_db['group_id'] = $group_id; // sender id

            $note_id = $db->insert('tbl_fri_group_notes', $data_to_db);

            if ($note_id) {
//                for ($i = 0; $i < count($members); $i++) {
//                    $data['note_id'] = $note_id;
//                    $data['group_id'] = $group_id;
//                    $data['to_who'] = $members[$i]['who'];
//                    $db->where('group_id', $data['group_id']);
//                    $db->where('to_who', $data['to_who']);
//                    $status = $db->getValue('tbl_fri_gp_not', 'status');
//
//                    // Check family exists
//                    if ($status !== 1) {
//                        $db->insert('tbl_fri_gp_not', $data);
//                    }

//                    // data to email
//                    $email_param = array(
//                        'who' => $log_user_id,
//                        'to_who' => $to,
//                        'note_id' => ''
//                    );
//
//                    // Send email to user
//                    $email_param['note_id'] = $note_id;
//                    $result = sendAddNoteEmail($email_param);
//                    if($result) {
//                        $_SESSION['success'] = "Note posted!";
//                        $_POST = array();
//                    } else {
//                        $_SESSION['success'] = "Note isn't posted :(";
//                    }
//                }
                $_SESSION['success'] = 'Note added successfully. ';
            } else {
                $_SESSION['failure'] = 'Oops, failure... ';
            }
        } else {
            $_SESSION['failure'] = "Sorry, error occur in photo uploading!";
        }
        $rows = get_fri_group_note_lists('', '', $group['id']);
    }
    elseif (isset($_POST['note_view_date']) && $_POST['view_category']) {
        $view_date = $_POST['note_view_date'];
        $view_cat = $_POST['view_category'];
        $newDate = date("Y-m-d", strtotime($view_date));

        $rows = get_fri_group_note_lists($view_cat, $newDate, $group['id']);
    }
    elseif (isset($_POST['note_update_date']) && $_POST['update_category'] && !isset($_POST['mode'])) {
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
        $newDate = date("Y-m-d", strtotime($update_date));

        $rows = get_fri_group_note__update_lists($update_cat, $newDate, $group['id']);
    }
    elseif (isset($_POST['mode']) && isset($_POST['note_id']) && $_POST['note_id'] != '' && $_POST['mode'] == 'edit') {
        $media_type = $_POST['note_media'];
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
//        echo $_POST['note_id']; exit;
        //    Update text
        if($media_type == 'text' && isset($_POST['note_value'])){
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_value'];
            $db = getDbInstance();
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_group_notes', $data_to_db);
            if ($last_id)
            {
                $_SESSION['success'] = 'Successfully updated';
            }
            else
            {
                $_SESSION['failure'] = 'Update failed!';
            }
            $rows = get_fri_group_note__update_lists($update_cat, $update_date, $group['id']);
        }
        //    Update photo
        else if($media_type == 'photo' && isset($_FILES["note_photo"]["name"])) {
            $target_dir = "./uploads/".$logged_id."/fri_group_notes/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);  //create directory if not exist
            }
            $target_file = $target_dir . uniqid('notes_', true) . basename($_FILES["note_photo"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["note_photo"]["tmp_name"]);
            if($check !== false) {
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
            if ($_FILES["note_photo"]["size"] > 500000) {
                //            echo "Sorry, your file is too large.";
                $_SESSION['failure'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            //         Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
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
                    $data_to_db = array();
                    $data_to_db['note_value'] = $target_file;
                    $db = getDbInstance();
                    $db->where('id', $_POST['note_id']);
                    $last_id = $db->update('tbl_fri_group_notes', $data_to_db);

                    if ($last_id)
                    {
                        $_SESSION['success'] = 'Successfully updated';
                    }
                    else
                    {
                        $_SESSION['failure'] = 'Update failed!';
                    }
                } else {
                    //                echo "Sorry, there was an failure uploading your file.";
                    $_SESSION['failure'] = "Sorry, your file was not uploaded.";
                }
            }
            $rows = get_fri_group_note__update_lists($update_cat, $update_date, $group['id']);
        }
        // Update video
        else if($media_type == 'video' && isset($_POST['note_video'])) {
            $db = getDbInstance();
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_video'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_group_notes', $data_to_db);

            if ($last_id)
            {
                $_SESSION['success'] = 'Successfully updated';
            }
            else
            {
                $_SESSION['failure'] = 'Update failed!';
            }
            $rows = get_fri_group_note__update_lists($update_cat, $update_date, $group['id']);
        }
    }
} else {
    $rows = get_fri_group_note_lists('', '', $group['id']);
}

