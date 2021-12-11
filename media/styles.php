<?php
/**
 *@package pXP
 *@file    SolModPresupuesto.php
 *@author  Rensi Arteaga Copari
 *@date    30-01-2014
 *@description permites subir archivos a la tabla de documento_sol
 */
header("content-type: text/css; charset=UTF-8");
?>

<style type="text/css" rel="stylesheet">

    .prioridad_menor {
        background-color: #3EB82A;//#e2ffe2
    color: #090;

    }

    .prioridad_medio {
        background-color: #FFF0B4;
        color: #090;
    }

    .prioridad_importanteA{

        background-color: #FFDADA;//#ffe2e2
        color: #900;
    }

    .prioridad_importanteB{

        background-color: #c2ddff;
        color: #51adff;
    }

    .obligatorio{
        background-color: #EAA8A8;
    }
    .y-grid3-different-row {
        color: #f20726 !important;
    }
    .seleccionado{
       background-color: #A0FAA4 !important;
    }
    .selector:hover{
      background-color: #A0FAA4 !important;
    }


</style>
