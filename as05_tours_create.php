<?php 

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('destination: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

if ( !empty($_POST)) { // if not first time through

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
		$destinationError = 'Please enter the destination.';
		$valid = false;
	}	
	if (empty($startDate)) {
		$startDateError = 'Please enter the departure date.';
		$valid = false;
	}
	if (empty($endDate)) {
		$endDateError = 'Please enter the return date.';
		$valid = false;
	}
	if (empty($time)) {
		$timeError = 'Please enter the departure time.';
		$valid = false;
	} 		
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO as05_tours (tours_destination, tours_startDate, tours_endDate, tours_time) values(?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($destination,$startDate,$endDate,$time));
		Database::disconnect();
		header("destination: as05_tours.php");
	}
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
				<h3>Add New Tour</h3>
			</div>
	
			<form class="form-horizontal" action="as05_tours_create.php" method="post">
			
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
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="as05_tours.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- div: class="container" -->
				
    </div> <!-- div: class="container" -->
	
</body>
</html>