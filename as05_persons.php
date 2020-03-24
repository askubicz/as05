<?php

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['as05_person_id'];
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
			<h3>Customers</h3>
		</div>
		<div class="row">
			<p>
				<?php if($_SESSION['as05_person_title']=='Administrator')
					echo '<a href="as05_per_create.php" class="btn btn-primary">Add Customer</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<a href="as05_persons.php">Customers</a> &nbsp;
				<a href="as05_tours.php">Tours</a> &nbsp;
				<a href="as05_assignments.php">Bookings</a>&nbsp;
				<a href="as05_assignments.php?id=<?php echo $sessionid; ?>">My Reservations</a>&nbsp;
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `as05_persons`.*, COUNT(`as05_assignments`.assign_per_id) AS countAssigns FROM `as05_persons` LEFT OUTER JOIN `as05_assignments` ON (`as05_persons`.id=`as05_assignments`.assign_per_id) GROUP BY `as05_persons`.id ORDER BY `as05_persons`.lname ASC, `as05_persons`.fname ASC';
						//$sql = 'SELECT * FROM as05_persons ORDER BY `as05_persons`.lname ASC, `as05_persons`.fname ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							if ($row['countAssigns'] == 0)
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') '.' - UNASSIGNED</td>';
							else
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') - '.$row['countAssigns']. ' tours</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['mobile'] . '</td>';
							echo '<td width=250>';
							# always allow read
							echo '<a class="btn" href="as05_per_read.php?id='.$row['id'].'">Details</a>&nbsp;';
							# person can update own record
							if ($_SESSION['as05_person_title']=='Administrator'
								|| $_SESSION['as05_person_id']==$row['id'])
								echo '<a class="btn btn-success" href="as05_per_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							# only admins can delete
							if ($_SESSION['as05_person_title']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="as05_per_delete.php?id='.$row['id'].'">Delete</a>';
							if($_SESSION["as05_person_id"] == $row['id']) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>