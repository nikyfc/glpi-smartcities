<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");
include "../inc/functions.php";

global $DB, $row_count, $type;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {	
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");	
	}  

if(!isset($_REQUEST["sel_item"])) {
	$id_item = $_GET["sel_item"];		
}

else {
	$id_item = $_POST["sel_item"];
}

if(isset($_REQUEST['itemtype'])) {
	$type = $_REQUEST['itemtype']; }
	
else {	

$itemtype = $_REQUEST['sel_item'];

	switch ($itemtype) {
	    case "1": $type = 'computer'; break;
	    case "2": $type = 'monitor'; break;
	    case "3": $type = 'software'; break;
	    case "4": $type = 'networkequipment'; break;
	    case "5": $type = 'peripheral'; break;
	    case "6": $type = 'printer'; break;
	    case "7": $type = 'phone'; break;
	} 
}


# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	$sel_ent = 0;
	$entidade = "";
	$entidade_u = "";
}
else {
	$entidade = "AND entities_id IN (".$sel_ent.") ";
	$entidade_u = "AND glpi_users.entities_id IN (".$sel_ent.") ";
}

?>

<html> 
<head>
<title> GLPI - <?php echo __('Assets') ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
  <meta http-equiv="content-language" content="en-us" />
  <meta charset="utf-8">
  
  <link rel="icon" href="../img/dash.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
  <link href="../css/styles.css" rel="stylesheet" type="text/css" />
  <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />  
  <link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />  
  
  <script language="javascript" src="../js/jquery.min.js"></script>  
  <link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
  <script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>
  <script src="../js/bootstrap-datepicker.js"></script>
  <link href="../css/datepicker.css" rel="stylesheet" type="text/css">
  <link href="../less/datepicker.less" rel="stylesheet" type="text/css">     
  
  <script src="./manufac.js"></script>
  <script src="./model.js"></script>
  
	<script src="../js/media/js/jquery.dataTables.min.js"></script>
	<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
	<script src="../js/media/js/dataTables.bootstrap.js"></script> 
	<link href="../js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
	<script src="../js/extensions/TableTools/js/dataTables.tableTools.js"></script>
	
	<style type="text/css">	
		select { width: 60px; }
		table.dataTable { empty-cells: show; }
	   a:link, a:visited, a:active { text-decoration: none;}
	</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 

<div id="charts" class="row-fluid chart"> 
<div id="pad-wrapper" >
<div id="head-rel" class="row-fluid" style="height:400px;">

<style type="text/css">
a:link, a:visited, a:active {
	text-decoration: none
	}
a:hover {
	color: #000099;
	}
	
.carregando {
display: none;
}	

