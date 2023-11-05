<?php

include_once('conexao.php');

include_once('rsnl.php');

include_once('Vlogin.php');

if (isset($_POST["excluirCom"]))
{
	$idCom = $_GET['ic'];
	
	$sqldeleteCom =  "delete from comentarios where sha1(idComentario) = '$idCom'";
	
	$resultado = @mysqli_query($conexao, $sqldeleteCom);
		if (!$resultado)
		{
			$_SESSION['msgComent'] = 3;
			header('Location: livro.php?i=' . sha1($_SESSION['idLitAtualBDexcuirCom']) . '#divMsgComsSup');
			die("erro: " . @mysqli_error($conexao));
		}
		else
		{
			$_SESSION['msgComent'] = 4;
			header('Location: livro.php?i=' . sha1($_SESSION['idLitAtualBDexcuirCom']) . '#divMsgComsSup');
		}
}
else
{
	header("Location: index.php");
	die();
}

?>