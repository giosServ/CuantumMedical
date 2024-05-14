<?PHP
session_start();
//unset ($_SESSION['usuari_validat']);
//unset ($_SESSION["clau"]);
//unset ($_SESSION['idioma']);
session_unset();
session_destroy ();
header("Location: index.php");
exit();
?>