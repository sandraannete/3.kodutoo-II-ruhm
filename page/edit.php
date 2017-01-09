<?php	//edit.php
	require("../functions.php");
	
		require("../class/Series.class.php");
		$Series = new Series($mysqli);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Series->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["seriesName"]));
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	//kui ei ole id-d aadressireal siis suunan
	if(!isset($_GET["id"])){
		header ("Location: series.php");
		exit();
	}

	$c=$Series-> getSingle($_GET["id"]);
	//var_dump($c);

	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}
	//kustutan
	if(isset($_GET["delete"])){
		
		delete($_GET["id"]);
	
		header("Location: series.php");
		exit();
	}
	
?>

<?php require("../header.php");?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="seriesname" >Seriaali nimi</label><br>
	<input id="seriesname" name="seriesname" type="text" value="<?php echo $c->seriesname;?>" ><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form> 

  <br>
  <?php require("../footer.php");?>
  <br>
 	<a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>