.sel_fab .sel_mod {
display: block;
}	
</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_graf"> <?php echo __('Assets') ?> </div>
	
		<div id="datas-tec3" class="span12 row-fluid" >
 
		<form id="form1" name="form1" class="form_rel" method="post" action="rel_assets.php?con=1" style="margin-left: 28%;"> 
		<table border="0" cellspacing="0" cellpadding="10" bgcolor="#efefef" >
		<tr>			
			<td style="margin-top:2px; width:110px;"><?php echo __('Period'); ?>: </td>	
			<td style="width: 200px;">
		
		<?php

		$url = $_SERVER['REQUEST_URI']; 
		$arr_url = explode("?", $url);
		$url2 = $arr_url[0];
		    
			echo'
			<table style="margin-top:0px;" border=0>
				<tr>
					<td>
					   <div class="input-group date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
					    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="'.$data_ini.'" >		    	
					    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
				    	</div>
					</td>
					<td>&nbsp;</td>
					<td>
				   	<div class="input-group date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
					    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="'.$data_fin.'" >		    	
					    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
				    	</div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table> ';
			?>	
		
		<script language="Javascript">		
			$('#dp1').datepicker('update');
			$('#dp2').datepicker('update');		
		</script>
		
		</td>
		</tr>
		<tr><td height="12px"></td></tr>
		<tr>
		<td style="margin-top:2px; width:100px;"><?php echo __('Type'); ?>: </td>		
			<td style="margin-top:2px;">			
			<?php
			
			// lista de tipos			
			echo "
			<select id='sel_item' name='sel_item' style='width: 300px; height: 27px;' autofocus onChange=\"ajaxComboBox('manufac.php','sel_fab');\">
				<option value='0'> ---- </option>
				<option value='1'>".__('Computer')."</option>
				<option value='2'>".__('Monitor')."</option>
				<option value='3'>".__('Software')."</option>
				<option value='4'>".__('Network')."</option>
				<option value='5'>".__('Device')."</option>
				<option value='6'>".__('Printer')."</option>
				<option value='7'>".__('Phone')."</option>
			</select> ";	
			?>
			</td>
		</tr>
		<tr><td height="12px"></td></tr>		
		<tr>
			<td style="margin-top:2px; width:100px;"><?php echo __('Manufacturer'); ?>:  </td>		
			<td style="margin-top:5px;">
			<span class="carregando">Wait, loading...</span>
				<select name="sel_fab" id="sel_fab" class="sel_fab" style="width: 300px; height: 27px;" autofocus onChange="ajaxComboBox2('model.php','sel_mod');">
					<option value="0"><?php echo __('Select a type','dashboard'); ?></option> 
				</select>		
			</td>
		</tr>
		<tr><td height="12px"></td></tr>		
		<tr>
			<td style="margin-top:2px; width:165px;"><?php echo __('Model')."/". __('Version'); ?>: </td>		
			<td style="margin-top:2px;">	  
				<select name="sel_mod" id="sel_mod" class="sel_mod" style="width: 300px; height: 27px;" autofocus>
					<option value="0"><?php echo __('Select a manufacturer','dashboard'); ?></option>
				</select>			
			</td>
		</tr>
			
		<tr><td height="20px"></td></tr>
		<tr>
		<td colspan="2" align="center">		 
			<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
			<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
		</td>
		</tr>			
	</table>
<?php Html::closeForm(); ?>

	</div>
	</div>	

<?php 


if(isset($_GET['con'])) {
	$con = $_GET['con'];
}
else {
	$con = 0;	
}

