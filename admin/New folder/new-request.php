<?php
session_start();
error_reporting(0);
include('../dbconnection.php');
if (strlen($_SESSION['homecare_db']==0)) {
  header('location:logout.php');
} else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Maid Hiring Management System || New Request</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container2 {
            margin-top: 30px;
            width: 70rem;
            margin-left: 20rem;
        }
        .table-container {
            overflow-x: auto;
        }
        .table {
            font-size: 14px;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            font-size: 12px;
            padding: 5px 8px;
        }
        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
        }
    </style>
</head>
<body class="inner_page tables_page">
    <div class="full_container">
        <div class="inner_container">
            <!-- Sidebar -->
            <?php include_once('sidebar.php');?>

            <!-- Right Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include_once('header.php');?>

                <!-- Dashboard Inner -->
                <div class="midde_cont">
                    <div class="container2">
                        <h4 class="text-center text-primary mb-3">New Request</h4>
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white p-2">
                                <h6 class="mb-0">All New Requests</h6>
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
                                                <th>Booking Date</th>
                                                <th>Status</th>
                                                
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * from maidbooking where Status is null";
                                            $query = $dbh -> prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            $cnt = 1;
                                            if($query->rowCount() > 0) {
                                                foreach($results as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($row->BookingID); ?></td>
                                                <td><?php echo htmlentities($row->Name); ?></td>
                                                <td><?php echo htmlentities($row->ContactNumber); ?></td>
                                                <td><?php echo htmlentities($row->Email); ?></td>
                                                <td><span class="badge bg-primary"><?php echo htmlentities($row->BookingDate); ?></span></td>
                                                <td>
                                                    <?php echo ($row->Status == "") ? '<span class="badge bg-warning">Not Updated Yet</span>' : '<span class="badge bg-primary">'.htmlentities($row->Status).'</span>'; ?>
                                                </td>
                                                
                                                <td>
                                                    <a href="view-booking-detail.php?editid=<?php echo htmlentities($row->ID); ?>&bookingid=<?php echo htmlentities($row->BookingID); ?>" class="btn btn-sm btn-primary">View</a>
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
                </div>

               
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
