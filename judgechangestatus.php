<?php session_start();
if(!isset($_SESSION['username'])) {
    header( 'Location: http://localhost/DBMS-Project/index.php');
}
?>
<?php 
	// $GLOBALS['seensuspect'] = "Yes";
	$firno = $_GET['id'];
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="DBMSProject";

	$conn = new mysqli($servername,$username,$password,$dbname);

	if($conn->connect_error){
		die("connection error".$conn->connect_error);
	}

	$sql = "SELECT see_suspect FROM firtable WHERE firno = '$firno'";

	$result = $conn->query($sql);

	$row = $result->fetch_assoc();

	if($row=='yes')
	{
		$GLOBALS['seensuspect'] = "Yes";
	}
	else
	{
		$GLOBALS['seensuspect'] = "No";
	}
	$sql = "SELECT * FROM firtable WHERE firno = '$firno'";
	$result = $conn->query($sql);

	$row = mysqli_fetch_row($result);

	if(isset($_REQUEST['submit-btn']))
	{
		$user = $_SESSION['username'];

		$sql = "SELECT copID FROM coptable WHERE username = '$user'";

		$result = $conn->query($sql);
		$res = mysqli_fetch_row($result);

		$date_time = $_POST['newdate']." ".$_POST['newtime'].":00";
		$status = $_POST['newstatus'];
		$sql = " INSERT INTO judgeupdate(judgeID,firno,datetime,statement) VALUES ($res[0],$firno,'$date_time','$status') ";


		$result = $conn->query($sql);

		echo "successfully added";
	}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="copchangestatus.css">
	<title>FIR Progress</title>
	<script type="text/javascript">
		function toggle() {
			if(document.getElementById('seensuspect').value == "Yes")
				document.getElementById('optional').style.display = 'block';
		}
		 function maxDateSetter() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            if(dd<10){
                dd='0'+dd
            }
            if(mm<10){
                mm='0'+mm
            }
            today = yyyy+'-'+mm+'-'+dd;
            document.getElementById("datefield").setAttribute("max", today);
        }
	</script>
</head>
<body>
	<div class = "global">
		<div class = "top">
			<h1 class="display-4" style="float: left; font-weight: lighter;font-size: 50px">Online FIR Portal</h1>
			<p style="float: left ; margin-left: 45%; margin-top: 2%;"; > <img style="margin-bottom: 3%;" src="user.png"><?php echo $_SESSION['username'] ; ?> &nbsp; &nbsp; &nbsp; <a href="http://localhost/DBMS-Project/logout.php" ><img src="logout.png"> Logout</a></p>
		</div>
		<div class ="leftt">
			<a href="http://localhost/DBMS-Project/judgemainpage.php" class = "anchor" >
			<div class = "clickable">
				Home
			</div>
			</a>

			<a href="http://localhost/DBMS-Project/judgeprofile.php" class = "anchor" >
			<div class = "clickable">
				Profile
			</div>
			</a>

			<a href="http://localhost/DBMS-Project/showjudgefir.php" class = "anchor" >
			<div class = "clickable">
				Check Pending Cases
			</div>
			</a>	

			<a href="http://localhost/DBMS-Project/contactus.html" class = "anchor" >
			<div class = "clickable">
				Contact Log
			</div>
			</a>	
		</div>
		<div class = "main-panel">
			<p style="padding-left: 200px; font-size: 20px;"> Scroll down to enter new status..</p>
			<form method="post">
				<label for = "firno">FIR Number </label>
				<input type="Number" name="firno" value="<?php echo $row[1]; ?>" disabled>
				<label for = "firno">Name(Victim) </label>
				<input type="text" name="firno" value="<?php echo $row[9]; ?>" disabled>
				<label for = "firno">Date </label>
				<input type="date" name="firno" value="<?php echo $row[2]; ?>" disabled>
				<label for = "place">Place of crime</label>
				<input type="text" name="place" value="<?php echo $row[3]; ?>" disabled>
				<label for = "time">Time </label>
				<input type="time" name="firno" value="<?php echo $row[6]; ?>" disabled >
				<label for = "description">Description of crime</label>
				<textarea disabled><?php echo $row[4]; ?></textarea>
				<label for = "seensuspect">Was suspect seen?</label>
				<input id = "seensuspect" type="text" name="seensuspect" disabled value=<?php echo $GLOBALS['seensuspect'] ?>>
				<div id = "optional"  style="display: none;">
					<label for = "describesuspect">Description of suspect</label>
					<textarea name="describesuspect" disabled><?php echo $row[5]; ?></textarea>
					<label for = "knowsuspect">Do you know suspect?</label>
					<input type="text" name="knowsuspect" value="<?php echo $row[8]; ?>" disabled>
				</div><br>
				<br>
				<label for = "place">Previous COP status</label>
				<table class="table table-striped" style="width: 655px;">
					<thead>
						<tr>
							<th scope="col">Cop ID</th>
							<th scope="col">Date & Time</th>
							<th scope="col">Status Uploaded</th>
						</tr>
					</thead>
					<tbody>
						<?php


						$sql = "SELECT * from copupdate WHERE firno = $firno";
						$ress = $conn->query($sql);
						$flag=0;
						while($roww = mysqli_fetch_row($ress))
						{
							$flag++;
							echo '<tr>';
							echo '<td>'.$roww[0].'</td>';
							echo '<td>'.$roww[2].'</td>';
							echo '<td>'.$roww[3].'</td>';
							echo '</tr>';
						}
						if($flag==0)
						{
							echo '<tr>';
							echo '<td>-</td>';
							echo '<td>-</td>';
							echo '<td>-</td>';
							echo '</tr>';
						}

						?>
						
					</tbody>
				</table>


				<label for = "place">Previous Judge status</label>
				<table class="table table-striped" style="width: 655px;">
					<thead>
						<tr>
							<th scope="col">Judge ID</th>
							<th scope="col">Date & Time</th>
							<th scope="col">Status Uploaded</th>
						</tr>
					</thead>
					<tbody>
						<?php


						$sql = "SELECT * from judgeupdate WHERE firno = $firno";
						$ress = $conn->query($sql);
						$flag2=0;
						while($roww = mysqli_fetch_row($ress))
						{
							$flag2++;
							echo '<tr>';
							echo '<td>'.$roww[0].'</td>';
							echo '<td>'.$roww[2].'</td>';
							echo '<td>'.$roww[3].'</td>';
							echo '</tr>';
						}
						if($flag2==0)
						{
							echo '<tr>';
							echo '<td>-</td>';
							echo '<td>-</td>';
							echo '<td>-</td>';
							echo '</tr>';
						}


						?>
						
					</tbody>
				</table>
				<label for = "description">What is new status of FIR ?? (Judicial Status)</label>
				<textarea name = "newstatus" required></textarea>
				<label for = "firno">Date of new status </label>
				<input type="date" name="newdate" required>
				<label for = "time">Time of new status </label>
				<input id="datefield" type="time" max="2008-04-04" name="newtime" required >
				<input type="submit" name="submit-btn" class="btn-primary">
			</form>

			

			<br><br>
			<script type="text/javascript">
				toggle();
			</script>
		</div>
	</div>
</body>
</html>