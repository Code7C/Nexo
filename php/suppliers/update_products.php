<?php include '../users/ifSession.php'?>
<?php 
require '../../vendor/autoload.php';
include '../conexion.php'; // Incluye el archivo de conexión a la base de datos
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $_FILES['excelFile2']['tmp_name']; // Obtén el archivo cargado
$sup = isset($_POST['supplier']) ? $_POST['supplier'] : ''; // Verifica si 'supplier' está definido

// Verifica si $sup tiene un valor
if (empty($sup)) {
    die("Error: El valor del proveedor no está definido.");
}

$nombreArchivo = $file;
$documento = IOFactory::load($nombreArchivo);
$hojaActual = $documento->getSheet(0); // Obtén la primera hoja

$numeroFilas = $hojaActual->getHighestDataRow();
$letra = $hojaActual->getHighestColumn();

$mysqli = $cnx; // Asegúrate de que $cnx es la conexión a la base de datos

for ($indiceFila = 2; $indiceFila <= $numeroFilas; $indiceFila++) {
    $name = mysqli_real_escape_string($mysqli, $hojaActual->getCell('A' . $indiceFila)->getValue());
    $cost = mysqli_real_escape_string($mysqli, $hojaActual->getCell('B' . $indiceFila)->getValue());
    $code = mysqli_real_escape_string($mysqli, $hojaActual->getCell('B' . $indiceFila)->getValue());
    $sql =  "UPDATE products SET COST = '$cost' WHERE  CODE = '$code' OR PRODUCT_NAME = '$name'";
    if (!mysqli_query($mysqli, $sql)) {
        die("Error al insertar datos: " . mysqli_error($mysqli));
    }
}

echo "Productos Actualizados exitosamente.";
echo "<a id='button' href='listSuppliers.php'>Volver al Inicio</a>";
// Cerrar la conexión
mysqli_close($mysqli);
?>