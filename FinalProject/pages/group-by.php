<?php
session_start();
if (!isset($_SESSION['username'])) {
        header("Location: ../login.php");
        die();
    }else{
	require_once('db_connect.php');
	
		$continueQuery = false;
		$tableTitle = null;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {									
			$lowestAge = 0;
			$highestAge = 0;
			$university = "";
			
			$groupByAge = false;
			$groupByUniversity = false;
		
			if(isset($_POST['ageCheckbox'])){
				$groupByAge = true;
				if (!empty($_POST['lowestAge']) && !is_numeric($_POST['lowestAge'])) {
					$message = "Invalid input";
				}
				if (!empty($_POST['highestAge']) && !is_numeric($_POST['highestAge'])) {
					$message = "Invalid input";
				}
				if (empty($_POST['lowestAge']) && empty($_POST['highestAge']) ) {
					$message = "Invalid input";
					$continueQuery = false;
				}
				
			}
			
			if (isset($_POST['universityCheckbox'])) {
				$groupByUniversity = true;
				$university = $_POST['university'];
			}
			
			if (!isset($_POST['universityCheckbox']) && !isset($_POST['ageCheckbox'] )) {
				$message = "Invalid input";
			}
										
			if (!isset($message)) {
				$continueQuery = true;
				if ($groupByAge) {
					if ($_POST['lowestAge'] > $_POST['highestAge']) {
						$lowestAge = $_POST['highestAge'];
						$highestAge = $_POST['lowestAge'];
					}
					else {
						$lowestAge = $_POST['lowestAge'];
						$highestAge = $_POST['highestAge'];
					}
				}
				
				if ($groupByAge && !$groupByUniversity) {
					$tableTitle = "<center><b>Age:</b> $lowestAge to $highestAge<br><br></center><br>";
					$groupByQuery =	$groupByQuery =	"SELECT LastName, FirstName, FLOOR(DATEDIFF(NOW(),Birthday) /365) AS 'AGE', university  
									 FROM
									 (  SELECT
										  LastName, 
										  FirstName,
										  Birthday,
										  FLOOR(DATEDIFF(NOW(),Birthday) /365) AS AGE,
										  University
									   FROM students)
									   WHERE AGE >= '$lowestAge' AND
										   AGE <= '$highestAge' ";
				}

				else if (!$groupByAge && $groupByUniversity) {
					
					$universityString = "";
					foreach ($_POST['university'] as $university){									
						$universityString.= $university;
					}					
					$tableTitle = "<center>Students from <b>$universityString</b></center><br>";

					$university = implode("','", $_POST['university']);
					
					$groupByQuery =	"SELECT LastName,FirstName, FLOOR(DATEDIFF(NOW(),Birthday) /365) AS 'AGE', University  
									 FROM students
									 WHERE University IN ('$university')";
				}
				else if ($groupByAge && $groupByUniversity) {
					$universityString = "";
					foreach ($_POST['university'] as $university){									
						$universityString.= $university;
					}					
					
					$tableTitle = "<center> Students of <b>$lowestAge</b> to <b>$highestAge</b> years old from <b>$universityString</b></center><br>";
					
					$university = implode("','", $_POST['university']);
					
					$groupByQuery =	"SELECT LastName,FirstName, FLOOR(DATEDIFF(NOW(),Birthday) /365) AS 'AGE', University  
									 FROM
									 (
									   SELECT
										  FirstName, 
										  LastName,
										  Birthday,
										  FLOOR(DATEDIFF(NOW(),Birthday) /365) AS AGE,
										  University
									   FROM students   
									 ) AS innerTable
									 WHERE AGE >= '$lowestAge' AND
										   AGE <= '$highestAge' AND
										   University IN ('$university') ";
				}																		
			}
			else {
				$continueQuery = false;
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>University Data</title>

     <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">

     <!-- DataTables CSS -->
    <link href="../css/dataTables.bootstrap.min.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

     <div id="wrapper">    
    <nav class="navbar navbar-fixed-top" role="navigation">
            
            <!-- Top Menu Items -->
            <ul class="nav navbar-left top-nav" style="position: fixed; top:0; width: 100%; background-color:#222">
                 <li style="position: relative; align-content: left;">
                    <a href="index.php" style="color:white">View All Students</a>
                </li>
                <li class>
                    <a href="total-students-in-university.php" style="color:white">View Total Students</a>
                </li>
				 <li class>
                    <a href="group-by.php" style="color:white">Group By</a>
                </li>
    
            </ul>
             <ul class="nav navbar-right top-nav">
                <li> 
                    <a href="logout.php" style="color:white">Logout</a>
                </li>
            </ul></div>

        <!-- Navigation -->
        
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"; style = "text-align: center;"> 
                           <small>University Data</small>
                        </h1>
                    </div>
                </div>

        <div id="page-wrapper">

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
							<form id="groupByForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">	
								<div>
									<b>Grouping Options:</b> <br>
									<input type="checkbox" name="universityCheckbox" onclick="showUniversityDropdown('universityDropdown')" value=""> University
									<input type="checkbox" name="ageCheckbox" onclick="showAgeInput('ageInput')"> Age
									<input type="submit" value="Group"><br><br>
								</div>
								
								<?php

									$query = "select university 
									            from students 
										    group by university";
									$result = mysqli_query($dbc,$query);	
					
									echo "<select name='university[]' id='universityDropdown' style='display:none;'>";
									echo '<option value="" disabled selected>Select University</option>';
									while ($row = $result->fetch_assoc()) {
										unset($university);
										$university = $row['university']; 
										echo '<option value="'.$university.'">'.$university.'</option>';
									}

									echo "</select>";

								?> 
								
								<br>
								<div id="ageInput" style="display:none;">
									<input id="lowestAge" name="lowestAge" size="5" type="text" class="small" value="" <?php if (isset($_POST['lowestAge'])) echo $_POST['lowestAge']; ?>/> 
									to
									<input id="highestAge" name="highestAge" size="5" type="text" class="small" value="" <?php if (isset($_POST['highestAge'])) echo $_POST['highestAge']; ?>/> years old
								</div>	
								<br>
								
							</form>
							
							<?php if ($tableTitle != null) { echo "<center>$tableTitle</center>";} ?>
						
							<table width="100%" class="table table-striped table-bordered" id="students-table">						
								<thead>
									<tr>
										<th class="text-center">Last Name</th>
										<th class="text-center">First Name</th>
										<th class="text-center">Age</th>
										<th class="text-center">University</th>
						 
									</tr>
								</thead>
								<tbody>
									<?php 
										if ($continueQuery) {
											$result=mysqli_query($dbc,$groupByQuery);
											
											while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){ ?>
											<tr>
												<td class="text-center"><?php echo $row['LastName']; ?></td>
												<td class="text-center"><?php echo $row['FirstName']; ?></td>	
												<td class="text-center"><?php echo $row['AGE']; ?></td>
												<td class="text-center"><?php echo $row['University']; ?></td>
											</tr>
										<?php } 
										}										
									?>
								</tbody>
							</table> 
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
	
  
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!--DataTables js-->
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap.min.js"></script>
    

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../js/plugins/morris/raphael.min.js"></script>
    <script src="../js/plugins/morris/morris.min.js"></script>
    <script src="../js/plugins/morris/morris-data.js"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
   <script>
        $('#students-table').dataTable();
    </script>
	
	<script>
	function showAgeInput (box) {

		var chboxs = document.getElementsByName("ageCheckbox");
		var vis = "none";
		for(var i=0;i<chboxs.length;i++) { 
			if(chboxs[i].checked){
			 vis = "block";
				break;
			}
		}
		document.getElementById(box).style.display = vis;
	}
	</script>
	
	<script>
	function showUniversityDropdown (box) {

		var chboxs = document.getElementsByName("universityCheckbox");
		var vis = "none";
		for(var i=0;i<chboxs.length;i++) { 
			if(chboxs[i].checked){
			 vis = "block";
				break;
			}
		}
		document.getElementById(box).style.display = vis;
	}
	</script>

</body>

</html>
