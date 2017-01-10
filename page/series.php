<?php
	require("../functions.php");
	require("../class/series.class.php");
	$Series = new Series($mysqli);
	//MUUTUJAD
	$seriesname = "";
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	//kui on ?logout aadressireal siis log out
	if (isset($_GET["logout"])) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["seriesname"]) && 
		!empty($_POST["seriesname"])
	  ) {
		  
		$Series->save($Helper->cleanInput($_POST["seriesname"]));
		
	}
	
	// sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	}else{
		// kui ei ole määratud siis vaikimis id ja ASC
		$sort = "id";
		$direction = "ascending";
	}
	
	//kas otsib
	if(isset($_GET["q"])){
		
		$q = $Helper->cleanInput($_GET["q"]);
		
		$seriesData = $Series->get($q, $sort, $direction);
	
	} else {
		$q = "";
		$seriesData = $Series->get($q, $sort, $direction);
	
	}
	
	
	
	
?>

<?php require("../header.php"); ?>

<div class="container">


<p> Tere tulemast <?=$_SESSION ["userEmail"];?>!

<p><a href="data.php"> <button onclick="goBack()">tagasi</button></a></p>
</p>
	<br><br>
		<h2>Salvesta oma lemmikseriaalid</h2>
		<form method="POST">
			<input name="seriesname" placeholder="Seriaali nimi" type="text">

		<input type="submit" value="Salvesta">

		<br>
	<body style="background-color:palegreen;">
	<h2>Leia infot seriaali kohta </h2>
		<form method="POST">

		<form name="series">
			<select name="menu" onChange="window.document.location.href=this.options[this.selectedIndex].value;" 
			<option selected="selected">Vali üks</option>
				<option value="http://www.imdb.com/title/tt0475784/episodes?ref_=tt_ov_epl">Westworld</option>
				<option value="http://www.imdb.com/title/tt1844624/episodes?ref_=tt_ov_epl">American Horror Story</option>
				<option value="http://www.imdb.com/title/tt1520211/episodes?ref_=tt_ov_epl">The Walking Dead</option>
				<option value="http://www.imdb.com/title/tt1826940/episodes?ref_=tt_ov_epl">New Girl</option>
				<option value="http://www.imdb.com/title/tt1442437/episodes?ref_=tt_ov_epl">Modern Family</option>
				<option value="http://www.imdb.com/title/tt0944947/episodes?ref_=tt_ov_epl">Game Of Thrones</option>
				<option value="http://www.imdb.com/title/tt2306299/episodes?season=4">Vikings</option>
				<option value="http://www.imdb.com/title/tt2707408/episodes?ref_=tt_ov_epl">Narcos</option>
				<option value="http://www.imdb.com/title/tt4158110/episodes?season=2">Mr.Robot</option>
			</select>
		</form>	
		<br><br>


<h2>Series</h2>
	
	<form>
		<input type="search" name="q">
		<input type="submit" value="Otsi">
	</form>
	


	<?php
		
$html = "<table class='table table-striped table-bordered'>";
		
		$html .= "<tr>";
			$html .= "<th>
						<a href='?q=".$q."&sort=id&direction=".$direction."'>
							id
						</a>
					</th>";
			$html .= "<th>
						<a href='?q=".$q."&sort=seriesname&direction=".$direction."'>
							seriesname
						</a>
					</th>";
		$html .= "</tr>";
		
		//iga liikme kohta massiivis
		foreach($seroesData as $c){
			// iga auto on $c
			//echo $c->seriesname."<br>";
			
			$html .= "<tr>";
				$html .= "<td>".$c->id."</td>";
				$html .= "<td>".$c->seriesname."</td>";
				$html .= "<td>
							<a href='edit.php?id=".$c->id."' class='btn btn-default'>
							
								<span class='glyphicon glyphicon-pencil'></span>
								Muuda
								
							</a>
						</td>";
				
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		
		echo $html;
		
		
		$listHtml = "<br><br>";
		

	?>
</div>

<?php require("../footer.php"); ?>
