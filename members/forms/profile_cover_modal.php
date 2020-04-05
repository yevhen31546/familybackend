<div class="modal fade profile-cover-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Choose Cover Photo</h4>
                <span style="color: #7398aa">Add line – Note: For best results, the image should be 1920 x 200 pixels</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="cover_photo_fg" value="add">
                    <div class="box-body">
                        <div class="form-group">
                            <input type="file" name="cover_photo" id="fileToUpload" required>
                            <img id="note_photo_id" src="#" alt="" />
                            <div id="note_photo_alt">
                                Upload your image here
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right add-note-content">Submit</button>
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