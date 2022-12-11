<?php

        /* --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
           --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
           --- 5M-Ware Copyright(c) 2014
           --- Web Creations Extended Programming Interface
           --- Server-Side API
           --- 
           --- Object: wepi_tooltip
           --- Version: 1.0
           ---
           --- Dependencies:
           ---
           --- jQuery, jQuery UI
           --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
           --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- */

	class wepi_tooltip {

		function create ( $id, $effect = "slideDown" ) {
			$code = '
                               <script>
                                     $(function() {
                                    $( "#'.$id.'" ).tooltip({
                                    show: {
                                    effect: "'.$effect.'",
                                    delay: 50
                                    }
                                    });});
                                </script>
                                ';
			return $code;
		}

		function hook( $id, $effect = "slideDown" ) {
			echo $this->create( $id, $effect );
		}

	}

?>

