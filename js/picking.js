function apriPopupCercaOrdine()
{
    var outerContainer=document.createElement("div");
    
    var inputContainer=document.createElement("div");
    inputContainer.setAttribute("class","formRicercaOrdineInputContainer");

    var formInputLabel=document.createElement("div");
    formInputLabel.setAttribute("class","formRicercaOrdineInputLabel");
    formInputLabel.innerHTML="Codice ordine";

    inputContainer.appendChild(formInputLabel);

    var formInput=document.createElement("input");
    formInput.setAttribute("type","number");
    formInput.setAttribute("class","formRicercaOrdineInput");
    formInput.setAttribute("id","formRicercaOrdineordine"); 

    inputContainer.appendChild(formInput);

    var formInputbutton=document.createElement("button");
    formInputbutton.setAttribute("class","formRicercaOrdineIconButton");
    formInputbutton.setAttribute("onclick","cercaOrdine(this)");
    formInputbutton.setAttribute("title","Cerca");
    formInputbutton.innerHTML='<i class="fad fa-search"></i>';

    inputContainer.appendChild(formInputbutton);
    
    outerContainer.appendChild(inputContainer);

    var infoPickContainer=document.createElement("div");
    infoPickContainer.setAttribute("id","formRicercaOrdineInfoPickContainer");
    outerContainer.appendChild(infoPickContainer);

    var infoOrdineContainer=document.createElement("div");
    infoOrdineContainer.setAttribute("id","formRicercaOrdineInfoOrdineContainer");
    outerContainer.appendChild(infoOrdineContainer);

    Swal.fire
    ({
        title: 'Ricerca ordine',
        width:"1200px",
        background: "#e2e1e0",
        html: outerContainer.outerHTML,
        showConfirmButton: false,
        showCancelButton : false,
        showCloseButton: true,
        onOpen : function()
                {
                    
                }
    });
}
function cercaOrdine(button)
{
    document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML="";
    document.getElementById("formRicercaOrdineInfoOrdineContainer").innerHTML="";

    var ordine=document.getElementById("formRicercaOrdineordine").value;
    button.innerHTML='<i class="fad fa-spinner-third fa-spin"></i>';
    $.get("cercaOrdine.php",
    {
        ordine
    },
    function(response, status)
    {
        if(status=="success")
        {
            button.innerHTML='<i class="fad fa-search"></i>';
            if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
            {
                Swal.fire
                ({
                    type: 'error',
                    title: 'Errore',
                    text: "Se il problema persiste contatta l' amministratore"
                });
                console.log(response);
            }
            else
            {console.log(response);
                var arrayResponse=JSON.parse(response);
                var sparato=arrayResponse[0];
                var infoPick=arrayResponse[1];
                var infoOrdine=arrayResponse[2];

                if(infoPick["n_Pick"]==null)
                {
                    document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML="Ordine non trovato";
                }
                else
                {
                    console.log(sparato);

                    if(sparato)
                        document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML+='<u><b style="color:green"><i class="fad fa-clipboard-check" style="margin-right:5px"></i>Sparato</b></u>';
					
					if(infoPick["n_Pick"]!="")
					{
						document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML="<div>Informazioni pick</div>"
						document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML+="<u><b>N pick: </b></u>"+infoPick["n_Pick"];
						document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML+="<u><b>Descrizione pick: </b></u>"+infoPick["descrPick"];
						document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML+="<u><b>Data consegna: </b></u>"+infoPick["dataConsegna"];
						document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML+="<u><b>Data pick: </b></u>"+infoPick["dataPick"];
					}
                    else
					{
						document.getElementById("formRicercaOrdineInfoPickContainer").innerHTML="<div>L' ordine non è in nessun pick</div>"
					}

                    document.getElementById("formRicercaOrdineInfoOrdineContainer").innerHTML="<div>Informazioni ordine</div>"

                    var infoOrdineTable=document.createElement("table");

                    var infoOrdineTableRow=document.createElement("tr");

                    var infoOrdineTableCell=document.createElement("th");
                    infoOrdineTableCell.innerHTML="Docnum";
                    infoOrdineTableRow.appendChild(infoOrdineTableCell);

                    var infoOrdineTableCell=document.createElement("th");
                    infoOrdineTableCell.innerHTML="Linenum";
                    infoOrdineTableRow.appendChild(infoOrdineTableCell);

                    var infoOrdineTableCell=document.createElement("th");
                    infoOrdineTableCell.innerHTML="Itemcode";
                    infoOrdineTableRow.appendChild(infoOrdineTableCell);

                    var infoOrdineTableCell=document.createElement("th");
                    infoOrdineTableCell.innerHTML="Descrizione";
                    infoOrdineTableRow.appendChild(infoOrdineTableCell);

                    var infoOrdineTableCell=document.createElement("th");
                    infoOrdineTableCell.innerHTML="Misure";
                    infoOrdineTableRow.appendChild(infoOrdineTableCell);

                    if(sparato)
                    {
                        var infoOrdineTableCell=document.createElement("th");
                        infoOrdineTableCell.innerHTML="Bancale";
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);

                        var infoOrdineTableCell=document.createElement("th");
                        infoOrdineTableCell.innerHTML="Gruppo";
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);
                    }

                    infoOrdineTable.appendChild(infoOrdineTableRow);

                    infoOrdine.forEach(element => {
                        console.log(element);

                        var infoOrdineTableRow=document.createElement("tr");

                        var infoOrdineTableCell=document.createElement("td");
                        infoOrdineTableCell.innerHTML=element["docNum"];
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);

                        var infoOrdineTableCell=document.createElement("td");
                        infoOrdineTableCell.innerHTML=element["lineNum"];
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);

                        var infoOrdineTableCell=document.createElement("td");
                        infoOrdineTableCell.innerHTML=element["itemCode"];
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);

                        var infoOrdineTableCell=document.createElement("td");
                        infoOrdineTableCell.innerHTML=element["dscription"];
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);

                        var infoOrdineTableCell=document.createElement("td");
                        infoOrdineTableCell.innerHTML=element["misure"];
                        infoOrdineTableRow.appendChild(infoOrdineTableCell);

                        if(sparato)
                        {
                            var infoOrdineTableCell=document.createElement("td");
                            infoOrdineTableCell.innerHTML=element["bancale"];
                            infoOrdineTableRow.appendChild(infoOrdineTableCell);

                            var infoOrdineTableCell=document.createElement("td");
                            infoOrdineTableCell.innerHTML=element["gruppo"];
                            infoOrdineTableRow.appendChild(infoOrdineTableCell);
                        }

                        infoOrdineTable.appendChild(infoOrdineTableRow);
                    });
                    document.getElementById("formRicercaOrdineInfoOrdineContainer").appendChild(infoOrdineTable);
                }
            }
        }
        else
            console.log(status);
    });
}