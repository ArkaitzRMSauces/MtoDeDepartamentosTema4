<?php
        require_once '../config/confDBPDO.php';
        $entradaOK = true;
        $archivo = null;
        $error = null;
        if(isset($_POST['volver'])){
            header('Location: ../mtoDepartamentos.php');
            exit;
        }   
        if (isset($_POST["importar"])) {
            if (!empty($_FILES["archivo"]["name"])) {
                $archivo = $_FILES['archivo']['tmp_name'];
            } else {
                $error = "Introduce un archivo";
                $entradaOK = false;
            }
        } else {
            $entradaOK = false;
        }
        if ($entradaOK) {
            try {
                echo $archivo;
                $dom = new DOMDocument;
                $dom->load($archivo);
                $miDB = new PDO(DNS, USER, PASSWORD);
                $pre = $miDB->prepare("Select * from Departamento");
                $pre -> execute();
                $prepare = $miDB->prepare("Insert into Departamento values (:codigo, :descripcion, :fecha, :volumen)");

                $departamento = $dom->getElementsByTagName('Departamento');
                $miDB->beginTransaction();
                foreach ($departamento as $dep) {
                    $valores = $dep->childNodes;

                    $aValores = array(
                        ":codigo" => $valores->item(1)->nodeValue,
                        ":descripcion" => $valores->item(3)->nodeValue,
                        ":fecha" => empty($valores->item(5)->nodeValue) ? null : $valores->item(5)->nodeValue,
                        ":volumen" => $valores->item(7)->nodeValue
                    );
                    
                    $eje = $prepare->execute($aValores);

                    if (!$eje) {
                        throw new Exception("Error al insertar en la base de datos ".$prepare->errorCode());
                    }
                }
                $miDB->commit();
                echo "<p> Todos los datos han sido importados</p>";
                echo "<input type='submit' name='volver' value='Volver'>";
            } catch (Exception $e) {
                echo "Error " . $e->getCode() . ", " . $e->getMessage() . ".";
                $miDB->rollBack();
                echo "<input type='submit' name='volver' value='Volver'>";
            } finally {
                unset($miDB);
            }
        } else {
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"  enctype = "multipart/form-data">
                <input type="file" name="archivo" >
                <?php
                if (is_null($error)) {
                    echo "<span style=\"color:blue;\">$error</span>";
                }
                ?>
                <br>
                <input type="submit" name="importar" value="Importar">
                <input type="submit" name="volver" value="Volver">
            </form>
        <?php } ?>