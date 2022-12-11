<?php

	/* ************************************************************************************************ *
	 * ************************************************************************************************ *
	 *
	 * COPYRIGHT 5M-WARE 2015(c)
	 * Abdülaziz Kurt, info@5m-ware.eu
	 * www.5m-ware.eu
	 *
	 * ------------------------------------------------------------------------------------------------
	 *
	 * DEPENDENCIES:
	 *
	 * wepi_core.js, wepi_serv.js, wepi_dyn* & wepi_sess* files, tinymce folder and files
	 * wepi_server_side.php, wepi_file_system.php
	 * mysql-Database with db_5m_tinymce*-Tables
	 *
	 * ------------------------------------------------------------------------------------------------
	 *
	 * DESCRIPTION:
	 *
	 * This class make it very easy to interact in session-mode with the tinyMCE-Editor and their
	 * installed Plug-In's on base of WEPI.
	 *
	 * ************************************************************************************************ *
	 * ************************************************************************************************ */

	class tinymce_5m_interact {

		/* ********************************************************************************** *
		 * ********************************************************************************** *
		 * Global interne parameters
		 * ********************************************************************************** *
		 * ********************************************************************************** */
		private $tinyMCELoaded = false;
		private $tinyMCE5MPlugins = "";

		/* ********************************************************************************** *
		 * ********************************************************************************** *
		 * Global öffentliche parameters
		 * ********************************************************************************** *
		 * ********************************************************************************** */
		// Core data for TinyMCE
		public $core = array();
		// Editor's width
		public $width = 1000;
		// Editor's height
		public $height = 400;
		// Preview-Box-Width
		public $preViewWidth = 600;
		// Preview-Box-Height
		public $preViewHeight = 500;
		// Editor's language
		public $language = "en";
		// Der Mode entscheidet, im welchem Modus der Editor geladen wird:
		// full = Alle Einstellungen werden wirksam
		// slim = Das nötigste Basis Editor
		// none = Das absolute Minimum
		// defl = Das volle TinyMCE-Editor selbst, ohne Plugins
		public $mode = "full";
		// Diverse Einstellungen
		public $unit = array();
		// Im neuen Editor können die Plugins alle einzeln angebunden werden.
		// Schemeta für jeden Entry ist wie folgt:
		// $this->plugin[n]['name'] >> Name des Plugins, um es mit einem Vorgang zu verknüpfen
		// $this->plugin[n]['text'] >> Name des Plugins im Edior
		// $this->plugin[n]['info'] >> ToolTip-Info des Plugins im Edior
		// $this->plugin[n]['code'] >> Datei für den JavaScript-Code, jedoch als PHP-Datei zu liefern
		// $this->plugin[n]['resc'] >> Resourcen-Verzeichnis für den Plugin selbst mit Bildern etc.
		// $this->plugin[n]['type'] >> 0 = Plugin ist ein in sich geschlossener Button, 1 = Es ist ein Dialog
		// $this->plugin[n]['html'] >> Liefert die HTML-Datei für den Dialog
		// $this->plugin[n]['args'] >> Dem Dialog-Fenster können SESSION-Argumente mitgegeben werden
		public $plugin = array();
		// Speicher-, Lade- und weitere Vorgänge steuern!
		// (Die Realisaton wird mittels eval() organisiert)
		// Jeder Entry wird wie folgt deklariert:
		// $this->run[n]['args'] >> Argument hier eintragen. 
		// Für eine $_GET-Variable einfach G[varname], 
		// für ein $_POST einfach P[...] und für $_SERVER 
		// einfach S[...] verwenden. Alles als String.
		// ***
		// $this->run[n]
		//              ['insert'] >> Speichern-Query (hinzufügen)
		//              ['update'] >> Speichern-Query (aktualisieren)
		//              ['read']   >> Lesen-Query
		//              ['remove'] >> Löschen-Query
		//              ['plugin']['pluginname']>>Query
		// ***
		public $run = array();

		public function init () {
			for ( $g = 0; $g < count($this->run); $g++ ) {
				$r =  $this->run[$g];
				// *** //
				if ( $r['insert'] != "" ) { $t = trim($r['insert']); } else
				if ( $r['update'] != "" ) { $t = trim($r['update']); } else
				if ( $r['remove'] != "" ) { $t = trim($r['remove']); } else
				if ( $r['select'] != "" ) { $t = trim($r['select']); } else
				if ( $r['read']   != "" ) { $t = trim($r['read']);   }
				// *** //
				$t .= " "; $d = "";
				// *** //
				$s = "";
				// *** //
				for ( $c = 0; $c < strlen($t); $c++ ) {
					if ( $t[$c] == ' ' ) {
						if ( strstr( $s, "G[" ) || strstr( $s, "g[" ) ) {
							$s = str_replace( "G[", "", $s ); 
							$s = str_replace( "g[", "", $s );
							$s = str_replace( "]", "",  $s );
							// *** //
							$d = "'" . $_GET[$s] . "'";
							// *** //
							$d .= $s . " ";
						} elseif ( strstr( $s, "P[" ) || strstr( $s, "p[" ) ) {
							$s = str_replace( "P[", "", $s ); 
							$s = str_replace( "p[", "", $s );
							$s = str_replace( "]", "",  $s );
							// *** //
							$d = "'" . $_POST[$s] . "'";
							// *** //
							$d .= $s . " ";
						} elseif ( strstr( $s, "S[" ) || strstr( $s, "s[" ) ) {
							$s = str_replace( "S[", "", $s ); 
							$s = str_replace( "s[", "", $s );
							$s = str_replace( "]", "",  $s );
							// *** //
							$d = "'" . $_SESSION[$s] . "'";
							// *** //
							$d .= $s . " ";
						} elseif ( strstr( $s, "*[" ) ) {
							$s = str_replace( "*[", "", $s ); 
							$s = str_replace( "]", "",  $s );
							$s = strtolower( $s );
							// *** //
							switch( $s ) {
								case 'me'         : $s = $_SERVER['PHP_SELF']; break;
								case 'addr'       : $s = $_SERVER['SERVER_ADDR']; break;
								case 'name'       : $s = $_SERVER['SERVER_NAME']; break;
								case 'software'   : $s = $_SERVER['SERVER_SOFTWARE']; break;
								case 'protocol'   : $s = $_SERVER['SERVER_PROTOCOL']; break;
								case 'method'     : $s = $_SERVER['REQUEST_METHOD']; break;
								case 'time'       : $s = $_SERVER['REQUEST_TIME']; break;
								case 'time.f'     : $s = $_SERVER['REQUEST_TIME_FLOAT']; break;
								case 'query'      : $s = $_SERVER['QUERY_STRING']; break;
								case 'root'       : $s = $_SERVER['DOCUMENT_ROOT']; break;
								case 'accept'     : $s = $_SERVER['HTTP_ACCEPT']; break;
								case 'charset'    : $s = $_SERVER['HTTP_ACCEPT_CHARSET']; break;
								case 'encoding'   : $s = $_SERVER['HTTP_ACCEPT_ENCODING']; break;
								case 'language'   : $s = $_SERVER['HTTP_ACCEPT_LANGUAGE']; break;
								case 'connection' : $s = $_SERVER['HTTP_CONNECTION']; break;
								case 'host'       : $s = $_SERVER['HTTP_HOST']; break;
								case 'referer'    : $s = $_SERVER['HTTP_REFERER']; break;
								case 'browser'    : $s = $_SERVER['HTTP_USER_AGENT']; break;
								case 'https'      : $s = $_SERVER['HTTPS']; break;
								case 'r.addr'     : $s = $_SERVER['REMOTE_ADDR']; break;
								case 'r.host'     : $s = $_SERVER['REMOTE_HOST']; break;
								case 'r.port'     : $s = $_SERVER['REMOTE_PORT']; break;
								case 'r.user'     : $s = $_SERVER['REMOTE_USER']; break;
								case 'rd.r.user'  : $s = $_SERVER['REDIRECT_REMOTE_USER']; break;
								case 'script'     : $s = $_SERVER['SCRIPT_FILENAME']; break;
								case 'svr.admin'  : $s = $_SERVER['SERVER_ADMIN']; break;
								case 'svr.port'   : $s = $_SERVER['SERVER_PORT']; break;
								case 'svr.sign'   : $s = $_SERVER['SERVER_SIGNATURE']; break;
								case 'path'       : $s = $_SERVER['PATH_TRANSLATED']; break;
								case 'scriptname' : $s = $_SERVER['SCRIPT_NAME']; break;
								case 'req.uri'    : $s = $_SERVER['REQUEST_URI']; break;
								case 'tinymce'    : $s = "TinyMCE Ver 4.0"; break;
								case '5m'         : $s = $_SERVER['5M-Ware']; break;
								case 'date'       : $s = $_SERVER['']; break;
								case 'day'        : $s = $_SERVER['']; break;
								case 'month'      : $s = $_SERVER['']; break;
								case 'year'       : $s = $_SERVER['']; break;
								case 'hour'       : $s = $_SERVER['']; break;
								case 'minute'     : $s = $_SERVER['']; break;
								case 'second'     : $s = $_SERVER['']; break;
							}
							// *** //
							$d .= "'" . $s . "'" . " ";
						} else {
							$d .= $s . " ";
						}
						// *** //
						$s = "";
					} else {
						$s .= $t[$c];
					}
				}
				// *** //
				if ( $r['insert'] != "" ) { $r['insert'] = $t; } else
				if ( $r['update'] != "" ) { $r['update'] = $t; } else
				if ( $r['remove'] != "" ) { $r['remove'] = $t; } else
				if ( $r['select'] != "" ) { $r['select'] = $t; } else
				if ( $r['read']   != "" ) { $r['read']   = $t; }
			}
		}

		/* ********************************************************************************** *
		 * ********************************************************************************** *
		 * Damit der $this->run auch durchläuft, muss manuell im PHP-Code
		 * diese Methode immer gecallt werden ???????????????
		 * ********************************************************************************** *
		 * ********************************************************************************** */
		public function process() {
		/*
			$result = array();
			// *** //
			foreach ( $this->run as $r ) {
				if ( is_array($result) ) {
					unset( $result );
					$result = array();
				}
				// *** //
				if ( $r['save']['value'] == $_SERVER[$r['save']['name']] || 
					 $r['save']['value'] == $_POST[$r['save']['name']]   ||
					 $r['save']['value'] == $_GET[$r['save']['name']]    ){
						$query=mysql_query($this->resetvars($r['read']['query'])); $fnd = 0;
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						while ( $datensatz = mysql_fetch_array($query) ) { $fnd = 1; break; }
						// *** //
						if ( $fnd == 0 ) {
							$query=mysql_query($this->resetvars($r['save']['insert']));
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						} elseif ( $fnd == 1 ) {
							$query=mysql_query($this->resetvars($r['save']['update']));
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						}
				}
				elseif ( $r['read']['value'] == $_SERVER[$r['read']['name']] || 
					 $r['read']['value'] == $_POST[$r['read']['name']]   ||
					 $r['read']['value'] == $_GET[$r['read']['name']]    ){
						$query=mysql_query($this->resetvars($r['read']['query'])); $rid = 0;
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						while ( $datensatz = mysql_fetch_array($query) ) {
							if ( is_array( $r['read']['db'] ) ) {
								foreach( $r['read']['db'] as $d ) {
									$result[$rid] = $datensatz[$r['read']['db'][$rid]];
									// *** //
									$rid++;
								}
							}
						}
				}
				elseif ( $r['remove']['value'] == $_SERVER[$r['remove']['name']] || 
					 $r['remove']['value'] == $_POST[$r['remove']['name']]   ||
					 $r['remove']['value'] == $_GET[$r['remove']['name']]    ){
						$query=mysql_query($tis->resetvars($r['remove']['query']));
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
			}
			// *** //
			return $result;
		*/
		}

		/* ********************************************************************************** *
		 * ********************************************************************************** *
		 * Ladet eine Editor-Instanz
		 * ********************************************************************************** *
		 * ********************************************************************************** */
		 public function load ( $identifier ) {
			$embed = array(); $embedder = 0;
			// *** //
			if ( $this->tinyMCELoaded == false ) {
				$this->tinyMCELoaded = true;
				echo '
					<script type="text/javascript" src="'.$this->core['editor.path'].'/tinymce.min.js"></script>
					<link href=\'http://fonts.googleapis.com/css?family=Questrial|Rambla:400,700,400italic|Alfa+Slab+One|Source+Sans+Pro:200,300,400,700,200italic,300italic,400italic,700italic|Glegoo:400,700|Dosis:200,300,400,500,600,700|Abel|Patrick+Hand|Fira+Sans:300,400,500,700,300italic,400italic,500italic,700italic|Noto+Sans:400,700,400italic,700italic|Droid+Sans:400,700|Permanent+Marker|Signika+Negative:300,400,600,700|Lato:100,300,400,700,100italic,300italic,400italic,700italic|Arvo:400,700,400italic,700italic|Antic+Slab|Cabin:400,500,600,700,400italic,500italic,600italic,700italic|Playfair+Display:400,700,400italic,700italic|BenchNine:300,400,700|Sorts+Mill+Goudy:400,400italic|Economica:400,700,400italic,700italic|News+Cycle:400,700|Lora:400,700,400italic,700italic|PT+Sans:400,700,400italic,700italic|Poiret+One|Armata|PT+Sans+Narrow:400,700|Ropa+Sans:400,400italic|Cabin+Condensed:400,500,600,700|Tinos:400,700,400italic,700italic|Philosopher:400,700,400italic,700italic|Istok+Web:400,700,400italic,700italic|Arimo:400,700,400italic,700italic|Quicksand:400,700|Paytone+One|Noto+Serif:400,700,400italic,700italic|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Gudea:400,700,400italic|Marck+Script|Droid+Sans+Mono|Josefin+Sans:300,400,600,700,300italic,400italic,600italic,700italic|Bitter:400,700,400italic|PT+Serif:400,700,400italic,700italic|Lobster|Kreon:300,400,700|Waiting+for+the+Sunrise|Monda:400,700|Anton|Crete+Round:400,400italic|Shadows+Into+Light|Rokkitt:400,700|Josefin+Slab:400,600,700,400italic,600italic,700italic|Fredoka+One|Russo+One|Libre+Baskerville:400,700,400italic|Sigmar+One|Source+Code+Pro:200,300,400,500,600,700,900|Gloria+Hallelujah|Fontdiner+Swanky|Exo:200,300,400,500,600,700,200italic,300italic,400italic,500italic,600italic,700italic|Asap:400,700,400italic,700italic|Tangerine:400,700|Cantarell:400,700,400italic,700italic|Muli:300,400,300italic,400italic|Great+Vibes|Alegreya+Sans:400,500,700,400italic,500italic,700italic|Ubuntu+Condensed|EB+Garamond|Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300|Roboto:400,300,300italic,400italic,500,500italic,700,700italic|Oswald:400,300,700|Slabo+27px|Roboto+Condensed:300italic,400italic,700italic,400,300,700|Open+Sans+Condensed:300,700,300italic|Raleway:400,100,200,300,500,600,700|Montserrat:400,700|Roboto+Slab:400,300,700|Merriweather:400,300,300italic,400italic,700,700italic|Titillium+Web:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic|Indie+Flower|Yanone+Kaffeesatz:400,200,300,700|Oxygen:400,300,700|Fjalla+One|Hind:400,300,500,600,700|Vollkorn:400italic,400|Bree+Serif|Inconsolata|Francois+One|Signika|Nunito:400,300,700|Play:400,700|Archivo+Narrow:400,400italic,700,700italic|Merriweather+Sans:400,300,300italic,400italic,700,700italic|Cuprum:400,400italic,700,700italic|Maven+Pro:400,500,700,900|Pacifico|Exo+2:400,200,200italic,300,300italic,400italic,500,500italic,600,600italic,700,700italic|Orbitron:400,500,700,900|Alegreya:400italic,700italic,900italic,400,700,900|Karla:400,400italic,700,700italic|Varela+Round|PT+Sans+Caption:400,700|Dancing+Script:400,700|Crimson+Text:400,400italic,600,600italic,700,700italic|Pathway+Gothic+One|Abril+Fatface|Patua+One|Architects+Daughter|Quattrocento+Sans:400,400italic,700,700italic|Pontano+Sans|Noticia+Text:400,400italic,700,700italic|Sanchez|Bangers|Amatic+SC:400,700|Kaushan+Script|Courgette|Old+Standard+TT:400,400italic,700|Lobster+Two:400,400italic,700,700italic|Cinzel:400,700|Archivo+Black|Coming+Soon|Ruda:400,700|Covered+By+Your+Grace|Passion+One:400,700|Hammersmith+One|Pinyon+Script|Cardo:400,400italic,700|Chewy|Playball|Varela|Comfortaa:400,300,700|Sintony:400,700|Righteous|Rock+Salt|Changa+One:400,400italic|Nobile:400,400italic,700,700italic|Satisfy|Bevan|Jura:400,300,500,600|Handlee|Amaranth:400,400italic,700,700italic|Playfair+Display+SC:400,700,700italic|Shadows+Into+Light+Two|Quattrocento:400,700|Vidaloka|Scada:400italic,700italic,400,700|Molengo|Enriqueta:400,700|Special+Elite|Actor|Luckiest+Guy|Cookie|Source+Serif+Pro:400,600,700&subset=latin,latin-ext,cyrillic-ext,greek,cyrillic,greek-ext\' rel=\'stylesheet\' type=\'text/css\'>
				';
				// *** //
				echo '
					<script type = "text/javascript">
					function mouseCoordinates(e) {
						if(!e) e = window.event;
						var body = (window.document.compatMode && window.document.compatMode == "CSS1Compat") ? 
						window.document.documentElement : window.document.body;
						return {
							top: e.pageY ? e.pageY : e.clientY + body.scrollTop - body.clientTop,
							left: e.pageX ? e.pageX : e.clientX + body.scrollLeft  - body.clientLeft
						};
					}
					function re_format( value ) {
						var i = 0; var container = "";
						// *** //
						for ( i = 0; i < value.length; i++ ) {
							if ( value.charCodeAt(i) <= 127 ) {
								if ( value.charAt(i) == \' \' ) {
									container += "&nbsp;";
								} else if ( value.charAt(i) == "\t" ) {
									container += "&nbsp;&nbsp;&nbsp;";
								} else {
									container += value.charAt(i);
								}
							} else {
								container += "&#" + value.charCodeAt(i) + ";";
							}
						}
						// *** //
						return container;
					}
					tinymce.PluginManager.add( \'tinymce5mWare_PlugIns\', function( editor, url ) {
				';
				// *** //
				// Unicode-Conversation-function ctu()
				include "5MPlugins/subsys/ctuf.php";
				// Embedded styling modules
				include "5MPlugins/subsys/newline.php";
				include "5MPlugins/subsys/paddings.php";
				include "5MPlugins/subsys/margins.php";
				include "5MPlugins/subsys/separators.php";
				include "5MPlugins/subsys/fields.php";
				include "5MPlugins/subsys/block.php";
				include "5MPlugins/subsys/tabs.php";
				// *** //
				foreach ( $this->plugin as $p ) {
					$embed[$embedder] = array();
					$embed[$embedder]['key'] = $p['name'];
					$embed[$embedder]['sep'] = $p['sepr'];
					// *** //
					$embedder++;
					// *** //
					switch ( $p['type'] ) {
						case "dialog":
							/* ******************************************************** *
							 * DIALOG
							 * ******************************************************** */
							 echo '
								editor.addButton(\''.$p['name'].'\', {
									text:    \''.$p['text'].'\',
									icon:    false,
									tooltip: \''.$p['info'].'\',
									onclick: function() {
										editor.windowManager.open({
											title: \''.$p['text'].'\',
											url: \''.$this->core['style.path'].$p['html'].'?'.session_id().'\',
											width: '.$p['width'].',
											height: '.$p['height'].',
											buttons: [{
												text: \'Close\',
												onclick: \'close\'},{
												text: \'Paste\',
												onclick: function(e) {
							';
							// *** //
							if ( file_exists( $p['code'] ) ) { include $p['code']; }
							// *** //
							echo '
												}
											}]
										});
									}
								});
							 ';
							/* ******************************************************** */
							 break;
						case "button":
							/* ******************************************************** *
							 * BUTTON
							 * ******************************************************** */
							 echo '
								editor.addButton(\''.$p['name'].'\', {
									text:    \''.$p['text'].'\',
									icon:    false,
									tooltip: \''.$p['info'].'\',
									onclick: function() {
							';
							// *** //
							if ( file_exists( $p['code'] ) ) { include $p['code']; }
							// *** //
							echo '
									}
								});
							 ';
							/* ******************************************************** */
							 break;
						case "combo":
							/* ******************************************************** *
							 * COMBO-BOX
							 * ******************************************************** */
							 ;
							/* ******************************************************** */
							 break;
						case "popup":
							/* ******************************************************** *
							 * POPUP-MENU
							 * ******************************************************** */
							 ;
							/* ******************************************************** */
							 break;
						case "format":
							/* ******************************************************** *
							 * FORMAT-UNIT
							 * ******************************************************** */
							 ;
							/* ******************************************************** */
							 break;
					}
				}
				// *** //
				if ( $embedder > 0 ) {
					$this->tinyMCE5MPlugins = "\"tinymce5mWare_PlugIns\",";
				}
				// *** //
				echo '
				});
				</script>
				';
			} else {
				foreach ( $this->plugin as $p ) {
					$embed[$embedder] = array();
					$embed[$embedder]['key'] = $p['name'];
					$embed[$embedder]['sep'] = $p['sepr'];
					// *** //
					$embedder++;
				}
			}
			// *** //
			$embedText = $this->unit['toolbar'] . " | ";
			// *** //
			foreach ( $embed as $e ) {
				$embedText .= $e['key'] . ' ';
				// *** //
				if ( $e['sep'] == true ) { $embedText .= ' | '; }
			}
			// *** //
			echo '
			<style type = "text/css">
				.tmce_popup_container {
					z-index: 80000;
					background-color: #fff;
					border: 1px solid rgb(200,200,200);
					border-radius: 8px;
					padding: 5px;
					box-shadow: 0 0 12px rgba(50,50,50,0.20);
					-moz-box-shadow: 0 0 12px rgba(50,50,50,0.20);
					-khtml-box-shadow: 0 0 12px rgba(50,50,50,0.20);
					-webkit-box-shadow: 0 0 12px rgba(50,50,50,0.20);
				}
				.tab_header {
					padding: 5px;
					border-top:    1px solid rgb(240,240,240);
					border-left:   1px solid rgb(240,240,240);
					border-right:  1px solid rgb(190,190,190);
					border-bottom: 1px solid rgb(190,190,190);
					background-color: rgb(235,236,237);
					text-align: left;
					font-weight: normal;
				}
				.tab_innerl {
					border-right: 1px solid rgb(230,230,230);
					padding: 5px; text-align: left; height: 200px;
					overflow-y: hidden; overflow-x: auto;
				}
				.tab_innerr {
					border-left: 1px solid rgb(230,230,230);
					padding: 5px; text-align: left; height: 200px;
					overflow-y: hidden; overflow-x: auto;
				}
				.tab_innerx {
					padding: 5px; text-align: left; height: 200px;
					overflow-y: hidden; overflow-x: auto;
				}
				.tab_innerl:hover, .tab_innerr:hover, .tab_innerx:hover,
				.tab_innerl:active, .tab_innerr:active, .tab_innerx:active {
					background-color: rgb(211, 223, 235);
				}
				.tab_navigo {
					border-top: 1px solid rgb(230,230,230);
					padding: 5px; text-align: right;
				}
				.tab_navigo input[type="button"] {
					background: url('.trim($this->core['editor.path']).'/../5MPlugins/subsys/imgs/tabs/buttons/blue.png) repeat-x top left rgb(60,123,193);
					cursor: pointer; color: #ffffff; font-weight: bold;
					height: 28px; padding: 3px;
					border-top: 1px solid rgb(230,233,251); 
					border-left: 1px solid rgb(230,233,251);
					border-right: 1px solid rgb(130,133,171);
					border-bottom: 1px solid rgb(130,133,171);
					border-radius: 5px; -moz-border-radius: 5px;
					-khtml-border-radius: 5px; -webkit-border-radius: 5px;
				}
				.tab_navigo input[type="button"]:hover {
					background: url('.trim($this->core['editor.path']).'/../5MPlugins/subsys/imgs/tabs/buttons/blueh.png) repeat-x top left rgb(60,123,223);
					color: #ffffff; font-weight: bold;
				}
				.tab_navigo input[type="button"]:active {
					background: url('.trim($this->core['editor.path']).'/../5MPlugins/subsys/imgs/tabs/buttons/bluec.png) repeat-x bottom left rgb(60,123,223);
					color: #ffffff; font-weight: bold;
					border-bottom: 1px solid rgb(230,233,251); 
					border-right: 1px solid rgb(230,233,251);
					border-left: 1px solid rgb(130,133,171);
					border-top: 1px solid rgb(130,133,171);
				}
				.tab_selector td {
					padding: 1px;
				}
				.tab_selector input[type="text"] {
					border: none;
					padding: 3px; width: 120px;
					border-radius: 5px; -moz-border-radius: 5px;
					-khtml-border-radius: 5px; -webkit-border-radius: 5px;
				}
				.tab_selector input[type="text"]:hover {
					border: 1px solid #ffffff;
					padding: 2px; color: rgb(11,23,35);
					background-color: rgb(211, 223, 235);
				}
				.tab_selector input[type="text"]:focus {
					border: 1px solid #ffffff;
					padding: 2px; color: rgb(11,23,35);
					background-color: rgb(231, 243, 255);
				}
			</style>
