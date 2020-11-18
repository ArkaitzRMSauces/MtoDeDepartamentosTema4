<?php
    header("Content-disposition: attachment; filename=departamento.xml");
    header("Content-type: text/xml");
    readfile("../tmp/departamento.xml");
?>