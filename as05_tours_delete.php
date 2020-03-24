<?php 

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM as05_tours  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: as05_tours.php");
	
} 
else { // otherwise, pre-populate fields to show data to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM as05_tours where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
}
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

		<div class="span10 offset1">
		
			<div class="row">
				<h3>Delete Tour</h3>
			</div>
			
			<form class="form-horizontal" action="as05_tours_delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to delete?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn" href="as05_tours.php">No</a>
				</div>
			</form>
			
			<!-- Display same information as in file: as05_tours_read.php -->
			
			<div class="form-horizontal" >

				<div class="control-group">
					<label class="control-label">Destination</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['tours_destination'];?>
						</label>
					</div>
				</div>
			
				<div class="control-group">
					<label class="control-label">Departure Date</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo Functions::dayMonthDate($data['tours_startDate']);?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Return Date</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo Functions::dayMonthDate($data['tours_endDate']);?>
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Depature Time</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo Functions::timeAmPm($data['tours_time']);?>
						</label>
					</div>
				</div>
			
			<div class="row">
				<h4>Customers Registered for This Tour</h4>
			</div>
			
			<?php
				$pdo = Database::connect();
				$sql = "SELECT * FROM as05_assignments, as05_persons WHERE assign_per_id = as05_persons.id AND assign_tours_id = " . $data['id'] . ' ORDER BY lname ASC, fname ASC';
				$countrows = 0;
				foreach ($pdo->query($sql) as $row) {
					echo $row['lname'] . ', ' . $row['fname'] . ' - ' . $row['mobile'] . '<br />';
					$countrows++;
				}
				if ($countrows == 0) echo 'none.';
			?>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>