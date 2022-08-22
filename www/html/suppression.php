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
	
       $cpe23Uri = $_POST['id'];
       $connection->query("delete from pnij where id= $cpe23Uri");
       
       

?>

<script language="javascript" type="text/javascript">
        window.location.replace("http://172.17.0.3/addpnij.php");
</script>

<?php
       exit();
?>

