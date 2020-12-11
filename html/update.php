<!-- Update the record given an id and new data -->
<?php
  include '../db.php';
  $id = $_POST['id'];
  $name = $_POST['name'];
  $score = $_POST['score'];
  $sql = "update demo_table set name='$name', score='$score' where id=$id";
  $result = $conn->query($sql);
  $conn->close();
  header("location: index.php");
?>
