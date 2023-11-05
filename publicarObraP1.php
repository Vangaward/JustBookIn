<?php 

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

if ($logado==1)
{

?>

<html>

<head>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">
	  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
<title>Novo Livro|JustBookin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<?php include_once("b.php");?>

</head>

<?php include_once("Styles/publicarObraP1.css");?>
<?php include_once("Styles/fonte.css");?>

<style>
.pdfViewerBorda
        {
            width: 50%;
            overflow: auto; /* Adicionado para permitir rolagem caso o PDF seja maior do que a div */
            height: 300px;
            width: 200px;

        }

        .pdfPage {
            display: block;
            margin-bottom: 20px;
            border: 1px solid black; /* Adicionado para a borda individual */
            padding: 5px; /* Adicionado para espaço interno da borda */
        }

        .pdfPage canvas {
            max-width: 100%; /* Adicionado para garantir que o canvas não exceda a largura da div */
            height: auto; /* Adicionado para manter a proporção do PDF */
        }

</style>

<body>

<?php include_once("header.php");?>
<main class="conteudo">
<?php
if (isset($_SESSION['publicarObraP1.phpErro']))
{
        $msgErro="";
        if ($_SESSION['publicarObraP1.phpErro'] == 1)
        {
            $msgErro = "O arquivo selecionado não é um PDF, por favor, selecione apenas arquivos em formato PDF!";
        }
        if ($_SESSION['publicarObraP1.phpErro'] == 2)
        {
            $msgErro = "Faltam arquivos, anexe o PDF e a Capa e tente novamente!";
        }
        if ($_SESSION['publicarObraP1.phpErro'] == 3)
        {
            $msgErro = "Houve um erro da nossa parte, por favor, tente novamente.";
        }
        if ($_SESSION['publicarObraP1.phpErro'] == 4)
        {
            $msgErro = "O arquivo da capa não é válido, selecione apenas arquivos .jpg, .jpeg, ou .png !";
        }
		if ($_SESSION['publicarObraP1.phpErro'] == 5)
        {
            $msgErro = "O nome de um dos arquivos é muito grande para o upload.";
        }

        echo "<div style='animation-name: Rotacao3d; animation-duration: 1s; ' class='alert alert-danger' role='alert'><center>$msgErro</center></div>";

    unset($_SESSION['publicarObraP1.phpErro']);
}

?>
<label class="tituloPagina">PUBLICAR OBRA</label>
<br>
<form method="POST" action="publicarObraP2.php" enctype="multipart/form-data">
<label class="texto">1. Selecione o conteúdo (PDF):</label>
<br>
  <div class="input-wrapper">
<div class="pdfViewerBorda">

            <div id="pdfViewer"></div>
        </div>
        </div>
        <br>
    <div class="input-wrapper">
    <label class="arquivo" for="arquivoPdfId">Enviar pdf</label>
    </div>
	<label class="texto">2. Selecione a imagem da capa:</label>
    <input type="file" id="arquivoPdfId" name="arquivoPdf" placeholder="Anexar PDF" onchange="carregarPDF(); validarEnvio();" accept="application/pdf" required>

<br>
 <div class="input-wrapper">
<div class="pdfViewerBorda">
<div class="imgCard" id="CapaViewer"></div>
</div>
</div>
<br>
    <div class="input-wrapper">
    <label class="arquivo" for="capaId">Enviar imagem da capa</label>
</div>
<input id="capaId" type="file" name="arquivoCapa" placeholder="Anexar Capa" onchange="exibirImagem(event); validarEnvio();" accept="image/*"><br>
<input  type="hidden" name="qtdPaginas" id="numeroPaginasInput">
<div class="input-wrapper">
<button class="btnPro" name="publicarObraP1Form" id="publicarObraP1FormId"><?php include_once('icones/Seta_Avancar.svg'); ?>Próximo</button>
</div>
</form>
</main>
<?php include_once("footer.php");?>

</body>

</html>

<script src="/bibliotecas/build/pdf.js"></script>

<script>
	var btnProximo = document.getElementById('publicarObraP1FormId');
	var qtdPaginas = document.getElementById('numeroPaginasInput');
        function carregarPDF() {
			btnProximo.disabled = true;
			btnProximo.value = "Aguarde...";
			
			//Contar páginas
			var fileInput = document.getElementById('arquivoPdfId');
			var pdfFile = fileInput.files[0];
			if (pdfFile) {
				var pdfFileURL = URL.createObjectURL(pdfFile);

				pdfjsLib.getDocument(pdfFileURL).promise.then(function (pdfDoc) {
					var numPages = pdfDoc.numPages;
					qtdPaginas.value=numPages;
				});
			}
			//Carregar pré-visualisação
            var fileInput = document.getElementById('arquivoPdfId');
            var pdfFile = fileInput.files[0];

            if (pdfFile) {
                var pdfViewer = document.getElementById('pdfViewer');
                pdfViewer.innerHTML = ''; // Limpa o conteúdo anterior, se houver

                var pdfFileURL = URL.createObjectURL(pdfFile);

                pdfjsLib.getDocument(pdfFileURL).promise.then(function (pdfDoc) {
                    var numPages = pdfDoc.numPages;

                    var loadPage = function (pageNumber) {
                        pdfDoc.getPage(pageNumber).then(function (page) {
                            var scale = 1.5;
                            var viewport = page.getViewport({ scale: scale });

                            var canvas = document.createElement('canvas');
                            var context = canvas.getContext('2d');
                            canvas.width = viewport.width;
                            canvas.height = viewport.height;

                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };

                            page.render(renderContext).promise.then(function () {
                                var pageDiv = document.createElement('div');
                                pageDiv.classList.add('pdfPage'); // Adiciona a classe pdfPage
                                pageDiv.appendChild(canvas);
                                pdfViewer.appendChild(pageDiv);

                                // Verifica se ainda há páginas para carregar
                                if (pageNumber < numPages) {
                                    loadPage(pageNumber + 1);
                                }
                            });
                        });
                    };

                    loadPage(1);
                });
            }
			var myTimeout = setTimeout(reabilitaBtnProximo, 3000);
        }
		
		function reabilitaBtnProximo ()
    {
        btnProximo.disabled = false;
        btnProximo.value = "Próximo >";
    }
	
    </script>
	
	<script language="Javascript">
		function exibirImagem(event) {
		  var input = event.target;
		  var reader = new FileReader();

		  reader.onload = function() {
			var imagem = document.createElement("img");
			imagem.src = reader.result;
			document.getElementById("CapaViewer").innerHTML = "";
			document.getElementById("CapaViewer").appendChild(imagem);
		  };

		  reader.readAsDataURL(input.files[0]);
		}
