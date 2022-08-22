<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Table CVE</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Searchbox CSS
    <link rel="stylesheet" href="css/search.css"-->

    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu/meanmenu.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
	<!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <link rel="stylesheet" href="css/wave/button.css">
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- Data Table JS
		============================================ -->
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>

<?
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


       $query = "SELECT * FROM sitanj";
       $cve = $connection->query($query);

       $query2 = "select count(DISTINCT cve_id) from cve inner join pnij where cve.cpe23uri = pnij.cpe23Uri";
       $data = $connection->query($query2); 
?>

    <!-- Mobile Menu start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul class="mobile-menu-nav">
                                <li><a data-toggle="collapse" data-target="#demodepart" href="#">Tables</a>
                                    <ul id="demodepart" class="collapse dropdown-header-top">
                                        <li><a href="index.php">Liste des CVEs</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Menu area start-->
    <div class="main-menu-area mg-tb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">                     
                        <li class="active"><a data-toggle="tab" href="index.php"><i class="notika-icon notika-windows"></i> Base de donnée</a>
                        </li>
		    </ul>
                    <div class="tab-content custom-menu-content">
                        
                        <div id="Tables" class="tab-pane active notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="index.php">Liste CVE's</a>
				</li>
                                <li><a href="sitanj.php">SITANJ</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area Start-->
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		    <div class="data-table-list">
			<form action="ajoutersitanj.php" method="post">
			<div class="basic-tb-hd">
			    <h2>Ajouter un fabricant</h2>
			</div>
			<div class="col">
      				<input type="text" class="form-control" id="ajouter" placeholder="Ajout d'un CPE23URI" name="cpe23Uri">
			</div>
			
			<br>
			<div>
			 <button type="submit" class="btn btn-primary">Ajouter</button>
			<br>
			</div>
			</form>
                        <div class="basic-tb-hd">
                            <h2>Liste des CVEs</h2>
			</div>
			<dl class="dropdown"> 

                        <div class="table-responsive-lg">
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                    <tr>
					<th>CVE</th>
					<th>test</th>
				    </tr>
				</thead>
				<tbody>
					<?php
					
        				while($row = $cve->fetch(PDO::FETCH_ASSOC)) : ?>
				<tr>
					<td><a href="https://nvd.nist.gov/vuln/detail/<?php echo htmlspecialchars($row['cve_id']); ?>"><?php echo htmlspecialchars($row['cve_id']); ?></a></td>

					<td> <?php echo htmlspecialchars($row['cpe23Uri']); ?></td>
					<form method="post" action="suppression_sitanj.php">
						<input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']) ?>"></input>
						<td><button type="submit" name="cpe23Uri" class="btn btn-outline-success">Supprimer</button></td>
					</form>
				</tr>
                			<?php endwhile; ?>
   

				</tr>
                                </tbody>	
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--searchbox -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script  language="JavaScript" type="text/javascript" src="js/script.js"></script> 
    <!-- jquery -->
	<script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
                ============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
                ============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- scrollUp JS
                ============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
                ============================================ -->
    <script src="js/meanmenu/jquery.meanmenu.js"></script>
	<!-- plugins JS
                ============================================ -->
    <script src="js/plugins.js"></script>
    <!-- Data Table JS
                ============================================ -->
    <script src="js/data-table/jquery.dataTables.min.js"></script>
    <script src="js/data-table/data-table-act.js"></script>	  
</body>

</html>
