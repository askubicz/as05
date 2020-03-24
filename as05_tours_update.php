<?php 

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('destination: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form

	# initialize/validate (same as file: as05_tours_create.php)

	// initialize user input validation variables
	$destinationError = null;
	$startDateError = null;
	$endDateError = null;
	$timeError = null;
	
	// initialize $_POST variables
	$destination = $_POST['tours_destination'];
	$startDate = $_POST['tours_startDate'];
	$endDate = $_POST['tours_endDate'];
	$time = $_POST['tours_time'];
	
	// validate user input
	$valid = true;
	if (empty($destination)) {
		$destinationError = 'Please enter destination';
		$valid = false;
	}	
	if (empty($startDate)) {
		$startDateError = 'Please enter date of departure';
		$valid = false;
	}
	if (empty($endDate)) {
		$endDateError = 'Please enter date of return';
		$valid = false;
	}
	if (empty($time)) {
		$timeError = 'Please enter time of departure';
		$valid = false;
	}
	
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE as05_tours  set tours_destination = ?, tours_startDate = ?, tours_endDate = ?, tours_time = ? = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($destination,$startDate,$endDate,$time,$id));
		Database::disconnect();
		header("location: as05_tours.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM as05_tours where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$destination = $data['tours_destination'];
	$startDate = $data['tours_startDate'];
	$endDate = $data['tours_endDate'];
	$time = $data['tours_time'];
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
				<h3>Update Tour Details</h3>
			</div>
	
			<form class="form-horizontal" action="as04_tours_update.php?id=<?php echo $id?>" method="post">
			
			<div class="control-group <?php echo !empty($destinationError)?'error':'';?>">
					<label class="control-label">Destination</label>
					<div class="controls">
						<input name="tours_destination" type="text" placeholder="Destination" value="<?php echo !empty($destination)?$destination:'';?>">
						<?php if (!empty($destinationError)): ?>
							<span class="help-inline"><?php echo $destinationError;?></span>
						<?php endif;?>
					</div>
				</div>

				<div class="control-group <?php echo !empty($startDateError)?'error':'';?>">
					<label class="control-label">Departure Date</label>
					<div class="controls">
						<input name="tours_startDate" type="date"  placeholder="Departure Date" value="<?php echo !empty($startDate)?$startDate:'';?>">
						<?php if (!empty($startDateError)): ?>
							<span class="help-inline"><?php echo $startDateError;?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="control-group <?php echo !empty($endDateError)?'error':'';?>">
					<label class="control-label">Return Date</label>
					<div class="controls">
						<input name="tours_endDate" type="date"  placeholder="Return Date" value="<?php echo !empty($endDate)?$endDate:'';?>">
						<?php if (!empty($endDateError)): ?>
							<span class="help-inline"><?php echo $endDateError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					<label class="control-label">Departure Time</label>
					<div class="controls">
						<input name="tours_time" type="time" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
						<?php if (!empty($timeError)): ?>
							<span class="help-inline"><?php echo $timeError;?></span>
						<?php endif;?>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="as05_tours.php">Back</a>
				</div>
				
			</form>
			
		</div><!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
</body>
</html>