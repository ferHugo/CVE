
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

	$query = "SELECT DISTINCT * FROM cve INNER JOIN pnij WHERE cve.cpe23Uri = pnij.cpe23Uri";
        $cve = $connection->query($query);
        $fichier = fopen("export/csv.txt","w");
        fclose($fichier);

        $fichier = fopen("export/csv.txt","w+");
        $chaine = "";

	//while($cve->fetch(PDO::FETCH_ASSOC))
	while($row = $cve->fetch(PDO::FETCH_ASSOC))
	{
		$chaine .= "\"".htmlspecialchars($row['cpe23Uri'])."\";";
		// $chaine = "\"".$cve["cve_id"]."\";";
		$chaine = "\"".htmlspecialchars($row['cve_id'])."\";";
		//$chaine .= "\"".htmlspecialchars($row['cpe23Uri'])."\";";
		$chaine .= "\"".htmlspecialchars($row['version'])."\";";
		$chaine .= "\"".htmlspecialchars($row['lastModifiedDate'])."\";";
		$chaine .= "\"".htmlspecialchars($row['versionStartIncluding'])."\";";

		//$chaine = htmlspecialchars($row['cvss2_Score']);
		//$chaine = htmlspecialchars($row['cpe23Uri']);
		

                fwrite($fichier,$chaine."\r\n");



        }

        fclose($fichier);
?>
<script language="javascript" type="text/javascript">
        window.location.replace("http://172.17.0.3/pnij.php");
</script>

