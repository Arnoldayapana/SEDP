    <div class="modal fade" id="$scholarId" tabindex="-1" aria-labelledby="scheduleInterviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header bg-primary border-0">
                    <div class="modal-title fw-bold text-white" id="scheduleModalLabel">
                        <h5 class="mb-1">Schedule Interview</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="max-height: 590px; overflow-y: auto;">
                    <form id="interviewForm" action="../Dao/Scholar-db/schedule_interview.php" method="POST">
                        <!-- Hidden input for scholar_id -->
                        <input type="hidden" class="scholar_id" name="scholar_id" id="scholar_id" value="">

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. First round interview" required>
                        </div>
                        <div class="mb-4">
                            <label for="date" class="form-label fw-semibold">Interview Date and Time</label>
                            <input type="datetime-local" class="form-control" id="date" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label for="interviewType" class="form-label fw-semibold">Interview Type</label>
                            <div class="btn-group w-100" role="group" aria-label="Interview Type">
                                <input type="radio" class="btn-check" name="interviewType" id="video-call" value="video" autocomplete="off" checked>
                                <label class="btn btn-outline-primary w-100" for="video-call">Video Call</label>

                                <input type="radio" class="btn-check" name="interviewType" id="phone" value="phone" autocomplete="off">
                                <label class="btn btn-outline-primary w-100" for="phone">Phone</label>

                                <input type="radio" class="btn-check" name="interviewType" id="in-office" value="in-office" autocomplete="off">
                                <label class="btn btn-outline-primary w-100" for="in-office">In-office</label>
                            </div>
                        </div>

                        <!-- Video Call Section -->
                        <div id="video-call-section" class="interview-details">
                            <label for="videocallLink" class="form-label fw-semibold">Video Call Link</label>
                            <input type="text" class="form-control mb-3" id="videocallLink" name="videocallLink" placeholder="e.g. https://meet.google.com/abc-defg-hij">

                            <label for="videoDescription" class="form-label fw-semibold">Description</label>
                            <textarea name="interviewDescription_video" id="videoDescription" class="form-control" placeholder="Describe the video call."></textarea>
                        </div>

                        <!-- Phone Section -->
                        <div id="phone-section" class="interview-details" style="display: none;">
                            <label for="phoneNumber" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control mb-3" id="phoneNumber" name="phoneNumber" placeholder="e.g. +123456789">

                            <label for="phoneDescription" class="form-label fw-semibold">Description</label>
                            <textarea name="interviewDescription_phone" id="phoneDescription" class="form-control" placeholder="Describe the phone interview."></textarea>
                        </div>

                        <!-- In-office Section -->
                        <div id="in-office-section" class="interview-details" style="display: none;">
                            <label for="officeAddress" class="form-label fw-semibold">Office Address</label>
                            <input type="text" class="form-control mb-3" id="officeAddress" name="officeAddress" placeholder="e.g. 123 Main St, City">

                            <label for="officeDescription" class="form-label fw-semibold">Description</label>
                            <textarea name="interviewDescription_office" id="officeDescription" class="form-control" placeholder="Describe the office interview."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript function to set the scholar_id in the form when the "Schedule" button is clicked
        function setScholarForInterview(scholarId) {
            document.getElementById('scholar_id').value = scholarId; // Set the scholar_id in the hidden input field
        }

        // function to switch between interview types
        document.addEventListener('DOMContentLoaded', function() {
            function updateInterviewType() {
                // Get all sections
                const videoSection = document.getElementById('video-call-section');
                const phoneSection = document.getElementById('phone-section');
                const inOfficeSection = document.getElementById('in-office-section');

                // Ensure all sections are hidden initially
                [videoSection, phoneSection, inOfficeSection].forEach(section => {
                    section.style.display = 'none';
                });

                // Determine which section to display based on the checked radio button
                const selectedType = document.querySelector('input[name="interviewType"]:checked');
                if (selectedType) {
                    switch (selectedType.id) {
                        case 'video-call':
                            videoSection.style.display = 'block';
                            break;
                        case 'phone':
                            phoneSection.style.display = 'block';
                            break;
                        case 'in-office':
                            inOfficeSection.style.display = 'block';
                            break;
                        default:
                            console.error("Unexpected radio button selected!");
                    }
                }
            }

            // Initialize on page load
            updateInterviewType();

            // Add event listeners for radio buttons
            document.querySelectorAll('input[name="interviewType"]').forEach(function(radio) {
                radio.addEventListener('change', updateInterviewType);
            });
        });
    </script>