<?php

	//Vlogin = Verificação de login
	//Esse arquivo verifica se o usuário fez login

	require_once 'conexao.php';

	session_start();

	if (!isset($_SESSION['logado'])) // Não logado
	{
		$dadosLogin = "";
		$logado = 0;
	}
	else
	{
		$idUsuarioSessao = $_SESSION['idUsuario'];
		$sql = "SELECT * FROM usuario WHERE idUsuario = '$idUsuarioSessao'";
		$resultado = mysqli_query($conexao, $sql);
		$dadosLogin = mysqli_fetch_array($resultado);
		$logado = 1;
	}
    
?>