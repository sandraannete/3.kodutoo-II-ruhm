<?php
class Series{
	        private $connection;
        function __construct($mysqli){
            $this->connection = $mysqli;
}

function delete($id){
   	$stmt = $this->connection->prepare("UPDATE series SET deleted=NOW() WHERE id=? AND deleted IS NULL");
    $stmt->bind_param("i", $id);
            // kas õnnestus salvestada
        if ($stmt->execute()) {
                echo "Deleted";
            }
            $stmt->close();
        }

	function get($q, $sort, $direction){
            //mis sort ja j�rjekord
            $allowedSortOptions = ["id", "seriesname"];
            //kas sort on lubatud valikute sees
        if (!in_array($sort, $allowedSortOptions)) {
                    $sort = "id";
            }
            echo "Sorteerin: " . $sort . " ";
          
            $orderBy = "ASC";
        if ($direction == "descending"){
                $orderBy = "DESC";
            }
            echo "Order: " . $orderBy ." ";

		if($q == ""){
		
			echo "ei otsi";
			
			$stmt = $this->connection->prepare("
				SELECT id, seriesname
				FROM series
				WHERE deleted IS NULL 
				ORDER BY $sort $orderBy
			");
				echo $this->connection->error;
		}else{
			
				echo "Otsib: ".$q;
			
			//teen otsisõna
			// lisan mõlemale poole %
			$searchword = "%".$q."%";
			
			$stmt = $this->connection->prepare("
				SELECT id, seriesname
				FROM series
				WHERE deleted IS NULL AND
				(seriesname LIKE ?)
				ORDER BY $sort $orderBy
			");
			$stmt->bind_param("s", $searchword);
		
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $seriesname);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$series = new StdClass();
			
			$series->id = $id;
			$series->seriesname = $seriesname;
			
			//echo $seriesname."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $series);
		}
		
		$stmt->close();
		
		
		return $result;
	}

function saveSeries ($seriesname) {
		$stmt = $this->connection->prepare("INSERT INTO series(seriesname) VALUES (?)");
            echo $this->connection->error;
            $stmt->bind_param("s", $seriesname);
            if ($stmt->execute()) {
                echo "salvestamine �nnestus";
            } else {
                echo "ERROR " . $stmt->error;
            }
            $stmt->close();
            $this->connection->close();
        }

function cleanInput($input){
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;
        }

function getSingle($edit_id){
            $stmt = $this->connection->prepare("SELECT seriesname FROM series WHERE id=? 
                AND deleted IS NULL");
            //et näha, mis error on koodis täpsemalt kirjutame echo error
            echo $this->connection->error;
            $stmt->bind_param("i", $edit_id);
            $stmt->bind_result($seriesname);
            $stmt->execute();
            //tekitan objekti
            $series = new Stdclass();
            if ($stmt->fetch()) {
                $series->seriesname = $seriesname;
            } else {
                header("Location: series.php");
                exit();
            }
            $stmt->close();
            return $series;
        }
 function update($id, $seriesname){
            $stmt = $this->connection->prepare("UPDATE series SET seriesname=? WHERE id=? 
                    AND deleted IS NULL");
            $stmt->bind_param("si", $seriesName, $id);
            // kas õnnestus salvestada
            if ($stmt->execute()) {
                // õnnestus
                echo "salvestus õnnestus!";
            }
            $stmt->close();
        }
}
?>

