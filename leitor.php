<?php

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

?>

<!DOCTYPE html>
<html>
<head>
    <title>Exibir PDF</title>
    <?php include_once ("b.php"); ?>
    </head>
    <style>
        body {
            background-color: #574f4f;
        }

        .pdfViewerBorda
        {
            width: 50%;
            overflow: auto; /* Adicionado para permitir rolagem caso o PDF seja maior do que a div */
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
    <link rel="icon" type="image/png" href="../imagens/JB2.PNG">

<body>

<?php include_once("header.php");?>

    <center>
        <div class="pdfViewerBorda">
            <div id="pdfViewer"></div>
        </div>
    </center>

    <script src="/bibliotecas/build/pdf.js"></script>
    <script>
        var urlDoPDF = 'literaturasPDFS/sfps.pdf';
        var startPage = 1; // Página inicial do intervalo desejado
        var endPage = 16; // Página final do intervalo desejado

        // Carrega e exibe o PDF
        pdfjsLib.getDocument(urlDoPDF).promise.then(function (pdfDoc) {
            var pdfViewer = document.getElementById('pdfViewer');

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
                        if (pageNumber < endPage) {
                            loadPage(pageNumber + 1);
                        }
                    });
                });
            };

            loadPage(startPage);
        });
    </script>
	<?php include_once("footer.php");?>
</body>
</html>
