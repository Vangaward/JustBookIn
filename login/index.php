<?php

//Sessão
session_start();

include_once('../conexao.php');


date_default_timezone_set('America/Sao_Paulo');

if (isset($_SESSION['logado'])) // Se o usuário tentar acessar a página de login mas não estiver logado
{
    //O mesmo bloco de código que está no arquivo Vlogin.php foi reescrito aqui ao invés de incluir o Vlogin.php para evitra bugs.
    $idUsuarioSessao = $_SESSION['idUsuario'];
	$sql = "SELECT * FROM usuario WHERE idUsuario = '$idUsuarioSessao'";
	$resultado = mysqli_query($conexao, $sql);
	$dadosLogin = mysqli_fetch_array($resultado);
//----------------------------------------------------

/*
1 - Adms
2 - Editoras
3 - Usuário padrão
*/
header('Location: ../inicio.php');
}
//$tipoConta = $_POST['nomeUsuario']; Em análise
/*if (isset($_SESSION['mostrarErro']))
{
	echo '<script> alert(' . $_SESSION['mostrarErro'] . ');</script>';
}*/
if(isset($_POST['btnEntrar']))
{
	$_SESSION['mostrarErro'] = 1;
	
	if (md5($_POST['email']) == '2b9ff3efc4a999ecfacd18c4bbc57a2e')
    {
        $_SESSION['j1407b'] = 't0';
        $_SESSION['j1407bKey'] = true;
        header('Location: ../j1407b.php');
		die();
    }
	
	$email = mysqli_escape_string($conexao, $_POST['email']); //Campo do email
	$senha = mysqli_escape_string($conexao, $_POST['senha']); //Campo da senha
    //$tipoConta = $_POST['tipoConta'];
	
	if (empty($email) or empty($senha)) //Veriicar se os campos estão vazios
	{
		$_SESSION['telaLoginErro'] = "<li>Os campos de E-mail e senha precisam ser preenchidos</li>";
	}
	else //verificando se o e-mail digitado exite
	{
		$sql = "SELECT email FROM usuario WHERE email = '$email'"; //Procurando por email
		$resultado = mysqli_query($conexao, $sql); //Resultado de quantas linhas foram encontradas para o e-mail digitado
		if (mysqli_num_rows($resultado) > 0) //Se o email for encontrado
		{
			$senha = sha1($senha); //criptografando a senha
			$sql = "SELECT * FROM usuario  WHERE email = '$email' AND senha = '$senha'"; //Procurando qual é a senha do e-mail encontrado
			$resultado = mysqli_query($conexao, $sql);
			
			if (mysqli_num_rows($resultado) == 1) //Se a senha for encontrada e ela for igual a senha digitada
			{
				$dadosLogin = mysqli_fetch_array($resultado); //Converte resultado em array e atrubui para $dadosLogin
                if ($dadosLogin['statusConta'] == 0) //Se a conta não tiver sido validada
                {
                    $EmailUsuarioSha1 = sha1($dadosLogin['email']);
                    die('<center><h1>Essa conta não foi validada e não é possível fazer login.</h1>Para validar sua conta <a href="verificaEmail.php?v=0&id=0&email=' . $EmailUsuarioSha1 . '">clique aqui</a></center>');
                }
                else
                {
                    
				    $_SESSION['logado'] = true;
                    $_SESSION['idUsuario'] = $dadosLogin['idUsuario'];
                    $idUsuario =  $dadosLogin['idUsuario'];

                    /* HISTÓRICO DE LOGIN
                    $data = date("Y-m-d H:i:s");
                    $sqlinsert =  "insert into hisLogin (idUsuario, data) values ('$idUsuario', '$data')";
                    $resultado = @mysqli_query($conexao, $sqlinsert);
                    if (!$resultado)
                    {
                        echo '<input type="button" onclick="window.location='."'../inicio.php'".';" value="Voltar"><br><br>';
                        die('<b>Query Inválida:</b>' . @mysqli_error($conexao)); 
                    }
                    */
                        if ($dadosLogin['tipoConta'] == 3)
                        {
                            header('Location: ../inicio.php');
                        }
                        if ($dadosLogin['tipoConta'] == 2)
                        {
                            die("Você é uma editora");
                        }
                        if ($dadosLogin['tipoConta'] == 1)
                        {
                            die("Você é um ADM SUPREMO");
                        }
                }
			}
			else //Se a senha for encontrada e ela for diferente da senha digitada
			{
				$_SESSION['telaLoginErro'] = "<li>E-mail ou senha inválidos</li>";
			}
		}
		else //Se o email não for encontrado
		{
			$_SESSION['telaLoginErro'] = "<li>E-mail ou senha inválidos</li>";
		}
	}
	
} //if btn entrar
?>

