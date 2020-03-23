<?php
/* ---------------------------------------------------------------------------
 * filename    : login.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program logs the user in by setting $_SESSION variables
 * ---------------------------------------------------------------------------
 */

// Start or resume session, and create: $_SESSION[] array
session_start(); 

require 'database.php';

if ( !empty($_POST)) { // if $_POST filled then process the form

	// initialize $_POST variables
	$username = $_POST['username'];
	$passwords = $_POST['passwords'];
	$passwordshash = MD5($passwords);
	// echo $password . " " . $passwordhash; exit();
	// robot 87b7cb79481f317bde90c116cf36084b
		
	// verify the username/password
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM accounts WHERE username = ? AND passwords = ? LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($username,$passwordshash));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	
	if($data) { // if successful login set session variables
		echo "success!";
		$_SESSION['accounts_id'] = $data['id'];
		$sessionid = $data['id'];
		$_SESSION['accounts_admin'] = $data['admin'];
		Database::disconnect();
		header("Location: categorys.php?id=$sessionid ");
		// javascript below is necessary for system to work on github
		echo "<script type='text/javascript'> document.location = 'categorys.php'; </script>";
		exit();
	}
	else { // otherwise go to login error page
		Database::disconnect();
		header("Location: login_error.html");
	}
} 
// if $_POST NOT filled then display login form, below.

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
				<img src="svsu_fr_logo.png" />
			</div>
			
			<!--
			<div class="row">
				<br />
				<p style="color: red;">System temporarily unavailable.</p>
			</div>
			-->

			<div class="row">
				<h3>Volunteer Login</h3>
			</div>

			<form class="form-horizontal" action="login.php" method="post">
								  
				<div class="control-group">
					<label class="control-label">Username (Email)</label>
					<div class="controls">
						<input name="username" type="text"  placeholder="username" required> 
					</div>	
				</div> 
				
				<div class="control-group">
					<label class="control-label">Password</label>
					<div class="controls">
						<input name="passwords" type="passwords" placeholder="password" required> 
					</div>	
				</div> 

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Sign in</button>
					&nbsp; &nbsp;
					<a class="btn btn-primary" href="accounts_create2.php">Join (New Volunteer)</a>
				</div>
				
				<p><strong>Dear All</strong>: this website is canceled due to covid-19 lmao.</p>
				<p><strong>Dear NEW Volunteers</strong>: do not forget my free TV.</p>
				<p><strong>Dear Registered Volunteers</strong>: it's time to kick gum and chew ass, and i'm all out of ass.</p>
				<p><strong>Regarding passwords</strong>: just don't make a sucky password and you're good XD.</p>
				
				<br />

				
				<!--
				
				<h4>From the President</h4>
				
				<p>Dear Colleagues,</p>

				<p>We host many wonderful events at SVSU, including a number that are high profile and draw large crowds. We enjoy a well-earned reputation for serving as fine hosts, and this is a compliment to so many of you.</p>

				<p>We are preparing to host the statewide FIRST Robotics competition in less than three weeks, Wednesday, April 12 through Saturday, April 15. We have not hosted an external event of this size, scope and significance in years. This competition will bring nearly 5,000 high school students to campus, plus their teachers, coaches, parents and others. For many, this will be their first visit to SVSU, and we want to make our first impression the best possible. Each of us can play a role to see that we shine.</p>

				<p>We have a great need for volunteers. We are looking for people to assist in greeting guests and making them feel welcome; we need others to direct visitors and help them find their way; we are still identifying some volunteer duties. This is the first time that this event has been held on a college campus; FIRST in Michigan also has expanded the field this year. We are in near daily communication with organizers, so we continue to better understand exactly where volunteers will be needed most. If you can help, we will have a place for you.</p>

				<p>You can register online at www.svsu.edu/firstvolunteers.  When you sign up, click the blue (Join New Volunteer) button. You will be able to log in to see shift schedules and other volunteers.</p>
				
				-->

				<p><strong>All volunteers will be provided a Free TV</p>

				<!--
				
				<p>If you would like to volunteer during your normal work hours, please talk to your supervisor. If you are a supervisor, please do all you can to make arrangements to allow your staff to participate. In the unlikely event that we have more volunteers than we need for a given shift, we can allow people to return to their normal duties.</p>

				<p>If you are unable to volunteer for whatever reason, you can still support our university by showing patience and understanding during the competition days. We are taking measures to minimize the event’s effect on normal university operations – including parking – but some disruption is inevitable.</p>

				<p>Thank you in advance for supporting this event and making it a success. Let’s show our guests why every day is a great day to be a Cardinal.</p>

				<p>Sincerely,</p>

				<p>President Bachand</p>

				-->
				
				<footer>
					<small>&copy; Copyright 2050, Zippydee
					</small>
				</footer>
				
			</form>


		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
  
</html>
	

	