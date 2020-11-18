<div>
    <a href="descarga.php">Descargar</a>
<?php
    require_once '../config/confDBPDO.php';
    $documentoXML = new DOMDocument(); // Creamos el fichero
    $departamentos = $documentoXML->createElement('Departamentos');
    $departamentos = $documentoXML->appendChild($departamentos);
    try {
        // Datos de la conexión a la base de datos
        $miDB = new PDO(DNS, USER, PASSWORD);
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $consulta = "SELECT * FROM Departamento;";
        $sentencia = $miDB->prepare($consulta);
        $sentencia->execute();
        while ($registro = $sentencia->fetchObject()) { // creamos un bucle para sacar todos los elementos en la estructura XML
            $departamento = $documentoXML->createElement("Departamento");
            $departamento = $departamentos->appendChild($departamento);
            $codigo = $documentoXML->createElement('Codigo',$registro->CodDepartamento);
            $codigo = $departamento->appendChild($codigo);
            $descripcion = $documentoXML->createElement('Descripcion',$registro->DescDepartamento);
            $descripcion = $departamento->appendChild($descripcion);
            $fechaBaja = $documentoXML->createElement('FechaBaja',$registro->FechaBaja);
            $fechaBaja = $departamento->appendChild($fechaBaja);
            $volumenNegocio = $documentoXML->createElement('VolumenNegocio',$registro->VolumenNegocio);
            $volumenNegocio = $departamento->appendChild($volumenNegocio);
        } 
        $documentoXML->formatOutput = true;
        $documentoXML->saveXML();
        $documentoXML->save("../tmp/departamento.xml");
        echo "<br>";
        highlight_file("../tmp/departamento.xml");

        echo "<h3>Exportacion realizada</h3>";
    } catch (PDOException $mensajeError) {
        echo "Error: " . $mensajeError->getMessage() . "<br>";
        echo "Código de error: " . $mensajeError->getCode();
    } finally {
        unset($miDB);
    }
?>
</div>