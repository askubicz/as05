<?php 

session_start();
if(!isset($_SESSION["as04_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM as05_tourss where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
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
				<h3>Tour Details</h3>
			</div>
			
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
					<label class="control-label">Time</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo Functions::timeAmPm($data['tours_time']);?>
						</label>
					</div>
				</div>
				
				<div class="form-actions">
					<a class="btn btn-primary" href="as05_assign_create.php?tours_id=<?php echo $id; ?>">Register for This Tour</a>
					<a class="btn" href="as04_tourss.php">Back</a>
				</div>
				
			<div class="row">
				<h4>Customers Signed Up For This Tour</h4>
			</div>
			
			<?php
				$pdo = Database::connect();
				$sql = "SELECT * FROM as05_assignments, as05_persons WHERE assign_per_id = as05_persons.id AND assign_tours_id = " . $data['id'] . ' ORDER BY lname ASC, fname ASC';
				$countrows = 0;
				if($_SESSION['as05_person_title']=='Administrator') {
					foreach ($pdo->query($sql) as $row) {
						echo $row['lname'] . ', ' . $row['fname'] . ' - ' . $row['mobile'] . '<br />';
					$countrows++;
					}
				}
				else {
					foreach ($pdo->query($sql) as $row) {
						echo $row['lname'] . ', ' . $row['fname'] . ' - ' . '<br />';
					$countrows++;
					}
				}
				if ($countrows == 0) echo 'none.';
			?>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>