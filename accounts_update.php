<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_update.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program updates one volunteer's details (table: accounts)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
	
require 'database.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form

	# initialize/validate (same as file: fr_per_create.php)

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

	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	} 

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
	
	if ($valid) { // if valid user input update the database
	
		if($fileSize > 0) { // if file was updated, update all fields
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE accounts  set username = ?, passwords = ?, email = ?, description = ?, admin = ?, filename = ?,filesize = ?,filetype = ?,filecontent = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($username, $passwords, $email, $description, $admin, $fileName,$fileSize,$fileType,$content, $id));
			Database::disconnect();
			header("Location: accounts.php");
		}
		else { // otherwise, update all fields EXCEPT file fields
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE accounts  set username = ?, passwords = ?, email = ?, description = ?, admin = ? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($username, $passwords, $email, $description, $admin,  $id));
			Database::disconnect();
			header("Location: accounts.php");
		}
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM accounts where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$username = $data['username'];
	$passwords = $data['passwords'];
	$email = $data['email'];
	$description = $data['description'];
	$admin =  $data['admin'];
	Database::disconnect();
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
			
			<?php
				require 'functions.php';
				Functions::logoDisplay2();
			?>
		
			<div class="row">
				<h3>Update Account Info</h3>
			</div>
	
			<form class="form-horizontal" action="accounts_update.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">
			
				<!-- Form elements (same as file: fr_per_create.php) -->

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
						<select class="form-control" name="title">
							<?php 
							# editor is a volunteer only allow volunteer option
							if (0==strcmp($_SESSION['account_admin'],'User')) echo '<option selected value="User" >User</option>';
							else if($title==User) echo 
							'<option selected value="User" >User</option><option value="Administrator" >Administrator</option>';
							else echo
							'<option value="User">User</option>
							<option selected value="Administrator" >Administrator</option>';
							?>
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
			
				<!-- Display photo, if any --> 

				<div class='control-group col-md-6'>
					<div class="controls ">
					<?php 
					if ($data['filesize'] > 0) 
						echo '<img  height=5%; width=15%; src="data:image/jpeg;base64,' . 
							base64_encode( $data['filecontent'] ) . '" />'; 
					else 
						echo 'No photo on file.';
					?><!-- converts to base 64 due to the need to read the binary files code and display img -->
					</div>
				</div>
				
		</div><!-- end div: class="span10 offset1" -->
		
    </div> <!-- end div: class="container" -->
	
</body>
</html>