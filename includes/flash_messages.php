<?php
if(isset($_SESSION['success']))
{

echo '<div class="alert alert-success alert-dismissable">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    		<strong>Success! </strong>'. $_SESSION['success'].'
  	  </div>';
  unset($_SESSION['success']);
}

if(isset($_SESSION['failure']))
{
echo '<div class="alert alert-danger alert-dismissable">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    		<strong>Oops! </strong>'. $_SESSION['failure'].'
  	  </div>';
  unset($_SESSION['failure']);
}

if(isset($_SESSION['info']))
{
echo '<div class="alert alert-info alert-dismissable">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    		'. $_SESSION['info'].'
  	  </div>';
  unset($_SESSION['info']);
}

if(isset($_SESSION['profile_update_failure']))
{
    echo '<div class="alert alert-info alert-dismissable">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    		'. $_SESSION['profile_update_failure'].'
  	  </div>';
    unset($_SESSION['profile_update_failure']);
}




// Friend/Family request notification message
if(isset($_SESSION['friend_request_msg']))
{
    echo $_SESSION['friend_request_msg'];
    unset($_SESSION['friend_request_msg']);
}

if(isset($_SESSION['family_request_msg']))
{
    echo $_SESSION['family_request_msg'];
    unset($_SESSION['family_request_msg']);
}

// Note request message
if(isset($_SESSION['note_request_msg']))
{
    echo $_SESSION['note_request_msg'];
    unset($_SESSION['note_request_msg']);
}


// Family group request message
if(isset($_SESSION['fam_group_requests_msg']))
{
    echo $_SESSION['fam_group_requests_msg'];
    unset($_SESSION['fam_group_requests_msg']);
}


// Friend group request message
if(isset($_SESSION['fri_group_requests_msg']))
{
    echo $_SESSION['fri_group_requests_msg'];
    unset($_SESSION['fri_group_requests_msg']);
}


// Family note request message
if(isset($_SESSION['fam_note_request_msg']))
{
    echo $_SESSION['fam_note_request_msg'];
    unset($_SESSION['fam_note_request_msg']);
}

// Friend note request message
if(isset($_SESSION['fri_group_note_request_msg']))
{
    echo $_SESSION['fri_group_note_request_msg'];
    unset($_SESSION['fri_group_note_request_msg']);
}

// Family group note request message
if(isset($_SESSION['fam_group_note_request_msg']))
{
    echo $_SESSION['fam_group_note_request_msg'];
    unset($_SESSION['fam_group_note_request_msg']);
}

// Friend group note request message
if(isset($_SESSION['fri_note_request_msg']))
{
    echo $_SESSION['fri_note_request_msg'];
    unset($_SESSION['fri_note_request_msg']);
}