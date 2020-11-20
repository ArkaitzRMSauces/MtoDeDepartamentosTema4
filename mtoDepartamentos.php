<?php
    define('PATH','codigoPHP/');
    define('PATHPROYECTOS','../');
    if(isset($_POST['insertar'])){
        header('Location: '.PATH.'altaDepartamento.php');
        exit;
    }
    if(isset($_POST['importar'])){
        header('Location: '.PATH.'importarDepartamentos.php');
        exit;
    }
    if(isset($_POST['exportar'])){
        header('Location: '.PATH.'exportarDepartamentos.php');
        exit;
    }
    if(isset($_POST['mostrarCodigo'])){
        header('Location: '.PATH.'mostrarCodigo.php');
        exit;
    }
    
    if(isset($_POST['volver'])){
        header('Location: '.PATHPROYECTOS.'proyectoDWES/indexProyectoDWES.php');
        exit;
    }
?>
<html>
    <head>
        <title>MtoDeDepartamentosTema4 - Arkaitz Rodriguez Martinez</title>
        <link rel="stylesheet" href="webroot/css/estilos.css" />
    </head>
    <body>
        <main>
            <header class="cabecera">MtoDeDepartamentos - Tema 4 - Arkaitz Rodriguez Martinez</header>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
                <nav>
                    <input type="submit" name="insertar" value="Añadir" class="input">
                    <input type="submit" name="importar" value="Importar" class="input">
                    <input type="submit" name="exportar" value="Exportar" class="input">
                    <input type="submit" name="volver" value="Volver" class="input">
                    <input type="submit" name="mostrarCodigo" value="Mostrar Codigo" class="input">
                </nav>
                <br>
                <div>
                    <label>Descripcion del Departamento</label>
                    <input type = "text" name = "descDepartamento"
                        value="<?php if(isset($_POST['descDepartamento'])){ echo $_POST['descDepartamento'];} ?>" class="text">              
                    <input type="submit" name="buscar" value="Buscar" class="input">
                </div>
            </form>
            <?php
                require_once './config/confDBPDO.php';//Importamos archivo de configuracion de la conexion de BD
                require_once './core/201020libreriaValidacion.php';//Importamos archivo de validacion de formularios
                $arrayFormulario = [
                    'descDepartamento' => null,
                ]; 

                try {   
                    $miDB = new PDO(DNS, USER, PASSWORD);//Creamos el objeto PDO, con la conexion
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if (isset($_POST['buscar'])) {//Al buscar "nada" Mostrara toda la tabla

                        //OBLIGATORIOS
                        $arrayFormulario['descDepartamento'] = ucfirst($_POST['descDepartamento']); //La primera letra en mayúscula

                        //Búsqueda del departamento  
                        $consultaSelect = $miDB->prepare('SELECT * FROM Departamento WHERE descDepartamento LIKE ("%":descripcion"%")');//Creamos la consulta preparada
                        $consultaSelect->bindParam(":descripcion", $arrayFormulario['descDepartamento']);//Parametro de la consulta preparada
                        $consultaSelect->execute();//Ejecutamos la sentencia
                        if($consultaSelect->rowCount() != 0){//Contamos si la sentencia a devuelto alguna fila, sino, no habrá encontrado nada
                            echo "<table border='0'>";
                            echo "<tr>";
                            echo "<th>Codigo</th>";
                            echo "<th>Descripción</th>";
                            echo "<th>Fecha Baja</th>";
                            echo "<th>Volumen de Negocio</th>";
                            echo "</tr>";
                            $objetoDepartamento = $consultaSelect->fetchObject();//Creamos objeto PDOStatement y avanzamos puntero
                            while ($objetoDepartamento) { //Al realizar el fetchObject, se pueden sacar los datos de $consultaSelect como si fuera un objeto
                                echo "<tr>";
                                echo "<td>$objetoDepartamento->CodDepartamento</td>";
                                echo "<td>$objetoDepartamento->DescDepartamento</td>";
                                echo "<td>$objetoDepartamento->FechaBaja</td>";
                                echo "<td>$objetoDepartamento->VolumenNegocio</td>";
                                echo "<td><a href=\"codigoPHP/modificarDepartamento.php?codigo=" . $objetoDepartamento->CodDepartamento . "\">&#9999;&#65039;</a>
                                      <a href=\"codigoPHP/bajaDepartamentos.php?codigo=" . $objetoDepartamento->CodDepartamento . "\">&#128465;&#65039;</a>
                                      <a href=\"codigoPHP/mostrarDepartamento.php?codigo=" . $objetoDepartamento->CodDepartamento . "\">&#128270;</a> </td>";
                                echo "</tr>";
                                $objetoDepartamento = $consultaSelect->fetchObject();//Avanzamos puntero dentro del bucle
                            }
                            echo "</table>";
                        } else {//Creamos la tabla con los nombres de los campos
                            echo "<h2>No hay ningún departamento con esa descripción</h2>";
                        } 
                    }else{
                        $consultaSelect = $miDB->prepare('SELECT * FROM Departamento');//Creamos la consulta preparada
                        $consultaSelect->execute();//Ejecutamos la sentencia
                        if($consultaSelect->rowCount() != 0){//Contamos si la sentencia a devuelto alguna fila, sino, no habrá encontrado nada
                            echo "<table border='0'>";
                            echo "<tr>";
                            echo "<th>Codigo</th>";
                            echo "<th>Descripción</th>";
                            echo "<th>Fecha Baja</th>";
                            echo "<th>Volumen de Negocio</th>";
                            echo "</tr>";
                            $objetoDepartamento = $consultaSelect->fetchObject();//Creamos objeto PDOStatement y avanzamos puntero
                            while ($objetoDepartamento) { //Al realizar el fetchObject, se pueden sacar los datos de $consultaSelect como si fuera un objeto
                                echo "<tr>";
                                echo "<td>$objetoDepartamento->CodDepartamento</td>";
                                echo "<td>$objetoDepartamento->DescDepartamento</td>";
                                echo "<td>$objetoDepartamento->FechaBaja</td>";
                                echo "<td>$objetoDepartamento->VolumenNegocio</td>";
                                echo "<td><a href=\"codigoPHP/modificarDepartamento.php?codigo=" . $objetoDepartamento->CodDepartamento . "\">&#9999;&#65039;</a>
                                      <a href=\"codigoPHP/bajaDepartamentos.php?codigo=" . $objetoDepartamento->CodDepartamento . "\">&#128465;&#65039;</a>
                                      <a href=\"codigoPHP/mostrarDepartamento.php?codigo=" . $objetoDepartamento->CodDepartamento . "\">&#128270;</a> </td>";
                                echo "</tr>";
                                $objetoDepartamento = $consultaSelect->fetchObject();//Avanzamos puntero dentro del bucle
                            }
                            echo "</table>";
                        } else {//Creamos la tabla con los nombres de los campos
                            echo "<h2>No hay ningún departamento con esa descripción</h2>";
                        }
                    }
                }catch (PDOException $miExceptionPDO) { 
                    echo 'Error :'.$miExceptionPDO->getMessage();
                    echo 'Codigo de error :'.$miExceptionPDO->getCode();
                }finally{
                    unset($miDB);
                }
            ?>
        </main>
        <footer>
            <div>
                <a href="https://github.com/ArkaitzRMSauces?tab=repositories" target="_blank"><img src="webroot/images/GitHub.png" width="120px" height="63px"></a>            
                <p>Todos los derechos reservados</p>
            </div>
        </footer>
    </body>
</html>