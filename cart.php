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
                                <form action="#">
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

                                   <!-- <div class="cart--submit-btn text-right">
                                        <button type="submit" class="btn btn-default">Update Cart</button>
                                    </div>-->
                                </form>
                            </div>
                            <!-- Cart Items End -->

                            <div class="row">
                                <div class="col-md-12 pb--60">
                                    <!-- Cart Coupon Start -->
                                    <div class="cart--coupon" data-form="validate">
                                        <div class="cart--title bg-lighter">
                                            <h3 class="h4"><strong>START CAPTURING YOUR MEMORIES TODAY!</strong></h3>
                                        </div>

                                       <!-- <form action="#">
                                            <p class="fs--14 text-darkest pb--15">Apply Coupon Code. If You Have One.</p>

                                            <input type="text" name="coupon" placeholder="Enter coupon code" class="form-control" required>

                                            <button type="submit" class="btn btn-default mt--20">Apply Now</button>
                                        </form>
                                    </div>
                                    <!-- Cart Coupon End
</div>

                                <div class="col-md-6 pb--60">
                                    <!-- Cart Total Start
<div class="cart--total">
                                        <div class="cart--title bg-lighter">
                                            <h3 class="h4">Cart Totals</h3>
                                        </div>

                                        <p class="fs--14 text-darkest pb--15">Total value of your cart items.</p>

                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Sub Total</th>
                                                    <td>$ 143.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Shipping Cost</th>
                                                    <td>Free</td>
                                                </tr>
                                                <tr>
                                                    <th>Total</th>
                                                    <td>$ 143.00</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <a href="#" class="btn btn-default">Proceed To Checkout</a>
                                    </div>-->
<!--Paypal Test-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="U325BK9NAH9NU">
<table>
<tr><td><input type="hidden" name="on0" value="Subscriptions Options"><h4>Choose your subscription option using the drop-down arrow, then click the "Subscribe" button.</h4></td></tr><tr><td><select name="os0">
	<option value="Adult (over 18)">Adult (over 18) : $6.99 USD - monthly</option>
	<option value="Family (max 5)">Family (max 5) : $24.99 USD - monthly</option>
	<option value="Young Adult/Child">Young Adult/Child : $4.99 USD - monthly</option>
	<option value="Senior (60+)">Senior (60+) : $3.99 USD - monthly</option>
	<option value="Adult (18+)">Adult (18+) : $74.99 USD - yearly</option>
	<option value="Family (max 5)">Family (max 5) : $239.99 USD - yearly</option>
	<option value="Young Adult/Child">Young Adult/Child : $53.00 USD - yearly</option>
	<option value="Senior">Senior : $43.00 USD - yearly</option>
</select><br> </td></tr>
</table>
	<br>
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">

<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">

</form>
<!--Paypal Test-->
                                  <!-- Cart Total End -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main Content End -->
                </div>
            </div>
			</div>
        </section>
        <!-- Page Wrapper End -->

<?php include BASE_PATH.'/includes/footer.php'; ?>