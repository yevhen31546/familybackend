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

/*
 * Init pagination variables
 */
$page = 1;
$pageLimit = 5;
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


// Function to fetch saved note lists
function get_fri_group_note_lists($cat, $note_date, $group_id, $page, $pageLimit) {
    $db = getDbInstance();
    $offset = $pageLimit * ($page - 1);
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, us.`last_name`, tmp.`note_date`, tmp.note_media, 
                    tmp.`note_value`, tmp.note_id, tmp.cat_id, tmp.note_comment, tmp.`cat_name`
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, notes.`user_id`, notes.`id` AS note_id,
                        notes.`note_media`, notes.note_comment, category.cat_name
            FROM tbl_fri_group_notes notes, tbl_group_note_cat category
            WHERE notes.group_id = '.$group_id.' AND notes.`cat_id` = category.`id`) tmp, tbl_users us
            WHERE us.`id`= tmp.user_id';

    if ($cat != '') {
        $query .=' AND tmp.cat_id ='.$cat;
    }
    if ($note_date != '') {
        $query .=' AND tmp.note_date ="'.$note_date.'"';
    }

    $totalCount = count($db->rawQuery($query));
    $query .=' ORDER BY tmp.note_date DESC, tmp.note_id DESC LIMIT '.$offset.', '.$pageLimit;
    $rows = $db->rawQuery($query);

    $result = array();
    $result['totalPages'] = ceil($totalCount / $pageLimit);
    $result['rows'] = $rows;
    return $result;
}

function get_fri_group_note__update_lists($cat, $note_date, $group_id, $page, $pageLimit) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $offset = $pageLimit * ($page - 1);
    $query = 'SELECT us.`id`, us.`avatar`, us.`first_name`, us.`last_name`, tmp.`note_date`, tmp.note_media,
                      tmp.`note_value`, tmp.note_id, tmp.cat_id, tmp.note_comment, tmp.`cat_name`
            FROM (SELECT notes.`note_date`, notes.`cat_id`, notes.`note_value`, notes.`user_id`, notes.`id` AS note_id,
                          notes.`note_media`, notes.note_comment, category.`cat_name`
            FROM tbl_fri_group_notes notes, tbl_group_note_cat category
            WHERE notes.`user_id` = '.$user_id.' AND notes.group_id = '.$group_id.' AND notes.`cat_id` = category.`id`) tmp, tbl_users us
            WHERE us.`id`= tmp.user_id';

    if ($cat != '') {
        $query .=' AND tmp.cat_id ='.$cat;
    }
    if ($note_date != '') {
        $query .=' AND tmp.note_date ="'.$note_date.'"';
    }

    $totalCount = count($db->rawQuery($query));
    $query .=' ORDER BY tmp.note_date DESC, tmp.note_id DESC LIMIT '.$offset.', '.$pageLimit;
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
        $media_type = $_POST['note_media'];
        $group_id = $group['id'];
        $note_date = $_POST['note_date'];
        $cat_id = $_POST['cat_id'];
        $note_comment = $_POST['note_comment'];
        $note_value = '';

        // If media type is photo, then get img_url after upload that photo
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
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
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
            $data_to_db['note_comment'] = $note_comment; // sender id

            $note_id = $db->insert('tbl_fri_group_notes', $data_to_db);

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

        $result = get_fri_group_note_lists('', '', $group['id'], $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_view_date']) && $_POST['view_category']) {
        $view_date = $_POST['note_view_date'];
        $view_cat = $_POST['view_category'];
        $newDate = '';
        if ($view_date) {
            $newDate = date("Y-m-d", strtotime($view_date));
        }

        $result = get_fri_group_note_lists($view_cat, $newDate, $group['id'], $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_update_date']) && $_POST['update_category'] && !isset($_POST['mode'])) {
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
        $newDate = '';
        if ($update_date) {
            $newDate = date("Y-m-d", strtotime($update_date));
        }

        $result = get_fri_group_note__update_lists($update_cat, $newDate, $group['id'], $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['mode']) && isset($_POST['note_id']) && $_POST['note_id'] != '' && $_POST['mode'] == 'edit') {
        $media_type = $_POST['note_media'];
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
        $newDate = '';
        if ($update_date) {
            $newDate = date("Y-m-d", strtotime($update_date));
        }

        // Update text
        if($media_type == 'text' && isset($_POST['note_value'])){
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_value'];
            $db = getDbInstance();
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_group_notes', $data_to_db);
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
        // Update photo
        else if($media_type == 'photo' && isset($_FILES["note_photo"]["name"]) && $_FILES["note_photo"]["name"]) {
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
                    $last_id = $db->update('tbl_fri_group_notes', $data_to_db);

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
            $last_id = $db->update('tbl_fri_group_notes', $data_to_db);

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
            $data_to_db['note_value'] = $_POST['note_video'];
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_fri_group_notes', $data_to_db);

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

        $result = get_fri_group_note__update_lists($update_cat, $update_date, $group['id'], $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
} else {
    $result = get_fri_group_note_lists('', '', $group['id'], $page, $pageLimit);
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
