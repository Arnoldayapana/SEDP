<?php
$title = 'Narrative Report | SEDP HRMS';
$page = 'compliance narrative report';

include('../../Core/Includes/header.php');
?>
<div class="wrapper">
    <?php
    include("../../Core/Includes/sidebar.php");
    ?>

    <div class="main p-3">
        <?php
        include('../../Core/Includes/navBar.php');
        ?>

        <div class="container-fluid shadow p-3 mb-5 bg-body-tertiary rounded-4">
            <h3 class="fw-bold fs-4">Narrative Report</h3>
            <hr>
            <div class="row">
                <div class="d-grid d-md-flex justify-content-md-end px-6">
                    <form action="#" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" value="" class="form-control" placeholder="Search Recipient">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>SUBMISION STATUS</th>
                        <th>OPERATIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //connection
                    include("../../../Database/db.php");
                    //read all row from database table
                    $sql = "SELECT * FROM scholar_login";
                    $result = $connection->query($sql);

                    if (!$result) {
                        die("Invalid Query" . $connection->error);
                    }
                    //read data of each row
                    while ($row = $result->fetch_assoc()) {
                        $modalId = "editRecipient" . $row['id'];
                        $ViewId = "viewRecipient" . $row['id'];
                        echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[username]</td>
                            <td>$row[password]</td>
                            <td class='text-success fw-semi-bold fs-6'>pending</td>
                            <td>
                                <!-- view Button -->
                                <a href='../Dao/Compliance-db/ViewNarative.php?id={$row['id']}' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-eye'></i>
                                </a>    
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>

        <?php
        include('../../../Assets/Js/bootstrap.js')
        ?>
        </body>

        </html>