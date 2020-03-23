<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_read.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays one volunteer's details (table: accounts)
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

$id = $_GET['id'];

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM accounts where id = ?";
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
		<link rel="icon" href="cardinal_logo.png" type="image/png" />
	</head>

	<body>
		<div class="container">
			<?php
				Functions::logoDisplay2();
			?>
			<div class="row">
				<h3>View Account Info</h3>
			</div>
			 
			<div class="form-horizontal" >
				
				<div class="control-group col-md-6">
					
					<label class="control-label">Username</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['username'];?> 
						</label>
					</div>
					
					<label class="control-label">Email</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['email'];?>
						</label>
					</div>
					
					<label class="control-label">Bio</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['description'];?>
						</label>
					</div>     
					
					<label class="control-label">Account type</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['admin'];?>
						</label>
					</div>   
					
					<!-- password omitted on Read/View -->
					
					<div class="form-actions">
						<a class="btn" href="accounts.php">Back</a>
					</div>
					
				</div>
				
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
				
				<div class="row">
					<h4>Events for which this Account has been assigned</h4>
				</div>
				
				<?php
					$pdo = Database::connect();
					$sql = "SELECT * FROM posts, categorys WHERE categoryID = categorys.id AND accountsID = " . $id . " ORDER BY date ASC, time ASC";
					$countrows = 0;
					foreach ($pdo->query($sql) as $row) {
						echo Functions::dayMonthDate($row['date']) . ': ' . Functions::timeAmPm($row['time']) . ' - ' . $row['upvotes'] . ' - ' . $row['content'] . '<br />';
						$countrows++;
					}
					if ($countrows == 0) echo 'none.';
				?>
				
			</div>  <!-- end div: class="form-horizontal" -->

		</div> <!-- end div: class="container" -->
		
	</body> 
	
</html>