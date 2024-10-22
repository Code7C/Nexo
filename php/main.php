<?php 
session_start();
if (empty($_SESSION['email'])) {
    // Redirigir al índice si la variable está vacía o no existe
    header('Location: ../index.php');
    exit();
}

// Definir la categoría actual (por defecto 'Ferreteria' si no se especifica ninguna)
$categoria = null;
if (!empty($_GET['categoria'])) {
    $categoria = $_GET['categoria'];
    $_SESSION['categoria'] = $categoria; // Actualizar la categoría en la sesión
}

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';
include 'users/hasPermissions.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio</title>

    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <script type="text/javascript" src="../js/main.js"></script>
    <script type="text/javascript" src="../js/popup.js"></script>
    
</head>
<body>
    <nav>
        <ul> 
            <div class='bussines-icon'>
                <li><img href="../index.php" class='bussines-icon' src="../resources/logo.png" alt="Logo"></li>
            </div>
            <li>
                <!-- Select para filtrar por categorías -->
                <div class="select-wrapper">
                    <select class="titulo" onchange="location = this.value;">
                        <option value="?categoria=#">--Selecciona una Opción--</option>
                        <?php
                        // Consulta para obtener las categorías
                        $sql = "SELECT CATEGORY_NAME FROM categories ORDER BY CATEGORY_NAME ASC";
                        $resultado = mysqli_query($cnx, $sql);
                        // Recorrer las filas y generar opciones para el select
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                $cat = htmlspecialchars($fila['CATEGORY_NAME']);
                                $selected = ($cat == $categoria) ? 'selected' : '';
                                echo "<option value='?categoria=$cat' $selected>$cat</option>";
                            }
                        }
                        ?>
                    </select>    
                </div>
            </li>
            <li>
                <!-- Formulario de búsqueda -->
                <form action="main.php" method="POST" class="search-form">
                    <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
                    <input type="text" name="buscar" placeholder="Buscar producto...">
                    <button type="submit">Buscar</button>
                </form>
            </li>
            <li>
                <ul>
                    <li>
                        <div class="user-icon">
                            <img width="100%" height="100%" src="../resources/user.png" alt="Usuario" class="menu-toggle" data-target="dropdown-user">
                            <ul>
                                <div class="dropdown-menu" id="dropdown-user">
                                    <li><a href="users/profile.php">Perfil</a></li> <?php 
                                    if (haspermission('ACCESS_ADMINISTRATION')){echo '<li><a href="users/admin.php">Panel de Administración</a></li>';}?>
                                    <li><a href="users/logout.php">Cerrar Sesión</a></li>
                                </div>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="menu-icon">
                            <img src="../resources/menu.png" alt="Menu" class="menu-toggle" data-target="dropdown-menu">
                            <ul>
                                <div class="dropdown-menu" id="dropdown-menu">
                                <?php if (haspermission('VIEW_RECORDS')){echo '<li><a href="#">Registros</a></li>';}
                                if (haspermission('VIEW_RECORDS')){echo '<li><a href="sales/statistics.php">Estadísticas</a></li>   ';}
                                ?>
                                <li><a href="#">Encargos</a></li>
                                </div>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <div class="sett-icon">
                            <img width="100%" height="100%" src="../resources/settings.png" alt="Configuración" class="menu-toggle" data-target="dropdown-settings">
                            <ul>
                                <div class="dropdown-menu" id="dropdown-settings">
                                    <?php if (haspermission('CUSTOM')){echo '<li><a href="#">Personalización</a></li>';}?>
                                    <li><a href="#">Preferencias</a></li>
                                    <li><a href="#">Seguridad</a></li>
                                    <li><a href="#">Notificaciones</a></li>
                                </div>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <?php
    $buscar = isset($_POST['buscar']) ? mysqli_real_escape_string($cnx, $_GET['buscar']) : '';

if (!empty($buscar)) {
    echo "<h1 id='title'>Resultados</h1>";
    // Consulta para obtener los productos filtrados por nombre y categoría
    $sql = "SELECT * FROM products WHERE PRODUCT_NAME LIKE '%$buscar%' ORDER BY PRODUCT_NAME ASC";
} else {
    echo "<h1 id='title'>$categoria</h1>";
    // Consulta para obtener los productos de la categoría seleccionada
    $sql = "SELECT * FROM products WHERE CATEGORY = '$categoria' ORDER BY PRODUCT_NAME ASC";
}
    $res = mysqli_query($cnx, $sql) or die("Error en la consulta: " . mysqli_error($cnx));
