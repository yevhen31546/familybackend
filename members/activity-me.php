<?php
session_start();
require_once '../config/config.php';
require_once '../vendor/autoload.php';
require_once BASE_PATH.'/includes/auth_validate.php';

require_once 'note_email_endpoint.php';
require_once 'my_album_endpoint.php';
require_once 'notification.php';

// Check posted note exist
checkNoteRequest($logged_id); // $_SESSION['note_request'] = 1
if($_SESSION['note_request']) {
    $db = getDbInstance();
    $query = 'SELECT users.*, notes.id AS note_id, notes.user_id, notes.note_to, notes.status
              FROM tbl_users AS users JOIN
              (SELECT * FROM tbl_notes WHERE note_to = '.$logged_id.' AND STATUS = 0) AS notes
              ON users.id = notes.user_id';
    $note_requests = $db->rawQuery($query);

    $notification_msg = genNotePostNotMsg($note_requests);
    $_SESSION['note_request_msg'] = $notification_msg;
}

include BASE_PATH.'/members/includes/header.php';
?>

<link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/auto_fill.css">
<!-- Page Header Start -->
<div class="page--header pt--60 pb--60 text-center" data-bg-img="img/myalbum6.png"
    data-overlay="0.35">
    <div class="container">
        <div class="title">
            <h2 class="h1 text-white">My Album</h2>
        </div>

        <ul class="breadcrumb text-gray ff--primary">
            <li><a href="../members/home.php" class="btn-link">Home</a></li>
            <li class="active"><span class="text-primary">My Album</span></li>
        </ul>
    </div>
</div>
<!-- Page Header End -->

