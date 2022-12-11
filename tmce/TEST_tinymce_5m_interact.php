<?php session_start(); ?>
<html>
	<head>
		<title>tinyMCE New Engine Test</title>
	</head>
	<body>
<?php

	// SOLLTE IN EINEM IFRAME GESETZT WERDEN

	$_SESSION['lang'] = "tr";

	// INKLUDIERE DIE 5M-WARE-SCHNITTSTELLE FÜR TINYMCE
	require_once "tinymce_5m_interact.php";

	// ERZEUGE EINE OBJEKTINSTANZ AUS DER KLASSE
	$tmce = new tinymce_5m_interact();

	// HAUPTPFADE SETZEN
	$tmce->core['editor.path'] = "http://localhost:8888/5mware4/test/wepi_basis/tmce/tinymce"; // Hier liegt der TinyMCE-Editor
	$tmce->core['life.cycle.path'] = "/system/iohandle/lifeCycle";           // Hier liegen die WEPI-Session-Dateien
	$tmce->core['style.path'] = "http://localhost:8888/5mware4/test/wepi_basis/tmce";          // Pfad, in der die CSS-Datei liegt

	// SPRACHE ÄNDERN
	$tmce->language = $_SESSION['lang'];

	// GRÖßENVERHÄLTNIS ÄNDERN
	$tmce->width = "1100";

	// PLUGINS ANBINDEN
	$tmce->plugin[0]['name'] = "tmce_5M_Report_Table";
	$tmce->plugin[0]['text'] = "Report-Table";
	$tmce->plugin[0]['info'] = "Open the Report-Table-Dialog";
	$tmce->plugin[0]['code'] = "/tmce/5MPlugins/report/plg_5m_report_table.js";
	$tmce->plugin[0]['resc'] = "/tmce/5MPlugins/report/report_table";
	$tmce->plugin[0]['type'] = "dialog";
	$tmce->plugin[0]['html'] = "/system/plugins/5mware/tmce/5MPlugins/report/plg_5m_report_table.html";
	$tmce->plugin[0]['args'] = "S[farbe], S[innenAbstand], S[aussenAbstand], S[vordefinierteZeilenMenge], S[vordefinierteSpaltenMenge]";
	$tmce->plugin[0]['width']= 500;
	$tmce->plugin[0]['height']=380;
	$tmce->plugin[0]['sepr'] = true;

	// PLUGINS ANBINDEN
	$tmce->plugin[1]['name'] = "tmce_5M_Zoom_Picture";
	$tmce->plugin[1]['text'] = "Zoomable Picture";
	$tmce->plugin[1]['info'] = "Open the Zoomable-Picture-Dialog";
	$tmce->plugin[1]['code'] = "/tmce/plg_5m_zoom_pic.js";
	$tmce->plugin[1]['resc'] = "/tmce/res/zoom_pic";
	$tmce->plugin[1]['type'] = "dialog";
	$tmce->plugin[1]['html'] = "/tmce/plg_5m_zoom_pic.html";
	$tmce->plugin[1]['args'] = "S[farbe], S[innenAbstand], S[aussenAbstand], S[vordefinierteZeilenMenge], S[vordefinierteSpaltenMenge]";
	$tmce->plugin[1]['width']= 600;
	$tmce->plugin[1]['height']=320;
	$tmce->plugin[1]['sepr'] = true;

	// PLUGINS ANBINDEN
	$tmce->plugin[2]['name'] = "tmce_5M_Banner";
	$tmce->plugin[2]['text'] = "Banner";
	$tmce->plugin[2]['info'] = "Open the Banner-Table-Dialog";
	$tmce->plugin[2]['code'] = "/5MPlugins/Banner/plg_5m_banner.js";
	$tmce->plugin[2]['resc'] = "/5MPlugins/Banner/banner";
	$tmce->plugin[2]['type'] = "dialog";
	$tmce->plugin[2]['html'] = "/5MPlugins/Banner/plg_5m_banner.php";
	$tmce->plugin[2]['args'] = "S[farbe], S[innenAbstand], S[aussenAbstand], S[vordefinierteZeilenMenge], S[vordefinierteSpaltenMenge]";
	$tmce->plugin[2]['width']= 680;
	$tmce->plugin[2]['height']=380;
	$tmce->plugin[2]['sepr'] = true;

	// AKTIONEN DEFINIEREN
	$tmce->run[0] = array();
	$tmce->run[0]['do']     = "save";
	$tmce->run[0]['name']   = "mode"; // $_GET, $_POST, $_SESSION-Varname
	$tmce->run[0]['value']  = "saveDOC";
	$tmce->run[0]['insert'] = "SELECT * FROM myTable WHERE id = G[idnummer] AND incDate = G[eingangsDatum] And incLang = S[lang] ORDER BY id";
	$tmce->run[0]['update'] = "UPDATE myTable SET incDate = G[eingangsDatum] WHERE id = G[idnummer]";

	// INITIALISIERT DIE AKTIONEN
	$tmce->init();

//	echo $tmce->run[0]['insert'];

	// AKTIONEN STEUERN
	$tmce->process();
echo trim($tmce->core['editor.path']).'/../5MPlugins/subsys/imgs/tabs/buttons/blue.png';
	// EDITOR LADEN
	$tmce->load( "editor1" ); // Erster Instanz

?>
	</body>
</html>
