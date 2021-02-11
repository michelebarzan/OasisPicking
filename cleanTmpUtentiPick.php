<?php

	include "connessione.php";
    include "Session.php";

	$query2="SELECT* FROM utenti WHERE username='".$_SESSION['Username']."'";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		die("error");
	}
	else
	{
        while($row2=sqlsrv_fetch_array($result2))
        {
            $id_utente = $row2['id_utente'];
			$query3="DELETE FROM tmp_utenti_pick WHERE utente=$id_utente";	
			$result3=sqlsrv_query($conn,$query3);
			if($result3==FALSE)
			{
				die("error");
			}
        }
	}
	
?>