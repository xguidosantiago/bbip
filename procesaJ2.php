<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>procesa Datos J2</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div> <?php include "index.php"; ?> </div>

	<?php 

	if(!isset($_POST['J2_INT'])){

	}else {

	$j2_int = $_POST['J2_INT'];
	$j2_ip = $_POST['J2_IP'];
	$j2_lo0 = $_POST['J2_LO0'];
	$prefix_name = $_POST['PREFIX_NAME'];
	$h3_name = $_POST['H3_NAME'];
	$h3_lo0 = $_POST['H3_LO0'];
	$h3_lo1 = $_POST['H3_LO1'];


	//h3 nombre sin numero
	$trimH3 = substr($h3_name, 0, -2);

	//generar IP nexthop segun J2
	$octetos = explode(".", $j2_ip);
	$ultimo_octeto = intval($octetos[3]) + 1;
	$nuevos_octetos = array($octetos[0], 
							$octetos[1], 
							$octetos[2], 
							$ultimo_octeto);
	$h3_ip = implode(".", $nuevos_octetos);

	//comunidades
	$h3_comm = $_POST['H3_COMM'];
	$h3_comm1 = $h3_comm . "1";
	$h3_comm2 = $h3_comm . "2";


	// obtener fin de palabra del h3
	$partes = explode("-", $h3_name);
	$ultimoValor = end($partes);
	
	?>

	<div class="inputs2">
		<h4>NOTA: LAS LINEAS COMENTADAS YA DEBERÍAN ESTAR CONFIGURDAS. CORROBORAR EN EL EQUIPO ANTES DE PEGAR</h4>

		<h1>CONFIGURACIÓN</h1>
		<h3>COMUNIDADES</h3>

		set policy-options community <span class="green-text">H-<?php echo $trimH3 ?>-1</span> members <span class="green-text">200:<?php echo $h3_comm1 ?></span> 
		<br>set policy-options community <span class="green-text">H-<?php echo $trimH3 ?>-2</span> members <span class="green-text">200:<?php echo $h3_comm2 ?></span> 
		<br>
		<br><span class="commented">#set policy-options community C-H-CORE members 100:100</span> 
		<br><span class="commented">#set policy-options community C-J-CORE members 200:200</span> 

		<h3>POLICIES</h3>

		set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term DENY_C-H-CORE from protocol bgp
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term DENY_C-H-CORE from community C-H-CORE
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term DENY_C-H-CORE then reject
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term DENY_C-J-CORE from protocol bgp
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term DENY_C-J-CORE from community C-J-CORE
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term DENY_C-J-CORE then reject

		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term SET_C-HL3-HL4 from protocol bgp
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term SET_C-HL3-HL4 from route-filter 0.0.0.0/0 prefix-length-range /32-/32
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term SET_C-HL3-HL4 then community add <span class="green-text">H-<?php echo $trimH3 ?>-1</span>
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term SET_C-HL3-HL4 then accept
		<br>set policy-options policy-statement <span class="green-text">FROM-<?php echo $h3_name?></span> term reject then reject

		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term LocalL0 from rib inet.3
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term LocalL0 from route-filter <span class="green-text"><?php echo $j2_lo0 ?>/32</span> exact
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term LocalL0 then aigp-originate distance 1
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term LocalL0 then next-hop self
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term LocalL0 then accept

		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_C341 from protocol bgp
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_C341 from community <span class="green-text">H-<?php echo $trimH3 ?>-1</span>
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_C341 then reject
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_C342 from protocol bgp
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_C342 from community <span class="green-text">H-<?php echo $trimH3 ?>-2</span>
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_C342 then reject

		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT from protocol bgp
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT then community add C-J-CORE
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT then next-hop self
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT then accept

		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_FUSION from protocol ldp
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_FUSION from rib inet.3
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_FUSION from route-filter-list LOOPBACKS-FUSION
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term DENY_FUSION then reject

		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY from protocol ldp
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY from rib inet.3
		set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY from route-filter 0.0.0.0/0 prefix-length-range /32-/32
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY then aigp-originate
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY then community add C-J-CORE
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY then next-hop self
		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term PERMIT_LEGACY then accept

		<br>set policy-options policy-statement <span class="green-text">TO-<?php echo $h3_name?></span> term reject then reject


		<h3>INTERFACES FISICAS - AGREGAR PUERTO!!</h3>

		set interfaces et-X/X/X description "Conexion con <span class="green-text"><?php echo $h3_name ?></span> - (x/x/x) AD: xxx-ADRED-xxx-0 ID: xxx tipo: TRONCAL - <span class="green-text"><?php echo $j2_int?></span>"
		<br>set interfaces et-X/X/X gigether-options 802.3ad <span class="green-text"><?php echo $j2_int?></span>

		<h3>BUNDLE DATOS</h3>

		set interfaces <span class="green-text"><?php echo $j2_int?></span> flexible-vlan-tagging
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> native-vlan-id 1
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> mtu 9108
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> encapsulation flexible-ethernet-services
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> aggregated-ether-options lacp active
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 0 description "Conexion con <?php echo $h3_name?> - ID: xxxxx ADRED: xxxx-x  tipo: TRONCAL - <?php echo $j2_int?>"
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 0 vlan-id 1
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 0 family inet address <?php echo $j2_ip ?>/31
		<br>set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 0 family mpls

		<h3>BUNDLE MULTICAST</h3>
		<br><span class="commented">#set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 3 description "MULTICAST <?php echo $h3_name?>"</span>
		<br><span class="commented">#set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 3 encapsulation vlan-ccc</span>
		<br><span class="commented">#set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 3 vlan-id 3</span>
		<br><span class="commented">#set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 3 input-vlan-map pop</span>
		<br><span class="commented">#set interfaces <span class="green-text"><?php echo $j2_int?></span> unit 3 output-vlan-map push</span>

		<h3>ROUTING</h3>

		set policy-options policy-statement IGP-EXPORT term PERMIT from next-hop <span class="green-text"><?php echo $h3_lo1?></span>
		<br>set policy-options policy-statement PL-RMT_LOOPBACK term RMT_LOOPBACK_1 from route-filter <span class="green-text"><?php echo $h3_lo1 ?>/32</span> exact

		<br>set routing-options static route <span class="green-text"><?php echo $h3_lo1 ?>/32</span>  next-hop <span class="green-text"><?php echo $h3_ip ?></span>
		<br>set routing-options static route <span class="green-text"><?php echo $h3_lo1 ?>/32</span>  no-readvertise
		<br>set protocols bgp group TO-HL3 neighbor <span class="green-text"><?php echo $h3_lo1 ?></span>  description <span class="green-text"><?php echo $h3_name?></span>
		<br>set protocols bgp group TO-HL3 neighbor <span class="green-text"><?php echo $h3_lo1 ?></span>  import <span class="green-text">FROM-<?php echo $h3_name?></span>
		<br>set protocols bgp group TO-HL3 neighbor <span class="green-text"><?php echo $h3_lo1 ?></span>  authentication-key "$9$mf36tu1creO1ds24ZGQF36t0EcleM8"
		<br>set protocols bgp group TO-HL3 neighbor <span class="green-text"><?php echo $h3_lo1 ?></span>  export <span class="green-text">TO-<?php echo $h3_name?></span> 

		<h3>LDP Loopback 0</h3>
		set protocols ldp session <span class="green-text"><?php echo $h3_lo0 ?></span> 
	</div>
		<?php 	} ?>

</body>
</html>

<script>
	document.getElementById("J2_INT").value = "<?php echo $J2_int ?>";
	document.getElementById("J2_IP").value = "<?php echo $J2_ip ?>";
	document.getElementById("PREFIX_NAME").value = "<?php echo $prefix_name ?>";
	document.getElementById("PREFIX_INDEX").value = "<?php echo $prefix_index ?>";
	document.getElementById("H3_NAME").value = "<?php echo $h3_name ?>";
	document.getElementById("H3_LO0").value = "<?php echo $h3_lo0 ?>";
	document.getElementById("H3_LO1").value = "<?php echo $h3_lo1?>";
	document.getElementById("H3_COMM").value = "<?php echo $h3_comm ?>";
	document.getElementById("J2_LO0").value = "<?php echo $j2_lo0 ?>";
</script>