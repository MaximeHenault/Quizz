<?php
	include('appmvc.php');

	if(isset($_GET['page'])) $page = $_GET['page'];
	else $page = 1;

	if(isset($_GET['question'])) $question = $_GET['question'];
    else $question = 1; 

	if(isset($_GET['compt'])) $compt = $_GET['compt'];
    else $compt = 0; 

	//$categorie = $_GET['categorie'];

	$monapp = new AppMVC();
	
	$monapp -> afficherPage($page, $question, $compt);
?>