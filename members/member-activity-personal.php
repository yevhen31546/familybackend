<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once '../vendor/autoload.php';
require_once 'smtp_endpoint.php';
require_once 'notification.php';
require_once 'member-activity-endpoint.php';

$bell_count += checkFamilyRequest($logged_id);
$bell_count += checkFriendRequest($logged_id);

$db = getDbInstance();
$db->join('tbl_notes', 'tbl_notes.user_id = tbl_users.id')->join('tbl_categories','tbl_notes.cat_id = tbl_categories.id');
$db->where('tbl_users.id', $_GET['user']);
$db->orderBy('tbl_notes.note_date');
$rows = $db->get('tbl_users'); // saved note lists

$userdb = getDbInstance();
$userdb->where('id', $_GET['user']);
$userrow = $userdb->getOne('tbl_users');

include BASE_PATH.'/members/includes/header.php';

?>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/auto_fill.css">

        <!-- Cover Header Start -->
        <?php if(isset($userrow['cover_photo'])) { ?>
            <div class="cover--header pt--80 text-center" data-bg-img="<?php echo substr($userrow['cover_photo'],2) ?>" data-overlay="0.6" data-overlay-color="white">
        <?php } else { ?>
            <div class="cover--header pt--80 text-center" data-bg-img="img/cover-header-img/bg-01.jpg" data-overlay="0.6" data-overlay-color="white">
        <?php } ?>
            <div class="container">
                <div class="cover--avatar online" data-overlay="0.3" data-overlay-color="primary">
                    <?php if(isset($userrow['avatar'])) { ?>
                        <img src="<?php echo substr($userrow['avatar'],2) ?>" alt="">
                    <?php } else { ?>
                        <img src="img/cover-header-img/avatar-01.jpg" alt="">
                    <?php } ?>
                </div>

                <div class="cover--user-name">
                    <h2 class="h3 fw--600"><?php echo $userrow['first_name'].' '. $userrow['last_name'];?></h2>
                </div>

<!--                <div class="cover--user-activity">-->
<!--                    <p><i class="fa mr--8 fa-clock-o"></i>Active 1 year 9 monts ago</p>-->
<!--                </div>-->

                <div class="cover--user-desc fw--400 fs--18 fstyle--i text-darkest">
                    <p>Hello everyone ! There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                </div>
            </div>
        </div>
        <!-- Cover Header End -->

        <!-- Page Wrapper Start -->
        <section class="page--wrapper pt--80 pb--20">
            <div class="container">
                <div class="row">
                    <!-- Main Content Start -->
                    <div class="main--content col-md-8 pb--60" data-trigger="stickyScroll">
                        <div class="main--content-inner drop--shadow">
                            <!-- Content Nav Start -->
                            <div class="content--nav pb--30">
                                <ul class="nav ff--primary fs--14 fw--500 bg-lighter">
                                    <li class="active"><a href="member-activity-personal.php">Activity</a></li>
                                    <li><a href="member-profile.php">Profile</a></li>
                                    <li><a href="member-friends.html">Friends</a></li>
                                    <li><a href="member-groups.html">Groups</a></li>
                                </ul>
                            </div>
                            <!-- Content Nav End -->

                            <!-- Filter Nav Start -->
                            <div class="filter--nav pb--60 clearfix">
                                <div class="filter--options float--right">
                                    <label>
                                        <span class="fs--14 ff--primary fw--500 text-darker">Show By :</span>

                                        <select name="activityfilter" class="form-control form-sm" data-trigger="selectmenu">
                                            <option value="updates" selected>Updates</option>
                                            <option value="friendships">Friendships</option>
                                            <option value="group-updates">Group Updats</option>
                                            <option value="membership">Membership</option>
                                            <option value="topics">Topics</option>
                                            <option value="replies">Replies</option>
                                            <option value="posts">Posts</option>
                                            <option value="comments">Comments</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- Filter Nav End -->

                            <!-- Activity List Start -->
                            <div class="activity--list">
                                <!-- Activity Items Start -->
                                <ul class="activity--items nav">
                                    <?php foreach ($rows as $row):?>
                                        <li>
                                            <!-- Activity Item Start -->
                                            <div class="activity--item">
                                                <div class="activity--avatar">
                                                    <a href="<?php echo BASE_URL . '/members/member-activity-personal.php'; ?>">
                                                        <?php if (isset($row['avatar'])) { ?>
                                                            <img src="<?php echo substr($row['avatar'], 2) ?>" alt="">
                                                        <?php } else { ?>
                                                            <img src="img/activity-img/avatar-01.jpg" alt="">
                                                        <?php } ?>
                                                    </a>
                                                </div>

                                                <div class="activity--info fs--14">
                                                    <div class="activity--header">
                                                        <p><a href="member-activity-personal.php"><?php echo $row['first_name'].$row['last_name']?></a> posted an <?php echo $row['note_media'];?> on <?php echo $row['cat_name']?></p>
                                                    </div>

                                                    <div class="activity--meta fs--12">
                                                        <p><i class="fa mr--8 fa-clock-o"></i><?php echo $row['note_date']?></p>
                                                    </div>

                                                    <div class="activity--content">
                                                        <?php if ($row['note_media'] == 'text'):?>
                                                            <p><?php echo $row['note_value']?></p>
                                                        <?php elseif ($row['note_media'] == 'photo'):?>
                                                            <img src="<?php echo $row['note_value']; ?>">
                                                        <?php elseif ($row['note_media'] == 'video'):?>
