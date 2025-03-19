<?php
session_start();
error_reporting(0);
include('../dbconnection.php');

if (strlen($_SESSION['homecare_db'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_GET['editid'];
        $bookingid = $_GET['bookingid'];
        $status = $_POST['status'];
        $remark = $_POST['remark'];
        $assignee = $_POST['assignee'];

        $sql = "UPDATE maidbooking SET Status=:status, Remark=:remark, AssignTo=:assignee WHERE ID=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':remark', $remark, PDO::PARAM_STR);
        $query->bindParam(':assignee', $assignee, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Remark has been updated")</script>';
        echo "<script>window.location.href ='all-request.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Maid Hiring Management System | View Booking Details</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .table th {
            background-color: #0d6efd;
            color: white;
            text-align: left;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .modal-header {
            background-color: #0d6efd;
            color: white;
        }
        .modal-content {
            border-radius: 8px;
        }
        .table2{
            width: 50rem;
            margin-left: 20rem;
        }
        .table2 th, .table2 td { 
            padding: 10px; 
            border: 1px solid #ddd; 
        }
        .modal-button {  
            padding: 10px; 
            border: none; 
            cursor: pointer;
             border-radius: 5px;
             margin-left: 10rem;
             background-color: #0d6efd;
             margin-top: 1rem; 
             color: #fff;
          
            }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>
<?php include('header.php'); ?>

<div class="container">
    <h2 class="text-center text-primary mb-4">Maid Booking Details</h2>

    <?php
    $eid = $_GET['editid'];
    $sql = "SELECT maidbooking.*, category.CategoryName FROM maidbooking 
            JOIN category ON maidbooking.CatID = category.ID 
            WHERE maidbooking.ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;

    if ($query->rowCount() > 0) {
        foreach ($results as $row) {
            $assignto = $row->AssignTo;
    ?>

    <table class="table2">
        <tr>
            <th>Booking ID</th>
            <td><?php echo $row->BookingID; ?></td>
            <th>Service Required</th>
            <td><?php echo $row->CategoryName; ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo $row->Name; ?></td>
            <th>Contact Number</th>
            <td><?php echo $row->ContactNumber; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $row->Email; ?></td>
            <th>Address</th>
            <td><?php echo $row->Address; ?></td>
        </tr>
        <tr>
            <th>Booking Status</th>
            <td><?php echo $row->Status ? $row->Status : "Not Updated"; ?></td>
            <th>Admin Remark</th>
            <td><?php echo $row->Remark ? $row->Remark : "Not Updated"; ?></td>
        </tr>
    </table>

    <?php if ($assignto != ''): ?>
        <h4 class="text-primary mt-4">Assigned Maid Details</h4>
        <table class="table table-bordered table-hover">
            <?php 
            $stmt = $dbh->prepare("SELECT * FROM maid WHERE MaidId='$assignto'");
            $stmt->execute();
            $maidDetails = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($maidDetails as $maid) { ?>
            <tr>
                <th>Maid Name</th>
                <td><?php echo $maid->Name; ?></td>
                <th>Email</th>
                <td><?php echo $maid->Email; ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo $maid->ContactNumber; ?></td>
                <th>Gender</th>
                <td><?php echo $maid->Gender; ?></td>
            </tr>
            <tr>
                <th>Photo</th>
                <td colspan="3"><img src="images/<?php echo $maid->ProfilePic; ?>" width="100"></td>
            </tr>
            <?php } ?>
        </table>
    <?php endif; ?>

    <?php } } ?>

    <?php if ($status == "") { ?>
        <div class="text-center">
            <button class="modal-button" data-bs-toggle="modal" data-bs-target="#actionModal">Take Action</button>
        </div>
    <?php } ?>

</div>

<!-- Modal -->
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Take Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label>Remark</label>
                        <textarea name="remark" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Approved">Approved</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3" id="assignToField" style="display: none;">
                        <label>Assign To</label>
                        <select name="assignee" id="assignee" class="form-control">
                            <option value="">Select Maid</option>
                            <?php 
                            $sql2 = "SELECT * FROM maid";
                            $query2 = $dbh->prepare($sql2);
                            $query2->execute();
                            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                            foreach ($result2 as $row) { ?>
                                <option value="<?php echo $row->MaidId; ?>"><?php echo $row->Name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('status').addEventListener('change', function() {
    document.getElementById('assignToField').style.display = (this.value === 'Approved') ? 'block' : 'none';
});
</script>

</body>
</html>

<?php } ?>
