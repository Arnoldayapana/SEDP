<div class="col-lg-6 col-md-6 col-sm-6 mb-3 mt-2">
    <div class="container m-0 ms-2 p-4" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <form action="../Dao/Announcement-db/announcement_db.php" method="POST" enctype="multipart/form-data">
            <div class="d-flex">
                <div class="header">
                    <h2 class="fw-bold fs-5">Create Announcements:</h2>
                    <p class="text-secondary" style="font-size:14px;">Connect with others!</p>
                </div>

                <!-- Dropdown for audience selection -->
                <div class="ms-auto mb-3 ">
                    <select class="form-select w-auto" id="audience" name="audience" required>
                        <option value="both" selected>Both</option>
                        <option value="scholar">Scholar</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>
            </div>

            <!-- Title Input Field -->
            <div class="row gap-0">
                <div class="col-12 d-flex">
                    <div class="col-10 form-floating-sm mb-2">
                        <input type="text" required class="form-control" id="title" name="title" placeholder="Title">
                    </div>
                    <!-- Image Upload Field -->
                    <div class="col-2 mb-3">
                        <div class="input-group">
                            <!-- Hidden File Input -->
                            <input
                                class="form-control visually-hidden"
                                type="file"
                                id="image"
                                name="image"
                                accept="image/*"
                                onchange="previewImage(event)">

                            <!-- Custom Label to Replace Default File Input -->
                            <label
                                for="image"
                                class="form-control btn btn-outline-secondary"
                                style="margin: 0;">
                                <i class="bi bi-card-image"></i>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Description Textarea -->
            <div class="form-floating-sm mb-2">
                <textarea class="form-control" rows="3" required name="content" placeholder="Description" id="content" style="height: 100px"></textarea>
            </div>

            <!-- Image Preview -->
            <div class="mt-2 d-flex justify-content-center">
                <img id="imagePreview" src="#" alt="Your Image" class="img-thumbnail d-none " style="width: 500px;" height:auto;>
            </div>


            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn text-white" style="background-color: #003c3c;">POST</button>
            </div>
        </form>
    </div>
</div>
<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none'); // Show the image
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = "#";
            imagePreview.classList.add('d-none'); // Hide the image
        }
    }
</script>