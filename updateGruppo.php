<?php

	include "connessione.php";

    $gruppo=$_REQUEST['gruppo'];
    $id_picking=$_REQUEST['id_picking'];
	
	$query2="UPDATE T_Picking_01 SET gruppo='$gruppo' WHERE id_picking=$id_picking";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		die("error".print_r(sqlsrv_errors(),TRUE));
	}

?>