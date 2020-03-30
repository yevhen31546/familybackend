<?php
session_start();
require_once 'config/config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));

// If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE)
{
    header('Location: members/home.php');
}

?>
<?php include BASE_PATH.'/includes/header.php'; ?>

        <!-- Page Header Start -->
        <div class="page--header pt--60 pb--60 text-center" data-bg-img="img/cartbanner5.png">
            <div class="container">
                <div class="title">
                    <h2 class="h1 text-white">Subscription</h2>
                </div>

                <ul class="breadcrumb text-gray ff--primary">
                    <li><a href="members/home-1.html" class="btn-link">Home</a></li>
                    <li class="active"><span class="text-primary">Subscriptions</span></li>
                </ul>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Page Wrapper Start -->
        <section class="page--wrapper pt--80 pb--20">
            <div class="container">
                <div class="row">
                    <!-- Main Content Start -->
                    <div class="main--content col-md-12">
                        <div class="main--content-inner">
                            <!-- Cart Items Start -->
                            <div class="cart--items pb--60">
                                <table class="table">
                                    <thead class="ff--primary fs--18 text-black bg-lighter">
                                        <tr>
                                            <th>PRICING</th>
                                            <th>Subscription Plans</th>
                                            <th>Monthly</th>
                                            <th>Annual</th>
                                            <th>Saving Calculations</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs--14 text-darkest">
                                        <tr>
                                            <td data-label="Subscription Plan">
                                                <img src="img/carta.png" alt="" data-overlay="0" data-overlay-color="white">
                                            </td>
                                            <td>
                                                <strong>Adult</strong> <br>(One person over 18)
                                            </td>
                                            <td data-label="Monthly">
                                                <p>$ 6.99</p>
                                            </td>
                                            <td data-label="Annual">
                                                <p>$74.99</p>
                                            </td>
                                            <td>
                                                10% savings when ordering an Annual Subscription
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-label="Subscription Plan">
                                                <img src="img/cartf.png" alt="" data-overlay="0" data-overlay-color="white">
                                            </td>
                                            <td data-label="">
                                               <strong>Family Membership</strong> <br> (Max. 5 people, immediate family only)
                                            </td>
                                            <td data-label="Monthly">
                                                <p>$ 24.99</p>
                                            </td>
                                            <td data-label="Annual">
                                                <p>$239.99</p>
                                            </td>

                                            <td>
                                                20% savings when ordering an Annual Subscription
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-label="Subscription Plan">
                                                <img src="img/cart2.png" alt="" data-overlay="0" data-overlay-color="white">
                                            </td>
                                            <td>
                                               <strong>Young Adult/Child</strong>  <br>(One person under 18)
                                            </td>
                                            <td data-label="Monthly Rate">
                                                <p>$  4.99</p>
                                            </td>
                                            <td data-label="Annual Rate">
                                                <p>$ 53.00</p>
                                            </td>
                                            <td>
                                                10% savings when ordering an Annual Subscription
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-label="Subscription Plan">
                                                <img src="img/cart1.png" alt="" data-overlay="0" data-overlay-color="white">
                                            </td>
                                            <td>
                                              <Strong>Senior</Strong><br> (One person over the age of 60)
                                            </td>
                                            <td data-label="Monthly">
                                                <p>$  3.99</p>
                                            </td>
                                            <td data-label="Annual">
                                                <p>$ 43.00</p>
                                            </td>
                                            <td>
                                                10% savings when ordering an Annual Subscription
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Cart Items End -->

                            <div class="row">
                                <div class="col-md-12 pb--60">
                                    <!-- Cart Coupon Start -->
                                    <div class="cart--coupon" data-form="validate">
                                        <div class="cart--title bg-lighter">
                                            <h3 class="h4"><strong>START CAPTURING YOUR MEMORIES TODAY!</strong></h3>
                                        </div>

                                        <!--Paypal Test-->
                                        <form action="<?php echo PAYPAL_URL; ?>" method="post" target="_top">
                                            <!-- Identify your business so that you can collect the payments -->
