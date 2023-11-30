<?php
require_once 'conectaBD.php';

session_start();

if (empty($_SESSION)) {
  header("Location: index.php?msgErro=Você precisa se autenticar no sistema.");
  die();
}


if (!empty($_POST)) {
  if ($_POST['enviarDados'] == 'CAD') { 
    try {
        $sql = "INSERT INTO anuncio
                  (fase, tipo, porte, sexo, pelagem_cor, raca, observacao, email_usuario)
                VALUES
                  (:fase, :tipo, :porte, :sexo, :pelagem_cor, :raca, :observacao, :email_usuario)";

        $stmt = $pdo->prepare($sql);

        $dados = array(
          ':fase' => $_POST['fase'],
          ':tipo' => $_POST['tipo'],
          ':porte' => $_POST['porte'],
          ':sexo' => $_POST['sexo'],
          ':pelagem_cor' => $_POST['pelagemCor'],
          ':raca' => $_POST['raca'],
          ':observacao' => $_POST['observacao'],
          ':email_usuario' => $_SESSION['email']
        );

        if ($stmt->execute($dados)) {
          header("Location: index_logado.php?msgSucesso=Anúncio cadastrado com sucesso!");
        }
    } catch (PDOException $e) {
        die($e->getMessage());
        header("Location: index_logado.php?msgErro=Falha ao cadastrar anúncio..");
    }
  }
  elseif ($_POST['enviarDados'] == 'ALT') {
    try {
      $sql = "UPDATE
                anuncio
              SET
                fase = :fase,
                tipo = :tipo,
                porte = :porte,
                pelagem_cor = :pelagem_cor,
                raca = :raca,
                sexo = :sexo,
                observacao = :observacao
              WHERE
                id = :id_anuncio AND
                email_usuario = :email";

      // Definir dados para SQL
      $dados = array(
        ':id_anuncio' => $_POST['id_anuncio'],
        ':fase' => $_POST['fase'],
        ':tipo' => $_POST['tipo'],
        ':porte' => $_POST['porte'],
        ':pelagem_cor' => $_POST['pelagemCor'],
        ':raca' => $_POST['raca'],
        ':sexo' => $_POST['sexo'],
        ':observacao' => $_POST['observacao'],
        ':email' => $_SESSION['email']
      );

      $stmt = $pdo->prepare($sql);

      // Executar SQL
      if ($stmt->execute($dados)) {
        header("Location: index_logado.php?msgSucesso=Alteração realizada com sucesso!!");
      }
      else {
        header("Location: index_logado.php?msgErro=Falha ao ALTERAR anúncio..");
      }
    } catch (PDOException $e) {
      //die($e->getMessage());
      header("Location: index_logado.php?msgErro=Falha ao ALTERAR anúncio..");
    }

  }
  elseif ($_POST['enviarDados'] == 'DEL') { 
    try {
      $sql = "DELETE FROM anuncio WHERE id = :id_anuncio AND email_usuario = :email";
      $stmt = $pdo->prepare($sql);

      $dados = array(':id_anuncio' => $_POST['id_anuncio'], ':email' => $_SESSION['email']);

      if ($stmt->execute($dados)) {
        header("Location: index_logado.php?msgSucesso=Anúncio excluído com sucesso!!");
      }
      else {
        header("Location: index_logado.php?msgSucesso=Falha ao EXCLUIR anúncio..");
      }
    } catch (PDOException $e) {
      header("Location: index_logado.php?msgSucesso=Falha ao EXCLUIR anúncio..");
    }
  }
  else {
    header("Location: index_logado.php?msgErro=Erro de acesso (Operação não definida).");
  }
}
else {
  header("Location: index_logado.php?msgErro=Erro de acesso.");
}
die();
 ?>
