<!--this is a toast message!-->
<?php if (isset($_GET['msg'])): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3 me-2">
        <div id="successToast" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error_msg'])): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= htmlspecialchars($_GET['error_msg']) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>