<?php //Esse arquivo tem a função de verificar se não é um adm que está logado.
      //Vcli = Verificação de cliente.
    include_once('Vlogin.php');
    if ($logado == 1)
    {
        if ($dadosLogin['tipoConta'] == 1) //Está logado mas é adm
        {
            header('Location: adm/painelControle.php');
        }
        else if ($dadosLogin['tipoConta'] == 2) //Está logado é editora, mas a conta não está validada
        {
            die('Editora, no momento você não tem permissão para acessar essa págiana, primeiro <a href="../login/logoff.php">faça logoff</a>, depois faça login e valide sua conta.');
        }
        else if ($dadosLogin['statusConta'] == 0) //A conta não está validada, seja tipoConta 1, 2 ou 3.
        {
            die('No momento você não tem permissão para acessar essa págiana, primeiro <a href="../login/logoff.php">faça logoff</a>, depois faça login e valide sua conta');
        }
    }
?>