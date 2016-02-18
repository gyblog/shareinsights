<?php
	/* CRATE JSON file 
	$obj = array();
	$f = scandir('../img');
	foreach($f as $p){
		if(!in_array($p, array(".", "..")))
			array_push($obj, array("name"=>substr($p, 0,-4), "insigths" => array()));
	}
	//var_dump($obj);
	//echo json_encode($obj, JSON_PRETTY_PRINT);
	file_put_contents("data.json", json_encode($obj, JSON_PRETTY_PRINT));*/
	
	/* Update JSON file */
	$dataF = file_get_contents("data.json");
	$data = json_decode($dataF);
	foreach($data as $i){
		if($i->name == $_POST["to"]){
			array_push($i->insights, array("from"=>$_POST["emp"], "tag"=>$_POST["tag"], "insight"=>$_POST["ins"], "date"=>date('l F j, g:i a')));
		}
	}
	file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT));
	echo "<p>Thank you for your feedback to ".$_POST["to"]."!</p>";
?>