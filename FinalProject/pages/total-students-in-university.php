<?php
require_once ('db_connect.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>University Data</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

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

    <!--<div id="wrapper"-->
    <div id="wrapper">    
    <nav class="navbar navbar-fixed-top" role="navigation">
            
            <!-- Top Menu Items -->
            <ul class="nav navbar-left top-nav">
                 <li style="position: relative; align-content: left;">
                    <a href="index.php">View All Students</a>
                </li>
                <li class>
                    <a href="total-students-in-university.php">View Total Students</a>
                </li>
				 <li class>
                    <a href="group-by.php">Group By</a>
                </li>
    
            </ul></div>

        <!-- Navigation -->
        
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"; style = "text-align: center;" 
                            <small>University Data</small>
                        </h1>
                    </div>
                </div>

                 <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="school-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">University</th>
                                        <th class="text-center">Total No. of Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $query = "SELECT University, COUNT(LASTNAME) AS 'TOTAL' FROM students GROUP BY UNIVERSITY";
                                        $result=mysqli_query($dbc,$query);
                                        while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                            ?>
                                            <tr>                                                    
                                                    <td class="text-center"><?php echo $row['University']; ?></td>
                                                    <td class="text-center"><?php echo $row['TOTAL']; ?></td>                                                       
                                            </tr>
                                        <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    <!--/div-->

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
	
	

    
</body>

</html>
