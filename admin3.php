<?php
	require('Configure.php');
	session_start();
	
	//when user click logout, will desroy all login session infomation and return admin login page
	if($_GET['logout']==1){ 
		session_destroy(); 
		echo'<script type="text/javascript">
		window.location="'.$_SERVER['PHP_SELF'].'";
		</script>';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Website information -->
		<title>WSP HR Admin Page</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="WSP HR Admin Page">
		
		
		<!-- Page styles -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.min.css">
		<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-blue.min.css" /> 
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		
		<!-- style for the Website -->
		<style>
			body{
			width:90%;
			margin:auto;
			text-align:center;
			vertical-align:middle;
			}
			
			#form{
			margin:auto;
			text-align:left;
			width:450px;
			}
			
			input[type=text] {
			width: 100%;
			box-sizing: border-box;
			}
			
		</style>
		
	</head>
	<body>
		<!-- Page banner -->
		<?php
			if($_SESSION["login"] != true){
		?>
			<!-- Login from -->
			<div id="form">
				<form id="login_form" action="admin3.php" method="POST">
					
					<span class="mdl-chip mdl-chip--contact mdl-chip--deletable">
						<span class="mdl-chip__text">Account&nbsp;&nbsp;</span>
					</span>
					<br/>
					<input type="text" id="account" name="account" required/>
					<br/><br/>
					<span class="mdl-chip mdl-chip--contact mdl-chip--deletable">
						<span class="mdl-chip__text">Password&nbsp;&nbsp;</span>
					</span>
					<br/>
					<input type="password" class="mdl-textfield__input" size="60" id="pw" name="pw" required />
					<br/>
					<input class="mdl-button mdl-js-button mdl-button--primary" style="float:left" type="submit" value="submit"/>
				</form>
			</div>
			<?php
				//check the user input. If account and password are both true, it will return login true. Otherwise, it cannot login andreturn error message
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$_SESSION["account"] =  $_POST['account'];
					$_SESSION["pw"] =  $_POST['pw'];
					if ($_SESSION["account"] == "admin" && $_SESSION["pw"]=="pass") {
						echo '<div class="mdl-spinner mdl-js-spinner is-active"></div>';
						echo "login success";
						$_SESSION["login"] = true;
						echo'<script type="text/javascript">
						window.location="'.($_SERVER['PHP_SELF']).'"
						</script>';
					}
					else {
						$_SESSION["login"] = false;
						echo "<br/><br/>account or password is wrong";
					}
				}
			}
			//if login success, it will show the data table and user menu
			if ($_SESSION["login"]){
				echo "Login success";
			?>	
			
			<a href="<?=($_SERVER['PHP_SELF'])?>?logout=1"><button class="mdl-button mdl-js-button mdl-button--primary" type="button">Logout</button></a>
			<br/>
			
			<?php
				
				try{
					// connect to the database
					$sql = 'SELECT * FROM [Graduate].[dbo].[GraduateRecruit]';
					$stmt = $conn->prepare($sql);  
					$res = $stmt->execute();
					
					//Show all the data form the database using table
					echo '<table id="record" class="table table-striped table-bordered dataTable no-footer" width="100%" border="5px solid black">
					<thead>
					<tr>
					<th>ID</th>
					<th>Gender</th>
					<th>Full Name</th>
					<th>Preferred name</th>
					<th>Mobile</th>
					<th>Email</th>
					<th>University</th>
					<th>Country</th>
					<th>Degree</th>
					<th>GPA</th>
					<th>Apply Date</th>
					</tr></thead>';
					echo'<tbody align="center">';
					//default order by time
					$order = "rtimestamp";
					

					//if user input a phone number in input field, it will change the input field
					if(!empty($_POST['search'])){
						$search =  $_POST['search'];
						$result = $pdo->query("SELECT * FROM reservation");
					}
				
					while ($row =$result->fetch(PDO::FETCH_ASSOC)) {
						echo '<tr>
						<td>'.$row["UploadFileName"].'</td>
						<td>'.$row["Gender"].'</td>
						<td>'.$row["lname"].', '.$row["fname"].'</td>
						<td>'.$row["PreferredName"].'</td>
						<td>'.$row["Mobile"].'</td>
						<td>'.$row["Email"].'</td>
						<td>'.$row["Country1"].'</td>
						<td>'.$row["University1"].'</td>
						<td>'.$row["Degree1"].'</td>
						<td>'.$row["GPA1"].'</td>
						<td>'.date('Y-m-d H:i:s',$row["rtimestamp"]).'</td>
						</tr>';
					}
					echo'</tbody>';
					echo '</table>';
				}
				//if there are exception error will print out error message
				catch (Exception $e){
					echo '<br/>Cannot connect to server. Please try later!';
				}
			}
			?>
		</body>
		<script>
  $(document).ready(function() {
      $('#record').dataTable({
        'columns'           : [
            { 'searchable': true },
            { 'searchable': true },
            { 'searchable': true },
            { 'searchable': true },
            { 'searchable': true },
            { 'searchable': true }
        ]
        });
  });
</script>
	</html>												