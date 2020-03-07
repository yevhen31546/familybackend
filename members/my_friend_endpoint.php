<?php

$logged_id = $_SESSION['user_id'];

/*
 * Get category lists
 */
$db = getDbInstance();
$category_lists = $db->get('tbl_group_note_cat');

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
            $status = $db->getValue('tbl_fri_groups_members', 'stat');
            if ($status) {
                header('Location: ' . BASE_URL . '/members/activity-frd.php');
            } else {
                $db->where('id', $member_id);
                $result = $db->update('tbl_fri_groups_members', $data_to_db);
                if ($result) {
                    $bell_count++;
                    $_SESSION['success'] = 'Approved as group member!<hr>';
                } else {
                    $bell_count++;
                    $_SESSION['failure'] = 'Approving as group member is failed!<hr>';
                }
            }
        } else if ($stat == 'delete') {
            $data_to_db = array(
                'stat' => -1
            );
            $db = getDbInstance();
            $db->where('id', $member_id);
            $status = $db->getValue('tbl_fri_groups_members', 'stat');
            if ($status) {
                header('Location: ' . BASE_URL . '/members/activity-frd.php');
            } else {
                $db->where('id', $member_id);
                $last_id = $db->update('tbl_fri_groups_members', $data_to_db);
                if ($last_id) {
                    $bell_count++;
                    $_SESSION['success'] = 'Disapproved as group member!<hr>';
                } else {
                    $bell_count++;
                    $_SESSION['failure'] = 'Disapproving as group member is failed!<hr>';
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
        $checkExistInGroup = $db->getOne('tbl_fri_groups_members');

        if (count($checkExistInGroup) > 0) {
            $db->where('group_id', $group_id);
            $db->where('who', $member_id);
            $last_id = $db->delete('tbl_fri_groups_members');
            if ($last_id) {
                $bell_count++;
                $_SESSION['success'] = 'Exit from group successfully!<hr>';
            } else {
                $bell_count++;
                $_SESSION['failure'] = 'Exit group failed!<hr>';
            }
        } else {
            header('Location: ' . BASE_URL . '/members/activity-frd.php');
        }
    }
}


/*
 * Check family group exists or not
 */
    $query = 'SELECT tbl_fri_groups.`id`, tbl_fri_groups.`group_name`
        FROM
        (SELECT DISTINCT fri_gp_mems.group_id
        FROM tbl_fri_groups_members fri_gp_mems
        WHERE fri_gp_mems.`who` = '.$logged_id.' AND stat = 1) tmp, tbl_fri_groups
        WHERE tmp.group_id = tbl_fri_groups.`id`
        ORDER BY tmp.group_id';
    $belongs_group_lists = $db->rawQuery($query);

    $query = 'SELECT tbl_fri_groups.`id`, tbl_fri_groups.`group_name`
        FROM
        (
        SELECT DISTINCT gp.`id` AS group_id
        FROM tbl_fri_groups gp
        WHERE gp.`by_who` = '.$logged_id.'
        ) tmp, tbl_fri_groups
        WHERE tmp.group_id = tbl_fri_groups.`id`
        ORDER BY tmp.group_id';
    $create_group_lists = $db->rawQuery($query);


// Function to fetch saved note lists
function get_fri_note_lists($cat, $note_date, $page, $pageLimit) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $offset = $pageLimit * ($page - 1);
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, us.`last_name`, tmp.`note_date`, tmp.note_media,
                      tmp.`note_value`, tmp.note_id, tmp.cat_id, tmp.note_comment
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, notes.`user_id`, 
                      notes.`id` AS note_id, notes.`note_media`, notes.note_comment
            FROM tbl_fri_notes notes
            WHERE notes.`user_id` = '.$user_id.'
            UNION
            SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, 
                    notes.`user_id`, notes.`id` AS note_id, notes.`note_media`, notes.note_comment
            FROM tbl_fri_notes notes
            WHERE notes.`user_id` IN (
                        SELECT DISTINCT fff.frd_id
                        FROM (
                            SELECT frd.with_who AS frd_id
                            FROM tbl_friend frd
                            WHERE frd.who = '.$user_id.' AND frd.stat = 1
                            UNION
                            SELECT frd.who AS frd_id
                            FROM tbl_friend frd
                            WHERE frd.with_who = '.$user_id.' AND frd.stat = 1
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
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, tmp.note_comment,
             us.`last_name`, tmp.`note_date`, tmp.note_media, tmp.`note_value`, tmp.note_id, tmp.cat_id
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.note_comment,
            notes.`note_value`, notes.`user_id`, notes.`id` AS note_id, notes.`note_media`
            FROM tbl_fri_notes notes
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
            $target_dir = "./uploads/".$_SESSION['user_id']."/fri_notes/";
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
                $bell_count++;
                $_SESSION['failure'] = "Sorry, file already exists.<hr>";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["note_photo"]["size"] > 2000000) {
                $bell_count++;
                $_SESSION['failure'] = "Sorry, your file is too large.<hr>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                $bell_count++;
                $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<hr>";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an failure
            if ($uploadOk == 0) {
                $bell_count++;
                $_SESSION['failure'] = "Sorry, your file was not uploaded.<hr>";
            } else {
                if (move_uploaded_file($_FILES["note_photo"]["tmp_name"], $target_file)) {
                    $note_value = $target_file;
                } else {
                    $bell_count++;
                    $_SESSION['failure'] = "Sorry, there was an failure uploading your file.<hr>";
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
            $data_to_db['note_comment'] = $note_comment;

            $db = getDbInstance();
            $note_id = $db->insert('tbl_fri_notes', $data_to_db);

            if ($note_id) {
                $bell_count++;
                $_SESSION['success'] = 'Note added successfully.<hr>';
            } else {
                $bell_count++;
                $_SESSION['failure'] = 'Oops, failure...<hr>';
            }

        } else {
            $bell_count++;
            $_SESSION['failure'] = "Sorry, error occur in photo uploading!<hr>";
        }

        $result = get_fri_note_lists('', '', $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_view_date']) && $_POST['view_category']) {
        $view_date = $_POST['note_view_date'];
        $view_cat = $_POST['view_category'];
        $newDate = date("Y-m-d", strtotime($view_date));

        $result = get_fri_note_lists($view_cat, $newDate, $page, $pageLimit);
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
        //    Update text
        if($media_type == 'text' && isset($_POST['note_value'])){
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_value'];
            $db = getDbInstance();
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_notes', $data_to_db);
            if ($last_id)
            {
                $bell_count++;
                $_SESSION['success'] = 'Successfully updated<hr>';
            }
            else
            {
                $bell_count++;
                $_SESSION['failure'] = 'Update failed!<hr>';
            }
        }
        //    Update photo
        else if($media_type == 'photo' && isset($_FILES["note_photo"]["name"]) && $_FILES["note_photo"]["name"]) {
            $target_dir = "./uploads/".$_SESSION['user_id']."/fri_notes/";
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
                $bell_count++;
                $_SESSION['failure'] = "Sorry, file already exists.<hr>";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["note_photo"]["size"] > 500000) {
                $bell_count++;
                $_SESSION['failure'] = "Sorry, your file is too large.<hr>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                $bell_count++;
                $_SESSION['failure'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<hr>";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an failure
            if ($uploadOk == 0) {
                $bell_count++;
                $_SESSION['failure'] = "Sorry, your file was not uploaded.<hr>";
            } else {
                if (move_uploaded_file($_FILES["note_photo"]["tmp_name"], $target_file)) {
                    $data_to_db = array();
                    $data_to_db['note_value'] = $target_file;
                    $data_to_db['note_comment'] = $_POST['note_comment'];
                    $db = getDbInstance();
                    $db->where('id', $_POST['note_id']);
                    $last_id = $db->update('tbl_fri_notes', $data_to_db);

                    if ($last_id)
                    {
                        $bell_count++;
                        $_SESSION['success'] = 'Successfully updated<hr>';
                    }
                    else
                    {
                        $bell_count++;
                        $_SESSION['failure'] = 'Update failed!<hr>';
                    }
                } else {
                    $bell_count++;
                    $_SESSION['failure'] = "Sorry, your file was not uploaded.<hr>";
                }
            }
        }
        else if($media_type == 'photo' && empty($_FILES["note_photo"]["name"])) {
            $db = getDbInstance();
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['update_note_photo'];
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_notes', $data_to_db);

            if ($last_id)
            {
                $bell_count++;
                $_SESSION['success'] = 'Successfully updated<hr>';
            }
            else
            {
                $bell_count++;
                $_SESSION['failure'] = 'Update failed!<hr>';
            }
        }
        // Update video
        else if($media_type == 'video' && isset($_POST['note_video'])) {
            $db = getDbInstance();
            $data_to_db = array();
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $data_to_db['note_value'] = $_POST['note_video'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_notes', $data_to_db);

            if ($last_id)
            {
                $bell_count++;
                $_SESSION['success'] = 'Successfully updated<hr>';
            }
            else
            {
                $bell_count++;
                $_SESSION['failure'] = 'Update failed!<hr>';
            }
        }

        $result = get_update_note_lists($update_cat, $update_date, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
} else {
    $result = get_fri_note_lists('', '', $page, $pageLimit);
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
