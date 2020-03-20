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
    $message = (new Swift_Message('MyNotes4U!'))
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
    $message .="<html><head><title></title></head>
                <body>
                <img src='".BASE_URL.LOGO_URL."'>
                <p>
                    Hello ".$receiver_name.",
                </p>
                <p>
                    You have been invited to become part of ".$sender." MyNotes4u Family Album.
                </p>
                <p>
                    <span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><a href=".$delete_url.">Delete</a></span>
                </p>
                </body>
                </html>";
    return $message;
}

// Generate friend message body
function generateFriMessageBody($user, $content, $friend_id) {
    $approve_url = SMTP_ENDPOINT."?type=friend&&from=".$user['id']."&&to=".$content['id']."&&friend_id=".$friend_id."&&status=approved";
    $delete_url = SMTP_ENDPOINT."?type=friend&&from=".$user['id']."&&to=".$content['id']."&&friend_id=".$friend_id."&&status=delete";
    $message = "";

    $message .="<html><head><title></title></head>
                <body>
                    <img src='".BASE_URL.LOGO_URL."'>
                    <p>
                        Hello ".$content['first_name']." ".$content['last_name']."!
                    </p>
                    <p>
                        You have been invited to become part of ".$user['first_name']." ".$user['last_name']." MyNotes4u Friend Album.
                    </p>
                    <p>
                        <span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span><a href=".$delete_url.">Delete</a></span>
                    </p>
                </body>
                </html>";
    return $message;
}

// Generate Approve family message body
function generateApprovedFamMessageBody($sender, $receiver, $family_relation) {
    $receivername = $receiver['first_name']." ".$receiver['last_name'];
    $sendername = $sender['first_name']." ".$sender['last_name'];

    $message = "";
    $message .="<html><head><title></title></head>
                <body>
                    <img src='".BASE_URL.LOGO_URL."'>
                    <p>
                        ".$receivername." approved your MyNotes4u Family Album
                    </p>
                </body>
                </html>";
    return $message;
}

