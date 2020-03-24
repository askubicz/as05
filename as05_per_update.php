<?php 

session_start();
if(!isset($_SESSION["as05_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
	
require 'database.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form

	# initialize/validate (same as file: as05_per_create.php)

	// initialize user input validation variables
	$fnameError = null;
	$lnameError = null;
	$emailError = null;
	$mobileError = null;
	$passwordError = null;
	$titleError = null;
	
	// initialize $_POST variables
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];
	$password = $_POST['password'];
	$title =  $_POST['title'];
	
	// validate user input
	$valid = true;
	if (empty($fname)) {
		$fnameError = 'Please enter first name';
		$valid = false;
	}
	if (empty($lname)) {
		$lnameError = 'Please enter last name';
		$valid = false;
	}

	if (empty($email)) {
		$emailError = 'Please enter valid email address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid email address';
		$valid = false;
	}

	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'Email address can contain only lower case letters';
		$valid = false;
	}

	if (empty($mobile)) {
		$mobileError = 'Please enter mobile number (or "none")';
		$valid = false;
	}
	if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $mobile)) {
		$mobileError = 'Please write mobile number in form XXX-XXX-XXXX';
		$valid = false;
	}
	if (empty($password)) {
		$passwordError = 'Please enter valid password';
		$valid = false;
	}
	if (empty($title)) {
		$titleError = 'Please enter valid title';
		$valid = false;
	}
	// restrict file types for upload
	
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE as05_persons  set fname = ?, lname = ?, email = ?, mobile = ?, password = ?, title = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($fname, $lname, $email, $mobile, $password, $title,  $id));
		Database::disconnect();
		header("Location: as05_persons.php");
	}
	else { // if $_POST NOT filled then pre-populate the form
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM as05_persons where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$fname = $data['fname'];
		$lname = $data['lname'];
		$email = $data['email'];
		$mobile = $data['mobile'];
		$password = $data['password'];
		$title =  $data['title'];
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
					<h3>Update Account Details</h3>
				</div>

				<form class="form-horizontal" action="as05_per_update.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">

					<!-- Form elements (same as file: as04_per_create.php) -->

					<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
						<label class="control-label">First Name</label>
						<div class="controls">
							<input name="fname" type="text"  placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
							<?php if (!empty($fnameError)): ?>
								<span class="help-inline"><?php echo $fnameError;?></span>
							<?php endif; ?>
						</div>
					</div>
								
					<div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
						<label class="control-label">Last Name</label>
						<div class="controls">
							<input name="lname" type="text"  placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
							<?php if (!empty($lnameError)): ?>
								<span class="help-inline"><?php echo $lnameError;?></span>
							<?php endif; ?>
						</div>
					</div>
				
					<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
						<label class="control-label">Email</label>
						<div class="controls">
							<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
							<?php if (!empty($emailError)): ?>
								<span class="help-inline"><?php echo $emailError;?></span>
							<?php endif;?>
						</div>
					</div>
								
					<div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
						<label class="control-label">Mobile Number</label>
						<div class="controls">
							<input name="mobile" type="text"  placeholder="Mobile Phone Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
							<?php if (!empty($mobileError)): ?>
								<span class="help-inline"><?php echo $mobileError;?></span>
							<?php endif;?>
						</div>
					</div>
				
					<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">	
						<label class="control-label">Password</label>
						<div class="controls">
							<input id="password" name="password" type="text"  placeholder="Password" value="<?php echo !empty($password)?$password:'';?>">
							<?php if (!empty($passwordError)): ?>
								<span class="help-inline"><?php echo $passwordError;?></span>
							<?php endif;?>
						</div>
					</div>

					<div class="controls">
						<select class="form-control" name="title">
								<?php 
								if (0==strcmp($_SESSION['as05_person_title'],'Customer')) echo '<option selected value="Customer" >Customer</option>';
								else if($title==Customer) echo 
								'<option selected value="Customer" >Customer</option><option value="Administrator" >Administrator</option>';
								else echo
								'<option value="Customer">Customer</option>
								<option selected value="Administrator" >Administrator</option>';
								?>
							</select>
						</div>
					</div>
				
				</form>
				
			</div><!-- end div: class="span10 offset1" -->
								
	    </div> <!-- end div: class="container" -->
								
	</body>
</html>