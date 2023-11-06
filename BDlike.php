<?php

include_once('conexao.php');
include_once('rsnl.php');
include_once('Vlogin.php');


$idUsuario = $dadosLogin['idUsuario'];

if(!isset($_SESSION['litAtual']))
{ header('Location: index.php'); }
$idLit = $_SESSION['litAtual'];
/**//**//**//**/

$queryLike = mysqli_query($conexao, "SELECT * FROM curtidasLit WHERE idLit = '$idLit' AND idUsuario = '$idUsuario'");

if (!$queryLike) {
    $_SESSION['msgLike'] = 1;
	header('Location: livro.php?i=' . sha1($idLit));
    die("Erro na consulta: " . mysqli_error($conexao));
}
$qtdeLinhas = mysqli_num_rows($queryLike);

/* No banco, se curtida = 1 então é "Like", 
   se curtida = 0 então é "deslike".*/
$dadosLike = mysqli_fetch_array($queryLike);

$idCurtida = $dadosLike['idLitCurtida'];

if (isset($_POST['sbmtLike']))
{
	if ($qtdeLinhas > 0)
	{
		if ($dadosLike['curtida'] == 0) //Já havia um "deslike"
		{
			$queryLike = mysqli_query($conexao, "update curtidasLit set curtida= 1 where idLitCurtida = '$idCurtida'");
			if (!$queryLike) {
				$_SESSION['msgLike'] = 1;
				header('Location: livro.php?i=' . sha1($idLit));
				die("Erro na consulta: " . mysqli_error($conexao));
			}
			header('Location: livro.php?i=' . sha1($idLit) . "#infoLivroId");
			die();
		}
		else //Já havia um like
		{
			$queryLike = mysqli_query($conexao, "delete from curtidasLit where idLitCurtida = '$idCurtida'");
			if (!$queryLike) {
				$_SESSION['msgLike'] = 1;
				header('Location: livro.php?i=' . sha1($idLit));
				die("Erro na consulta: " . mysqli_error($conexao));
			}
			header('Location: livro.php?i=' . sha1($idLit) . "#infoLivroId");
			die();
		}
	}
	else
	{
		$queryInsertLike = mysqli_query($conexao, "INSERT INTO curtidasLit (curtida, idUsuario, idLit) values (1, '$idUsuario', '$idLit')");

			if (!$queryInsertLike) {
				$_SESSION['msgLike'] = 1;
				header('Location: livro.php?i=' . sha1($idLit));
				die("Erro: " . mysqli_error($conexao));
			}
			header('Location: livro.php?i=' . sha1($idLit) . "#infoLivroId");
			die();
	}
}
else if (isset($_POST['sbmtDeslike']))
{
	if ($qtdeLinhas > 0)
	{
		if ($dadosLike['curtida'] == 1) //Já havia um "like"
		{
			$queryLike = mysqli_query($conexao, "update curtidasLit set curtida= 0 where idLitCurtida = '$idCurtida'");
			if (!$queryLike) {
				$_SESSION['msgLike'] = 1;
				header('Location: livro.php?i=' . sha1($idLit));
				die("Erro na consulta: " . mysqli_error($conexao));
			}
			header('Location: livro.php?i=' . sha1($idLit) . "#infoLivroId");
			die();
		}
		else //Já havia um deslike
		{
			$queryLike = mysqli_query($conexao, "delete from curtidasLit where idLitCurtida = '$idCurtida'");
			if (!$queryLike) {
				$_SESSION['msgLike'] = 1;
				header('Location: livro.php?i=' . sha1($idLit));
				die("Erro na consulta: " . mysqli_error($conexao));
			}
			header('Location: livro.php?i=' . sha1($idLit) . "#infoLivroId");
			die();
		}
	}
	else
	{
		$queryInsertLike = mysqli_query($conexao, "INSERT INTO curtidasLit (curtida, idUsuario, idLit) values (0, '$idUsuario', '$idLit')");

			if (!$queryInsertLike) {
				$_SESSION['msgLike'] = 1;
				header('Location: livro.php?i=' . sha1($idLit));
				die("Erro: " . mysqli_error($conexao));
			}
			header('Location: livro.php?i=' . sha1($idLit) . "#infoLivroId");
			die();
	}
}
else //Form não enviado
{
	header('Location: index.php');
}

?>