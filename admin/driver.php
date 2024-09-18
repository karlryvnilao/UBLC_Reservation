<?php
use ClickSend\Model\SmsMessage;
require_once '../vendor/autoload.php';
include '../config/connection.php';
include '../session1.php';
// Configure HTTP basic authorization: BasicAuth
// username: hiruzen2497
// password: 9DA29B8C-B496-760D-BF13-B5E2B7825BAD
$config = ClickSend\Configuration::getDefaultConfiguration()
    ->setUsername('karlkarl@karl.com')
    ->setPassword('DCA954AB-10DD-8824-2390-3A0BB2AC9876');
$apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(),$config);

if(isset($_POST['sendMessageBtn'])) {

    $patientNumber = $_POST['messagePhoneNumber'];
    $drivername = $_POST['messageDriverName'];
    $location = $_POST['messageLocation'];
    $arrival = $_POST['messageArrival'];





    try {
        $msg = new SmsMessage();
        $msg->setBody("Hello Mr {$drivername}, You have Trip Tomorrow to: '{$location}'" .
        "You are expected to be in UB Main: '{$arrival}'");
        $msg->setTo($patientNumber);
        $msg->setSource("sdk");
// \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
        $sms_messages = new \ClickSend\Model\SmsMessageCollection();
        $sms_messages->setMessages([$msg]);
        $result = $apiInstance->smsSendPost($sms_messages);

        echo "<script>alert('Message has been sent.');</script>";
    } catch(PDOException $e) {
        echo $e->getMessage();
        echo "<script>alert('$message');</script>";

    }

    header("Location:congratulation.php?goto_page=driver.php&message=$message");
    exit;
}





try {
    $query = "SELECT urv.*, d.name 
              FROM `user_reservation_vehicle` urv 
              INNER JOIN `ublc_department` d ON urv.department_id = d.id 
              WHERE urv.status = 'approved'";
    $stmtVehicle = $con->prepare($query);
    $stmtVehicle->execute();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-Zz1TI7qqu+K5SslEvopUc1fSmm9PoX0GpRPUD9LxYzDF0OM0+Ue8YMaI5xjo5oq0" crossorigin="anonymous">

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
            <h3 class="page-title">Approved Ticket</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Approved Ticket</li>
            </ul>
        </div>
        </div>
    </div>
        <!-- /Page Header -->
        <section class="content">
            <!-- Default box -->
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Total Approved Tickets</h3>

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
                                <th>No.</th>
                                <!-- <th>Date Filed</th> -->
                                <th>Department</th>
                                <th>Departure</th>
                                <th>Return</th>
                                <th>Purpose</th>
                                <th>Driver Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $count = 0;
                            while($row =$stmtVehicle->fetch(PDO::FETCH_ASSOC)){
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <!-- <td><?php //echo $row['date_filed'];?></td> -->
                                    <td><?php echo $row['name'];?></td>
                                    <td><?php echo $row['arrival'];?></td>
                                    <td><?php echo $row['departure'];?></td>
                                    <td><?php echo $row['purpose_description'];?></td>
                                    <td><?php echo $row['driver_name'];?></td>
                                    <td style="text-align: center">
                                        <form action="" method="POST">
                                            <input type="hidden" value="<?=$row['driver_contact'];?>" name="messagePhoneNumber">
                                            <input type="hidden" value="<?=$row['driver_name'];?>" name="messageDriverName">
                                            <input type="hidden" value="<?=$row['location'];?>" name="messageLocation">
                                            <input type="hidden" value="<?=$row['arrival'];?>" name="messageArrival">
                                            <button type="submit" name="sendMessageBtn" class="btn btn-danger" title="Send emergency message"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </button>
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

            </div>


        </section>
    
        </div>
    
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