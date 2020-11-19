<?php
if (isset($_REQUEST["Modificar"])) {
     header('Location: ../mtoDepartamentos.php');
}
if (isset($_REQUEST["Cancelar"])) {
     header('Location: ../mtoDepartamentos.php');
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">  
    </head>
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
            if (isset($_GET['codigo'])) {
                $consulta->bindValue(1, $_GET['codigo']);
            } else {
                $consulta->bindValue(1, $_REQUEST['codigo']);
            }//Añadimos los parametros que necesitamos
            $consulta->execute(); //Ejecutamos la consulta
            $resultado = $consulta->fetchObject();
            
            /*while ($resultado = $consulta->fetchObject()) {
                    echo $resultado->CodDepartamento;
                    echo $resultado->DescDepartamento;
                    echo $resultado->FechaBaja;
                    echo $resultado->VolumenNegocio;
            }*/
            
        } catch (PDOException $exc) {
            echo $exc->getMessage();
            echo "Error al conectar " . "<br>";
        } finally {
            unset($mySQL);
        }
        
        
        if (isset($_REQUEST['Modificar'])) {
            $aErrores['descripcion'] = validacionFormularios::comprobarAlfaNumerico($_POST['descripcion'], 255, 1, 1);
            $aErrores['volumen'] = validacionFormularios::comprobarFloat($_POST['volumen'], PHP_FLOAT_MAX, 0, 1);
            foreach ($aErrores as $key => $value) {
                if ($value != null) {
                    $entradaOK = false;
                }
            }
        }
        if ($entradaOK == true && isset($_REQUEST['Modificar'])) {
            try {
                $miDB = new PDO(DNS, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sqlDepartamento = 'UPDATE Departamento SET DescDepartamento=:desc,VolumenNegocio=:volumen WHERE CodDepartamento=:codigo';
                $aDatos = [":codigo" => $_REQUEST['codigo'], ":desc" => $_REQUEST['descripcion'], ":volumen" => $_REQUEST['volumen']];
                $consulta = $miDB->prepare($sqlDepartamento);
                $consulta->execute($aDatos);
            } catch (PDOException $exc) {
                echo $exc->getMessage();
                echo "Error al conectar " . "<br>";
            } finally {
                unset($mySQL);
            }
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="Añadirformulario" method="POST">
            <fieldset>
                <label for="codigo">Codigo</label>
                <input type="text" name="codigo2" id="codigo2" disabled="true" value="<?php echo $resultado->CodDepartamento ?>"><br><br>
                <input type="hidden" name="codigo" id="codigo" value="<?php echo $resultado->CodDepartamento ?>">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" value="<?php echo $resultado->DescDepartamento ?>"><br><br>
                <label for="fecha">Fecha de baja</label>
                <input type="text" name="fecha" id="fecha" disabled="true" value="<?php echo $resultado->FechaBaja;?>"><br><br>
                <label for="volumen">Volumen Negocio</label>
                <input type="text" name="volumen" id="volumen" value="<?php echo $resultado->VolumenNegocio ?>"><br><br>
                <input type="submit" value="Modificar" name="Modificar">
                <input type="submit" value="Cancelar" name="Cancelar">
            </fieldset>
        </form>
    </body>
</html> 