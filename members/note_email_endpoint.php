<?php

function generateEmailBody($sender, $receiver, $note_id) {
    $msg = '';
    $receiver_name = $receiver['user_name'];
    $sender_name = $sender['user_name'];

    $approve_url = BASE_URL."/members/activity-me.php?from=".$sender['id']."&&note_to=".$receiver['id']."&&note_id=".$note_id."&&stat=approved";
    $delete_url = BASE_URL."/members/activity-me.php?from=".$sender['id']."&&note_to=".$receiver['id']."&&note_id=".$note_id."&&stat=delete";

    $msg .="<html><head><title></title></head><body><p>Hi ".$receiver_name."!</p><p>".$sender_name." has posted something on your profile</p><p><span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $msg;
}

function sendAddNoteEmail($data) {

    $who = $data['who'];
    $to_who = $data['to_who'];
    $note_id = $data['note_id'];

//    Get sender and receiver
    $sender = array();
    $receiver = array();
    $db = getDbInstance();
    $query = 'SELECT *
                FROM tbl_users
                WHERE id='.$who.' OR id='.$to_who;
    $users = $db->rawQuery($query);

    foreach ($users as $user):
        if($user['id'] == $who) {
            $sender = $user;
        } else {
            $receiver = $user;
        }
    endforeach;

    $body = generateEmailBody($sender, $receiver, $note_id);
    $to = $receiver['user_email'];

    // Create the Transport
    $transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT, SMTP_ENC))
        ->setUsername(SMTP_FROM)
        ->setPassword(SMTP_PASS)
    ;

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

// Create a message
    $message = (new Swift_Message('Invitation from MyNotes4U!'))
        ->setFrom([SMTP_FROM => 'MyNotes4U'])
        ->setTo([$to => $to])
        ->setContentType("text/html")
        ->setBody($body)
    ;

// Send the message
    $result = $mailer->send($message);
    return $result;
}