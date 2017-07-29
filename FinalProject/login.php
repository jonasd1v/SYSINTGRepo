<?php
	session_start();
	if (isset($_POST['submit'])){
		require_once('C:\xampp\htdocs\SYSINTGRepo\FinalProject\pages\db_connect.php');
		$message=NULL;
		$status_query = "select * from users"; 
		$stat_result=mysqli_query($dbc,$status_query);	
		$sRow=mysqli_fetch_array($stat_result,MYSQLI_ASSOC);
		
		$count=0;
		if(!isset($message)){
		
			$user=$_POST['username'];
			$pass=$_POST['password'];
			
			$query_test="SELECT * FROM users WHERE username='$user' AND password='$pass'";
			$result=mysqli_query($dbc,$query_test);
			
			$count=mysqli_num_rows($result);
			
			if ($count == false) {
				$count = 0;
			}
			
			$flag=1;				
		}

		if ($count!=0){		
			
			$row = mysqli_fetch_assoc($result);
				$_SESSION['username'] = $user;
				$_SESSION['logged_in'] = true;
				header("Location: pages\index.php");
			
		}else{
			//echo "Please try again";
		}			
		
	}/*End of main Submit conditional*/

	if (isset($message)){
	 echo '<font color="red">'.$message. '</font>';
	}

	if(isset($_SESSION['username'])){
		header("Location: pages\index.php");
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
    	<h3 class="page-header" style="text-align: center">Please log-in</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        	<div class="form-group">
            	<div class="col-xs-2" style="position:relative; left: 42%">        
                	<input class="form-control" placeholder="Username" name="username" type="text"  value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" required/>
                    	<div class="form-group">
                        	<input class="form-control" placeholder="Password" name="password" type="password" required>
                        </div>
                        <div align="center">
                        	<button type="submit" name="submit" />Login</button>
                        </div> 
                        </div>   
            </div>	
        </form>
    </div>

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>

