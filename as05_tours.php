<?php

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['as05_person_id'];
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body style="background-color: white !important";>
    <div class="container">
		<div class="row">
			<h3>Tours</h3>
		</div>
		
		<div class="row">
			<p>
				<?php if($_SESSION['as05_person_title']=='Administrator')
					echo '<a href="as05_tours_create.php" class="btn btn-primary">Create Tour</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['as05_person_title']=='Administrator')
					echo '<a href="as05_persons.php">Customers</a> &nbsp;';
				?>
				<a href="as05_tours.php">Tours</a> &nbsp;
				<?php if($_SESSION['as05_person_title']=='Administrator')
					echo '<a href="as05_assignments.php">Bookings</a>&nbsp;';
				?>
				<a href="as05_assignments.php?id=<?php echo $sessionid; ?>">My Reservations</a>&nbsp;
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Tour</th>
						<th>Time</th>
						<th>Departure Date</th>
						<th>Return Date</th>
						<th>Reservation Number</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `as05_tours`.*, SUM(case when assign_per_id ='. $_SESSION['as05_person_id'] .' then 1 else 0 end) AS sumAssigns, COUNT(`as05_assignments`.assign_tours_id) AS countAssigns FROM `as05_tours` LEFT OUTER JOIN `as05_assignments` ON (`as05_tours`.id=`as05_assignments`.assign_tours_id) GROUP BY `as05_tours`.id ORDER BY `as05_tours`.tours_date ASC, `as05_tours`.tours_time ASC';
						
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. $row['tours_destination'] . '</td>';
							echo '<td>'. Functions::timeAmPm($row['tours_time']) . '</td>';
							echo '<td>'. Functions::dayMonthDate($row['tours_startDate']) . '</td>';
							echo '<td>'. Functions::dayMonthDate($row['tours_endDate']) . '</td>'

							if ($row['countAssigns']==0)
								echo '<td>No Reservations Currently </td>';
							else
								echo '<td>'. $row['countAssigns']. ' Reservations Booked)' . '</td>';
							echo '<td>';
							echo '<a class="btn" href="as05_tours_read.php?id='.$row['id'].'">Details</a> &nbsp;';
							if ($_SESSION['as05_person_title']=='Customer' )
								echo '<a class="btn btn-primary" href="as05_tours_read.php?id='.$row['id'].'">Customer</a> &nbsp;';
							if ($_SESSION['as05_person_title']=='Administrator' )
								echo '<a class="btn btn-success" href="as05_tours_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							if ($_SESSION['as05_person_title']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="as05_tours_delete.php?id='.$row['id'].'">Delete</a>';
							if($row['sumAssigns']==1) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
    	</div>
	
    </div> <!-- end div: class="container" -->
	
  </body>
  
</html>