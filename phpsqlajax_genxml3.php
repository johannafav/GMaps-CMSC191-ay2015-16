<?php

  require("phpsqlajax_dbinfo.php");

  // Start XML file, create parent node

  $dom = new DOMDocument("1.0");
  $node = $dom->createElement("markers");
  $parnode = $dom->appendChild($node);

  // Opens a connection to a MySQL server

  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Select all the rows in the markers table

  $query = "SELECT * FROM markers WHERE 1";
  $result = mysqli_query($conn,$query);
  if (!$result) {
    die('Invalid query: ' . mysql_error());
  }

  header("Content-type: text/xml");

  // Iterate through the rows, adding XML nodes for each

  while ($row=mysqli_fetch_assoc($result)){
    // ADD TO XML DOCUMENT NODE
    $node = $dom->createElement("marker");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("name",$row['name']);
    $newnode->setAttribute("address", utf8_encode($row['address']));
    $newnode->setAttribute("lat", $row['lat']);
    $newnode->setAttribute("lng", $row['lng']);
    $newnode->setAttribute("type", $row['type']);
  }

  echo $dom->saveXML();

?>