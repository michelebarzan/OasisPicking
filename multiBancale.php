<?php
	include "Session.php";
	include "connessione.php";
	
	$nBancali=$_GET["nBancali"];
	$N_Pick=$_SESSION['N_Pick'];
	$descrPick=$_SESSION['descrPick'];
	
?>
<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39+Text" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<script>
		function stampa() 
		{
			removeCircleSpinner();
			window.print();
		}			
		</script>
		<style>
			.stampaBancaleDivContainer
			{
				page-break-after:always;
				width:15cm;
				height:12cm;
				border:2px solid gray;
			}
			.stampaBancaleDivBarcode
			{
				float:left;
				display:inline-block;
				font-size:350%;
				width:13cm;
				margin-left:1cm;
				margin-top:2cm;
				margin-bottom:1cm;
				height:2cm;
				transform: scale(1, 2);
				font-family: 'Libre Barcode 39 Text', cursive;
			}
			.stampaBancaleDivLogo
			{
				margin-top:1cm;
				float:left;
				display:inline-block;
				width:5cm;
				height:5cm;
				margin-left:5cm;
			}
		</style>
	</head>
	<body onload="stampa()">
		<script>newCircleSpinner("Caricamento in corso...");</script>
		<?php
		
		for ($x = 0; $x < $nBancali; $x++)
		{
			$bancaleCreato=nuovoBancale($N_Pick,$conn);
			$nuovoBancale=$bancaleCreato;
			echo "<div class='stampaBancaleDivContainer' id='stampaBancaleDiv$x'>";
				echo '<img src="./images/logo.png" class="stampaBancaleDivLogo" alt="Logo">';
				echo "<div class='stampaBancaleDivBarcode'>"."*".$nuovoBancale."*"."</div>";
			echo "</div>";
		}
		
		function nuovoBancale($N_Pick,$conn)
		{
			$q="SELECT bancali.* FROM bancali WHERE n_Pick=$N_Pick";
			$r=sqlsrv_query($conn,$q);
			if($r==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				$rows = sqlsrv_has_rows( $r );
				if ($rows === true)
				{
					//echo "Ci sono gia  bancali per $N_Pick<br>";
					$qMax="SELECT MAX(numero) AS max FROM bancali WHERE n_Pick=$N_Pick";
					$rMax=sqlsrv_query($conn,$qMax);
					if($rMax==FALSE)
					{
						echo "<br><br>Errore esecuzione query<br>Query: ".$qMax."<br>Errore: ";
						die(print_r(sqlsrv_errors(),TRUE));
					}
					else
					{
						while($rowMax=sqlsrv_fetch_array($rMax))
						{
							$numero=$rowMax['max']+1;
							$bancale='BANCALE'.$N_Pick.'.'.$numero;
							$qInsert="INSERT INTO bancali (nome,n_Pick,numero) VALUES ('$bancale',$N_Pick,$numero)";
						}
					}
				}
				else 
				{
					//echo "NON ci sono gia  bancali per $N_Pick<br>";
					$numero=0;
					$bancale='BANCALE'.$N_Pick.'.'.$numero;
					$qInsert="INSERT INTO bancali (nome,n_Pick,numero) VALUES ('$bancale',$N_Pick,$numero)";
				}
			}
			$rInsert=sqlsrv_query($conn,$qInsert);
			if($rInsert==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$qInsert."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
				return $bancale;
		}
		?>
		<form name="chiudiF" id="chiudiF" action="picking2.php" method="POST" style="display:none">
			<input type="hidden" name="codice1" value=<?php echo $_SESSION['N_Pick']; ?>>
		</form>
		<script>
			(function() 
			{
				var beforePrint = function() {
					
				};

				var afterPrint = function() {
					newCircleSpinner("Caricamento in corso...");
					document.getElementById("chiudiF").submit();
				};

				if (window.matchMedia) {
					var mediaQueryList = window.matchMedia('print');
					mediaQueryList.addListener(function(mql) {
						if (mql.matches) {
							beforePrint();
						} else {
							afterPrint();
						}
					});
				}

				window.onbeforeprint = beforePrint;
				window.onafterprint = afterPrint;

			}());
		</script>
	</body>
</html>
