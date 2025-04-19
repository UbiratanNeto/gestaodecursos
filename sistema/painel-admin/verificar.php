<?php 
@session_start();
if(@$_SESSION['nivel'] != 'Administrador' and @$_SESSION['nivel'] != 'Professor'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}	
 ?>