<!--

		<div id = "tmce_embedded_tabs_popup_box" class = "tmce_popup_container" style = "display:none; position:absolute; top: 0px; left: 0px; width: 200px; height: 100px;"
			 onmousedown = "javascript: tinyMCE.activeEditor.insertContent(\'Tab-Panel sagt Hallo :-) ...\'); this.style.display = \'none\';">
				<div>
					Hallo Welt
				</div>
				<div>
					Oha be :-)
				</div>
			 </div>
		</div>

-->
		<div id = "tmce_embedded_tabs_popup_box" class = "tmce_popup_container" style = "display:none; position:absolute; top: 0px; left: 0px; width: 620px; height: 280px;">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th class = "tab_header" style = "width: 25%;" onmousedown = "javascript: document.getElementById(\'tmce_embedded_tabs_popup_box\').style.display = \'none\';">
';
	switch ( $_SESSION['lang'] ) {
		case 'de': echo 'Tab-Stil'; break;
		case 'tr': echo 'Tab-Stili'; break;
		default  : echo 'Tab-Style'; break;
	}
echo '
						</th>
						<th class = "tab_header" style = "width: 25%;" onmousedown = "javascript: document.getElementById(\'tmce_embedded_tabs_popup_box\').style.display = \'none\';">
';
	switch ( $_SESSION['lang'] ) {
		case 'de': echo 'Aussehen'; break;
		case 'tr': echo 'G&ouml;r&uuml;n&uuml;m'; break;
		default  : echo 'Apperance'; break;
	}
