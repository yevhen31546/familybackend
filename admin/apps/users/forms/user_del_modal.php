<div class="modal center-modal fade" id="confirm-delete-<?php echo $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <!-- Modal content -->
                    <input type="hidden" name="del_id" id="del_id" value="<?php echo $row['id']; ?>">
                    <p>Are you sure you want to delete this row?</p>
                    <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-bold btn-pure btn-primary float-right">Yes</button>
                </form>
            </div>
            <div class="modal-footer modal-footer-uniform">

            </div>
        </div>
    </div>
</div>