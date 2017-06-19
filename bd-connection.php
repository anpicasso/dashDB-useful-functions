<?php
  if( getenv("VCAP_SERVICES") ) { // Connection from bluemix app
    $json = getenv("VCAP_SERVICES");
    $services_json = json_decode($json,true);
    $blu = $services_json["dashDB"];
    if (empty($blu)) {
        echo "No dashDB service instance is bound. Please bind a dashDB service instance";
        return;
    }
    $bludb_config = $services_json["dashDB"][0]["credentials"];

    // create DB connect string
    $conn_string = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".
       $bludb_config["db"].
       ";HOSTNAME=".
       $bludb_config["host"].
       ";PORT=".
       $bludb_config["port"].
       ";PROTOCOL=TCPIP;UID=".
       $bludb_config["username"].
       ";PWD=".
       $bludb_config["password"].
       ";";   
  } else { // Local Connection - Need to install php_ibm_db2.dll
    $database = "BLUDB";        # Get these database details from
    $hostname = "HOSTNAME";  # the Connect page of the dashDB
    $user     = "USER";   # web console.
    $password = "PASSWORD";   #
    $port     = 50000;          #
    $ssl_port = 50001;          #

    # Build the connection string
    #
    $driver  = "DRIVER={IBM DB2 ODBC DRIVER};";
    $dsn     = "DATABASE=$database; " .
               "HOSTNAME=$hostname;" .
               "PORT=$port; " .
               "PROTOCOL=TCPIP; " .
               "UID=$user;" .
               "PWD=$password;";
    $ssl_dsn = "DATABASE=$database; " .
               "HOSTNAME=$hostname;" .
               "PORT=$ssl_port; " .
               "PROTOCOL=TCPIP; " .
               "UID=$user;" .
               "PWD=$password;" .
               "SECURITY=SSL;";
    $conn_string = $driver . $dsn;     # Non-SSL
    //$conn_string = $driver . $ssl_dsn; # SSL

    # Connect
    #
  }
  
  // connect to BLUDB
  $conn = db2_connect($conn_string, '', '');
?>
