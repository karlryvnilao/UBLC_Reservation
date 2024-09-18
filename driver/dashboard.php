<?php 
include '../config/connection.php';
include '../session1.php';


// Function to fetch count based on a given SQL query
function getCount($con, $sql) {
  $result = $con->query($sql);

  if ($result === FALSE) {
      echo "Error: " . $con->errorInfo()[2];
      exit;
  }

  return $result->fetchColumn();
}

// Get the current date
$currentDate = date("Y-m-d");

// Get count for today
$sqlToday = "SELECT COUNT(*) FROM user_reservation_vehicle WHERE DATE(reservation_date) = '$currentDate'";
$todayCount = getCount($con, $sqlToday);

// Get count for this week
$sqlWeek = "SELECT COUNT(*) FROM user_reservation_vehicle WHERE YEARWEEK(reservation_date) = YEARWEEK(NOW())";
$weekCount = getCount($con, $sqlWeek);

// Get count for this month
$sqlMonth = "SELECT COUNT(*) FROM user_reservation_vehicle WHERE MONTH(reservation_date) = MONTH(NOW()) AND YEAR(reservation_date) = YEAR(NOW())";
$monthCount = getCount($con, $sqlMonth);

// Get count for this year
$sqlYear = "SELECT COUNT(*) FROM user_reservation_vehicle WHERE YEAR(reservation_date) = YEAR(NOW())";
$yearCount = getCount($con, $sqlYear);


$sqlDrivers = "SELECT driver_name,id, location, COUNT(*) as reservation_count FROM user_reservation_vehicle WHERE driver_name IS NOT NULL GROUP BY driver_name";
$stmt = $con->query($sqlDrivers);
$driversData = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sqlLocationArea = "SELECT lat.label, COUNT(*) as area_count
                   FROM user_reservation_vehicle urv
                   INNER JOIN location_area_types lat ON urv.location_area = lat.id
                   GROUP BY lat.label";
$stmtLocationArea = $con->query($sqlLocationArea);
$locationAreaData = $stmtLocationArea->fetchAll(PDO::FETCH_ASSOC);
// Close the database connection
//$con = null;
?>

<!-- Now use $todayCount, $weekCount, $monthCount, $yearCount in your HTML code -->



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/sidebar.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/font-awesome.min.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/line-awesome.min.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/select2.min.css">
  <link rel="stylesheet" href="../style/morris/morris.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
  <title>Document</title>
</head>
<body>
  <!-- Main Wrapper -->
  <div class="main-wrapper">
		
    <!-- Header -->
          <?php include_once("components/header.php");?>
    <!-- /Header -->
    
    <!-- Sidebar -->
          <?php include_once("components/sidebar.php");?>
    <!-- /Sidebar -->
    
    <!-- Page Wrapper -->
          <div class="page-wrapper">
    
      <!-- Page Content -->
              <div class="content container-fluid">
      
        <!-- Page Header -->
        <div class="page-header">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="page-title">Clients</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Clients</li>
              </ul>
            </div>
          </div>
        </div>
        <!-- /Page Header -->
        <section class="content">
        <div class="container-fluid">
              
                <div class="row mt-5">
                        <?php foreach ($driversData as $driver): ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-secondary text-light p-3">
                                    <div class="inner">
                                        <h3><?php echo $driver['reservation_count']; ?></h3>
                                        <p><?php echo $driver['driver_name']; ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user"> Driver</i>
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#driverModal<?php echo $driver['id']; ?>">
                                            View Information
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="driverModal<?php echo $driver['id']; ?>" tabindex="-1" aria-labelledby="driverModalLabel<?php echo $driver['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="driverModalLabel<?php echo $driver['id']; ?>">Locations for <?php echo $driver['driver_name']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul>
                                            <p>Name: <?php echo $driver['driver_name']; ?></p>
                                            <h5>List of Locations</h5>
                                                <?php
                                                // Query to fetch locations for this driver
                                                $sqlDriverLocations = "SELECT location FROM user_reservation_vehicle WHERE driver_name = :driverName AND location IS NOT NULL";
                                                $stmtDriverLocations = $con->prepare($sqlDriverLocations);
                                                $stmtDriverLocations->bindParam(':driverName', $driver['driver_name']);
                                                $stmtDriverLocations->execute();
                                                $driverLocations = $stmtDriverLocations->fetchAll(PDO::FETCH_COLUMN);
                                                foreach ($driverLocations as $location):
                                                ?>
                                                    <li><?php echo $location; ?></li>
                                                    
                                                <?php endforeach; ?>
                                                
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="printModalContent('driverModal<?php echo $driver['id']; ?>')">Print</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>



                    
                </div>
            
        </section>
    
        </div>
    <!-- /Page Wrapper -->
    
    </div>
    <script>
    function printModalContent(modalId) {
        var content = document.getElementById(modalId).querySelector('.modal-content');
        var printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print</title></head><body>');
        printWindow.document.write(content.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script src="../style/bootstrap-5.3.2-dist/js/jquery-3.2.1.min.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/popper.min.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/bootstrap.bundle.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/jquery.slimscroll.min.js"></script>
<script src="../style/morris/morris.min.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/nav.js"></script>

</body>
</html>
