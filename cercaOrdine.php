<?php

    include "Session.php";
    include "connessione.php";

    $ordine=$_REQUEST['ordine'];

    $sparato=false;
    $infoPick["n_Pick"]=null;
    $righeOrdine=[];

    $query2="select T_Picking_01.*,bancali.nome  from T_Picking_01,bancali where T_Picking_01.bancale=bancali.id_bancale AND docNum = '$ordine'";	
    $result2=sqlsrv_query($conn,$query2);
    if($result2==TRUE)
    {
        while($row2=sqlsrv_fetch_array($result2))
        {
            $sparato=true;
            if($infoPick["n_Pick"]==null)
            {
                $infoPick["n_Pick"]=$row2['n_Pick'];
                $infoPick["descrPick"]=$row2['descrPick'];
                $infoPick["dataConsegna"]=$row2['DataConsegna']->format('d/m/Y');
                $infoPick["dataPick"]=$row2['DataPick']->format('d/m/Y');
            }
            $infoOrdine["docNum"]=$row2['docNum'];
            $infoOrdine["lineNum"]=$row2['lineNum'];
            $infoOrdine["itemCode"]=$row2['itemCode'];
            $infoOrdine["dscription"]=$row2['dscription'];
            $infoOrdine["misure"]=$row2['Misure'];
            $infoOrdine["bancale"]=$row2['nome'];
            $infoOrdine["gruppo"]=$row2['gruppo'];

            array_push($righeOrdine,$infoOrdine);
        }
    }
    else
        die("error");

    if($sparato===false)
    {
        $query3="SELECT * FROM Q_Picking_04 WHERE DocNum = '$ordine'";	
        $result3=sqlsrv_query($conn,$query3);
        if($result3==TRUE)
        {
            while($row3=sqlsrv_fetch_array($result3))
            {
                if($infoPick["n_Pick"]==null)
                {
                    $infoPick["n_Pick"]=$row3['N_Pick'];
                    $infoPick["descrPick"]=$row3['DescrPick'];
                    $infoPick["dataConsegna"]=$row3['DataConsegna']->format('d/m/Y');
                    $infoPick["dataPick"]=$row3['DataPick']->format('d/m/Y');
                }
                $infoOrdine["docNum"]=$row3['DocNum'];
                $infoOrdine["lineNum"]=$row3['N_Riga'];
                $infoOrdine["itemCode"]=$row3['ItemCode'];
                $infoOrdine["dscription"]=$row3['Dscription'];
                $infoOrdine["misure"]=$row3['Misure'];
    
                array_push($righeOrdine,$infoOrdine);
            }
        }
        else
            die("error");
    }

    $arrayResponse=[$sparato,$infoPick,$righeOrdine];
    echo json_encode($arrayResponse);

?>