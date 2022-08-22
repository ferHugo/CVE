<?php
       $database ="cve";
       $user = "root";
       $password = "secret";
       $host = "mysql";

       try{ $connection = new PDO("mysql:host={$host};dbname={$database};charset=utf8", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                echo "connexion réussie";
          }
       catch(PDOException $ex){
                echo "connexion refusée";
       }


       $query = "INSERT INTO sitanj (cpe23Uri) values ('$_POST[cpe23Uri]')";
       $cve = $connection->query($query);

?>

<script language="javascript" type="text/javascript">
        window.location.replace("http://172.17.0.3/sitanj.php");
</script>

