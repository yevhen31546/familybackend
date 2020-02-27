<div class="modal fade note-add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add New Note</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                    <input type="hidden" name="cat_id" value="cat_id">
                    <input type="hidden" name="note_date" value="note_date">
                    <input type="hidden" name="note_media" value="note_media">
                    <input type="hidden" name="note_to">
                    <input type="hidden" name="update_date"
                           value="<?php if(isset($_POST['note_update_date']) && $_POST['update_category']){
                               echo $_POST['note_update_date'];
                           }?>">
                    <input type="hidden" name="update_cat"
                           value="<?php if(isset($_POST['note_update_date']) && $_POST['update_category']){
                               echo $_POST['update_category'];
                           }?>">
                    <input type="hidden" name="mode" value="add">
                    <input type="hidden" name="note_id" value="">
                    <div class="box-body">
                        <div class="form-group">
                            <input type="text" name="note_value" class="form-control" placeholder="Enter the text" required>
                            <input type="file" name="note_photo" id="fileToUpload" required>
                            <img id="note_photo_id" src="#" alt="" />
                            <div id="note_photo_alt">
                                Upload your image here
                            </div>
                            <input type="text" name="note_video" class="form-control" placeholder="Enter the video link" required>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success pull-right add-note-content">Send</button>
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
    function checkForm(form)
    {
        var to = form.note_to.value;
        var familyLists = <?php print_r(json_encode($familyLists)); ?>;
        if(familyLists.indexOf(to) === -1) {
            alert("Error: The selected profile is invalid!");
            return false;
        } else {
            return true;
        }
    }
</script>