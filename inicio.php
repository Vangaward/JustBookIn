<?php

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

$splashScreen = true;

if (!isset($_GET['splash']))
{
    $splashScreen = false;//não mecher aqui
}

 $queryLit = mysqli_query($conexao, "SELECT l.idLit, l.titulo, l.urlCapa, u.nome
 FROM Literatura l
INNER JOIN usuario u ON l.idUsuario = u.idUsuario;
");
        
		if (!$queryLit)
		{
            echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
            die('<b>Query Inválida:</b>');  
	    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once ("b.php"); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
    <link rel="icon" type="image/png" href="../imagens/JB2.PNG">
    <title>JustBookIn</title>
</head>

<?php include_once("Styles/inicio.css");?>
<?php include_once ("Styles/fonte.css");?>
<?php include_once("Styles/card.css");?>

<body id="bodyInicio">
	<!--Área de Include-->

	<?php include_once("splashScreen.php");?>

	<!--Fim da área-->
	<!--/*/*/*/*/*/*/*/*/*/*/*/*/*/**/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/**/*/*/*/*/*/*/*/*/*/*/*-->
	<?php include_once("header.php");?>
		
	<main class="conteudo">
		<div class="inicio" id="inicioId">
			<div class="input-group mb-3">
				<input type="text" class="form-control" placeholder="Coma queijo Gorgonzola, é uma Ordem!" aria-label="Recipient's username" aria-describedby="button-addon2">
				<button class="btn btn-outline-secondary" type="button" id="button-addon2"><?php include_once('icones/Pesquisar.svg'); ?></button>
			</div>
			<label>Top 50:</label>
			<div id="idTopLits" class="topLits">
			<?php while($dadosLit=mysqli_fetch_array($queryLit)){ ?>

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
			<a href="livro.php?i=<?php echo sha1($dadosLit['idLit']);?>">
			<div class="divDivisor">
				<div class="cardLivro">
					<img class="imgCard" src="<?php echo $img; ?>">
					<div class="overlay" style="background-image: url(<?php echo $img; ?>)"></div>
					<label class="tituloCard">
						<?php echo $dadosLit['titulo']; ?>
						<br>
						<?php echo 'Postado por: ' . $dadosLit['nome']; ?>
					</label>
				</div>
			</a>
		</div> <!--divDivisor-->
		<?php } ?>
	</div><!--topLits-->
	</main>
	<div class="clearfix"></div>
	<?php include_once("footer.php"); ?>
	</div> <!--inicio-->
</body>
</html>

<script src="bibliotecas/jquery.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  // Seu código JavaScript aqui

var scrollContainer = document.querySelector('.divDivisor');

scrollContainer.addEventListener('mousemove', function (e) {
  if (e.buttons === 1) { // Verifica se o botão do mouse está pressionado (botão esquerdo)
    scrollContainer.scrollLeft += e.movementX; // Move a barra de rolagem horizontal com o movimento do mouse
  }
});});
</script>
<script>
document.getElementById('idTopLits').addEventListener('wheel', function(event) {
    if (event.deltaY !== 0) {
        // Se a roda do mouse rolar verticalmente, não faz nada
        return;
    }

    // Se a roda do mouse rolar horizontalmente
    this.scrollLeft += event.deltaX;
    event.preventDefault();
});
</script>