<!-- Delete Confirmation Modal -->
<div class="modal fade" id="DeleteJobApplicant" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Applicant?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="../Dao/JobApplicants-db/DeleteJobApplicants_db.php">
                    <input type="hidden" id="applicant_id" name="applicant_id" value="">
                    <button type="submit" class="btn btn-danger">Reject</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to set the applicant_id in the modal before opening
    function setJobApplicantIdForDelete(applicantId) {
        document.getElementById('applicant_id').value = applicantId;
    }
</script>