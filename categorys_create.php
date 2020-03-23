<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new event (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$subjectError = null;
	$descriptionError = null;
	$tagsError = null;
	
	// initialize $_POST variables
	$subject = $_POST['subject'];
	$description = $_POST['description'];
	$tags = $_POST['tags'];		
	
	// validate user input
	$valid = true;
	if (empty($subject)) {
		$subjectError = 'Please enter a subject';
		$valid = false;
	} 		
	if (empty($description)) {
		$descriptionError = 'Please enter content';
		$valid = false;
	}		
	if (empty($tags)) {
		$tagsError = 'Please enter tags';
		$valid = false;
	}

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO category (subject, description, tags) values(?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($subject,$description,$tags));
		Database::disconnect();
		header("Location: categorys.php");
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
		<?php 
			//gets logo
			functions::logoDisplay();
		?>	
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Add New Category</h3>
			</div>
	
			<form class="form-horizontal" action="categorys_create.php" method="posts">
			
			  
				<div class="control-group <?php echo !empty($subjectError)?'error':'';?>">
					<label class="control-label">Subject</label>
					<div class="controls">
						<input name="subject" type="subject" placeholder="Subject" value="<?php echo !empty($subject)?$subject:'';?>">
						<?php if (!empty($subjectError)): ?>
							<span class="help-inline"><?php echo $subjectError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					<label class="control-label">Content</label>
					<div class="controls">
						<input name="description" type="text" placeholder="Content" value="<?php echo !empty($description)?$description:'';?>">
						<?php if (!empty($descriptionError)): ?>
							<span class="help-inline"><?php echo $descriptionError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($tagsError)?'error':'';?>">
					<label class="control-label">Tags</label>
					<div class="controls">
						<input name="tags" type="text" placeholder="Tags" value="<?php echo !empty($tags)?$tags:'';?>">
						<?php if (!empty($tagsError)): ?>
							<span class="help-inline"><?php echo $tagsError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="categorys.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- div: class="container" -->
				
    </div> <!-- div: class="container" -->
	
</body>
</html>