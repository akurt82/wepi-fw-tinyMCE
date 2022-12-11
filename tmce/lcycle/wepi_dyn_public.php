
    <?php

		include "db_ac.php";

		$wepi_conn = mysql_connect( $wepi_host, $wepi_user, $wepi_pass );
		$wepi_connect = mysql_select_db( $wepi_data, $wepi_conn );

		// ----------------------------- START ----------------------------- //
		// HINWEIS:
		// Diese Datei gehört zur upl* Dateigruppe zum Hochladen und Entfernen
		// von Bildern auf dem Server.
		// Verwerfen-Funktion
		if ( file_exists("upldelete.php") ) { include( "upldelete.php" ); }
		// ----------------------------- EN.DE ----------------------------- //

	function geschnitten_( $text, $grenze = 27, $tooltip = true ) {
		$nu = 0; $zz = 0; $utf = 0; $txx = "";
		for( $r = 0; $r < strlen($text); $r++ ) {
			if ( $zz == $grenze ) {
				$txx .= "..."; $nu = 1;
				break;
			}
			if ( $text[$r] == '&' ) {
				$utf = 1;
				$txx .= $text[$r];
			} elseif ( $text[$r] == ';' ) {
				$utf = 0;
				$zz++; $txx .= $text[$r];
			} else {
				if ( $utf == 0 ) {
					$zz++;
				}
				$txx .= $text[$r];
			}
		}
		if ( $nu == 0 ) {
			return $text;
		} elseif ( $nu == 1 ) {
			if ( $tooltip == true ) {
				$te = ""; $au = 0;
				for ($n = 0; $n < strlen($text); $n++ ) {
					if ( $text[$n] == '<' ) {
						$au = 1;
					} elseif( $text[$n] == '>' ) {
						$au = 0;
					} else {
						if ( $au == 0 ) { $te .= $text[$n]; }
					}
				}
				return '<span title = "'.$te.'">'.$txx.'</span>';
			} else {
				return $txx.'...';
			}
		}
	}
	
	function modifyUTFex( $value ) {
		$vlu = $value;
		$vlu = ereg_replace("{amp}","&",$vlu);
		$vlu = ereg_replace("{raute}","#",$vlu);
		return $vlu;
	}

		 function get_file_name( $path ) {
			$ni = 0;
			$tx = "";
			// *** //
			for( $ni = 0; $ni < strlen( $path ); $ni++ ) {
				if ( $path[$ni] == '/' ) {
					$tx = "";
				} else {
					$tx .= $path[$ni];
				}
			}
			// *** //
			return $tx;
		 }

		 function get_file_type( $path ) {
			$ni = 0;
			$tx = "";
			// *** //
			for( $ni = 0; $ni < strlen( $path ); $ni++ ) {
				if ( $path[$ni] == '.' ) {
					$tx = "";
				} else {
					$tx .= $path[$ni];
				}
			}
			// *** //
			return $tx;
		 }

         function gibTage( $datum1, $datum2 ) {
            $diff = $datum1->diff($datum2);
            return $diff->days;
         }

         function gibDatum( $datum ) {
              $dtm = date_create($datum);
			  if ( $dtm ) {
				return trim(date_format($dtm,"d.m.Y"));
			  } else {
				return "";
			  }
         } 

         function kommazahl($str) {
              $str = preg_replace('[^0-9\,\.\-\+]', '', strval($str));
              $str = strtr($str, ',', '.');
              $pos = strrpos($str, '.');
              return $str;//($pos===false ? floatval($str) : floatval(str_replace('.', '', substr($str, 0, $pos)) . substr($str, $pos)));
         }

         function kommazahl2($str) {
              $str = preg_replace('[^0-9\,\.\-\+]', '', strval($str));
              $str = strtr($str, ',', '.');
              $pos = strrpos($str, '.');
              $rr = $str;//($pos===false ? floatval($str) : floatval(str_replace('.', '', substr($str, 0, $pos)) . substr($str, $pos)));
              $rr = ereg_replace( ",", "%", $rr );
              $rr = ereg_replace( ".", "!", $rr );
              $rr = ereg_replace( "%", ".", $rr );
              $rr = ereg_replace( "!", ",", $rr );
              return $rr;
         }

          function zeichensatz ( $v ) {
              return htmlentities( $v );
          }

		  function neuer_benutzer_eintrag ( $usr, $pas, $fnm, $snm, $eml ) {

			  $query=mysql_query("INSERT INTO aj_nutzer 
			  ( benutzer, kennwort, vorname, nachname, email, modus ) VALUES 
			  ( '$usr', '$pas', '$fnm', '$snm', '$eml', '0' )
			  ");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function benutzer_konto_andern ( $ori, $usr, $pas, $fnm, $snm, $eml ) {

			  $query=mysql_query("UPDATE aj_nutzer SET
			  benutzer = '$usr',
			  kennwort = '$pas',
			  vorname = '$fnm',
			  nachname = '$snm',
			  email = '$eml' 
			  WHERE benutzer = '$ori'
			  ");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function benutzer_konto_loschen ( $usr ) {

			  $query=mysql_query("DELETE FROM aj_nutzer WHERE benutzer = '$usr'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function spende_abhaken ( $id ) {

			  $query=mysql_query("UPDATE aj_spende SET abgehakt = '1' WHERE id = '$id'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function sende_antwort_mitglied( $von, $an, $titel, $nach ) {

			  $query=mysql_query("
				INSERT INTO aj_messages 
				( datum, von_benutzer, an_benutzer, titel, beschreibung ) VALUES 
				( NOW(), '$von', '$an', '".zeichensatz($titel)."', '".zeichensatz($nach)."' )
			  ");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function adm_mitgl_nachl ( $id ) {

			  $query=mysql_query("DELETE FROM aj_messages WHERE id = '$id'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function adm_kontakt_nachl ( $id ) {

			  $query=mysql_query("DELETE FROM aj_contacts WHERE id = '$id'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function Sende_Kontakt_Daten ( $titel, $email, $tel, $nach ) {

			  $query=mysql_query("
				INSERT INTO aj_contacts 
				( datum, email, telefon, titel, thema ) VALUES 
				( NOW(), '$email', '$tel', '".zeichensatz($titel)."', '".zeichensatz($nach)."' )
			  ");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function sende_antwort_kontakt( $email, $titel, $eingabe ) {
			$empfaenger = "$email";
			$absendername = "info@aktivejugend.de";
			$absendermail = "info@aktivejugend.de";
			$betreff = "$titel";
			$text = "$eingabe";
			mail($empfaenger, $betreff, $text, "From: $absendername");
		  }

		  function projekt_dokument_loschen ( $id, $pid ) {

			  $query=mysql_query("DELETE FROM aj_projects_content WHERE id = '$id' AND pid = '$pid'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function projekt_loschen ( $id ) {

			  $query=mysql_query("DELETE FROM aj_projects WHERE id = '$id'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function news_dokument_loschen ( $id ) {

			  $query=mysql_query("DELETE FROM aj_news WHERE id = '$id'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

		  }

		  function beitrag_dokument_loschen ( $wo, $id ) {

			  if ( $wo == 1 ) {
				$query=mysql_query("DELETE FROM aj_bildung WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  } elseif ( $wo == 2 ) {
				$query=mysql_query("DELETE FROM aj_wasser WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  } elseif ( $wo == 3 ) {
				$query=mysql_query("DELETE FROM aj_gesundheit WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  } elseif ( $wo == 4 ) {
				$query=mysql_query("DELETE FROM aj_soforthilfe WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  } elseif ( $wo == 5 ) {
				$query=mysql_query("DELETE FROM aj_patenschaft WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  }

		  }

		  function bild_linken ( $pictr, $galid ) {
			$query=mysql_query("INSERT INTO aj_gallerie_inhalt ( gid, datei ) VALUES ( '$galid', '$pictr' )");
			if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function bild_ablosen ( $picid, $galid ) {
				$query=mysql_query("DELETE FROM aj_gallerie_inhalt WHERE id = '$picid' AND gid = '$galid'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function gallerie_loschen( $galid ) {
				$query=mysql_query("DELETE FROM aj_gallerie_inhalt WHERE gid = '$galid'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$query=mysql_query("DELETE FROM aj_gallerie WHERE id = '$galid'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function hintergrundbild_fur_kampagne( $id, $bild ) {
				$query=mysql_query("UPDATE aj_kampagne SET hbild = '$bild' WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function starte_kampagne( $id ) {
				$query=mysql_query("UPDATE aj_kampagne SET status = '0' WHERE id >= 0");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$query=mysql_query("UPDATE aj_kampagne SET status = '1' WHERE id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function kampagne_beenden() {
				$query=mysql_query("UPDATE aj_kampagne SET status = '0' WHERE id >= 0");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function blog( $key, $val ) {
				$query=mysql_query("UPDATE aj_daten SET wert = '$val' WHERE kennung = 'blog' AND eintrag = '$key'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function banner( $key, $val ) {
				$query=mysql_query("UPDATE aj_daten SET wert = '$val' WHERE kennung = 'banner' AND eintrag = '$key'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function gallery( $key, $val ) {
				$query=mysql_query("UPDATE aj_daten SET wert = '$val' WHERE kennung = 'gallery' AND eintrag = '$key'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function advertise( $key, $val ) {
				$query=mysql_query("UPDATE aj_daten SET wert = '$val' WHERE kennung = 'advertise' AND eintrag = '$key'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function notiere( $tab, $dir, $key, $val ) {
				$query=mysql_query("UPDATE aj_$tab SET wert = '$val' WHERE kennung = '$dir' AND eintrag = '$key'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function flang( $key, $state ) {
				$query=mysql_query("SELECT * FROM aj_lang_support WHERE lkey = '".ereg_replace("'","",$key)."'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$iss++; break;
				}
				if ( $iss == 0 ) {
					$query=mysql_query(ereg_replace("''","'","INSERT INTO aj_lang_support ( lkey, lval ) VALUES ( '$key', '$state' )"));
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$query=mysql_query(ereg_replace("''","'","UPDATE aj_lang_support SET lval = '$state' WHERE lkey = '$key'"));
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function langstream( $langkey, $type, $stream ) {
				$query=mysql_query("SELECT * FROM aj_text WHERE kennung = '$langkey' AND subkenn = '$type'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$iss = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {
					$iss++; break;
				}
				if ( $iss == 0 ) {
					$query=mysql_query(ereg_replace("''","'","INSERT INTO aj_text ( kennung, subkenn, inhalt ) VALUES ( '$langkey', '$type', '$stream' )"));
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$query=mysql_query(ereg_replace("''","'","UPDATE aj_text SET inhalt = '$stream' WHERE kennung = '$langkey' AND subkenn = '$type'"));
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }
		  
		  function erfrage( $tab, $dir, $key ) {
				$query=mysql_query("SELECT * FROM aj_$tab WHERE kennung = '$dir' AND eintrag = '$key' LIMIT 1");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['wert'];
				}
				echo $temp;
		  }

		  function linkZuKampagne( $pid, $id, $gros, $klein ) {
				$query=mysql_query("UPDATE aj_kampagne_content SET datei = '$gros', mintr = '$klein' WHERE pid = '$pid' AND id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				echo "<center><img border = \"0\" src = \"$klein\" style = \"width:200px;\" /></center>";
		  }

		  function bildRausAusKampagne( $pid, $id ) {
				$query=mysql_query("UPDATE aj_kampagne_content SET datei = '', mintr = '' WHERE pid = '$pid' AND id = '$id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				echo "<center><img border = \"0\" src = \"imgs/none.png\" style = \"width:200px;\" /></center>";
		  }
		  
		  function hineinInsGal( $gid, $gros, $klein ) {
				$query=mysql_query("INSERT INTO aj_gallerie_inhalt ( gid, datei, mintr ) VALUES ( '$gid', '$gros', '$klein' )");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
			echo '<table border = "0" cellspacing = "0" cellpadding = "0" class = "lo_tab" style = "width:900px;">
				<tr>
					<th colspan = "5" align = "right" style = "border-bottom:1px solid rgb(150,150,150); text-align: left;">
						Bilder in der Gallerie "<?php echo $projnam; ?>"
					</th>
				</tr>';

				  $query=mysql_query("SELECT * FROM aj_gallerie_inhalt WHERE gid = '$gid' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

				  $zr = 0; $idx = 0;

				  while ( $datensatz = mysql_fetch_array($query) ) {

						if ( $zr == 0 ) { echo '<tr>'; }

						echo '
							<td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">
							<center>
								<a href = "index.php?page=egall&mode=1&proj='.$datensatz['id'].'&pid='.$datensatz['id'].'">
									<img border = "0" src = "'.$datensatz['datei'].'" style = "max-width: 128px; max-height: 128px;" /> <br />
									'.$datensatz['titel'].'
								</a> <br />
								<a href = "javascript:bild_ablosen('.$datensatz['id'].','.$gid.');">
								Entfernen
								</a>
							</center>
							</td>';

						$zr++; $idx++;

						if ( $zr == 5 ) { echo '</tr>'; $zr = 0; }


						}

				  switch ( $zr ) {
					case 1 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> </tr>'; break;
					case 2 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> </tr>'; break;
					case 3 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> </tr>'; break;
					case 4 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 ); border-bottom: 1px solid rgb( 120, 120, 120 );">&nbsp;</td> </tr>'; break;
				  }

			echo '</table>';
		  }

		  /* ------------------------------------------------------------------------------------- *
		   * ------------------------------------------------------------------------------------- *
		   *
		   *  Diese Funktionen sind für die upl*-Technik notwendig, damit bestimmte Aktionen wie
		   *  gewähltes Bild, Style-Eigenschaften etc. im Server-Session zwsichengespeichert 
		   *  werden können.
		   *
		   * ------------------------------------------------------------------------------------- *
		   * ------------------------------------------------------------------------------------- */

		   function upl_io_value( $varname, $varvalue = -39999 ) {
				if ( $varvalue == -39999 ) {
					$query=mysql_query("SELECT * FROM aj_text WHERE kennung = '$varname' LIMIT 1");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					while ( $datensatz = mysql_fetch_array($query) ) {
						$temp = $datensatz['inhalt'];
					}
					echo $temp;
				} else {
					$iss = 0;
					// *** //
					if ( $varname == "uplSOURCE" ) {
						$query=mysql_query("SELECT * FROM aj_images WHERE id = '$varvalue'");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						while ( $datensatz = mysql_fetch_array($query) ) {
							$temp = $datensatz['datei'];
						}
					} else {
						$temp = $varvalue;
					}
					// *** //
					$query=mysql_query("SELECT * FROM aj_text WHERE kennung = '$varname'");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					while ( $datensatz = mysql_fetch_array($query) ) {
						$iss++; break;
					}
					// *** //
					if ( $iss == 0 ) {
						$query=mysql_query("INSERT INTO aj_text ( kennung, inhalt ) VALUES ( '$varname', '$temp' )");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					} else {
						$query=mysql_query("UPDATE aj_text SET inhalt = '$temp' WHERE kennung = '$varname'");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					}
				}
		   }

		   function clear_partial_stream ( $sid ) {
				if ( file_exists("ps$sid.tmp") ) {
					unlink( "ps$sid.tmp" );
				}
				$fh = fopen("ps$sid.tmp", 'a');
				fclose($fh);
		   }

		   function send_partial_stream ( $chartr, $sid ) {
				$fh = fopen("ps$sid.tmp", 'a');
				fwrite($fh, $chartr);
				fclose($fh);
		   }

		   function set_chat_data_stream ( $from, $to, $sid ) { //, $message
				$mess = "";
				$fh = fopen("ps$sid.tmp", 'a');
				while (false !== ($char = fgetc($fh))) {
					if ( ( $char[0] != 10 ) || ( $char[0] != 13 ) ) {
						$mess .= $char;
					}
				}
				fclose($fh);
				echo $mess;
				/*$ls = array(); $mess = ""; $char = "";
				// *** //
				for ( $u = 0; $u < strlen( $message ) + 1; $u++ ) {
					if ( $message[$u] == '[' ) {
						$char = "";
					} elseif ( $message[$u] == ']' ) {
						$l = chr($char);
						if ( $char > 127 ) {
							$mess .= "&#$char;";
						} else {
							$mess .= $l;
						}
					} else {
						$char .= $message[$u];
					}
				}*/
				//$mess = $message
				// *** //
				//$mess = ereg_replace( "'", "%@1", $mess );
				//$mess = ereg_replace( "@F", ' ', $mess );
				//$mess = ereg_replace( "@#", '&#', $mess );
				//$mess = ereg_replace( "@E", '&', $mess );
				// *** //
				/*$query=mysql_query("
					INSERT INTO db_5m_chat (
						msend, mto, mdate, mtext
					) VALUES (
						'$from', '$to', NOW(), '$mess'
					)
				");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				//echo $mess;
				*/
		   }

		   function remove_chat_stream ( $this_chat_id ) {
				$query=mysql_query("DELETE FROM db_5m_chat WHERE id = '$this_chat_id'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		   }

		   function dlgPropImageFolder_Content() {
				echo '
					fsd ffasd fas aafd fas asd asdf te wf sefw e r qwer f we asa ewg aew ewf sfa
				';
		   }

		   function get_total_of_images ( $dir, $e1, $e2 ) {
				$ex1 = "";
				$ex2 = "";
				// *** //
				if ( $e1 != "*" ) { $ex1 = " datei LIKE '%.$e1' "; }
				// *** //
				if ( $e2 != "*" ) { $ex2 = " datei LIKE '%.$e2' "; }
				// *** //
				$whr = "";
				// *** //
				if ( $ex1 != "" ) { $whr .= " AND $ex1 "; }
				if ( $ex2 != "" ) { $whr .= " OR ( ( datei LIKE '%$dir/%' ) OR ( datei LIKE '$dir/%' ) ) AND $ex2 "; }
				// *** //
				$query=mysql_query("SELECT * FROM aj_images WHERE ( ( datei LIKE '%$dir/%' ) OR ( datei LIKE '$dir/%' ) ) $whr");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$cnt = 0;
				// *** //
				while ( $datensatz = mysql_fetch_array($query) ) {
					$cnt++;
				}
				// *** //
				echo $cnt;
		   }

		   function zip_total_of_images ( $dir ) {
				$query=mysql_query("SELECT * FROM aj_images WHERE ( datei LIKE '%$dir/%' ) OR ( datei LIKE '$dir/%' ) ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$zip = new ZipArchive();
				$res = $zip->open("./tempdownloads/".$dir.".zip", ZIPARCHIVE::CREATE);
				// *** //
				if($res === TRUE) {
					while ( $datensatz = mysql_fetch_array($query) ) {
						$zip->addFile( "./tempdownloads/".$dir, $datensatz['datei'] );
					}
					// *** //
					$zip->close();
					// *** //
					echo '<a href = "./tempdownloads/'.$dir.'">'.$dir.'</a>';
				} else { echo "ZIP-ERROR"; }
		   }

		   function get_my_current_message_stream( $user ) {
				$query=mysql_query("SELECT * FROM db_5m_chat_sender WHERE nick = '$user'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['chatstream'];
				}
				$temp = ereg_replace( "%@1", "'", $temp );
				echo $temp;
		   }

		   function get_my_current_message_recivr( $user ) {
				$query=mysql_query("SELECT * FROM db_5m_chat_sender WHERE nick = '$user'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['reci'];
				}
				echo $temp;
		   }

		   function get_my_current_message_status( $user ) {
				$query=mysql_query("SELECT * FROM db_5m_chat_sender WHERE nick = '$user'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['mstatus'];
				}
				echo $temp;
		   }

		   function clear_my_current_message_stream( $user ) {
				$query=mysql_query("UPDATE db_5m_chat_sender SET chatstream = '', reci = '', mstatus = '0' WHERE nick = '$user'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		   }


		   function get_my_stored_messages ( $user, $lang ) {
				$llg = $lang;
				if ( $llg == "" ) { $llg = "en"; }
				$d5mm = array();
				$query=mysql_query("SELECT * FROM db_5m_keywords WHERE lkey = '$llg' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$key = $datensatz['lkey'];
					$kwd = $datensatz['lkeyword'];
					$tra = $datensatz['translated'];
					$d5mm[$kwd] = $tra;
				}
				$query=mysql_query("SELECT * FROM db_5m_chat WHERE ( msend = '$user' OR mto = '$user' ) ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$thid = $datensatz['id'];
					$from = $datensatz['msend'];
					$isto = $datensatz['mto'];
					$datm = gibDatum($datensatz['mdate']);
					$cott = stripslashes($datensatz['mtext']);
					// *** //
					$cott = ereg_replace( '%@1', '\'', $cott );
					$cott = ereg_replace( '%@2', '\"', $cott );
					$cott = ereg_replace( '%@3', ':', $cott );
					$cott = ereg_replace( '%@4', ';', $cott );
					$cott = ereg_replace( '%@5', '=', $cott );
					// *** //
					if ( $from == $user ) {
						$stilis = "myside";
						$isfrom = $d5mm['prefix_to']."&nbsp;$isto";
					} else {
						$stilis = "otside";
						$isfrom = $d5mm['prefix_from']."&nbsp;$from";
					}
					// *** //
					$wert  = '<div id = "thisbox'.$thid.'"><table border = "0" cellspacing = "0" cellpadding = "0" width = "100%" class = "'.$stilis.'">';
					$wert .= '<tr>';
					$wert .= '<th colspan = "2" width = "100%"><i>('.$datm.')</i>&nbsp;&nbsp;&nbsp;'.$isfrom.'</th>';
					$wert .= '<th valign = "top" align = "right"><div align = "right"><a href = "javascript:$_id(\'thisbox'.$thid.'\').innerHTML=\'\';svr.Return(\'remove_chat_stream\','.$thid.');">';
					$wert .= '<img border = "0" src = "imgs/quit.png" title = "'.$d5ml['button_remove'].'" /></a></div></th>';
					$wert .= '</tr><tr>';
					$wert .= '<td valign = "top" colspan = "3">'.$cott.'</td>';
					$wert .= '</tr>';
					$wert .= '</table></div>';
					// *** //
					echo $wert;
				}
		   }


		   function point_of_message_query_stream( $user ) {
				$query=mysql_query("SELECT * FROM db_5m_chat WHERE mto = '$user' AND mstatus = '1' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['mtext'];
				}
				$temp = ereg_replace( "%@1", "'", $temp );
				echo $temp;
		   }

		   function point_of_message_query_recivr( $user ) {
				$query=mysql_query("SELECT * FROM db_5m_chat WHERE mto = '$user' AND mstatus = '1' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['msend'];
				}
				echo $temp;
		   }

		   function point_of_message_query_status( $user ) {
				$query=mysql_query("SELECT * FROM db_5m_chat WHERE mto = '$user' AND mstatus = '1' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				while ( $datensatz = mysql_fetch_array($query) ) {
					$temp = $datensatz['mstatus'];
				}
				echo $temp;
		   }

		   function i_read_my_incoming_message( $user ) {
				$query=mysql_query("UPDATE db_5m_chat SET mstatus = '0' WHERE mto = '$user'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		   }

		   function tinyMCEPluginEngine( $command, $name, $type ) {
				echo "abc = $command, $name, $type";
				if ( $command == "magnify" ) {
					switch( $type ) {
						case  1 : $typ = "lens";   break;
						case  2 : $typ = "window"; break;
						default : $typ = "inner";  break;
					};
					// *** //
					echo ' $(\'#'.$name.'\').elevateZoom({ zoomType: '.$typ.', cursor: "crosshair", zoomWindowFadeIn: 500, zoomWindowFadeOut: 500 }); ';
					$query=mysql_query("
						INSERT INTO db_5m_plugin_sources 
						( plugin_type_name, plugin_type_numb, plugin_name ) 
						VALUES 
						( '$name', '$typ', '$command' )
					");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
		   }

		   function f5_smartmenu_update( $id, $node, $icon, $text, $link, $lang = "" ) {
				$tex2 = ereg_replace( "{amp}", "&", $text );
				$tex2 = ereg_replace( "{sem}", ";", $tex2 );
				$tex2 = ereg_replace( "{rau}", "#", $tex2 );
				$tex2 = ereg_replace( "{spc}", " ", $tex2 );
				$tex2 = ereg_replace( "'", "`", $tex2 );
				$lin2 = ereg_replace( '\'', '@%1', $link );
				// *** //
				$query=mysql_query("
					UPDATE db_5m_smart_menu SET 
					item_node = '$node',
					lang_group = '$lang',
					item_icon = '$icon',
					item_text = '$tex2',
					item_link = '$lin2' 
					WHERE id = '$id'
				");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		   }

		   function f5_smartmenu_add( $node, $icon, $text, $link, $lang = "" ) {
				$tex2 = ereg_replace( "{amp}", "&", $text );
				$tex2 = ereg_replace( "{sem}", ";", $tex2 );
				$tex2 = ereg_replace( "{rau}", "#", $tex2 );
				$tex2 = ereg_replace( "{spc}", " ", $tex2 );
				$tex2 = ereg_replace( "'", "`", $tex2 );
				// *** //
				echo $tex2;
				$quer3=mysql_query("SELECT * FROM db_5m_smart_menu");
				if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
				$sortid = 0;
				while ( $datensatz = mysql_fetch_array($quer3) ) { $sortid++; }
				// *** //
				$query=mysql_query("
					INSERT INTO db_5m_smart_menu 
					( sortid, lang_group, item_node, item_icon, item_text, item_link ) 
					VALUES 
					( '$sortid', '$lang', '$node', '$icon', '$tex2', '$link' )
				");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$quer2=mysql_query("SELECT * FROM db_5m_smart_menu WHERE item_node = '$node' AND item_icon = '$icon' AND item_text = '$tex2' AND item_link = '$link' AND lang_group = '$lang' ORDER BY id");
				if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				$temp = "?";
				while ( $datensatz = mysql_fetch_array($quer2) ) {
					$temp = $datensatz['id'];
				}
				echo $temp;
		   }












		  function add_new_content( $name, $typeof ) {
			  /* ------------------------- ADD CONTENT HEADLINE -------------------------- */
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '$typeof' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

			  $gefunden = 0;

			  //$pro = ereg_replace("{amp}","&",$name);
			  //$pro = ereg_replace("{raute}","#",$pro);
			  $pro = modifyUTFex($name);

			  while ( $datensatz = mysql_fetch_array($query) ) {

				if ( $datensatz['doctitle'] == $pro ) {
					$gefunden = 1; break;
				}

			  }

			  if ( $gefunden == 0 ) {
				if ( $pro != "" ) {
					$query=mysql_query("INSERT INTO db_5m_content_docs ( doctitle, doctype ) VALUES ( '$pro', '$typeof' )");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					}
			  }
		  }

		  function add_new_document_to( $idnr, $title ) {
				$titel = modifyUTFex($title);
				if ( $titel != "" ) {
					$query=mysql_query("INSERT INTO db_5m_content_docs_data ( did, doctitle ) VALUES ( '$idnr', '$titel' )");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function get_contents($lang,$elang,$dtyp) {
			  /* ------------------------- RELOAD CONTENT ITEMS -------------------------- */
			  echo '
				<table border = "0" cellspacing = "0" cellpadding = "0" class = "lo_tab" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "border-bottom:1px solid rgb(150,150,150); text-align: left; padding: 15px;">
							';
							switch ( $dtyp ) {
								case 0:
									if ( $lang == "de" ) { echo "Bestehende Dokumente"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Belgeler"; }
									break;
								case 1:
									if ( $lang == "de" ) { echo "Bestehende Neuigkeiten <small><span style = \"font-weight:normal;\">(Welche Neuigkeiten sollen auf die Startseite verkn&uuml;pft werden?)</span></small>"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Duyurular <small><span style = \"font-weight:normal;\">(Hangi duyurular anasayfaya ba&#287;lanacak?)</span></small>"; }
									break;
								case 2:
									if ( $lang == "de" ) { echo "Bestehende Aktivit&auml;ten"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Aktiviteler"; }
									break;
								case 3:
									if ( $lang == "de" ) { echo "Bestehende Meldungen"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Reklam-Duruyular&#305;"; }
									break;
								case 4:
									if ( $lang == "de" ) { echo "Bestehende Gallerien"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Galleriler"; }
									break;
								case 5:
									if ( $lang == "de" ) { echo "Bestehende Kampagnen"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Kampanyalar"; }
									break;
								case 6:
									if ( $lang == "de" ) { echo "Bestehende Gruppen"; }
									elseif ( $lang == "tr" ) { echo "Mevcut Gruplar"; }
									break;
							}
							echo'
						</th>
					</tr>
					';

					  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '$dtyp' ORDER BY id");
					  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

					  $zr = 0;

					  while ( $datensatz = mysql_fetch_array($query) ) {

							if ( $zr == 0 ) { echo '<tr>'; }

							if ( ( $dtyp == 1 ) || ( $dtyp == 2 ) ) {
							if ( $lang == "de" ) { $lg = "01"; }
							if ( $lang == "tr" ) { $lg = "02"; }
							if ( $datensatz["dochook$lg"] == 1 ) { $chck = " checked "; } else { $chck = ""; }
							echo '
							<td style = "border-top: 1px solid rgb( 220, 220, 220 );">
							<center>
									<table border = "0">
									<tr>
									<td valign = "top">
										<input type = "checkbox" onchange = "javascript:setgethook('.$datensatz['id'].',this.checked);" onclick = "javascript:setgethook('.$datensatz['id'].',this.checked);"  '.$chck.' />
									</td><td valign = "top" onclick = "javascript:get_content_of('.$datensatz['id'].',\''.$lang.'\',\''.$ilng.'\');svr.Sess(\'todo\',\'\');svr.Sess(\'mode\',\'\');">
										<img border = "0" src = "imgs/ordner.png" /> <br />
									</td>
									</tr><tr><td>&nbsp;</td><td valign = "top" onclick = "javascript:get_content_of('.$datensatz['id'].',\''.$lang.'\',\''.$ilng.'\');svr.Sess(\'todo\',\'\');svr.Sess(\'mode\',\'\');"><center>
										'.geschnitten_($datensatz['doctitle'],18,true).'
									</center></td></tr></table>
							</center>
							</td>';
							} else {
							echo '
								<td style = "border-top: 1px solid rgb( 220, 220, 220 );" onclick = "javascript:get_content_of('.$datensatz['id'].');svr.Sess(\'todo\',\'\');svr.Sess(\'mode\',\'\');">
								<center>
										<img border = "0" src = "imgs/ordner.png" /> <br />
										'.geschnitten_($datensatz['doctitle'],18,true).'
								</center>
								</td>';
							}

							$zr++;

							if ( $zr == 4 ) { echo '</tr>'; $zr = 0; }

					  }

					  switch ( $zr ) {
					case 1 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 2 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 3 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					  }

					echo '
				</table>
				';
		  }

		  function get_content_of( $idnr, $dtyp, $lang, $elang = "" ) {
			  /* ------------------ LOAD THE CONTENT OF A SPECIFIC CONTENT ------------------- */
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$idnr' AND doctype = '$dtyp' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['doctitle'];
				$m1 = $datensatz['docmeta01']; $m1 = ereg_replace( '%@1', "'", $m1 );
				$m2 = $datensatz['docmeta02']; $m2 = ereg_replace( '%@1', "'", $m2 );
				$na = $datensatz['docnavi'];
				$sm = $datensatz['searchflag'];
				$d1 = $datensatz['docadvr'];
				$d2 = $datensatz['doccamp'];
			  }
			  // *** //
			  $dd1 = ""; $dd2 = "";
			  // *** MELDUNGSTEXT AUSLESEN *** //
			  if ( isset($d1) ) {
				  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$d1' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensatz = mysql_fetch_array($query) ) {
					$dd1 = "($d1) ".$datensatz['doctitle'];
				  }
			  }
			  // *** KAMPANGENTEXT AUSLESEN *** //
			  if ( isset($d2) ) {
				  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$d2' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensatz = mysql_fetch_array($query) ) {
					$dd2 = "($d2) ".$datensatz['doctitle'];
				  }
			  }
			  // *** //
				switch ( $dtyp ) {
					case 0:
						$linksrc = "edocs";
						break;
					case 1:
						$linksrc = "enews";
						break;
					case 2:
						$linksrc = "eacts";
						break;
					case 3:
						$linksrc = "epops";
						break;
					case 4:
						$linksrc = "egall";
						break;
					case 5:
						$linksrc = "ecamp";
						break;
				}
			  // *** //
			  if ( $sm != 0 ) { $sm = " checked "; } else { $sm = ""; }
			  // *** //
			  if ( $lang == "de" ) { $aktdoc = "Aktuelles Dokument"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Belge"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

			
			
			
			  if ( $lang == "de" ) { $aktdoc = "Hauptdokument"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ana belge"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top" style = "padding-right:20px;">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "2">
									'.$aktdoc.'
								</th>
							</tr>
							<tr><td colspan = "2">
';
			  if ( $lang == "de" ) { $btntx = "Bearbeiten"; echo '
			  Bitte klicken Sie auf die Schaltfl&auml;che <b>Bearbeiten</b> um das Dokument bearbeiten zu k&ouml;nnen.
			  '; }
			  elseif($lang=="tr" ) { $btntx = "D&uuml;zenle"; echo '
			  Belgeyi d&uuml;zenlemek i&ccedil;in l&uuml;tfen <b>D&uuml;zenle</b> butonuna t&#305;klat&#305;n.'; }
echo '
							</td>
							</tr><tr>
								<td valign = "top"><input type = "checkbox" name = "seamotfl" id = "seamotfl" onclick = "javascript:svr.Return(\'search_motor_flag\','.$idnr.',this.checked);" '.$sm.' /></td>
								<td>
';
			  if ( $lang == "de" ) { echo '
			  Normalerweise wird jedes Dokument vom internen Suchmotor automatisch durchsucht und wenn die Suche zutrifft, dessen Inhalt unter
			  den Suchergebnissen aufgef&uuml;hrt. Soll das Dokument vom Suchmotor ignoriert werden?
			  '; }
			  elseif($lang=="tr" ) { echo '
			  Normalde her belge sayfan&#305;n kendi arama motoru taraf&#305;ndan incelenir ve aranan kritere uygunsa, arama sonu&ccedil;lar&#305;nda 
			  yer al&#305;r. Bu belge arama motorundan gizlensin mi?
			  '; }
echo '
								</td>
							</tr>
							<tr>
								<th align = "right" colspan = "2"><div align = "right"> <input type = "button" value = "'.$btntx.'"
								onclick = "javascript:location.href=\'index.php?page='.$linksrc.'&lang='.$lang.'&ilng='.$elang.'&todo=editmd&id='.$idnr.'\'"
								style = "font-weight: bold; padding: 5px;" /></div> </th>
							</tr>
						</table>
						</div>
				</td><td valign = "top">';
			  if ( $lang == "de" ) { $aktdoc = "Extras"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ekstralar"; }
			  if ( $dtyp == 0 ) {
				echo '
						<div class = "lo_tab" style = "width:300px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
';
			  if ( $lang == "de" ) { $btntx = "Meldung"; }
			  elseif($lang=="tr" ) { $btntx = "Reklam&nbsp;Duyurusu"; }
echo '
								<td>'.$btntx.':</td>
								<td><input type = "text" value = "'.$dd1.'" id = "meldung" style = "width:150px;"
								onclick = "javascript:if(document.getElementById(\'meldpop\').style.display==\'none\'){document.getElementById(\'meldpop\').style.display=\'block\';document.getElementById(\'campop\').style.display=\'none\';}else if(document.getElementById(\'meldpop\').style.display==\'block\'){document.getElementById(\'meldpop\').style.display=\'none\';}"
								/>
			<div style = "display:none;position:absolute;-khtml-border-radius: 20px; padding:10px;
				border:1px solid rgb(150,150,150);background-color:#FFFFFF; border-radius: 20px; -moz-border-radius: 20px; -webkit-border-radius: 20px;
				box-shadow:2px 2px 4px rgb(180,180,180);-moz-box-shadow:2px 2px 4px rgb(180,180,180);-webkit-box-shadow:2px 2px 4px rgb(180,180,180);
				-khtml-box-shadow:2px 2px 4px rgb(180,180,180);" id = "meldpop"
				><div style = "width:280px;height:120px;overflow:auto">
			';
				  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '3' ORDER by id"); // 3 = Meldungen
				  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensat4 = mysql_fetch_array($quer4) ) {
				  echo '<div class = "inner_entry" onclick = "javascript:document.getElementById(\'meldung\').value = \'('.$datensat4['id'].') '.$datensat4['doctitle'].'\';document.getElementById(\'meldpop\').style.display=\'none\';">';
				  echo $datensat4['doctitle'];
				  echo '</div>';
				  }
			echo '
			</div></div></td>
							</tr><tr>
';
			  if ( $lang == "de" ) { $btntx = "Kampagne:"; }
			  elseif($lang=="tr" ) { $btntx = "Kampanya:"; }
echo '
								<td>'.$btntx.'</td>
								<td><input type = "text" value = "'.$dd2.'" id = "kampagn" style = "width:150px;"
								onclick = "javascript:if(document.getElementById(\'campop\').style.display==\'none\'){document.getElementById(\'campop\').style.display=\'block\';document.getElementById(\'meldpop\').style.display=\'none\';}else if(document.getElementById(\'campop\').style.display==\'block\'){document.getElementById(\'campop\').style.display=\'none\';}"
								/>
			<div style = "display:none;position:absolute;-khtml-border-radius: 20px; padding:10px;
				border:1px solid rgb(150,150,150);background-color:#FFFFFF; border-radius: 20px; -moz-border-radius: 20px; -webkit-border-radius: 20px;
				box-shadow:2px 2px 4px rgb(180,180,180);-moz-box-shadow:2px 2px 4px rgb(180,180,180);-webkit-box-shadow:2px 2px 4px rgb(180,180,180);
				-khtml-box-shadow:2px 2px 4px rgb(180,180,180);" id = "campop"
				><div style = "width:280px;height:120px;overflow:auto">
			';
				  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '5' ORDER by id");
				  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensat4 = mysql_fetch_array($quer4) ) {
				  echo '<div class = "inner_entry" onclick = "javascript:document.getElementById(\'kampagn\').value = \'('.$datensat4['id'].') '.$datensat4['doctitle'].'\';document.getElementById(\'campop\').style.display=\'none\';">';
				  echo $datensat4['doctitle'];
				  echo '</div>';
				  }
			echo '
			</div></div></td>
';
			  if ( $lang == "de" ) { $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $btntx = "Kaydet"; }
echo '
							<tr>
								<th colspan = "2" align = "right"><div align = "right"> <input type = "button" value = "'.$btntx.'"
								onclick = "javascript:link_to_doc('.$idnr.',$_id(\'meldung\').value,$_id(\'kampagn\').value);"
								style = "font-weight: bold; padding: 5px;" /></div> </th>
							</tr>
						</table>
						</div>'; }
		echo '
				</td></td><td valign = "top" style = "padding-left:20px;">';
			if ( $dtyp == 0 ) {
			  if ( $lang == "de" ) { echo '
				Wenn der Besucher das Dokument &ouml;ffnet, so k&ouml;nnen Sie dem Besucher sofort eine Meldung anzeigen lassen. 
				Der Besucher klickt die Meldung weg, damit er das Dokument selbst zu Gesicht bekommt.<br /><br />
				Wenn Sie m&ouml;chten, kann beim Laden des Dokuments &uuml;ber dem Dokument eine Kampagne laufen.
			  '; }
			  elseif($lang=="tr" ) { echo '
				Misafiriniz belgeyi i&ccedil;eren sayfay&#305; a&ccedil;arken reklam duyurusu ile kar&#351;&#305;la&#351;abilir. As&#305;l belgeye ge&ccedil;mek 
				i&ccedil;in reklam penceresini kapatmas&#305; gerekir.<br /><br />
				Sayfa a&ccedil;&#305;l&#305;rken bir kampanya sayfan&#305;n hemen &uuml;st&uuml;nde yer alabilir.
			  '; }
			}
echo '
				</td></tr></table>
				</div>
				<br /><br />
				';

			
			
			
			
			
			  if ( $lang == "de" ) { $aktdoc = "Navigation zur Unterdokumenten"; }
			  elseif($lang=="tr" ) { $aktdoc = "Alt belgeler i&ccedil;in navigasyon"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr><td valign = "top" width = "100%" style = "padding-right:20px;">
			';
			  if ( $lang == "de" ) { echo '
			  Wenn Sie Unterdokumente f&uuml;r dieses Dokument anlegen, so wird ausdr&uuml;cklich empfohlen, 
			  das Sie die Navigation aktivieren, damit die Unterdokumente erreichbar werden.
			  <br /><br />
			  Wenn Sie jedoch keine Unterdokumente anlegen, so stellen Sie bitte sicher das im Navigationskasten die Option 
			  <b>Keine Navigation</b> ausgew&auml;hlt ist.
			  '; }
			  elseif($lang=="tr" ) { echo '
			  E&#287;er bu belgenin alt belgeleri varsa, o halde navigasyonu etkinle&#351;tirmeniz &ouml;nemlidir. 
			  &Ccedil;&uuml;nk&uuml; alt belgelere eri&#351;im sa&#287;lanabilmesi i&ccedil;in, navigasyon etkili 
			  olmal&#305;d&#305;r.<br /><br />
			  &#350;ayet alt beleger eklenmiyecekse, o halde Navigasyon b&ouml;l&uuml;m&uuml;nde <b>Navigasyon devred&#305;&#351;&#305;</b> 
			  se&ccedil;ene&#287;inin se&ccedil;ili oldu&#287;undan emin olun.
			  '; }
			  // *** //
			  if ( $lang == "de" ) { 
				$type01 = "Keine Navigation";
				$type02 = "Navigation auf der linken Seite";
				$type03 = "Navigation unterhalb des Hauptdokuments";
				$type04 = "Navigationsleiste oberhalb des Hauptdokuments";
				// *** //
				$tin01 = "Wenn dieses Dokument Unterdokumente enth&auml;lt, so bleiben diese Dokumente unerreichbar, da keine Navigation verwendet wird.";
				$tin02 = "Es wird ein Sidebar auf der linken Seite erzeugt, das die Unterdokumente anbietet.";
				$tin03 = "Die Unterdokumente werden unterhalb des Hauptdokuments zu Verf&uuml;gung gestellt.";
				$tin04 = "Die Navigationsleiste entspricht dem Administationsleiste oberhalb dieser Seite, unter der Men&uuml;leiste.";
			  } elseif($lang=="tr" ) { 
				$type01 = "Navigasyon devred&#305;&#351;&#305;";
				$type02 = "Navigasyon solda olacak";
				$type03 = "Navigasyon ana belgenin alt&#305;nda olacak";
				$type04 = "Navigasyon-&Ccedil;ubu&#287;u ana belgenin &uuml;st&uuml;nde olacak";
				// *** //
				$tin01 = "E&#287;er bu belgenin alt belgeleri varsa, onlara eri&#351;ilemez, &ccedil;&uuml;nk&uuml; navigasyon devred&#305;&#351;&305;.";
				$tin02 = "Sitenin sol taraf&#305;nda bir Belge-&Ccedil;ubu&#287;u etkinle&#351;tirilir ve burada alt belgeler yer al&#305;r.";
				$tin03 = "Alt belgeler ana belgenin hemen alt&#305;na yerle&#351;tirilir.";
				$tin04 = "Navigasyon-&Ccedil;ubu&#287;u ana belgenin &uuml;st&uuml;ne yerle&#351;ir, t&#305;pk&#305; bu sayfan&#305;n &uuml;zerindeki Y&ouml;netim-&Ccedil;ubu&#287;u gibi.";
			  }
			  $nav = array();
			  $nav[0] = '';$nav[1] = '';$nav[2] = '';$nav[3] = '';
			  $nav[$na] = ' checked ';
			echo '
				</td><td valign = "top">
						<div class = "lo_tab" style = "width:450px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td title = "'.$tin01.'" 
								><input type = "radio" name = "navtype" onchange = "javascript:svr.Return(\'save_navi_type\','.$idnr.',0);" value = "0" '.$nav[0].'
								/>'.$type01.'
								</td>
							</td>
							<tr>
								<td title = "'.$tin02.'" 
									onmouseover = "javascript:document.getElementById(\'tipper1\').style.display=\'block\';" 
									onmouseout  = "javascript:document.getElementById(\'tipper1\').style.display=\'none\';" 
								><input type = "radio" name = "navtype" onchange = "javascript:svr.Return(\'save_navi_type\','.$idnr.',1);" value = "1" '.$nav[1].' />'.$type02.'
									<div id = "tipper1" style = "display:none;position:absolute;background:url(\'imgs/tipper.png\') no-repeat top left; width: 140px; height: 120px; padding:0px; margin-top: -10px; margin-left: 14px;">
										<center><img border = "0" src = "imgs/navigation/navi01.png" style = "padding-top: 30px;" /></center>
									</div>
								</td>
							</td>
							<tr>
								<td title = "'.$tin03.'" 
									onmouseover = "javascript:document.getElementById(\'tipper2\').style.display=\'block\';" 
									onmouseout  = "javascript:document.getElementById(\'tipper2\').style.display=\'none\';" 
								><input type = "radio" name = "navtype" onchange = "javascript:svr.Return(\'save_navi_type\','.$idnr.',2);" value = "2" '.$nav[2].' />'.$type03.'
									<div id = "tipper2" style = "display:none;position:absolute;background:url(\'imgs/tipper.png\') no-repeat top left; width: 140px; height: 120px; padding:0px; margin-top: -10px; margin-left: 14px;">
										<center><img border = "0" src = "imgs/navigation/navi02.png" style = "padding-top: 30px;" /></center>
									</div>								
								</td>
							</td>
							<tr>
								<td title = "'.$tin04.'" 
									onmouseover = "javascript:document.getElementById(\'tipper3\').style.display=\'block\';" 
									onmouseout  = "javascript:document.getElementById(\'tipper3\').style.display=\'none\';" 
								><input type = "radio" name = "navtype" onchange = "javascript:svr.Return(\'save_navi_type\','.$idnr.',3);" value = "3" '.$nav[3].' />'.$type04.'
									<div id = "tipper3" style = "display:none;position:absolute;background:url(\'imgs/tipper.png\') no-repeat top left; width: 140px; height: 120px; padding:0px; margin-top: -10px; margin-left: 14px;">
										<center><img border = "0" src = "imgs/navigation/navi03.png" style = "padding-top: 30px;" /></center>
									</div>								
								</td>
							</td>
						</table>
						</div>
				</td></tr></table>
				</div>
				<br /><br />
				';
				
				
				
				
			  if ( $lang == "de" ) { $aktdoc = "Meta-Daten f&uuml;r das Dokument"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "Bu belge i&ccedil;in meta-bilgileri"; $btntx = "Kaydet"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr><td valign = "top" width = "100%" style = "padding-right:20px;">
			';
			  if ( $lang == "de" ) { echo '
			  Damit dieses Dokument &uuml;ber Google, Bing oder Yahoo direkt aufgefunden werden kann, 
			  lohnt es sich Meta-Daten zu diesem Dokument einzutragen.<br /><br />Meta-Daten sind einfach nur W&ouml;rter, 
			  die von Suchmaschinen genutzt werden, um Webseiten bzw. Inhalte aus Webseiten zu finden.
			  '; }
			  elseif($lang=="tr" ) { echo '
			  Bu belge Google, Bing veya Yahoo &uuml;zerinden hemen bulunabilmesi i&ccedil;in, bu belgeye meta-bilgileri 
			  eklemeniz tavsiye olunur.<br /><br />Meta-Bilgileri kelimelerden olu&#351;ur ve arama motorlar&#305; taraf&#305;ndan 
			  internet sayfalar&#305;n&#305; veya i&ccedil;eriklerini bulunmaya yard&#305;mc&#305; olur.
			  '; }
			echo '
				</td><td valign = "top">
						<div class = "lo_tab" style = "width:450px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "2">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td><img border = "0" src = "imgs/lang/DE.png" /></td>
								<td><input type = "text" value = "'.$m1.'" id = "metaDE" style = "width:360px;" /></td>
							</td>
							<tr>
								<td><img border = "0" src = "imgs/lang/TR.png" /></td>
								<td><input type = "text" value = "'.$m2.'" id = "metaTR" style = "width:360px;" /></td>
							</td>
							<tr>
								<th colspan = "2" align = "right"><div align = "right"> <input type = "button" value = "'.$btntx.'"
								onclick = "javascript:svr.Return(\'meta_for_doc\','.$idnr.',modifyUTFex($_id(\'metaDE\').value),modifyUTFex($_id(\'metaTR\').value));"
								style = "font-weight: bold; padding: 5px;" /></div> </th>
							</tr>
						</table>
						</div>
				</td></tr></table>
				</div>
				<br /><br />
				';

				
				
				

			if ( $lang == "de" ) { $aktdoc = "Neues Unterdokument"; $btntx = "Erstellen"; }
			elseif($lang=="tr" ) { $aktdoc = "Yeni alt belge"; $btntx = "Olu&#351;tur"; }
			echo '
			<table border = "0" cellspacing = "0" cellpadding = "0"><tr><td valign = "top" style = "width:450px;" class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
							'.$aktdoc.'
						</th>
					</tr>
					<tr>
						<td style = "border-top: 1px solid rgb( 220, 220, 220 ); "> Titel: </td>
						<td style = "border-top: 1px solid rgb( 220, 220, 220 ); "> <input type = "text" name = "dnam" id = "dnam" /> </td>
					</tr>
					<tr>
						<th colspan = "2" align = "right"><div align = "right"> <input type = "button" value = "'.$btntx.'"
						onclick = "javascript:add_new_document_to('.$idnr.');"
						style = "font-weight: bold; padding: 5px;" /></div> </th>
					</tr>
				</table>
			</td><td valign = "top" style = "padding-left:20px;width: 300px; text-align: justify; font-family: Arial, Helvetica; font-size: 16px; font-weight: normal;line-height:24px;">
		';
		if ( $lang == "de" ) { echo '
			<b style = "color:rgb(130,30,30);">Hauptdokument l&ouml;schen?</b> <br /><br />
			Um das Dokument <span style = "background:url(\'imgs/btn_orange.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><b style = "color:rgb(70,70,210);">'.$pr.'</b></span> 
			mit samt allen enthaltenen Unterdokumenten vollst&auml;ndig zu l&ouml;schen, klicken Sie bitte 
			<span style = "background:url(\'imgs/btn_lila.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><a href = "javascript:remove_content('.$idnr.');">hier</a></span>.
			</td></tr></table>
		'; } elseif ( $lang == "tr" ) { echo '
			<b style = "color:rgb(130,30,30);">Ana belge silinsin mi?</b> <br /><br />
			<span style = "background:url(\'imgs/btn_orange.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><b style = "color:rgb(70,70,210);">'.$pr.'</b></span> 
			belgesini ve i&ccedil;erdi&#287;i t&uuml;m alt belgeleri birlikte sunucudan kald&#305;rmak istiyorsan&#305;z, o halde 
			<span style = "background:url(\'imgs/btn_lila.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><a href = "javascript:remove_content('.$idnr.');">&#351;uraya</a></span> 
			t&#305;klat&#305;n.
			</td></tr></table>
		';}
			if ( $lang == "de" ) { $aktdoc = "Unterdokumente zu <span style = \"color:rgb(150,30,45);\">$pr</span>"; }
			elseif($lang=="tr" ) { $aktdoc = "<span style = \"color:rgb(150,30,45);\">$pr ana belgesinin</span> alt belgeleri"; }
		echo '

			<br /><br />

			<div class = "lo_tab">
			<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
				<tr>
					<th colspan = "4" align = "right" style = "border-bottom:1px solid rgb(150,150,150); text-align: left; padding: 15px;">
						'.$aktdoc.'
					</th>
				</tr>
				';

				  $query=mysql_query("SELECT * FROM db_5m_content_docs_data WHERE did = '$idnr' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

				  $zr = 0;

				  while ( $datensatz = mysql_fetch_array($query) ) {

						if ( $zr == 0 ) { echo '<tr>'; }

						echo '
							<td style = "border-top: 1px solid rgb( 220, 220, 220 ); " onclick = "javascript:get_document_from('.$idnr.','.$datensatz['id'].');">
							<center>
									<img border = "0" src = "imgs/document.png" /> <br />
									'.geschnitten_($datensatz['doctitle'],18,true).'
							</center>
							</td>';

						$zr++;

						if ( $zr == 4 ) { echo '</tr>'; $zr = 0; }

				  }
				  
				  switch ( $zr ) {
					case 1 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 2 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 3 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
				  }

				echo '
			</table></div>
			';
		  }

			function change_content_image( $grpid, $value ) {
				$pro = zeichensatz($value);
				if ( $pro != "" ) {
					$query=mysql_query("UPDATE aj_projects SET bild = '$pro' WHERE id = '$grpid'");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
			}

			function remove_content( $gid ) {
				$query=mysql_query("DELETE FROM db_5m_content_docs WHERE id = '$gid'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$query=mysql_query("DELETE FROM db_5m_content_docs_data WHERE did = '$gid'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$query=mysql_query("DELETE FROM db_5m_content_docs_gallery WHERE did = '$gid'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}

		   function save_navi_type( $idnr, $option ){
				$query=mysql_query("UPDATE db_5m_content_docs SET docnavi = '$option' WHERE id = '$idnr'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		   }

		   function meta_for_doc( $idnr, $met1, $met2 ) {
				$meta1 = $met1; $meta1 = ereg_replace( "'", '%@1', $meta1 );
				$meta2 = $met2; $meta2 = ereg_replace( "'", '%@1', $meta2 );
				// *** //
				$meta1 = modifyUTFex($meta1);
				$meta2 = modifyUTFex($meta2);
				// *** //
				$query=mysql_query("UPDATE db_5m_content_docs SET docmeta01 = '$meta1', docmeta02 = '$meta2' WHERE id = '$idnr'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		   }

			function link_to_doc( $idnr, $link1, $link2 ) {
				$query=mysql_query("UPDATE db_5m_content_docs SET docadvr = '$link1', doccamp = '$link2' WHERE id = '$idnr'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}

			function search_motor_flag( $idnr, $flag ) {
				if ( $flag == "true" ) { $fl = 1; } else { $fl = 0; }
				$query=mysql_query("UPDATE db_5m_content_docs SET searchflag = '$fl' WHERE id = '$idnr'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}


			

			
			
			
			
			
			
			
			
			
			
	
			
			



		  function get_content_of_advr( $idnr, $dtyp, $lang, $elang = "" ) {
			  /* ------------------ LOAD THE CONTENT OF A SPECIFIC CONTENT ------------------- */
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$idnr' AND doctype = '$dtyp' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['doctitle'];
				$m1 = $datensatz['docmeta01']; $m1 = ereg_replace( '%@1', "'", $m1 );
				$m2 = $datensatz['docmeta02']; $m2 = ereg_replace( '%@1', "'", $m2 );
				$na = $datensatz['docnavi'];
				$sm = $datensatz['docadvr'];
			  }
			  // *** //
			  if ( $lang == "de" ) { $aktdoc = "Aktuelles Dokument"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Belge"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

			
			
			
			  if ( $lang == "de" ) { $aktdoc = "Meldung"; }
			  elseif($lang=="tr" ) { $aktdoc = "Reklam-Duyurusu"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top" style = "padding-right:20px;">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "2">
									'.$aktdoc.'
								</th>
							</tr>
							<tr><td colspan = "2">
';
				if ( $sm == 0 ) {
					$ck1 = " checked ";
					$ck2 = "";
				} else {
					$ck1 = "";
					$ck2 = " checked ";
				}
			  if ( $lang == "de" ) { $btex = 'Darstellung im Fenster'; }
			  elseif($lang=="tr" ) { $btex = 'Pencere i&ccedil;inde'; }
echo '
							</td>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "typico" value = "0" onclick = "javascript:advert_flag('.$idnr.',0);" '.$ck1.' /></td>
								<td valign = "top">'.$btex.'</td>
							</tr>';
			  if ( $lang == "de" ) { $btex = 'Vollbild'; }
			  elseif($lang=="tr" ) { $btex = 'Tam Ekran'; }
							echo '
							<tr>
								<td valign = "top"><input type = "radio" name = "typico" value = "1" onclick = "javascript:advert_flag('.$idnr.',1);" '.$ck2.' /></td>
								<td valign = "top">'.$btex.'</td>
							</tr>
						</table>
						</div>
				</td><td valign = "top">';
			  if ( $lang == "de" ) { echo 'Soll die Meldung in einem Fenster dargestellt werden oder die gesamte Seite &uuml;berdecken?'; }
			  elseif($lang=="tr" ) { echo 'Duyuru bir pencere i&ccedil;inde mi g&omul;sterilsin yoksa ekran#305; komple kaplas#305;n m#305;?'; }
			  echo '</td></tr></table>
';


		  }

		  
		  
		  
		  
		  
		  
		  
		  function get_content_of_camp( $idnr, $dtyp, $lang, $elang = "" ) {
			  /* ------------------ LOAD THE CONTENT OF A SPECIFIC CONTENT ------------------- */
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$idnr' AND doctype = '$dtyp' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['doctitle'];
				$m1 = $datensatz['docmeta01']; $m1 = ereg_replace( '%@1', "'", $m1 );
				$m2 = $datensatz['docmeta02']; $m2 = ereg_replace( '%@1', "'", $m2 );
				$na = $datensatz['docnavi'];
				$sm = $datensatz['doccamp'];
				$dc = $datensatz['doccamp_interval'];
				$datum1 = $datensatz['camp_start'];
				$datum2 = $datensatz['camp_end'];
			  }
			  // *** //
			  if ( $lang == "de" ) { $aktdoc = "Aktuelles Dokument"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Belge"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

			
			
			
			  if ( $lang == "de" ) { $aktdoc = "Wechseleffekt"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ge&ccedil;i&#351; efekti"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top" style = "padding-right:20px;">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "2">
									'.$aktdoc.'
								</th>
							</tr>
							<tr><td colspan = "2">
';
				if ( $sm == 0 ) {
					$ck1 = " checked ";
					$ck2 = "";
				} else {
					$ck1 = "";
					$ck2 = " checked ";
				}
			  if ( $lang == "de" ) { $btex = '&Uuml;berblendung'; }
			  elseif($lang=="tr" ) { $btex = 'G&ouml;z kama&#351;t&#305;r&#305;c&#305;'; }
echo '
							</td>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "typico" value = "0" onclick = "javascript:camp_flag('.$idnr.',0);" '.$ck1.' /></td>
								<td valign = "top" width = "100%">'.$btex.'</td>
							</tr>';
			  if ( $lang == "de" ) { $btex = 'Einrollen'; }
			  elseif($lang=="tr" ) { $btex = 'Kaygan'; }
							echo '
							<tr>
								<td valign = "top"><input type = "radio" name = "typico" value = "1" onclick = "javascript:camp_flag('.$idnr.',1);" '.$ck2.' /></td>
								<td valign = "top" width = "100%">'.$btex.'</td>
							</tr>
						</table>
						</div>
				</td><td valign = "top">';
			  if ( $lang == "de" ) { echo 'Soll der Wechsel zwischen den Dokumenten dieser Kampagne mittels &Uuml;berblendungseffekt oder Einrollen umgesetzt werden?'; }
			  elseif($lang=="tr" ) { echo 'Bir kampanya belgesinden bir sonraki belgeye ge&ccedil;&#351; g&ouml;z kama&#351;t&#305;r&#305;c&#305; m&#305; olsun yoksa ge&ccedil;i&#351; kaygan m&#305; olsun?'; }
			  echo '</td></tr></table><br /><br />
';




			  if ( $lang == "de" ) { $aktdoc = "Wechselinterval"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ge&ccedil;&#351; s&uuml;resi"; }
echo '			  
				<div style = "width:900px;">
				<table border = "0"><tr>
<td valign = "top">';
			  if ( $lang == "de" ) { echo 'In wie viel Sekunden soll der Wechsel zum n&auml;chsten Dokument ausgef&uuml;hrt werden?'; }
			  elseif($lang=="tr" ) { echo 'Ka&ccedil; saniyede bir sonraki belgeye ge&ccedil;i&#351; olsun?'; }
			  echo '</td>
				<td valign = "top" style = "padding-right:20px;">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "4">
									'.$aktdoc.'
								</th>
							</tr>
							<tr><td colspan = "4">
';
				$cami = array();
				$cami[0] = ""; $cami[1] = ""; $cami[2] = ""; $cami[3] = ""; $cami[4] = ""; $cami[5] = ""; $cami[6] = ""; $cami[7] = "";
				$cami[$dc] = " checked ";
			  if ( $lang == "de" ) { $btex = 'Sekunden'; }
			  elseif($lang=="tr" ) { $btex = 'Saniye'; }
echo '
							</td>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "intero" value = "0" onclick = "javascript:camp_intr('.$idnr.',0);" '.$cami[0].' /></td>
								<td valign = "top" width = "50%">5 '.$btex.'</td>
								<td valign = "top"><input type = "radio" name = "intero" value = "0" onclick = "javascript:camp_intr('.$idnr.',4);" '.$cami[4].' /></td>
								<td valign = "top" width = "50%">30 '.$btex.'</td>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "intero" value = "1" onclick = "javascript:camp_intr('.$idnr.',1);" '.$cami[1].' /></td>
								<td valign = "top" width = "50%">10 '.$btex.'</td>
								<td valign = "top"><input type = "radio" name = "intero" value = "0" onclick = "javascript:camp_intr('.$idnr.',5);" '.$cami[5].' /></td>
								<td valign = "top" width = "50%">45 '.$btex.'</td>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "intero" value = "2" onclick = "javascript:camp_intr('.$idnr.',2);" '.$cami[2].' /></td>
								<td valign = "top" width = "50%">15 '.$btex.'</td>
								<td valign = "top"><input type = "radio" name = "intero" value = "0" onclick = "javascript:camp_intr('.$idnr.',6);" '.$cami[6].' /></td>
								<td valign = "top" width = "50%">60 '.$btex.'</td>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "intero" value = "3" onclick = "javascript:camp_intr('.$idnr.',3);" '.$cami[3].' /></td>
								<td valign = "top" width = "50%">20 '.$btex.'</td>
								<td valign = "top"><input type = "radio" name = "intero" value = "0" onclick = "javascript:camp_intr('.$idnr.',7);" '.$cami[7].' /></td>
								<td valign = "top" width = "50%">90 '.$btex.'</td>
							</tr>
							';
echo '
						</table>
						</div>
				</td>
			  </tr></table><br /><br />
			  <div id="datepicker"></div>
			  
';



			
			  if ( $lang == "de" ) { $aktdoc = "Beginn"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ba&#351;lang&#305;&ccedil;"; }
			echo '
				<table border = "0"><tr>
				<td valign = "top">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;">
								'.$aktdoc.'
								</th>
							</tr>
							<tr><td valign = "top">
								';
								$d = ""; $m = ""; $y = ""; $mo = 0; $datestring = $datum1;
								// *** //
								for( $i = 0; $i < strlen( $datestring ); $i++ ) {
									if ( $datestring[$i] == '-' ) {
										$mo++;
									} else {
										switch( $mo ) {
											case 0: $y .= $datestring[$i]; break;
											case 1: $m .= $datestring[$i]; break;
											case 2: $d .= $datestring[$i]; break;
										}
									}
								}
								$date_id = "_from_";
								include "date_picker.php";
								echo '
							</td></tr>
						</table>
						</div>
			    </td>';
			  if ( $lang == "de" ) { $aktdoc = "Ende"; }
			  elseif($lang=="tr" ) { $aktdoc = "Biti&#351;"; }
				echo '
				<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;">
								'.$aktdoc.'
								</th>
							</tr>
							<tr><td valign = "top">
								';
								$d = ""; $m = ""; $y = ""; $mo = 0; $datestring = $datum2;
								// *** //
								for( $i = 0; $i < strlen( $datestring ); $i++ ) {
									if ( $datestring[$i] == '-' ) {
										$mo++;
									} else {
										switch( $mo ) {
											case 0: $y .= $datestring[$i]; break;
											case 1: $m .= $datestring[$i]; break;
											case 2: $d .= $datestring[$i]; break;
										}
									}
								}
								$date_id = "_to_";
								include "date_picker.php";
								echo '
							</td></tr>
						</table>
						</div>
				</td>
			  </tr></table><br /><br />

';

			if ( $lang == "de" ) { $aktdoc = "Neues Dokument"; $btntx = "Erstellen"; }
			elseif($lang=="tr" ) { $aktdoc = "Yeni belge"; $btntx = "Olu&#351;tur"; }
			echo '
			<table border = "0" cellspacing = "0" cellpadding = "0"><tr><td valign = "top" style = "width:450px;" class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
							'.$aktdoc.'
						</th>
					</tr>
					<tr>
						<td style = "border-top: 1px solid rgb( 220, 220, 220 ); "> Titel: </td>
						<td style = "border-top: 1px solid rgb( 220, 220, 220 ); "> <input type = "text" name = "dnam" id = "dnam" /> </td>
					</tr>
					<tr>
						<th colspan = "2" align = "right"><div align = "right"> <input type = "button" value = "'.$btntx.'"
						onclick = "javascript:add_new_document_to('.$idnr.');"
						style = "font-weight: bold; padding: 5px;" /></div> </th>
					</tr>
				</table>
			</td><td valign = "top" style = "padding-left:20px;width: 300px; text-align: justify; font-family: Arial, Helvetica; font-size: 16px; font-weight: normal;line-height:24px;">
		';
		if ( $lang == "de" ) { echo '
			<b style = "color:rgb(130,30,30);">Kampagne l&ouml;schen?</b> <br /><br />
			Um die Kampagne <span style = "background:url(\'imgs/btn_orange.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><b style = "color:rgb(70,70,210);">'.$pr.'</b></span> 
			mit samt allen enthaltenen Dokumenten vollst&auml;ndig zu l&ouml;schen, klicken Sie bitte 
			<span style = "background:url(\'imgs/btn_lila.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><a href = "javascript:remove_content('.$idnr.');">hier</a></span>.
			</td></tr></table>
		'; } elseif ( $lang == "tr" ) { echo '
			<b style = "color:rgb(130,30,30);">Ana belge silinsin mi?</b> <br /><br />
			<span style = "background:url(\'imgs/btn_orange.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><b style = "color:rgb(70,70,210);">'.$pr.'</b></span> 
			kampanyas&#305;n&#305; ve i&ccedil;erdi&#287;i t&uuml;m belgeleri birlikte sunucudan kald&#305;rmak istiyorsan&#305;z, o halde 
			<span style = "background:url(\'imgs/btn_lila.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><a href = "javascript:remove_content('.$idnr.');">&#351;uraya</a></span> 
			t&#305;klat&#305;n.
			</td></tr></table>
		';}
			if ( $lang == "de" ) { $aktdoc = "Dokumente zu Kampagne <span style = \"color:rgb(150,30,45);\">$pr</span>"; }
			elseif($lang=="tr" ) { $aktdoc = "<span style = \"color:rgb(150,30,45);\">$pr kampanyas&#305; belgeleri"; }
		echo '

			<br /><br />

			<div class = "lo_tab">
			<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
				<tr>
					<th colspan = "4" align = "right" style = "border-bottom:1px solid rgb(150,150,150); text-align: left; padding: 15px;">
						'.$aktdoc.'
					</th>
				</tr>
				';

				  $query=mysql_query("SELECT * FROM db_5m_content_docs_data WHERE did = '$idnr' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

				  $zr = 0;

				  while ( $datensatz = mysql_fetch_array($query) ) {

						if ( $zr == 0 ) { echo '<tr>'; }

						echo '
							<td style = "border-top: 1px solid rgb( 220, 220, 220 ); " onclick = "javascript:get_document_from('.$idnr.','.$datensatz['id'].');">
							<center>
									<img border = "0" src = "imgs/document.png" /> <br />
									'.$datensatz['doctitle'].'
							</center>
							</td>';

						$zr++;

						if ( $zr == 4 ) { echo '</tr>'; $zr = 0; }

				  }
				  
				  switch ( $zr ) {
					case 1 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 2 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 3 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
				  }

				echo '
			</table></div>
			';

		  }

		  
		  
		  
		  
		  
		  
		  







		  function get_content_of_group_declaration( $idnr, $lang, $elang = "" ) {
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$idnr' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['doctitle'];
			  }
			  if ( $lang == "de" ) { $aktdoc = "Atuelle Gruppe"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Grup"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

			echo '
				<table border = "0" width = "100%"><tr>
				<td width = "50%"><div style = "text-align:left;" align = "left">
				';
				if ($lang=="de"){echo'Bitte machen Sie einen Haken vor all den Mitgliedern, welche in diese Gruppe aufgenommen werden sollen.';}
				elseif($lang=="tr"){echo'Gruba almak istedi&#287;iniz t&uuml;m &uuml;yeleri &ccedil;etleyin.';}
				echo '</div>
				</td><td width = "50%">
				<div style = "text-align:right;" align = "right">
					<a href = "javascript:remove_group('.$idnr.');">';
					if ($lang=="de"){echo'Diese Gruppe l&ouml;schen?';}elseif($lang=="tr"){echo'Bu grubu sil?';}
					echo '</a>
				</div><br /><br />
				</td></tr></table>
			';
			$query=mysql_query("SELECT * FROM db_5m_users WHERE ((ingroup IS NULL) OR (ingroup = '$idnr')) AND region = '0' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  $cll=0;$clr="#ffffff";$nof=0;
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$nof++;
				if($nof==1){
				echo'
					<table border = "0" width = "100%" style = "border-bottom:1px solid rgb(200,200,200);padding-bottom:2px;margin-bottom:2px;background-color:#000000;color:#ffffff;font-weight:bold;"><tr>
						<td width = "12%">*</td>
						<td width = "22%">';if ($lang=="de"){echo'Nick';}elseif($lang=="tr"){echo'Takma&nbsp;Ad';};echo'</td>
						<td width = "22%">';if ($lang=="de"){echo'Vorname';}elseif($lang=="tr"){echo'&#304;sim';};echo'</td>
						<td width = "22%">';if ($lang=="de"){echo'Nachname';}elseif($lang=="tr"){echo'Soyisim';};echo'</td>
						<td width = "22%">';if ($lang=="de"){echo'E-Mail';}elseif($lang=="tr"){echo'E-Posta';};echo'</td>
					</tr></table>
				';
				}
				if ( $datensatz['ingroup'] == $idnr ) { $ck = " checked "; } else { $ck = ""; }
				echo '
					<table border = "0" width = "100%" style = "border-bottom:1px solid rgb(200,200,200);padding-bottom:2px;margin-bottom:2px;background-color:'.$clr.';"><tr>
						<td width = "12%"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_me(this.checked,'.$datensatz['id'].','.$idnr.');" '.$ck.' /></td>
						<td width = "22%">'.$datensatz['nick'].'</td>
						<td width = "22%">'.$datensatz['firstname'].'</td>
						<td width = "22%">'.$datensatz['lastname'].'</td>
						<td width = "22%">'.$datensatz['e_mail'].'</td>
					</tr></table>
				';
				if ( $cll==0 ) {
					$cll=1;
					$clr="rgb(250,250,250)";
				} elseif( $cll==1 ) {
					$cll=0;
					$clr="#ffffff";
				}
			  }
			  if($nof==0){
				if ($lang=="de"){
					echo 'Derzeit sind keine Mitglieder im System registriert oder alle Mitglieder sind bereits anderen Gruppen zugeteilt wurden. Administratoren wie Sie k&ouml;nnen nicht in Gruppen zugeordnet werden.';
				} elseif($lang=="tr"){
					echo 'Sistemde kay&#305;tl&#305; &uuml;ye bulunmuyor veya &uuml;yeler di&#287;er gruplara tabi. Sizin gibi y&ouml;neticiler ise gruplara tabi olamaz.';
				}
			  }
			  echo '<br /><br /><br />';
		  }

		  function link_user_to_group( $val, $uid, $gid ) {
			if ( $val == 1 ) {
			  $query=mysql_query("UPDATE db_5m_users SET ingroup = '$gid' WHERE id = '$uid'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			} else {
			  $query=mysql_query("UPDATE db_5m_users SET ingroup = NULL WHERE id = '$uid'");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}
		  }

			
			



		function get_form_attributes($idnr,$lang,$elang=""){
			  $query=mysql_query("SELECT * FROM db_5m_forms WHERE id = '$idnr' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$titelo = $datensatz['doctitle'];
				if ($lang=="de"){$co="01";}elseif ($lang=="tr"){$co="02";}
				if($datensatz["doctitle$co"]!=""){$titelo=$datensatz["doctitle$co"];}
				$share = $datensatz['docshare'];
				$usage = $datensatz['usetype'];
				$linka = $datensatz['doclink'];
			  }
			  $pr=$titelo;
			  // *** //
			  $docsh = array();
			  $docsh[0] = "";$docsh[1] = "";$docsh[2] = "";$docsh[3] = "";$docsh[4] = "";$docsh[54] = "";
			  $docsh[$share] = " checked ";
			  // *** //
			  $docty = array();
			  $docty[0] = "";$docty[1] = "";
			  $docty[$usage] = " checked ";
			  // *** //
			  $docta = array();
			  $docta[0] = "";$docta[1] = "";$docta[2] = "";
			  $docta[$linka] = " checked ";
			  // *** //
			  if ( $lang == "de" ) { $aktdoc = "Aktuelles Formular"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Form"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top">';
			  if ( $lang == "de" ) { $aktdoc = "Freigabe"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "Yerle&#351;im"; $btntx = "Kaydet"; }
			  if ( $dtyp == 0 ) {
				echo '
						<div class = "lo_tab" style = "width:560px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td><input type = "radio" name = "typo" id = "typo1" value = "0" onchange = "javascript:sharing_mode_form('.$idnr.',this.value);" '.$docsh[0].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Formular wird manuell freigegeben'; }
								  elseif($lang=="tr" ) { echo'Form manuel olarak yerle&#351;tirilir'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typo" id = "typo2" value = "1" onchange = "javascript:sharing_mode_form('.$idnr.',this.value);" '.$docsh[1].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Formular wird allen Gruppen freigegeben'; }
								  elseif($lang=="tr" ) { echo'Form t&uuml;m gruplara sunulur'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typo" id = "typo3" value = "2" onchange = "javascript:sharing_mode_form('.$idnr.',this.value);" '.$docsh[2].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Formular wird nur den Blog-Admins freigegeben'; }
								  elseif($lang=="tr" ) { echo'Form sadece Blog-Y&ouml;neticilerine sunulur'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typo" id = "typo4" value = "3" onchange = "javascript:sharing_mode_form('.$idnr.',this.value);" '.$docsh[3].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Formular wird nur f&uuml;r die Administratoren freigegeben'; }
								  elseif($lang=="tr" ) { echo'Form sadece y&ouml;neticilere sunulur'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typo" id = "typo5" value = "4" onchange = "javascript:sharing_mode_form('.$idnr.',this.value);" '.$docsh[4].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Formular wird den Blog- und beschr&auml;nkten Administratoren freigegeben'; }
								  elseif($lang=="tr" ) { echo'Form Blog- ve s&#305;n&#305;rl&#305; y&ouml;neticilere sunulur'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typo" id = "typo6" value = "5" onchange = "javascript:sharing_mode_form('.$idnr.',this.value);" '.$docsh[5].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Formular wird allen Mitgliedern und Administratoren freigegeben'; }
								  elseif($lang=="tr" ) { echo'Form t&uuml; &uuml;yelere ve y&ouml;neticilere sunulur'; }
								echo'</td>
							</tr>
						</table>
						</div>'; }
		echo '
				</td><td valign = "top" style = "padding-left:20px;">';
			if ( $dtyp == 0 ) {
			  if ( $lang == "de" ) { echo '
				Die manuelle Freigabe erfordert die Implementierung des Formulars in die Men&uuml;leiste oder in die Gruppen durch den Adminsitrator selbst.
				<br /><br />
				Falls das Formular f&uuml;r bestimmte Nutzerschaft sofort zug&auml;glich sein soll, so haben Sie von hier aus die Gelegenheit, das 
				Formular f&uuml;r eine bestimmte Nutzerschaft mit sofortiger Wirkung freizugeben.
			  '; }
			  elseif($lang=="tr" ) { echo '
				Manuel sunum formun y&ouml;netici taraf&#305;ndan M&ouml;n&uuml;-&Ccedil;ubu&#287;una veya gruplara ba&#287;lanmas&#305;n&#305; 
				gerektiriyor.
				<br /><br />
				&#350;ayet form hemen belli bir kullan&#305;c&#305; kitlesine sunulacak ise, o halde uygun se&ccedil;imi yap&#305;n.
			  '; }
			}
echo '
				</td></tr></table>
				</div>
				<br /><br />
				';

				
				
				
				
				
				
				
				
				
				
				
				
				
				
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top" style = "padding-right:20px;">';
			if ( $dtyp == 0 ) {
			  if ( $lang == "de" ) { echo '
				Wenn das Formular dazu verwendet werden soll, Statistiken zu erstellen, so ist es notwendig das Formular als Statistikformular 
				zu kennzeichnen.
			  '; }
			  elseif($lang=="tr" ) { echo '
				E&#287;er bu form istatistik olarak de&#287;erlendirilecekse, o halde formu &#304;statistik formu olarak etkinle&#351;tirilmesi gerekiyor.
			  '; }
			}
		echo '
				</td><td valign = "top">';
			  if ( $lang == "de" ) { $aktdoc = "Art"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "T&uuml;r"; $btntx = "Kaydet"; }
			  if ( $dtyp == 0 ) {
				echo '
						<div class = "lo_tab" style = "width:560px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td><input type = "radio" name = "typu" id = "typo1" value = "0" onchange = "javascript:type_mode_form('.$idnr.',this.value);" '.$docty[0].' /></td>
								<td width = "100%">';
								  if ( $lang == "de" ) { echo'Datensammelformular'; }
								  elseif($lang=="tr" ) { echo'Veri toplama formu'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typu" id = "typo2" value = "1" onchange = "javascript:type_mode_form('.$idnr.',this.value);" '.$docty[1].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Statistikformular'; }
								  elseif($lang=="tr" ) { echo'&#304;statistik formu'; }
								echo'</td>
							</tr>
						</table>
						</div>'; }
echo '
				</td></tr></table>';
				echo '</div>
				<br /><br />
				';
				
				
				
				
				
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top">';
			  if ( $lang == "tr" ) { $aktdoc = "K&#305;sayol"; $btntx = "Speichern"; }
			  elseif($lang=="de" ) { $aktdoc = "Verkn&uuml;pfung"; $btntx = "Kaydet"; }
			  if ( $dtyp == 0 ) {
				echo '
						<div class = "lo_tab" style = "width:560px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td><input type = "radio" name = "typa" id = "rtypo3" value = "0" onchange = "javascript:link_mode_form('.$idnr.',this.value);" '.$docta[0].' /></td>
								<td width = "100%">';
								  if ( $lang == "de" ) { echo'Keine'; }
								  elseif($lang=="tr" ) { echo'Yok'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typa" id = "rtypo4" value = "1" onchange = "javascript:link_mode_form('.$idnr.',this.value);" '.$docta[1].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Kurs-Anmeldung'; }
								  elseif($lang=="tr" ) { echo'Kurs Kay&#305;tlar&#305;'; }
								echo'</td>
							</tr>
							<tr>
								<td><input type = "radio" name = "typa" id = "rtypo5" value = "2" onchange = "javascript:link_mode_form('.$idnr.',this.value);" '.$docta[2].' /></td>
								<td>';
								  if ( $lang == "de" ) { echo'Mitglieder-Registrierung'; }
								  elseif($lang=="tr" ) { echo'&Uuml;ye Kay&#305;tlar&#305;'; }
								echo'</td>
							</tr>
						</table>
						</div>'; }
echo '
				</td><td valign = "top" style = "padding-left:20px;">';
			if ( $dtyp == 0 ) {
			  if ( $lang == "de" ) { echo '
				Das Formular kann zur <b>Kurs-Anmeldung</b> oder <b>Mitglieder-Registrierung</b> gelinkt werden. Dadurch werden die <b>Eing&auml;nge</b> 
				unter Konto auf der Startseite hinzugef&uuml;gt.<br /><br />
				Das ist eine Schnellstart-Verkn&uuml;pfung. Sie m&uuml;ssen nicht zuerst zur <b>Eingehenden Formulare</b> und dann zum 
				betroffenen Formular wechseln, um an die neuen Daten rannzukommen.
			  '; }
			  elseif($lang=="tr" ) { echo '
				Bu form <b>Kurs kay&#305;tlar&#305;na</b> veya <b>&Uuml;ye kay&#305;tlar&#305;na</b> ba&#287;lanabilir. B&ouml;ylece 
				bu forma gelen d&ouml;k&uuml;mler anasayfadaki <b>Hesap</b> biriminde g&ouml;z&uuml;k&uuml;r ve eri&#351; kolayla&#351;&#305;r.
				<br /><br />
				Bu bir k&#305;sayoldur. <b>Giren D&ouml;k&uuml;mlere</b> girip, formu se&ccedil;mek zorunda kalmadan verilere bir 
				t&#305;kla ula&#351;ma kolayl&#305;&#287;&#305; sunar.
			  '; }
 			}
		echo '
				</td></tr></table>';
				echo '</div>
				<br /><br />
				';
				
		}
			
			

			
			
			


		  function get_content_of_group( $idnr, $lang, $elang = "" ) {
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$idnr' AND doctype = '6' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['doctitle'];
				$d1 = $datensatz['docadvr'];
				$d2 = $datensatz['doccamp'];
			  }
			  // *** //
			  $dd1 = ""; $dd2 = "";
			  // *** MELDUNGSTEXT AUSLESEN *** //
			  if ( isset($d1) ) {
				  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$d1' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensatz = mysql_fetch_array($query) ) {
					$dd1 = "($d1) ".$datensatz['doctitle'];
				  }
			  }
			  // *** KAMPANGENTEXT AUSLESEN *** //
			  if ( isset($d2) ) {
				  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$d2' ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensatz = mysql_fetch_array($query) ) {
					$dd2 = "($d2) ".$datensatz['doctitle'];
				  }
			  }
			  // *** //
			  if ( $lang == "de" ) { $aktdoc = "Aktuelle Gruppe"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Grup"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top">';
			  if ( $lang == "de" ) { $aktdoc = "Extras"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ekstralar"; }
			  if ( $dtyp == 0 ) {
				echo '
						<div class = "lo_tab" style = "width:320px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
';
			  if ( $lang == "de" ) { $btntx = "Meldung"; }
			  elseif($lang=="tr" ) { $btntx = "Reklam&nbsp;Duyurusu"; }
echo '
								<td>'.$btntx.':</td>
								<td><input type = "text" value = "'.$dd1.'" id = "meldung" style = "width:150px;"
								onclick = "javascript:if(document.getElementById(\'meldpop\').style.display==\'none\'){document.getElementById(\'meldpop\').style.display=\'block\';document.getElementById(\'campop\').style.display=\'none\';}else if(document.getElementById(\'meldpop\').style.display==\'block\'){document.getElementById(\'meldpop\').style.display=\'none\';}"
								/>
			<div style = "display:none;position:absolute;-khtml-border-radius: 20px; padding:10px;
				border:1px solid rgb(150,150,150);background-color:#FFFFFF; border-radius: 20px; -moz-border-radius: 20px; -webkit-border-radius: 20px;
				box-shadow:2px 2px 4px rgb(180,180,180);-moz-box-shadow:2px 2px 4px rgb(180,180,180);-webkit-box-shadow:2px 2px 4px rgb(180,180,180);
				-khtml-box-shadow:2px 2px 4px rgb(180,180,180);" id = "meldpop"
				><div style = "width:280px;height:120px;overflow:auto">
			';
				  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '3' ORDER by id"); // 3 = Meldungen
				  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensat4 = mysql_fetch_array($quer4) ) {
				  echo '<div class = "inner_entry" onclick = "javascript:document.getElementById(\'meldung\').value = \'('.$datensat4['id'].') '.$datensat4['doctitle'].'\';document.getElementById(\'meldpop\').style.display=\'none\';">';
				  echo $datensat4['doctitle'];
				  echo '</div>';
				  }
			echo '
			</div></div></td>
							</tr><tr>
';
			  if ( $lang == "de" ) { $btntx = "Kampagne:"; }
			  elseif($lang=="tr" ) { $btntx = "Kampanya:"; }
echo '
								<td>'.$btntx.'</td>
								<td><input type = "text" value = "'.$dd2.'" id = "kampagn" style = "width:150px;"
								onclick = "javascript:if(document.getElementById(\'campop\').style.display==\'none\'){document.getElementById(\'campop\').style.display=\'block\';document.getElementById(\'meldpop\').style.display=\'none\';}else if(document.getElementById(\'campop\').style.display==\'block\'){document.getElementById(\'campop\').style.display=\'none\';}"
								/>
			<div style = "display:none;position:absolute;-khtml-border-radius: 20px; padding:10px;
				border:1px solid rgb(150,150,150);background-color:#FFFFFF; border-radius: 20px; -moz-border-radius: 20px; -webkit-border-radius: 20px;
				box-shadow:2px 2px 4px rgb(180,180,180);-moz-box-shadow:2px 2px 4px rgb(180,180,180);-webkit-box-shadow:2px 2px 4px rgb(180,180,180);
				-khtml-box-shadow:2px 2px 4px rgb(180,180,180);" id = "campop"
				><div style = "width:280px;height:120px;overflow:auto">
			';
				  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '5' ORDER by id");
				  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				  while ( $datensat4 = mysql_fetch_array($quer4) ) {
				  echo '<div class = "inner_entry" onclick = "javascript:document.getElementById(\'kampagn\').value = \'('.$datensat4['id'].') '.$datensat4['doctitle'].'\';document.getElementById(\'campop\').style.display=\'none\';">';
				  echo $datensat4['doctitle'];
				  echo '</div>';
				  }
			echo '
			</div></div></td>
';
			  if ( $lang == "de" ) { $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $btntx = "Kaydet"; }
echo '
							<tr>
								<th colspan = "2" align = "right"><div align = "right"> <input type = "button" value = "'.$btntx.'"
								onclick = "javascript:link_to_doc('.$idnr.',$_id(\'meldung\').value,$_id(\'kampagn\').value);"
								style = "font-weight: bold; padding: 5px;" /></div> </th>
							</tr>
						</table>
						</div>'; }
		echo '
				</td><td valign = "top" style = "padding-left:20px;">';
			if ( $dtyp == 0 ) {
			  if ( $lang == "de" ) { echo '
				Wenn der Besucher das Dokument &ouml;ffnet, so k&ouml;nnen Sie dem Besucher sofort eine Meldung anzeigen lassen. 
				Der Besucher klickt die Meldung weg, damit er das Dokument selbst zu Gesicht bekommt.<br /><br />
				Wenn Sie m&ouml;chten, kann beim Laden des Dokuments &uuml;ber dem Dokument eine Kampagne laufen.
			  '; }
			  elseif($lang=="tr" ) { echo '
				Misafiriniz belgeyi i&ccedil;eren sayfay&#305; a&ccedil;arken reklam duyurusu ile kar&#351;&#305;la&#351;abilir. As&#305;l belgeye ge&ccedil;mek 
				i&ccedil;in reklam penceresini kapatmas&#305; gerekir.<br /><br />
				Sayfa a&ccedil;&#305;l&#305;rken bir kampanya sayfan&#305;n hemen &uuml;st&uuml;nde yer alabilir.
			  '; }
			}
echo '
				</td></tr></table>
				</div>
				<br /><br />
				';
				// ******************************************************************** //
				echo '<div style = "width:900px;"><table border = "0" width = "100%"><tr>';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Dokumente"; }
				elseif($lang=="tr" ) { $aktdoc = "Belgeler"; }
				echo '<td valign = "top">
						<div class = "lo_tab" style = "width:280px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry1 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_group_connect WHERE doctype = '0' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry1[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '0' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = $datensat4['doctitle'];
								  if ($lang=="de"){$co="01";}elseif($lang=="tr"){$co="02";}
								  if ( $datensat4["doctitle$co"] != "" ) { $titelo = $datensat4["doctitle$co"]; }
								  $ckd = "";
								  foreach($arry1 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_doc_to_group(0,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td valign = "top" width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Dokumente zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Dokumente, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba belgeler sunuabilirsiniz. Sunmak istedi&#287;iniz belgeyi &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Bildergallerien"; }
				elseif($lang=="tr" ) { $aktdoc = "Resim galerileri"; }
				echo '<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:280px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry2 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_group_connect WHERE doctype = '4' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry2[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '4' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = $datensat4['doctitle'];
								  if ($lang=="de"){$co="01";}elseif($lang=="tr"){$co="02";}
								  if ( $datensat4["doctitle$co"] != "" ) { $titelo = $datensat4["doctitle$co"]; }
								  $ckd = "";
								  foreach($arry2 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_doc_to_group(4,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td valign = "top" width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Bildergallerien zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Gallerien, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba resim galerileri sunuabilirsiniz. Sunmak istedi&#287;iniz resim galerilerini &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Formulare"; }
				elseif($lang=="tr" ) { $aktdoc = "Formlar"; }
				echo '<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:280px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry3 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_group_connect WHERE doctype = '100' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry3[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_forms ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = $datensat4['doctitle'];
								  if ($lang=="de"){$co="01";}elseif($lang=="tr"){$co="02";}
								  if ( $datensat4["doctitle$co"] != "" ) { $titelo = $datensat4["doctitle$co"]; }
								  $ckd = "";
								  foreach($arry3 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_doc_to_group(100,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td valign = "top" width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Formulare zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Formulare, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba formlar sunuabilirsiniz. Sunmak istedi&#287;iniz formlar&#305; &ccedil;etleyin.'; }						echo '
					  </td>
				';
				// *** //
				echo '</tr></table></div><br /><br />';
				// ******************************************************************** //
				echo '<div style = "width:900px;"><table border = "0" width = "100%"><tr>';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "PDF-Dokumente"; }
				elseif($lang=="tr" ) { $aktdoc = "PDF-Belgeleri"; }
				echo '<td valign = "top">
						<div class = "lo_tab" style = "width:420px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry4 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_group_connect WHERE doctype = '101' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry4[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_pdfs ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = utf8_encode(geschnitten_(get_file_name($datensat4['datei']),40));
								  $ckd = "";
								  foreach($arry4 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0" title = "'. utf8_encode(get_file_name($datensat4['datei'])).'"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:16px;" value = "'.$datensat4['datei'].'" onchange = "javascript:link_doc_to_group(101,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td><img border = "0" style = "width:16px;" src = "imgs/fts/doc_pdf.png" /></td>
										<td width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte PDF-Dokumente zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Dokumente, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba PDF-belgeleri sunuabilirsiniz. Sunmak istedi&#287;iniz belgeyi &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Dateien"; }
				elseif($lang=="tr" ) { $aktdoc = "Dosyalar"; }
				echo '<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:420px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry5 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_group_connect WHERE doctype = '102' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry5[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_files ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo =  utf8_encode(geschnitten_(get_file_name($datensat4['datei']),40));
								  $daty = strtolower(get_file_type($datensat4['datei']));
								  $dati = $daty;
								  // *** //
								  if ( $daty == "pdf" ) { $daty = "doc_pdf"; } else
								  if ( $daty == "bmp" ) { $daty = "picture_bmp"; } else
								  if ( $daty == "jpg" ) { $daty = "picture_jpg"; } else
								  if ( $daty == "jpeg" ) { $daty = "picture_jpg"; } else
								  if ( $daty == "png" ) { $daty = "picture_png"; } else
								  if ( $daty == "ico" ) { $daty = "picture_ico"; } else
								  if ( $daty == "psd" ) { $daty = "picture_photoshop"; } else
								  if ( $daty == "tif" ) { $daty = "picture_tiff"; } else
								  if ( $daty == "tiff" ) { $daty = "picture_tiff"; } else
								  if ( $daty == "cmd" ) { $daty = "app_cmd"; } else
								  if ( $daty == "exe" ) { $daty = "app_exe"; } else
								  if ( $daty == "rpm" ) { $daty = "app_rpm"; } else
								  if ( $daty == "acf" ) { $daty = "apple_acf"; } else
								  if ( $daty == "mp3" ) { $daty = "audio_mp3"; } else
								  if ( $daty == "mp4" ) { $daty = "audio_mp4"; } else
								  if ( $daty == "ogg" ) { $daty = "audio_ogg"; } else
								  if ( $daty == "wav" ) { $daty = "audio_wav"; } else
								  if ( $daty == "wma" ) { $daty = "audio_wmv"; } else
								  if ( $daty == "dmg" ) { $daty = "bin_dmg"; } else
								  if ( $daty == "iso" ) { $daty = "bin_iso"; } else
								  if ( $daty == "bas" ) { $daty = "code_bas"; } else
								  if ( $daty == "c" ) { $daty = "code_c"; } else
								  if ( $daty == "cp" ) { $daty = "code_cpp"; } else
								  if ( $daty == "cpp" ) { $daty = "code_cpp"; } else
								  if ( $daty == "h" ) { $daty = "code_h"; } else
								  if ( $daty == "hp" ) { $daty = "code_hpp"; } else
								  if ( $daty == "hpp" ) { $daty = "code_hpp"; } else
								  if ( $daty == "pas" ) { $daty = "code_pas"; } else
								  if ( $daty == "php" ) { $daty = "code_php"; } else
								  if ( $daty == "xhtml" ) { $daty = "code_xhtml"; } else
								  if ( $daty == "xhtm" ) { $daty = "code_xhtml"; } else
								  if ( $daty == "xml" ) { $daty = "code_xml"; } else
								  if ( $daty == "html" ) { $daty = "code_html"; } else
								  if ( $daty == "htm" ) { $daty = "code_html"; } else
								  if ( $daty == "gz" ) { $daty = "compressed_gzip"; } else
								  if ( $daty == "gzip" ) { $daty = "compressed_gzip"; } else
								  if ( $daty == "zip" ) { $daty = "compressed_zip"; } else
								  if ( $daty == "tar" ) { $daty = "compressed_tar"; } else
								  if ( $daty == "rar" ) { $daty = "compressed_rar"; } else
								  if ( $daty == "sit" ) { $daty = "compressed_sit"; } else
								  if ( $daty == "mdb" ) { $daty = "doc_access"; } else
								  if ( $daty == "doc" ) { $daty = "doc_word"; } else
								  if ( $daty == "docx" ) { $daty = "doc_word"; } else
								  if ( $daty == "xls" ) { $daty = "doc_excel"; } else
								  if ( $daty == "xlsx" ) { $daty = "doc_excel"; } else
								  if ( $daty == "ppt" ) { $daty = "doc_powerpoint"; } else
								  if ( $daty == "pptx" ) { $daty = "doc_powerpoint"; } else
								  if ( $daty == "odt" ) { $daty = "doc_writer"; } else
								  if ( $daty == "ods" ) { $daty = "doc_calc"; } else
								  if ( $daty == "odp" ) { $daty = "doc_impress"; } else
								  if ( $daty == "odg" ) { $daty = "doc_draw"; } else
								  if ( $daty == "txt" ) { $daty = "doc_text"; } else
								  if ( $daty == "rtf" ) { $daty = "doc_text"; } else
								  if ( $daty == "log" ) { $daty = "doc_text"; } else
								  if ( $daty == "readme" ) { $daty = "doc_text"; } else
								  if ( $daty == "dll" ) { $daty = "lib_dll"; } else
								  if ( $daty == "dylib" ) { $daty = "lib_dylib"; } else
								  if ( $daty == "so" ) { $daty = "lib_so"; } else
								  if ( $daty == "avi" ) { $daty = "video_avi"; } else
								  if ( $daty == "flv" ) { $daty = "video_flash"; } else
								  if ( $daty == "mpg" ) { $daty = "video_mpg"; } else
								  if ( $daty == "mpeg" ) { $daty = "video_mpg"; } else
								  if ( $daty == "swf" ) { $daty = "video_swf"; } else
								  if ( $daty == "webm" ) { $daty = "video_webm"; } else
								  if ( $daty == "wmv" ) { $daty = "video_wmv"; } else
								  if ( $daty == "mov" ) { $daty = "video_mov"; }
								  // *** //
								  $ckd = "";
								  foreach($arry5 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  // *** //
								  echo '
									<table border = "0" title = "'. utf8_encode(get_file_name($datensat4['datei'])).'"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:16px;" value = "'.$datensat4['datei'].'" onchange = "javascript:link_doc_to_group(102,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td><img border = "0" style = "width:16px;" src = "imgs/fts/'.$daty.'.png" /></td>
										<td width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Dateien zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Dateien, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba dosya sunuabilirsiniz. Sunmak istedi&#287;iniz dosyay&#305; &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				echo '</tr></table></div><br /><br />';

		  }

		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
	function get_content_of_userlink( $idnr, $lang, $elang = "" ) {
			  $query=mysql_query("SELECT * FROM db_5m_users WHERE id = '$idnr' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['nick'] . ', ' . $datensatz['firstname'] . ' ' . $datensatz['lastname'] . ' (' . $datensatz['e_mail'] . ')';
			  }
			  if ( $lang == "de" ) { $aktdoc = "Mitglied"; }
			  elseif($lang=="tr" ) { $aktdoc = "&Uuml;ye"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

				// ******************************************************************** //
				echo '<div style = "width:900px;"><table border = "0" width = "100%"><tr>';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Dokumente"; }
				elseif($lang=="tr" ) { $aktdoc = "Belgeler"; }
				echo '<td valign = "top">
						<div class = "lo_tab" style = "width:280px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry1 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_member_connect WHERE doctype = '0' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry1[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '0' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = $datensat4['doctitle'];
								  if ($lang=="de"){$co="01";}elseif($lang=="tr"){$co="02";}
								  if ( $datensat4["doctitle$co"] != "" ) { $titelo = $datensat4["doctitle$co"]; }
								  $ckd = "";
								  foreach($arry1 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_doc_to_member(0,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td valign = "top" width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Dokumente zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Dokumente, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba belgeler sunuabilirsiniz. Sunmak istedi&#287;iniz belgeyi &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Bildergallerien"; }
				elseif($lang=="tr" ) { $aktdoc = "Resim galerileri"; }
				echo '<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:280px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry2 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_member_connect WHERE doctype = '4' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry2[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_content_docs WHERE doctype = '4' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = $datensat4['doctitle'];
								  if ($lang=="de"){$co="01";}elseif($lang=="tr"){$co="02";}
								  if ( $datensat4["doctitle$co"] != "" ) { $titelo = $datensat4["doctitle$co"]; }
								  $ckd = "";
								  foreach($arry2 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_doc_to_member(4,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td valign = "top" width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Bildergallerien zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Gallerien, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba resim galerileri sunuabilirsiniz. Sunmak istedi&#287;iniz resim galerilerini &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Formulare"; }
				elseif($lang=="tr" ) { $aktdoc = "Formlar"; }
				echo '<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:280px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry3 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_member_connect WHERE doctype = '100' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry3[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_forms ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = $datensat4['doctitle'];
								  if ($lang=="de"){$co="01";}elseif($lang=="tr"){$co="02";}
								  if ( $datensat4["doctitle$co"] != "" ) { $titelo = $datensat4["doctitle$co"]; }
								  $ckd = "";
								  foreach($arry3 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:20px;" onchange = "javascript:link_doc_to_member(100,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td valign = "top" width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Formulare zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Formulare, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba formlar sunuabilirsiniz. Sunmak istedi&#287;iniz formlar&#305; &ccedil;etleyin.'; }						echo '
					  </td>
				';
				// *** //
				echo '</tr></table></div><br /><br />';
				// ******************************************************************** //
				echo '<div style = "width:900px;"><table border = "0" width = "100%"><tr>';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "PDF-Dokumente"; }
				elseif($lang=="tr" ) { $aktdoc = "PDF-Belgeleri"; }
				echo '<td valign = "top">
						<div class = "lo_tab" style = "width:420px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry4 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_member_connect WHERE doctype = '101' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry4[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_pdfs ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo = utf8_encode(geschnitten_(get_file_name($datensat4['datei']),40));
								  $ckd = "";
								  foreach($arry4 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  echo '
									<table border = "0" title = "'. utf8_encode(get_file_name($datensat4['datei'])).'"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:16px;" value = "'.$datensat4['datei'].'" onchange = "javascript:link_doc_to_member(101,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td><img border = "0" style = "width:16px;" src = "imgs/fts/doc_pdf.png" /></td>
										<td width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte PDF-Dokumente zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Dokumente, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba PDF-belgeleri sunuabilirsiniz. Sunmak istedi&#287;iniz belgeyi &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Dateien"; }
				elseif($lang=="tr" ) { $aktdoc = "Dosyalar"; }
				echo '<td valign = "top" style = "padding-left:20px;">
						<div class = "lo_tab" style = "width:420px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
								<div style = "height:200px;overflow:auto;">
								';
								  $arry5 = array();$ari=0;
								  $quer4=mysql_query("SELECT * FROM db_5m_member_connect WHERE doctype = '102' AND gid = '$idnr' ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
									$arry5[$ari] = $datensat4['did'];$ari++;
								  }
								  // *** //
								  $quer4=mysql_query("SELECT * FROM db_5m_files ORDER by id");
								  if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
								  while ( $datensat4 = mysql_fetch_array($quer4) ) {
								  $titelo =  utf8_encode(geschnitten_(get_file_name($datensat4['datei']),40));
								  $daty = strtolower(get_file_type($datensat4['datei']));
								  $dati = $daty;
								  // *** //
								  if ( $daty == "pdf" ) { $daty = "doc_pdf"; } else
								  if ( $daty == "bmp" ) { $daty = "picture_bmp"; } else
								  if ( $daty == "jpg" ) { $daty = "picture_jpg"; } else
								  if ( $daty == "jpeg" ) { $daty = "picture_jpg"; } else
								  if ( $daty == "png" ) { $daty = "picture_png"; } else
								  if ( $daty == "ico" ) { $daty = "picture_ico"; } else
								  if ( $daty == "psd" ) { $daty = "picture_photoshop"; } else
								  if ( $daty == "tif" ) { $daty = "picture_tiff"; } else
								  if ( $daty == "tiff" ) { $daty = "picture_tiff"; } else
								  if ( $daty == "cmd" ) { $daty = "app_cmd"; } else
								  if ( $daty == "exe" ) { $daty = "app_exe"; } else
								  if ( $daty == "rpm" ) { $daty = "app_rpm"; } else
								  if ( $daty == "acf" ) { $daty = "apple_acf"; } else
								  if ( $daty == "mp3" ) { $daty = "audio_mp3"; } else
								  if ( $daty == "mp4" ) { $daty = "audio_mp4"; } else
								  if ( $daty == "ogg" ) { $daty = "audio_ogg"; } else
								  if ( $daty == "wav" ) { $daty = "audio_wav"; } else
								  if ( $daty == "wma" ) { $daty = "audio_wmv"; } else
								  if ( $daty == "dmg" ) { $daty = "bin_dmg"; } else
								  if ( $daty == "iso" ) { $daty = "bin_iso"; } else
								  if ( $daty == "bas" ) { $daty = "code_bas"; } else
								  if ( $daty == "c" ) { $daty = "code_c"; } else
								  if ( $daty == "cp" ) { $daty = "code_cpp"; } else
								  if ( $daty == "cpp" ) { $daty = "code_cpp"; } else
								  if ( $daty == "h" ) { $daty = "code_h"; } else
								  if ( $daty == "hp" ) { $daty = "code_hpp"; } else
								  if ( $daty == "hpp" ) { $daty = "code_hpp"; } else
								  if ( $daty == "pas" ) { $daty = "code_pas"; } else
								  if ( $daty == "php" ) { $daty = "code_php"; } else
								  if ( $daty == "xhtml" ) { $daty = "code_xhtml"; } else
								  if ( $daty == "xhtm" ) { $daty = "code_xhtml"; } else
								  if ( $daty == "xml" ) { $daty = "code_xml"; } else
								  if ( $daty == "html" ) { $daty = "code_html"; } else
								  if ( $daty == "htm" ) { $daty = "code_html"; } else
								  if ( $daty == "gz" ) { $daty = "compressed_gzip"; } else
								  if ( $daty == "gzip" ) { $daty = "compressed_gzip"; } else
								  if ( $daty == "zip" ) { $daty = "compressed_zip"; } else
								  if ( $daty == "tar" ) { $daty = "compressed_tar"; } else
								  if ( $daty == "rar" ) { $daty = "compressed_rar"; } else
								  if ( $daty == "sit" ) { $daty = "compressed_sit"; } else
								  if ( $daty == "mdb" ) { $daty = "doc_access"; } else
								  if ( $daty == "doc" ) { $daty = "doc_word"; } else
								  if ( $daty == "docx" ) { $daty = "doc_word"; } else
								  if ( $daty == "xls" ) { $daty = "doc_excel"; } else
								  if ( $daty == "xlsx" ) { $daty = "doc_excel"; } else
								  if ( $daty == "ppt" ) { $daty = "doc_powerpoint"; } else
								  if ( $daty == "pptx" ) { $daty = "doc_powerpoint"; } else
								  if ( $daty == "odt" ) { $daty = "doc_writer"; } else
								  if ( $daty == "ods" ) { $daty = "doc_calc"; } else
								  if ( $daty == "odp" ) { $daty = "doc_impress"; } else
								  if ( $daty == "odg" ) { $daty = "doc_draw"; } else
								  if ( $daty == "txt" ) { $daty = "doc_text"; } else
								  if ( $daty == "rtf" ) { $daty = "doc_text"; } else
								  if ( $daty == "log" ) { $daty = "doc_text"; } else
								  if ( $daty == "readme" ) { $daty = "doc_text"; } else
								  if ( $daty == "dll" ) { $daty = "lib_dll"; } else
								  if ( $daty == "dylib" ) { $daty = "lib_dylib"; } else
								  if ( $daty == "so" ) { $daty = "lib_so"; } else
								  if ( $daty == "avi" ) { $daty = "video_avi"; } else
								  if ( $daty == "flv" ) { $daty = "video_flash"; } else
								  if ( $daty == "mpg" ) { $daty = "video_mpg"; } else
								  if ( $daty == "mpeg" ) { $daty = "video_mpg"; } else
								  if ( $daty == "swf" ) { $daty = "video_swf"; } else
								  if ( $daty == "webm" ) { $daty = "video_webm"; } else
								  if ( $daty == "wmv" ) { $daty = "video_wmv"; } else
								  if ( $daty == "mov" ) { $daty = "video_mov"; }
								  // *** //
								  $ckd = "";
								  foreach($arry5 as $arit){if($arit==$datensat4['id']){$ckd=" checked ";break;}}
								  // *** //
								  echo '
									<table border = "0" title = "'. utf8_encode(get_file_name($datensat4['datei'])).'"><tr>
										<td valign = "top"><input type = "checkbox" style = "width:16px;" value = "'.$datensat4['datei'].'" onchange = "javascript:link_doc_to_member(102,'.$idnr.','.$datensat4['id'].',this.checked);" '.$ckd.' /></td>
										<td><img border = "0" style = "width:16px;" src = "imgs/fts/'.$daty.'.png" /></td>
										<td width="100%">'.$titelo.'</td>
									</tr></table>
								  ';
								  }
								echo'
								</div>
								</td>
							</tr>
						</table>
						</div><br />
						';
						if ( $lang == "de" ) { echo 'Sie haben nun die M&ouml;glichkeit, bestimmte Dateien zu dieser Gruppe bereitzustellen. Bitte setzen Sie den Haken vor all jene Dateien, welche in diese Gruppe verkn&uuml;pft werden sollen.'; }
						elseif($lang=="tr" ) { echo 'Dilerseniz, bu gruba dosya sunuabilirsiniz. Sunmak istedi&#287;iniz dosyay&#305; &ccedil;etleyin.'; }
						echo '
					  </td>
				';
				// *** //
				echo '</tr></table></div><br /><br />';

		  }
		  
















	function get_content_of_userrights( $idnr, $lang, $elang = "" ) {
			  $quer2=mysql_query("SELECT * FROM db_5m_user_rights_data WHERE uid = '$idnr' ORDER BY id");
			  if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());$urights="";
			  while ( $datensat2 = mysql_fetch_array($quer2) ) { $urights = $datensat2['urights']; }
			  // *** //
			  $query=mysql_query("SELECT * FROM db_5m_users WHERE id = '$idnr' AND ingroup IS NULL ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['nick'] . ', ' . $datensatz['firstname'] . ' ' . $datensatz['lastname'] . ' (' . $datensatz['e_mail'] . ')';
			  }
			  if ( $lang == "de" ) { $aktdoc = "Mitglied"; }
			  elseif($lang=="tr" ) { $aktdoc = "&Uuml;ye"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

				// ******************************************************************** //
				echo '
				<style type = "text/css">
					.admAccessRights {
						padding: 0px;
						margin-left: 1px;
						margin-bottom: 1px;
					}
					.admAccessRights th {
						background:url(\'\') rgb(230,230,230);border:1px solid rgb(210,210,210);padding:6px;
						text-align:left;font-size:12px;font-weight:bold;
					}
					.admAccessRights td {
						background:url(\'\');padding:6px;
						text-align:left;font-size:12px;font-weight:normal;
					}
				</style>
				';
				// *** //
				$idno = $idnr;
				// *** //
				echo '<div style = "width:900px;"><table border = "0" width = "100%"><tr>';
				// *** //
				if ( $lang == "de" ) { $aktdoc = "Module"; }
				elseif($lang=="tr" ) { $aktdoc = "Mod&uuml;ller"; }
				echo '<td valign = "top">
						<div class = "lo_tab" style = "width:750px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th colspan = "2" align = "right" style = "text-align: left;padding: 15px;">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top">
									<table border = "0" class = "admAccessRights" style = "background:url();" width = "100%">
										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Konten";}elseif($lang=="tr"){echo "Hesaplar";};echo'
											</th>
										</tr>
										<tr>
										<td valign = "top" align = "center">
												<table border = "0">
													<tr><td valign = "top"><input type = "checkbox" value = "forml" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);" ';if(strpos($urights,'[forml]')!=false){echo"checked";};echo' /></td>
													<td valign = "top"><img border = "0" src = "imgs/regist.png" style = "width:64px;" /></td></tr>
													<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Kurs-<br />Anmeldungen";}elseif($lang=="tr"){echo "Kurs<br />kay&#305;tlar&#305;";};echo'</td></tr>
												</table>
										</td>
										<td valign = "top" align = "center">
												<table border = "0">
													<tr><td valign = "top"><input type = "checkbox" value = "membr" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[membr]')!=false){echo"checked";};echo' /></td>
													<td valign = "top"><img border = "0" src = "imgs/groupevent.png" /></td></tr>
													<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Mitglieder";}elseif($lang=="tr"){echo "&Uuml;yeler";};echo'</td></tr>
												</table>
										</td>
										<td valign = "top" align = "center">
												<table border = "0">
													<tr><td valign = "top"><input type = "checkbox" value = "conts" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[conts]')!=false){echo"checked";};echo' /></td>
													<td valign = "top"><img border = "0" src = "imgs/contacts.png" /></td></tr>
													<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Kontakt";}elseif($lang=="tr"){echo "&#304;leti&#351;im";};echo'</td></tr>
												</table>
										</td>
										<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Inhalte";}elseif($lang=="tr"){echo "&#304;&ccedil;erikler";};echo'
											</th>
										</tr>
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "ehome" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[ehome]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/document.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Startseite";}elseif($lang=="tr"){echo "Anasayfa";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "eagb" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[eagb]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/document.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "AGB";}elseif($lang=="tr"){echo "Kullan&#305;c&#305;<br />s&ouml;zle&#351;mesi";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "eabus" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[eabus]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/document.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "&Uuml;ber&nbsp;uns";}elseif($lang=="tr"){echo "Hakk&#305;m&#305;zda";} ;echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "eimpr" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[eimpr]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/document.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Impressum";}elseif($lang=="tr"){echo "Kurumsal<br />bilgiler";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "edocs" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[edocs]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Dokumente";}elseif($lang=="tr"){echo "Belgeler";};echo'</td></tr>
													</table>
											</td>			
										</tr>
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "enews" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[enews]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Neuigkeiten";}elseif($lang=="tr"){echo "Duyurular";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "eacts" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[eacts]')!=false){echo"checked";};echo'/></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Aktivit&auml;ten";}elseif($lang=="tr"){echo "Etkinlikler";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "egall" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[egall]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Bilder-<br />gallerien";}elseif($lang=="tr"){echo "Resim<br />galerileri";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "epops" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[epops]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Meldungen";}elseif($lang=="tr"){echo "Reklam<br />Duyrular&#305;";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "ecamp" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[ecamp]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Kampagnen";}elseif($lang=="tr"){echo "Kampanyalar";};echo'</td></tr>
													</table>
											</td>			
										</tr>
										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Mitgliederverwaltung";}elseif($lang=="tr"){echo "&Uuml;yelerinizi y&ouml;netin";};echo'
											</th>
										</tr>
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "mem1" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[mem1]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Gruppen";}elseif($lang=="tr"){echo "Gruplar";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "mem2" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[mem2]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Gruppen-<br />verwaltung";}elseif($lang=="tr"){echo "Gruplar&#305;-<br />organize&nbsp;edin";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "mem3" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[mem3]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ordner.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Individuelle<br />Angebote";}elseif($lang=="tr"){echo "Ki&#351;iye &ouml;zel<br />sunular";};echo'</td></tr>
													</table>
											</td>
											<td colspan="2"></td>
										</tr>

										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Hochladen";}elseif($lang=="tr"){echo "Y&uuml;kle";};echo'
											</th>
										</tr>
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "upic" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[upic]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ark.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "Bilder";}elseif($lang=="tr"){echo "Resimler";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "uico" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[uico]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ark.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "Icons";}elseif($lang=="tr"){echo "Simgeler";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "updf" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[updf]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ark.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "PDF-Dokumente";}elseif($lang=="tr"){echo "PDF-Belgeleri";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "ufil" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[ufil]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/ark.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "Dateien";}elseif($lang=="tr"){echo "Dosyalar";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
												&nbsp;
											</td>
										</tr>
										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Formulare";}elseif($lang=="tr"){echo "Formlar";};echo'
											</th>
										</tr>		
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "efrm" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[efrm]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/document.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "Formular<br />Editor";}elseif($lang=="tr"){echo "Form<br />Edit&ouml;r&uuml;";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "mfrm" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[mfrm]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/forms.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "Formulare<br />verwalten";}elseif($lang=="tr"){echo "Formlar&#305;<br />organize&nbsp;et";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "ifrm" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[ifrm]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/forms.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">'; if($lang=="de" ){echo "Eingegangene<br />Formulare";}elseif($lang=="tr"){echo "Giren D&ouml;k&uuml;mler";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "sfrm" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[sfrm]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/forms.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top">';if($lang=="de" ){echo "Statistik";}elseif($lang=="tr"){echo "&#304:statistik";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top">
												&nbsp;
											</td>
										</tr>
										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Einstellungen";}elseif($lang=="tr"){echo "Ayarlar";};echo'
											</th>
										</tr>
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "meta" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[meta]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/document.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Meta-Daten";}elseif($lang=="tr"){echo "Meta-Bilgileri";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "emenu" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[emenu]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/setup.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Men&uuml;leiste";}elseif($lang=="tr"){echo "M&ouml;n&uuml;-&Ccedil;ubu&#287;u";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "advr" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[advr]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/setup.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Werbung";}elseif($lang=="tr"){echo "Reklam";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "nlet" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[nlet]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/setup.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Newsletter";}elseif($lang=="tr"){echo "Mesaj Servisi";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = >
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "mail" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[mail]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/setup.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "E-Mail-Dienst";}elseif($lang=="tr"){echo "E-Posta Servisi";};echo'</td></tr>
													</table>
											</td>
										</tr>
										<tr>
											<th colspan = "5" style = "background:url();border-bottom:1px dashed rgb(200,200,200);">
												';if($lang=="de" ){echo "Werkzeuge";}elseif($lang=="tr"){echo "Ara&ccedil;lar";};echo'
											</th>
										</tr>
										<tr>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "mngr" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[mngr]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/toolfileman.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Explorer";}elseif($lang=="tr"){echo "Gezgin";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center">
													<table border = "0">
														<tr><td valign = "top"><input type = "checkbox" value = "dloa" onchange = "javascript:set_admin_right('.$idno.',this.value,this.checked);"';if(strpos($urights,'[dloa]')!=false){echo"checked";};echo' /></td>
														<td valign = "top"><img border = "0" src = "imgs/tooldownload.png" style = "width:64px;" /></td></tr>
														<tr><td></td><td valign = "top" align="center">';if($lang=="de" ){echo "Download";}elseif($lang=="tr"){echo "&#304;ndir";};echo'</td></tr>
													</table>
											</td>
											<td valign = "top" align = "center" colspan = "3">
												&nbsp;
											</td>
										</tr>

									</table>
								</td>
							</tr>
							<tr>
								<th colspan = "2" align = "right" style = "text-align: right;padding: 8px;">
									<input type = "button" value = "';if($lang=="de"){echo'Keine';}elseif($lang=="tr"){echo'Hi&ccedil;biri';};echo'" onclick = "javascript:remove_admin_rights('.$idno.');" />
									<input type = "button" value = "';if($lang=="de"){echo'Alle';}elseif($lang=="tr"){echo'T&uuml;m&uuml;';};echo'" onclick = "javascript:set_all_admin_rights('.$idno.');" />
								</th>
							</tr>
						</table>
						</div>
					  </td>
				';
				// *** //
				echo '<td valign = "top" style = "padding-left:20px;">
						';
						if ( $lang == "de" ) { echo 'Bitte machen Sie einen Haken vor all den Modulen, die Sie diesem Mitglied zur Administration der Webseite bereitstellen m&ouml;chten.<br /><br />Einige Module sind nur f&uuml;r den echten Administrator, also f&uuml;r Sie vorbehalten und k&ouml;nnen anderen Adminsitratoren aus Sicherheitsgr&uuml;nden nicht zug&auml;nglich gemacht werden.'; }
						elseif($lang=="tr" ) { echo 'L&uuml;tfen bu &uuml;yeye y&ouml;netimsel i&#351;lemler yapabilmesi i&ccedil;in &ouml;ng&ouml;rd&uuml;&#287;&uuml;n&uuml;z mod&uuml;lleri &ccedil;etleyin.<br /><br />Baz&#305; mod&uuml;ller sadece ger&ccedil;ek y&ouml;neticiye, yani size &ouml;zeldir ve bu nedenden dolay&#305; ba&#351;ka y&ouml;neticilere verilemez.'; }
						echo '
					  </td>
				';
				// *** //
				echo '</tr></table></div><br /><br /><div align = "left" style = "text-align:left;">';
						if ( $lang == "de" ) { 
						?>
						Ein Mitglied wird zum beschr&auml;nkten Administrator, wenn er mindestens eines der oben aufgef&uuml;hrten Module bereitgestellt 
						bekommt.<br /><br />
						Sobald kein Modul mehr auf Ihn verkn&uump;ft ist, verliert der Mitglied automatisch seine administrative Funktion und ist ab dann 
						als normaler Mitglied im System unterwegs.
						<br /><br />
						Geh&ouml;rt der Mitglied jedoch zu einer Gruppe, so wird er unter den Mitgliedern auf dieser Seite nicht zu sehen sein, da Mitglieder 
						in Gruppen keine administrative Funktionen haben d&uuml;rfen. Das Gleiche gilt auch umgekehrt.<br /><br />
						Aus Sicherheitsr&uuml;nden werden normale Mitglieder, Blogger (Blog-Administratoren) und (beschr&auml;nkte) Administratoren 
						von einander unterschieden und streng unterschiedlich behandelt, damit der eine Mitglied mit seinem Konto keinen Schaden im System 
						verursachen kann.
						<br /><br />
						Wenn eine nat&uuml;rliche Personen sowohl in einer Gruppe unterkommen m&ouml;chte und gleichzeitig als beschr&auml;kter Administrator 
						t&auml;tig werden m&ouml;chte, so muss diese Person zwei separate Mitgliedskonten anlegen, wobei der eine als Administrator und der 
						andere als normaler Mitglied eingerichtet werden kann.
						<?php
						}
						elseif($lang=="tr" ) { 
						?>
						Bir &uuml;yenin s&#305;n&#305;rl&#305; y&ouml;netici olabilmesi i&ccedil;in, yukardaki mod&uuml;llerin en az birisi ona 
						sunulmas&#305; laz&#305;m.
						<br /><br />
						E&#287;er &uuml;yenin hi&ccedil;bir mod&uuml;le ba&#287;lant&#305;s&#305; yok ise veya bu hak ondan geri al&#305;n&#305;rsa, 
						otomatik olarak y&ouml;netim kadrosundan ihra&ccedil; edilir ve sistemde normal &uuml;ye olarak varl&#305;&#287;&#305;n&#305; 
						s&uuml;rd&uuml;r&uuml;r.
						<br /><br />
						Fakat &uuml;ye bir gruba tabi ise, o halde bu sayfadaki &uuml;yeler aras&#305;nda bulunamaz. &Ccedil;&uuml;nk&uuml; 
						gruplarda bulunan &uuml;yelerin y&ouml;netim kadrosuna girme haklar&#305; yoktur. Ayn&#305; kural ters y&ouml;nede i&#351;lemektedir.
						<br /><br />
						G&uuml;venlik a&ccedil;&#305;s&#305;ndan normal &uuml;yeler, Blog&ccedil;ular (Blog-Y&ouml;neticileri) ve 
						(s&#305;n&#305;rl&#305;) y&ouml;neticiler kesin olarak farkl&#305; de&#287;erlendirilir ve birbirleriyle bir alakalar&#305; 
						yoktur. B&ouml;ylece bir &uuml;yenin kasten veya bilemeden sisteme zarar vermesi &ouml;nlenir.
						<br /><br />
						&#350;ayet bir ki&#351;i hep bir gruba tabi olmak istiyorsa ve ayn&#305; zamanda y&ouml;netici kimli&#287;iylede 
						sistemde bulunmak istiyorsa, o halde bu &#351;ah&#305;s iki adet hesap a&ccedil;mas&#305; gerekiyor. Birisini y&ouml;netici 
						olarak, di&#287;eride normal &uuml;ye olarak.
						<?php
						}
						echo'</div>';

		  }





		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  


			
			
			

		  function get_content_of_gallery( $idnr, $lang, $elang = "" ) {
			  $dtyp = 4;
			  /* ------------------ LOAD THE CONTENT OF A SPECIFIC CONTENT ------------------- */
			  $query=mysql_query("SELECT * FROM db_5m_content_docs WHERE id = '$idnr' AND doctype = '4' ORDER BY id");
			  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			  while ( $datensatz = mysql_fetch_array($query) ) {
				$pr = $datensatz['doctitle'];
				$m1 = $datensatz['docmeta01']; $m1 = ereg_replace( '%@1', "'", $m1 );
				$m2 = $datensatz['docmeta02']; $m2 = ereg_replace( '%@1', "'", $m2 );
				$na = $datensatz['docnavi'];
				$sm = $datensatz['searchflag'];
				$gs = $datensatz['docgallery_type'];
				$gz = $datensatz['docgallery_zoom'];
				$gc = $datensatz['docgallery_size_on_click'];
			  }
			  // *** //
			  if ( $sm != 0 ) { $sm = " checked "; } else { $sm = ""; }
			  // *** //
			  if ( $lang == "de" ) { $aktdoc = "Aktuelles Dokument"; }
			  elseif($lang=="tr" ) { $aktdoc = "A&ccedil;&#305;k Belge"; }
			  echo '
			  <div class = "lo_tab">
				<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
					<tr>
						<th colspan = "4" align = "right" style = "text-align: left;padding:15px;">
							<small><small>'.$aktdoc.':</small></small> <span style = "color:rgb(150,30,45);">'.$pr.'</span>
						</th>
					</tr>
				</table>
				</div>
				<br /><br />
			';

			
			
			
			  if ( $lang == "de" ) { $aktdoc = "Hauptdokument"; }
			  elseif($lang=="tr" ) { $aktdoc = "Ana belge"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr>
				<td valign = "top" style = "padding-right:20px;">
						<div class = "lo_tab" style = "width:340px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "2">
									'.$aktdoc.'
								</th>
							</tr>
							<tr><td colspan = "2">
';
			  if ( $lang == "de" ) { $btntx = "Bearbeiten"; echo '
			  Bitte klicken Sie auf die Schaltfl&auml;che <b>Bearbeiten</b> um das Dokument bearbeiten zu k&ouml;nnen.
			  '; }
			  elseif($lang=="tr" ) { $btntx = "D&uuml;zenle"; echo '
			  Belgeyi d&uuml;zenlemek i&ccedil;in l&uuml;tfen <b>D&uuml;zenle</b> butonuna t&#305;klat&#305;n.'; }
echo '
							</td>
							</tr><tr>
								<td valign = "top"><input type = "checkbox" name = "seamotfl" id = "seamotfl" onclick = "javascript:svr.Return(\'search_motor_flag\','.$idnr.',this.checked);" '.$sm.' /></td>
								<td>
';
			  if ( $lang == "de" ) { echo '
			  Normalerweise wird jedes Dokument vom internen Suchmotor automatisch durchsucht und wenn die Suche zutrifft, dessen Inhalt unter
			  den Suchergebnissen aufgef&uuml;hrt. Soll das Dokument vom Suchmotor ignoriert werden?
			  '; }
			  elseif($lang=="tr" ) { echo '
			  Normalde her belge sayfan&#305;n kendi arama motoru taraf&#305;ndan incelenir ve aranan kritere uygunsa, arama sonu&ccedil;lar&#305;nda 
			  yer al&#305;r. Bu belge arama motorundan gizlensin mi?
			  '; }
echo '
								</td>
							</tr>
							<tr>
								<th align = "right" colspan = "2"><div align = "right"> <input type = "button" value = "'.$btntx.'"
								onclick = "javascript:location.href=\'index.php?page=egall&lang='.$lang.'&ilng='.$elang.'&todo=editmd&id='.$idnr.'\'"
								style = "font-weight: bold; padding: 5px;" /></div> </th>
							</tr>
						</table>
						</div>
				</td><td valign = "top">';
			  if ( $lang == "de" ) {
			  echo '
			  Die Gallerie kann optional einen Einf&uuml;hrungstext haben, in der der Inhalt der Gallerie beschrieben wird. Zum Beispiel 
			  wann die Bilder aufgenommen wurden. Das kann ein Festival gewesen sein, oder eine Gesch&auml;ftsausflug, ein Ferienalbum oder 
			  etwas ganz anderes.<br /><br />
			  Der Einf&uuml;hrungstext ist das Hauptdokument zur Gallerie und kann leer gelassen werden.
			  ';
			  }
			  elseif($lang=="tr" ) {
			  echo '
			  Her galeri isterseniz bir giri&#351; metnine sahip olabilir. Bu metinde galerinin i&ccedil;eri&#287;ini anlatabilirsiniz. 
			  Mesela resimlerin ne zaman &ccedil;ekildi&#287;ini. Mesela resimler bir festivalden, bir i&#351; gezisinden, bir 
			  tatil alb&uuml;m&uuml;nden veya bamba&#351;ka bir yerden olabilir.<br /><br />
			  Giri&#351; metni ana belgedir ve dilerseniz bo&#351; b&#305;rakabilirsiniz.
			  ';
			  }
		echo '
				</td></td><td valign = "top">&nbsp;';
echo '
				</td></tr></table>
				</div>
				<br /><br />
				';




			$galt = array();
			$galt[0] = ""; $galt[1] = ""; $galt[2] = ""; $galt[3] = ""; $galt[4] = ""; $galt[5] = "";
			$galt[$gs] = " checked ";

			$galz = array();
			$galz[0] = ""; $galz[1] = ""; $galz[2] = "";
			$galz[$gz] = " checked ";

			$galc = array();
			$galc[0] = ""; $galc[1] = ""; $galz[c] = "";
			$galc[$gc] = " checked ";

			  if ( $lang == "de" ) { $aktdoc = "Subsystem"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "Altyap&#305; sistemi"; $btntx = "Kaydet"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr><td valign = "top" style = "padding-right:20px;">
						<div class = "lo_tab" style = "width:560px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "6">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td valign = "top"><input type = "radio" name = "typoca" style = "width:20px;" onchange = "gall_subsys('.$idnr.',0);" '.$galt[0].' /></td>
								<td valign = "top" width = "50%"><img border = "0" src = "imgs/gallery/gall01.png" /></td>
								<td valign = "top"><input type = "radio" name = "typoca" style = "width:20px;" onchange = "gall_subsys('.$idnr.',1);" '.$galt[1].' /></td>
								<td valign = "top" width = "50%"><img border = "0" src = "imgs/gallery/gall02.png" /></td>
								<td valign = "top"><input type = "radio" name = "typoca" style = "width:20px;" onchange = "gall_subsys('.$idnr.',2);" '.$galt[2].' /></td>
								<td valign = "top" width = "50%"><img border = "0" src = "imgs/gallery/gall03.png" /></td>
							</tr><tr>
								<td valign = "top"><input type = "radio" name = "typoca" style = "width:20px;" onchange = "gall_subsys('.$idnr.',3);" '.$galt[3].' /></td>
								<td valign = "top" width = "50%"><img border = "0" src = "imgs/gallery/gall04.png" /></td>
								<td valign = "top"><input type = "radio" name = "typoca" style = "width:20px;" onchange = "gall_subsys('.$idnr.',4);" '.$galt[4].' /></td>
								<td valign = "top" width = "50%"><img border = "0" src = "imgs/gallery/gall05.png" /></td>
								<td valign = "top"><input type = "radio" name = "typoca" style = "width:20px;" onchange = "gall_subsys('.$idnr.',5);" '.$galt[5].' /></td>
								<td valign = "top" width = "50%"><img border = "0" src = "imgs/gallery/gall06.png" /></td>
							</td>';
			  if ( $lang == "de" ) { $aktdoc = "Zoom-Effekt"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "B&uuml;y&uuml;te&ccedil; efekti"; $btntx = "Kaydet"; }
echo '
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "6">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td><input type = "radio" name = "zoomef" style = "width:20px;" onchange = "gall_zoomef('.$idnr.',0);" '.$galz[0].' /></td>
								<td style = "padding-right:10px;" colspan = "4">';
								if     ( $lang == "de" ) { echo 'Keine'; }
								elseif ( $lang == "tr" ) { echo 'Devred&#305;&#351;&#305;'; }
								echo '</td></tr>
							<tr>
								<td><input type = "radio" name = "zoomef" style = "width:20px;" onchange = "gall_zoomef('.$idnr.',1);" '.$galz[1].' /></td>
								<td style = "padding-right:10px;" colspan = "4">';
								if     ( $lang == "de" ) { echo 'Normal'; }
								elseif ( $lang == "tr" ) { echo 'Normal'; }
								echo '</td></tr>
							<tr>
									<td><input type = "radio" name = "zoomef" style = "width:20px;" onchange = "gall_zoomef('.$idnr.',2);" '.$galz[2].' /></td>
								<td style = "padding-right:10px;" colspan = "4">';
								if     ( $lang == "de" ) { echo 'Im separaten Kasten auf der rechten Seite'; }
								elseif ( $lang == "tr" ) { echo 'Sa&#287; tarafta ayr&#305; bir kutuda'; }
								echo '</td></tr>';
			  if ( $lang == "de" ) { $aktdoc = "Aktion"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "Aksiyon"; $btntx = "Kaydet"; }
echo '
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "6">
									'.$aktdoc.'
								</th>
							</tr>
								<tr>
								<td><input type = "radio" name = "clickef" style = "width:20px;" onchange = "gall_action('.$idnr.',0);" '.$galc[0].' /></td>
								<td style = "padding-right:10px;" colspan = "4">';
								if     ( $lang == "de" ) { echo 'Keine'; }
								elseif ( $lang == "tr" ) { echo 'Devred&#305;&#351;&#305;'; }
								echo '</td>
							</td>
								<tr>
								<td><input type = "radio" name = "clickef" style = "width:20px;" onchange = "gall_action('.$idnr.',1);" '.$galc[1].' /></td>
								<td style = "padding-right:10px;" colspan = "4">';
								if     ( $lang == "de" ) { echo 'Beim draufklicken Bild vergr&ouml;&szlig;ern'; }
								elseif ( $lang == "tr" ) { echo '&Uuml;zerine t&#305;klat&#305;nca resmi b&uuml;y&uuml;lt'; }
								echo '</td>
							</td>
								<tr>
								<td><input type = "radio" name = "clickef" style = "width:20px;" onchange = "gall_action('.$idnr.',2);" '.$galc[2].' /></td>
								<td style = "padding-right:10px;" colspan = "4">';
								if     ( $lang == "de" ) { echo 'Wenn der Mauszeiger &uuml;ber das Bild liegt, dann das Bild vergr&ouml;&szlig;ern'; }
								elseif ( $lang == "tr" ) { echo 'E&#287;er fare resmin &uuml;zerine gelirse, o zaman resmi b&uuml;y&uuml;lt'; }
								echo '</td>
							</td>

						</table>
						</div>
				</td><td valign = "top" width = "100%">
			';
			  if ( $lang == "de" ) { echo '
			  Das Subsystem legt fest, wie die Gallerie dargestellt wird. Sie haben 6 verschiedene Gallerie-Subsysteme, die Sie verwenden 
			  k&ouml;nnen.<br /><br />
			  Die zweite Aktionstyp <b>Mauszeiger &uuml;ber Bild</b> vertr&auml;gt sich nicht mit dem Zoom-Effekt. In diesem Fall bitte keinen 
			  Zoom-Effekt festlegen.<br /><br />Jede andere Kombination ist problemlos m&ouml;glich.
			  '; }
			  elseif($lang=="tr" ) { echo '
			  Altyap&#305; sistemi galerini nas&#305;l ekrana yans&#305;t&#305;laca&#287;&#305;n&#305; belirtir. Alt&#305; adet altyap&#305; sistemi 
			  se&ccedil;ene&#287;iniz mevcut.<br /><br />
			  &#304;kinci aksiyon t&uuml;r&uuml; <b>Fare resmin &uuml;zerine gelince</b> B&uuml;y&uuml;te&ccedil; efektiyle uyumlu de&#287;ildir. Bu durumda B&uuml;y&uuml;te&ccedil; efektini 
			  devred&#305;&#351;&#305; b&#305;rak&#305;n.<br /><br />
			  Di&#287;er kombinasyonlarda sorun yok.
			  '; }
			echo '
				</td></tr></table>
				</div>
				<br /><br />
				';

				




				
			  if ( $lang == "de" ) { $aktdoc = "Meta-Daten f&uuml;r das Dokument"; $btntx = "Speichern"; }
			  elseif($lang=="tr" ) { $aktdoc = "Bu belge i&ccedil;in meta-bilgileri"; $btntx = "Kaydet"; }
			echo '
				<div style = "width:900px;">
				<table border = "0"><tr><td valign = "top" width = "100%" style = "padding-right:20px;">
			';
			  if ( $lang == "de" ) { echo '
			  Damit dieses Dokument &uuml;ber Google, Bing oder Yahoo direkt aufgefunden werden kann, 
			  lohnt es sich Meta-Daten zu diesem Dokument einzutragen.<br /><br />Meta-Daten sind einfach nur W&ouml;rter, 
			  die von Suchmaschinen genutzt werden, um Webseiten bzw. Inhalte aus Webseiten zu finden.
			  '; }
			  elseif($lang=="tr" ) { echo '
			  Bu belge Google, Bing veya Yahoo &uuml;zerinden hemen bulunabilmesi i&ccedil;in, bu belgeye meta-bilgileri 
			  eklemeniz tavsiye olunur.<br /><br />Meta-Bilgileri kelimelerden olu&#351;ur ve arama motorlar&#305; taraf&#305;ndan 
			  internet sayfalar&#305;n&#305; veya i&ccedil;eriklerini bulunmaya yard&#305;mc&#305; olur.
			  '; }
			echo '
				</td><td valign = "top">
						<div class = "lo_tab" style = "width:450px;">
						<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
							<tr>
								<th align = "right" style = "text-align: left;padding: 15px;" colspan = "2">
									'.$aktdoc.'
								</th>
							</tr>
							<tr>
								<td><img border = "0" src = "imgs/lang/DE.png" /></td>
								<td><input type = "text" value = "'.$m1.'" id = "metaDE" style = "width:360px;" /></td>
							</td>
							<tr>
								<td><img border = "0" src = "imgs/lang/TR.png" /></td>
								<td><input type = "text" value = "'.$m2.'" id = "metaTR" style = "width:360px;" /></td>
							</td>
							<tr>
								<th colspan = "2" align = "right"><div align = "right"> <input type = "button" value = "'.$btntx.'"
								onclick = "javascript:svr.Return(\'meta_for_doc\','.$idnr.',modifyUTFex($_id(\'metaDE\').value),modifyUTFex($_id(\'metaTR\').value));"
								style = "font-weight: bold; padding: 5px;" /></div> </th>
							</tr>
						</table>
						</div>
				</td></tr></table>
				</div>
				<br /><br />
				';

				
				
				
				
			echo '
			<table border = "0" cellspacing = "0" cellpadding = "0"><tr>
			<td valign = "top" style = "padding-left:40px;padding-right:40px;text-align: justify; font-family: Arial, Helvetica; font-size: 16px; font-weight: normal;line-height:24px;">
		';
		if ( $lang == "de" ) { echo '
			<b style = "color:rgb(130,30,30);">Hauptdokument l&ouml;schen?</b> <br /><br />
			Um die Gallerie <span style = "background:url(\'imgs/btn_orange.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><b style = "color:rgb(70,70,210);">'.$pr.'</b></span> 
			vollst&auml;ndig zu l&ouml;schen, klicken Sie bitte 
			<span style = "background:url(\'imgs/btn_lila.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><a href = "javascript:remove_content('.$idnr.');">hier</a></span>.
			</td></tr></table>
		'; } elseif ( $lang == "tr" ) { echo '
			<b style = "color:rgb(130,30,30);">Ana belge silinsin mi?</b> <br /><br />
			<span style = "background:url(\'imgs/btn_orange.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><b style = "color:rgb(70,70,210);">'.$pr.'</b></span> 
			galerisini sunucudan kald&#305;rmak istiyorsan&#305;z, o halde 
			<span style = "background:url(\'imgs/btn_lila.png\') repeat-x center center;
			border:1px solid rgb(180,180,180);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;
			box-shadow:1px 1px 2px rgb(220,220,220);-moz-box-shadow:1px 1px 2px rgb(220,220,220);-webkit-box-shadow:1px 1px 2px rgb(220,220,220);
			-khtml-box-shadow:1px 1px 2px rgb(220,220,220);padding:3px;"><a href = "javascript:remove_content('.$idnr.');">&#351;uraya</a></span> 
			t&#305;klat&#305;n.
			</td></tr></table>
		';}
			if ( $lang == "de" ) { $aktdoc = "Bilder in die Gallerie <span style = \"color:rgb(150,30,45);\">$pr</span> verkn&uuml;pfen"; }
			elseif($lang=="tr" ) { $aktdoc = "<span style = \"color:rgb(150,30,45);\">$pr</span> galerisine resim ba&#287;lay&#305;n"; }
		echo '

			<br /><br />

			<div class = "lo_tab">
			<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
				<tr>
					<th colspan = "4" align = "right" style = "border-bottom:1px solid rgb(150,150,150); text-align: left; padding: 15px;">
						'.$aktdoc.'
					</th>
				</tr>
				';

				  $query=mysql_query("SELECT * FROM aj_images ORDER BY id");
				  if(!$query) die("Fehler bei der Abfrage: ".mysql_error());

				  $zr = 0; $idn = 0;
				  
				  while ( $datensatz = mysql_fetch_array($query) ) {
				  
						if ( $zr == 0 ) { echo '<tr>'; }

						  $quer2=mysql_query("SELECT * FROM db_5m_content_docs_gallery WHERE picid = '".$datensatz['id']."' AND gid = '$idnr' ORDER BY id");
						  if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error()); $hooked = ""; $text1 = ""; $text2 = "";
						  while ( $datensat2 = mysql_fetch_array($quer2) ) {
							$hooked = " checked ";
							$text1 = $datensat2['galtitle'];
							$text2 = $datensat2['galinfo'];
						  }

						echo '
						<td style = "border-top: 1px solid rgb( 220, 220, 220 ); " valign = "top">
								<center>
								<table border = "0" cellspacing = "0" cellpadding = "0" class = "gallitem" align = "center">
									<tr>
								<td valign = "top"><input type = "checkbox" id = "galpic'.$idn.'" onchange = "javascript:pick_image_to_gallery('.$idnr.','.$datensatz['id'].',\''.$datensatz['datei'].'\',this.checked);" '.$hooked.' /></td>
								<td valign = "top"><center>
								<a href = "javascript:gotoHoverGalPic('.$idn.');">
									<img border = "0" src = "'.$datensatz['mintr'].'" style = "width:160px;" />
								</a>
								<div id = "galpiceditinfo'.$idn.'" style = "display:none;position:absolute;margin-left:0px;width:300px;-khtml-border-radius: 10px; padding:2px;
								border:1px solid rgb(150,150,150);background-color:#FFFFFF; border-radius: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px;
								box-shadow:2px 2px 4px rgb(180,180,180);-moz-box-shadow:2px 2px 4px rgb(180,180,180);-webkit-box-shadow:2px 2px 4px rgb(180,180,180);
								-khtml-box-shadow:2px 2px 4px rgb(180,180,180);">
								<div style = "position:absolute;margin-left:290px;margin-top:-20px;
								border-radius: 20px; -moz-border-radius: 20px; -webkit-border-radius: 20px;
								border:1px solid rgb(200,200,200);background:url(\'imgs/quit.png\') no-repeat center center #ffffff;
								box-shadow:2px 2px 4px rgb(180,180,180);-moz-box-shadow:2px 2px 4px rgb(180,180,180);-webkit-box-shadow:2px 2px 4px rgb(180,180,180);
								cursor:pointer;padding:5px; width: 20px; height: 20px;-khtml-box-shadow:2px 2px 4px rgb(180,180,180);
								" onclick = "javascript:gotoHoverGalPic(-1);"></div>
									<table border = "0" class = "lo_tab" width = "100%">
										<tr>
											<td>
											';
											if     ( $lang == "de" ) { echo "Titel:"; }
											elseif ( $lang == "tr" ) { echo "Ba&#351;l&#305;k:"; }
											echo '
											</td>
											<td width = "100%">
												<input type = "text" id = "edtitl'.$idn.'" style = "width:200px;" value = "'.$text1.'" />
											</td>
										</tr>
										<tr>
											<td>
											';
											if     ( $lang == "de" ) { echo "Info:"; }
											elseif ( $lang == "tr" ) { echo "Bilgi:"; }
											echo '
											</td>
											<td width = "100%">
												<input type = "text" id = "edinfo'.$idn.'" style = "width:200px;" value = "'.$text2.'" />
											</td>
										</tr>
										<tr>
											<th width = "100%" colspan = "2"><div align = "right">
												<input type = "text" id = "edtitle'.$idn.'" value = "';
												if     ( $lang == "de" ) { echo "Speichern"; }
												elseif ( $lang == "tr" ) { echo "Kaydet"; }
												echo '" style = "text-align:center;width:70px;" 
												onclick = "javascript:link_image_info_to_gallery('.$idnr.','.$idn.','.$datensatz['id'].',\''.$datensatz['datei'].'\');" /></div>
											</th>
										</tr>
									</table>
								</div>
								</center>
								</td></tr></table>
								</center>
							</td>';

						$zr++; $idn++;

						if ( $zr == 4 ) { echo '</tr>'; $zr = 0; }

				  }
				  
				  switch ( $zr ) {
					case 1 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 2 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> <td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
					case 3 : echo '<td style = "border-top: 1px solid rgb( 220, 220, 220 );">&nbsp;</td> </tr>'; break;
				  }

				  $idn++;

				echo '
			</table></div>
			';
		  }

		  function link_doc_to_member( $flag, $mid, $did, $dtp ) {
				if ( $flag == 1 ) {
					$query=mysql_query("SELECT * FROM db_5m_member_connect 
					WHERE 
 					gid = '$mid' AND did = '$did' AND doctype = '$dtp'		
					ORDER BY id");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					$avai = 0;
					while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
					if ( $avai == 0 ) {
						$query=mysql_query("SELECT * FROM db_5m_member_connect 
						WHERE gid IS NULL AND did IS NULL AND doctype IS NULL LIMIT 1");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						$emid = -1;
						while ( $datensatz = mysql_fetch_array($query) ) {$emid = $datensatz['id'];}
						if ( $emid == -1 ) {
							$query=mysql_query("
								INSERT INTO db_5m_member_connect 
								( gid, did, doctype ) VALUES ( '$mid', '$did', '$dtp' )
							");if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						} else {
							$query=mysql_query("
								UPDATE db_5m_member_connect SET 
								gid = '$mid', did = '$did', doctype = '$dtp' 
								WHERE id = '$emid'
							");if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						}
					}
				} elseif ( $flag == 0 ) {
					$query=mysql_query("UPDATE db_5m_member_connect SET 
					gid = NULL, did = NULL, doctype = NULL
					WHERE 
 					gid = '$mid' AND did = '$did' AND doctype = '$dtp'		
					ORDER BY id");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function link_doc_to_group( $flag, $gid, $did, $dtp ) {
				if ( $flag == 1 ) {
					$query=mysql_query("SELECT * FROM db_5m_group_connect 
					WHERE 
 					gid = '$gid' AND did = '$did' AND doctype = '$dtp'		
					ORDER BY id");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					$avai = 0;
					while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
					if ( $avai == 0 ) {
						$query=mysql_query("SELECT * FROM db_5m_group_connect 
						WHERE gid IS NULL AND did IS NULL AND doctype IS NULL LIMIT 1");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						$emid = -1;
						while ( $datensatz = mysql_fetch_array($query) ) {$emid = $datensatz['id'];}
						if ( $emid == -1 ) {
							$query=mysql_query("
								INSERT INTO db_5m_group_connect 
								( gid, did, doctype ) VALUES ( '$gid', '$did', '$dtp' )
							");if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						} else {
							$query=mysql_query("
								UPDATE db_5m_group_connect SET 
								gid = '$gid', did = '$did', doctype = '$dtp' 
								WHERE id = '$emid'
							");if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						}
					}
				} elseif ( $flag == 0 ) {
					$query=mysql_query("UPDATE db_5m_group_connect SET 
					gid = NULL, did = NULL, doctype = NULL
					WHERE 
 					gid = '$gid' AND did = '$did' AND doctype = '$dtp'		
					ORDER BY id");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function remove_admin_rights( $idno ) {
				$query=mysql_query("UPDATE db_5m_user_rights_data SET urights = NULL, uid = NULL WHERE uid = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$query=mysql_query("UPDATE db_5m_users SET region = '0' WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function set_all_admin_rights( $idno ) {
				$query=mysql_query("SELECT * FROM db_5m_user_rights_data WHERE uid = '$idno' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai != 0 ) {
					$query=mysql_query("UPDATE db_5m_user_rights_data SET 
					urights = ' [forml] [membr] [conts] [ehome] [eagb] [eabus] [eimpr] [edocs] [enews] [eacts] [egall] [epops] [ecamp] [upic] [uico] [updf] [ufil] [mem1] [mem2] [mem3] [efrm] [ifrm] [mfrm] [sfrm] [meta] [advr] [nlet] [mail] [emenu] [mngr] [dloa] ' 
					WHERE uid = '$idno'");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$query=mysql_query("UPDATE db_5m_users SET region = '2' WHERE id = '$idno'");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$query=mysql_query("SELECT * FROM db_5m_user_rights_data WHERE uid IS NULL ORDER BY id LIMIT 1");
					if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					$ava2 = 0;
					while ( $datensatz = mysql_fetch_array($query) ) {$ava2++;$fid=$datensatz['id'];}
					// *** //
					if ( $ava2 == 0 ) {
						$query=mysql_query("INSERT INTO db_5m_user_rights_data ( uid, urights ) VALUES ( '$idno', ' [forml] [membr] [conts] [ehome] [eagb] [eabus] [eimpr] [edocs] [enews] [eacts] [egall] [epops] [ecamp] [upic] [uico] [updf] [ufil] [mem1] [mem2] [mem3] [efrm] [ifrm] [mfrm] [sfrm] [meta] [advr] [nlet] [mail] [emenu] [mngr] [dloa] ' )");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					} else {
						$query=mysql_query("UPDATE db_5m_user_rights_data SET uid = '$idno', 
						urights = ' [forml] [membr] [conts] [ehome] [eagb] [eabus] [eimpr] [edocs] [enews] [eacts] [egall] [epops] [ecamp] [upic] [uico] [updf] [ufil] [mem1] [mem2] [mem3] [efrm] [ifrm] [mfrm] [sfrm] [meta] [advr] [nlet] [mail] [emenu] [mngr] [dloa] ' 
						WHERE id = '$fid'");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						// *** //
						$query=mysql_query("UPDATE db_5m_users SET region = '2' WHERE id = '$idno'");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					}
				}
		  }

		  function set_admin_right( $idno, $valu, $chkd ) {
				$query=mysql_query("SELECT * FROM db_5m_user_rights_data WHERE uid = '$idno' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0; $datastream = "";
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++; $datastream = $datensatz['urights'];}
				// *** //
				if ( $avai == 0 ) {
					if ( $chkd == 1 ) {
						$query=mysql_query("SELECT * FROM db_5m_user_rights_data WHERE uid IS NULL ORDER BY id LIMIT 1");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						$ava2 = 0;
						while ( $datensatz = mysql_fetch_array($query) ) {$ava2++;$fid=$datensatz['id'];}
						// *** //
						if ( $ava2 == 0 ) {
							$query=mysql_query("INSERT INTO db_5m_user_rights_data ( uid, urights ) VALUES ( '$idno', ' [$valu] ' )");
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						} else {
							$query=mysql_query("UPDATE db_5m_user_rights_data SET urights = ' [$valu] ', uid = '$idno' WHERE id = '$fid'");
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						}
						// *** //
						$query=mysql_query("UPDATE db_5m_users SET region = '2' WHERE id = '$idno'");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
					}
				} else {
					if ( $chkd == 1 ) {
						if ( strpos( $datastream, "[$valu]" ) == false ) {
							$datastream .= " [$valu] ";
							// *** //
							$query=mysql_query("UPDATE db_5m_user_rights_data SET urights = '$datastream' WHERE uid = '$idno'");
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
							// *** //
							$query=mysql_query("UPDATE db_5m_users SET region = '2' WHERE id = '$idno'");
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						}
					} else {
						if ( strpos( $datastream, "[$valu]" ) != false ) {
							$datastream = ereg_replace( "$valu", "", $datastream );
							// *** //
							$datastream = ereg_replace( " \[\] ", "", $datastream );
							// *** //
							$query=mysql_query("UPDATE db_5m_user_rights_data SET urights = '$datastream' WHERE uid = '$idno'");
							if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						}
						// *** //
						$query=mysql_query("SELECT * FROM db_5m_user_rights_data WHERE uid = '$idno' ORDER BY id");
						if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
						$ava3 = 0; $datastream = "";
						while ( $datensatz = mysql_fetch_array($query) ) {$ava3++; $datastream = $datensatz['urights'];}
						// *** //
						if ( $ava3 != 0 ) {
							$off = 1;
							// *** //
							if ( strpos( $datastream, "forml" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "membr" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "conts" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "ehome" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "eagb" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "eabus" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "eimpr" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "edocs" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "enews" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "eacts" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "egall" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "epops" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "ecamp" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "mem1" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "mem2" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "mem3" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "efrm" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "mfrm" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "ifrm" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "sfrm" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "meta" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "emenu" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "advr" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "nlet" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "mail" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "mngr" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "dloa" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "upic" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "uico" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "updf" ) != false ) { $off = 0; }
							if ( strpos( $datastream, "ufil" ) != false ) { $off = 0; }
							// *** //
							if ( $off == 1 ) {
								$query=mysql_query("UPDATE db_5m_user_rights_data SET urights = NULL, uid = NULL WHERE uid = '$idno'");
								if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
								// *** //
								$query=mysql_query("UPDATE db_5m_users SET region = '0' WHERE id = '$idno'");
								if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
							}
						}
					}
				}
		  }

		  function add_new_member_account( $fnam_, $snam_, $enam_ ) {
				$fnam = modifyUTFex($fnam_);
				$snam = modifyUTFex($snam_);
				$enam = modifyUTFex($enam_);
				// *** //
				$np = rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9);
				// *** //
				$query=mysql_query("
					INSERT INTO db_5m_users 
					( nick, pass, region, account_verification, account_locked, firstname, lastname, e_mail )
					VALUES 
					( '$enam', '$np', '0', '0', '1', '$fnam', '$snam', '$enam' )
				");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				// VERFIKATIONSMAIL MUSS GESCHICKT WERDEN!!!!
		  }

		  function link_advr_camp_to_doc( $idno, $adid, $caid ) {
				if ( $adid == "NULL" ) { $aid = "NULL"; } else { $aid = "'$adid'"; }
				if ( $caid == "NULL" ) { $cid = "NULL"; } else { $cid = "'$caid'"; }
				$query=mysql_query("UPDATE db_5m_content_docs SET docadvr = $aid, doccamp = $cid WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function remove_group( $idno ) {
				$query=mysql_query("DELETE FROM db_5m_content_docs WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$query=mysql_query("UPDATE db_5m_users SET ingroup = NULL WHERE ingroup = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function set_blog_lock( $idno, $val ) {
				$query=mysql_query("UPDATE db_5m_users SET blog_locked = '$val' WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function set_blog_user( $idno, $val ) {
				$reg = "0";
				if ( $val == 1 ) { $reg = "3"; }
				$query=mysql_query("UPDATE db_5m_users SET region = '$reg' WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function advert_flag( $idno, $val ) {
				$query=mysql_query("UPDATE db_5m_content_docs SET docadvr = '$val' WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function camp_flag( $idno, $val ) {
				$query=mysql_query("UPDATE db_5m_content_docs SET doccamp = '$val' WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function camp_intr( $idno, $val ) {
				$query=mysql_query("UPDATE db_5m_content_docs SET doccamp_interval = '$val' WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  function set_blog_flags( $f1, $f2, $f3, $f4 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_flags WHERE flag_group = 'blog' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'blog', 'doc', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'blog', 'new', '$f2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'blog', 'act', '$f3' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'blog', 'img', '$f4' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f1' WHERE flag_group = 'blog' AND flag_name = 'doc' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f2' WHERE flag_group = 'blog' AND flag_name = 'new' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f3' WHERE flag_group = 'blog' AND flag_name = 'act' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f4' WHERE flag_group = 'blog' AND flag_name = 'img' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function set_counter_flags( $f1, $f2, $f3, $f4 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_flags WHERE flag_group = 'counter' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'counter', 'mode1', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'counter', 'mode2', '$f2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'counter', 'mode3', '$f3' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'counter', 'mode4', '$f4' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f1' WHERE flag_group = 'counter' AND flag_name = 'mode1' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f2' WHERE flag_group = 'counter' AND flag_name = 'mode2' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f3' WHERE flag_group = 'counter' AND flag_name = 'mode3' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f4' WHERE flag_group = 'counter' AND flag_name = 'mode4' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function set_bgsound_flags( $f1, $f2 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_flags WHERE flag_group = 'bgsound' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'bgsound', 'mode1', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'bgsound', 'mode2', '$f2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f1' WHERE flag_group = 'bgsound' AND flag_name = 'mode1' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f2' WHERE flag_group = 'bgsound' AND flag_name = 'mode2' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function set_login_flags( $f1, $f2, $f3 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_flags WHERE flag_group = 'login' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'login', 'mode1', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'login', 'mode2', '$f2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'login', 'mode3', '$f3' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f1' WHERE flag_group = 'login' AND flag_name = 'mode1' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f2' WHERE flag_group = 'login' AND flag_name = 'mode2' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f3' WHERE flag_group = 'login' AND flag_name = 'mode3' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function set_banner_width( $f1 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_flags WHERE flag_group = 'banner' AND flag_name = 'size' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'banner', 'size', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f1' WHERE flag_group = 'banner' AND flag_name = 'size' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  function set_banner_style( $f1, $f2 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_flags WHERE flag_group = 'banner' AND flag_name != 'size' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'banner', 'type', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_flags ( flag_group, flag_name, flag_value ) VALUES ( 'banner', 'more', '$f2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f1' WHERE flag_group = 'banner' AND flag_name = 'type' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_flags SET flag_value = '$f2' WHERE flag_group = 'banner' AND flag_name = 'more' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }
		  
		  function set_banner_screen( $f1, $f2 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_attribs WHERE attrib_group = 'banner' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'banner', 'pict', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'banner', 'camp', '$f2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$f1' WHERE attrib_group = 'banner' AND attrib_name = 'pict' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$f2' WHERE attrib_group = 'banner' AND attrib_name = 'camp' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }
		  
		  function set_home_splash( $f1 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_attribs WHERE attrib_group = 'advertise' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'advertise', 'splash', '$f1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$f1' WHERE attrib_group = 'advertise' AND attrib_name = 'splash' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

			function save_mails ( $m1, $m2, $m3, $m4, $m5, $m6, $m7, $m8, $m9, $m10 ) {
				$query=mysql_query("SELECT * FROM db_5m_base_attribs WHERE attrib_group = 'mailserv' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '1', '$m1' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '2', '$m2' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '3', '$m3' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '4', '$m4' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '5', '$m5' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '6', '$m6' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '7', '$m7' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '8', '$m8' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '9', '$m9' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" INSERT INTO db_5m_base_attribs ( attrib_group, attrib_name, attrib_value ) VALUES ( 'mailserv', '10', '$m10' ) ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				} else {
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m1' WHERE attrib_group = 'mailserv' AND attrib_name = '1' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m2' WHERE attrib_group = 'mailserv' AND attrib_name = '2' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m3' WHERE attrib_group = 'mailserv' AND attrib_name = '3' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m4' WHERE attrib_group = 'mailserv' AND attrib_name = '4' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m5' WHERE attrib_group = 'mailserv' AND attrib_name = '5' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m6' WHERE attrib_group = 'mailserv' AND attrib_name = '6' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m7' WHERE attrib_group = 'mailserv' AND attrib_name = '7' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m8' WHERE attrib_group = 'mailserv' AND attrib_name = '8' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m9' WHERE attrib_group = 'mailserv' AND attrib_name = '9' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer2=mysql_query(" UPDATE db_5m_base_attribs SET attrib_value = '$m10' WHERE attrib_group = 'mailserv' AND attrib_name = '10' ");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
				}
			}
		  
		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
			function recode_specs( $text, $this_here = '', $into_this = '' ) {
				$tt = $text;
				$tt = ereg_replace( '{amp}', '&', $tt );
				$tt = ereg_replace( '{res}', '=', $tt );
				$tt = ereg_replace( '{rau}', '#', $tt );
				$tt = ereg_replace( '{sem}', ';', $tt );
				$tt = ereg_replace( '%@1', '&#239;', $tt );
				if ( $this_here != '' ) { $tt = ereg_replace( $this_here, $into_this, $tt ); }
				return $tt;
			}

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_company ( $Acomp, $Aaddr, $Aloca, $dlng = "*" ) {
				$comp = recode_specs( $Acomp );
				$addr = recode_specs( $Aaddr );
				$loca = recode_specs( $Aloca );
				// *** //
				$query=mysql_query("SELECT * FROM db_5m_core_data WHERE datagrp = 'company' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'company', 'name', '$comp', '$dlng' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'company', 'addr', '$addr', '$dlng' )
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'company', 'loca', '$loca', '$dlng' )
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				} elseif ( $avai > 0 ) {
					$quer2=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$comp' 
							WHERE datagrp = 'company' AND datakey = 'name' AND datalng = '$dlng'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$addr' 
							WHERE datagrp = 'company' AND datakey = 'addr' AND datalng = '$dlng'
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$loca' 
							WHERE datagrp = 'company' AND datakey = 'loca' AND datalng = '$dlng'
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_contact ( $Atel, $Afax, $Aeml, $Ainh, $Aco1, $Aco2, $dlng = "*" ) {
				$tel = recode_specs( $Atel );
				$fax = recode_specs( $Afax );
				$eml = recode_specs( $Aeml );
				$inh = recode_specs( $Ainh );
				$co1 = recode_specs( $Aco1 );
				$co2 = recode_specs( $Aco2 );
				// *** //
				$query=mysql_query("SELECT * FROM db_5m_core_data WHERE datagrp = 'contact' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'contact', 'tel', '$tel', '$dlng' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'contact', 'fax', '$fax', '$dlng' )
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'contact', 'eml', '$eml', '$dlng' )
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer5=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'contact', 'inh', '$inh', '$dlng' )
					");
					if(!$quer5) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer6=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'contact', 'co1', '$co1', '$dlng' )
					");
					if(!$quer6) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer7=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'contact', 'co2', '$co2', '$dlng' )
					");
					if(!$quer7) die("Fehler bei der Abfrage: ".mysql_error());
				} elseif ( $avai > 0 ) {
					$quer2=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$tel' 
							WHERE datagrp = 'contact' AND datakey = 'tel' AND datalng = '$dlng'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$fax' 
							WHERE datagrp = 'contact' AND datakey = 'fax' AND datalng = '$dlng'
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$eml' 
							WHERE datagrp = 'contact' AND datakey = 'eml' AND datalng = '$dlng'
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer5=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$inh' 
							WHERE datagrp = 'contact' AND datakey = 'inh' AND datalng = '$dlng'
					");
					if(!$quer5) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer6=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$co1' 
							WHERE datagrp = 'contact' AND datakey = 'co1' AND datalng = '$dlng'
					");
					if(!$quer6) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer7=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$co2' 
							WHERE datagrp = 'contact' AND datakey = 'co2' AND datalng = '$dlng'
					");
					if(!$quer7) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_legal ( $Acomp, $Aaddr, $Aloca, $dlng = "*" ) {
				$comp = recode_specs( $Acomp );
				$addr = recode_specs( $Aaddr );
				$loca = recode_specs( $Aloca );
				// *** //
				$query=mysql_query("SELECT * FROM db_5m_core_data WHERE datagrp = 'legal' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'legal', 'no1', '$comp', '$dlng' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'legal', 'no2', '$addr', '$dlng' )
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'legal', 'no3', '$loca', '$dlng' )
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				} elseif ( $avai > 0 ) {
					$quer2=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$comp' 
							WHERE datagrp = 'legal' AND datakey = 'no1' AND datalng = '$dlng'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$addr' 
							WHERE datagrp = 'legal' AND datakey = 'no2' AND datalng = '$dlng'
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$loca' 
							WHERE datagrp = 'legal' AND datakey = 'no3' AND datalng = '$dlng'
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_bank ( $Acomp, $Aaddr, $Aloca, $dlng = "*" ) {
				$comp = recode_specs( $Acomp );
				$addr = recode_specs( $Aaddr );
				$loca = recode_specs( $Aloca );
				// *** //
				$query=mysql_query("SELECT * FROM db_5m_core_data WHERE datagrp = 'bank' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'bank', 'name', '$comp', '$dlng' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'bank', 'iban', '$addr', '$dlng' )
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							INSERT INTO db_5m_core_data 
							( datagrp, datakey, dataval, datalng ) 
							VALUES 
							( 'bank', 'bicc', '$loca', '$dlng' )
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				} elseif ( $avai > 0 ) {
					$quer2=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$comp' 
							WHERE datagrp = 'bank' AND datakey = 'name' AND datalng = '$dlng'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer3=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$addr' 
							WHERE datagrp = 'bank' AND datakey = 'iban' AND datalng = '$dlng'
					");
					if(!$quer3) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$quer4=mysql_query("
							UPDATE db_5m_core_data SET 
							dataval = '$loca' 
							WHERE datagrp = 'bank' AND datakey = 'bicc' AND datalng = '$dlng'
					");
					if(!$quer4) die("Fehler bei der Abfrage: ".mysql_error());
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_linklist_column_1 ( 
			$title,
			$val01, $val02, $val03, $val04, $val05, $val06, $val07, $val08, $val09, $val10,
			$val11, $val12, $val13, $val14, $val15, $val16, $val17, $val18, $val19, $val20,
			$val21, $val22, $val23, $val24, $val25, $val26, $val27, $val28, $val29, $val30 ) {
				$query=mysql_query("SELECT * FROM db_5m_info_link_list WHERE pos = '0' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				$list = array();
				$list[] = $val01;$list[] = $val02;$list[] = $val03;$list[] = $val04;$list[] = $val05;
				$list[] = $val06;$list[] = $val07;$list[] = $val08;$list[] = $val09;$list[] = $val10;
				$list[] = $val11;$list[] = $val12;$list[] = $val13;$list[] = $val14;$list[] = $val15;
				$list[] = $val16;$list[] = $val17;$list[] = $val18;$list[] = $val19;$list[] = $val20;
				$list[] = $val21;$list[] = $val22;$list[] = $val23;$list[] = $val24;$list[] = $val25;
				$list[] = $val26;$list[] = $val27;$list[] = $val28;$list[] = $val29;$list[] = $val30;
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_info_link_list 
							( pos, typ, itemnode, itemtext, itemlink ) 
							VALUES 
							( '0', '1', '0', '".recode_specs( $title )."', '' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
								INSERT INTO db_5m_info_link_list 
								( pos, typ, itemnode, itemtext, itemlink ) 
								VALUES 
								( '0', '0', '$no', '$aa', '$bb' )
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				} else {
					$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '".recode_specs( $title )."' 
							WHERE 
							typ = '1' AND pos = '0'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '$aa', 
							itemlink = '$bb' 
							WHERE 
							typ = '0' AND pos = '0' AND itemnode = '$no'
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_linklist_column_2 ( 
			$title,
			$val01, $val02, $val03, $val04, $val05, $val06, $val07, $val08, $val09, $val10,
			$val11, $val12, $val13, $val14, $val15, $val16, $val17, $val18, $val19, $val20,
			$val21, $val22, $val23, $val24, $val25, $val26, $val27, $val28, $val29, $val30 ) {
				$query=mysql_query("SELECT * FROM db_5m_info_link_list WHERE pos = '1' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				$list = array();
				$list[] = $val01;$list[] = $val02;$list[] = $val03;$list[] = $val04;$list[] = $val05;
				$list[] = $val06;$list[] = $val07;$list[] = $val08;$list[] = $val09;$list[] = $val10;
				$list[] = $val11;$list[] = $val12;$list[] = $val13;$list[] = $val14;$list[] = $val15;
				$list[] = $val16;$list[] = $val17;$list[] = $val18;$list[] = $val19;$list[] = $val20;
				$list[] = $val21;$list[] = $val22;$list[] = $val23;$list[] = $val24;$list[] = $val25;
				$list[] = $val26;$list[] = $val27;$list[] = $val28;$list[] = $val29;$list[] = $val30;
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_info_link_list 
							( pos, typ, itemnode, itemtext, itemlink ) 
							VALUES 
							( '1', '1', '0', '".recode_specs( $title )."', '' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
								INSERT INTO db_5m_info_link_list 
								( pos, typ, itemnode, itemtext, itemlink ) 
								VALUES 
								( '1', '0', '$no', '$aa', '$bb' )
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				} else {
					$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '".recode_specs( $title )."' 
							WHERE 
							typ = '1' AND pos = '1'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '$aa', 
							itemlink = '$bb' 
							WHERE 
							typ = '0' AND pos = '1' AND itemnode = '$no'
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_linklist_column_3 ( 
			$title,
			$val01, $val02, $val03, $val04, $val05, $val06, $val07, $val08, $val09, $val10,
			$val11, $val12, $val13, $val14, $val15, $val16, $val17, $val18, $val19, $val20,
			$val21, $val22, $val23, $val24, $val25, $val26, $val27, $val28, $val29, $val30 ) {
				$query=mysql_query("SELECT * FROM db_5m_info_link_list WHERE pos = '2' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				$list = array();
				$list[] = $val01;$list[] = $val02;$list[] = $val03;$list[] = $val04;$list[] = $val05;
				$list[] = $val06;$list[] = $val07;$list[] = $val08;$list[] = $val09;$list[] = $val10;
				$list[] = $val11;$list[] = $val12;$list[] = $val13;$list[] = $val14;$list[] = $val15;
				$list[] = $val16;$list[] = $val17;$list[] = $val18;$list[] = $val19;$list[] = $val20;
				$list[] = $val21;$list[] = $val22;$list[] = $val23;$list[] = $val24;$list[] = $val25;
				$list[] = $val26;$list[] = $val27;$list[] = $val28;$list[] = $val29;$list[] = $val30;
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_info_link_list 
							( pos, typ, itemnode, itemtext, itemlink ) 
							VALUES 
							( '2', '1', '0', '".recode_specs( $title )."', '' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
								INSERT INTO db_5m_info_link_list 
								( pos, typ, itemnode, itemtext, itemlink ) 
								VALUES 
								( '2', '0', '$no', '$aa', '$bb' )
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				} else {
					$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '".recode_specs( $title )."' 
							WHERE 
							typ = '1' AND pos = '2'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '$aa', 
							itemlink = '$bb' 
							WHERE 
							typ = '0' AND pos = '2' AND itemnode = '$no'
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function co_save_linklist_column_4 ( 
			$title,
			$val01, $val02, $val03, $val04, $val05, $val06, $val07, $val08, $val09, $val10,
			$val11, $val12, $val13, $val14, $val15, $val16, $val17, $val18, $val19, $val20,
			$val21, $val22, $val23, $val24, $val25, $val26, $val27, $val28, $val29, $val30 ) {
				$query=mysql_query("SELECT * FROM db_5m_info_link_list WHERE pos = '3' ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				$avai = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$avai++;}
				// *** //
				$list = array();
				$list[] = $val01;$list[] = $val02;$list[] = $val03;$list[] = $val04;$list[] = $val05;
				$list[] = $val06;$list[] = $val07;$list[] = $val08;$list[] = $val09;$list[] = $val10;
				$list[] = $val11;$list[] = $val12;$list[] = $val13;$list[] = $val14;$list[] = $val15;
				$list[] = $val16;$list[] = $val17;$list[] = $val18;$list[] = $val19;$list[] = $val20;
				$list[] = $val21;$list[] = $val22;$list[] = $val23;$list[] = $val24;$list[] = $val25;
				$list[] = $val26;$list[] = $val27;$list[] = $val28;$list[] = $val29;$list[] = $val30;
				// *** //
				if ( $avai == 0 ) {
					$quer2=mysql_query("
							INSERT INTO db_5m_info_link_list 
							( pos, typ, itemnode, itemtext, itemlink ) 
							VALUES 
							( '3', '1', '0', '".recode_specs( $title )."', '' )
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
								INSERT INTO db_5m_info_link_list 
								( pos, typ, itemnode, itemtext, itemlink ) 
								VALUES 
								( '3', '0', '$no', '$aa', '$bb' )
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				} else {
					$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '".recode_specs( $title )."' 
							WHERE 
							typ = '1' AND pos = '3'
					");
					if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					// *** //
					$no = -1;
					// *** //
					foreach( $list as $li ) {
						$no++;
						// *** //
						$li = recode_specs( $li );
						// *** //
						$aa = ""; $bb = ""; $bl = 0;
						// *** //
						for( $n = 0; $n < strlen($li); $n++ ) {
							if ( $li[$n] == '{' ) {
								if ( ( $li[$n+1] == 'S' ) && ( $li[$n+2] == 'E' ) && ( $li[$n+3] == 'P' ) && ( $li[$n+4] == '}' ) ) {
									$bl = 1;
								}
							} else {
								switch($bl){
									case 0: $aa .= $li[$n]; break;
									case 1: if ( $li[$n] == '}' ) { $bl = 2; } ; break;
									case 2: $bb .= $li[$n]; break;
								}
							}
						}
						// *** //
						$quer2=mysql_query("
							UPDATE db_5m_info_link_list SET 
							itemtext = '$aa', 
							itemlink = '$bb' 
							WHERE 
							typ = '0' AND pos = '3' AND itemnode = '$no'
						");
						if(!$quer2) die("Fehler bei der Abfrage: ".mysql_error());
					}
				}
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  function get_internal_message($idno){
				$query=mysql_query("SELECT * FROM db_5m_internal_messages WHERE id = '$idno' ORDER BY id LIMIT 1");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				while ( $datensatz = mysql_fetch_array($query) ) {echo $datensatz['im_text'];}
		  }

		  function remove_internal_message($idno){
				$query=mysql_query("DELETE FROM db_5m_internal_messages WHERE id = '$idno'");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  include "searchengine.php";
		  function suchmotor( $lang, $begriff ) {
				if ( $lang == "de" ) { $co = "01"; } elseif ( $lang == "tr" ) { $co = "02"; }
				// *** //
				$query=mysql_query("SELECT * FROM db_5m_content_docs ORDER BY id");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$idlist = "";
				// *** //
				$searchRobot = new searchengine();
				// *** //
				while ( $datensatz = mysql_fetch_array($query) ) {
					$searchRobot->keywords( "docs", $datensatz["id"], $datensatz["docmeta$co"] );
					// *** //
					$res = $searchRobot->search( $begriff );
					// *** //
					if ( $res != false ) { $idlist .= $res . ","; }
					// *** //
					unset($searchRobot);
					// *** //
					$searchRobot = new searchengine();
				}
				// *** //
				echo $idlist.'?';
		  }

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */

			function hook_splash ( $which, $lng, $key, $vlu ) {
				$query=mysql_query("SELECT * FROM db_5m_base_docs WHERE lkey = '$lng' AND dockey = '$key' ORDER BY id LIMIT 1");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$gefunden = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$gefunden=1;break;}
				// *** //
				if ( $which == 1 ) { $whi = "splash1_use"; } else
				if ( $which == 2 ) { $whi = "splash2_use"; } else
				if ( $which == 3 ) { $whi = "videoframe_use"; }
				// *** //
				if ( $gefunden == 1 ) {
					$qry = "
					UPDATE db_5m_base_docs SET 
					$whi = '$vlu' 
					WHERE lkey = '$lng' AND dockey = '$key'
					";
				} else {
					$qry = "
					INSERT INTO db_5m_base_docs 
					( lkey, dockey, $whi ) 
					VALUES 
					( '$lng', '$key', '$vlu' )
					";
				}
				// *** //
				$query=mysql_query($qry);
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}

			function hook_splash_source ( $which, $lng, $key, $vlu ) {
				$query=mysql_query("SELECT * FROM db_5m_base_docs WHERE lkey = '$lng' AND dockey = '$key' ORDER BY id LIMIT 1");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$gefunden = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$gefunden=1;break;}
				// *** //
				if ( $which == 1 ) { $whi = "splash1"; } else
				if ( $which == 2 ) { $whi = "splash2"; } else
				if ( $which == 3 ) { $whi = "videoframe"; }
				// *** //
				if ( $gefunden == 1 ) {
					$qry = "
					UPDATE db_5m_base_docs SET 
					$whi = '$vlu' 
					WHERE lkey = '$lng' AND dockey = '$key'
					";
				} else {
					$qry = "
					INSERT INTO db_5m_base_docs 
					( lkey, dockey, $whi ) 
					VALUES 
					( '$lng', '$key', '$vlu' )
					";
				}
				// *** //
				$query=mysql_query($qry);
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}

			function size_splash ( $which, $lng, $key, $vlu ) {
				$query=mysql_query("SELECT * FROM db_5m_base_docs WHERE lkey = '$lng' AND dockey = '$key' ORDER BY id LIMIT 1");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$gefunden = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$gefunden=1;break;}
				// *** //
				if ( $which == 1 ) { $whi = "splash1_h"; } else
				if ( $which == 2 ) { $whi = "splash2_h"; }
				// *** //
				if ( $gefunden == 1 ) {
					$qry = "
					UPDATE db_5m_base_docs SET 
					$whi = '$vlu' 
					WHERE lkey = '$lng' AND dockey = '$key'
					";
				} else {
					$qry = "
					INSERT INTO db_5m_base_docs 
					( lkey, dockey, $whi ) 
					VALUES 
					( '$lng', '$key', '$vlu' )
					";
				}
				// *** //
				$query=mysql_query($qry);
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}

			function hook_video ( $which, $lng, $key ) {
				$query=mysql_query("SELECT * FROM db_5m_base_docs WHERE lkey = '$lng' AND dockey = '$key' ORDER BY id LIMIT 1");
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
				// *** //
				$gefunden = 0;
				while ( $datensatz = mysql_fetch_array($query) ) {$gefunden=1;break;}
				// *** //
				if ( $gefunden == 1 ) {
					$qry = "
					UPDATE db_5m_base_docs SET 
					videoframe_pos = '$which' 
					WHERE lkey = '$lng' AND dockey = '$key'
					";
				} else {
					$qry = "
					INSERT INTO db_5m_base_docs 
					( lkey, dockey, videoframe_pos ) 
					VALUES 
					( '$lng', '$key', '$which' )
					";
				}
				// *** //
				$query=mysql_query($qry);
				if(!$query) die("Fehler bei der Abfrage: ".mysql_error());
			}

		  /* ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   *
		   * ---------------------------------------------------------------------------------------------------------------- *
		   * ---------------------------------------------------------------------------------------------------------------- */
		  include "morefuncs.php";
		  include "theme_server_side.php";

?>
