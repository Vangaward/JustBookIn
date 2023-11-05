<?php

include_once('conexao.php');
include_once('rsnl.php');
include_once('configs.php');
include_once('Vlogin.php');

$idUsuario = $dadosLogin['idUsuario'];
$idLitSha1 = $_GET['i'];

$queryLit = mysqli_query($conexao, "SELECT * FROM Literatura WHERE sha1(idLit) = '$idLitSha1'");

if (!$queryLit)
{
	echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
	die('<b>Query Inválida:</b>');
		//exibir mensagem de erro
}
$dadosLit=mysqli_fetch_array($queryLit);

	if ($dadosLit['idUsuario'] != $dadosLogin['idUsuario'])
	{
		header('Location: index.php');
		die();
	}

if (isset($_POST['excluFoto']))
{
	
	if (unlink($dirCapa . $dadosLit['urlCapa']))
		{
			$sqlupdate =  "update Literatura set urlCapa='' where sha1(idLit) = '$idLitSha1'";
			$resultado = @mysqli_query($conexao, $sqlupdate);
			if (!$resultado) {
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); }
				header('Location: perfil.php');
				exit();
		}
		else
		{
			$_SESSION['editLit'] = 3;
			header('Location: editarLivro.php?i=' . $idLitSha1);
            exit();
		}
	die();
}

if (isset($_FILES['alterCapa'])) //Alterar foto enviado
{
	$erroArquivo = $_FILES['alterCapa']['error'];
	$erroExclImg;
	if ($erroArquivo === UPLOAD_ERR_OK)
	{
		if (!empty($dadosLit['urlCapa']))
		{
			if (unlink($dirCapa . $dadosLit['urlCapa']))
			{
				$erroExclImg = false;
			}
			else
			{
				$erroExclImg = true;
			}
		}
		else
		{
			$erroExclImg = false;
		}
		if ($erroExclImg == false)
		{
			$nomeArquivo = $_FILES['alterCapa']['name'];
			$caminhoTemporario = $_FILES['alterCapa']['tmp_name'];
			$nomeArquivoUnico = md5(time()) . '_' . $nomeArquivo;
			$destinoCapa = $dirCapa . $nomeArquivoUnico;
			
			/*Tratar extensão*/
			$extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
                $extensoesPermitidas = array('jpg', 'jpeg', 'png');

                if (!in_array($extensao, $extensoesPermitidas))
                {
                    $_SESSION['editLit'] = 1; //mensagem de erro
                    header('Location: editarLivro.php?i=' . $idLitSha1);
                    exit();
                }
				
				/*Trartar tamanho do nome*/
			if(mb_strlen($nomeArquivo) > 41)
			{
				$_SESSION['editLit'] = 2;
				header('Location: editarLivro.php?i=' . $idLitSha1);
                exit();
			}
			if (file_exists($destinoCapa))
			{
				$_SESSION['editLit'] = 3;
				header('Location: editarLivro.php?i=' . $idLitSha1);
                exit();
			}
			else
			{
				if(move_uploaded_file($caminhoTemporario, $destinoCapa))
				{
					echo 'sucesso ao mover';
				}
				else
				{
					$_SESSION['editLit'] = 3;
					header('Location: editarLivro.php?i=' . $idLitSha1);
					exit();
				}
			}
			
			$sqlupdate =  "update Literatura set urlCapa='$nomeArquivoUnico' where sha1(idLit) = '$idLitSha1'";
			$resultado = @mysqli_query($conexao, $sqlupdate);
			if (!$resultado) {
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); }
				header('Location: editarLivro.php?i=' . $idLitSha1);
				die();
		}
		else
		{
			$sqlupdate =  "update Literatura set urlCapa='' where sha1(idLit) = '$idLitSha1'";
			$resultado = @mysqli_query($conexao, $sqlupdate);
			if (!$resultado) {
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); }
				
				$_SESSION['editLit'] = 3;
				header('Location: editarLivro.php?i=' . $idLitSha1);
                exit();
		}
	} //UPLOAD_ERR_OK
		else
		{
			$_SESSION['editLit'] = 3;
			header('Location: editarLivro.php?i=' . $idLitSha1);
            exit();
		}
}//Alterar foto enviado

	if (!isset($_POST['excluCapa']) && !isset($_FILES['alterCapa']))
	{
		header('Location: index.php');
	}
?>