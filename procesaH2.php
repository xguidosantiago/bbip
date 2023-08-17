<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>procesa Datos H2</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div> <?php include "index.php"; ?> </div>

	<?php 

	if(!isset($_POST['H2_INT'])){

	}else {

	$h2_int = $_POST['H2_INT'];
	$h2_ip = $_POST['H2_IP'];
	$prefix_name = $_POST['PREFIX_NAME'];
	$prefix_index = $_POST['PREFIX_INDEX'];
	$h3_name = $_POST['H3_NAME'];
	$h3_lo0 = $_POST['H3_LO0'];
	$h3_lo1 = $_POST['H3_LO1'];


	//generar IP nexthop segun h2
	$octetos = explode(".", $h2_ip);
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
		<h3>FILTER + PREFIX:</h3>

		<span class="commented">#ip ip-prefix Any-Loopback index 10 permit 0.0.0.0 32 </span> 
		<br><span class="commented">#ip community-filter basic PERMIT:22927:22927 index 10 permit 22927:22927</span> 
		<br><span class="commented">#ip ip-prefix AGGREGATE-RMS index 10 permit 10.166.0.0 16</span> 
		<br><span class="commented">#ip community-filter basic From-IP-CORE index 10 permit 100:100</span> 
		<br><span class="commented">#ip community-filter basic From-IP-CORE index 20 permit 200:200</span> 
		<br>ip community-filter basic <span class="green-text">From-HL3-<?php echo $ultimoValor ?></span> index 10 permit 100:<span class="green-text"><?php echo $h3_comm1; ?></span>
		<br>ip community-filter basic <span class="green-text">From-HL3-<?php echo $ultimoValor ?></span> index 20 permit 100:<span class="green-text"><?php echo $h3_comm2; ?></span>

		<h3>#POLICIES:</h3>
		<br>
		#1
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Import deny node 10
		<br> if-match community-filter From-IP-CORE 
		<br>
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Import permit node 20
		<br> apply community <span class="green-text">100:<?php echo $h3_comm1; ?></span> additive
		<br>
		<br>
		<br>#2
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Export deny node 10
		<br> if-match community-filter <span class="green-text">From-HL3-<?php echo $ultimoValor ?></span>
		<br>
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Export permit node 20
		<br> if-match mpls-label
		<br> if-match ip-prefix Any-Loopback
		<br> apply community 100:100 additive
		<br> apply mpls-label
		<br>
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Export permit node 30
		<br> if-match ip-prefix Loopback0
		<br> apply community 100:100 additive
		<br> apply mpls-label
		<br> apply aigp 1 
		<br>
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Export permit node 40
		<br> if-match community-filter PERMIT:22927:22927 
		<br> apply community 100:100 additive
		<br> apply mpls-label
		<br>
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-Export permit node 50
		<br> if-match ip-prefix AGGREGATE-RMS
		<br> apply community 100:100 additive
		<br> apply mpls-label
		<br>
       
		<h3>Interfaz Datos</h3>

		interface <span class="green-text"><?php echo $h2_int ?></span>
		<br> mtu 9100
		<br> description Conexion con <span class="green-text"><?php echo $h3_name;?> <?php echo $h2_int ?></span> Tipo: TRONCAL
		<br> ip address <span class="green-text"><?php echo $h2_ip ?> 255.255.255.254</span>
		<br> trust upstream default
		<br> ip netstream inbound
		<br> ipv6 netstream inbound
		<br> mode lacp-static
		<br> lacp timeout fast
		<br> mpls
		<br> mpls ldp
		<br> port-queue-template QoS-FUSION outbound

		<h3>BGP</h3>

		BGP 22927
		<br><span class="commented">#group HL3-Client internal</span> 
		<br><span class="commented">#peer HL3-Client connect-interface LoopBack1</span> 
		<br><span class="commented">#peer HL3-Client password cipher %^%#Ziv9-Z3:A.|ea"Iu*$!IW=PH7ND]R1S;_)-0ttj:%^%#</span> 
		<br><span class="commented">#peer HL3-Client tracking delay 30</span> 
		<br> peer <span class="green-text"><?php echo $h3_lo1 ?></span> as-number 22927
		<br> peer <span class="green-text"><?php echo $h3_lo1 ?></span> group HL3-Client
		<br> peer <span class="green-text"><?php echo $h3_lo1 ?></span> description <span class="green-text"><?php echo $h3_name;?></span>
		<br>
		<br> ipv4-family unicast
		<br>  <span class="commented">#peer HL3-Client enable</span> 
		<br>  <span class="commented">#peer HL3-Client aigp</span> 
		<br>  <span class="commented">#peer HL3-Client reflect-client</span> 
		<br>  <span class="commented">#peer HL3-Client next-hop-local</span> 
		<br>  <span class="commented">#peer HL3-Client label-route-capability </span> 
		<br>  <span class="commented">#peer HL3-Client advertise-community</span> 
		<br>  peer <span class="green-text"><?php echo $h3_lo1 ?></span> enable
		<br>  y
		<br>  peer <span class="green-text"><?php echo $h3_lo1 ?></span> group HL3-Client
		<br>  peer <span class="green-text"><?php echo $h3_lo1 ?></span> route-policy <span class="green-text"><?php echo $h3_name;?></span>-Import import
		<br>  peer <span class="green-text"><?php echo $h3_lo1 ?></span> route-policy <span class="green-text"><?php echo $h3_name;?></span>-Export export
		<br>  peer <span class="green-text"><?php echo $h3_lo1 ?></span> advertise-ext-community

		<h3>prefix para redistribucion BGP TO OSPF</h3>
		ip ip-prefix <span class="green-text"><?php echo $prefix_name ?></span> index <span class="green-text"><?php echo $prefix_index ?></span>  permit <span class="green-text"><?php echo $h3_lo1 ?></span> 32
		<br>
		<h3>Estaticas</h3>

		ip route-static <span class="green-text"><?php echo $h3_lo1 ?></span> 255.255.255.255 <span class="green-text"><?php echo $h2_int ?></span> <span class="green-text"><?php echo $h3_ip ?></span> description To: <span class="green-text"><?php echo $h3_name;?></span> Loopback1
		<br>ip route-static <span class="green-text"><?php echo $h3_lo0 ?></span> 255.255.255.255 <span class="green-text"><?php echo $h2_int ?></span> <span class="green-text"><?php echo $h3_ip ?></span> preference 255 description To: <span class="green-text"><?php echo $h3_name;?></span> Loopback0
		<br>
		<h3>Multicast</h3>

		interface <span class="green-text"><?php echo $h2_int?>.3</span>
		<br> vlan-type dot1q 3
		<br> mtu 9100
		<br> description Conexion con <span class="green-text"><?php echo $h3_name;?> <?php echo $h2_int?>.3</span> - PIM Opcion A - tipo: TRONCAL
		<br> ip binding vpn-instance IPTV-MULTICAST
		<br> ip address <span class="green-text"><?php echo $h2_ip?></span> 255.255.255.254
		<br> statistic enable
		<br> trust upstream default
		<br> pim sm
		<br>
		<br>
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-IPTV-Import deny node 10
		<br> if-match community-filter From-IP-CORE 
		<br>#
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-IPTV-Import permit node 20
		<br> apply community 100:<?php echo $h3_comm1 ?> additive
		<br>#
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-IPTV-Export deny node 10
		<br> if-match community-filter From-HL3-<?php echo $ultimoValor ?> 
		<br>#
		<br>route-policy <span class="green-text"><?php echo $h3_name;?></span>-IPTV-Export permit node 20
		<br> apply community 100:100 additive
		<br> apply extcommunity rt 0:88
		<br><br>

		<h3>Multicast BGP</h3>
		bgp 22927
		<br>ipv4-family vpn-instance IPTV-MULTICAST
		<br> import-route direct
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> as-number 22927
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> description HL3 PIM Opcion A
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> connect-interface Eth-Trunk176.3
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> tracking delay 30
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> route-policy <span class="green-text"><?php echo $h3_name;?></span>-IPTV-Import</span> import
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> route-policy <span class="green-text"><?php echo $h3_name;?></span>-IPTV-Export</span> export
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> reflect-client
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> next-hop-local
		<br> peer <span class="green-text"><?php echo $h3_ip ?></span> advertise-community
		<br>
		<h3>Reflectors</h3>

		router bgp 22927
		<br> neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> remote-as 22927
		<br> neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> description <span class="green-text"><?php echo $h3_name ?></span>
		<br> neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> password 7 00510752104E5E4B3E341C
		<br> neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> update-source Loopback0
		<br>address-family vpnv4
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> activate
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> send-community both
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> route-reflector-client
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> route-map RM-DENY-IPTVRTFUSION in
		<br>address-family vpnv6
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> activate
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> send-community both
		<br>  neighbor <span class="green-text"><?php echo $h3_lo0 ?></span> route-reflector-client

		<h4>Reflector IPs</h4>

			RRBRR02:10.166.95.25
		<br>RRBRR03:10.166.95.26
		<br>RRCUY02:10.166.95.10
		<br>RRCUY03:10.166.95.11
	</div>
		<?php 	} ?>
</body>
</html>

<script>
	document.getElementById("H2_INT").value = "<?php echo $h2_int ?>";
	document.getElementById("H2_IP").value = "<?php echo $h2_ip ?>";
	document.getElementById("PREFIX_NAME").value = "<?php echo $prefix_name ?>";
	document.getElementById("PREFIX_INDEX").value = "<?php echo $prefix_index ?>";
	document.getElementById("H3_NAME").value = "<?php echo $h3_name ?>";
	document.getElementById("H3_LO0").value = "<?php echo $h3_lo0 ?>";
	document.getElementById("H3_LO1").value = "<?php echo $h3_lo1?>";
	document.getElementById("H3_COMM").value = "<?php echo $h3_comm ?>";
</script>