<?php

  // Verificar qual formulário foi enviado
  $redirFunc;
  if (isset($_POST['btnGoLogin']))
  {
      $_SESSION['$currentLocationPHP'] = 2;
  }
  if (isset($_POST['btnGoCriarConta']))
  {
      $_SESSION['$currentLocationPHP'] = 3;
  }
  if (isset($_POST['goEsqueciSenha']))
  {
      $_SESSION['$currentLocationPHP'] = 4;
  }
      ?>






<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php include_once ("b.php"); ?>
    <title>Login|JustBookIn</title>
    <link rel="stylesheet" href="style.css">
     <link rel="icon" type="image/png" href="../imagens/JB2.PNG">
    <script src="https://kit.fontawesome.com/b0f29e9bfe.js" crossorigin="anonymous"></script>
</head>

<style><?php include_once('style.css');?></style>

<?php include_once('../Styles/login.css');?>

<body id="bodyId">

<?php 

//echo '<script>alert("antes do coiso: ' . $_SESSION['mostrarErro'] . '")</script>';

if (isset($_SESSION['telaLoginErro']) and $_SESSION['mostrarErro'] == 1)
{
	echo '<div style="position: absolute; top: 50px; animation-name: Rotacao3d; animation-duration: 1s; z-index: 1000;" class="alert alert-danger shadow"><div class="divErro">' . $_SESSION['telaLoginErro'] . '</div></div>';
	unset($_SESSION['telaLoginErro']);
	$_SESSION['mostrarErro'] = 0;
}
?>

 <div class="capa" id="capa">
 
     
 </div><!--div capa-->
 <center>
<!-- Previous Button -->

   <button style="display: none;" id="prev-btn" class="btnEsquerdo">
        <i class="fas fa-arrow-circle-left"></i>
    </button>

    <!-- Book -->
    <div id="book" class="book">
        <!-- Paper 1 -->
        <div id="p1" class="paper">

            <div class="front">
                <div id="f1" class="front-content">
                    <img  src="../imagens/Justbookin2.PNG" style="width: 320px; height: 60px; flex-shrink: 0;  cursor: pointer;">
                </div>
            </div>

            <div class="back">
                <div id="b1" class="back-content">
               <label class="txtLogo"> Bem-Vindo à</label>
                <img  src="../imagens/Justbookin.png" style="width: 320px; height: 60px; flex-shrink: 0;  cursor: pointer;" onclick="location.href='../inicio.php'">
                <button class="btnVoltar" onclick="location.href='../inicio.php'">Voltar</button>
                </div>
            </div>
        </div>
        
        <!-- Paper 2 -->
        <div id="p2" class="paper">
            <div class="front">
                <div id="f2" class="front-content">
                    
                    <!--Login-->

        <form name="frmLogin" id="frmLogin" action="" method="post">
        <h1 class="titulo">Entrar</h1>
        <div class="divLogin">
            
            <label class="lblEmail"  value="">E-mail:</label><br>
            <input class="txtEmail type="text" placeholder="Exemplo@gmail.com" name="email" id="Email"><br>
            <label class="lblSenha">Senha:</label><input type="button" class="btnVis" text="Exibir senha" value="&#128065;" onclick="verSenha()">
            <br>
            <input class="txtSenha" type="password" placeholder="********" name="senha" id="senhaid"><input type="button" value="Esqueci a senha" class="btnEsqueciSenha" onclick="goEsqueciSenha()">
            <br>
            <div class="container">
            <input class="btnCadastrar" type="button" value="Fazer Cadastro" id="cadastrar" name="btnCadastrar" onclick="location.href='criarConta.php'">
            <input class="btnEntrar" type="submit" value="Entrar" id="entrar" name="btnEntrar">
            </div>

            </div>
            </form>
