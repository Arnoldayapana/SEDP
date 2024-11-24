<!-- Delete Employee Modal -->
<div class="modal fade" id="DeleteEmployeeArchive" tabindex="-1" aria-labelledby="DeleteEmployeeArchiveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DeleteEmployeeArchiveLabel">Delete Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this employee from the archive?</p>
            </div>
            <div class="modal-footer">
                <!-- The delete form -->
                <form method="POST" action="../Dao/Employee-db/DeleteEmployeeArchive_db.php">
                    <input type="hidden" name="delete_employee_id" id="deleteEmployeeId" value="">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function setEmployeeIdForDelete(employeeId) {
        // Set the employee ID to the hidden input field
        document.getElementById('deleteEmployeeId').value = employeeId;
    }
</script>