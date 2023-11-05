<?php

	$host = "sql306.infinityfree.com"; 			
	$user = "if0_34867037"; 
	$pass = "7Pa9bwHEBCuD"; 
	$banco = "if0_34867037_jbi";
		
	$conexao = @mysqli_connect($host, $user, $pass, $banco )
	or die ("Não foi possível acessar o banco de dados, por favor, tente novamente mais tarde");
	$conexao->set_charset("utf8");
	
?>