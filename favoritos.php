<?php

include_once("conexao.php");
include_once('Vlogin.php');
include_once('rsnl.php');

 $queryLit = mysqli_query($conexao, "SELECT l.idLit, l.titulo, l.urlCapa, u.nome
 FROM Literatura l
INNER JOIN usuario u ON l.idUsuario = u.idUsuario
INNER JOIN favorito f ON f.idLit = l.idLit
where f.idLit = l.idLit");

if (!$queryLit)
		{
            echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
            die('<b>Query Inválida: </b>' . @mysqli_error($conexao));  
	    }
$qtdFavs = mysqli_num_rows($queryLit);
$txtQtdFavs = "";
if ($qtdFavs <= 0)
{
	$txtQtdFavs = "Você ainda não favoritou nenhuma Literatura.";
}
?>

<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include_once ("b.php"); ?>
<title>Favoritos | JustBookIn</title>

<head>

<body>
<?php include_once('header.php'); ?>

<label>Favoritos (<?php echo $qtdFavs; ?>)</label>
<br>

<?php while($dadosLit=mysqli_fetch_array($queryLit)){ ?>
<hr>
<?php echo $dadosLit['titulo']; ?>
<br>

<?php } echo $txtQtdFavs; ?>


</body>
</html>