if($con == "1") {

if(!isset($_POST['date1']))
{	
	$data_ini2 = $_GET['date1'];	
	$data_fin2 = $_GET['date2'];
}

else {	
	$data_ini2 = $_POST['date1'];	
	$data_fin2 = $_POST['date2'];	
}  


if(isset($_REQUEST['itemtype'])) {
	$type = $_REQUEST['itemtype']; 
	}
	
else {	

$itemtype = $id_item;

	switch ($itemtype) {
	    case "1": $type = 'computer'; break;
	    case "2": $type = 'monitor'; break;
	    case "3": $type = 'software'; break;
	    case "4": $type = 'networkequipment'; break;
	    case "5": $type = 'peripheral'; break;
	    case "6": $type = 'printer'; break;
	    case "7": $type = 'phone'; break;
	} 
}

if(!isset($_REQUEST["sel_item"])) { $id_item = 0; }
else { $id_item = $_REQUEST["sel_item"]; }

if(isset($_REQUEST["sel_fab"]) && $_REQUEST["sel_fab"] != '0') { $id_fab = $_REQUEST["sel_fab"]; }
else { $id_fab = ''; }

if(isset($_REQUEST["sel_mod"]) && $_REQUEST["sel_mod"] != '0')
	{ 
		if($_REQUEST["sel_mod"] != '') {
			 $id_mod = $_REQUEST["sel_mod"]; $model = "AND ".$type."models_id = ".$id_mod.""; 
		} 
	}

else { $id_mod = ''; $model = '';}

if($data_ini2 == $data_fin2) {
	$datas2 = "LIKE '".$data_ini2."%'";	
}	

else {
	$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";	
}

// Chamados
if($id_mod == '') {

	if($type != 'software') {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			".$entidade."
			ORDER BY name";
			
			$result_cham = $DB->query($sql_cham);			
			
			$consulta1 = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			".$entidade."
			ORDER BY name";
			
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;	
		}	
		
		else {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			".$entidade."
			ORDER BY name ";
			 			
			$result_cham = $DB->query($sql_cham);
			
					
			$consulta1 = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE manufacturers_id = ".$id_fab."
			AND is_deleted = 0
			".$entidade."
			ORDER BY name";
			
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;
			}
	
	}

else {

	if($type != 'software') {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND ".$type."models_id = ".$id_mod."
			AND is_deleted = 0
			".$entidade."
			ORDER BY name ";			
			
			$result_cham = $DB->query($sql_cham);
			
			//fim paginacao 1	
			$consulta1 = 
			"SELECT id, name
			FROM glpi_".$type."s
			WHERE manufacturers_id = ".$id_fab."
			AND ".$type."models_id = ".$id_mod."
			AND is_deleted = 0
			".$entidade."
			ORDER BY name ";
		
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;
		}
		
		else {
			$sql_cham = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE id = ".$id_mod."	
			".$entidade."		
			ORDER BY name ";
		
			
			$result_cham = $DB->query($sql_cham);
			
			//fim paginacao 1	
			$consulta1 = 
			"SELECT id, name
			FROM glpi_softwares
			WHERE id = ".$id_mod."
			".$entidade."			
			ORDER BY name ";
		
			$result_cons1 = $DB->query($consulta1);	
			
			$conta_cons = $DB->numrows($result_cons1);	
			$consulta = $conta_cons;
		}		
		
	}


if($consulta > 0) {

//fabricante
	$sql_fab = "SELECT name
			 		FROM glpi_manufacturers
			 		WHERE id = ".$id_fab." ";
	
	$result_fab = $DB->query($sql_fab);
	$fab = $DB->fetch_assoc($result_fab);	

//listar chamados

echo "

<div class='well info_box row-fluid col-md-12 report-lg' style='margin-left: -1px;'>

<table class='row-fluid'  style='margin-bottom: 25px; font-size: 18px; font-weight:bold;' cellpadding = 1px >
	<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Type').": </span>". __(ucfirst($type)) ." </td>
	<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Manufacturer').": </span>". $fab['name'] ." </td>
	<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Quantity','dashboard').": </span>".$consulta." </td>
	<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
	".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." 
	</td>
</table>

<table id='asset' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
	<thead>
		<tr>
			<th style='text-align:center; font-size: 12px; font-weight:bold; cursor:pointer;'> ".__('Name')." </th>
			<th style='text-align:center; font-size: 12px; font-weight:bold; cursor:pointer;'> ".__('Model')."/". __('Version')." </th>
			<th style='text-align:center; font-size: 12px; font-weight:bold; cursor:pointer;'> ".__('Serial')." </th>
			<th style='text-align:center; font-size: 12px; font-weight:bold; cursor:pointer; text-align:center;'> ".__('Tickets','dashboard')." </th>
		</tr>
	</thead>
<tbody>";


while($row = $DB->fetch_array($result_cham)){

if($type == 'software') {	
	
	$sql_item = "SELECT id, name
			 		FROM glpi_softwares
			 		WHERE id = " . $row['id'] . "			 		
			 		".$entidade." ";
}
else	{

	$sql_item = "SELECT id, name, serial
			 		FROM glpi_".$type."s
			 		WHERE id = " . $row['id'] . "			 		
			 		AND is_deleted = 0
			 		". $model ."
			 		".$entidade."";
}

	$result_item = $DB->query($sql_item);		
	$row_item = $DB->fetch_assoc($result_item);
	
	//contar chamados
	$sql_count = "SELECT count(id) AS conta
			 		FROM glpi_tickets
			 		WHERE itemtype = '" . ucfirst($type) . "'
			 		AND items_id = " . $row['id'] . " 
					AND is_deleted = 0 
					AND date ".$datas2." ";
	
	$result_count = $DB->query($sql_count);
	$row_count = $DB->fetch_assoc($result_count);	
	
	
	//fabricantes
	if($id_fab != '') {	
	 
		$sql_fab = "SELECT name
				 		FROM glpi_manufacturers
				 		WHERE id = ".$id_fab." ";
		
		$result_fab = $DB->query($sql_fab);
		$row_fab = $DB->fetch_assoc($result_fab);	
	}
	
	//modelo	 
	if($id_mod != '' ) {
		
		if($type != 'software') {
			$sql_mod = "SELECT gtm.name AS name
							FROM glpi_".$type."s gt, glpi_".$type."models gtm
							WHERE gt.".$type."models_id = ".$id_mod."
							AND gt.is_deleted = 0
							AND gt.".$type."models_id = gtm.id
							AND gt.id = ".$row['id']." ";
						
			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);		
		}
		
		else {
			$sql_mod = "SELECT id, name
							FROM `glpi_softwareversions`
							WHERE `softwares_id` = ".$row['id']."";
				
			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);			
			  }
	}	
	
	else { 
	
		if($type != 'software') {
			$sql_mod = "SELECT gtm.id AS id, gtm.name AS name
							FROM glpi_".$type."models gtm, glpi_".$type."s gt						
							WHERE gt.".$type."models_id = gtm.id
							AND gt.manufacturers_id = ".$id_fab."
							AND gt.id = ".$row['id']."
							AND gt.is_deleted = 0 ";		
			
			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);
		}	
		else {
			$sql_mod = "SELECT id, name
			FROM glpi_softwareversions
			WHERE id = ".$row['id']."			
			ORDER BY name";		

			$result_mod = $DB->query($sql_mod);
			$row_mod = $DB->fetch_assoc($result_mod);	
		
		}	
	}	
		 				