echo '
						</th>
						<th class = "tab_header" style = "width: 25%;" onmousedown = "javascript: document.getElementById(\'tmce_embedded_tabs_popup_box\').style.display = \'none\';">
';
	switch ( $_SESSION['lang'] ) { // Verhalten
		case 'de': echo 'Eigenschaften';   break;
		case 'tr': echo '&Ouml;zellikler'; break;
		default  : echo 'Properties';      break;
	}
echo '
						</th>
						<th class = "tab_header" style = "width: 25%;" onmousedown = "javascript: document.getElementById(\'tmce_embedded_tabs_popup_box\').style.display = \'none\';">
';
	switch ( $_SESSION['lang'] ) { // Verhalten
		case 'de': echo 'Tab-Felder'; break;
		case 'tr': echo 'Ba&#351;l&#305;klar';     break;
		default  : echo 'Tab-Fields'; break;
	}
echo '
						</th>
					</tr>
					<tr>
						<td class = "tab_innerl" valign = "top">
							<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
								<tr><td colspan = "2">Panel</td><tr>
								<tr>
									<td><input type = "radio" name = "tabseltfds3" value = "0" checked /></td>
									<td><img border = "0" src = "" /></td>
									<td><input type = "radio" name = "tabseltfds3" value = "1" /></td>
									<td><img border = "1" src = "" /></td>
								</tr>
								<tr>
									<td><input type = "radio" name = "tabseltfds3" value = "2" /></td>
									<td><img border = "2" src = "" /></td>
									<td><input type = "radio" name = "tabseltfds3" value = "3" /></td>
									<td><img border = "3" src = "" /></td>
								</tr>
								<tr><td colspan = "2">Slider</td><tr>
								<tr>
									<td><input type = "radio" name = "tabseltfds3" value = "4" checked /></td>
									<td><img border = "4" src = "" /></td>
									<td><input type = "radio" name = "tabseltfds3" value = "5" /></td>
									<td><img border = "5" src = "" /></td>
								</tr>
								<tr>
									<td><input type = "radio" name = "tabseltfds3" value = "6" /></td>
									<td><img border = "6" src = "" /></td>
									<td><input type = "radio" name = "tabseltfds3" value = "7" /></td>
									<td><img border = "7" src = "" /></td>
								</tr>
							</table>
						</td>
						<td class = "tab_innerl" valign = "top">
						
						</td>
						<td class = "tab_innerl" valign = "top">
							Tab-Wechsel mit: <br />
							<select size = "1">
								<option value = "0">Klick</option>
								<option value = "1">Maus-RollOver</option>
								<option value = "2">Automatisch in 5 Sek.</option>
								<option value = "2">Automatisch in 10 Sek.</option>
								<option value = "2">Automatisch in 15 Sek.</option>
								<option value = "2">Automatisch in 20 Sek.</option>
								<option value = "2">Automatisch in 25 Sek.</option>
								<option value = "2">Automatisch in 30 Sek.</option>
								<option value = "2">Automatisch in 35 Sek.</option>
								<option value = "2">Automatisch in 40 Sek.</option>
							</select>
							<br /><br />
							Effekt beim Tab-Wechsel: <br />
							<select size = "1">
								<option value = "0">Keine</option>
								<option value = "1">&Uuml;berblendung</option>
								<option value = "2">Runterfallen</option>
								<option value = "2">Aufsteigen</option>
								<option value = "2">Von Links fahren</option>
								<option value = "2">Von Rechts fahren</option>
							</select>
							<br /><br />
							Inhaltsfl&auml;sche: <br />
							<table border = "0" cellspacing = "0" cellpadding = "0"><tr>
							<td>
								<select size = "1">
									<option value = "0">Passt sich automatisch an</option>
									<option value = "1">Beh&auml;lt die maximale H&ouml;he</option>
									<option value = "2">Hat die feste H&ouml;he von:</option>
								</select>
							</td><td>
								<input type = "text" style = "width: 40px; text-align: right;" />
							</td>
							</tr></table>
						</td>
						<td><div class = "tab_innerx" valign = "top">
							<table border = "0" cellspacing = "0" cellpadding = "0" class = "tab_selector">
