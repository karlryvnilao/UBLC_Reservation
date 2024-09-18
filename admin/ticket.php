<?php
include '../config/connection.php';
include '../session1.php';

try {

    $query = "SELECT * FROM `user_reservation_vehicle`
    WHERE status = 'pending'
    ORDER BY id DESC";

    $stmtTicket = $con->prepare($query);
    $stmtTicket->execute();

} catch(PDOException $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/sidebar.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/font-awesome.min.css">
  <link rel="stylesheet" href="../style/bootstrap-5.3.2-dist/css/line-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
  <title>Document</title>
</head>
<body>
  <div class="main-wrapper">
		
    <?php
    include_once("./components/header.php");
    include_once("./components/sidebar.php");
    ?>

<div class="page-wrapper">
    
    <div class="content container-fluid">
    
        <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Trip Ticket</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Trip Ticket Request</li>
            </ul>
        </div>
        </div>
    </div>
        <!-- /Page Header -->
        <section class="content">
            <!-- Default box -->
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Total Tickets</h3>

                    <div class="card-tools ">
                        <button type="button" class="btn btn-tool" data-toggle="collapse" data-target="#collapseContent" aria-expanded="true">
                        <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Bar -->
                        <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                        </div>
                    <div class="row table-responsive">
                        <table id="all_patients"
                            class="table table-striped dataTable table-bordered dtr-inline"
                            role="grid" aria-describedby="all_patients_info" id="myTable">

                            <thead>
                            <tr>
                                <!-- <th>No.</th> -->
                                <!-- <th>Date Filed</th> -->
                                <th>No.</th>
                                <th>Title</th>
                                <th>Departure</th>
                                <th>Return</th>
                                <th>Purpose</th>
                                <th>Driver</th>
                                <th class="text-center">Action</th>
                                
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $count = 0;
                            while($row =$stmtTicket->fetch(PDO::FETCH_ASSOC)){
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['title'];?></td>
                                    <td><?php echo date('F j, Y', strtotime($row['departure'])); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($row['arrival'])); ?></td>
                                    <td><?php echo $row['purpose_description'];?></td>
                                    <!-- Inside the table body where you want to select the driver -->
                                    <td>
                                        <form action='update/approveTripStatus.php?id=<?php echo $row['id']; ?>' method='POST'>
                                            <select name="driver_id" class="form-select">
                                                <?php
                                                // Fetch list of drivers using PDO
                                                $driverQuery = "SELECT id, name, contact FROM driver";
                                                $driverStmt = $con->query($driverQuery);
                                                while ($driverRow = $driverStmt->fetch(PDO::FETCH_ASSOC)) {
                                                    // Populate the dropdown options with driver names and contacts
                                                    ?>
                                                    <option value="<?php echo $driverRow['id']; ?>">
                                                        <?php echo $driverRow['name'] . ' - ' . $driverRow['contact']; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        <input type="hidden" name="user_email" value="<?php echo $row['user_email']; ?>">
                                        <!-- Pass the trip ticket ID as a hidden input field -->
                                        <input type="hidden" name="trip_id" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td style="text-align: center;">
                                    <a href='update/approveTripStatus.php?id=<?php echo $row['id']; ?>'><button id='statusCancel' class='btn btn-outline-primary '>Approve</button></a>
                                    
                                    </td></form>
                                    <td>
                                    <form action="update/cancelTripStatus.php?id=<?php echo $row['id']; ?>" method="post">
                                        <!-- Other form fields and trip details here -->
                                        <button class='btn btn-outline-primary ' type="submit" name="cancel">Cancel Trip</button>
                                    </form>

                                    </td>
                                    

                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- /.card-footer-->
            </div>
            <!-- /.card -->


        </section>
    
        </div>
    <!-- /Page Wrapper -->
    
      </div>
  <!-- /Main Wrapper -->
  
  <!-- jQuery -->
      <script src="../style/bootstrap-5.3.2-dist/js/jquery-3.2.1.min.js"></script>
  
  <!-- Bootstrap Core JS -->
      <script src="../style/bootstrap-5.3.2-dist/js/popper.min.js"></script>
      <script src="../style/bootstrap-5.3.2-dist/js/bootstrap.bundle.js"></script>
  
  <!-- Slimscroll JS -->
  <script src="../style/bootstrap-5.3.2-dist/js/jquery.slimscroll.min.js"></script>
  
  <!-- Select2 JS -->
  <script src="../style/morris/morris.min.js"></script>
  
  <!-- Custom JS -->
  <script src="../style/bootstrap-5.3.2-dist/js/nav.js"></script>
  
<script>
    // JavaScript for Table Search
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("table tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    });
});

$(function () {
            $("#all_patients").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');

        });



 // Use jQuery to handle form submission
$(document).ready(function() {
    $('#statusForm').submit(function(e) {
      e.preventDefault(); // Prevent the default form submission
    
      // Get the selected status value
    var selectedStatus = $('#statusDropdown').val();
    
      // Send the selected status to the server using AJAX
    $.ajax({
        type: 'POST',
        url: 'process_status.php', // Replace with your PHP script URL
        data: { status: selectedStatus },
        success: function(response) {
        alert('Status successfully submitted: ' + response);
          // You can perform additional actions here based on the server response
        },
        error: function(error) {
        alert('Error submitting status: ' + error);
        }
    });
    });
});

$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
</body>
</html>