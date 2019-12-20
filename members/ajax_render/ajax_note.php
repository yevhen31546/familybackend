<?php
session_start();
require_once '../../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
if(isset($_POST) && isset($_POST['cat_id']) && $_POST['cat_id']) {
    $db = getDbInstance();
    $log_user_id = $_SESSION['user_id'];
    $data_to_db = array();
    $data_to_db['cat_id'] = $_POST['cat_id'];
    $data_to_db['note_date'] = $_POST['note_date'];
    $data_to_db['note_media'] = $_POST['note_media'];
    $data_to_db['user_id'] = $log_user_id;
    $last_id = $db->insert('tbl_notes', $data_to_db);

    if ($last_id)
    {
        $db = getDbInstance();
        $db->where('id', $_POST['cat_id']);
        $category = $db->getOne('tbl_categories');
        $db->where('id', $log_user_id);
        $user = $db->getOne('tbl_users');
        $return_array = array("note_date" => $_POST['note_date'],"category" => $category['cat_name'],"user_name" => $user['first_name'].$user['last_name'], "message" => "true");
        echo json_encode($return_array);
    }
    else
    {
        $return_array = array("user_id" => $log_user_id, "message" => "false");
        echo json_encode($return_array);
    }
}
?>