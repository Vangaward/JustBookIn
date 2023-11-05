<?php

header('Content-Type: text/html; charset=UTF-8');

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

include_once ('configs.php');

/**/

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Inicia a transação
        $conexao->begin_transaction();
		
		try
		{
		
        $dataHoraAtual = date('Y-m-d H:i:s');
        $idUsuario = $dadosLogin['idUsuario'];
        $titulo = $_POST['tituloLivroName'];
        $descricao = $_POST['descricaoLivroName'];
        //$urlCapa = "Não configurado";

        /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
		
		/*DEFIIR NOME DA IMAGEM E DO PDF*/
		/*pdf*/
		$nomeArquivoPDF = $_POST['nomeArquivoPDF'];
        $dadosArquivoPDF = base64_decode($_POST['dadosArquivoPDF']);
        $nomeArquivoUnicoPDF = md5(time()) . '_' . $nomeArquivoPDF;
		$contadorPDF = 1;
        $diretorioDeBuscaPDF = $dirPdf . $nomeArquivoUnicoPDF;

        while (file_exists($diretorioDeBuscaPDF))
        {
            $nomeArquivoUnicoPDF = $contadorPDF . $nomeArquivoUnicoPDF;
            $contadorPDF++;
            $diretorioDeBuscaPDF = $dirPdf . $nomeArquivoUnicoPDF;
        }
		/*imagem*/
		
		$nomeArquivoCapa = $_POST['nomeArquivoCapa'];
		if (!empty($nomeArquivoCapa))
		{
			$dadosArquivoCapa = base64_decode($_POST['dadosArquivoCapa']);
			$infoArquivo = pathinfo($nomeArquivoCapa);
			$nomeArquivoComExtensao = $infoArquivo['basename'];
			$nomeArquivoUnicoCapa = md5(time()) . '_' . $nomeArquivoComExtensao;
			$contadorCapa = 1;
			$caminhoDestinoCapa = $dirCapa . $nomeArquivoCapa;
			
			while (file_exists($caminhoDestinoCapa))
			{
				$nomeArquivoUnicoPDF = $contadorPDF . $nomeArquivoUnicoPDF;
				$contadorPDF++;
				$caminhoDestinoCapa = $dirCapa . $nomeArquivoUnicoPDF;
			}
			
			$queryLiteratura = mysqli_query($conexao, "SELECT urlCapa, urlPdf FROM Literatura where urlCapa = '$caminhoDestinoCapa' && urlPdf = '$nomeArquivoUnico'");
			if (!$queryLiteratura) {
			die ("Houve um erro ao carregar os recursos necessários. #1");}
			
			if (mysqli_num_rows($queryLiteratura) > 0)
			{
				echo "<script>window.location.reload();</script>";
				die();
			}
			
			if (file_exists($dirCapa . $caminhoDestinoCapa))
			{
				echo "<script>window.location.reload();</script>";
				die();
			}
		} //!empty($nomeArquivoCapa)
		
		if (file_exists($dirPdf . $nomeArquivoUnicoPDF))
		{
			echo "<script>window.location.reload();</script>";
			die();
		}
		
		//die("\n\nPaused ||");
		
        /*PDF*/
		
        $caminhoDestinoPDF = $dirPdf . $nomeArquivoUnicoPDF;

        if (file_put_contents($caminhoDestinoPDF, $dadosArquivoPDF) !== false) {
            echo "<br>O arquivo PDF foi enviado com sucesso para o terceiro arquivo.";
            echo "<br>" . $nomeArquivoUnico;
        } else {
            echo "<br>Erro ao enviar o arquivo PDF para o terceiro arquivo.";
            die();
        }

       //die("<br>Execução do salvamento da imagem interrompido!");
        /*ImagemCapa*/
        if (!empty($nomeArquivoCapa))
		{
				$caminhoDestinoCapa2 = $dirCapa . $nomeArquivoUnicoCapa;

			if(file_put_contents($caminhoDestinoCapa2, $dadosArquivoCapa) !== false)
			{
				echo "<br>O arquivo de Imagem foi enviado com sucesso para o terceiro arquivo.";
			}
			else
			{
				echo "<br>Erro ao enviar o arquivo de Imagem para o terceiro arquivo.";
				die();
			}
		} //!empty($nomeArquivoCapa)
		
		if (empty($nomeArquivoCapa))
		{
			$nomeArquivoUnicoCapa = $litSemImg;
		}

        /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

		$sqlinsertLit =  "insert into Literatura (dataEdit, urlCapa, dataLanc, idUsuario, titulo, descricao, views, urlPdf) values ('$dataHoraAtual', '$nomeArquivoUnicoCapa', '$dataHoraAtual', '$idUsuario', '$titulo', '$descricao', 0, '$nomeArquivoUnicoPDF')";
            $resultado = @mysqli_query($conexao, $sqlinsertLit);
			if (!$resultado) {
			echo '<br><input type="button" onclick="window.location='."'../index.php'".';" value="Voltar ao início"><br><br>';
			die('<style> .erro { background-color: red; color: #ffffff;}</style><div class="erro"><b>Query Inválida:</b><br>Ocorreu um erro inesperado.</div><br>' . @mysqli_error($conexao)); 
			}
			$lastInsertId = mysqli_insert_id($conexao);

    if (isset($_POST['btnPublicarHaCapitulos']))
    {
        echo "Há capítulos";
        $capitulosNome = $_POST['capitulo'];
        $paginas = $_POST['pagina'];
        $quantidade = count($capitulosNome);
		
            // Insere cada capítulo na tabela "capitulo"
			
            $stmt = $conexao->prepare("INSERT INTO capituloLit (idLit, nomeCapitulo, paginaInicial, numCapitulo, dataCapitulo) VALUES ('$lastInsertId', ?, ?, ?, '$dataHoraAtual')");

            for ($i = 0; $i < $quantidade; $i++) {
                $nomeCapitulo = $capitulosNome[$i];
                $paginaInicial = $paginas[$i];
                $numCapitulo = $i + 1;

                $stmt->bind_param("sii", $nomeCapitulo , $paginaInicial, $numCapitulo);
                $stmt->execute();
            }
			
			$stmt->close();
        

            
    } //Há capítulos
    if (isset($_POST['btnPublicarSemCapitulos']))
    {
        echo "Não há capítulos";

    }// Não há capítulos
	
	/*Inserir as categorias*/

if (isset($_POST['checkboxCat'])) {
    $selectedCheckboxesCats = $_POST['checkboxCat'];
    foreach ($selectedCheckboxesCats as $id) {
        // Consulta SQL para obter o ID da Categoria com base no Sha1
        $queryCategoria = mysqli_query($conexao, "SELECT idCategoria FROM Categoria where sha1(idCategoria) = '$id'");
        
        if (!$queryCategoria) {
            echo "Houve um erro ao carregar as categorias: " . mysqli_error($conexao);
        } else {
            // Obtém o ID da Categoria da consulta
            $categoria = mysqli_fetch_assoc($queryCategoria);
            $idCategoria = $categoria['idCategoria'];
            
            // Consulta SQL para inserir na tabela itemCategoria
            $queryItemCategoria = "INSERT INTO itemCategoria (idCategoria, idLit) values ('$idCategoria', '$lastInsertId')";
            
            // Executa a consulta e verifica erros
            $resultadoItemCategoria = mysqli_query($conexao, $queryItemCategoria);
            
            if (!$resultadoItemCategoria) {
                echo "Houve um erro ao carregar os Itemcategorias: " . mysqli_error($conexao);
            } else {
                // A inserção foi bem-sucedida, você pode fazer algo aqui se necessário
            }
        }
    }
}

	
	/*Fim de inserir as categorias*/
	
	// Confirma a transação
            $conexao->commit();
		}catch (Exception $e) {
            // Em caso de erro, desfaz a transação
            $conexao->rollback();
            throw $e;
        }
			
        $conexao->close();
		
		$_SESSION['msgLitPubli'] = true;
		header("Location: perfil.php");
		
} //$SERVER
else
{
	header('Location: login/');
}

?>

<html>

<head>

    <title>Novo Livro|JustBookin</title>
    <meta charset="UTF-8">
    <?php include_once("b.php");?>

    </head>

<body>

</body>

</html>