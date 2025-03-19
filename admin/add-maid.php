<?php
session_start();
// error_reporting(0);
include('../dbconnection.php');
if (strlen($_SESSION['homecare_db'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    // Handle the form submission and file upload
    $catid = $_POST['catid'];
    $maidid = $_POST['maidid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $contno = $_POST['contno'];
    $experience = $_POST['experience'];
    $dob = $_POST['dob'];
    $add = $_POST['add'];
    $desc = $_POST['desc'];

    // Handle file upload
    if (isset($_FILES['idproof']) && $_FILES['idproof']['error'] == 0) {
      $fileName = $_FILES['idproof']['name'];
      $fileTmpName = $_FILES['idproof']['tmp_name'];
      $fileSize = $_FILES['idproof']['size'];
      $fileType = $_FILES['idproof']['type'];

      // Specify upload directory
      $uploadDir = 'images/';
      $newFileName = time() . '_' . $fileName;
      $targetFilePath = $uploadDir . $newFileName;

      // Check if the file is an image (optional validation)
      if (getimagesize($fileTmpName)) {
        // Check if the directory is writable
        if (is_writable($uploadDir)) {
          if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            // File uploaded successfully
            $sql = "INSERT INTO maid (CategoryID, MaidID, Name, Email, Gender, ContactNumber, Experience, DateOfBirth, Address, Description, IDProof) 
                    VALUES (:catid, :maidid, :name, :email, :gender, :contno, :experience, :dob, :add, :desc, :idproof)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':catid', $catid, PDO::PARAM_INT);
            $query->bindParam(':maidid', $maidid, PDO::PARAM_INT);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':gender', $gender, PDO::PARAM_STR);
            $query->bindParam(':contno', $contno, PDO::PARAM_STR);
            $query->bindParam(':experience', $experience, PDO::PARAM_STR);
            $query->bindParam(':dob', $dob, PDO::PARAM_STR);
            $query->bindParam(':add', $add, PDO::PARAM_STR);
            $query->bindParam(':desc', $desc, PDO::PARAM_STR);
            $query->bindParam(':idproof', $newFileName, PDO::PARAM_STR);
            $query->execute();

            echo "<script>alert('Maid added successfully!');</script>";
          } else {
            echo "<script>alert('Error uploading file. Unable to move file.');</script>";
          }
        } else {
          echo "<script>alert('Upload directory is not writable.');</script>";
        }
      } else {
        echo "<script>alert('Uploaded file is not an image.');</script>";
      }
    } else {
      echo "<script>alert('No file uploaded or there was an error.');</script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Homecare || Add Maid</title>
  <style>
    /* Custom Styling for Form */
    .form-container {
      max-width: 800px;
      margin: 30px auto;
      padding: 30px;
      background-color: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    .form-container .form-title {
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
      font-weight: bold;
    }

    .form-container label {
      font-weight: 600;
    }

    .form-container input,
    .form-container select,
    .form-container textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .form-container button {
      background-color: #007bff;
      color: #fff;
      padding: 12px 25px;
      font-size: 16px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: #0056b3;
    }

    .form-container .alert {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      border-left: 5px solid #007bff;
    }
  </style>
</head>

<body class="inner_page general_elements">
  <div class="full_container">
    <div class="inner_container">
      <!-- Sidebar -->
      <?php include_once('sidebar.php'); ?>
      <!-- End Sidebar -->

      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php include_once('header.php'); ?>
        <!-- End Topbar -->

        <!-- Dashboard Content -->
        <div class="midde_cont">
          <div class="container-fluid">
            <div class="row column_title">
              <div class="col-md-12">
                <div class="page_title">
                  <h2>Add Maid</h2>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                  <div class="full graph_head">
                    <div class="heading1 margin_0">
                      <h2>Add Maid</h2>
                    </div>
                  </div>

                  <div class="form-container">
                    <div class="alert alert-primary" role="alert">
                      <form method="post" enctype="multipart/form-data">
                        <div class="form-title">
                          <h3>Enter Maid Information</h3>
                        </div>

                        <div class="form-group">
                          <label for="catid">Category Name</label>
                          <select name="catid" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                            $sql2 = "SELECT * from category";
                            $query2 = $dbh->prepare($sql2);
                            $query2->execute();
                            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                            foreach ($result2 as $row2) {
                            ?>
                              <option value="<?php echo htmlentities($row2->ID); ?>"><?php echo htmlentities($row2->CategoryName); ?></option>
                            <?php } ?>
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="maidid">Maid ID</label>
                          <input type="text" name="maidid" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label for="gender">Gender</label>
                          <select name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="contno">Contact Number</label>
                          <input type="text" name="contno" class="form-control" required maxlength="10" pattern="[0-9]+">
                        </div>

                        <div class="form-group">
                          <label for="experience">Experience</label>
                          <input type="text" name="experience" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label for="dob">Date of Birth</label>
                          <input type="date" name="dob" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label for="add">Address</label>
                          <textarea name="add" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                          <label for="desc">Description (if any)</label>
                          <textarea name="desc" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                          <label for="idproof">ID Proof</label>
                          <input type="file" name="idproof" class="form-control" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Add Maid</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<?php } ?>