</script>

<script>
var btnProximo = document.getElementById('publicarObraP1FormId');
        function validarEnvio() {
            var inputArquivoCapa = document.getElementById('capaId');
            var inputArquivoPdf = document.getElementById('arquivoPdfId');
            var tamanhoMaximoNome = 42;
            
            // Verificar o campo de capa
            if (inputArquivoCapa.files[0]) {
                var nomeArquivoCapa = inputArquivoCapa.files[0].name;
                // Verificar o tamanho do nome
                if (nomeArquivoCapa.length > tamanhoMaximoNome) {
                    alert('O nome do arquivo da capa é muito grande. Por favor, escolha um nome de arquivo mais curto. (Máximo de 42 caracteres)');
                    btnProximo.disabled = true;
					pdfViewer.innerHTML = '';
					location.reload();
                }
            }

            // Verificar o campo de PDF
            if (inputArquivoPdf.files[0]) {
                var nomeArquivoPdf = inputArquivoPdf.files[0].name;
                // Verificar o tamanho do nome
                if (nomeArquivoPdf.length > tamanhoMaximoNome) {
                    alert('O nome do arquivo PDF é muito grande. Por favor, escolha um nome de arquivo mais curto.');
                    btnProximo.disabled = true;
					pdfViewer.innerHTML = '';
					location.reload();
                   
                }
            }
            
            return true; // Permite o envio do formulário
        }
    </script>

<?php

}
else // Se não logado
{
    header('Location: login');
    exit;
}


?>