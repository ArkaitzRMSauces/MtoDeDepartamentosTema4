<?php
if (isset($_REQUEST["eliminar"])) {
     header('Location: ../mtoDepartamentos.php');
}
if (isset($_REQUEST["cancelar"])) {
     header('Location: ../mtoDepartamentos.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    <body>        
        <?php
        require_once '../core/201020libreriaValidacion.php'; //Importamos la libreria de validacion
        require_once ('../config/confDBPDO.php'); //Configuracion de la base de datos
        $entradaOK = true;

        try {
            $miDB = new PDO(DNS, USER, PASSWORD);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqlDepartamento = 'Select * FROM Departamento WHERE CodDepartamento LIKE ?'; //Creamos la sentencia sql    
            $consulta = $miDB->prepare($sqlDepartamento); //preparamos el query

            if (isset($_REQUEST['codigo'])) {
                $consulta->bindValue(1, $_REQUEST['codigo']);
            } else {
                $consulta->bindValue(1, $_REQUEST['codigo']);
            }//Añadimos los parametros que necesitamos
            $consulta->execute(); //Ejecutamos la consulta
            $resultado = $consulta->fetchObject();
        } catch (PDOException $exc) {
            echo $exc->getMessage();
            echo "Error al conectar " . "<br>";
        } finally {
            unset($miDB);
        }

        if ($entradaOK == true && isset($_REQUEST['eliminar'])) {
            try {
                // Datos de la conexión a la base de datos
                $miDB = new PDO(DNS, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cómo capturar las excepciones y muestre los errores
                //Crear el departamento en la base de datos    
                $sentenciaSQL = $miDB->prepare("DELETE FROM Departamento  WHERE CodDepartamento LIKE '" . $_REQUEST['codigo'] . "'");
                $sentenciaSQL->execute();
            } catch (PDOException $mensajeError) { //Cuando se produce una excepcion se corta el programa y salta la excepción con el mensaje de error
                echo "<h3>Mensaje de ERROR</h3>";
                echo "Error: " . $mensajeError->getMessage() . "<br>";
                echo "Código de error: " . $mensajeError->getCode();
            } finally {
                unset($miDB);
            }
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="Añadirformulario" method="POST">
            <fieldset>
                <label for="codigo">Codigo</label>
                <input type="text" name="codigo2" id="codigo2" readonly value="<?php echo $resultado->CodDepartamento ?>"><br><br>
                <input type="hidden" name="codigo" id="codigo" readonly value="<?php echo $resultado->CodDepartamento ?>">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" readonly value="<?php echo $resultado->DescDepartamento ?>"><br><br>
                <label for="fecha">Fecha de baja</label>
                <input type="text" name="fecha" id="fecha" readonly value="<?php echo $resultado->FechaBaja ?>"><br><br>
                <label for="volumen">Volumen Negocio</label>
                <input type="text" name="volumen" id="volumen" readonly value="<?php echo $resultado->VolumenNegocio ?>"><br><br>
                <input type="submit" value="Eliminar" name="eliminar">
                <input type="submit" value="Cancelar" name="cancelar">
            </fieldset>
        </form>

    </body>
</html>