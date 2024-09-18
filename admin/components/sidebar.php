<style>
	a {
  text-decoration: none !important;
}
</style>




<div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li class="menu-title">
								<span>Main</span>
							</li>
							<li>
								<a href="dashboard.php"><i class="fa fa-tachometer-alt"></i><span>Dashboard</span></a>
							</li>
							
							
							<li class="menu-title">
								<span>Tickets</span>
							</li>
							<li>
								<a href="ticket.php"><i class="fa fa-ticket-alt"></i><span>Trip Ticket Request</span></a>
							</li>
							<li class="submenu">
								<a href="#" ><i class="fa fa-list-alt"></i> <span> Finalize</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="approveRequest.php"><span>Approved Request</span></a></li>
									<li><a href="cancelRequest.php"><span>Cancelled Request</span></a></li>
								</ul>
							</li>

							<li class="menu-title">
								<span>SMS</span>
							</li>
							<li>
								<a href="driver.php"><i class="fa fa-ticket-alt"></i><span>Send</span></a>
							</li>

							
							<li class="menu-title">
								
								<span>Pages</span>
							</li>
							<li>
								<a href="#" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-user-plus"></i><span>Add Driver</span></a>
							</li>
							<li>
								<a href="manage_driver.php"><i class="fa fa-users"></i> <span>Drivers</span></a>
							</li>
							<li>
								<a href="users.php"><i class="fa fa-users"></i> <span>Users</span></a>
							</li>
							<li>
								<a href="/UBLC_Reservation-master/logout.php"><i class="fa fa-power-off"></i> <span>Logout</span></a>
							</li>
									
						</ul>
					</div>
                </div>
            </div>

			<!-- modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Add Driver</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="addDriverForm" method="post">
								<div class="form-group">
									<label for="email">Name:</label>
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
								<div class="form-group">
									<label for="email">Password:</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-key"></i></span>
										</div>
										<input type="text" class="form-control" id="password" name="password" placeholder="Enter Password" required>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" id="addDriverButton" class="btn btn-primary form-control">Add</button>
						</div>
					</div>
				</div>
			</div>

			<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
			<script>
    $(document).ready(function () {
        $('#addDriverButton').click(function () {
            // Submit form data via AJAX
            $.ajax({
                type: 'POST',
                url: 'add_driver.php', // Replace 'your_php_script.php' with the filename of your PHP script
                data: $('#addDriverForm').serialize(),
                success: function (response) {
                    // Display the response from the server
                    alert(response);
                },
                error: function (xhr, status, error) {
                    // Display an error message if the AJAX request fails
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>