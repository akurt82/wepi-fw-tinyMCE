<?php
	session_start();
?>
<html>
	<head>
		<title>IchShoppe.de</title>
	</head>
  <meta http-equiv="content-type" content="text/html; charset=utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 

  <script type = "text/javascript" src = "api/wepi_core.js"></script>
  <script type = "text/javascript" src = "api/wepi_serv.js"></script>

    <script type = "text/javascript">

		var svr = new wepiServ(); svr.sessionID = '<?php echo session_id(); ?>';

		function hol_die_daten(){
			$_id("inhalt").innerHTML = svr.Return("hol_dir_daten_aus_dem_server");
		}

    </script>

	<body>
	
		<div id = "inhalt">...</div>
		
		<input type = "button" value = "Auslesen" onclick = "javascript:hol_die_daten();" />
	
	</body>
</html>