// Generate Approve friend message body
function generateApprovedFriMessageBody($sender, $receiver) {
    $receivername = $receiver['first_name']." ".$receiver['last_name'];
    $sendername = $sender['first_name']." ".$sender['last_name'];

    $message = "";
    $message .="<html><head><title></title></head>
                <body>
                    <img src='".BASE_URL.LOGO_URL."'>
                    <p>".$receivername." approved your MyNotes4u Friend Album</p>
                </body>
                </html>";
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

// generate invitation message body for family group
function genFamGroupMsgBody($from, $group_name, $group_id, $fam_group_members_id) {
    $who = $from['first_name']." ".$from['last_name'];
    $approve_url = BASE_URL."/members/activity-fam.php?group_id=".$group_id."&&member_id=".$fam_group_members_id."&&stat=approved";
    $delete_url = BASE_URL."/members/activity-fam.php?group_id=".$group_id."&&member_id=".$fam_group_members_id."&&stat=delete";

    $message = "";

    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Invitation is arrived from ".$group_name." that created by ".$who."</p><p><span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $message;
}

// generate invitation message body for friend group
function genFriGroupMsgBody($from, $group_name, $group_id, $fri_group_members_id) {
    $who = $from['first_name']." ".$from['last_name'];
    $approve_url = BASE_URL."/members/activity-frd.php?group_id=".$group_id."&&member_id=".$fri_group_members_id."&&stat=approved";
    $delete_url = BASE_URL."/members/activity-frd.php?group_id=".$group_id."&&member_id=".$fri_group_members_id."&&stat=delete";

    $message = "";

    $message .="<html><head><title></title></head><body><img src='".BASE_URL.LOGO_URL."'><p>Invitation is arrived from ".$group_name." that created by ".$who."</p><p><span><a href=".$approve_url.">Approve</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href=".$delete_url.">Delete</a></span></p></body></html>";
    return $message;
}

// contact form message body
function genContactFormMsgBody($email, $subject, $name, $msg) {
    $message = "";

    $message .="<div style='display:grid; justify-content: center'>
                    <img src='".BASE_URL.LOGO_URL."'>
                    <p>Form details below.</p>
                    <br>
                    <p>Name:&nbsp;&nbsp;$name</p>
                    <p>Email:&nbsp;&nbsp;$email</p>
                    <p>Subject:&nbsp;&nbsp;$subject</p>
                    <p>Message:&nbsp;&nbsp;$msg</p>
                </div>";
    return $message;
}

// Generate invitation message body for outside friend
function generateInviteFriMessageBody($user, $receiver_name, $ref_code) {
    $join_url = BASE_URL."/register.php?ref=".$ref_code;
    $sender_name = $user['first_name']." ".$user['last_name'];
    $message = "";

    $message .="<html><head><title></title></head>
                <body>
                    <img src='".BASE_URL.LOGO_URL."'>
                    <p>Hello ".$receiver_name.",</p>
                    <p>
                        ".$sender_name." has invited you to join MyNotes4U.
                        An exciting new way for family end friends to 'do life' together.
                        Share comments, pictures and videos free from all of today's social media worries.
                        No more selling of your information or tracking of your online habits.
                    </p>
                    <p>
                        Try it out for free at <a href=".BASE_URL.">MyNotes4U.com</a>.
                        Your first 30 days are on us.
                    </p>
                    <p>
                        MyNotes4U is a secure, non-intrusive, exclusive meeting place for you and your circle of family
                        and friends. But that's not all. It's also a place where you can build and
                        store your life's personal treasures. Are you an artist, a musician, a writer, a builder,
                        a sport enthusiast, an athlete, etc? If so, MyNotes4U is an easy and exciting way to 
                        keep your lifetime of treasures in one secure and organized location. 
                    </p>
                    <p>
                        Becoming a member of MyNotes4U gives you:
                        <ul>
                            <li>
                                Three private exclusive albums.
                                <ul>
                                    <li>
                                        My Album - Your own private space. A place where you can write and store 
                                        your memoirs to be discovered by future generations - your very own time 
                                        capsule.
                                    </li>
                                    <li>
                                        My Family Album - A place to 'do life' exclusively with your family. You 
                                        choose who can be a part of your album.
                                    </li>
                                    <li>
                                        My Friends Album - A private place to hang-out with your close friends, share
                                        special occasions, challenges, adventures, feelings and thoughts.
                                    </li>
                                </ul>
                            </li>
                            <li>
                                Meet other members of MyNotes4U by becoming a part of group 
                                where you can share recipes, church gatherings, home remodeling or 
                                repair ideas, sporting events and more.
                            </li>
                        </ul>
                    </p>
                    <p>
                        Perhaps the most intriguing part of MyNotes4U is that it 
                        doesn't have to end. When you store your memories with us, 
                        we will preserve them for your future generations. Now you 
                        can have a voice into the lives of love ones you haven't met. 
                        your great, great, great grandchildren can know you intimately. 
                        Discover who you are. That's a game changer.
                    </p>
                    <p>
                        Try it out for free at <a href=".BASE_URL.">MyNotes4U.com</a>.
                        Your first 30 days are on us.
                    </p>
                </body>
                </html>";
    return $message;
}

// Generate invitation message body for outside family
function generateInviteFamMessageBody($user, $receiver_name, $ref_code, $relation) {
    $join_url = BASE_URL."/register.php?ref=".$ref_code;
    $sender_name = $user['first_name']." ".$user['last_name'];
    $message = "";

//    $message .="<html><head><title></title></head>
//                <body>
//                    <img src='".BASE_URL.LOGO_URL."'>
//                    <p>Hello!</p>
//                    <p>You have been added as a family by ".$user['first_name']." ".$user['last_name']." on MyNotes4U.</p>
//                    <p>Click the button below to join</p>
//                    <p><a href=".$join_url.">Join MyNotes4U</a></p>
//                </body>
//                </html>";

    $message .="<html><head><title></title></head>
                <body>
                    <img src='".BASE_URL.LOGO_URL."'>
                    <p>Hello ".$receiver_name.",</p>
                    <p>
                        ".$sender_name." has invited you to join MyNotes4U.
                        An exciting new way for family end friends to 'do life' together.
                        Share comments, pictures and videos free from all of today's social media worries.
                        No more selling of your information or tracking of your online habits.
                    </p>
                    <p>
                        Try it out for free at <a href=".BASE_URL.">MyNotes4U.com</a>.
                        Your first 30 days are on us.
                    </p>
                    <p>
                        MyNotes4U is a secure, non-intrusive, exclusive meeting place for you and your circle of family
                        and friends. But that's not all. It's also a place where you can build and
                        store your life's personal treasures. Are you an artist, a musician, a writer, a builder,
                        a sport enthusiast, an athlete, etc? If so, MyNotes4U is an easy and exciting way to 
                        keep your lifetime of treasures in one secure and organized location. 
                    </p>
                    <p>
                        Becoming a member of MyNotes4U gives you:
                        <ul>
                            <li>
                                Three private exclusive albums.
                                <ul>
                                    <li>
                                        My Album - Your own private space. A place where you can write and store 
                                        your memoirs to be discovered by future generations - your very own time 
                                        capsule.
                                    </li>
                                    <li>
                                        My Family Album - A place to 'do life' exclusively with your family. You 
                                        choose who can be a part of your album.
                                    </li>
                                    <li>
                                        My Friends Album - A private place to hang-out with your close friends, share
                                        special occasions, challenges, adventures, feelings and thoughts.
                                    </li>
                                </ul>
                            </li>
                            <li>
                                Meet other members of MyNotes4U by becoming a part of group 
                                where you can share recipes, church gatherings, home remodeling or 
                                repair ideas, sporting events and more.
                            </li>
                        </ul>
                    </p>
                    <p>
                        Perhaps the most intriguing part of MyNotes4U is that it 
                        doesn't have to end. When you store your memories with us, 
                        we will preserve them for your future generations. Now you 
                        can have a voice into the lives of love ones you haven't met. 
                        your great, great, great grandchildren can know you intimately. 
                        Discover who you are. That's a game changer.
                    </p>
                    <p>
                        Try it out for free at <a href=".BASE_URL.">MyNotes4U.com</a>.
                        Your first 30 days are on us.
                    </p>
                </body>
                </html>";
    return $message;
}


// Generate family message body
function generateForgotPassMsgBody($token) {
    $reset_url = BASE_URL.'/reset_password.php?token='.$token;

    $message = "";
    $message .="<html><head><title></title></head>
                <body>
                <img src='".BASE_URL.LOGO_URL."'>
                <p>
                    We heard that you lost your MyNotes4u.com password. Sorry about that!
                </p>
                <p>
                    But don't worry! You can use the following link to reset your password:
                </p>
                <p>
                    <span><a href=".$reset_url.">Link to reset password</a></span>
                </p>
                <p>
                    If you don't use this link within 3 hours, it will expire.
                </p>
                </body>
                </html>";
    return $message;
}
