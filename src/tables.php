<html lang="en">
<head>
  <title>License Plates</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<br>
<p>
<?php
include ("database.config.php");
$tbl = "";
$index = 0;
$result = mysql_query("show tables");

// showing table names in header
while($table = mysql_fetch_array($result)) { 
    echo "<a class='btn btn-primary' href='?tbl=$table[0]'>$table[0]</a> "; 
    if($index == 0) $tbl = $table[0];
    $index++;
}
?>
</p>

<?php
    /*default table*/
	if(isset($_GET["tbl"])) {
	   $tbl = $_GET["tbl"];
	}

	$query="SELECT * from ".$tbl;
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	
	print "Total Records : $num_rows<P>";
	print "<table width=100% class='table table-bordered'>\n";
	$cols = 0;

	while ($get_info = mysql_fetch_assoc($result)){
	
	if($cols == 0) {
	  $cols = 1;
	  print "<thead><tr>";
	  foreach($get_info as $col => $value) {
		print "<th>$col</th>";
	  }
	  print "</tr></thead>";
	}

	print "<tr>";
	foreach ($get_info as $field)
	print "\t<td class='td-all'><font face=arial size=2/>$field</font></td>\n";
	print "</tr>\n";
	}
	print "</table>\n";
?>
</div>
</body>
</html>
