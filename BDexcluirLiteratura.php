<?php

header('Content-Type: text/html; charset=UTF-8');

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

include_once ('rsnl.php');

include_once ('configs.php');

if (isset ($_POST['excLitName']))
{
	
	$idLitSha1 = $_GET['i'];
	$senhaSha1 = sha1($_POST['senhaName']);
	
	if ($senhaSha1 != $dadosLogin['senha'])
	{
		$_SESSION['msgBDExLit'] = 3; //senha errada
		header('Location: excluirLiteratura.php?i=' . $idLitSha1);
		die();
	}

	$queryLit = mysqli_query($conexao, "SELECT * from Literatura where sha1(idLit) = '$idLitSha1';");     
			if (!$queryLit)
			{
				echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
				die('<b>Query Inválida: </b>' . @mysqli_error($conexao));  
			}
	$dadosLit = mysqli_fetch_array($queryLit);

	if ($dadosLit['idUsuario'] != $dadosLogin['idUsuario'])
			{
				header ('Location: login');
				die();
			}
			
	$idLit = $dadosLit['idLit'];
	
	// Inicia a transação
mysqli_begin_transaction($conexao);

try {
    // Excluindo categorias
    $sqlDeleteCats =  "DELETE FROM itemCategoria WHERE idLit = '$idLit';";
    $resultado = @mysqli_query($conexao, $sqlDeleteCats);

    if (!$resultado) {
        throw new Exception('<b>Query Inválida:</b>' . @mysqli_error($conexao));
    }

    // Excluindo capítulos
    $sqlDeleteCapLit =  "DELETE FROM capituloLit WHERE idLit = '$idLit';";
    $resultado = @mysqli_query($conexao, $sqlDeleteCapLit);

    if (!$resultado) {
        throw new Exception('<b>Query Inválida:</b>' . @mysqli_error($conexao));
    }

    // Excluindo comentários
    $sqlDeleteComentarios =  "DELETE FROM comentarios WHERE idLit = '$idLit';";
    $resultado = @mysqli_query($conexao, $sqlDeleteComentarios);

    if (!$resultado) {
        throw new Exception('<b>Query Inválida:</b>' . @mysqli_error($conexao));
    }

    // Excluindo verificações
    $sqlDeleteVerificacao =  "DELETE FROM verificacao WHERE idLit = '$idLit';";
    $resultado = @mysqli_query($conexao, $sqlDeleteVerificacao);

    if (!$resultado) {
        throw new Exception('<b>Query Inválida:</b>' . @mysqli_error($conexao));
    }

    // Excluindo curtidas
    $sqlDeleteCurtidas =  "DELETE FROM curtidasLit WHERE idLit = '$idLit';";
    $resultado = @mysqli_query($conexao, $sqlDeleteCurtidas);

    if (!$resultado) {
        throw new Exception('<b>Query Inválida:</b>' . @mysqli_error($conexao));
    }
	
	// Excluindo favoritos
    $sqlDeleteFavs =  "DELETE FROM favorito WHERE idLit = '$idLit';";
    $resultado = @mysqli_query($conexao, $sqlDeleteFavs);

    if (!$resultado) {
        throw new Exception('<b>Query Inválida:</b>' . @mysqli_error($conexao));
    }

    // Se todas as consultas foram bem-sucedidas, commit na transação
    mysqli_commit($conexao);

    echo "Registros excluídos com sucesso!";
} catch (Exception $e) {
    // Se ocorrer um erro, rollback na transação
    mysqli_rollback($conexao);

	$_SESSION['msgBDExLit'] = 1;
	header('Location: editarLivro.php?i=' . $idLitSha1);
    die();
   // echo "Erro na transação: " . $e->getMessage();
}
	
	/*Manipulando os arquivos*/
//Buscando caminho
	
	$confirmExclusLit = 0; //0 = Não pode | 1 = pode
	
	if ($dadosLit['urlCapa'] != "")
	{
		$img = $dirCapa . $dadosLit['urlCapa'];
		if (unlink($img))
		{
		   echo 'Capa excluída com sucesso.';
		   $confirmExclusLit = 1;
		}
		else
		{
			echo 'Não foi possível excluir a capa.';
			$confirmExclusLit = 0;
		}
	}
	
	$pdf = $dirPdf . $dadosLit['urlPdf'];
	if (unlink($pdf))
	{
     echo 'PDF excluído com sucesso.';
	 $confirmExclusLit = 1;
    }
	else
	{
        echo 'Não foi possível excluir o PDF.';
		$confirmExclusLit = 0;
    }
	
	if ($confirmExclusLit == 1)
	{
		$sqldeleteLit =  "delete from Literatura where idLit = '$idLit'";
	
		// executando instrução SQL
		$resultado = @mysqli_query($conexao, $sqldeleteLit);
		if (!$resultado)
		{
			$_SESSION['msgBDExLit'] = 1;
			header('Location: perfil.php');
			die("erro: " . @mysqli_error($conexao));
		}
	}
	else
	{
		$_SESSION['msgBDExLit'] = 1;//erro
		//header('Location: editarLivro.php?i=' . $idLitSha1);
		die("ó o erro"); 
	}
	
	$_SESSION['msgBDExLit'] = 2;//Sucesso
	header('Location: perfil.php');
	die();
	
// Fecha a conexão
mysqli_close($conexao);

}//isset('excLitName')
else
{
	header('Location: editarLivro.php?i=' . $idLitSha1);
	die(); 
}
?>