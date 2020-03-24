<?php 

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$personid = $_SESSION["as05_person_id"];
$toursid = $_GET['tours_id'];

require 'database.php';
require 'functions.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$personError = null;
	$toursError = null;
	
	// initialize $_POST variables
	$person = $_POST['person'];    // same as HTML name= attribute in put box
	$tours = $_POST['tours'];
	
	// validate user input
	$valid = true;
	if (empty($person)) {
		$personError = 'Please choose someone';
		$valid = false;
	}
	if (empty($tours)) {
		$toursError = 'Please choose a tour';
		$valid = false;
	} 
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO as05_assignments 
			(assign_per_id,assign_tours_id) 
			values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$tours));
		Database::disconnect();
		header("Location: as05_assignments.php");
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
				<h3>Sign Someone Up for a Tour</h3>
			</div>
	
			<form class="form-horizontal" action="as05_assign_create.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Customer</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM as05_persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='person' id='person_id'>";
							if($toursid) // if $_GET exists restrict person options to logged in user
								foreach ($pdo->query($sql) as $row) {
									if($personid==$row['id'])
										echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">Tour</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM as05_tours ORDER BY tours_destination ASC, tours_startDate ASC';
							echo "<select class='form-control' name='tours' id='tours_id'>";
							if($toursid) // if $_GET exists restrict tour options to selected tour (from $_GET)
								foreach ($pdo->query($sql) as $row) {
									if($toursid==$row['id'])
										echo "<option value='" . $row['id'] . " '> " . $row['destination'] . ": " . Functions::dayMonthDate($row['startDate']) . "</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " . $row['destination'] . ": " . Functions::dayMonthDate($row['startDate']) . "</option>";
								}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="as05_assignments.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
		<?php 
			//gets logo
			functions::logoDisplay();
		?>	
    </div> <!-- end div: class="container" -->

  </body>
</html>