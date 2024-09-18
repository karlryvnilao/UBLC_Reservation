<?php
include '../config/connection.php';
include '../session1.php';





try {

    $query = "SELECT * FROM `driver`";

    $stmtDriver = $con->prepare($query);
    $stmtDriver->execute();

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
   <style>
        .card {
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  .card-header {
    background-color: #007bff;
    color: #fff;
    border-radius: 10px 10px 0 0;
  }
  .card-body {
    padding: 20px;
  }
  .card-title {
    font-size: 18px;
    margin-bottom: 10px;
  }
  .card-text {
    font-size: 16px;
    margin-bottom: 5px;
  }
  .btn-tool {
    color: #fff;
  }
   /* Yellow stars */
   .fa-star, .fa-star-half-alt {
    color: #ffc107;
  }
    </style>
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
                                <th>Name</th>
                                <th>Contact</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $count = 0;
                            while($row =$stmtDriver->fetch(PDO::FETCH_ASSOC)){
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <!-- <td><?php //echo $row['date_filed'];?></td> -->
                                    <td><?php echo $row['name'];?></td>
                                    <td><?php echo $row['contact'];?></td>
                                    <td class="px-2 py-1 align-middle text-center">
                                    <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#form_update<?php echo $row['id']?>">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#form_delete<?php echo $row['id']?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                </td>
                                <!-- Modal -->
                                <div class="modal fade" id="form_update<?php echo $row['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update <?php echo $row['name']?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="update/update_driver.php" method="post">
                                                    <div class="form-group">
                                                        <label for="name">Name:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Driver's Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Contact:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter Driver's Contact" maxlength="11" required>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="update">Update</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="form_delete<?php echo $row['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Delete template</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete <?php echo $row['name']?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <form method="POST" action="delete/delete_driver.php">
                                                                        <input type="hidden" name="form_id" value="<?php echo $row['id']?>"/>
                                                                        <input type="hidden" name="form_name" value="<?php echo $row['name']?>"/>
                                                                        <button type="submit" class="btn btn-danger" name="form_remove">Continue</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

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
        <section class="content">
        <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Ratings</h3>

                    <div class="card-tools ">
                        <button type="button" class="btn btn-tool" data-toggle="collapse" data-target="#collapseContent" aria-expanded="true">
                        <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
            <?php
            function displayStars($rating) {
                $stars = '';
                // Round the rating to the nearest 0.5
                $rounded_rating = round($rating * 2) / 2;
                // Maximum number of stars
                $max_stars = 5;
                // Fill the stars based on the rating
                for ($i = 1; $i <= $max_stars; $i++) {
                    if ($rounded_rating >= $i) {
                        $stars .= '<i class="fas fa-star"></i>'; // Full star
                    } elseif ($rounded_rating + 0.5 == $i) {
                        $stars .= '<i class="fas fa-star-half-alt"></i>'; // Half star
                    } else {
                        $stars .= '<i class="far fa-star"></i>'; // Empty star
                    }
                }
                return $stars;
            }
            
            try {
                // SQL query to select data from the ratings table and join with the users and drivers tables
                $query = "SELECT ratings.*, users.name AS user_name, driver.name AS driver_name 
                          FROM ratings 
                          INNER JOIN users ON ratings.user_id = users.id
                          INNER JOIN driver ON ratings.driver_id = driver.id";
            
                // Prepare the query
                $stmt = $con->prepare($query);
            
                // Execute the query
                $stmt->execute();
            
                // Fetch all rows of the result as an associative array
                $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                // Check if there are any ratings
                if ($stmt->rowCount() > 0) {
                    ?>
                    <div class="row">
                    <?php
                    // Output card bodies for each rating
                    foreach ($ratings as $rating) {
                        ?>
                        <div class="col-md-6 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">User Name: <?php echo $rating['user_name']; ?></p>
                                    <p class="card-text">Driver Name: <?php echo $rating['driver_name']; ?></p>
                                    <p class="card-text">Rating: <?php echo displayStars($rating['rating']); ?></p>
                                    <p class="card-text">Comment: <?php echo $rating['comment']; ?></p>
                                    <p class="card-text">Created At: <?php echo $rating['created_at']; ?></p>
                                </div>
                            </div>
                            <br>
                        </div>
                        <?php
                    }
                    ?>
                    </div> <!-- .row -->
                    <?php
                } else {
                    // No ratings found
                    echo "<p>No ratings found.</p>";
                }
            } catch (PDOException $e) {
                // Handle PDO exceptions
                echo "Error: " . $e->getMessage();
            }
            ?>
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

</script>
</body>
</html>