<!--                                            <input type="hidden" name="business" value="--><?php //echo PAYPAL_ID; ?><!--">-->
                                            <!-- Specify a subscriptions button. -->
                                            <input type="hidden" name="cmd" value="_s-xclick">
                                            <!-- Specify details about the subscription that buyers will purchase -->
                                            <input type="hidden" name="hosted_button_id" value="NZ9APHTVP6WSL">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="on0" value="Subscriptions Options">
                                                        Subscription Options
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="os0">
<!--                                                        <select name="os0" onchange="updateForm(this)">-->
                                                            <option value="Adult (18+)">
                                                                Adult (18+) : $6.99 USD - monthly
                                                            </option>

                                                            <option value="Family (Max. 5)">
                                                                Family (Max. 5) : $24.99 USD - monthly
                                                            </option>

                                                            <option value="Young Adult/Child (under 18)">
                                                                Young Adult/Child (under 18) : $4.99 USD - monthly
                                                            </option>

                                                            <option value="Senior (60+)">
                                                                Senior (60+) : $3.99 USD - monthly
                                                            </option>

                                                            <option value="Adult (18+)">
                                                                Adult (18+) : $74.99 USD - yearly
                                                            </option>

                                                            <option value="Family (Max 5)">
                                                                Family (Max 5) : $239.99 USD - yearly
                                                            </option>

                                                            <option value="Young Adult (under 18)">
                                                                Young Adult (under 18) : $53.00 USD - yearly
                                                            </option>

                                                            <option value="Senior (60+)">
                                                                Senior (60+) : $43.00 USD - yearly
                                                            </option>
                                                        </select>
                                                        <br>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <input type="hidden" name="currency_code" value="USD">
<!--                                            <input type="hidden" name="currency_code" value="--><?php //echo PAYPAL_CURRENCY; ?><!--">-->
<!--                                            <input type="hidden" name="a3" id="paypalAmt" value="6.99">-->
<!--                                            <input type="hidden" name="p3" id="paypalValid" value="1">-->
<!--                                            <input type="hidden" name="t3" value="M">-->
<!--                                            <!-- Custom variable user ID -->
<!--                                            <input type="hidden" name="custom" value="--><?php //echo time(); ?><!--">-->
<!--                                            <!-- Specify urls -->
<!--                                            <input type="hidden" name="cancel_return" value="--><?php //echo PAYPAL_CANCEL_URL; ?><!--">-->
<!--                                            <input type="hidden" name="return" value="--><?php //echo PAYPAL_RETURN_URL; ?><!--">-->
                                            <!-- <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>"> -->
                                            <input type="image"
                                                   src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif"
                                                   border="0" name="submit"
                                                   alt="PayPal - The safer, easier way to pay online!">

                                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
                                                 width="1" height="1">
                                        </form>
                                        <!--Paypal Test-->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main Content End -->
                </div>
			</div>
        </section>
        <!-- Page Wrapper End -->

<script>
    function updateForm(subscription) {
//        console.log(subscription.value);
        var a3 = ''; // price
        var t3 = ''; // monthly or yearly
        switch (subscription.value) {
            case "Adult (over 18)":
                a3 = 6.99;
                t3 = "M";
                break;
            case "Family (max 5)":
                a3 = 24.99;
                t3 = "M";
                break;
            case "Young Adult/Child(m)":
                a3 = 4.99;
                t3 = "M";
                break;
            case "Senior (60+)":
                a3 = 3.99;
                t3 = "M";
                break;
            case "Adult (18+)":
                a3 = 74.99;
                t3 = "Y";
                break;
            case "Family (5+)":
                a3 = 74.99;
                t3 = "Y";
                break;
            case "Young Adult/Child(y)":
                a3 = 74.99;
                t3 = "Y";
                break;
            case "Senior":
                a3 = 74.99;
                t3 = "Y";
                break;
            default:
                a3 = 6.99;
                t3 = "M";
        }

        // update payment config
        $('#paypalAmt').val(a3);
        $("input[name='t3']").val(t3);
    }
</script>

<?php include BASE_PATH.'/includes/footer.php'; ?>