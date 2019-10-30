<?php
	include "session.php";
	include "connessione.php";
	
	set_time_limit(240);
	
	$q="DROP TABLE Q_Picking_04_tmp";
	$r=sqlsrv_query($conn,$q);
	
	$q2="INSERT INTO Q_Picking_04_tmp SELECT * FROM Q_Picking_04";
	$r2=sqlsrv_query($conn,$q2);
	if($r2==FALSE)
	{
		die("error");
	}
	else
		echo "ok";
?>