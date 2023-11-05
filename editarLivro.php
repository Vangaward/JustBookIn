<?php

header('Content-Type: text/html; charset=UTF-8');

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

include_once ('rsnl.php');

include_once ('configs.php');

$idLitSha1 = $_GET['i'];
$txtSemCat = "";
$txtSemCap = "";

 $queryLit = mysqli_query($conexao, "SELECT l.idLit, l.idUsuario, l.descricao, l.titulo, l.urlCapa, u.nome
 FROM Literatura l
INNER JOIN usuario u ON l.idUsuario = u.idUsuario where sha1(idLit) = '$idLitSha1';");     
		if (!$queryLit)
		{
            echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
            die('<b>Query Inválida: </b>' . @mysqli_error($conexao));  
	    }
$dadosLit = mysqli_fetch_array($queryLit);

 $queryItemCat = mysqli_query($conexao, "SELECT c.nomeCategoria FROM itemCategoria ic
                                         INNER JOIN Categoria c ON ic.idCategoria = c.idCategoria
										 where sha1(idLit) = '$idLitSha1';");
		if (!$queryItemCat)
		{
            echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
            die('<b>Query Inválida: </b>' . @mysqli_error($conexao));  
	    }
		if(mysqli_num_rows($queryItemCat) <=0)
		{
			$txtSemCat = "Não possui categorias";
		}
		
 $queryCapLit = mysqli_query($conexao, "SELECT * from capituloLit where sha1(idLit) = '$idLitSha1';");
		if (!$queryCapLit)
		{
            echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
            die('<b>Query Inválida: </b>' . @mysqli_error($conexao));  
	    }
		if(mysqli_num_rows($queryCapLit) <=0)
		{
			$txtSemCap = "Não possui capítulos";
		}
		
		if ($dadosLit['idUsuario'] != $dadosLogin['idUsuario'])
		{
			header ('Location: login');
			die();
		}

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

<html lang="pt-br">

<head>

<title>Editar Literatura|JustBookIn</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<?php include_once('Styles/editarLivro.css'); ?>
<?php include_once('Styles/livro.css'); ?>

<body>
	
		<?php include_once('header.php');?>	
	
	
		<main class="conteudo">
		
		<?php 
$erro;
if (isset($_SESSION['msgBDExLit']))
{
	if ($_SESSION['msgBDExLit'] == 1)
	{
		$erro = "Houve um erro ao excluir a Literatura, tente novamente mais tarde.";
	}
	
	echo '<div style="position: absolute; top: 50px; animation-name: Rotacao3d; animation-duration: 1s; " class="alert alert-danger shadow"><div class="divErro">' . $erro . '</div></div>';
	unset($_SESSION['msgBDExLit']);
}


?>
		
			<?php
	
	if (mysqli_num_rows($queryLit) <=0) {die( 'Parece que a literatura não foi encontrada.<br>Talvez você tenha digitado um endereço errado ou a literatura tenha sido excluída pelo usuário.');}
	
	?>
			<div class="containerLivro">
				<div class="divCapa"><img class="imgCapa" src="<?php echo $img; ?>"></div>
				<div class="opcoesLivro">
					<div class="infoLivro">
						<label class="lblTituloLivro tituloPagina"><?php echo $dadosLit['titulo']; ?></label>
						<label class="lblAutorLivro texto"><?php echo $dadosLit['nome']; ?></label>
						<div class="divCategorias">
							<?php while($dadosItemCat=mysqli_fetch_array($queryItemCat)){ ?>
							<div class="lblNomeCategoria"><?php echo $dadosItemCat['nomeCategoria'];?></div>
							<?php } echo $txtSemCat;?>
						</div>
						<label class="lblDescricaoLivro texto"><?php echo $dadosLit['descricao']; ?></label>
					</div>
					<input type="file" value="Alterar capa">
				</div>	
			</div>
			<center>
				<div class="divCapitulos">
					<div class="headerCaps">
						<label class="lblCapsTituloHeader">Capítulos</label>
					</div> <!--headerCaps-->
					<div class="divCapCorpo">
						<?php while($dadosCapLit=mysqli_fetch_array($queryCapLit)){ ?>
						<a href="https://po.ta.to">
							<div class="capitulo">
								<label class="nomeCap"><?php echo $dadosCapLit['nomeCapitulo']; ?></label>
								<label class="dataCap"><?php echo $dadosCapLit['data']; ?></label>
							</div>
						</a>
						<?php } ?>
					</div><!--divCapCorpo-->
				</div> <!--divCapitulos-->
			</center>
			
			<a href="excluirLiteratura.php?i=<?php echo $idLitSha1; ?>">Excluir essa literatura</a>
			
		</main>
		
		<?php include_once('footer.php');?>
	</body>