<?php 
	require 'db_connect.php';
 		
 		$query = mysqli_query($dbc, "SELECT * FROM students ");
		$arrVal = array();
 		
		$i=1;
 		while ($rowList = mysqli_fetch_array($query)) {
 								 
						$name = array(
								'num' => $i,
 	 		 	 				'last'=> $rowList['LastName'],
	 		 	 				'first'=> $rowList['FirstName'],
								'bday'=> $rowList['Birthday'],
								'univ'=> $rowList['University']
 	 		 	 			);		


							array_push($arrVal, $name);	
			$i++;			
	 	}
	 		 echo  json_encode($arrVal);		
 

	 	mysqli_close($dbc);
?>   
 