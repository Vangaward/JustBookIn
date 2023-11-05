<?php

include_once("conexao.php");
include_once('Vlogin.php');
include_once('rsnl.php');

$idLitSha1 = $_GET['i'];

if (isset($_POST["sbmtCurtitLit"]))
	{
		$queryLiteratura = ("SELECT * from Literatura where sha1(idLit) = '$idLitSha1'");
			if (!$queryLiteratura) {
				$_SESSION['msgFav'] = 1;
				header('Location: livro.php?i=' . $idLitSha1);
			die ("Houve um erro ao carregar os recursos necessários. #1");}
			
			$resultado = mysqli_query($conexao, $queryLiteratura);
			$dadosLit = mysqli_fetch_array($resultado);
			
			$idLit =  $dadosLit['idLit'];
			$idUsuario = $dadosLogin['idUsuario'];
			
			$queryFavoritos = mysqli_query($conexao, "SELECT * from favorito where idLit = '$idLit' and idUsuario = '$idUsuario';");
			if (!$queryFavoritos) {
				$_SESSION['msgFav'] = 1;
				header('Location: livro.php?i=' . $idLitSha1);
			die ("Houve um erro ao carregar os recursos necessários. #1");}
			
			$dadosFav = mysqli_fetch_array($queryFavoritos);
			$idFavExclui = $dadosFav['idFavorito'];
		
			if (mysqli_num_rows($queryFavoritos) > 0) //Já está favoritado
			{
				$sqldeleteFav =  "delete from favorito where idFavorito = '$idFavExclui'";
	
				$resultado = @mysqli_query($conexao, $sqldeleteFav);
					if (!$resultado)
					{
						$_SESSION['msgFav'] = 4;
						header('Location: livro.php?i=' . $idLitSha1);
						die("erro: " . @mysqli_error($conexao));
					}
					else
					{
						header('Location: livro.php?i=' . $idLitSha1);
					}
			}
			else
			{
				$queryComents = "INSERT INTO favorito (idLit, idUsuario) values ('$idLit', '$idUsuario')";
				$resultado = @mysqli_query($conexao, $queryComents);
				if (!$resultado) {
					$_SESSION['msgFav'] = 2;
					//header('Location: livro.php?i=' . $idLitSha1);
					die ("Houve um erro ao carregar inserir a literatura "/* . mysqli_error($conexao)*/);
				}
				else
				{
					header('Location: livro.php?i=' . $idLitSha1);
					die();
				}
			}
	}
	else
	{
		header('Location: index.php');
	}

?>