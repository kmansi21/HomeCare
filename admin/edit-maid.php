<?php
session_start();
include('../dbconnection.php');

// Check if session is not set, redirect to logout page
if (strlen($_SESSION['homecare_db'] == 0)) {
    header('location:logout.php');
} else {
    // Check if 'id' is present in the URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $eid = $_GET['id']; // Store the 'id' from URL into $eid
    } else {
        echo "Error: Maid ID not found.";
        exit;
    }

    // Query to fetch all maids
$sql = "SELECT maid.MaidId, maid.Name, maid.Email, maid.Gender, maid.ContactNumber, maid.Experience, maid.DateOfBirth, maid.Address, maid.Description, maid.IDProof, category.CategoryName 
FROM maid 
JOIN category ON maid.CategoryID = category.ID"; // Don't filter by maidId here

$query = $dbh->prepare($sql);
$query->execute();
$maids = $query->fetchAll(PDO::FETCH_OBJ);

    $query = $dbh->prepare($sql);
    $query->bindParam(':maidId', $eid, PDO::PARAM_INT);
    $query->execute();
    $maid = $query->fetch(PDO::FETCH_OBJ);

    // Handle form submission and update the maid details
    if (isset($_POST['submit'])) {
        $catid = $_POST['catid'];
        $maidid = $_POST['maidid'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contno = $_POST['contno'];
        $exp = $_POST['experience'];
        $dob = $_POST['dob'];
        $add = $_POST['add'];
        $desc = $_POST['desc'];
        $gender = $_POST['gender'];
    
        // Update query to update maid details in the database
        $update_sql = "UPDATE maid SET CategoryID = :catid, MaidId = :maidid, Name = :name, Email = :email, ContactNumber = :contno, Experience = :exp, DateOfBirth = :dob, Address = :add, Description = :desc, Gender = :gender WHERE MaidId = :maidid";
        $update_query = $dbh->prepare($update_sql);
        $update_query->bindParam(':catid', $catid, PDO::PARAM_STR);
        $update_query->bindParam(':maidid', $maidid, PDO::PARAM_STR);
        $update_query->bindParam(':name', $name, PDO::PARAM_STR);
        $update_query->bindParam(':email', $email, PDO::PARAM_STR);
        $update_query->bindParam(':contno', $contno, PDO::PARAM_STR);
        $update_query->bindParam(':exp', $exp, PDO::PARAM_STR);
        $update_query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $update_query->bindParam(':add', $add, PDO::PARAM_STR);
        $update_query->bindParam(':desc', $desc, PDO::PARAM_STR);
        $update_query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $update_query->execute();
    
        // Immediately redirect to avoid duplicate form submissions or reloads
        header('Location: manage-maid.php');
        exit; // Prevent further execution of the script
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homecare || Edit Maid</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .content-wrapper {
            margin-left: 150px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            margin-left: 10rem;
            color: #343a40;
            margin-bottom: 20px;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            
            margin: auto;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 5px;
            border: 1px solid #222;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-bottom: 2rem;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .back-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <?php include_once('sidebar.php');?>

    <?php include_once('header.php');?>

    <div class="content-wrapper">
        <div class="page-title">Edit Maid</div>

        <div class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="maidid" value="<?php echo htmlentities($maid->MaidId); ?>">

                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlentities($maid->Name); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlentities($maid->Email); ?>" required>

                <label for="gender">Gender</label>
                <input type="text" id="gender" name="gender" value="<?php echo htmlentities($maid->Gender); ?>" required>

                <label for="contactNumber">Contact Number</label>
                <input type="text" id="contactNumber" name="contno" value="<?php echo htmlentities($maid->ContactNumber); ?>" required>

                <label for="experience">Experience</label>
                <input type="text" id="experience" name="experience" value="<?php echo htmlentities($maid->Experience); ?>" required>

                <label for="category">Category</label>
                <input type="text" id="category" name="catid" value="<?php echo htmlentities($maid->CategoryName); ?>" required>

                <label for="address">Address</label>
                <textarea id="address" name="add" rows="4" required><?php echo htmlentities($maid->Address); ?></textarea>

                <label for="description">Description</label>
                <textarea id="description" name="desc" rows="4" required><?php echo htmlentities($maid->Description); ?></textarea>

                <button type="submit" name="submit">Update Maid</button>
            <!-- </form> -->

            <a href="manage-maid.php" class="back-button">Back to Manage Maids</a>
            </form>
        </div>
    </div>

</body>
</html>
<?php } ?>
