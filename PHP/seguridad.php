<?
@session_start();

if($_SESSION["autentica"] != "SIP"){
	header("Location:../index.html");
	exit();
}
?>
