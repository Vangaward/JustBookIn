<?php
if (!isset($logado)) {
    header('Location: index.php');
    exit;
	}
	else
	{
		if ($logado == 1)
		{
			$urlFotoPerfil;
			if ($dadosLogin['urlFotoPerfil'] == "")
			{ $urlFotoPerfil = "imagens/userPerfil.svg";}else;{$urlFotoPerfil = 'fotosPerfil/' . $dadosLogin['urlFotoPerfil'];}
		}
	}
?>
<?php include_once('Styles/Header.css'); ?>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #16463F;
            color: white;
            padding: 10px 20px;
            z-index: 2;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        .btnPub, .btnLog {
            background-color: #6EBBB0;
            color: black;
            cursor: pointer;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            margin-right: 20px;
        }
        .btnPub
        {
            margin-top: -10px;
        }
        .btnPub:hover, .btnLog:hover {
            background-color: #000;
        }
     #btnSide {
    background: transparent !important;
    border: none;
    cursor: pointer;
    outline: none;
}

    
        .container {
            display: flex;
            margin-top: 80px;
        }

        .sidebar {
            width: 0;
            height: 100%;
            background-color: #E7E1D5;
            overflow-x: hidden;
            transition: 0.3s;
            position: fixed;
            top: 80px; 
            right: 0;
            z-index: 1;
            padding-top: 60px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .li1 {
            padding: 10px;
            border-top: 4px solid #D6CCB8;
        }

        .sidebar a {
            text-decoration: none;
            color: #fff;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 250px;
            }

            main {
                width: 100%;
            }
        }
    </style>
<div class="bodyHeader" id="bodyHeader">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Armata">
    <header>
        <div class="header-content">

            <img src="../imagens/Justbookin2.PNG" style="width: 320px; height: 60px; flex-shrink: 0; cursor: pointer;" onclick="location.href='../inicio.php'">
            <?php if ($logado == 1) { ?>
                <!-- Conteúdo relacionado ao usuário logado, se necessário -->
                <button class="btnPub" onclick="location.href='publicarObraP1.php'"><img src="imagens/Livro.svg" alt="Descrição da imagem SVG">Publicar obra</button>
                    <button id="btnSide"><img src="imagens/tracos.svg" alt="Ícone"></button>
               
            <?php } else { ?>
                <button class="btnLog" onclick="location.href='login'" id="btnLogin">Login</button>
            <?php } ?>
        </div>
    </header>
    <div class="container">
        <?php if ($logado == 1) { ?>
        <div class="sidebar" id="sidebar">
            <ul>
                <li class="li1"><a href="#">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 40px; height: 40px; background: #E7E1D5; border-radius: 5px; display: flex; justify-content: center; align-items: center;">
                            <div style="width: 26.25px; height: 30px; background: url('imagens/Vector.svg') center/contain no-repeat;"></div>
                        </div>
                        <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-end;">
                            <div onclick="location.href='perfil.php'" style="color: #1F4152; font-size: 20px; font-family: Arimo; font-weight: 400;"><?php echo $dadosLogin['nome']; ?></div>
                            <div onclick="location.href='login/logoff.php'" style="color: #1F4152; font-size: 16px; font-family: Arimo; font-weight: 400;">Sair da conta</div>
                        </div>
                        <div style="width: 62.5px; height: 62.5px; background: #1F4152; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            <img style="width: 62.5px; height: 62.5px; border-radius: 50%;" src="<?php echo $urlFotoPerfil; ?>" />
                        </div>
                    </div>
                </a></li>
                <li class="li1"><a href="favoritos.php" class="btnFav" style="color: #1F4152;">Obras Favoritas</a></li>
                <li class="li1"><a href="historico.php" class="btnHis" style="color: #1F4152;">Histórico</a></li>
                <li class="li1"><a href="inscricao.php" class="btnIns" style="color: #1F4152;">Inscrições</a>   
                        <ul>    
                            <li></li>
                                </ul>
                        </li>
            </ul>
        </div>
        <?php } ?>
        <main>
            <!-- Conteúdo principal da página -->
        </main>
    </div>

	</div><!--bodyHeader-->
	    <script>
        const toggleSidebarButton = document.getElementById('btnSide');
        const sidebar = document.getElementById('sidebar');

        toggleSidebarButton.addEventListener('click', () => {
            if (sidebar.style.width === '250px') {
                sidebar.style.width = '0';
            } else {
                sidebar.style.width = '250px';
            }
        });
    </script>