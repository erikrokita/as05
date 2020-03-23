<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new volunteer (table: accounts)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
	
require 'database.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$usernameError = null;
	$passwordsError = null;
	$emailError = null;
	$descriptionError = null;
	$adminError = null;
	$pfpError = null; // not used
	
	// initialize $_POST variables
	$username = $_POST['username'];
	$passwords = $_POST['passwords'];
	$passwordshash = MD5($passwords);
	$email = $_POST['email'];
	$description = $_POST['description'];
	$admin =  $_POST['admin'];
	$pfp = $_POST['pfp']; // not used
	
	// initialize $_FILES variables
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];
	$content = file_get_contents($tmpName);

	// validate user input
	$valid = true;
	if (empty($username)) {
		$usernameError = 'Please enter a username';
		$valid = false;
	}
	// do not allow 2 records with same email address!
	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	} 
	
	$pdo = Database::connect();
	$sql = "SELECT * FROM accounts";
	foreach($pdo->query($sql) as $row) {

		if($email == $row['email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();

	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}

	if (empty($description)) {
		$descriptionError = 'Please enter an account bio';
		$valid = false;
	}
	if (empty($passwords)) {
		$passwordsError = 'Please enter valid Password';
		$valid = false;
	}
	if (empty($admin)) {
		$adminError = 'Please enter valid administrator status';
		$valid = false;
	}
	// restrict file types for upload

	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO accounts (username,passwords,email,description,admin,
		filename,filesize,filetype,filecontent) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($username,$passwords,$passwordshash,$email,$admin,
		$fileName,$fileSize,$fileType,$content));
		Database::disconnect();
		header("Location: accounts.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">
    
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Add New Account</h3>
			</div>
	
			<form class="form-horizontal" action="accounts_create.php" method="post" enctype="multipart/form-data">
				
				<div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
					<label class="control-label">Username</label>
					<div class="controls">
						<input name="username" type="text"  placeholder="Username" value="<?php echo !empty($username)?$username:'';?>">
						<?php if (!empty($usernameError)): ?>
							<span class="help-inline"><?php echo $usernameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordsError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="passwords" type="text"  placeholder="Password" value="<?php echo !empty($passwords)?$passwords:'';?>">
						<?php if (!empty($passwordsError)): ?>
							<span class="help-inline"><?php echo $passwordsError;?></span>
						<?php endif;?>
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
				
				<div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					<label class="control-label">Description</label>
					<div class="controls">
						<input name="description" type="text"  placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
						<?php if (!empty($descriptionError)): ?>
							<span class="help-inline"><?php echo $descriptionError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Admin</label>
					<div class="controls">
						<select class="form-control" name="admin">
							<option value="User" selected>User</option>
							<option value="Administrator" >Administrator</option>
						</select>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					<label class="control-label">Profile image</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="userfile" type="file" id="userfile">
					</div>
				</div>
			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="accounts.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>