echo "	
	<tr>
		<td style='vertical-align:middle;'><a href=".$CFG_GLPI['root_doc']."/front/".$type.".form.php?id=". $row_item['id'] ." target=_blank >".$row_item['name']." (".$row_item['id'].")</a></td>
		<td style='vertical-align:middle;'> ". $row_mod['name'] ." </td>
		<td style='vertical-align:middle;'> ". $row_item['serial'] ." </td>				
		<td style='vertical-align:middle; text-align:center;'> <a href='rel_assets_tickets.php?con=1&itemtype=". $type."&sel_item=".$row['id']."&sel_fab=".$id_fab."&date1=".$data_ini2."&date2=".$data_fin2."' target=_blank>". $row_count['conta'] ." </a></td>
	</tr>";
}

echo "</tbody>
		</table>
		</div><br><p>"; ?>

<script type="text/javascript" charset="utf-8">

$('#asset')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');

$(document).ready(function() {
    oTable = $('#asset').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "aaSorting": [[0,'desc']], 
        "iDisplayLength": 25,
    	  "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], 

        "sDom": 'T<"clear">lfrtip',
         "oTableTools": {
         "aButtons": [
             {
                 "sExtends": "copy",
                 "sButtonText": "<?php echo __('Copy'); ?>"
             },
             {
                 "sExtends": "print",
                 "sButtonText": "<?php echo __('Print','dashboard'); ?>",
                 "sMessage": "<div id='print' class='info_box row-fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' id='print_tb' class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Type'); ?> : </span><?php echo __($typeuc); ?> </td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Manufacturer'); ?> : </span><?php echo $fab['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Quantity','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>"
             },
             {
                 "sExtends":    "collection",
                 "sButtonText": "<?php echo _x('button', 'Export'); ?>",
                 "aButtons":    [ "csv", "xls",
                  {
                 "sExtends": "pdf",
                 "sPdfOrientation": "landscape",
                 "sPdfMessage": ""
                  } ]
             }
         ]
        }
		  
    });    
} );
		
</script> 

<?php

echo "</div><br>";

}

else {
	
echo "
	<div id='nada_rel' class='well info_box row-fluid col-md-12'>
	<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
	<tr></tr>
	</table></div>";	
	}	

}
?>

<script type="text/javascript" >
	$(document).ready(function() { $("#sel_item").select2(); });
	$(document).ready(function() { $("#sel_fab").select2(); });
	$(document).ready(function() { $("#sel_mod").select2(); });
</script>

			</div>
		</div>
	</div>
</div>

</body> 
</html>
