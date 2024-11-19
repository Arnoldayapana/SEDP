<!-- Schedule Interview Modal -->
<div class="modal fade" id="ScheduleScholarApplicant" tabindex="-1" aria-labelledby="ScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Schedule Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ScheduleForm" method="POST" action="../Dao/ScholarApplicant-db/ScheduleScholarApplicant-db.php">
                <div class="modal-body">
                    <label for="interview_date" class="form-label">Select the interview Date and Time</label>
                    <input type="datetime-local" class="form-control" id="interview_date" name="interview_date" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Use hidden input to pass scholar_id -->
                    <input type="hidden" id="scholar_id" name="scholar_id" value="">
                    <button type="submit" class="btn btn-danger">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to set the scholar_id in the modal before opening
    function setScholarApplicantIdForSchedule(scholarApplicantId) {
        document.getElementById('scholar_id').value = scholarApplicantId;
    }
</script>