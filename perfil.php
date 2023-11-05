<?php

include_once ('Vlogin.php');

include_once("conexao.php");

$lad = "asc";
$meuUsuario;

if (isset($_GET['lad']))
{
	if ($_GET['lad'] == "desc")
		$lad = $_GET['lad'];
	if ($_GET['lad'] == "asc")
		$lad = $_GET['lad'];
}

if (isset($_GET['i'])) //Não estou acessando meu perfil
{
	if ($logado == 1)
	{
		if ($_GET['i'] != sha1($dadosLogin['idUsuario']))
		{
			$idUsuarioSha1 = $_GET['i'];
			$meuUsuario = false;
		}
	}
	if ($logado == 0)
	{
		$idUsuarioSha1 = $_GET['i'];
		$meuUsuario = false;
	}
}
if ($logado == 1)
{
	if ($_GET['i'] == sha1($dadosLogin['idUsuario'])) //estou acessando meu perfil com o meu id na url
	{
		$idUsuarioSha1 = sha1($dadosLogin['idUsuario']);
		$meuUsuario = true;
	}
	if (!isset($_GET['i']))
	{
		$idUsuarioSha1 = sha1($dadosLogin['idUsuario']);//estou acessando meu perfil sem o meu id na url
		$meuUsuario = true;
	}
}

$queryUsuario = mysqli_query($conexao, "SELECT * FROM usuario WHERE sha1(idUsuario) = '$idUsuarioSha1'");