<!--Fim Login-->

                </div>
            </div>
            <div class="back">
                <div id="b2" class="back-content">
                      <!--Esqueci a senha-->
                    <form name="frmRe" action="" method="post">
        <h1 class="titulo">Redefinir senha</h1>
        <div class="divLogin">
            
            <label class="lblEmail">Insira o seu E-mail:</label><br>
            <input class="txtEmail" placeholder="Exemplo@gmail.com" type="text" name="email" id="Email"><br>
            <br>
            <div class="container">
            <input class="btnCadastrar" type="button" value="Voltar" id="cadastrar" name="btnCadastrar" onclick="goLogin()">
            <input class="btnEntrar" type="submit" value="Prosseguir" id="entrar" name="btnEntrar">
            </div>

            </div>
            </form>
                </div>
            </div>
        </div>
        <!-- Paper 3 -->
        <div id="p3" class="paper">
            <div class="front">
                <div id="f3" class="front-content">
                <label class="txtLogo"> Bem-Vindo à</label>
                <img  src="../imagens/Justbookin.png" style="width: 320px; height: 60px; flex-shrink: 0;  cursor: pointer;" onclick="location.href='../inicio.php'">
             
                </div>
            </div>
            <div class="back">
                <div id="b3" class="back-content">
                    <h1>Back 3</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Button -->
   <button style="display: none;" id="next-btn" class="btnDireito">
        <i class="fas fa-arrow-circle-right"></i>
    </button>



</center>

  <div class="novo-codigo">
  
   <img class="espiral" src="../imagens/Espiral.png" alt="Imagem de Cabeçalho">
   
        <div class="input-container">
            <div>
                   <form name="frmLogin" action="" method="post">
        <h1 class="titulo">Entrar</h1>
        <div class="divLogin">
            <label class="lblEmail"  value="">E-mail:</label>
            <input class="txtEmail type="text" placeholder="Exemplo@gmail.com" name="email" id="Email">
            <label class="lblSenha">Senha:</label><input type="button" class="btnVis" text="Exibir senha" value="&#128065;" onclick="verSenha()">
            <input class="txtSenha" type="password" placeholder="********" name="senha" id="senhaid">
            <input type="button" value="Esqueci a senha" class="btnEsqueciSenha2" onclick="location.href='../esqueciSenha.php'"
            <div class="container">
            <input class="btnCadastrar" type="button" value="Fazer Cadastro" id="cadastrar" name="btnCadastrar" onclick="location.href='criarConta.php'">
            <input class="btnEntrar" type="submit" value="Entrar" id="entrar" name="btnEntrar">
            </div>
            </div>
            </form>
            </div>
        </div>
    </div>
</body>
</html>

<script src="../bibliotecas/jquery.js"></script>
<script defer>// References to DOM Elements

var capa = document.getElementById("capa");

const prevBtn = document.querySelector("#prev-btn");
const nextBtn = document.querySelector("#next-btn");
const book = document.querySelector("#book");

const paper1 = document.querySelector("#p1");
const paper2 = document.querySelector("#p2");
const paper3 = document.querySelector("#p3");

