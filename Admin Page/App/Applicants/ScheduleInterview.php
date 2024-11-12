<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleApplicant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="scheduleModalLabel">Schedule Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="./sample.php">
                <div class="modal-body">
                    <!-- Use hidden input to pass applicant_id -->
                    <input type="hidden" id="applicant_id" name="applicant_id" value="">
                    <label for="date" class="form-label">Interview Date and Time</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setApplicantForInterview(applicantId) {
        document.getElementById('applicant_id').value = applicantId;
    }
</script>