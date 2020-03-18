<?php

/*
 *  Get current user
 */
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$user = $db->getOne('tbl_users');

/*
 * Get category lists
 */
$db = getDbInstance();
$category_lists = $db->get('tbl_categories');

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

// Function to save note lists
function save_note_lists($data) {
    $data_to_db['note_value'] = $data['note_value']; // text, video link, photo url
    $data_to_db['note_media'] = $data['media_type']; // video, text, photo
    $data_to_db['cat_id'] = $data['cat_id'];   // category id => 1: My Story, 2: ...
    $data_to_db['note_date'] = $data['note_date']; // note post date
    $data_to_db['user_id'] = $data['who']; // sender id
    $data_to_db['note_comment'] = $data['note_comment']; // sender id

    $db = getDbInstance();
    $note_id = $db->insert('tbl_notes', $data_to_db);
    return $note_id;
}

// Function to fetch saved note lists
function get_note_lists($cat, $note_date, $page, $pageLimit) {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
    $offset = $pageLimit * ($page - 1);
    $query = 'SELECT *
                FROM
                (SELECT *
                FROM tbl_notes
                LEFT JOIN (
                SELECT
                tbl_categories.id AS categoryId,
                tbl_categories.cat_name AS cat_name
                FROM tbl_categories
                ) categories ON tbl_notes.cat_id = categories.categoryId
                LEFT JOIN (
                SELECT
                tbl_users.id AS userid,
                tbl_users.first_name AS first_name,
                tbl_users.last_name AS last_name,
                tbl_users.avatar AS avatar
                FROM tbl_users
                ) users ON tbl_notes.user_id = users.userid) tmp
                WHERE tmp.user_id = '.$user_id;

    if ($cat != '' && $note_date != '') {
        $query .=' AND tmp.categoryId ='.$cat.' AND tmp.note_date = "'.$note_date.'"';
    }

    $totalCount = count($db->rawQuery($query));
    $query .=' ORDER BY tmp.note_date DESC, tmp.id DESC LIMIT '.$offset.', '.$pageLimit;
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

        // data to save to db
        $data = array(
            'who' => $log_user_id,
            'media_type' => $media_type,
            'cat_id' => $_POST['cat_id'],
            'note_value' => '',
            'note_comment' => $_POST['note_comment'],
            'note_date' => $_POST['note_date']
        );

        // If media type is photo, then get img_url after upload that photo
        if($media_type == 'photo'){
            $target_dir = "./uploads/".$_SESSION['user_id']."/notes/";
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
                $uploadOk = 0; // File is not an image
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
                    $data['note_value'] = $target_file;
                } else {
                    $bell_count++;
                    $_SESSION['failure'] = "Sorry, there was an failure uploading your file.<hr>";
                }
            }
        }
        else if ($media_type == 'text' ){
            $data['note_value'] = $_POST['note_value'];
        }
        else if ($media_type == 'video') {
            $data['note_value'] = $_POST['note_video'];
        }

        if($data['note_value'] != '') {
            $note_id = save_note_lists($data);
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

        $result = get_note_lists('', '', $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_view_date']) && $_POST['view_category']) {
        $view_date = $_POST['note_view_date'];
        $view_cat = $_POST['view_category'];
        $newDate = date("Y-m-d", strtotime($view_date));

        $result = get_note_lists($view_cat, $newDate, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['note_update_date']) && $_POST['update_category'] && !isset($_POST['mode'])) {
        $update_cat = $_POST['update_category'];
        $update_date = $_POST['note_update_date'];
        $newDate = date("Y-m-d", strtotime($update_date));

        $result = get_note_lists($update_cat, $newDate, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
    elseif (isset($_POST['mode']) && isset($_POST['note_id']) && $_POST['note_id'] != '' && $_POST['mode'] == 'edit') {
        $media_type = $_POST['note_media'];
        $update_date = $_POST['note_update_date'];
        $update_cat = $_POST['update_category'];
        // Update text
        if($media_type == 'text' && isset($_POST['note_value'])){
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['note_value'];
            $db = getDbInstance();
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_notes', $data_to_db);
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
            $target_dir = "./uploads/".$_SESSION['user_id']."/notes/";
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
                    $last_id = $db->update('tbl_notes', $data_to_db);

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
        // Update only photo's comment
        else if($media_type == 'photo' && empty($_FILES["note_photo"]["name"])) {
            $db = getDbInstance();
            $data_to_db = array();
            $data_to_db['note_value'] = $_POST['update_note_photo'];
            $data_to_db['note_comment'] = $_POST['note_comment'];
            $db->where('id', $_POST['note_id']);
            $last_id = $db->update('tbl_notes', $data_to_db);

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
            $last_id = $db->update('tbl_notes', $data_to_db);

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

        $result = get_note_lists($update_cat, $update_date, $page, $pageLimit);
        $totalPages = $result['totalPages'];
        $rows = $result['rows'];
    }
} else {
    $result = get_note_lists('', '', $page, $pageLimit);
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
