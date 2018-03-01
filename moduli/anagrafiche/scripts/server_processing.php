<?php
header('Content-Type: text/plain');
include_once('../../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

if (isset($_GET['tbl'])) {
    $tabella = $_GET['tbl'];
}

$orderColumn = $_REQUEST['order']; //numero colonna

$arrayCampoRicerca = array();
$campoRicerca = $_REQUEST['search']['value'];
if(strpos($campoRicerca," ")!==false){
    $arrayCampoRicerca = explode(" ",$campoRicerca);
}else{
    if(strlen($campoRicerca)>0){
        $arrayCampoRicerca[] = $campoRicerca;
    }
}

switch($tabella){

        default:
                //securityLogut();
        break;

        case 'lista_aziende':
            $tabella = "lista_aziende";
            $campi_visualizzati = $table_listaAziende['index']['campi'];
            $where = $table_listaAziende['index']['where'];
            if(!empty($arrayCampoRicerca)){
                foreach ($arrayCampoRicerca as $campoRicerca) {
                    $campoRicerca = $dblink->filter($campoRicerca);
                    $where.= " AND ( ragione_sociale LIKE '%".$campoRicerca."%' OR codice LIKE '%".$campoRicerca."%'";
                    $where.= " OR partita_iva LIKE '%".$campoRicerca."%' OR codice_fiscale LIKE '%".$campoRicerca."%'";
                    $where.= " OR email LIKE '%".$campoRicerca."%' OR cellulare LIKE '%".$campoRicerca."%'";
                    $where.= " OR telefono LIKE '%".$campoRicerca."%' OR stato LIKE '%".$campoRicerca."%')";
                }
            }
            $ordine = $table_listaAziende['index']['order'];
        break;

        case 'lista_professionisti':
            $tabella = "lista_professionisti";
            $campi_visualizzati ="CONCAT('<a class=\"btn btn-circle btn-icon-only yellow btn-outline\" href=\"dettaglio.php?tbl=lista_professionisti&id=',id,'\" title=\"DETTAGLIO\" alt=\"DETTAGLIO\"><i class=\"fa fa-search\"></i></a>') AS 'fa-search',
                            CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=lista_professionisti&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
                            CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"dettaglio_tab.php?tbl=lista_professionisti&id=',id,'\" title=\"RICHIESTA\" alt=\"RICHIESTA\"><i class=\"fa fa-book\"></i></a>') AS 'fa-book',
                            CONCAT('<b>',`cognome`,' ',`nome`,'</b>') AS 'Professionista', codice_fiscale, 
                            CONCAT(IF(cellulare>0, CONCAT('Cel: ',cellulare),''),IF(cellulare > 0 AND telefono > 0, '<br>', ''),IF(telefono>0, CONCAT('Tel: ',telefono),'')) AS Telefono, email";
            $where = $table_listaProfessionisti['index']['where'];
            if(!empty($arrayCampoRicerca)){
                foreach ($arrayCampoRicerca as $campoRicerca) {
                    $campoRicerca = $dblink->filter($campoRicerca);
                    $where.= " AND (cognome LIKE '%".$campoRicerca."%' OR nome LIKE '%".$campoRicerca."%'";
                    $where.= " OR codice_fiscale LIKE '%".$campoRicerca."%' OR cellulare LIKE '%".$campoRicerca."%'";
                    $where.= " OR codice LIKE '%".$campoRicerca."%' OR professione LIKE '%".$campoRicerca."%'";
                    $where.= " OR telefono LIKE '%".$campoRicerca."%' OR email LIKE '%".$campoRicerca."%')";
                }
            }
            $ordine = $table_listaProfessionisti['index']['order'];
        break;
}

$sql_0001 = "SELECT count(id) AS conto FROM ".$tabella." WHERE $where $ordine";

$numRowRes = $dblink->get_row($sql_0001,true);
$numRow = $numRowRes['conto'];
//$numRow = 40052;
//$fields = $dblink->list_fields($sql_0001);

if(!empty($orderColumn)){
    $ordine = "ORDER BY ";
    foreach ($orderColumn as $order) {
        $ordine.= (intval($order['column'])+1)." ".$order['dir'].", ";
    }
    $ordine = substr($ordine, 0, -2);
}

$iTotalRecords = $numRow;
$iDisplayLength = intval($_REQUEST['length']);
$iDisplayLength = $iDisplayLength <= 0 ? $iTotalRecords : $iDisplayLength; 
$iDisplayStart = intval($_REQUEST['start']);
$sEcho = intval($_REQUEST['draw']);

$records = array();
$records["data"] = array(); 

$end = $iDisplayStart + $iDisplayLength;
$end = $end > $iTotalRecords ? $iTotalRecords : $end;

$limite = ' LIMIT '.$iDisplayStart.','.$iDisplayLength;
$sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";

if($_SESSION['livello_utente']=='amministratore'){
    //echo $sql_0001;
}

$rs_0001 = $dblink->get_results($sql_0001);
//$numRow = $dblink->num_rows($sql_0001);
//$fields = $dblink->list_fields($sql_0001);

$r = 0;

foreach ($rs_0001 as $row) {
    $c = 0;
    foreach ($row as $column) {
        $records["data"][$r][] = '' . $column . '';
        /*if (strtolower($fields[$c]->name) == "stato") {
            //echo $column;
            if (strpos($column, "|") !== false) {
                $tmpStato = explode("|", $column);
                $classeColore = array($tmpStato[0]);
                $column = $tmpStato[1];
            } else {
                $classeColore = $dblink->get_row("SELECT colore_sfondo FROM lista_richieste_stati WHERE nome='" . $dblink->filter($column) . "' AND colore_sfondo!=''");
            }
        } else {
            $classeColore = false;
        }
        if ($classeColore != false) {
            $records["data"][$r][] = '<span class="badge bold bg-' . $classeColore[0] . ' bg-font-' . $classeColore[0] . '"> ' . $column . ' </span>';
        } else {
            switch (strtolower($column)) {
                case "disponibile":
                case "richiamare":
                case "chiuso":
                case "attivo":
                case "attiva":
                case "pagata":
                case "lavorazione terminata":
                    $records["data"][$r][] = '<span class="badge bold bg-green-jungle bg-font-green-jungle"> ' . $column . ' </span>';
                    break;

                case "in attesa":
                case "in corso":
                case "in lavorazione":
                case "in attesa di controllo":
                case "nuovo nominativo in attesa di controllo":
                    $records["data"][$r][] = '<span class="badge bold bg-yellow-saffron bg-font-yellow-saffron"> ' . $column . ' </span>';
                    break;

                case "negativo":
                case "non disponibile":
                case "non attivo":
                case "non attiva":
                case "annullata":
                case "mai contattato":
                case "non interessa":
                case "non letto":
                    $records["data"][$r][] = '<span class="badge bold bg-red-thunderbird bg-font-red-thunderbird"> ' . $column . ' </span>';
                    break;

                case "venduto":
                case "terminata":
                    $records["data"][$r][] = '<span class="badge bold bg-blue-steel bg-font-blue-steel"> ' . $column . ' </span>';
                    break;

                case "img":
                    $records["data"][$r][] = '' . $column . '';
                    break;

                default:
                    if(strtolower($fields[$c]->name)=="selezione"){
                        $records["data"][$r][] = '<label class="mt-checkbox mt-checkbox-outline"><input name="txt_checkbox_' . $r . '" id="txt_checkbox_' . $r . '" type="checkbox"  value="' . $column . '"><span></span></label>'; 
                    }else if (strtolower($fields[$c]->name) == "data") {
                        $records["data"][$r][] = '' . GiraDataOra($column) . '';
                    }else{
                        $records["data"][$r][] = '' . $column . '';
                    }
                break;
            }
        }*/
        $c++;
    }
    $r++;
}

if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
}

$records["draw"] = $sEcho;
$records["recordsTotal"] = $iTotalRecords;
$records["recordsFiltered"] = $iTotalRecords;

echo json_encode($records);

?>