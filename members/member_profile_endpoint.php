<?php

// Get current user
$logged_id = $_SESSION['user_id'];
$db = getDbInstance();
$db->where('id', $logged_id);
$row = $db->getOne('tbl_users');

//Get User list
$db = getDbInstance();
$user_list = $db->get('tbl_users');

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

//    Sender info
        $db = getDbInstance();
        $db->where('id', $sender_id);
        $sender = $db->getOne('tbl_users');

//    Receiver info
        $db = getDbInstance();
        $db->where('id', $receiver_id);
        $receiver = $db->getOne('tbl_users');

        if($status == 'approved') {
//          Update stat to family table
            $data_to_db = array(
                'stat'=> 1
            );
            $db = getDbInstance();
            $db->where('id', $family_id);
            $stat = $db->update('tbl_family', $data_to_db);

            if ($stat) {
                $_SESSION['success'] = $sender['user_name'].' has added you as family!';
            }

//            $to = $sender['user_email']; // sender's email
//            $body = generateApprovedFamMessageBody($sender, $receiver, $family_relation);
//            $stat = sendEmail($to, $body);
//            if ($stat) {
//                $_SESSION['success'] = $sender['user_name'].' has added you as family!';
//            }
        }

        if($status == 'delete') {
            $data_to_db['stat'] = -1; // update status

            $db = getDbInstance();
            $db->where('id', $family_id);
            $last_id = $db->update('tbl_family', $data_to_db);  // Update tbl_family's status

//            $to = $sender['user_email']; // sender's email
//            $body = generateDeleteFamMessageBody($sender, $receiver, $family_relation);
//            $stat = sendEmail($to, $body);
        }
    }
    if($_GET['type']=='friend') {
//        echo "Invite friend email is received";
        $status = $_GET['status'];
        $sender_id = $_GET['from'];
        $receiver_id = $_GET['to'];
        $friend_id = $_GET['friend_id'];

//    Sender info
        $db = getDbInstance();
        $db->where('id', $sender_id);
        $sender = $db->getOne('tbl_users');

//    Receiver info
        $db = getDbInstance();
        $db->where('id', $receiver_id);
        $receiver = $db->getOne('tbl_users');

        if($status == 'approved') {
//            echo "Request is approved!";
            $to = $sender['user_email']; // sender's email
            $body = generateApprovedFriMessageBody($sender, $receiver);

//          Saving data to family table
            $data_to_db = array(
                'stat' => 1
            );
            $db = getDbInstance();
            $db->where('id', $friend_id);
            $re = $db->update('tbl_friend', $data_to_db);

            if ($re) {
                $_SESSION['success'] = $sender['user_name'].' has added you a friend!';
            }
//            $stat = sendEmail($to, $body);
//            if ($stat) {
//                $_SESSION['success'] = $sender['user_name'].' has added you a friend!';
//            }
        }

        if($status == 'delete') {
            $data_to_db['stat'] = -1; // update status

            $db = getDbInstance();
            $db->where('id', $friend_id);
            $last_id = $db->update('tbl_friend', $data_to_db);  // Update tbl_friend's status

//            $to = $sender['user_email']; // sender's email
//            $body = generateDeleteFriMessageBody($sender, $receiver);
//            $stat = sendEmail($to, $body);
        }
    }
}

// Cover photo
if(isset($_POST) && isset($_POST['cover_photo_fg']) && isset($_FILES["cover_photo"]["name"])) {
    $db = getDbInstance();
    $data_to_db = array();
    if(isset($_FILES["cover_photo"]["name"])) {
        $target_dir = "./uploads/" . $_SESSION['user_id'] . "/covers/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  //create directory if not exist
        }
        $target_file = $target_dir . basename($_FILES["cover_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["cover_photo"]["tmp_name"]);

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
//        // Check file size
        if ($_FILES["cover_photo"]["size"] > 500000) {
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
//            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
//            echo "Sorry, your file was not uploaded.";
            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file)) {
//                echo "The file ". basename( $_FILES["cover_photo"]["name"]). " has been uploaded.";
                $data_to_db['cover_photo'] = $target_file;
                $db->where('id', $logged_id);
                $last_id = $db->update('tbl_users', $data_to_db);
                $db = getDbInstance();
                $db->where('id', $logged_id);
                $row = $db->getOne('tbl_users');
                if ($last_id) {
                    $_SESSION['success'] = 'Successfully uploaded';
                    $_POST = array();
                } else {
                    $_SESSION['failure'] = 'Insert failed!';
                    $_POST = array();
                }
            } else {
//                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
                $_POST = array();

            }
        }
    }
}

// Avatar
if(isset($_POST) && isset($_POST['avatar_fg']) && isset($_FILES["avatar_photo"]["name"])) {
    $db = getDbInstance();
    $data_to_db = array();
    if(isset($_FILES["avatar_photo"]["name"])) {
        $target_dir = "./uploads/" . $_SESSION['user_id'] . "/avatars/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  //create directory if not exist
        }
        $target_file = $target_dir . basename($_FILES["avatar_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["avatar_photo"]["tmp_name"]);

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
//        // Check file size
        if ($_FILES["avatar_photo"]["size"] > 500000) {
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
//            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
//            echo "Sorry, your file was not uploaded.";
//            $_SESSION['failure'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["avatar_photo"]["tmp_name"], $target_file)) {
//                echo "The file ". basename( $_FILES["cover_photo"]["name"]). " has been uploaded.";
                $data_to_db['avatar'] = $target_file;
                $db->where('id', $logged_id);
                $last_id = $db->update('tbl_users', $data_to_db);
                $db = getDbInstance();
                $db->where('id', $logged_id);
                $row = $db->getOne('tbl_users');
                if ($last_id) {
                    $_SESSION['success'] = 'Successfully uploaded';
                    $_POST = array();
                } else {
                    $_SESSION['failure'] = 'Insert failed!';
                    $_POST = array();
                }
            } else {
//                echo "Sorry, there was an failure uploading your file.";
                $_SESSION['failure'] = "Sorry, your file was not uploaded.";
                $_POST = array();

            }
        }
    }
}

