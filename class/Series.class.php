<?php
class Series{
	        private $connection;
        function __construct($mysqli){
            $this->connection = $mysqli;
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
            if ($q == ""){
                echo "ei otsi";
                $stmt = $this->connection->prepare("
                    SELECT id, seriesname
                    FROM series
                    WHERE deleted IS NULL 
                    ORDER BY $sort $orderBy
                ");
                echo $this->connection->error;
            } else {
                echo "Searches: " . $q;
              
                $searchword = "%".$q."%";
                $stmt = $this->connection->prepare("
                    SELECT id, seriesname
                    FROM series
                    WHERE deleted IS NULL AND
                    (id LIKE ? OR seriesname LIKE ?)
                    ORDER BY $sort $orderBy
                ");
                $stmt->bind_param("ss", $searchword, $searchword);
            }

            echo $this->connection->error;

            $stmt->bind_result($id, $seriesName);
            $stmt->execute();

            //tekitan massiivi

            $result = array();

            while ($stmt->fetch()) {
                //tekitan objekti
                $series = new StdClass();
                $series->id = $id;
                $series->seriesName = $seriesName;

                //echo $plate."<br>";
                // iga kord massiivi lisan juurde nr m�rgi
                array_push($result, $series);
            }
            $stmt->close();
            return $result;
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

function saveSeries ($seriesName) {
		$stmt = $this->connection->prepare("INSERT INTO series(seriesname) VALUES (?)");
            echo $this->connection->error;
            $stmt->bind_param("s", $seriesName);
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
            $stmt->bind_result($seriesName);
            $stmt->execute();
            //tekitan objekti
            $series = new Stdclass();
            if ($stmt->fetch()) {
                $series->seriesName = $seriesName;
            } else {
                header("Location: series.php");
                exit();
            }
            $stmt->close();
            return $series;
        }
 function update($id, $seriesName){
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

