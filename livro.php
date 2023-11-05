<?php 

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

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
if ($logado == 1)
{
//queryFavoritos está aqui para o front-end do botão de favoritos
$estiloBtnFavoritar;
$idUsuario = $dadosLogin['idUsuario'];
$queryFavoritos = mysqli_query($conexao, "SELECT * from favorito where sha1(idLit) = '$idLitSha1' and idUsuario = '$idUsuario';");
			if (!$queryFavoritos) {
				$_SESSION['msgFav'] = 1;
				header('Location: livro.php?i=' . $idLitSha1);
			die ("Houve um erro ao carregar os recursos necessários. #1");
				}
			if (mysqli_num_rows($queryFavoritos) > 0)
			{
				$estiloBtnFavoritar = 1;
			}else{$estiloBtnFavoritar = 0;}
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

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
		<?php include_once('b.php'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo $dadosLit['titulo']; ?>|Justbookin</title>
	</head>
	<?php include_once('Styles/livro.css');?>
	<?php include_once("Styles/fonte.css");?>
	<body>
		1<br>2<br>3<br>4<br>5<br>6<br>
		<?php include_once('header.php');?>	
		<main class="conteudo">
			<?php
				if (mysqli_num_rows($queryLit) <=0) {die( 'Parece que a literatura não foi encontrada.<br>Talvez você tenha digitado um endereço errado ou a literatura tenha sido excluída pelo usuário.');}
				if ($logado == 1)
				{
					if ($dadosLit['idUsuario'] == $dadosLogin['idUsuario'])
					{
						?>
							<button type="button" class="btn btn-warning" onclick="window.location='editarLivro.php?i=<?php echo $idLitSha1; ?>'">Editar essa literatura</button>
						<?php
					}
				}
			?>
			
			<?php 
				$erro;
				if (isset($_SESSION['msgFav']))
				{
					if ($_SESSION['msgFav'] == 1)
					{
						$erro = "Houve um erro inesperado ao tentar favoritar a literatura.";
					}
					if ($_SESSION['msgFav'] == 4)
					{
						$erro = "Houve um erro inesperado ao tentar desfavoritar a literatura.";
					}
					
					echo '<div style="animation-name: Rotacao3d; animation-duration: 1s;" class="alert alert-danger shadow"><div class="divErro">' . $erro . '</div></div>';
					unset($_SESSION['msgFav']);
				}


			?>
			
			<div class="containerLivro">
				<div class="divCapa"><img class="imgCapa" src="<?php echo $img; ?>"></div>
				<div class="opcoesLivro">
					<div class="infoLivro">
						<label class="lblTituloLivro tituloPagina"><?php echo $dadosLit['titulo']; ?></label>
						<label class="lblAutorLivro texto" onclick="window.location.href='perfil.php?i=<?php echo sha1($dadosLit['idUsuario']); ?>';">Postado por: <?php echo $dadosLit['nome']; ?></label>
						<div class="divCategorias">
							<?php while($dadosItemCat=mysqli_fetch_array($queryItemCat)){ ?>
							<div class="lblNomeCategoria"><?php echo $dadosItemCat['nomeCategoria'];?></div>
							<?php } echo $txtSemCat;?>
						</div>
						<label class="lblDescricaoLivro texto"><?php echo $dadosLit['descricao']; ?></label>
					</div>
					<div class="botoesLivro">
						<button class="btnContinuarLeitura" name="ContinuarLeitura" id="ContinuarLeituraId"><?php include_once('icones/Livro.svg'); ?>Continuar Leitura</button>
						<form action="BDfavoritos.php?i=<?php echo $idLitSha1; ?>" method="post"><button name="sbmtCurtitLit" type="submit" class="btnFavorito" name="Favorito" id="FavoritoId"><?php include('icones/Favorito.svg'); ?><span>Favoritar</span></button></form>
					</div>
					<div class="avaliacao">
						<form method="post" action="BDlike.php">
							<button name="sbmtLike" type="submit" class="btnCurtir" id="likeId"><?php include_once('icones/Gostei.svg'); ?></button>
							<button name="sbmtDeslike" class="tbnNaoCurtit " type="submit"><?php //include_once(''); ?></button>
						</form>
					</div>
				</div>	
			</div>
			
				<div class="divCapitulos">
					<div class="headerCaps">
						<label class="lblCapsTituloHeader">Capítulos</label>
					</div> <!--headerCaps-->
					<div class="divCapCorpo">
						<?php while($dadosCapLit=mysqli_fetch_array($queryCapLit)){ ?>
						<a class="nomeCapLink" href="https://po.ta.to">
							<div class="capitulo">
								<label class="nomeCap texto"><?php echo $dadosCapLit['nomeCapitulo']; ?></label>
							</div>
						</a>
						<?php } ?>
					</div><!--divCapCorpo-->
				</div> <!--divCapitulos-->
				<?php include_once('comentarios.php'); ?>
			
		</main>
		<?php include_once('footer.php');?>
	</body>
</html>

<script>

    const FavoritoId = document.getElementById('FavoritoId');
	var estiloBtnFavoritar = <?php echo $estiloBtnFavoritar; ?>;
	

        const spanElement = FavoritoId.querySelector('span');

        if (estiloBtnFavoritar == 1) {
            spanElement.innerHTML = 'Favoritado';
            FavoritoId.classList.remove("btnFavorito");
            FavoritoId.classList.add("btnFavoritado");
        } else {
            spanElement.innerHTML = 'Favoritar';
            FavoritoId.classList.remove("btnFavoritado");
            FavoritoId.classList.add("btnFavorito");
        }

</script>

<script>

    /*const likeId = document.getElementById('likeId');
	var estiloBtnLike = <?php echo $estiloBtnLike; ?>;

        const spanElement = likeId.querySelector('span');

        if (estiloBtnLike == 1) {
            spanElement.innerHTML = 'curtido';
            likeId.classList.remove("btnFavorito");
            likeId.classList.add("btnFavoritado");
        } else {
            spanElement.innerHTML = 'curtir';
            likeId.classList.remove("btnFavoritado");
            likeId.classList.add("btnFavorito");
        }*/

</script>
