<?php

session_start();
require 'B.php';

$message = "";
//  var_dump($_POST['Usuario']);
//  var_dump($_POST['Contrasena']);
if (!empty($_POST['Usuario']) && !empty($_POST['Contrasena'])) {
  $sql = "SELECT id, Usuario, Contrasena FROM usuarios WHERE Usuario = '" . $_POST['Usuario'] . "'";
  $row = $conn->query($sql);
  // $records->bindParam(':Usuario', $_POST['Usuario']);
  //$records->execute();
  $results = $row->fetch_array(MYSQLI_ASSOC);
  
  // var_dump($_POST['Contrasena']);
  // var_dump($results['Contrasena']);
  // var_dump(password_verify($_POST['Contrasena'], $results['Contrasena']));
  
  $message = '';
  
  // var_dump($row->fetch_array(MYSQLI_ASSOC));
  // var_dump($results['Contrasena']);
  
  
  if ($row->num_rows > 0 && $_POST['Contrasena'] == $results['Contrasena']) {
    $_SESSION['usuario_id'] = $results['id'];
    // var_dump($results); 
    header('Location: ./principal.php');
  } else {
    $message = "Tus credenciales no coinciden";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="CSS/S.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
  <div class="wrapper fadeInDown">
    <div id="formContent">
      <!-- Tabs Titles -->

      <!-- Icon -->
      <div class="fadeIn first">
        <img src="IMG/m.png" id="icon" alt="User Icon" />
      </div>

      <?php
      if (!empty($message)) : ?>
        <p><?= $message ?></p>
      <?php endif; ?>
      <!-- Login Form -->
      <form method="POST">
        <input type="text" id="username" class="fadeIn second" name="Usuario" placeholder="Usuario" required>
        <input type="password" id="password" class="fadeIn third" name="Contrasena" placeholder="Contrasena" required>
        <input type="submit" class="fadeIn fourth" value="Iniciar Sesion">
      </form>

      <!-- Remind Passowrd -->
      <div id="formFooter">
        <a class="underlineHover" href="register.php">No tienes cuenta?</a>
      </div>

    </div>
  </div>
</body>

</html>