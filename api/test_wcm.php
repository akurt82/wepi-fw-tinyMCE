<?php
	session_start();
?>
<html>
	<head>
		<title>Data</title>
	</head>

	<meta http-equiv="content-type" content="text/html; charset=utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 

	<script type = "text/javascript" src = "wepi_core.js"></script>
	<script type = "text/javascript" src = "wepi_categ_menu.js"></script>

	<style type = "text/css">
		body {
			padding: 0px;
			margin: 0px;
		}
	</style>

	<body>

		<script type = "text/javascript">

			var wcm = new wepiCategMenu( "wcm" );

			wcm.style.dock = "top";

			wcm.item( "item", "home", "Startseite" );

			wcm.item( "item", "home.eintrag01", "Apple" );
			wcm.item( "item", "home.eintrag02", "Adobe" );
			wcm.item( "item", "home.eintrag03", "Google" );
			wcm.item( "separator", "home.sep1", "" );
			wcm.item( "item", "home.eintrag04", "Microsoft" );
			wcm.item( "item", "home.eintrag05", "IBM Corp." );
			wcm.item( "item", "home.eintrag06", "Intel Corp." );

			wcm.item( "item", "abou", "&Uuml;ber uns" );

			wcm.item( "topic", "abou.[0].hd1", "Kommerziell" )
			wcm.item( "item", "abou.[0].osx", "Apple Macintosh OS X" )
			wcm.item( "item", "abou.[0].red", "Microsoft Windows" )
			//wcm.item( "separator", "abou.[0].sep1", "" )
			wcm.item( "item", "abou.[0].os2", "IBM OS/2 Warp", "IBM OS/2 Warp Connect 4.5" )
			wcm.item( "item", "abou.[0].osc", "MenSys eComStation" )
			wcm.item( "headline", "abou.[0].hd2", "Open&nbsp;Source" )
			wcm.item( "item", "abou.[0].hai", "Haiku OS" )
			wcm.item( "item", "abou.[0].lin", "Linux" )

			wcm.item( "item", "abou.[1].osx2", "Apple iLife" )
			wcm.item( "item", "abou.[1].red2", "Microsoft Office" )
			wcm.item( "item", "abou.[1].os22", "IBM Lotus 123" )
			wcm.item( "item", "abou.[1].osc2", "Adobe inDesign" )
			wcm.item( "separator", "abou.[1].sep2", "" )
			wcm.item( "label", "abou.[1].lab1", "Alles wird gut :-)" )

			wcm.item( "item", "abou.[2].osx3", "Apple iTunes" )
			wcm.item( "item", "abou.[2].red3", "Microsoft Media Player" )
			wcm.item( "item", "abou.[2].os23", "IBM VisualAge" )
			wcm.item( "item", "abou.[2].osc3", "Adobe Premiere" )

			wcm.item( "item", "impr", "Impressum" );

			wcm.event.click = "klick_mich"; function klick_mich(key) {
				$_id("klicki").innerHTML = key;
			}

			print( wcm.get() );

		</script>

		<div id = "klicki"></div>

	</body>
</html>
