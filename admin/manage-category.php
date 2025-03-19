<?php
session_start();
error_reporting(0);
include('../dbconnection.php');

if (strlen($_SESSION['homecare_db']) == 0) {
    header('location:logout.php');
} else {
    // Code for deletion
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM category WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Category deleted');</script>";
        echo "<script>window.location.href = 'manage-category.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homecare | Manage Categories</title>
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Main Content */
        .content-wrapper {
            margin-left: 230px; /* Matches sidebar width */
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }

        /* Table Styling */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Action Buttons */
        .btn {
            display: inline-block;
            padding: 8px 14px;
            font-size: 14px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

       /* Edit Button (Green) */
.btn-edit {
    background: #28a745 !important; /* Green */
    color: white !important;
}

.btn-edit:hover {
    background: #218838 !important; /* Darker green on hover */
}

/* Delete Button (Red) */
.btn-delete {
    background: #dc3545 !important; /* Red */
    color: white !important;
}

.btn-delete:hover {
    background: #c82333 !important; /* Darker red on hover */
}

        /* Aligning buttons properly */
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include_once('sidebar.php');?>

    <!-- Header -->
    <?php include_once('header.php');?>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="page-title">Manage Categories</div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>Creation Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM category";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) { ?> 
                            <tr>
                                <td><?php echo htmlentities($cnt); ?></td>
                                <td><?php echo htmlentities($row->CategoryName); ?></td>
                                <td><?php echo htmlentities($row->CreationDate); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-category.php?editid=<?php echo htmlentities($row->ID); ?>" class="btn btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manage-category.php?delid=<?php echo ($row->ID); ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this category?');">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php $cnt++; }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
<?php } ?>