// Update about me
if(isset($_POST) && isset($_POST['first_name'])) {
    $db = getDbInstance();
    $db->where('id', $logged_id);
    $stat = $db->update('tbl_users', $_POST);
    if ($stat)
    {
        $_SESSION['success'] = 'Profile has been updated successfully';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $row = $db->getOne('tbl_users');
        $_POST = array();
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update profile: ' . $db->getLastError();
        $_POST = array();
    }
}

// Update credentials
if(isset($_POST) && isset($_POST['user_name'])) {
    $db = getDbInstance();
    $db->where('id', $logged_id);
    $updated_arr = array(
        "user_name" => $_POST['user_name'],
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT)
    );
    $stat = $db->update('tbl_users', $updated_arr);
    if ($stat)
    {
        $_SESSION['success'] = 'Credentials has been updated successfully';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $row = $db->getOne('tbl_users');
        $_POST = array();
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update credentials: ' . $db->getLastError();
        $_POST = array();
    }
}

// Biography credentials
if(isset($_POST) && isset($_POST['biography'])) {
    $db = getDbInstance();
    $db->where('id', $logged_id);
    $stat = $db->update('tbl_users', $_POST);
    if ($stat)
    {
        $_SESSION['success'] = 'Biography has been updated successfully';
        $db = getDbInstance();
        $db->where('id', $logged_id);
        $row = $db->getOne('tbl_users');
        $_POST = array();
    } else {
        $_SESSION['profile_update_failure'] = 'Failed to update biography: ' . $db->getLastError();
        $_POST = array();
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

//    Request Save to tbl_family
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
        $_SESSION['failure'] = 'Already send, pending status...!';
    } else {
        $family_id = $db->insert('tbl_family', $data_to_db);

        if ($family_id) {
            $_SESSION['success'] = 'Invitation email is sent successfully!';
            $_POST = array();
        } else {
            $_SESSION['failure'] = 'Sending invitation email is failed!';
            $_POST = array();
        }
    }


//    $body = generateFamMessageBody($row, $family_user, $relation, $family_id); // $row: from, $family_user: to,
//    // $relation: family relationship
//
//    $stat = sendEmail($to, $body);
//    if ($stat) {
//        $_SESSION['success'] = 'Invitation email is sent successfully!';
//        $_POST = array();
//    } else {
//        $_SESSION['failure'] = 'Sending invitation email is failed!';
//        $_POST = array();
//    }
}

// Invite friend
if(isset($_POST) && isset($_POST['myfriend'])) {
    $myfriend = $_POST['myfriend']; // friend id
    $db = getDbInstance();
    $db->where('user_name', $myfriend);
    $friend_user = $db->getOne('tbl_users');
    $to = $friend_user['user_email'];

    //    Request Save to tbl_family
    $data_to_db = array(
        'who' => $logged_id,
        'with_who' => $friend_user['id']
    );
    $db->where('who', $logged_id);
    $db->where('with_who', $friend_user['id']);
    $stat = $db->getValue('tbl_friend', 'stat');
    if ($stat === 0) {
        $_SESSION['failure'] = 'Already send, pending status...!';
    } else {
        $friend_id = $db->insert('tbl_friend', $data_to_db);

        if ($friend_id) {
            $_SESSION['success'] = 'Invitation email is sent successfully!';
            $_POST = array();
        } else {
            $_SESSION['failure'] = 'Sending invitation email is failed!';
            $_POST = array();
        }
    }

//    $body = generateFriMessageBody($row, $friend_user, $friend_id);
//    $stat = sendEmail($to, $body);
//    if ($stat) {
//        $_SESSION['success'] = 'Invitation email is sent successfully!';
//        $_POST = array();
//    } else {
//        $_SESSION['failure'] = 'Sending invitation email is failed!';
//        $_POST = array();
//    }
}

// Get all saved family member lists
    $db = getDbInstance();
    $get_family_query = 'SELECT us.user_name, us.first_name, us.last_name, fa.relation 
                         FROM tbl_users us JOIN 
                         (SELECT with_who, who, relation  
                             FROM tbl_family WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa
                         ON us.id=fa.with_who OR us.id=fa.who WHERE us.id!='.$logged_id;
    $family_lists = $db->rawQuery($get_family_query);

// Get all saved friend lists
    $db = getDbInstance();
    $get_friend_query = 'SELECT us.user_name, us.first_name, us.last_name
                         FROM tbl_users us JOIN
                         (SELECT with_who, who  
                             FROM tbl_friend WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa
                         ON us.id=fa.with_who OR us.id=fa.who WHERE us.id!='.$logged_id;
    $friend_lists = $db->rawQuery($get_friend_query);

// Get all users for auto fill box
    $db = getDbInstance();
    //$get_family_query = 'SELECT DISTINCT us.user_name,us.id FROM tbl_users us JOIN (SELECT DISTINCT with_who, who  FROM tbl_family WHERE (who='.$logged_id.' OR with_who='.$logged_id.') AND stat=1) fa ON us.id=fa.with_who OR us.id=fa.who WHERE us.id != '.$logged_id;
    $get_family_query = 'SELECT user_name from tbl_users';
    $members = $db->rawQuery($get_family_query);
    $users = [];
    foreach($members as $member):
        array_push($users, $member['user_name']);
    endforeach;