';
for( $n = 0; $n < 8; $n++ ) {
echo '
								<tr>
									<td><input type = "checkbox"></td>
									<td><input type = "text" /></td>
								</tr>
';
}
echo '
							</table>
						</div></td>
					</tr>
					<tr>
						<td class = "tab_navigo" colspan = "4">
							<input type = "button" 
							        value = "';
	switch ( $_SESSION['lang'] ) { // Abbrechen
		case 'de': echo 'Abbrechen'; break;
		case 'tr': echo '&#304;ptal';     break;
		default  : echo 'Cancel'; break;
	}
							        echo '"
							         onclick = "javascript: document.getElementById(\'tmce_embedded_tabs_popup_box\').style.display = \'none\';"
							/>
							<input type = "button" 
							        value = "';
	switch ( $_SESSION['lang'] ) { // Übernehmen
		case 'de': echo 'Einf&uuml;gen'; break;
		case 'tr': echo 'Tamam';     break;
		default  : echo 'Apply'; break;
	}
							        echo '"
							         onclick = "javascript: document.getElementById(\'tmce_embedded_tabs_popup_box\').style.display = \'none\';"
							/>
						</td>
				</table>
			 </div>

		<script type = "text/javascript">

		var tmce_active_editor_instance;
		var tmce_actice_force_to_hide = "";

		function tmce_actice_force_to_hide_proc() {
			if(document.getElementById(tmce_actice_force_to_hide)){document.getElementById(tmce_actice_force_to_hide).style.display="none";}
		}

		/*if ( window.addEventListener ) {
			window.addEventListener( "mouseup", function(){ tmce_actice_force_to_hide_proc(); }, false );
		} else if ( window.attachEvent ) {
			window.attachEvent("onmouseup", function(){ tmce_actice_force_to_hide_proc(); } );
		}*/

		tinymce.init({
			selector: "textarea#'.$identifier.'",
			theme: "modern",
			language: "'.$this->language.'",
			width: '.$this->width.',
			height: '.$this->height.',
			plugins: [
				 '.$this->tinyMCE5MPlugins.'
				 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				 "save table contextmenu directionality emoticons template paste textcolor", "table", "preview"
		   ],
			formats: {
				bold : { inline : \'b\' },  
				italic : { inline : \'i\' },
				underline : { inline : \'u\'}
			},
		   content_css: "'.$this->core['style.path'].'/tinymce_5m_lifetime_theme.css",
		   toolbar : "'.$embedText.'",
		   force_br_newlines : true,
		   force_p_newlines : false,
		   forced_root_block : "",
		   valid_elements: "@[id|class|title|style|onmouseover|onclick]," +
						   "a[name|href|target|title|alt]," +
						   "div[align|valign|style|width|height],blockquote,ol,ul,li,br,img[src|height|width],sub,sup,b,i,u,table,tbody,tr,td,th,center," +
						   "-span[data-mce-type],hr," +
						   "input[style|type|name|id|value|checked],"+
						   "select[style|size|type|name|id],"+
						   "option[value|selected],"+
						   "iframe[src|title|width|height|allowfullscreen|frameborder|class|id],object[classid|width|height|codebase|*],param[name|value|_value|*],embed[type|width|height|src|*],"+
						   "style[type],"+
						   "table[border|cellspacing|cellpadding|width],tr[rowspan],td[colspan|width|height|align|valign|style],th[colspan|width|height|align|valign|style],tr[style|width|height]",
		   extended_valid_elements : "@[id,class,title,style,onclick,onmouseover,onmouseout,onmousemove,onkeydown,onkeyup,onkeypress]" +
									 ",img[class|src|border=0|style|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|onclick|box-shadow|-moz-box-shadow|-webkit-box-shadow|box-radius|-moz-box-radius|-webkit-box-radius]"+
		                             ",div[class,align,style,width,height,onmouseover,onmouseout,onmousemove,onclick,onkeydown,onkeyup,onkeypress,alt,name,id]"+
									 ",p[class,align,style,width,height,onmouseover,onmouseout,onmousemove,onclick,onkeydown,onkeyup,onkeypress,alt,name,id]"+
									 ",table[class,align,style,width,height,onmouseover,onmouseout,onmousemove,onclick,onkeydown,onkeyup,onkeypress,alt,name,id,width,height]"+
									 ",tr[class,align,style,width,height,onmouseover,onmouseout,onmousemove,onclick,onkeydown,onkeyup,onkeypress,alt,name,id,width,height]"+
									 ",td[class,align,style,width,height,onmouseover,onmouseout,onmousemove,onclick,onkeydown,onkeyup,onkeypress,alt,name,id,width,height,colspan,rowspan]"+
									 ",th[class,align,style,width,height,onmouseover,onmouseout,onmousemove,onclick,onkeydown,onkeyup,onkeypress,alt,name,id,width,height,colspan,rowspan]"+
									 ",script[language|type|src]"+
									 ",style[type]"+
									 ",input[style,type,name,id,value,checked,onkeydown,onkeyup,onkeypress,onblur,onmousedown,onmouseup,onmouseover,onmouseout,onclick,ondblclick]"+
									 ",select[style,size,type,name,id]"+
									 ",iframe[value,value,src,method,width,height,border,style]"+
									 ",option[value,selected]"+
									 ",5mware[mode|type|size|command|action|method|width|height|position|do|top|left|right|bottom|padding|margin|border|interval|timeshift|define|declare|unit|item|root]"+
									 ",b[style],i[style],u[style],big[style],strike[style],small[style],caption[style],cite[style]",
		   entity_encoding : "raw",
		   fontsize_formats: "2pt 4pt 6pt 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 38pt 40pt  42pt 44pt 46pt 50pt 55pt 60pt 65pt 70pt 75pt 80pt 85pt 90pt 100pt 110pt 120pt 130pt 140pt 150pt 160pt 170pt 180pt 200pt 210pt 220pt 230pt 240pt 250pt 260pt 270pt 280pt 290pt 300pt 325pt 350pt 375pt 400pt 425pt 450pt 475pt 500pt",
		   mode : "textareas",
		   theme_advanced_buttons3_add : "tablecontrols",
		   table_styles : "Header 1=header1;Header 2=header2;Header 3=header3",
		   table_cell_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
		   table_row_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",
			theme_advanced_buttons4_add : "preview",
			plugin_preview_width : "'.$this->preViewWidth.'",
			plugin_preview_height : "'.$this->preViewHeight.'",
			font_formats : "Andale Mono=andale mono,times;"+
                "Arial=arial,helvetica,sans-serif;"+
                "Arial Black=arial black,avant garde;"+
                "Book Antiqua=book antiqua,palatino;"+
                "Comic Sans MS=comic sans ms,sans-serif;"+
                "Courier New=courier new,courier;"+
                "Georgia=georgia,palatino;"+
                "Helvetica=helvetica;"+
                "Impact=impact,chicago;"+
                "Symbol=symbol;"+
                "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                "Terminal=terminal,monaco;"+
                "Times New Roman=times new roman,times;"+
                "Trebuchet MS=trebuchet ms,geneva;"+
                "Verdana=verdana,geneva;"+
                "Webdings=webdings;"+
                "Wingdings=wingdings,zapf dingbats;"+
				"Questrial, Serif= \'Questrial\', sans-serif;"+
				"Rambla, Serif= \'Rambla\', sans-serif;"+
				"Alaf Slab One, Cursive= \'Alfa Slab One\', cursive;"+
				"Source Sans Pro, Serif= \'Source Sans Pro\', sans-serif;"+
				"Glegoo, Serif= \'Glegoo\', serif;"+
				"Dosis, Serif= \'Dosis\', sans-serif;"+
				"Abel, Serif= \'Abel\', sans-serif;"+
				"Patrick Hand, Cursive= \'Patrick Hand\', cursive;"+
				"Fira Sans, Serif= \'Fira Sans\', sans-serif;"+
				"Noto Sans, Serif= \'Noto Sans\', sans-serif;"+
				"Droid Sans, Serif= \'Droid Sans\', sans-serif;"+
				"Permanent Marker, Cursive= \'Permanent Marker\', cursive;"+
				"Signika Negative, Serif= \'Signika Negative\', sans-serif;"+
				"Lato, Serif= \'Lato\', sans-serif;"+
				"Arvo, Serif= \'Arvo\', serif;"+
				"Antic Slab, Serif= \'Antic Slab\', serif;"+
				"Cabin, Serif= \'Cabin\', sans-serif;"+
				"Playfair Display, Serif= \'Playfair Display\', serif;"+
				"BenhNine, Serif= \'BenchNine\', sans-serif;"+
				"Sorts Mill Goudy, Serif= \'Sorts Mill Goudy\', serif;"+
				"Economica, Serif= \'Economica\', sans-serif;"+
				"News Cycle, Serif= \'News Cycle\', sans-serif;"+
				"Lora, Serif= \'Lora\', serif;"+
				"PT Sans, Serif= \'PT Sans\', sans-serif;"+
				"Poiret One, Serif= \'Poiret One\', cursive;"+
				"Armata, Serif= \'Armata\', sans-serif;"+
				"PT Sans Narrow, Serif= \'PT Sans Narrow\', sans-serif;"+
				"Ropa Sans, Serif= \'Ropa Sans\', sans-serif;"+
				"Cabin Condensed, Serif= \'Cabin Condensed\', sans-serif;"+
				"Tinos, Serif= \'Tinos\', serif;"+
				"Philosopher, Serif= \'Philosopher\', sans-serif;"+
				"Isotek WEb, Serif= \'Istok Web\', sans-serif;"+
				"Arimo, Serif= \'Arimo\', sans-serif;"+
				"Quicksand, Serif= \'Quicksand\', sans-serif;"+
				"Paytone One, Serif= \'Paytone One\', sans-serif;"+
				"Noto, Serif= \'Noto Serif\', serif;"+
				"Ubuntu, Serif= \'Ubuntu\', sans-serif;"+
				"Gudea, Serif= \'Gudea\', sans-serif;"+
				"Marck Script, Cursive= \'Marck Script\', cursive;"+
				"Droid Sans Mono= \'Droid Sans Mono\', ;"+
				"Josefin Sans, Serif= \'Josefin Sans\', sans-serif;"+
				"Bitter, Serif= \'Bitter\', serif;"+
				"PT Serif= \'PT Serif\', serif;"+
				"Lobster, Cursive= \'Lobster\', cursive;"+
				"Kreon, Serif= \'Kreon\', serif;"+
				"Sunrise, Cursive= \'Waiting for the Sunrise\', cursive;"+
				"Monda, Serif= \'Monda\', sans-serif;"+
				"Anton, Serif= \'Anton\', sans-serif;"+
				"Crete Round, Serif= \'Crete Round\', serif;"+
				"Shadows Into Light, Cursive= \'Shadows Into Light\', cursive;"+
				"Rokkitt, Serif= \'Rokkitt\', serif;"+
				"Josefin Slab, Serif= \'Josefin Slab\', serif;"+
				"Fredoka One, Cursive= \'Fredoka One\', cursive;"+
				"Russo One, Serif= \'Russo One\', sans-serif;"+
				"Libre Baskerville, Serif= \'Libre Baskerville\', serif;"+
				"Sigmar One, Cursive= \'Sigmar One\', cursive;"+
				"Source Code Pro= \'Source Code Pro\', ;"+
				"Gloria Hallelujah, Cursive= \'Gloria Hallelujah\', cursive;"+
				"Fontdiner Swanky, Cursive= \'Fontdiner Swanky\', cursive;"+
				"Exo, Serif= \'Exo\', sans-serif;"+
				"Asap, Serif= \'Asap\', sans-serif;"+
				"Tangerine, Cursive= \'Tangerine\', cursive;"+
				"Cantarell, Serif= \'Cantarell\', sans-serif;"+
				"Muli, Serif= \'Muli\', sans-serif;"+
				"Great Vibes, Cursive= \'Great Vibes\', cursive;"+
				"Alegreya Sans, Serif= \'Alegreya Sans\', sans-serif;"+
				"Ubuntu Condensed, Serif= \'Ubuntu Condensed\', sans-serif;"+
				"EB Garamond, Serif= \'EB Garamond\', serif;"+
				"Droid Serif= \'Droid Serif\', serif;"+
				"Open Sans, Serif= \'Open Sans\', sans-serif;"+
				"Roboto, Serif= \'Roboto\', sans-serif;"+
				"Oswald, Serif= \'Oswald\', sans-serif;"+
				"Slabo 27px, Serif= \'Slabo 27px\', serif;"+
				"Roboto Condensed, Serif= \'Roboto Condensed\', sans-serif;"+
				"Open Sans Condensed, Serif= \'Open Sans Condensed\', sans-serif;"+
				"Raleway, Serif= \'Raleway\', sans-serif;"+
				"Montserrat, Serif= \'Montserrat\', sans-serif;"+
				"Roboto Slab, Serif= \'Roboto Slab\', serif;"+
				"Merriweather, Serif= \'Merriweather\', serif;"+
				"Titillium Web, Serif= \'Titillium Web\', sans-serif;"+
				"Indie Flower, Cursive= \'Indie Flower\', cursive;"+
				"Yanone Kaffeesatz, Serif= \'Yanone Kaffeesatz\', sans-serif;"+
				"Oxygen, Serif= \'Oxygen\', sans-serif;"+
				"Fjalla One, Serf= \'Fjalla One\', sans-serif;"+
				"Hind, Serif= \'Hind\', sans-serif;"+
				"Vollkorn, Serif= \'Vollkorn\', serif;"+
				"Bree Serif= \'Bree Serif\', serif;"+
				"Inconsolata= \'Inconsolata\', ;"+
				"Francois One, Serif= \'Francois One\', sans-serif;"+
				"Signika, Serif= \'Signika\', sans-serif;"+
				"Nunito, Serif= \'Nunito\', sans-serif;"+
				"Play, Serif= \'Play\', sans-serif;"+
				"Archivo Narrow, Serif= \'Archivo Narrow\', sans-serif;"+
				"Merriweather Sans, Serif= \'Merriweather Sans\', sans-serif;"+
				"Cuprum, Serif= \'Cuprum\', sans-serif;"+
				"Maven Pro, Serif= \'Maven Pro\', sans-serif;"+
				"Pacifico, Cursive= \'Pacifico\', cursive;"+
				"Exo 2, Serif= \'Exo 2\', sans-serif;"+
				"Orbitron, Serif= \'Orbitron\', sans-serif;"+
				"Alegreya, Serif= \'Alegreya\', serif;"+
				"Karla, Serif= \'Karla\', sans-serif;"+
				"Varela Round, Serif= \'Varela Round\', sans-serif;"+
				"PT Sans Caption, Serif= \'PT Sans Caption\', sans-serif;"+
				"Dancing Script, Cursive= \'Dancing Script\', cursive;"+
				"Crimson Text, Serif= \'Crimson Text\', serif;"+
				"Pathway Gothic One, Serif= \'Pathway Gothic One\', sans-serif;"+
				"Abril Fatface, Cursive= \'Abril Fatface\', cursive;"+
				"Patua One, Cursive= \'Patua One\', cursive;"+
				"Architects Daughter, Cursive= \'Architects Daughter\', cursive;"+
				"Quattrocento Sans, Serif= \'Quattrocento Sans\', sans-serif;"+
				"Pontano Sans, Serif= \'Pontano Sans\', sans-serif;"+
				"Noticia Text, Serif= \'Noticia Text\', serif;"+
				"Sanchez, Serif= \'Sanchez\', serif;"+
				"Bangers, Cursive= \'Bangers\', cursive;"+
				"Amatic SC, Curive= \'Amatic SC\', cursive;"+
				"Kaushan Script, Cursive= \'Kaushan Script\', cursive;"+
				"Courgette, Cursive= \'Courgette\', cursive;"+
				"Old Standard TT, Serif= \'Old Standard TT\', serif;"+
				"Lobster Two, Cursive= \'Lobster Two\', cursive;"+
				"Cinzel, Serif= \'Cinzel\', serif;"+
				"Archivo Black, Serif= \'Archivo Black\', sans-serif;"+
				"Coming Soon, Cursive= \'Coming Soon\', cursive;"+
				"Ruda, Serif= \'Ruda\', sans-serif;"+
				"Your Grace, Cursive= \'Covered By Your Grace\', cursive;"+
				"Passion One, Cursive= \'Passion One\', cursive;"+
				"Hammersmith One, Serif= \'Hammersmith One\', sans-serif;"+
				"Pinyon Script, Cursive= \'Pinyon Script\', cursive;"+
				"Cardo, Serif= \'Cardo\', serif;"+
				"Chewy, Cursive= \'Chewy\', cursive;"+
				"Playball, Cursive= \'Playball\', cursive;"+
				"Varela, Serif= \'Varela\', sans-serif;"+
				"Comfortaa, Cursive= \'Comfortaa\', cursive;"+
				"Sintony, Serif= \'Sintony\', sans-serif;"+
				"Righteous, Cursive= \'Righteous\', cursive;"+
				"Rock Salt, Cursive= \'Rock Salt\', cursive;"+
				"Changa One, Cursive= \'Changa One\', cursive;"+
				"Nobile, Serif= \'Nobile\', sans-serif;"+
				"Satisfy, Cursive= \'Satisfy\', cursive;"+
				"Bevan, Cursive= \'Bevan\', cursive;"+
				"Jura, Serif= \'Jura\', sans-serif;"+
				"Handlee, Cursive= \'Handlee\', cursive;"+
				"Amaranth, Serif= \'Amaranth\', sans-serif;"+
				"Playfair Display SC, Serif= \'Playfair Display SC\', serif;"+
				"Shadows Into Light Two, Cursive= \'Shadows Into Light Two\', cursive;"+
				"Quattrocento, Serif= \'Quattrocento\', serif;"+
				"Vidaloka, Serif= \'Vidaloka\', serif;"+
				"Scada, Serif= \'Scada\', sans-serif;"+
				"Molengo, Serif= \'Molengo\', sans-serif;"+
				"Enriquesta, Serif= \'Enriqueta\', serif;"+
				"Special Elite,  Cursive= \'Special Elite\', cursive;"+
				"Actor, Serif= \'Actor\', sans-serif;"+
				"Luckiest Guy, Cursive= \'Luckiest Guy\', cursive;"+
				"Cookie, Cursive= \'Cookie\', cursive;"+
				"Source Serif Pro= \'Source Serif Pro\', serif"
		 }); 

		</script>

		<textarea name = "'.$identifier.'" id = "'.$identifier.'"></textarea>

			';
		 }

		/* ********************************************************************************** *
		 * ********************************************************************************** *
		 * Konstruktor
		 * ********************************************************************************** *
		 * ********************************************************************************** */
		function __construct() {
			/* -------------------------------------------------------------------- *
			 * BASIS-EINSTELLUNGEN, DIE FEST VORGEGEBEN WERDEN MÜSSEN
			 * -------------------------------------------------------------------- */
			// Pfad zum Ordner tinyMCE
			$this->core['editor.path'] = "";
			// Pfad des WEPI-DYN-* für den SESSION-INTERAKTION
			// Hier müssen erreichbar sein:
			// db_ac.php
			// wepi_dyn_public.php
			// wepi_req_sess.php
			$this->core['life.cycle.path'] = "";
			// Pfad WEPI-API wegen wepi_core und wepi_serv
			// Im API-Ordner muss das Schema für JS und PHP
			// Includes getrennt vorliegen:
			// api/client/ für JavaScript (wepi_core.js, wepi_serv.js)
			// api/server/ für PHP        (wepi_server_side.php, wepi_file_system.php)
			$this->core['api.path'] = "";
			// Pfad für die Plugins
			$this->core['plugin.path'] = "";
			// Pfad der CSS-Datei
			$this->core['style.path'] = "";
			/* -------------------------------------------------------------------- *
			 * VORDEFINIERTE, JEDOCH ANPASSBARE EINSTELLUNGEN
			 * -------------------------------------------------------------------- */
			// Fügt den Speichern-Button hinzu oder entfernt es
			$this->unit['save'] = true;
			// Fügt den Leeren-Kopf hinzu oder entfernt es
			$this->unit['clear'] = true;
			// Fügt den Vorschau-Kopf hinzu oder entfernt es
			$this->unit['preview'] = true;
			// Aktiviert oder deaktiviert die Menüleiste
			$this->unit['menu.bar'] = true;
			// CSS-Style-Datei, die eingebunden werden soll
			$this->unit['style.code'] = "";
			// Erlaubt oder verweigert die Quellcode-Editierbarkeit
			$this->unit['source.code'] = true;
			// Aktiviert oder deaktiviert die JavaScript-Unterstützung
			$this->unit['javascript'] = true;
			// Wird dieser Flag aktiviert, wird der Editor um 5M-Ware
			// Plugins ergänzt, falls nicht, wird der typische Editor
			// dargestellt.
			$this->unit['5m.plugins'] = true;
			// Der neue Editor-Engine kann eine Ansammlung von Bildern,
			// zum Beispiel Icons zur Auswahl anbieten. Hierfür kann
			// an dieser Stelle ein Pfad angegeben werden, in dem weitere
			// Unterordner mit Bildern vorliegen können.
			$this->unit['include.picture.path'] = "";
			// Liste von zusätzlichen Schriftarten, die namentlich im 
			// Editor aufgeführt werden
			$this->unit['font.list'] = array();
			// Liste von für zusätzliche Schriftarten benötigte 
			// link-rel Deklarationen
			$this->unit['font.link'] = array();
			// Standard-Elemente im Toolbar
			$this->unit['toolbar'] = "new save | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | forecolor backcolor emoticons | sizeselect | fontselect |  fontsizeselect | 5MPlugin_Embedded_NewLine | 5MPlugin_Embedded_Paddings 5MPlugin_Embedded_Margins | 5MPlugin_Embedded_Separators | 5MPlugin_Embedded_Fields | 5MPlugin_Embedded_Block | 5MPlugin_Embedded_Tabs";
		}

	}

?>
