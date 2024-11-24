<div class="col-lg-6 col-md-6 col-sm-6 mb-3 mt-2">
    <div class="container m-0 ms-2 p-4" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <form action="../Dao/Announcement-db/announcement_db.php" method="POST" enctype="multipart/form-data">
            <div class="d-flex">
                <div class="header">
                    <h2 class="fw-bold fs-4">Create Announcements:</h2>
                    <p class="text-secondary">Connect with others!</p>
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
            <div class="form-floating mb-2">
                <input type="text" required class="form-control" id="title" name="title" placeholder="Title">
                <label for="title">Title</label>
            </div>

            <!-- Description Textarea -->
            <div class="form-floating mb-2">
                <textarea class="form-control" rows="3" required name="content" placeholder="Description" id="content" style="height: 100px"></textarea>
                <label for="content">Description</label>
            </div>

            <!-- Image Upload Field -->
            <div class="mb-2">
                <label for="image" class="form-label">Upload Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*">
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn text-white btn-primary">POST</button>
            </div>
        </form>
    </div>
</div>