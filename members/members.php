<?php
session_start();
require_once '../config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once '../vendor/autoload.php';
require_once 'smtp_endpoint.php';
require_once 'notification.php';
require_once 'members_endpoint.php';

$bell_count += checkFamilyRequest($logged_id);
$bell_count += checkFriendRequest($logged_id);

include BASE_PATH.'/members/includes/header.php';

?>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/members/css/auto_fill.css">
        <!-- Page Header Start -->
        <div class="page--header pt--60 pb--60 text-center" data-bg-img="img/page-header-img/members2.png" data-overlay="0.35">
            <div class="container">
                <div class="title">
                    <h2 class="h1 text-white">Members</h2>
                </div>

                <ul class="breadcrumb text-gray ff--primary">
                    <li><a href="../members/home.php" class="btn-link">Home</a></li>
                    <li class="active"><span class="text-primary">Members</span></li>
                </ul>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Page Wrapper Start -->
        <section class="page--wrapper pt--80 pb--20">
            <div class="container">
                <div class="row">
                    <!-- Main Content Start -->
                    <div class="main--content col-md-9 pb--60" data-trigger="stickyScroll">
                        <div class="main--content-inner">
                            <!-- Filter Nav Start -->
                            <div class="filter--nav pb--30 clearfix">
                                <div class="filter--link float--left">
                                    <h2 class="h4">
                                        Total My Notes Members: &nbsp;&nbsp; <?php echo $total; ?>
                                    </h2>
                                </div>

                                <div class="filter--options float--right">
                                    <label>
                                        <span class="fs--14 ff--primary fw--500 text-darker">Show By :</span>

                                        <select name="membersfilter" class="form-control form-sm" data-trigger="selectmenu">
                                            <option value="last-active" selected>Last Active</option>
                                            <option value="new-registered">New Registerd</option>
                                            <option value="alphabetical">Alphabetical</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- Filter Nav End -->

                            <!-- Member Items Start -->
                            <div class="member--items">
                                <div class="row gutter--15 AdjustRow">
                                    <?php foreach ($rows as $row):?>
                                        <div class="col-md-4 col-xs-6 col-xxs-12">
                                            <!-- Member Item Start -->
                                            <div class="member--item online">
                                                <div class="img img-circle">
                                                    <a href="member-activity-personal.php?user=<?php echo $row['id'];?>" class="btn-link">
                                                        <?php if(isset($row['avatar'])) { ?>
                                                            <img src="<?php echo substr($row['avatar'], 2) ?>" alt="">
                                                        <?php } else { ?>
                                                            <img src="img/members-img/member-01.jpg" alt="">
                                                        <?php } ?>
                                                    </a>
                                                </div>

                                                <div class="name">
                                                    <h3 class="h6 fs--12">
                                                        <a href="member-activity-personal.php?user=<?php echo $row['id'];?>" class="btn-link"><?php echo $row['first_name'].$row['last_name'];?></a>
                                                    </h3>
                                                </div>



                                                <div class="actions">
                                                    <ul class="nav">
                                                        <li>
                                                            <a href="mailto:<?php echo $row['user_email']; ?>" title="Send Message" class="btn-link" data-toggle="tooltip" data-placement="bottom">
                                                                <i class="fa fa-envelope-o"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add as Friend" class="btn-link" data-toggle="tooltip" data-placement="bottom">
                                                                <i class="fa fa-user-plus"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add as Family" class="btn-link" data-toggle="tooltip" data-placement="bottom">
                                                                <i class="fa fa-group"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- Member Item End -->
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <!-- Member Items End -->

                            <!-- Page Count Start -->
                            <div class="page--count pt--30">
                                <form method="get">
                                <label class="ff--primary fs--14 fw--500 text-darker">
                                    <span>Viewing</span>

                                    <a href="<?php echo BASE_URL.'/members/members.php?page_num='.$prev_page; ?>"
                                       class="btn-link"><i class="fa fa-caret-left"></i></a>
                                    <input type="number" name="page_num" value="<?php echo $page; ?>"
                                           class="form-control form-sm">
                                    <a href="<?php echo BASE_URL.'/members/members.php?page_num='.$next_page; ?>"
                                       class="btn-link"><i class="fa fa-caret-right"></i></a>

                                    <span>of <?php echo $pages; ?></span>
                                </label>
                                </form>
                            </div>
                            <!-- Page Count End -->
                        </div>
                    </div>
                    <!-- Main Content End -->

                    <!-- Main Sidebar Start -->
                    <div class="main--sidebar col-md-3 pb--60" data-trigger="stickyScroll">
                        <!-- Widget Start -->
                        <div class="widget">
                            <h2 class="h4 fw--600 widget--title">Invite a Family Member</h2>
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


                        <div class="widget">
                            <form action="" method="post" onsubmit="return checkFriendForm(this);">
                                <h2 class="h4 fw--600 widget--title">Invite a Friend</h2>
                                <div class="autocomplete" style="width: 100%;">
                                    <input id="myfriend" type="text" class="form-control" name="myfriend" placeholder="Friend's Name">
                                </div>
                                <button type="submit" class="btn btn-sm btn-google btn btn-primary" style="margin-top: 20px;"><i class="fa mr--8 fa-play"></i>Send</button>
                            </form>
                        </div>

                        <!-- Widget End -->
                    </div>
                    <!-- Main Sidebar End -->

                </div>
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

    var families = <?php print_r(json_encode($users)); ?>;
    var friends = <?php print_r(json_encode($users)); ?>;
    console.log("user lists: ", families);
    console.log("user lists: ", friends);

    autocomplete(document.getElementById("myfamily"), families);
    autocomplete(document.getElementById("myfriend"), friends);
</script>

<?php include BASE_PATH.'/members/includes/footer.php'?>