<!-- Page Wrapper Start -->
<section class="page--wrapper pt--80 pb--20">
    <div class="container">
        <div class="row">

            <?php include BASE_PATH . '/includes/flash_messages.php'; ?>
            <!-- Main Content Start -->
            <div class="main--content col-md-8 pb--60" data-trigger="stickyScroll">
                <div class="main--content-inner drop--shadow">
                    <!-- Filter Nav Start -->
                    <div class="filter--nav pb--60 clearfix">
                        <div class="filter--link float--left">
                            <h2>Your Collection of Notes</h2>
                        </div>

                    </div>
                    <!-- Filter Nav End -->

                    <!-- Activity List Start -->
                    <div class="activity--list">
                        <!-- Activity Items Start -->
                        <ul class="activity--items nav"> 
                            <li>
                                <!-- Activity Item Start -->
                                <div class="activity--item">

                                    <div class="activity--info fs--14">

                                        <div class="activity--content">

                                        </div>
                                    </div>
                                </div>
                                <!-- Activity Item End -->
                            </li>
                            <?php foreach ($rows as $row):?>
                                <li>
                                    <!-- Activity Item Start -->
                                    <div class="activity--item">
                                        <div class="activity--avatar">
                                            <a href="<?php echo BASE_URL.'/members/member-activity-personal.php?user='.$row['user_id']; ?>" >
                                                <?php if(isset($row['avatar'])) { ?>
                                                    <img src="<?php echo substr($row['avatar'],2) ?>" alt="">
                                                <?php } else { ?>
                                                    <img src="img/activity-img/avatar-05.jpg" alt="">
                                                <?php } ?>
                                            </a>
                                        </div>

                                        <div class="activity--info fs--14">
                                            <div class="activity--header">
                                                <p><a href="member-activity-personal.php?user=<?php echo $_SESSION['user_id']?>"><?php echo $row['first_name'].$row['last_name']?></a> posted
                                                    an <?php echo $row['note_media'];?> on <?php echo $row['cat_name']?> </p>
                                            </div>

                                            <div class="activity--meta fs--12">
                                                <p><i class="fa mr--8 fa-clock-o"></i><?php echo $row['note_date']?></p>
                                            </div>

                                            <div class="activity--content">
                                                <?php if ($row['note_media'] == 'text'):?>
                                                    <p id="note_text_edit"><?php echo $row['note_value']?></p>
                                                    <input type="button" id="<?php echo $row['id'];?>_note_<?php echo $row['note_media'];?>" style="display: none;" class="btn btn-primary note_edit pull-right" value="Edit">
                                                <?php elseif ($row['note_media'] == 'photo'):?>
                                                    <img id="note_photo_edit" src="<?php echo $row['note_value']; ?>" style="padding-bottom: 10px;">
                                                    <input type="button" id="<?php echo $row['id'];?>_note_<?php echo $row['note_media'];?>" style="display: none;" class="btn btn-primary note_edit pull-right" value="Edit">
                                                <?php elseif ($row['note_media'] == 'video'):?>
                                                    <a class="link--url"
                                                       href="<?php echo $row['note_value']; ?>"
                                                       data-trigger="video_popup"></a>

                                                    <div class="link--video">
                                                        <img src="img/activity-img/link-video-poster.jpg" alt="">
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Activity Item End -->
                                </li>
                            <?php endforeach; ?>

                        </ul>
                        <!-- Activity Items End -->
                    </div>
                    <!-- Activity List End -->
                </div>

                <!-- Load More Button Start -->
                <div class="load-more--btn pt--30 text-center">
                    <a href="#" class="btn btn-animate">
                        <span>See More Activities<i class="fa ml--10 fa-caret-right"></i></span>
                    </a>
                </div>
                <!-- Load More Button End -->
            </div>
            <!-- Main Content End -->

            <!-- Main Sidebar Start -->
            <div class="main--sidebar col-md-4 pb--60" data-trigger="stickyScroll">
                <!-- Widget Add a Note Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Add a Note</h2>
                    <!-- Buddy Finder Widget Start -->
                    <div class="buddy-finder--widget">
                        <form id="add_note_form" action="" method="post">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                            <input type="date" name="note_add_date" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <select name="category" class="form-control form-sm category"
                                            data-trigger="selectmenu">
                                            <?php
                                            foreach ($category_lists as $category_list): ?>
                                                <option value="<?php echo $category_list['id']; ?>">
                                                    <?php echo $category_list['cat_name']; ?>
                                                </option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="multimedia" class="form-control form-sm multimedia"
                                                    data-trigger="selectmenu">
                                                <option value="addmedia">Add Comment, Photo or Video</option>
                                                <option value="text">Add Text</option>
                                                <option value="photo">Add a Photo</option>
                                                <option value="video">Add a Video Link</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="sel_profile">
                                            Choose a profile
                                        </label>
                                        <div class="autocomplete" style="width: 100%;">
                                            <input id="friendAndfamily" type="text" class="form-control" name="friendAndfamily" placeholder="Family or friend's name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary activity-note-add">Add</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                        </form>

                    </div>
                    <!-- Buddy Finder Widget End -->
                </div>
                <!-- Widget End -->

                <!-- Widget View Notes Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">View Notes</h2>

                    <!-- Text Widget Start -->
                    <div class="buddy-finder--widget">
                        <form action="" method="post" id="view_note_form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <?php if (isset($_POST['note_view_date'])) { ?>
                                        <input type="date" name="note_view_date"
                                               value="<?php echo $_POST['note_view_date'] ?>">
                                        <?php } else { ?>
                                        <input type="date" name="note_view_date"
                                               value="<?php echo date("Y-m-d") ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="view_category" class="form-control form-sm category"
                                                    data-trigger="selectmenu">
                                                <?php
                                                foreach ($category_lists as $category_list): ?>
                                                    <option value="<?php echo $category_list['id']; ?>"
                                                        <?php if(isset($_POST['view_category']) &&
                                                            ($_POST['view_category'] == $category_list['id']))
                                                            echo 'selected'; ?> >
                                                        <?php echo $category_list['cat_name']; ?>
                                                    </option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary view_note_submit">Search</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Text Widget End -->
                </div>
                <!-- Widget End -->

                <!-- Widget Update a Note Start -->
                <div class="widget">
                    <h2 class="h6 fw--700 widget--title">Update a Note</h2>
                    <!-- Text Widget Start -->
                    <div class="buddy-finder--widget">
                        <form action="" method="post" id="update_note_form">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <?php if (isset($_POST['note_update_date'])) { ?>
                                            <input type="date" name="note_update_date"
                                                   value="<?php echo $_POST['note_update_date'] ?>">
                                        <?php } else { ?>
                                            <input type="date" name="note_update_date"
                                                   value="<?php echo date("Y-m-d") ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>
                                            <select name="update_category" class="form-control form-sm"
                                                data-trigger="selectmenu">
                                                <?php
                                                foreach ($category_lists as $category_list): ?>
                                                    <option value="<?php echo $category_list['id']; ?>"
                                                        <?php if(isset($_POST['update_category']) &&
                                                            ($_POST['update_category'] == $category_list['id']))
                                                            echo 'selected'; ?> >
                                                        <?php echo $category_list['cat_name']; ?>
                                                    </option>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="text--widget">

                                </div>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary update_note_submit">Search</button>
                                    <button type="button" class="btn btn-primary cancel_button">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
                <!-- Widget End -->

                <div class="ad--widget">
                    <a href="#">
                        <img src="img/widgets-img/ad.jpg" alt="" class="center-block">
                    </a>
                </div>
                <!-- Ad Widget End -->
            </div>
            <!-- Widget End -->
        </div>
        <!-- Main Sidebar End -->
    </div>
    <?php include BASE_PATH . '/members/forms/note_add_modal.php';?>
</div>
</section>
<!-- Page Wrapper End -->

<script>
    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    var friendAndfamilies = <?php print_r(json_encode($friendAndfamilies)); ?>;
    console.log("families: ", friendAndfamilies);

    autocomplete(document.getElementById("friendAndfamily"), friendAndfamilies);
</script>

<?php include BASE_PATH.'/members/includes/footer.php'?>