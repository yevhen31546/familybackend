<!--Add as family modal-->
<div class="modal fade invite-family-select-modal" id="invite-family-select-modal" tabindex="-1"
     role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add as Family</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" onsubmit="return checkForm(this);">
                    <input type="hidden" name="myfamily">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="message">Select Family Relationship:</label>
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
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success pull-right">Send</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function checkForm(form) {
        if(form.family_member.value === "family_member") {
            // alert("Error: Please select relationship");
            form.family_member.style.border = "1px solid red";
            // console.log(form.family_member);
            return false;
        } else {
            form.family_member.style.border = "1px solid white";
            return true;
        }
    }
</script>