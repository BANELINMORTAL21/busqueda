<?php
session_start();
include './consultas.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Document</title>
  <link rel="stylesheet" href="CSS/Q.css">
  <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
</head>

<body>
  <div class="wrapper fadeInDown">
    <div id="container">
      <div class="user menu">
        <div>
          <?php
          if (!empty($datos_usuario)) {
            echo $datos_usuario['0'];
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <div class="container mt-5">
    <div class="col-12">

      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">

              <h4 class="card-title">Buscador</h4>

              <form id="form2" name="form2" method="POST" action="principal.php">
                <div class="row d-flex justify-content-center">
                  <div class="col-2">
                    <label class="form-label">Id</label>
                    <input type="text" class="form-control" id="buscar" name="buscar">
                  </div>
                  <div class="row d-flex justify-content-center mt-2">
                    <div class="col-1">
                      <button class="btn " style=" background-color: #56baed; color: white;">Buscar</button>
                    </div>
                  </div>
                </div>

                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Programa</th>
                      <th>Creditos</th>
                      <th>Semestre Actual</th>
                      <th>Semestre ref credito</th>
                    </tr>
                  </thead>
                  <tbody id="datos">
                    <?php
                    if (!empty($datos_html)) {
                      foreach ($datos_html as $estudiante) {
                        echo $estudiante;
                      }
                    } else {
                    }
                    ?>
                    <!-- datos tabla  -->

                  </tbody>
                </table>
            </div>
            <!-- 
            <table>
              <thead>
                <tr>
                  <th>id</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Programa</th>
                  <th>Creditos</th>
                  <th>Semestre Actual</th>
                  <th>Semestre ref credito</th>
                  <th>Estado</th>
                </tr>
              </thead>

              <tbody>
                <tr data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                  <td>Hola</td>
                </tr>
                <tr>
                  <td>
                    <div class="collapse" id="collapseExample">
                      <div class="card card-body">
                        <table>
                          <thead>
                            <tr>
                              <th>id</th>
                              <th>Nombre</th>
                              <th>Apellido</th>
                              <th>Programa</th>
                              <th>Creditos</th>
                              <th>Semestre Actual</th>
                              <th>Semestre ref credito</th>
                              <th>Estado</th>
                            </tr>
                          </thead>

                          <tbody>
                            <tr data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                              <td>Hola</td>
                            </tr>
                            <tr>
                              <div class="collapse" id="collapseExample">
                                <div class="">
                                  Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                                </div>
                              </div>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <p>
              <a class="btn btn-primary">
                Link with href
              </a>
            </p> -->

            <!-- JavaScript Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>