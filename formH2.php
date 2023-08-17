<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>TEMPLATE H2</title>
</head>
<body>

	<?php $ipRegex = "(([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\\.){3}([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";?>

<form action="procesaH2.php" method="POST">
  <div class="inputs">
  	<header>CONFIG H2</header>
  	<label for="H2_INT">H2 TRUNK INT</label><br>
    <input type="text" name="H2_INT" id="H2_INT" required><br>
    <label for="H2_IP">H2 WAN IP</label><br>
    <input type="text" name="H2_IP" id="H2_IP" pattern="<?php echo $ipRegex ?>" required><br>
    <label for="PREFIX_NAME">H2 OSPF IP-PREFIX NAME</label><br>
    <input type="text" name="PREFIX_NAME" id="PREFIX_NAME" required placeholder="Ej: HL3-NextHop"><br>
    <label for="PREFIX_INDEX">H2 OSPF IP-PREFIX INDEX</label><br>
    <input type="text" name="PREFIX_INDEX" id="PREFIX_INDEX" required><br>
    <label for="H3_NAME">H3 NAME</label><br>
    <input type="text" name="H3_NAME" id="H3_NAME" required><br>
    <label for="H3_LO0">H3 LO0</label><br>
    <input type="text" name="H3_LO0" id="H3_LO0" pattern="<?php echo $ipRegex ?>" required><br>
    <label for="H3_LO1">H3 LO1</label><br>
    <input type="text" name="H3_LO1" id="H3_LO1" pattern="<?php echo $ipRegex ?>" required><br>
    <label for="H3_COMM">H3 COMMUNITY DOMAIN</label><br>
    <select name="H3_COMM" id="H3_COMM" ><br>
	  <option value="10">Cuyo - Avenida</option>
	  <option value="11">Florencio Varela - Longchamps</option>
	  <option value="12">Flores - Cuyo</option>
	  <option value="13">Flores - Ramos Mejía</option>
	  <option value="14">Güemes - Merlo</option>
	  <option value="15">Güemes - San Justo</option>
	  <option value="16">Lomas de Zamora - Monte Chingolo</option>
	  <option value="17">Lomas de Zamora - Monte Grande</option>
	  <option value="18">Longchamps - Monte Grande</option>
	  <option value="19">Merlo - San Miguel</option>
	  <option value="20">Monte - Chingolo-Wilde</option>
	  <option value="21">Morón - San Miguel</option>
	  <option value="22">Ranelagh - Florencio Varela</option>
	  <option value="23">Ranelagh - Wilde</option>
	  <option value="24">San Miguel - Cuyo</option>
	  <option value="25">Avenida - Barracas</option>
	  <option value="26">Moron - San Justo</option>
	  <option value="30">Córdoba - Tucumán</option>
	  <option value="30">Corrientes - Rosario</option>
	  <option value="30">Corrientes - Zárate</option>
	  <option value="30">Rosario - Córdoba</option>
	  <option value="30">Zárate - Rosario</option>
	  <option value="31">Santa Rosa - Bahía Blanca</option>
	  <option value="31">Santa Rosa - Villa Mercedes</option>
	  <option value="31">Villa Mercedes - Chivilcoy</option>
	  <option value="31">Villa Mercedes - Córdoba</option>
	  <option value="31">Chivilcoy - Santa Rosa</option>
	  <option value="31">Villanueva - Villa Mercedes</option>
	  <option value="32">San Juan - Santa Lucía</option>
	  <option value="33">Gral. Paz - Villa Nueva</option>
	  <option value="34">Chivilcoy - Cuyo (Huawei)</option>
	  <option value="34">Chivilcoy - Cuyo</option>
	  <option value="34">Mar del Plata Centro - Bahía Blanca</option>
	  <option value="34">Mar del Plata Centro - Chivilcoy</option>
	  <option value="34">Bahía Blanca - Chivilcoy</option>
	  <option value="34">Bahía - Independencia</option>
	  <option value="35">Mar del Plata Centro - Mar del Plata Norte</option>
	  <option value="36">Rocha - Parque Gral. San Martín</option>
	  <option value="37">Rocha-Mar del Plata Norte</option>
	  <option value="38">Gral. Paz - Neuquén</option>
	  <option value="38">Neuquén - C. Valentina</option>
	  <option value="39">Bahía Blanca - Neuquén</option>
	  <option value="39">Bahía Blanca-Trelew</option>
	  <option value="39">Comodoro Rivadavia - Río Grande</option>
	  <option value="39">Río Gallegos - Comodoro Rivadavia</option>
	  <option value="39">Comodoro Rivadavia - Mosconi o Barrio Laprida</option>
	  <option value="39">Bariloche-Comodoro Rivadavia</option>
	  <option value="39">Comodoro Rivadavia - Pueyrredon</option>
	  <option value="39">Bariloche - Neuquén</option>
	</select>
	<br><br>

    <div class="buttons">
    	<button type="submit" id="submitBtn">Enviar</button>
    	<button type="button" class="clear" id="clear" onclick="clearFields();">Limpiar</button>
    </div>
  </div>
 
</form>
	
</body>
</html>

<script>	
		function clearFields(){
			document.getElementById('H2_INT').value = "";
			document.getElementById('H2_IP').value = "";
			document.getElementById('H3_NAME').value = "";
			document.getElementById('H3_LO1').value = "";
			document.getElementById('H3_LO0').value = "";
			document.getElementById('PREFIX_NAME').value = "";
			document.getElementById('PREFIX_INDEX').value = "";
		}
</script>	
