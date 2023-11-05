<?php

session_start();

	if (isset($_GET['t']))
	{
		$_SESSION['splashTxtSairConta'] = true;
	}
	header('Location: inicio.php?splash=true');

?>