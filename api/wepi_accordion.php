<?php

        /* --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
           --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
           --- 5M-Ware Copyright(c) 2014
           --- Web Creations Extended Programming Interface
           --- Server-Side API
           --- 
           --- Object: wepi_accordion
           --- Version: 1.0
           ---
           --- Dependencies:
           ---
           --- jQuery, jQuery UI
           --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
           --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- */

	class wepi_accordion {

		private $liste = array();
		private $licnt = 0;
		public  $myname = "wepi_accordion";

		function name( $value = "{()gdfgzewasf  dv fd3t54.-,fd.-AE;rl345{gds}ß\\}" ) {
			if ( $value == "{()gdfgzewasf  dv fd3t54.-,fd.-AE;rl345{gds}ß\\}" ) {
				return $this->myname;
			} else {
				$this->myname = $value;
			}
		}

		function add( $panelid, $title, $content ) {
			$this->liste[$this->licnt]['id'] = $panelid;
			$this->liste[$this->licnt]['ti'] = $title;
			$this->liste[$this->licnt]['co'] = $content;
			$this->licnt++;
		}

		function content( $panelid, $value = "{()gdfgzewasf  dv fd3t54.-,fd.-AE;rl345{gds}ß\\}" ) {
			foreach( $this->liste as $li ) {
				if ( $li['id'] == $panelid ) {
					if ( $value == "{()gdfgzewasf  dv fd3t54.-,fd.-AE;rl345{gds}ß\\}" ) {
						return $li['co'];
					} else {
						$li['co'] = $value; break;
					}
				}
			}
		}

		function create () {
			$code = '
                              <div id = "' . $this->myname . '" name = "' . $this->myname . '">
                              ';
			// *** //
			foreach( $this->liste as $li ) {
				$code .= '
                                        <h3>' . $li['ti'] . '</h3>
                                        ';
				$code .= '<div><p id = "' . $li['id'] . '" name = "' . $li['id'] . '">
                                        ';
				$code .= $li['co'];
				$code .= '</p></div>
                                        ';
			}
			// *** //
			$code .= '
                              </div>
                              ';
                            // *** //
                            $code .= '<script>
                                $(function() {$( "#' . $this->myname . '" ).accordion();});
                            </script>';
			// *** //
			return $code;
		}

		function write() {
			echo $this->create();
		}

	}

?>

