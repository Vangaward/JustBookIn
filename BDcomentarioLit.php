<?php

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

include_once ('rsnl.php');

$idLitSha1 = $_GET['i'];
//die($idLitSha1);

	if (isset($_POST["submEnviaComent"]))
	{
		$queryLiteratura = ("SELECT * from Literatura where sha1(idLit) = '$idLitSha1'");
			if (!$queryLiteratura) {
			die ("Houve um erro ao carregar os recursos necessários. #1");}
		
			$resultado = mysqli_query($conexao, $queryLiteratura);
			$dadosLit = mysqli_fetch_array($resultado);
			
			$dataHoraAtual = date('Y-m-d H:i:s');
			$idLit =  $dadosLit['idLit'];
			$idUsuario = $dadosLogin['idUsuario'];
			$txtComent =  $_POST["nameTxtComent"];
			$queryComents = "INSERT INTO comentarios (idLit, idUsuario, txtComentario, dataCom) values ('$idLit', $idUsuario, '$txtComent', '$dataHoraAtual')";
            
            // Executa a consulta e verifica erros
            $resultadoComents = mysqli_query($conexao, $queryComents);
            
            if (!$resultadoComents) {
				$_SESSION['msgComent'] = 2;
				header('Location: livro.php?i=' . $idLitSha1 . '#divMsgComsSup');
                die ("Houve um erro ao carregar inserir o comentário "/* . mysqli_error($conexao)*/);
            }
			else
			{
				$_SESSION['msgComent'] = 1;
                header('Location: livro.php?i=' . $idLitSha1 . '#divMsgComsSup');
            }
		
	}
	else
	{
		header('Location: index.php');
	}

?>