<?php if ($splashScreen == true){
	$txtSplash = "";
	
	if (isset($_SESSION['splashTxtSairConta']))
	{
		if ($_SESSION['splashTxtSairConta'] == true)
		{
			$txtSplash = "Saindo da sua conta...";
		}
		unset($_SESSION['splashTxtSairConta']);
	}

    echo '
    
	<style>

.bodyHeader, .bodyFooter
{
	opacity: 0;
}
.inicio
    {
        display: none;
		text-align: center;
    }

@keyframes limparDireita {
  0% { opacity: 1; clip-path: inset(0 100% 0 0);}
  100% {opacity: 1; clip-path: inset(0 0 0 0);}
}
@keyframes ocultar
{
        0%{opacity: 1;}
        100% {opacity: 0;}
}
@keyframes desocultar
{
        0%{opacity: 0;}
        100% {opacity: 1;}
}
@keyframes deslizarDireita
{
  0% {margin-left: 0px}
  100% {margin-left: 246px}
}
.preSplashDivImgs
{
   margin-left: 25%;
}
.preSplashDivImgs.desvanecer
{
	animation: ocultar 0.2s forwards;
}
.splashDivImgs
{
   position: relative;  
   background-color: blue;
}
.justbImg, .jImg
{
  position: absolute;
  top: 0;
  left: 0;
}
.justbImg
{
    position: absolute;
  opacity: 0.0;
  animation: limparDireita 2s forwards;
  animation-delay: 1s;
  margin-top: 0px;
  height: 132px;
}
.jImg
{
    position: absolute;
    //opacity: 0.8;
    height: 100px;
    margin-top: 16px;
    animation: ocultar 0.2s forwards;
    animation-delay: 2s;
    
}
.bImg
{
    position: absolute;
    z-index: 3;
    height: 100px;
    margin-top: 16px;
    margin-left: 56px;
    animation: deslizarDireita 0.9s forwards;
   animation-delay: 1.1s;
   animation-timing-function: linear;zz
}
.ookinImg
{
    opacity: 0;
    position: absolute;
    margin-left: 317px;
    margin-top: 16px;
    height: 100px; 
    animation: desocultar 0.9s forwards;
    animation-delay: 2.5s;
}
.txtSplash
{
	//margin-top: 40%;
}
OcultarImgsSplash
{
	display: none;
}

</style>
	
<div class="preSplashDivImgs" id="preSplashDivImgs">
  <div class="splashDivImgs">
    <img class="bImg" id="bImg" src="imagens/b.png">
    <img class="justbImg" id="justbImg" src="imagens/justb.png">
    <img class="jImg" id="jImg" src="imagens/j.png">
    <img class="ookinImg" id="ookinImg" src="imagens/ookin.PNG">
  </div>
  </div>
  
  <div class="txtSplash">' . $txtSplash . '</div>

	<script language="javascript">
  /* Splash Script */
  setTimeout(removeSplashScreen, 6000);
  function removeSplashScreen() {
    var bImg = document.getElementById("bImg");
	var justbImg = document.getElementById("justbImg");
	var jImg = document.getElementById("jImg");
	var ookinImg = document.getElementById("ookinImg");
	/*var preSplashDivImgs = document.getElementById("preSplashDivImgs");
	document.preSplashDivImgs.classList.toggle("desvanecer");*/
	
    bImg.style.display = "none";
	justbImg.style.display = "none";
	jImg.style.display = "none";
	ookinImg.style.display = "none";
	
	var bodyHeader = document.getElementById("bodyHeader");
	bodyHeader.style.opacity = 1;
	var bodyFooter = document.getElementById("bodyFooter");
	bodyFooter.style.opacity = 1;
	var inicio = document.getElementById("inicioId");
	inicio.style.display = "block";
	inicio.style.textAlign = "";
	
  }
</script>
 '; } ?>
 