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

            <!-- Login Form -->
            <form action="datos_excel.php" method="POST" enctype="multipart/form-data">

                <strong><label>
                        <h2>IMPORTAR REGISTROS </h2>
                    </label></strong>
                <td><input type="file" name="foto" id="foto"></td>

                <input type="submit" class="fadeIn fourth" value="Cargar">
            </form>
            <div id="formFooter">
                <a class="underlineHover" href="principal.php">Ya tengo registros</a>
            </div>

        </div>
    </div>

</body>

</html>