<!--                                                            <iframe width="100%" height="100%"-->
<!--                                                                    src="--><?php //echo $row['note_value']?><!--">-->
<!--                                                            </iframe>-->
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
                                    <?php endforeach;?>
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
                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Invite a Family Member</h2>
                            <form action="" autocomplete="off" method="post" onsubmit="return checkFamilyForm(this);">
                                <div class="autocomplete" style="width: 100%;">
                                    <input id="myfamily" type="text" class="form-control" name="myfamily" placeholder="Family member's Name">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <select name="family_member" class="form-control form-sm">
                                        <option value="family_member">*Select Family Relationship</option>
                                        <option value="Husband">Husband</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Significant Other">Significant Other</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Father">Father</option>
                                        <option value="Sister">Sister</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Aunt">Aunt</option>
                                        <option value="Uncle">Uncle</option>
                                        <option value="Niece">Niece</option>
                                        <option value="Nephew">Nephew</option>
                                        <option value="Cousin">Cousin</option>
                                        <option value="Grandmother">Grandmother</option>
                                        <option value="Grandfather">Grandfather</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-google btn btn-primary"><i class="fa mr--8 fa-play"></i>Send</button>
                            </form>
                        </div>
                        <!-- Widget End -->

                        <!-- Widget Start -->
                        <div class="widget">
                            <form action="" method="post" onsubmit="return checkFriendForm(this);">
                                <h2 class="h4 fw--700 widget--title">Invite a Friend</h2>
                                <div class="autocomplete" style="width: 100%;">
                                    <input id="myfriend" type="text" class="form-control" name="myfriend" placeholder="Friend's Name">
                                </div>
                                <button type="submit" class="btn btn-sm btn-google btn btn-primary" style="margin-top: 20px;"><i class="fa mr--8 fa-play"></i>Send</button>
                            </form>
                        </div>
                        <!-- Widget End -->

                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--700 widget--title">Advertisements</h2>
s                            <!-- Ad Widget Start -->
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
            </div>
        </section>
        <!-- Page Wrapper End -->

<script>
    function checkFamilyForm(form) {
        var families = <?php print_r(json_encode($users)); ?>;

        if(form.myfamily.value === '') {
            alert("Error: Please type family member's name :(");
            form.myfamily.focus();
            return false;
        } else if (families.indexOf(form.myfamily.value) === -1) {
            form.myfamily.focus();
            alert("Error: Selected profile is invalid!");
            return false;
        }

        if(form.family_member.value === "family_member") {
            alert("Error: Please select family relationship!");
            form.family_member.focus();
            return false;
        }

        return true;
    }

    function checkFriendForm(form) {
        var friends = <?php print_r(json_encode($users)); ?>;

        if(form.myfriend.value === "") {
            alert("Error: Please type friend's name :(");
            form.myfriend.focus();
            return false;
        } else if (friends.indexOf(form.myfriend.value) === -1) {
            form.myfriend.focus();
            alert("Error: Selected profile is invalid!");
            return false;
        }

        return true;
    }

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

    var families = <?php print_r(json_encode($users)); ?>;
    var friends = <?php print_r(json_encode($users)); ?>;
    console.log("user lists: ", families);
    console.log("user lists: ", friends);

    autocomplete(document.getElementById("myfamily"), families);
    autocomplete(document.getElementById("myfriend"), friends);

</script>

<?php include BASE_PATH.'/members/includes/footer.php'?>