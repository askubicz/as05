<?php 

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
$id = $_GET['id']; // for MyAssignments
$sessionid = $_SESSION['as05_person_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
		<div class="row">
			<h3><?php if($id) echo 'My'; ?>Bookings</h3>
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					if($id) 
						$sql = "SELECT * FROM as05_assignments 
						LEFT JOIN as05_persons ON as05_persons.id = as05_assignments.assign_per_id 
						LEFT JOIN as05_tours ON as05_tours.id = as05_assignments.assign_tours_id
						WHERE as05_persons.id = $id 
						ORDER BY tours_date ASC, tours_time ASC, lname ASC, lname ASC;";
					else
						$sql = "SELECT * FROM as05_assignments 
						LEFT JOIN as05_persons ON as05_persons.id = as05_assignments.assign_per_id 
						LEFT JOIN as05_tours ON as05_tours.id = as05_assignments.assign_tours_id
						ORDER BY tours_date ASC, tours_time ASC, lname ASC, lname ASC;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. $row['tours_destination'] . '</td>';
						echo '<td>'. Functions::timeAmPm($row['tours_time']) . '</td>';
						echo '<td>'. Functions::dayMonthDate($row['tours_startDate']) . '</td>';
						echo '<td>'. Functions::dayMonthDate($row['tours_endDate']) . '</td>'
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="as05_assign_read.php?id='.$row[0].'">Details</a>';
						if ($_SESSION['as05_person_title']=='Administrator' )
							echo '&nbsp;<a class="btn btn-success" href="as05_assign_update.php?id='.$row[0].'">Update</a>';
						if ($_SESSION['as05_person_title']=='Administrator' 
							|| $_SESSION['as05_person_id']==$row['assign_per_id'])
							echo '&nbsp;<a class="btn btn-danger" href="as05_assign_delete.php?id='.$row[0].'">Delete</a>';
						if($_SESSION["as05_person_id"] == $row['assign_per_id']) 		echo " &nbsp;&nbsp;Me";
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