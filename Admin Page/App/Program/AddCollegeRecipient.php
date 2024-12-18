        <!-- Modal -->
        <div class="modal fade" id="AddRecipient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="width: 550px;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Recipient</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="../Dao/programs/AddCollegeRecipient-db.php" method="Post">
                        <div class="modal-body">

                            <input type="hidden" name="recipient_id" value="<?php echo htmlspecialchars($recipient_id); ?>">
                            <div class="form-group mb-1">
                                <label class="col-sm-6 col-form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label class="col-sm-6 col-form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
                            </div>

                            <div class="form-group mb-1">
                                <label class="col-sm-6 col-form-label">School</label>
                                <input type=" text" class="form-control" name="school" value="<?php echo $school; ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label class="col-sm-6 col-form-label">Contact Number</label>
                                <input type=" number" class="form-control" name="contact" value="<?php echo $contact; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="branch" class="col col-form-label">Branch</label>
                                <select class="form-select" id="branch" name="branch" required>
                                    <option value="" disabled <?php echo empty($branch) ? 'selected' : ''; ?>>Select</option>
                                    <?php
                                    // Fetch branches from the database
                                    $sql = "SELECT * FROM tblbranch";
                                    $result = $connection->query($sql);
                                    if (!$result) {
                                        die("Invalid Query: " . $connection->error);
                                    }
                                    // Display each branch as an option
                                    while ($row = $result->fetch_assoc()) {
                                        // Set the selected attribute only if $branch matches the current row's name
                                        $selected = ($branch == $row['name']) ? 'selected' : '';
                                        echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="col col-form-label">GradeLevel</label>
                                <input type="text" class="form-control" name="GradeLevel" value="College">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>