<html>
	<head>
		<title>Data</title>
	</head>

	<meta http-equiv="content-type" content="text/html; charset=utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<script type = "text/javascript" src = "wepi_core.js"></script>
	<script type = "text/javascript" src = "wepi_categorial_sidebar.js"></script>

	<style type = "text/css">
		body {
			padding: 0px;
			margin: 0px;
		}
		.sidebar {
			padding: 4px;
			width: 240px;
			margin: 0px;
			padding: 0px;
		}
		.sidebar div {
			margin: 0px;
			padding: 0px;		
		}
		.sidebar .menu {
			border-top-left-radius: 5px;
			border-bottom-left-radius: 5px;
			line-height: 30px;
			padding: 2px; padding-left: 10px;
			padding-bottom: 1px;
			cursor: pointer;
			text-shadow: 0px 0px 12px #ffffff;
		}
		.sidebar .menu:hover {
			padding: 0px; padding-left: 8px;
			padding-right: 2px;
			border-top: 2px solid #ffffff;
			border-left: 2px solid #ffffff;
			border-bottom: 2px solid #ffffff;
			background: url(categSBIHover.jpg) no-repeat center right rgb(249, 253, 255);
		}
		.sidebar .popup {
			position: absolute; margin-left: 220px;
			margin-top: -32px; line-height: 30px;
			background: url(categPopup.jpg) no-repeat center left rgb(249, 253, 255);
			border-top: 2px solid #ffffff;
			border-right: 2px solid #ffffff;
			border-bottom: 2px solid #ffffff;
			display: none; cursor: default;
			box-shadow: 2px 2px 3px rgb(70,85,150);
			border-top-right-radius: 5px;
			border-bottom-right-radius: 5px;
			border-bottom-left-radius: 5px;
			overflow: hidden;
		}
		.sidebar .link {
			padding-left: 10px;
			padding-right: 40px;
			border-left: 2px solid #ffffff;
			cursor: pointer; color: #000000;
			line-height: 30px; font-size: 12px;
			text-shadow: 0px 0px 2px #ffffff;
		}
		.sidebar .link:hover {
			background-color: rgb(244, 248, 250);
			text-shadow: 0px 0px 4px #ffffff; color: #000000;
			background: url(blue.png) repeat-x bottom center;
		}
		.sidebar .link:active {
			background-color: rgb(244, 248, 250);
			text-shadow: 0px 0px 4px #ffffff; color: #000000;
			background: url(blue_back.png) repeat-x top center;
		}
		.sidebar .nextColumn {
			border-left: 2px solid #ffffff;
		}
		.sidebar .title {
			padding-left: 10px;
			padding-right: 40px;
			border-left: 2px solid #ffffff;
			line-height: 30px; color: #000000;
			text-shadow: 0px 0px 8px #ffffff;
			font-weight: bold; font-size: 16px;
		}
		.sidebar .headline {
			padding-left: 10px;
			padding-right: 40px;
			border-left: 2px solid #ffffff;
			line-height: 30px;
			font-weight: bold;
			color: #000000; font-size: 14px;
			text-shadow: 0px 0px 8px #ffffff;
		}
		.sidebar .label {
			padding-left: 10px;
			padding-right: 40px;
			border-left: 2px solid #ffffff;
			line-height: 30px;
			font-weight: normal;
			font-style: italic;
			font-size: 12px;
			color: #000000;
			text-shadow: 0px 0px 12px #ffffff;
		}
	</style>

	<body>

		<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%" height = "100%"><tr>
		<td valign = "top" style = "background: url(sidebar.jpg) no-repeat fixed">
		<script type = "text/javascript">

			var sidebar = new wepiCategorialSidebar( "sidebar", "sidebar" );

			sidebar.begin();

			sidebar.add.menu( "software", "javascript:alert('Alle Kinder spielen Tabu');", "Software", "" );
			sidebar.add.item( "begin" );
				sidebar.add.item( "column" );
					sidebar.add.item( "title", "Sofware" );
					sidebar.add.item( "link", "software.apple", "javascript:alert('Apple Software');", "Apple Inc.", "Apple iTunes, iMovie, iMusic, QuickTime and more..." );
					sidebar.add.item( "link", "software.adobe", "javascript:alert('Apple Software');", "Adobe Incorporated", "PhotoShop, Premiere, Acrobat Reader, Flash Player" );
					sidebar.add.item( "link", "software.mscorp", "javascript:alert('Apple Software');", "Microsoft Corporation", "Windows, Office, XBox" );
					sidebar.add.item( "link", "software.google", "javascript:alert('Apple Software');", "Google Inc.", "YouTube, Books" );
					sidebar.add.item( "link", "software.ibm", "javascript:alert('Apple Software');", "IBM Corporation", "OS/2 Warp, Lotus 123, AIX, Works" );
				sidebar.add.item( "ends" );
				sidebar.add.item( "column" );
					sidebar.add.item( "title", "Hardware" );
					sidebar.add.item( "link", "software.1", "javascript:alert('aa');", "Macintosh" );
					sidebar.add.item( "link", "software.2", "javascript:alert('aa');", "Amiga" );
					sidebar.add.item( "link", "software.3", "javascript:alert('aa');", "PC" );
				sidebar.add.item( "ends" );
			sidebar.add.item( "close" );

			sidebar.add.menu( "hardware", "javascript:alert('hello');", "Marken", "" );
			sidebar.add.item( "begin" );
				sidebar.add.item( "column" );
					sidebar.add.item( "headline", "Prozessor" );
					sidebar.add.item( "link", "hardware.1", "javascript:alert('Apple Software');", "Intel" );
					sidebar.add.item( "headline", "Komponente" );
					sidebar.add.item( "link", "hardware.2", "javascript:alert('Apple Software');", "Creative Labs" );
					sidebar.add.item( "link", "hardware.3", "javascript:alert('Apple Software');", "Samsung" );
					sidebar.add.item( "link", "hardware.4", "javascript:alert('Apple Software');", "Gigabyte" );
					sidebar.add.item( "link", "hardware.5", "javascript:alert('Apple Software');", "Asus" );
					sidebar.add.item( "link", "hardware.6", "javascript:alert('Apple Software');", "Acer" );
					sidebar.add.item( "link", "hardware.7", "javascript:alert('Apple Software');", "Hewelett Packard" );
					sidebar.add.item( "link", "hardware.8", "javascript:alert('Apple Software');", "Packad Bell" );
					sidebar.add.item( "headline", "Server" );
					sidebar.add.item( "link", "hardware.9", "javascript:alert('Apple Software');", "SCO" );
				sidebar.add.item( "ends" );
				sidebar.add.item( "column" );
					sidebar.add.item( "label", "Wir bieten alle Hardware-Komponenten und externe Peripherie sehr g&uuml;nstig und mit mindestens 2 jähriger Garantie.", 200 );
				sidebar.add.item( "ends" );
			sidebar.add.item( "close" );

			sidebar.close();

		</script>
		</td><td valign = "top" width = "100%" style = "padding: 20px;">
			Alles wird gut ..... :-)
			Muhaahahahahaha...
		</td></tr></table>

	</body>
</html>
