<?php 

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

if ($logado==1)
{

    if (isset($_POST['publicarObraP1Form']))
    {
		
		$queryCategoria = mysqli_query($conexao, "SELECT * FROM Categoria");
		if (!$queryCategoria) {
		echo ("Houve um erro ao carregar as categorias. #1");}

        $nomeImagemCapa;

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
                $qtdPaginas = $_POST['qtdPaginas'];
				$nomeCompletoPDF = $_FILES['arquivoPdf']['name'];
				$nomeCompletoCapa = $_FILES['arquivoCapa']['name'];
                
            // Verifica se o arquivo foi enviado
            if (isset($_FILES['arquivoPdf']) && $_FILES['arquivoPdf']['error'] === UPLOAD_ERR_OK)
			{
                // Obtém a extensão do arquivo
                $extensao = pathinfo($_FILES['arquivoPdf']['name'], PATHINFO_EXTENSION);

                // Verifica se a extensão é PDF
                if ($extensao === 'pdf' && $qtdPaginas != null) //O arquivo é um PDF
                {
                    $nomeArquivoPDF = $_FILES['arquivoPdf']['name']; //para reenviar PDF pelo form
                    $caminhoTemporarioPDF = $_FILES['arquivoPdf']['tmp_name']; //para reenviar PDF pelo form
                    $dadosArquivoPDF = file_get_contents($caminhoTemporarioPDF); //para reenviar PDF pelo form

                }
                else if ($extensao === 'pdf' && $qtdPaginas == null)
                {
                    $_SESSION['publicarObraP1.phpErro'] = 3;
                    header('Location: publicarObraP1.php');
                    exit();
                }
                else //não é PDF
                {
                    $_SESSION['publicarObraP1.phpErro'] = 1;
                    header('Location: publicarObraP1.php');
                    exit();
                }
            }
             if (isset($_FILES['arquivoCapa']) && $_FILES['arquivoCapa']['error'] === UPLOAD_ERR_OK)
            {
                $nomeArquivoCapa = $_FILES['arquivoCapa']['name'];
                $caminhoTemporarioCapa = $_FILES['arquivoCapa']['tmp_name']; //para reenviar imagem pelo form
                $dadosArquivoCapa = file_get_contents($caminhoTemporarioCapa); //para reenviar imagem pelo form

                $extensao = strtolower(pathinfo($nomeArquivoCapa, PATHINFO_EXTENSION));
                $extensoesPermitidas = array('jpg', 'jpeg', 'png');

                if (!in_array($extensao, $extensoesPermitidas))
                {
                    $_SESSION['publicarObraP1.phpErro'] = 4;
                    header('Location: publicarObraP1.php');
                    exit();
                }
                $dadosCapaImgBase64 = file_get_contents($_FILES['arquivoCapa']['tmp_name']);
            }
            if ($_FILES['arquivoPdf']['error'] === 4)
            {
                $_SESSION['publicarObraP1.phpErro'] = 2;
                    header('Location: publicarObraP1.php');
                    exit();
            }
			/**//**/
			if (mb_strlen($nomeCompletoPDF) > 42 || mb_strlen($nomeCompletoCapa) > 42)
				{
					$_SESSION['publicarObraP1.phpErro'] = 5;
                    header('Location: publicarObraP1.php');
                    exit();
				}
        }

        $txtseletorCapitulos = "";
        $txtCapitulosPlural = "página";

        if ($qtdPaginas > 1)
        {
            $txtseletorCapitulos = "Dentre as $qtdPaginas páginas do seu PDF,";
            $txtCapitulosPlural = "páginas";
        }

    ?>

    <html>

    <head>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
		<title>Novo Livro|JustBookin</title>
		<meta charset="UTF-8">
		<?php include_once("b.php");?>
    </head>

<?php include_once("Styles/publicarObraP2.css");?>
<?php include_once("Styles/fonte.css");?>

    <body>

		<?php include_once("header.php");?>
		<main class="conteudo">
			<label class="tituloPagina tituloPublicarObra">PUBLICAR OBRA</label>
			<a class="texto linkVoltarPublicarObraP1" href="publicarObraP1.php"><img style="height: 25px" src="./icones/Seta_Voltar.svg">Voltar à seleção de conteúdo</a>
			<form class="formP2" action="BDpublicarObraP2.php" method="post" enctype="multipart/form-data">
				<?php
					echo '<input type="hidden" name="nomeArquivoCapa" value="' . $nomeArquivoCapa . '">';
					echo '<input type="hidden" name="dadosArquivoCapa" value="' . base64_encode($dadosArquivoCapa) . '">';
					echo '<input type="hidden" name="nomeArquivoPDF" value="' . $nomeArquivoPDF . '">';
					echo '<input type="hidden" name="dadosArquivoPDF" value="' . base64_encode($dadosArquivoPDF) . '">';
				?>
				
				<label class="texto passosPublicarObra">3. Título do livro:</label>
				<br>
				<textarea class="txtTituloLivro" name="tituloLivroName" rows="1" required></textarea>
				<br><br>
				<label class="texto passosPublicarObra">4. Descrição:</label>
				<br>
				<textarea class="txtDescricao" name="descricaoLivroName" rows="5" required></textarea>
				<br><br>
				<label class="texto passosPublicarObra">5. Gêneros:</label>
				<br>
				<div class="divCat">
					<div class="checkboxCat">
						<?php while ($dadosCategoria = mysqli_fetch_array($queryCategoria)) { ?>
						<input type="checkbox" name="checkboxCat[]" id="<?php echo sha1($dadosCategoria['idCategoria']);?>" value="<?php echo sha1($dadosCategoria['idCategoria']);?>">
						<label for="<?php echo sha1($dadosCategoria['idCategoria']);?>"><?php echo $dadosCategoria['nomeCategoria'];?></label>
						<?php }?>
					</div>
				</div>
				<br>
				<label class="texto passosPublicarObra">6. O seu livro possui <?php echo $qtdPaginas . " " . $txtCapitulosPlural;?>. Ele é dividido em capítulos?</label>
				<br>
                <br>
				<div class="radioBtn">
					<input id="radio1" type="radio" name="opcao" value="Sim" onclick="haCapitulosFunc('sim')">
					<label class="texto respostaRadio" for="radio1">Sim, meu livro está dividido em subdivisões</label>
				</div>
				<br>
				<div class="radioBtn">
					<input id="radio2" type="radio" name="opcao" value="Não" onclick="haCapitulosFunc('nao')">
					<label class="texto respostaRadio" for="radio2">Não, meu livro não possui subdivisões</label>
				</div>
				<br>
				<div class="seletorCapitulos" id="seletorCapitulosId">
					<label class="texto passosPublicarObra">7. <?php echo $txtseletorCapitulos; ?> informe qual a quantidade de capítulos/subdivisões que o seu livro tem, o nome deles e em qual página se iniciam:</label>
					<br>
					<!--<input type="number"  value="">-->
					<div class="hEditCap">
						<label for="quantidade">Quantidade de Capítulos:</label>
						<input type="number" id="quantidade" name="quantidade" min="1" readonly>
						<button type="button" id="adicionar-capitulo">Adicionar Capítulo</button>
						<button type="button" id="remover-capitulo">Remover Capítulo</button>
					</div>
					<div class="editorCapitulos texto">
						<div class="capitulos" id="capitulos-container" id="capitulosContainerId"></div>
						<br>
					</div> <!--editor de capítulos-->
					<button class="btnPublicar" type="submit" name="btnPublicarHaCapitulos"><?php include('icones/Publicar.svg'); ?>Publicar</input>
				</div>
				<br>
				<button class="btnPublicar" type="submit" name="btnPublicarSemCapitulos" id="btnPublicarSemCapitulosId" style="display: none;"><?php include('icones/Publicar.svg'); ?>Publicar</input>
			</form>
		</main>
		<div class="clearfix"></div>
		<?php include_once("footer.php");?>
	</body>

</html>

<script>

var adicionarCapituloButton = document.getElementById('adicionar-capitulo');
var removerCapituloButton = document.getElementById('remover-capitulo');
var quantidadeInput = document.getElementById('quantidade');
var capitulosContainer = document.getElementById('capitulos-container');
var capituloData = []; // Array para armazenar os dados dos capítulos
var quantidadePaginas = <?php echo $qtdPaginas; ?>; // Quantidade de páginas do PDF

// Função para adicionar um capítulo
function adicionarCapitulo() {
  var quantidade = capituloData.length + 1;

  // Cria os campos de entrada para o novo capítulo
  var inputLabel = document.createElement('label');
  inputLabel.textContent = 'Capítulo ' + quantidade + ' - Nome: ';
  inputLabel.classList.add('lblIn');

  var inputField = document.createElement('input');
  inputField.setAttribute('type', 'text');
  inputField.setAttribute('name', 'capitulo[]');
  inputField.classList.add('txtNomeCapitulo');
  inputField.setAttribute('value', 'Capítulo ' + quantidade);
  inputField.setAttribute('required', 'required');

  var paginaLabel = document.createElement('label');
  paginaLabel.textContent = 'Página Inicial:';
  paginaLabel.classList.add('lblPag');
  
  
  
  var paginaField = document.createElement('input');
  paginaField.setAttribute('type', 'number');
  paginaField.setAttribute('name', 'pagina[]');
  paginaField.setAttribute('min', '1');
  paginaField.setAttribute('max', quantidadePaginas);
  paginaField.classList.add('txtPaginaCapitulo');
  paginaField.setAttribute('required', 'required');

  var novoItem = document.createElement('hr');
	novoItem.classList.add('linhaCap');
	
  // Adiciona o event listener para validar o valor inserido
  paginaField.addEventListener('input', function() {
    var value = parseInt(paginaField.value);
    if (isNaN(value) || value < 1) {
      paginaField.value = '1';
    } else if (value > quantidadePaginas) {
      paginaField.value = quantidadePaginas.toString();
    }
  });

  // Adiciona o event listener para detectar o "Backspace" e limpar o campo se o valor for "1"
  paginaField.addEventListener('keyup', function(event) {
    var value = paginaField.value;

    if (event.keyCode === 8 && value === "1") {
      setTimeout(function() {
        paginaField.value = "";
      }, 0);
    }
  });

  capitulosContainer.appendChild(inputLabel);
  capitulosContainer.appendChild(inputField);
  capitulosContainer.appendChild(document.createElement('br'));
  capitulosContainer.appendChild(paginaLabel);
  capitulosContainer.appendChild(paginaField);
  capitulosContainer.appendChild(document.createElement('br'));
  capitulosContainer.appendChild(novoItem); // Adiciona a linha horizontal

  capituloData.push({
    nome: inputField.value,
    paginaInicial: paginaField.value
  }); // Armazena os dados do capítulo atual

  quantidadeInput.value = quantidade; // Atualiza o campo de quantidade
}

// Adicionar o capítulo inicial ao carregar a página
adicionarCapitulo();

adicionarCapituloButton.addEventListener('click', adicionarCapitulo);

removerCapituloButton.addEventListener('click', function() {
  if (capituloData.length > 1) {
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove a linha horizontal
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove a quebra de linha
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove o campo de entrada de página
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove o label de página
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove a quebra de linha
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove o campo de entrada de capítulo
    capitulosContainer.removeChild(capitulosContainer.lastChild); // Remove o label de capítulo

    capituloData.pop(); // Remove o último capítulo do array

    var quantidade = capituloData.length;
    quantidadeInput.value = quantidade; // Atualiza o campo de quantidade
  }
});

var enviarButton = document.getElementById('enviar');
enviarButton.addEventListener('click', function() {
  var formData = new FormData();

  for (var i = 0; i < capituloData.length; i++) {
    formData.append('capitulo[]', capituloData[i].nome);
    formData.append('pagina[]', capituloData[i].paginaInicial);
  }

  console.log(capituloData); // Exibe o array de capítulos no console para depurar

  // Realize a lógica para enviar os dados via AJAX ou qualquer outra abordagem desejada
  // ...
});

    </script>

    <script language="Javascript">

    var qtdCapitulos = document.getElementById("quantidade").value;
    
    function addCapitulo ()
    {
        qtdCapitulos++;
        quantidade.value = qtdCapitulos;
    }

    function valorMenorUm ()
    {

        if (qtdCapitulos < 1)
        {
            quantidade.value = 1;
        }
    }

    function haCapitulosFunc (haCapitulos)
    {
        //var haCapitulos = slctHaCapitulosId.value;
        var seletorCapitulosDiv = document.getElementById('seletorCapitulosId');
		var btnPublicarSemCapitulos = document.getElementById('btnPublicarSemCapitulosId');
        var campos = seletorCapitulosDiv.getElementsByTagName('input'); // Substitua 'input' pelo tipo de campo que você deseja desabilitar
        if (haCapitulos == "sim")
        {
			btnPublicarSemCapitulos.style.display = 'none';
            seletorCapitulosDiv.style.display = 'block';
			
            for (var i = 0; i < campos.length; i++)
            {
                campos[i].disabled = false;
            }
        }
        if (haCapitulos == "nao")
        {
            seletorCapitulosDiv.style.display = 'none';

            for (var i = 0; i < campos.length; i++)
            {
            campos[i].disabled = true;
            }

            if (haCapitulos == "nao") { btnPublicarSemCapitulos.style.display = 'flex'; }
            else {btnPublicarSemCapitulos.style.display = 'none';}
        }
    }

    </script>

    <?php

    }//if (isset($_POST['publicarObraP1Form']))
    else
    {
        header('Location: publicarObraP1.php');
        exit;
    }
}
else // Se não logado
{
    header('Location: login');
    exit;
}


?>