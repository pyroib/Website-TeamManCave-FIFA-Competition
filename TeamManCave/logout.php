<?PHP
session_destroy();
$_SESSION = array();
echo "<h2>done</h2>";
?>