if (!$queryUsuario)
{
	echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
	die('<b>Query Inválida:</b>');  
}

 $queryLit = mysqli_query($conexao, "SELECT l.idLit, l.titulo, l.urlCapa, u.nome
 FROM Literatura l
INNER JOIN usuario u ON l.idUsuario = u.idUsuario where sha1(l.idUsuario) = '$idUsuarioSha1' order by l.idLit $lad;
");
 $queryLitPerfil = mysqli_query($conexao, "SELECT u.nome, u.urlFotoPerfil
 FROM Literatura l
INNER JOIN usuario u ON l.idUsuario = u.idUsuario where sha1(l.idUsuario) = '$idUsuarioSha1' order by l.idLit $lad;
");
if (!$queryLit || !$queryLitPerfil)
{
	echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
	die('<b>Query Inválida:</b>');  
}
		
$qtdlits = mysqli_num_rows($queryLit);

$dadosLitPerfil=mysqli_fetch_array($queryLitPerfil);

?>

<!doctype HTML>
<html lang="pt-br">

<head>
<title>Perfil</title>
<link rel="icon" type="image/png" href="../imagens/JB2.PNG">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
</head>

<?php include_once("Styles/perfil.css");?>


<body>
<?php
if (mysqli_num_rows($queryUsuario) < 1)
{
	include_once('header.php');
	?>
	
	<label style="font-size: 35px;">O perfil do usuário não foi encontrado</label><br>
	<?php if ($logado == 0) { ?><label style="font-size: 20px;">Se você está tentando acessar o seu perfil, <a href="login">faça login</a>.</label><?php } ?>
	
	<?php include_once('footer.php'); die();
}
?>

<div class="image-container">
<img class="Banner" src="imagens/Banner.svg">
</div>

<?php

$urlFotoPerfil;
if ($dadosLitPerfil['urlFotoPerfil'] == "")
{ $urlFotoPerfil = "imagens/userPerfil.svg";}else{$urlFotoPerfil = 'fotosPerfil/' . $dadosLitPerfil['urlFotoPerfil'];}

?>

<img class="imgPerfil" src="<?php echo $urlFotoPerfil; ?>">

<label class="nomeUsuario"><?php echo $dadosLitPerfil['nome'];?> <?php if ($meuUsuario == true) { ?>(Seu perfil)<?php } ?></label>
<div class="statsUser">
<label class="NumInscritos">0 incritos</label>
<label class="NumObras"><?php echo $qtdlits;?> Obras</label>
</div>
<?php include_once('header.php');?>
<?php
if (isset($_SESSION['perfilMsg']))
{
        $msgErro="";
        if ($_SESSION['perfilMsg'] == 1)
        {
            $msgErro = "A extensão do arquivo não é permitida, selecione apenas arquivos .jpg, .jpeg ou .png";
        }
		if ($_SESSION['perfilMsg'] == 2)
        {
            $msgErro = "O nome do arquivo não deve conter mais de 41 caractes!";
        }
		if ($_SESSION['perfilMsg'] == 3)
        {
            $msgErro = "Pedimos desculpas, houve um erro da nossa parte, por favor, teve novamente!";
        }

        echo "<div style='animation-name: Rotacao3d; animation-duration: 1s; ' class='alert alert-danger' role='alert'><center>$msgErro</center></div>";

    unset($_SESSION['perfilMsg']);
}
?>

<!--Poupop lit publicada com sucesso-->
<?php if (isset($_SESSION['msgLitPubli'])) { if($_SESSION['msgLitPubli'] == true) { ?>
<div id="popup-container">
        <div id="popup">
            <h2>Publicado com sucesso!</h2>
            <p>Sua literatura foi publicada com sucesso</p>
            <button onclick="fecharPopup()" class="btnOkPopUp">OK</button>
        </div>
    </div>
	
<?php $_SESSION['msgLitPubli'] = false; } } ?>

<!--Poupop lit excluída com sucesso-->
<?php if (isset($_SESSION['msgBDExLit'])) { if($_SESSION['msgBDExLit'] == 2) { ?>
<div id="popup-container">
        <div id="popup">
            <h2>Excluído com sucesso!</h2>
            <p>Sua literatura foi excluída com sucesso</p>
            <button onclick="fecharPopup()" class="btnOkPopUp">OK</button>
        </div>
    </div>
	
<?php unset($_SESSION['msgBDExLit']); } } ?>

<?php if ($meuUsuario) { ?>
<form name="frmFotoPerfil" id="frmFotoPerfilId" method="post" action="BDFotoPerfil.php" enctype="multipart/form-data">
<input type="file" id="arquivoFotoPerfilId" name="arquivoFotoPerfil" onchange="enviarFormulario()" accept="image/*"><?php if ($dadosLogin['urlFotoPerfil'] != ""){ ?><button type="submit" name="excluFoto">Excluir foto de perfil</button><?php } ?>
</form>
<?php } ?>

<?php if ($meuUsuario == false) { ?><button>Inscrever-se</button><?php } ?>

<div class="containerFlex">
<br>
<label class="lblObras"><?php if ($meuUsuario == true) { ?>SUAS OBRAS:<?php } else { ?> OBRAS DE <?php echo $dadosLitPerfil['nome'] . ":"; } ?></label>
<br>
<!--Dropbox-->
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
    </style>
    <script>
        function toggleDropdown() {
            var dropdownContent = document.getElementById("dropdown-content");
            if (dropdownContent.style.display === "none" || dropdownContent.style.display === "") {
                dropdownContent.style.display = "block";
            } else {
                dropdownContent.style.display = "none";
            }
        }
    </script>

<div class="dropdown">
    <button class="filtro" onclick="toggleDropdown()"><?php include_once('icones/Funnel.svg'); ?></button>
    <div class="dropdown-content" id="dropdown-content">
        <a href="#" onclick="ordemAsc()">Crescente</a>
        <a href="#" onclick="ordemDesc()">Decrescente</a>
    </div>
</div>
<!--Fim de Dropbox-->
</div>
<div id="containerObrasId" class="containerObras">

<?php

if ($qtdlits <= 0)
{ ?>
	<label><h2>Você ainda não publicou nenhuma literatura.</h2></label>
<?php } ?>

<div class="row">

<?php include_once('Styles/card.css');?>

<?php
	while($dadosLit=mysqli_fetch_array($queryLit)){
?>

<?php
if ($dadosLit['urlCapa'] == "")
{
	$img = "imagens/batata.png";
}
else
{
	$img = "imagensCapa/" . $dadosLit['urlCapa'];
}

?>
<div class="col-6 col-sm-4 col-md-3 col-lg-2">
<a href="livro.php?i=<?php echo sha1($dadosLit['idLit']);?>">
<div class="divDivisor">
<div class="cardLivro">

<image class="imgCard" src="<?php echo $img; ?>">
<label class="tituloCard"><?php echo $dadosLit['titulo']; ?>
<br>
<?php echo 'Postado por: ' . $dadosLit['nome']; ?></label>

</div>
</div>
</a>
</div>
<?php } ?>

</div><!--row-->

</div> <!--containerObras-->

<?php include_once('footer.php'); ?>

</body>

</html>

<script>
        function fecharPopup() {
            var popupContainer = document.getElementById("popup-container");
            popupContainer.style.display = "none"; // Oculta o container do popup
        }
    </script>
	
	
<script language="Javascript">

function ordemDesc ()
{
	 window.location.href = 'perfil.php?i=<?php echo $idUsuarioSha1; ?>&lad=desc#containerObrasId'; //lad = Literaturas - Asc ou Desc
}
function ordemAsc ()
{
	 window.location.href = 'perfil.php?i=<?php echo $idUsuarioSha1; ?>&lad=asc#containerObrasId';
}

</script>
<script>
$(window).on('scroll', function() {
    let scrollTop = $(this).scrollTop();

    // Mova o banner a uma velocidade um pouco mais rápida que a rolagem normal
    $('.Banner').css('transform', 'translate3d(0,' + (scrollTop * 0.5) + 'px, 0)');
});
</script>

<script>
    function enviarFormulario() {
        // Obtém o formulário pelo ID
        var formulario = document.getElementById('frmFotoPerfilId');
		var inputArquivoPerfil = document.getElementById('arquivoFotoPerfilId');
		var tamanhoMaximoNome = 41;
		// Verificar o campo de capa
            if (inputArquivoPerfil.files[0]) {
                var nomeArquivoPerfil = inputArquivoPerfil.files[0].name;
                // Verificar o tamanho do nome
                if (nomeArquivoPerfil.length > tamanhoMaximoNome) {
                    alert('O nome do arquivo da foto de perfil é muito grande. Por favor, escolha um nome de arquivo mais curto. (Máximo de 41 caracteres)');
					window.location.reload();
				}
				else
				{
					// Envia o formulário
					formulario.submit();
				}
					
            }
		
    }
</script>