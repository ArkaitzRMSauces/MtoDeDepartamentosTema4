<?php
    require_once '../config/confDBPDO.php';//Importamos archivo de configuracion de la conexion de BD
    require_once '../core/201020libreriaValidacion.php';//Importamos archivo de validacion de formularios
    $entradaOK = true;
            
    $arrayErrores = [ //Recoge los errores del formulario
        'codDepartamento' => null,
        'descDepartamento' => null,
        'volumenNegocio' => null
    ];
    $arrayFormulario = [ //Recoge los datos del formulario
        'codDepartamento' => null,
        'descDepartamento' => null,
        'volumenNegocio' => null
    ];
    if (isset($_POST['enviar'])) { //Código que se ejecuta cuando se envía el formulario
        $arrayErrores['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_POST['codDepartamento'], 3, 3, 1);  //Máximo, mínimo y opcionalidad
        $arrayErrores['descDepartamento'] = validacionFormularios::comprobarAlfabetico($_POST['descDepartamento'], 255, 1, 1);  //Máximo, mínimo y opcionalidad
        $arraErrores['volumenNegocio'] = validacionFormularios::comprobarFloat($_POST['volumenNegocio'], 255, 0, PHP_FLOAT_MAX, 1);  //maximo, mínimo y opcionalidad
                
        foreach ($arrayErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
            if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
                $entradaOK = false; //Cambia la condiccion de la variable
            }else{
                if(isset($_POST[$campo])){
                    $aFormulario[$campo] = $_REQUEST[$campo];
                }
            } 
        }
    } else {
        $entradaOK = false;
    }
    if ($entradaOK) { //Si el valor es true procesamos los datos que hemos recogido
        //Mostramos los datos por pantalla
        $arrayFormulario['codDepartamento'] = strtoupper($_POST['codDepartamento']); //Todo en mayúsculas
        $arrayFormulario['descDepartamento'] = ucfirst($_POST['descDepartamento']); //La primera letra en mayúscula
        $arrayFormulario['volumenNegocio'] = $_POST['volumenNegocio'];
            
        try {   
            $miDB = new PDO(DNS, USER, PASSWORD);//Creamos el objeto PDO, con la conexion
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $consultaInsert = $miDB->prepare('INSERT INTO Departamento (CodDepartamento, DescDepartamento, VolumenNegocio) VALUES (:codigo, :descripcion, :volumen)');//Creamos la consulta preparada
            $consultaInsert->bindParam(":codigo", $arrayFormulario['codDepartamento']);//Parametro de la consulta preparada
            $consultaInsert->bindParam(":descripcion", $arrayFormulario['descDepartamento']);//Parametro de la consulta preparada
            $consultaInsert->bindParam(":volumen", $arrayFormulario['volumenNegocio']);//Parametro de la consulta preparada
            $consultaInsert->execute();//Ejecutamos la sentencia
            $consultaSelect = "SELECT * FROM Departamento";//Creamos la consulta de visualizacion
            $resultadoConsulta = $miDB->query($consultaSelect); //La metemos en una variable
            $resultadoConsulta->execute(); //Ejecutamos la sentencia
        ?>
        <table>
            <tr>
                <th>CodDepartamento</th>
                <th>DescDepartamento</th>
                <th>FechaBaja</th>
                <th>VolumenNegocio</th>
            </tr>
            <?php 
                while ($registro = $resultadoConsulta->fetchObject()) { //Al realizar el fetchObject, se pueden sacar los datos de $registro como si fuera un objeto
                    echo "<tr>";
                    echo "<td>$registro->CodDepartamento</td>";
                    echo "<td>$registro->DescDepartamento</td>";
                    echo "<td>$registro->FechaBaja</td>";
                    echo "<td>$registro->VolumenNegocio</td>";
                    echo "</tr>";
                }
            ?> 
        </table>
        <?php
            }catch (PDOException $miExceptionPDO) { 
                echo 'Error :'.$miExceptionPDO->getMessage();
                echo 'Codigo de error :'.$miExceptionPDO->getCode();
            }finally{
                unset($miDB);
            }
            }else { //Mostrar el formulario hasta que se rellene correctamente
        ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div>
                        <label>Código de Departamento </label>
                        <input type = "text" name = "codDepartamento"
                               value="<?php if($arrayErrores['codDepartamento'] == NULL && isset($_POST['codDepartamento'])){ echo $_POST['codDepartamento'];} ?>"><br>
                    </div>
                    <div>
                    <?php
                    if ($arrayErrores['codDepartamento'] != NULL) {
                        echo $arrayErrores['codDepartamento']; //Mensaje de error que tiene el $arrayErrores
                    }
                    ?>
                    </div>
                    <br>
                    <div>
                        <label>Descripción Departamento</label>
                        <input type = "text" name = "descDepartamento"
                               value="<?php if($arrayErrores['descDepartamento'] == NULL && isset($_POST['descDepartamento'])){ echo $_POST['descDepartamento'];} ?>"><br>
                    </div>
                    <div>
                    <?php
                    if ($arrayErrores['descDepartamento'] != NULL) {
                        echo $arrayErrores['descDepartamento']; //Mensaje de error que tiene el $arrayErrores
                    }
                    ?>
                    </div>
                    <br>
                    <div>
                        <label>Volumen Negocio</label>
                        <input type = "text" name = "volumenNegocio"
                               value="<?php if($arrayErrores['volumenNegocio'] == NULL && isset($_POST['volumenNegocio'])){ echo $_POST['volumenNegocio'];} ?>"><br>
                    </div>
                    <div>
                    <?php
                    if ($arrayErrores['volumenNegocio'] != NULL) {
                        echo $arrayErrores['volumenNegocio']; //Mensaje de error que tiene el $arrayErrores
                    }
                    ?>
                    </div>
                    <br>
                    <div>
                        <input type="submit" name="enviar" value="Añadir Departamento">
                    </div>
                </fieldset>
            </form>
        <?php } ?>