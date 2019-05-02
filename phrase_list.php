<?php
  $statements = file($filename, FILE_IGNORE_NEW_LINES);
  $filename = "file.txt";
  $text = file_get_contents($filename);
  file($filename, $text, FILE_APPEND, "/n");
?>

<html>
<head>
</head>
<body>
<?php
  echo $text
  foreach ($statements as $stmt){
  echo "<p>". $stmt . "</p>";
  }
?>
</body>
</html>
