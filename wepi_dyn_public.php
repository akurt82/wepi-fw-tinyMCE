
    <?php

		include "db_ac.php";

		$wepi_conn = mysql_connect( $wepi_host, $wepi_user, $wepi_pass );
		$wepi_connect = mysql_select_db( $wepi_data, $wepi_conn );

		function hol_dir_daten_aus_dem_server () {
			echo "Ola, aus der PHP-Ecke!";
		}

?>