if (mysqli_num_rows($res) > 0) {        
        echo '<div class="body_product">';
        mysqli_close($cnx);
        while ($FILA = mysqli_fetch_array($res)) {
        $contado = $FILA[2] * (($FILA[4] / 100) + 1);
        $inputId = 'cant_' . htmlspecialchars($FILA[0]); // ID dinámico para cada producto
        $units_remaining = htmlspecialchars($FILA[9]);
        $low_units = htmlspecialchars($FILA[10]);
            $badge_class = ($units_remaining > $low_units) ? 'badge-success' : 'badge-danger'; // Ejemplo de color según cantidad
        echo '<div class="product">';
        echo "<img id='img_prd' src='products/" . htmlspecialchars($FILA[3]) . "' alt='" . htmlspecialchars($FILA[1]) . "'>";
        echo "<p class='codigos'>" . htmlspecialchars($FILA[5]) . "</p>";
        echo "<h3>" . htmlspecialchars($FILA[1]) . "</h3>";
        if (haspermission('VIEW_COMPLETE_PRODUCTS')){
        echo "<p class='proveedor'>" . htmlspecialchars($FILA[6]) . "</p>";
        echo "<p>Costo: <b><label id='costo'>$" . number_format($FILA[2], 2, ',', '.') . "</label></b></p>";
        }
        echo "<p>Contado: <b><label id='contado'>$" . number_format($contado, 2, ',', '.') . "</label></b></p>";
        // Aquí es donde se muestra la cantidad de unidades restantes
        echo "<div class='units-remaining'><span class='badge $badge_class'>$units_remaining</span></div>";
        echo '<div class="icons">';
        if (haspermission('EDIT_PRODUCTS')){
        echo "<a href='products/editProd.php?id=" . htmlspecialchars($FILA[0]) . "&prod=" . htmlspecialchars($FILA[1]) . "&costo=" . htmlspecialchars($FILA[2]) . "&img=" . htmlspecialchars($FILA[3]) . "&porc=" . htmlspecialchars($FILA[4]) . "&cod=" . htmlspecialchars($FILA[5]) . "&prov=" . htmlspecialchars($FILA[6]) . "&cat=" . htmlspecialchars($FILA[8]) . "&uni=" . htmlspecialchars($FILA[7]) . "&ind=" . htmlspecialchars($categoria) . "&cantMin=" . htmlspecialchars($FILA[10])."' class='popup-link'><img class='icon' src='../resources/edit.png'></a>";
        echo "<a href='products/deleteProd.php?id=" . htmlspecialchars($FILA[0]) . "&prod=" . htmlspecialchars($FILA[1]) . "&img=" . htmlspecialchars($FILA[3]) . "&ind=" . htmlspecialchars($categoria) . "' class='popup-link'><img class='icon' src='../resources/trash.png'></a>";
        }
        echo "<a href='sales/sell.php?id=" . htmlspecialchars($FILA[5]) . "&prod=" . htmlspecialchars($FILA[1]) . "&cost=" . htmlspecialchars($FILA[2]) . "&ind=" . htmlspecialchars($categoria) . "'><img class='icon' src='../resources/car.png'></a>";
        echo "<div class='input-container'>";
        echo "<button type='button' onclick='decreaseQuantity(\"$inputId\")' class='increment-decrement'>-</button>";
        echo "<input id='$inputId' type='text' name='cantidad' class='cantidad' value='1' />";
        echo "<button type='button' onclick='increaseQuantity(\"$inputId\")' class='increment-decrement'>+</button>";
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } 
}else {
        if ($categoria) {
            echo "<h2>No hay productos en esta categoría.</h2>";
        } else {
            echo "<h2>Crea una categoría y nuevos productos</h2>";
        }
    }
    ?>
</body>
</html>
<div id="buttonContainer">
<?php  if (haspermission('VIEW_SUPPLIERS')){
    echo '
         
    <div id="addProductButton1">
        <a href="suppliers/listSuppliers.php" class="popup-link">
            <img src="../resources/sup.png" alt="Agregar Proveedor">
        </a>
    </div>
    ';
}?>
    <div id="addProductButton3">
        <a href="products/newProduct.php" class="popup-link">
            <img src="../resources/add.png" alt="Agregar Producto">
        </a>
    </div>
    <div id="addProductButton4">
        <a href="sales/sales.php" class="popup-link">
            <img src="../resources/sales.png" alt="Ventas">
        </a>
    </div>
</div>
    <!-- Primer pop-up -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <iframe id="popup-frame" src="" frameborder="0"></iframe>
        </div>
    </div>

    <!-- Segundo pop-up -->
    <div id="popup2" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <iframe id="popup-frame2" src="" frameborder="0"></iframe>
        </div>
    </div>
    </body>
    </html>