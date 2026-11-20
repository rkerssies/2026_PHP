<?php
include "app/core/db_connect.php";


$sql = "SELECT * FROM fruits";  
// Execute the SQL query
$result = mysqli_query($conn, $sql);

// Process the result set
$out = '';
if (mysqli_num_rows($result) > 0) {
  // Output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    $out .= "id: " . $row["id"]. " - Name: " . $row["naam"]. " " . $row["sweetness"]. "<br>";
  }
} else {
  $out = "0 results";
}

?>




<h1>Fruit</h1>
<?php echo $out ; ?>