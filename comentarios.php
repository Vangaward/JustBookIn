<?php

$qtdComs; //Quantidade de comentários

if ($logado == 0)
{
	$queryCom = mysqli_query($conexao, "SELECT c.idComentario, c.dataCom, c.txtComentario, u.nome from comentarios c
	INNER JOIN usuario u ON c.idUsuario = u.idUsuario
	where sha1(idLit) = '$idLitSha1' ORDER BY c.idComentario desc;");
	
	if (!$queryCom)
	{
        echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
        die('<b>Query Inválida:</b>' . @mysqli_error($conexao));  
	}
	$qtdComs = mysqli_num_rows($queryCom);
}
if ($logado == 1)
{
	$idUsuarioLogado = $dadosLogin['idUsuario'];
	$queryCom = mysqli_query($conexao, "SELECT c.idComentario, c.dataCom, c.idUsuario, c.txtComentario, u.nome from comentarios c
	INNER JOIN usuario u ON c.idUsuario = u.idUsuario
	where sha1(idLit) = '$idLitSha1' and c.idUsuario != '$idUsuarioLogado' ORDER BY c.idComentario desc;");
	
	$queryComLogado =  mysqli_query($conexao, "SELECT c.idComentario, c.dataCom, c.idUsuario, c.txtComentario, u.nome from comentarios c
	INNER JOIN usuario u ON c.idUsuario = u.idUsuario
	where sha1(idLit) = '$idLitSha1' and c.idUsuario = '$idUsuarioLogado' ORDER BY c.idComentario desc;");

	if (!$queryCom || !$queryComLogado)
	{
        echo '<input type="button" onclick="window.location='."'index.php'".';" value="Voltar"><br><br>';
        die('<b>Query Inválida:</b>' . @mysqli_error($conexao));  
	}
	$qtdComs = mysqli_num_rows($queryCom) + $qtdComs = mysqli_num_rows($queryComLogado);
}
	
	$_SESSION['idLitAtualBDexcuirCom'] = $dadosLit['idLit'];
?>

<div id="divMsgComsSup"></div><!--Essa div é necessária-->

<?php 
$erro;
$corErro;
if (isset($_SESSION['msgComent']))
{
	if ($_SESSION['msgComent'] == 1)
	{
		$erro = "Comentário publicado com sucesso";
		$corErro = "success";
	}
	if ($_SESSION['msgComent'] == 2)
	{
		$erro = "Houve um erro ao inserir o comentário, tente novamente mais tarde.";
		$corErro = "danger";
	}
	if ($_SESSION['msgComent'] == 3)
	{
		$erro = "Houve um erro ao excluir o comentário, tente novamente mais tarde.";
		$corErro = "danger";
	}
	if ($_SESSION['msgComent'] == 4)
	{
		$erro = "Comentário excluido com sucesso";
		$corErro = "warning";
	}
	
	echo '<div style="animation-name: Rotacao3d; animation-duration: 1s; animation-delay: 1.2s; " class="alert alert-' . $corErro . ' shadow"><div class="divErro">' . $erro . '</div></div>';
	unset($_SESSION['msgComent']);
}
?>

<div class="sessaoComentarios titulo">
	<div class="headerComents">
	
		<label class="tituloComents">Comentários (<?php echo ($qtdComs); ?>)</label>
		<label><?php if ($qtdComs <=0){ ?> Ninguém comentou ainda, seja o primeiro! <?php }?></label>
	</div><!--headerComents-->
	<div class="comentCorpo">
	<?php if ($logado == 1) { ?>
		<form class="comentar" name="enviaComent" action="BDcomentarioLit.php?i=<?php echo $idLitSha1; ?>" method="post">
			<!--<input class="txtComentario" type="text" name="nameTxtComent" id="inptComentId" maxlength="100" oninput="digitaComent();" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1" required>-->
			<input type="text" class="form-control txtComentario" name="nameTxtComent" id="inptComentId" maxlength="100" oninput="digitaComent();" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1" required>
			<button class="btnEnviar" name="submEnviaComent" type="submit"><?php include_once('icones/aviaoPapel.svg');?></button>
			<label id="lblQtdCaracteresId"></label>
		</form>
	<?php } else {?> <label>(<a href="login">Faça login</a> para fazer um comentário)</label> <?php } ?>
		<div class="comentarios">
		<?php if ($logado == 1) { ?>
			<?php while($dadosComLogado=mysqli_fetch_array($queryComLogado)){ ?>
			
			<div class="comentario">
				<img class="fotoPerfil" src="imagens/userPerfil.svg">
				<div class="corpoComent">
					<div>
						<span class="nomeUsuario"><?php echo $dadosComLogado['nome']; ?></span>
						<span class="dataHorario"><?php echo (new DateTime($dadosComLogado['dataCom']))->format('d/m/Y H:i'); ?></span>
					</div>
					<span class="textoComentario"><?php echo $dadosComLogado['txtComentario']; ?></span>
				</div>
				<form method="post" action="BDexcluirComentario.php?ic=<?php echo sha1($dadosComLogado['idComentario']); ?>">
					<button class="btnExcluir" type="submit" name="excluirCom"><?php include('icones/Excluir.svg');?><?php include('icones/Excluir_Hover.svg');?></button>
				</form>
				<hr>
			</div> <!--comentario-->
		<?php } } ?>
			<?php while($dadosCom=mysqli_fetch_array($queryCom)){ ?>
			<div class="comentario">
				<img class="fotoPerfil" src="imagens/userPerfil.svg">
				<div class="corpoComent">
					<div>
						<span class="nomeUsuario"><?php echo $dadosCom['nome']; ?></span>
						<span class="dataHorario"><?php echo (new DateTime($dadosCom['dataCom']))->format('d/m/Y H:i'); ?></span>
					</div>
					<span class="textoComentario"><?php echo $dadosCom['txtComentario']; ?></span>
					<hr>
				</div>
			</div> <!--comentario-->
			<?php } ?>
		</div>
	</div> <!--comentCorpo-->
</div> <!--comentarios-->

<script>
function digitaComent ()
{
		var inptComent = document.getElementById('inptComentId').value;
		var carcAtual = inptComent.length;
		var caracRest;
		
		if (carcAtual == 0) {
			caracRest = null;
		}
		else {
			caracRest = 100 - carcAtual + " Caracteres" //caracRest = caracteres restantes)
		};

		document.getElementById('lblQtdCaracteresId').innerHTML = caracRest;
}
</script>