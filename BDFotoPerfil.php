<?php

include_once('conexao.php');
include_once('rsnl.php');
include_once('configs.php');
include_once('Vlogin.php');

$idUsuario = $dadosLogin['idUsuario'];

if (isset($_POST['excluFoto']))
{
	if (unlink($dirFotoPerfil . $dadosLogin['urlFotoPerfil']))
		{
			$sqlupdate =  "update usuario set urlFotoPerfil='' where idUsuario = '$idUsuario'";
			$resultado = @mysqli_query($conexao, $sqlupdate);
			if (!$resultado) {
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); }
				header('Location: perfil.php');
		}
		else
		{
			$_SESSION['perfilMsg'] = 3;
			header('Location: perfil.php');
            exit();
		}
	
}

if (isset($_FILES['arquivoFotoPerfil'])) //Alterar foto enviado
{
	$erroArquivo = $_FILES['arquivoFotoPerfil']['error'];
	$erroExclImg;
	if ($erroArquivo === UPLOAD_ERR_OK)
	{
		if (!empty($dadosLogin['urlFotoPerfil']))
		{
			if (unlink($dirFotoPerfil . $dadosLogin['urlFotoPerfil']))
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
			$nomeArquivo = $_FILES['arquivoFotoPerfil']['name'];
			$caminhoTemporario = $_FILES['arquivoFotoPerfil']['tmp_name'];
			$nomeArquivoUnico = sha1(time()) . '_' . $nomeArquivo;
			$destinoFotoPerfil = $dirFotoPerfil . $nomeArquivoUnico;
			
			/*Tratar extensão*/
			$extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
                $extensoesPermitidas = array('jpg', 'jpeg', 'png');

                if (!in_array($extensao, $extensoesPermitidas))
                {
                    $_SESSION['perfilMsg'] = 1; //mensagem de erro
                    header('Location: perfil.php');
                    exit();
                }
				
				/*Trartar tamanho do nome*/
			if(mb_strlen($nomeArquivo) > 41)
			{
				$_SESSION['perfilMsg'] = 2;
				header('Location: perfil.php');
                exit();
			}
			if (file_exists($destinoFotoPerfil))
			{
				$_SESSION['perfilMsg'] = 3;
				header('Location: perfil.php');
                exit();
			}
			else
			{
				if(move_uploaded_file($caminhoTemporario, $destinoFotoPerfil))
				{
					echo 'sucesso ao mover';
				}
				else
				{
					$_SESSION['perfilMsg'] = 3;
					header('Location: perfil.php');
					exit();
				}
			}
			
			$sqlupdate =  "update usuario set urlFotoPerfil='$nomeArquivoUnico' where idUsuario = '$idUsuario'";
			$resultado = @mysqli_query($conexao, $sqlupdate);
			if (!$resultado) {
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); }
				header('Location: perfil.php');
		}
		else
		{
			$sqlupdate =  "update usuario set urlFotoPerfil='' where idUsuario = '$idUsuario'";
			$resultado = @mysqli_query($conexao, $sqlupdate);
			if (!$resultado) {
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); }
				header('Location: perfil.php');
				
				$_SESSION['perfilMsg'] = 3;
				header('Location: perfil.php');
                exit();
		}
	} //UPLOAD_ERR_OK
		else
		{
			die("ef");
			$_SESSION['perfilMsg'] = 3;
			header('Location: perfil.php');
            exit();
		}
}//Alterar foto enviado

	if (!isset($_POST['excluFoto']) && !isset($_FILES['arquivoFotoPerfil']))
	{
		header('Location: index.php');
	}
?>