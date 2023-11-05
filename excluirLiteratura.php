<?php

header('Content-Type: text/html; charset=UTF-8');

include_once ('conexao.php');

include_once ('Vlogin.php');

include_once ('Vcli.php');

include_once ('rsnl.php');

$idLitSha1 = $_GET['i'];

$queryLit = mysqli_query($conexao, "SELECT l.idLit, l.idUsuario, l.descricao, l.titulo, l.urlCapa, u.nome
 FROM Literatura l
INNER JOIN usuario u ON l.idUsuario = u.idUsuario where sha1(idLit) = '$idLitSha1';");     
		if (!$queryLit)
		{
            echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
            die('<b>Query Inválida: </b>' . @mysqli_error($conexao));  
	    }
$dadosLit = mysqli_fetch_array($queryLit);

if ($dadosLit['idUsuario'] != $dadosLogin['idUsuario'])
		{
			header ('Location: login');
			die();
		}

?>

<html lang="pt-br">

<head>

<title>Excluir Literatura|JustBookIn</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include_once('b.php'); ?>

</head>
<?php include_once('Styles/excluirLiteratura.css'); ?>
<body>

<?php 
$erro;
if (isset($_SESSION['msgBDExLit']))
{
	if ($_SESSION['msgBDExLit'] == 3)
	{
		$erro = "Senha incorreta! Tente novamente.";
	}
	
	echo '<div style="animation-name: Rotacao3d; animation-duration: 1s; " class="alert alert-danger shadow"><div class="divErro">' . $erro . '</div></div>';
	unset($_SESSION['msgBDExLit']);
}


?>

<form action="BDexcluirLiteratura.php?i=<?php echo $idLitSha1; ?>" method="post">
<div class="container">

<label>Você está prestes a excluir PERMANENTEMENTE sua literatura</label>
<br>
<label>(<?php echo $dadosLit['titulo']; ?>)</label>
<br>
<label>Digite sua senha para confirmar a exclusão:</label>
<br>
<input type="password" name="senhaName" id="senhaid" required><input type="button" class="btnVis" text="Exibir senha" value="&#128065;" onclick="verSenha()">
<br>
<input type="submit" name="excLitName" value="confirmar exclusão">

</div>

</body>

</html>

<script language="Javascript">

    function verSenha ()
    {
        var senha1 = document.getElementById("senhaid");
        
        if (senha1.type == "password")
        {
            senhaid.type = ("text");
        }
        else
        {
            senhaid.type = ("password");
        }
    }
    
</script>