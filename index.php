	<!--     FAULT LOG 
	This is the homepage of the fault log.
	It simply displays all information or data of faulty pos terminals.
	other actions can be done in the homepage such as searching for each pos terminal via terminal serial or fault descriptions.
-->
<?php

// A session is started when a user is logged in.

session_start();
if (!$_SESSION['signup_page']) {
	header('location: main/login.php');
}
?>
<!-- Homepage -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.O1 Transitional//EN* *http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Fault Log</title>
	<link rel="stylesheet" type="text/css" href="css/stylee.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>

</head>
<div class="container">
	<body class="body" style="background-color: white; " >
		<!-- Navigation bar  -->
		<nav>
			<div class="topnav">
				<a href="index.php">Home<i class="fa fa-home" aria-hidden="true"></i></a>

				<a href="main/fault_form.php"><i class="fa fa-plus" aria-hidden="true"></i>
				Add Faults</a>
				<a href="main/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>
				Logout</a>
				<div class="search-container">
					<form action="index.php" method="POST">
						<input type="text" placeholder="Search.."   name="search"  autocomplete="on" required>
						<button type="submit"><i class="fa fa-search" aria-hidden="true"></i>
						</button>
					</form>
				</div>
			</div>
		</nav>
		<!-- Body -->
		<?php
			//database connection
		include("main/db_conn.php");
		$db = new DB();
		$conn = $db->connection();

		//A query to display and organise the total number of terminals in the database.
		$sql="SELECT terminal_serial FROM repair_log ORDER BY terminal_serial";

		$res = mysqli_query("SELECT * FROM repair_log");

		if ($result=mysqli_query($conn,$sql))

			$rowcount=mysqli_num_rows($result);
		printf("<br>Total: %d\n",$rowcount);{
	  // Return the number of rows in result set
	  // Free result set
			mysqli_free_result($result);
		}

	      //if search($set) button is clicked, a query($show) is executed to show the details of what was searched    

		if (isset($_POST['search'])) 
		{
			$set = $_POST['search'];

	        //search query($show)
			$show = "SELECT `id`, `terminal_serial`, 'terminal_id', `terminal_type`, `fault_descriptions`, `fault_categories`, `banks`, `date_received`, `date_delivered`, `status`, COUNT(*) AS num FROM repair_log  WHERE terminal_serial LIKE '$set%' OR  fault_descriptions LIKE '$set%' ";
			$result=mysqli_query($conn, $show);
			}//a query($result) to display all data in the homepage
			else
			{
				$result = mysqli_query($conn,"SELECT `id`, `terminal_serial`, 'terminal_id', `terminal_type`, `fault_descriptions`, `fault_categories`, `banks`, `date_received`, `date_delivered`, `status`, COUNT(*) AS num FROM repair_log  GROUP BY terminal_serial ORDER BY Id DESC LIMIT 52");
			}

          //A table to display each data
			echo "<table id='customers' style='font-family:Comic Sans MS', cursive'>
			<tr>
			<th>S/N</th>
			<th>Terminal Serial</th>
			<th>dup</th>
			<th>Terminal Type</th>
			<th>Date Received</th>
			<th>Banks</th>
			<th>Fault Categories</th>
			<th>Status</th>
			</tr>";

			$i = 1;

			while($row = mysqli_fetch_array($result))

	//The data displayed
			{
				echo "<tr>";
				echo "<td>". $i ."</td>";  
				echo "<td><a href='main/reference.php?tis=".$row['terminal_serial']."' style='text-decoration:none'>" .$row['terminal_serial']. "</a></td> "; 
				echo "<td>" . $row['num'] . "</td>";
				echo "<td>" . $row['terminal_type'] . "</td>";
				echo "<td>" . $row['date_received'] . "</td>";
				echo "<td>" . $row['banks'] . "</td>";
				echo "<td>" . $row['fault_categories'] . "</td>";
				echo "<td>" . $row['status'] . "</td>";
				echo "</tr>";
				$i++;
			}
			echo "</table>";

	// if the status button($done) is clicked the update query is executed
			if (isset($_POST["commiting"])) {
				$term =htmlspecialchars($_POST["commit"]);
				$term = trim($_POST["commit"]);

	//queries to update the status of terminals and  dates each terminals are updated. 

				$query = "SELECT terminal_serial FROM repair_log  WHERE terminal_serial = '$term' ";
				$result = mysqli_query($conn,$query);
				if($result == TRUE){ 

					$done = "DONE";
					$date = date("Y-m-d h:i:s");

					$query = "UPDATE repair_log  SET date_delivered = '$date' , status = '$done' WHERE terminal_serial = '$term'  ";
					$result = mysqli_query($conn,$query);

				}
				echo "updated";
			}
			
			mysqli_close($conn);

			?>
		</body>
	</div>
	</html>
	<!--footer -->
	<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style4.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	</head>
	<body>
		<div class="footer">
			<img src="img/itex.jpg" style="float: left; height: 37px; width: 120px;">


			<form action="index.php" method="POST" style="float: right;">
				<input type="text" name="commit" style="border-radius: 5px; height: 30px;">
				<input type="submit"   name="commiting"value="DONE" style="text-align:left; border-radius:5px; background-color: black; color: white; cursor:pointer; height: 30px;  ">
			</form>
		</div>

	</body>
	</html>





