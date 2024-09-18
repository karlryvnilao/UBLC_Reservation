<?php
session_start();
include '../session1.php';
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
                                <th>Title</th>
                                <th>Departure</th>
                                <th>Return</th>
                                <th>Purpose</th>
                                <th class="text-center">Status</th>
                                
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "ublc_reservation";
                            
                            $conn = mysqli_connect($servername, $username, $password, $dbname);
                            
                            if(!$conn){
                                die("Connection failed"). mysqli_connect_error();
                            }
                            // $user = $_SESSION['user'];
                            
                            $sql = "SELECT * FROM `user_reservation_vehicle` WHERE STATUS = 'pending'";
                            $result = mysqli_query($conn, $sql);
                            $count = 0;
                            if(mysqli_num_rows($result)>0){
                                while($row = mysqli_fetch_assoc($result)){
                                    $dep = $row['title'];
                                    $departure = $row['departure'];
                                    $arrival = $row['arrival'];
                                    $purp = $row['purpose_description'];
                                    echo "<tr>
                                    <td>$dep</td>
                                    <td>$departure</td>
                                    <td>$arrival</td>
                                    <td>$purp</td>
                                    <td>
                                        <a href='update/cancelTripStatus.php?userId=$row[id]'><button id='statusCancel' class='btn btn-outline-danger'>Cancel Ticket</button></a>
                                        <a href='update/approveTripStatus.php?userId=$row[id]'><button id='statusCancel' class='btn btn-outline-primary'>Approve Ticket</button></a>
                                    </td>
                                    </tr>";
                                }
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
<script src="../style/bootstrap-5.3.2-dist/js/jquery-3.2.1.min.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/popper.min.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/bootstrap.bundle.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/jquery.slimscroll.min.js"></script>
<script src="../style/morris/morris.min.js"></script>
<script src="../style/bootstrap-5.3.2-dist/js/nav.js"></script>

<script>
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