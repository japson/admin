<?
//$login="root";
//$password="";
$login="mysql";
$password="mysql";
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=jap7;charset=UTF8', $login, $password);
  //  foreach($dbh->query('SELECT * from FOO') as $row) {
  //      print_r($row);
  //  }
   // $dbh = null;
} catch (PDOException $e) {
   // print "Error!: " . $e->getMessage() . "<br/>";
	//$mass[0]['atribut']=0;$mass[0]['text']=$e->getMessage()."Неудача соединения.";
    die();
}
?>