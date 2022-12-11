<?php
	session_start();

	/* Special commend to Microsoft Internet Explorer  
	   cross-working with sessions
	*/
	header('P3P: CP="CAO PSA OUR"');

    include_once "wepi_dyn_host.php";
    include_once "wepi_dyn_public.php";

    /* ************************************************************
     *
     *    If the request comes within the host, then wepiServ
     *    will recieve response. Otherwise, the response will
     *    be empty.
     *    
     *    This is a security check up.
     *          
     * ************************************************************ */
/*
    if (
        ( $_SERVER["SERVER_NAME"] == $wepi_source_url ) ||
        ( $_SERVER["SERVER_NAME"] == "www.$wepi_source_url" )
    ) {
*/
        $cmd = $_GET["wepi"];
        $txt = "";
    
        $wepiProc = "";
    
        for ( $x = 0; $x < $_GET["parcount"]; $x++ ) {
            $par = "param$x";
            $par = $_GET[$par];
            switch( $x ) {
				case 0;
					// Will be ignored, because it's the session_id
					break;
                case 1:
                    if ( $par == "call_proc" ) { $wepiProc = "call"; }
                    break;
                case 2:
                    if ( $wepiProc == "call" ) { $txt = "$par ("; }
                    break;
                case 3:
                    // Will be ignored, because it's the random-code
                    break;
                default:
                    $ppp = $par;
                    $ppp = ereg_replace( "'", "\{0x98p4}.t.", $ppp );
                    $ppp = ereg_replace( "\{0x98p4}.t.", "\'", $ppp );
                    $ppp = ereg_replace( '"', '\{0x98p4}.t.', $ppp );
                    $ppp = ereg_replace( "\{0x98p4}.t.", '\"', $ppp );
                    $txt .= "'$ppp'";
                    if ( $x < $_GET["parcount"] - 1 ) { $txt .= ","; }
            };
        }

        if ( $wepiProc == "call" ) {
            $txt .= ");";
            eval( $txt );
        }

		if ( $cmd == "get_multi_sess" ) {
			$cmd = trim($_GET["get_multi_sess"]);
			$arr = array(); $ari = 0; $art = "";
			for( $no = 0; $no < strlen($cmd); $no++ ) {
				if ( $cmd[$no] == ',' ) {
					$ari++;
				} else {
					if ( $cmd[$no] != ' ' ) { $arr[$ari] .= $cmd[$no]; }
				}
			}
			$out = "";
			foreach( $arr as $a ) {
				$out .= $_SESSION[$a] + ";";
			}
			echo $out;
		} else if ( $cmd == "set_multi_sess" ) {
			// var1, var2, var3, var4 = val1, val2, val3, val4
			$cmd = trim($_GET["get_multi_sess"]);
			$vlu = $_GET["set_val"];
			$arr = array(); $ari = 0; $art = "";
			$vrr = array(); $vri = 0; $art = "";
			for( $no = 0; $no < strlen($cmd); $no++ ) {
				if ( $cmd[$no] == ',' ) {
					$ari++; echo $arr[$ari];
				} else {
					if ( $cmd[$no] != ' ' ) { $arr[$ari] .= $cmd[$no]; }
				}
			}echo $arr[$ari];
			for( $no = 0; $no < strlen($vlu); $no++ ) {
				if ( $vlu[$no] == ',' ) {
					$vri++;echo $vrr[$vri];
				} else {
					if ( $vlu[$no] != ' ' ) { $vrr[$vri] .= $vlu[$no]; }
				}
			}echo $vrr[$vri];
			$out = "";
			$vri = 0;
			foreach( $arr as $a ) {
				$vri = 0;
				$_SESSION[$a] = $vrr[$vri];
				$vri++;
			}
        } else if ( $cmd == "get_sess" ) {
            $cmd = trim($_GET["get_sess"]);
			$cnt = substr_count( $cmd, "." );
			eval("echo $"."_SESSION['".$cmd."'];");
		} else if ( $cmd == "set_sess" ) {
            $vlu = $_GET["set_val"];
            $cmd = trim($_GET["set_sess"]);
			$cnt = substr_count( $cmd, "." );
			eval("$"."_SESSION['".$cmd."'] = '$vlu';");
        } else if ( $cmd == "read_file" ) {
            $handle = fopen ($_GET["read_file"], "r");
            while (!feof($handle)) {
                $buffer = fgets($handle);
                echo $buffer;
            }
            fclose ($handle);
        } else if ( $cmd == "write_file" ) {
            $handle = fopen ($_GET["write_file"], "w");
            fwrite($handle, $_GET["set_val"] );
            fclose ($handle);
        } else if ( $cmd == "append_file" ) {
            $handle = fopen ($_GET["append_file"], "a");
            fwrite($handle, $_GET["set_val"] );
            fclose ($handle);
        }

    //}

?>
