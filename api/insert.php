<?php
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $fname = "";
  if($request->meeting == "cms"){
    $f = file_get_contents('insights_cms.json');
    $fname = "insights_cms.json";
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