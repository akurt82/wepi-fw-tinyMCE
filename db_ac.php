<?php
    // HOST
    $wepi_host = "localhost";
    // USERNAME
    $wepi_user = "root";
    // PASSWORD
    $wepi_pass = "test";
    // DATABASE
    $wepi_data = "test";
    $wepi_conn = mysql_connect( $wepi_host, $wepi_user, $wepi_pass );
    $wepi_connect = mysql_select_db( $wepi_data, $wepi_conn );
?>