<?php
session_start();
error_reporting(0);
include '../dbconnection.php';

if (strlen($_SESSION['homecare_db']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_GET['editid']; // Booking ID
        $status = $_POST['status'];
        $remark = $_POST['remark'];
        $assignee = !empty($_POST['assignee']) ? $_POST['assignee'] : NULL;

        // Debugging - see what is being posted
        // Uncomment this if needed:
        // var_dump($_POST); exit();

        $sql = "UPDATE maidbooking SET Status=:status, Remark=:remark, AssignTo=:assignee WHERE ID=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':remark', $remark, PDO::PARAM_STR);
        $query->bindParam(':assignee', $assignee, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_INT);

        if ($query->execute()) {
            echo '<script>alert("Remark and assignment updated successfully!")</script>';
            echo "<script>window.location.href ='all-request.php'</script>";
        } else {
            echo '<script>alert("Update failed.")</script>';
        }
    }

    if (isset($_GET['editid'])) {
        $eid = $_GET['editid'];
        $sql = "SELECT mb.*, c.CategoryName, m.Name AS MaidName, m.Email AS MaidEmail, m.ContactNumber AS MaidContact 
                FROM maidbooking mb 
                LEFT JOIN category c ON mb.CatID = c.ID
                LEFT JOIN maid m ON mb.AssignTo = m.MaidId
                WHERE mb.ID = :eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eid', $eid, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Maid Booking Details</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { width: 80%; margin: auto; padding: 20px; background: white; border-radius: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { padding: 10px; border: 1px solid #ddd; }
        .table th { background: #4CAF50; color: white; }
        .btn { padding: 10px; border: none; cursor: pointer; border-radius: 5px; }
        .btn-primary { background: #4CAF50; color: white; }
        .btn-danger { background: red; color: white; }
        .modal-content { width: 60%; margin: auto; }
        .modal-button {  padding: 10px; border: none; cursor: pointer; border-radius: 5px; margin-left: 25rem; background-color: #0d6efd; margin-top: 1rem; }
        .table2 { width: 50rem; margin-left: 25rem; }
        .table2 th, .table2 td { padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<?php include('sidebar.php'); ?>
<?php include('header.php'); ?>

<div class="container">
    <h2 class="text-center">Maid Booking Details</h2>

    <?php if ($result) { ?>
        <table class="table2">
            <tr><th>Booking ID</th><td><?php echo htmlentities($result->BookingID); ?></td></tr>
            <tr><th>Service Required</th><td><?php echo htmlentities($result->CategoryName); ?></td></tr>
            <tr><th>Name</th><td><?php echo htmlentities($result->Name); ?></td></tr>
            <tr><th>Contact</th><td><?php echo htmlentities($result->ContactNumber); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlentities($result->Email); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlentities($result->Address); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlentities($result->Gender); ?></td></tr>
            <tr><th>Shift From</th><td><?php echo htmlentities($result->WorkingShiftFrom); ?></td></tr>
            <tr><th>Shift To</th><td><?php echo htmlentities($result->WorkingShiftTo); ?></td></tr>
            <tr><th>Start Date</th><td><?php echo htmlentities($result->StartDate); ?></td></tr>
            <tr><th>Status</th><td><?php echo ($result->Status) ? htmlentities($result->Status) : "Not Responded Yet"; ?></td></tr>
            <tr><th>Admin Remark</th><td><?php echo ($result->Remark) ? htmlentities($result->Remark) : "Not Updated Yet"; ?></td></tr>
            <tr><th>Assigned Maid</th><td><?php echo ($result->MaidName) ? htmlentities($result->MaidName) : "Not Assigned"; ?></td></tr>
        </table>

        <button class="modal-button" data-toggle="modal" data-target="#actionModal">Take Action</button>
    <?php } else { echo "<p>No Booking Found</p>"; } ?>

    <!-- Take Action Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Take Action</h5>
        </div>
        <div class="modal-body">
          <table class="table">
            <tr>
              <th>Remark:</th>
              <td><textarea name="remark" class="form-control" required></textarea></td>
            </tr>
            <tr>
              <th>Status:</th>
              <td>
                <select name="status" class="form-control" required>
                  <option value="">Select</option>
                  <option value="Approved">Approved</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
              </td>
            </tr>
            <tr>
              <th>Assign To:</th>
              <td>
              <select name="assignee" class="form-control">
  <option value="">Assign Maid</option>
  <?php
  $sql2 = "SELECT MaidID, Name FROM maid";
  $query2 = $dbh->prepare($sql2);
  $query2->execute();
  $maids = $query2->fetchAll(PDO::FETCH_OBJ);
  foreach ($maids as $maid) { ?>
      <option value="<?php echo htmlentities($maid->MaidID); ?>">
          <?php echo htmlentities($maid->MaidID) . " - " . htmlentities($maid->Name); ?>
      </option>
  <?php } ?>
</select>

              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

</body>
</html>

<?php } ?>
