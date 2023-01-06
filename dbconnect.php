<?php
   $host        = "host = 127.0.0.1";
   $port        = "port = 5432";
   $dbname      = "dbname = bank";
   $credentials = "user = postgres password=tushar";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   if(!$conn)
   {
      echo "Error : Unable to open database\n";
   } else 
   {
      echo "Opened Database Successfully\n";
   }
   //echo
?>