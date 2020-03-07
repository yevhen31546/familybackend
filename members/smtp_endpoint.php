<?php

function sendEmail($to, $body) {

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

// Generate family message body
function generateFamMessageBody($user, $content, $relation, $family_id) {
    $receiver_name = $content['first_name']." ".$content['last_name'];
    $family_relation = $relation;
    $sender = $user['first_name']." ".$user['last_name'];
    $approve_url = SMTP_ENDPOINT."?type=family&&from=".$user['id']."&&to=".$content['id']."&&relation=".$family_relation."&&family_id=".$family_id."&&status=approved";
    $delete_url = SMTP_ENDPOINT."?type=family&&from=".$user['id']."&&to=".$content['id']."&&relation=".$family_relation."&&family_id=".$family_id."&&status=delete";

    $message = "";
    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Hello ".$receiver_name."!</p><p>Invite family member request with ".$family_relation." is arrived from ".$sender."</p><p><span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $message;
}

// Generate friend message body
function generateFriMessageBody($user, $content, $friend_id) {
    $approve_url = SMTP_ENDPOINT."?type=friend&&from=".$user['id']."&&to=".$content['id']."&&friend_id=".$friend_id."&&status=approved";
    $delete_url = SMTP_ENDPOINT."?type=friend&&from=".$user['id']."&&to=".$content['id']."&&friend_id=".$friend_id."&&status=delete";
    $message = "";

    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Hello ".$content['first_name']." ".$content['last_name']."!</p><p>Invite friend request is arrived from ".$user['first_name']." ".$user['last_name']."</p><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $message;
}

// Generate Approve family message body
function generateApprovedFamMessageBody($sender, $receiver, $family_relation) {
    $receivername = $receiver['first_name']." ".$receiver['last_name'];
    $sendername = $sender['first_name']." ".$sender['last_name'];

    $message = "";
    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Hello ".$sendername."! Congratulations</p><p>".$receivername." was approved your family request as ".$family_relation."</p><p><a href='".SMTP_APPROVED_URL."'>View</a> </p></body></html>";
    return $message;
}

// Generate Approve friend message body
function generateApprovedFriMessageBody($sender, $receiver) {
    $receivername = $receiver['first_name']." ".$receiver['last_name'];
    $sendername = $sender['first_name']." ".$sender['last_name'];

    $message = "";
    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Hello ".$sendername."! Congratulations</p><p>".$receivername.". was approved your friend request</p><p><a href='".SMTP_APPROVED_URL."'>View</a> </p></body></html>";
    return $message;
}

// Generate Decline family message body
function generateDeleteFamMessageBody($sender, $receiver, $family_relation) {
    $receivername = $receiver['first_name']." ".$receiver['last_name'];
    $sendername = $sender['first_name']." ".$sender['last_name'];

    $message = "";
    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Hello ".$sendername."!</p><p>Your family request as ".$family_relation." was decliend from ".$receivername."</p></body></html>";
    return $message;
}

// Generate Decline friend message body
function generateDeleteFriMessageBody($sender, $receiver) {
    $receivername = $receiver['first_name']." ".$receiver['last_name'];
    $sendername = $sender['first_name']." ".$sender['last_name'];

    $message = "";
    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Hello ".$sendername."!</p><p>Your friend request was decliend from ".$receivername."</p></body></html>";
    return $message;
}

