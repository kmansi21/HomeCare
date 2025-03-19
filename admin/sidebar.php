

<head>
    <!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

   <style>
    .sidebar {
        width: 230px;
        background: #343a40;
        color: white;
        height: 100vh;
        padding: 20px;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
    }
    .sidebar h4 {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        padding-bottom: 10px;
        border-bottom: 1px solid #495057;
    }

    .user-profile {
        margin-bottom: 20px;
        flex-direction: column;
    }

    .user-img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .email {
        font-size: 14px;
        color: #ddd;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        padding: 10px;
        transition: 0.3s;
    }

    .sidebar-menu li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 16px;
    }

    .sidebar-menu li i {
        margin-right: 10px;
    }

    .sidebar-menu li:hover {
        background: #495057;
        border-radius: 5px;
    }

    .submenu {
        display: none;
        list-style: none;
        padding-left: 20px;
    }

    .submenu li {
        padding: 8px 0;
    }

    .submenu li a {
        font-size: 15px;
        color: #ccc;
    }

    .submenu li a:hover {
        color: #fff;
    }

    /* Add arrow for collapsed menu */
    .arrow {
        margin-left: auto;
        font-size: 12px;
    }
    
    /* Active menu item style */
    .active > .submenu {
        display: block;
    }

   </style>
</head>

<div class="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>
    <div class="user-profile text-center">
        <img src="../images/admin_img.jpg" alt="Admin" class="user-img">
        <?php
        $aid = $_SESSION['homecare_db'];
        $sql = "SELECT AdminName, Email FROM admin WHERE ID=:aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aid', $aid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $row) { ?>
                <h6><?php echo htmlentities($row->AdminName); ?></h6>
                <p class="email"><?php echo htmlentities($row->Email); ?></p>
            <?php }
        } ?>
    </div>

    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>

        <li class="toggle">
            <a href="#"><i class="fa fa-list"></i> Category <span class="arrow">&#9662;</span></a>
            <ul class="submenu">
                <li><a href="add-category.php">Add</a></li>
                <li><a href="manage-category.php">Manage</a></li>
            </ul>
        </li>

        <li class="toggle">
            <a href="#"><i class="fa fa-user-friends"></i> Maid <span class="arrow">&#9662;</span></a>
            <ul class="submenu">
                <li><a href="add-maid.php">Add Maid</a></li>
                <li><a href="manage-maid.php">Manage Maid</a></li>
            </ul>
        </li>
       
        <li class="toggle">
            <a href="#"><i class="fa fa-file-alt"></i> Maid Hiring Request <span class="arrow">&#9662;</span></a>
            <ul class="submenu">
                <li><a href="new-request.php">New Request</a></li>
                <li><a href="assign-request.php">Assign Request</a></li>
                <li><a href="cancel-request.php">Cancel Request</a></li>
                <li><a href="all-request.php">All Requests</a></li>
            </ul>
        </li>
      

        <li class="toggle">
            <a href="#"><i class="fa fa-search"></i> Search <span class="arrow">&#9662;</span></a>
            <ul class="submenu">
                <li><a href="search-booking-request.php">Booking Request</a></li>
                <li><a href="search-maid.php">Search Maid</a></li>
            </ul>
        </li>

        <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<script>
    // JavaScript to toggle the dropdown visibility when clicked
    document.querySelectorAll('.toggle > a').forEach((item) => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the link from following its href
            const parent = item.parentElement;
            const submenu = parent.querySelector('.submenu');
            
            // Toggle the active class
            parent.classList.toggle('active');
            
            // Optionally, close other submenus
            document.querySelectorAll('.sidebar-menu > li').forEach((other) => {
                if (other !== parent) {
                    other.classList.remove('active');
                }
            });
        });
    });
</script>
