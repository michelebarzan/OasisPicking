<?php
ini_set("session.gc_maxlifetime",720000); 
ini_set('session.gc_probability',0); 
ini_set('session.gc_divisor',1); 
session_start();
?>
<html>
	<head>
		<title>Picking</title>
		<link rel="stylesheet" href="css/stylePicking.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script type="text/javascript">
		function FocusOnInput()
		{
			document.getElementById("codice3").focus();
		}
		function importaDatiSap()
		{
			newCircleSpinner("Importazione dati SAP in corso...");
			$.post("importaDatiSap.php",
			function(response, status)
			{
				if(status=="success")
				{
					if(response.indexOf("ok")>-1)
						removeCircleSpinner();
					else
					{
						Swal.fire
						({
							type: 'error',
							title: 'Errore: Impossibile importare i dati da SAP',
							text: "Se il problema persiste contatta l' amministratore"
						})
					}
				}
				else
					console.log(status);
			});
		}
		</script>
		<style>
			.swal2-title
			{
				font-family:'Montserrat',sans-serif;
				font-size:18px;
			}
			.swal2-content
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
			.swal2-confirm,.swal2-cancel
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
			.flash 
			{
				font-size:200%;
				font-family:arial;
				animation-name: flash;
				animation-duration: 0.3s;
				animation-timing-function: linear;
				animation-iteration-count: infinite;
				animation-direction: alternate;
				animation-play-state: running;
			}

			@keyframes flash 
			{
				from {color: red;}
				to {color: gray;}
			}
		 </style>
	</head>
	<body onload="FocusOnInput()">
		<div id="container">
			<div id="header">
				<div id="user">
				<?php
					include "connessione.php";
					iniziaSessione($conn);
					$_SESSION['nBancali']=0;
				?>
				</div>
				<div id="logout" class="logout">
					<!--<form method="POST" action="logout.php">
						<input type="submit" value="logout">
					</form>-->
				</div>
			</div>
			<div id="content">
				<div id="riga1">
					<div id="codice" class="codice">
						<form action="checkCodice.php" method="POST">
							<input type="text" id="codice3" name="codice3" placeholder="Spara un codice pick" required/>
						</form>
					</div>
					<div id="immagine">
					</div>
				</div>
				
				
				<div id="tabella" class="tabella">
					<div style='font-family:arial;font-size:150%;color:#66B2FF;font-weight:bold;'><br><br><br>Spara un codice pick</div>
				</div>
			</div>
		</div>
		<div id="footer">
			Oasis Group  |  Via Favola 19 33070 San Giovanni PN  |  Tel. +39 0434654752
		</div>
	</body>
</html>

<!---------------------------------------------------------------------------------------------------------------------------------------------------------------

<?php
function iniziaSessione($conn)
{
	$UsernamePC = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	//CANCELLA TUTTO CIO CHE CE DOPO IL PUNTO
	if (strpos($UsernamePC, '.') != false)
		$UsernamePC = substr($UsernamePC, 0, strpos($UsernamePC, "."));
	
	if($conn)
	{
		$queryPW="SELECT password, username FROM utenti WHERE usernamePC='$UsernamePC' ";
		$resultPW=sqlsrv_query($conn,$queryPW);
						
		if($resultPW==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryPW."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowPW=sqlsrv_fetch_array($resultPW))
			{
				$password=$rowPW['password'];
				$Username=$rowPW['username'];
			}
		}
	}
	else
	{
		echo "<b style='color:black'>Connessione fallita</b>";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	//echo getenv("USERNAME");
	if($Username!="" || $Username!=NULL)
		echo $Username;
	else
	{
		echo "<b style='color:red'>Utente $UsernamePC non registrato."."Indirizzo ip:".$_SERVER['REMOTE_ADDR']."</b>";
		
		echo '</div>
				<div id="logout" class="logout" style="margin-top:1%";>
					<form method="POST" action="logout.php">
						<input type="submit" value="logout">
					</form>
				</div>';
		die();
	}
	$_SESSION['Username']=$Username;
	$_SESSION['Password']=$password;
}
?>