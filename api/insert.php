<?php
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $fname = "";
  if($request->meeting == "sales"){
    $f = file_get_contents('insights_sales.json');
    $fname = "insights_sales.json";
  } else if ($request->meeting == "mycon"){
    $f = file_get_contents('insights_mycon.json');
    $fname = "insights_mycon.json";
  }

  if($request->insight){
    $ins = json_decode($f);
    array_push($ins, $request->insight);
  }
  try {
    file_put_contents($fname, json_encode($ins, JSON_PRETTY_PRINT));
    echo json_encode($request->insight);
    $f = null;
    $fname = null;
  } catch(Exception $e){
    echo $e->getMessage();
  }

?>