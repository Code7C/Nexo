<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ventas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav>
        <ul>
            <li>FERRETERÍA BUENA TIERRA</li>
        </ul>
    </nav>
    <h1 id="title">VENTAS</h1>
    <div class="container">
        <button id="button">
            <a href="index.php?categoria=<?php echo $_REQUEST['ind']; ?>">Volver al Inicio</a>
        </button>
    </div>
    <div id="ventasSection">
        <table id="tablaVentas">
            <tr>
                <th>Total Costos Diarios</th>
                <th>Total Ganancias Diarias</th>
            </tr>
            <tr>
                <?php
                include 'conexion.php';
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                $fecha = date('Y-m-d');
                
                $sql = "SELECT SUM(GANANCIA) AS total_ganancia FROM ventas WHERE FECHA LIKE '%$fecha%'";
                $res = mysqli_query($cnx, $sql);
                $ganancias = $res ? mysqli_fetch_assoc($res)['total_ganancia'] : 0;
                
                $sql2 = "SELECT SUM(COSTO) AS total_costo FROM ventas WHERE FECHA LIKE '%$fecha%'";
                $res2 = mysqli_query($cnx, $sql2);
                $costos = $res2 ? mysqli_fetch_assoc($res2)['total_costo'] : 0;
                
                echo "<td>$costos</td>";
                echo "<td>$ganancias</td>";

                mysqli_close($cnx);
                ?>
            </tr>
        </table>
        <br><br>
        <table id="tablaVentas">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Costo</th>
                <th>Ganancia</th>
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
            <?php
            include 'conexion.php';
            $sql = "SELECT ID_VENTA, PRODUCTO, COSTO, GANANCIA, FECHA, CANTIDAD FROM ventas ORDER BY FECHA DESC";
            $res = mysqli_query($cnx, $sql);
            while ($fila = mysqli_fetch_assoc($res)) {
                echo '<tr>';
                echo '<td>' . $fila['PRODUCTO'] . '</td>';
                echo '<td>' . $fila['CANTIDAD'] . '</td>';
                echo '<td>' . $fila['COSTO'] . '</td>';
                echo '<td>' . $fila['GANANCIA'] . '</td>';
                echo '<td>' . $fila['FECHA'] . '</td>';
                echo '<td><a href="deleteSales.php?vent=' . $fila['ID_VENTA'] . '&prod=' . $fila['PRODUCTO'] . '"><img class="icon" src="recursos/trash.png"></a></td>';
                echo '</tr>';
            }
            mysqli_close($cnx);
            ?>
        </table>
    </div>
</body>
</html>
