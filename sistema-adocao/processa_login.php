<?php
require_once 'conectaBD.php';

if (!empty($_POST)) {
  session_start();
  try {
    $sql = "SELECT nome, email, telefone, data_nascimento FROM usuario WHERE email = :email AND senha = :senha";

    $stmt = $pdo->prepare($sql);

    $dados = array(
      ':email' => $_POST['email'],
      ':senha' => md5($_POST['senha'])
    );

    $stmt->execute($dados);

    $result = $stmt->fetchAll();

    if ($stmt->rowCount() == 1) {

      $result = $result[0];
      $_SESSION['nome'] = $result['nome'];
      $_SESSION['email'] = $result['email'];
      $_SESSION['data_nascimento'] = $result['data_nascimento'];
      $_SESSION['telefone'] = $result['telefone'];

      header("Location: index_logado.php");

    } else {
      session_destroy();
      header("Location: index.php?msgErro=E-mail e/ou Senha inválido(s).");
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
else {
  header("Location: index.php?msgErro=Você não tem permissão para acessar esta página..");
}
die();
?>