// Event Listener
prevBtn.addEventListener("click", goPrevPage);
nextBtn.addEventListener("click", goNextPage);

// Business Logic
let currentLocation = 1;
let numOfPapers = 3;
let maxLocation = numOfPapers + 1;

function openBook() {
    book.style.transform = "translateX(50%)";
    prevBtn.style.transform = "translateX(-180px)";
    nextBtn.style.transform = "translateX(180px)";
}

function closeBook(isAtBeginning) {
    if(isAtBeginning) {
        book.style.transform = "translateX(0%)";
    } else {
        book.style.transform = "translateX(100%)";
    }
    
    prevBtn.style.transform = "translateX(0px)";
    nextBtn.style.transform = "translateX(0px)";
}

function goNextPage() {
    if(currentLocation < maxLocation) {
        switch(currentLocation) {
            case 1:
                openBook();
                paper1.classList.add("flipped");
                paper1.style.zIndex = 1;
                break;
            case 2:
                paper2.classList.add("flipped");
                paper2.style.zIndex = 2;
                break;
            case 3:
            capa.classList.add("fechar-animacao");
                paper3.classList.add("flipped");
                paper3.style.zIndex = 3;
                closeBook(false);
                break;
            default:
                throw new Error("unkown state");
        }
        currentLocation++;
    }
	
}

function goPrevPage() {
    if(currentLocation > 1) {
        switch(currentLocation) {
            case 2:
            capa.classList.add("fechar-animacao");
                closeBook(true);
                paper1.classList.remove("flipped");
                paper1.style.zIndex = 3;
				console.log(currentLocation);
                break;
            case 3:
                paper2.classList.remove("flipped");
                paper2.style.zIndex = 2;
				console.log(currentLocation);
                break;
            case 4:
                openBook();
                capa.classList.add("abrir-animacao");
                paper3.classList.remove("flipped");
                paper3.style.zIndex = 1;
				console.log(currentLocation);
                break;
            default:
                throw new Error("unkown state");
        }

        currentLocation--;
    }
}
var myTimeout = setTimeout(goLogin, 0780);

function goLogin()
{
    if(currentLocation < maxLocation)
    {
		//alert(currentLocation);
     switch(currentLocation)
     {
            case 1:
			
                openBook();
                paper1.classList.add("flipped");
                paper1.style.zIndex = 1;
				currentLocation++;
                break;
			
				case 3:
				
				paper2.classList.remove("flipped");
                paper2.style.zIndex = 2;
				console.log(currentLocation);
				currentLocation--;
                break;
				
     }
     
    }
}

function goEsqueciSenha ()
{
    if(currentLocation < maxLocation)
    {
        switch(currentLocation)
        {
                case 1:
                    openBook();
                    paper1.classList.add("flipped");
                    paper1.style.zIndex = 1;
                    var myTimeout = setTimeout(goCC, 0600);
                    function goCC ()
                    {
                        paper2.classList.add("flipped");
                        paper2.style.zIndex = 2;
                        //var myTimeout = setTimeout(goES, 0600);
                    }
                    break;
                case 2:
                paper2.classList.add("flipped");
                paper2.style.zIndex = 2;
                break;


        }
        currentLocation = currentLocation + 1;
    }
}
function goBack3()
{
    switch(currentLocation)
        {
            case 2:
            paper2.classList.add("flipped");
            paper2.style.zIndex = 2;
            var myTimeout = setTimeout(goBK2, 0600);
            function goBK2 ()
            {
            paper3.classList.add("flipped");
            paper3.style.zIndex = 2;
            var myTimeout = setTimeout(goBK3, 0600);
            }
            function goBK3 ()
            {
            capa.classList.add("fechar-animacao");
                paper3.classList.add("flipped");
                paper3.style.zIndex = 3;
                closeBook(false);
            }
            break;

        }
        currentLocation = currentLocation + 2;
}

</script>

<!--Abaixo, script para botão de ver senha-->
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

