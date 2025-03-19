<?php
session_start();
error_reporting(0);
include('../dbconnection.php');

if (strlen($_SESSION['homecare_db'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Maid Hiring Management System | All Request</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container2 {
            margin-top: 30px;
            width: 70rem;
            margin-left: 20rem;
        }
        .table-container {
            overflow-x: auto; /* Enables horizontal scrolling if needed */
        }
        .table {
            font-size: 14px; /* Smaller font size */
        }
        .table th, .table td {
            padding: 6px; /* Reduces cell padding */
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #0d6efd;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            font-size: 12px; /* Smaller badges */
            padding: 5px 8px;
        }
        .btn-sm {
            padding: 4px 10px;
            font-size: 12px; /* Smaller button text */
        }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>
<?php include('header.php'); ?>
<div class="container2">
    <h4 class="text-center text-primary mb-3">All Booking Requests</h4>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white p-2">
            <h6 class="mb-0">All Requests</h6>
        </div>
        <div class="card-body p-2">
            <div class="table-container">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Assign To</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM maidbooking";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;

                        if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                        ?>
                        <tr>
                            <td><?php echo htmlentities($cnt); ?></td>
                            <td><?php echo htmlentities($row->BookingID); ?></td>
                            <td><?php echo htmlentities($row->Name); ?></td>
                            <td><?php echo htmlentities($row->ContactNumber); ?></td>
                            <td><?php echo htmlentities($row->Email); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlentities($row->BookingDate); ?></span></td>
                            <td>
                                <?php if ($row->Status == "") { ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php } else { ?>
                                    <span class="badge bg-primary"><?php echo htmlentities($row->Status); ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($row->Status == "") { ?>
                                    <span class="badge bg-warning">Not Assigned</span>
                                <?php } elseif ($row->Status == "Cancelled") { ?>
                                    <span class="badge bg-danger">Cancelled</span>
                                <?php } else { ?>
                                    <span class="badge bg-primary"><?php echo htmlentities($row->AssignTo); ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="view-booking-detail.php?editid=<?php echo htmlentities($row->ID); ?>&bookingid=<?php echo htmlentities($row->BookingID); ?>" 
                                   class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        <?php
                            $cnt++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php } ?>
