<form>
    <div class="box-body">
        <h4 class="mt-0 mb-20">1. Customer Info:</h4>
        <div class="form-group">
            <label for="example_input_full_name">Full Name:</label>
            <input type="email" class="form-control" placeholder="Enter full name">
        </div>
        <div class="form-group">
            <label>Email address:</label>
            <input type="email" class="form-control" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label>Contact:</label>
            <input type="tel" class="form-control" placeholder="Phone number">
        </div>
        <div class="form-group">
            <label>Communications :</label>
            <div class="c-inputs-stacked">
                <input type="checkbox" id="checkbox_123">
                <label for="checkbox_123" class="block">Email</label>
                <input type="checkbox" id="checkbox_234">
                <label for="checkbox_234" class="block">SMS</label>
                <input type="checkbox" id="checkbox_345">
                <label for="checkbox_345" class="block">Phone</label>
            </div>
        </div>
        <hr>

        <h4 class="mt-0 mb-20">2. Payment Info:</h4>

        <div class="form-group">
            <label>Payment Method :</label>
            <div class="c-inputs-stacked">
                <input name="group123" type="radio" id="radio_123" value="1">
                <label for="radio_123" class="mr-30">Credit Card</label>
                <input name="group456" type="radio" id="radio_456" value="1">
                <label for="radio_456" class="mr-30">Cash</label>
                <input name="group789" type="radio" id="radio_789" value="1">
                <label for="radio_789" class="mr-30">Wallet</label>
            </div>
        </div>
        <div class="form-group">
            <label for="example_input_full_name">Amount:</label>
            <input type="email" class="form-control" placeholder="Enter full name">
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" class="btn btn-danger">Cancel</button>
        <button type="submit" class="btn btn-success pull-right">Submit</button>
    </div>
</form>