<!--Invite friend modal for outside site-->
<div class="modal fade invite-friend-modal" id="invite-friend-modal" tabindex="-1"
     role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Invite a Friend</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST"">
                    <div class="box-body">
                        <div class="form-group">
                            <input type="text" name="friend_name" class="form-control" placeholder="Enter the friend name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="friend_email" class="form-control" placeholder="Enter the email" required>
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
