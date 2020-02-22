<?php

/**
 * After user approve or delete your notes, update notes
 */

//    If friend/family approve your request, then update status tbl_notes
if(isset($_GET) && isset($_GET['stat'])){
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
    if($_GET['stat'] == 'approved') {
        $data_to_db['status'] = 1; // update status

        $db = getDbInstance();
        $db->where('id', $note_id);
        $last_id = $db->update('tbl_notes', $data_to_db);  // Update tbl_notes's status
        if ($last_id) {
//            echo "successfully added";

            $body = generateApprovedNoteMessageBody($sender, $receiver);
            $stat = sendNoteEmail($to, $body);
            if ($stat) {
                $_SESSION['success'] = $sender['user_name'].' has posted something on your profile successfully';
            }

        } else {
//            echo "error: save to notes";
        }
    } else if($_GET['stat'] == 'delete') {
        $body = generateDeleteNoteMessageBody($sender, $receiver);
        $stat = sendNoteEmail($to, $body);
//        $db = getDbInstance();
//        $db->where('id', $note_id);
//        $db->delete('tbl_notes');  // Delete posted note
    }
}

// Function to fetch saved note lists
function get_note_lists() {
    $user_id = $_SESSION['user_id'];
    $db = getDbInstance();
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
                    tbl_users.last_name AS last_name
                    FROM tbl_users
                    ) users ON tbl_notes.user_id = users.userid) tmp
                    WHERE tmp.user_id = '.$user_id.' OR (tmp.note_to = '.$user_id.' AND tmp.status = 1)';
    $rows = $db->rawQuery($query);
    return $rows;
}

// Function to save note lists
function save_note_lists($data) {
    $data_to_db['note_value'] = $data['note_value']; // text, video link, photo url
    $data_to_db['note_media'] = $data['media_type']; // video, text, photo
    $data_to_db['cat_id'] = $data['cat_id'];   // category id => 1: My Story, 2: ...
    $data_to_db['note_date'] = $data['note_date']; // note post date
    $data_to_db['note_to'] = $data['to_who']; // receiver profile id
    $data_to_db['user_id'] = $data['who']; // sender id

    $db = getDbInstance();
    $note_id = $db->insert('tbl_notes', $data_to_db);
    return $note_id;
}



/**
 * Send Notes
 */
if(isset($_POST) && isset($_POST['cat_id']) && $_POST['cat_id'] && $_POST['mode'] == 'add') {
    $db = getDbInstance();
    $log_user_id = $_SESSION['user_id'];
    $media_type = $_POST['note_media'];

//    get receiver's id
    $db = getDbInstance();
    $db->where('user_name', $_POST['note_to']);
    $to = $db->getValue('tbl_users', 'id');
// data to save to db
    $data = array(
        'who' => $log_user_id,
        'to_who' => $to,
        'media_type' => $media_type,
        'cat_id' => $_POST['cat_id'],
        'note_value' => '',
        'note_date' => $_POST['note_date']
    );
// data to email
    $email_param = array(
        'who' => $log_user_id,
        'to_who' => $to,
        'note_id' => ''
    );


//    If media type is photo, then get img_url after upload that photo
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
                $data['note_value'] = $target_file;
            } else {
                //                echo "Sorry, there was an failure uploading your file.";
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
        //    Save notes
        $note_id = save_note_lists($data);
        if ($note_id) {
           $_SESSION['success'] = 'Note added successfully. ';
        } else {
            $_SESSION['failure'] = 'Oops, failure... ';
        }

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

// View Notes
if(isset($_POST) && isset($_POST['view_date']) && $_POST['view_date']) {
    $db = getDbInstance();
    $view_date = $_POST['view_date'];

    $view_cat = $_POST['view_category'];

    $query = 'SELECT *
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
    tbl_users.last_name AS last_name
    FROM tbl_users
    ) users ON tbl_notes.user_id = users.userid';

    if($view_date == 'today') {
        $view_date = date('Y-m-d');
        $query .= ' WHERE cat_id = '.$view_cat.' AND note_date = '.$view_date;
    } else {
        $view_date = date('Y-m-d');
        $query .= ' WHERE cat_id = '.$view_cat.' AND note_date != '.$view_date;
    }

    $rows = $db->rawQuery($query);

}

// Update Notes
if(isset($_POST) && isset($_POST['update_date']) && $_POST['update_date']) {
    $db = getDbInstance();
    $update_date = $_POST['update_date'];

    $update_cat = $_POST['update_category'];

    $query = 'SELECT *
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
    tbl_users.last_name AS last_name
    FROM tbl_users
    ) users ON tbl_notes.user_id = users.userid';

    if($update_date == 'today') {
        $update_date = date('Y-m-d');
        $query .= ' WHERE cat_id = '.$update_cat.' AND note_date = '.$update_date;
    } else {
        $update_date = date('Y-m-d');
        $query .= ' WHERE cat_id = '.$update_cat.' AND note_date != '.$update_date;
    }

    $rows = $db->rawQuery($query);

}

// Edit
if(isset($_POST) && isset($_POST['mode']) && isset($_POST['note_id']) && $_POST['note_id'] != '' && $_POST['mode'] == 'edit') {
    $media_type = $_POST['note_media'];
//    Update text
    if($media_type == 'text' && isset($_POST['note_value'])){
        $data_to_db = array();
        $data_to_db['note_value'] = $_POST['note_value'];
        $db = getDbInstance();
        $db->where('id', $_POST['note_id']);
        $last_id = $db->update('tbl_notes', $data_to_db);

        if ($last_id)
        {
            get_note_lists();
        }
        else
        {
            $_SESSION['failure'] = 'Update failed!';
        }
    }
//    Update photo
    else if($media_type == 'photo' && isset($_FILES["note_photo"]["name"])) {
        $target_dir = "./uploads/".$_SESSION['user_id']."/notes/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  //create directory if not exist
        }
        $target_file = $target_dir . basename($_FILES["note_photo"]["name"]);
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
                $last_id = $db->update('tbl_notes', $data_to_db);

                if ($last_id)
                {
                    $_SESSION['success'] = 'Successfully uploaded';
                }
                else
                {
                    $_SESSION['failure'] = 'Insert failed!';
                }
            } else {
                //                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            }
        }
        get_note_lists();
    }
// Update video
    else if($media_type == 'video' && isset($_POST['note_video'])) {
        $db = getDbInstance();
        $data_to_db = array();
        $data_to_db['note_value'] = $_POST['note_video'];
        $db->where('id', $_POST['note_id']);
        $last_id = $db->update('tbl_notes', $data_to_db);

        if ($last_id)
        {
            get_note_lists();
        }
        else
        {
            $_SESSION['failure'] = 'Insert failed!';
        }
    }

}
