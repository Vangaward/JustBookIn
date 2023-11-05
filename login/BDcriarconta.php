<?php

include_once('../conexao.php');

session_start();

$nomeUsuario = $_POST['nome'];
$sobrenomeUsuario = $_POST['sobrenome'];
$emailUsuario = $_POST['email'];
$senhaUsuario = $_POST['senha'];
$repetirSenha = $_POST['repetirSenha'];
$dataUsuario = $_POST['data'];

$senhaUsuarioSha1 = sha1($senhaUsuario);

if ($senhaUsuario != $repetirSenha)
{
	$_SESSION['msgErroCriarConta'] = 1;
	header('Location: criarConta.php');
	die();
}

$sql = "INSERT INTO usuario (nome, sobrenome, email, senha, dataNascimento, statusConta, tipoConta)
        VALUES ('$nomeUsuario', '$sobrenomeUsuario', '$emailUsuario', '$senhaUsuarioSha1', '$dataUsuario', 0, 3);";
		
$resultado = @mysqli_query($conexao, $sql);
			if (!$resultado) {
			echo '<br><input type="button" onclick="window.location='."'../index.php'".';" value="Voltar ao início"><br><br>';
			die('<style> .erro { background-color: red; color: #ffffff;}</style><div class="erro"><b>Query Inválida:</b><br>Ocorreu um erro inesperado.</div><br>' . @mysqli_error($conexao)); 
			}
   
    header('Location: ../inicio.php');
    exit;

$conexao->close();
?>
