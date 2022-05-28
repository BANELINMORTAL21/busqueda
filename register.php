<?php
require 'B.php';

$message = "";


if (!empty($_POST['Nombre']) && !empty($_POST['Usuario']) && !empty($_POST['Contrasena'])) {

  $contrasena = password_hash($_POST['Contrasena'], PASSWORD_BCRYPT);
  $sql = "INSERT INTO usuarios (Nombre, Usuario, Contrasena) VALUES ('" . $_POST['Nombre'] . "','" . $_POST['Usuario'] . "','" . $_POST['Contrasena'] . "')";
  $stmt = $conn->query($sql);

  // $stmt->bindParam(':Nombre',$_POST['Nombre']);
  // $stmt->bindParam(':Usuario',$_POST['Usuario']);
  // $stmt->bindParam(':Contrasena', $contrasena);

  // var_dump($sql);
  if ($stmt) {
    $message = "Usuario creado correctamente";
  } else {
    $message = "A ocurrido un error creando su usuario";
  }
}
// $foto = $_FILES['foto'];
// $tmp_name= $foto['tmp_name'];
// $img_file = $foto['name'];
// $img_type = $foto['type'];
// echo 1;
// if ((strpos($img_type,"gif") || strpos($img_type,"jpeg") ||
// strpos($img_type,"jpg") || strpos($img_type,"png"))) {
// echo 2;
// $destino = $directorio_destino. '/' .$img_file;
// mysqli_query($con, "INSERT INTO usuarios VALUES foto ='' ;");
// if (move_uploaded_file($tmp_name, $destino)) {
//   return true;
// }
// }
//  return false;
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

      <?php if (!empty($message)) : ?>
        <p><?= $message ?></p>
      <?php endif; ?>




      <!-- Login Form -->
      <form method="POST">

        <input type="text" id="Nombre" class="fadeIn second" name="Nombre" placeholder="Nombre" required>
        <input type="text" id="Usuario" class="fadeIn second" name="Usuario" placeholder="Usuario" required>
        <input type="password" id="Contrasena" class="fadeIn third" name="Contrasena" placeholder="Contraseña" required>
        <!-- <input type="file" id="foto" class="fadeIn second" name="foto" placeholder="foto">  -->
        <input type="submit" class="fadeIn fourth" value="Registrar">
      </form>

      <!-- Remind Passowrd -->
      <div id="formFooter">
        <a class="underlineHover" href="login.php">Inicia Sesion</a>
      </div>

    </div>
  </div>
</body>

</html>