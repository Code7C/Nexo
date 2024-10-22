<?php include '../users/ifSession.php'?>
<?php
//En vez de muchas consultas hacer solo 1 consulta grande con una matriz.
//Preguntar que hacer cuando el nombre de la hoja no coincida con el de niinguna categoria existente  (descartar hojas, renombrar, etc).

require '../../vendor/autoload.php';
include '../conexion.php'; // Incluye el archivo de conexión a la base de datos
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $_FILES['excelFile']['tmp_name']; // Obtén el archivo cargado
$sup = isset($_POST['supplier']) ? $_POST['supplier'] : ''; // Verifica si 'supplier' está definido

// Verifica si $sup tiene un valor
if (empty($sup)) {
    die("Error: El valor del proveedor no está definido.");
}

$nombreArchivo = $file;
$documento = IOFactory::load($nombreArchivo);
$hojaActual = $documento->getSheet(0); // Obtén la primera hoja

$nombreHojaActual = $hojaActual->getTitle(); // Obtén el nombre de la hoja actual

$numeroFilas = $hojaActual->getHighestDataRow();
$letra = $hojaActual->getHighestColumn();
$hojaCount = $documento->getSheetCount(); // Obtén el número total de hojas

$mysqli = $cnx; // Asegúrate de que $cnx es la conexión a la base de datos


for ($hojaIndice = 0; $hojaIndice < $hojaCount; $hojaIndice++) {
    $hojaActual = $documento->getSheet($hojaIndice); // Obtén la hoja actual
    $nombreHojaActual = $hojaActual->getTitle(); // Obtén el nombre de la hoja actual

    $numeroFilas = $hojaActual->getHighestDataRow();

        for ($indiceFila = 2; $indiceFila <= $numeroFilas; $indiceFila++) {
            $name = mysqli_real_escape_string($mysqli, $hojaActual->getCell('A' . $indiceFila)->getValue());
            $cost = mysqli_real_escape_string($mysqli, $hojaActual->getCell('B' . $indiceFila)->getValue());
            $percent = mysqli_real_escape_string($mysqli, $hojaActual->getCell('C' . $indiceFila)->getValue());
            $code = mysqli_real_escape_string($mysqli, $hojaActual->getCell('D' . $indiceFila)->getValue());
            $unit = mysqli_real_escape_string($mysqli, $hojaActual->getCell('E' . $indiceFila)->getValue());
            $quantity = mysqli_real_escape_string($mysqli, $hojaActual->getCell('F' . $indiceFila)->getValue());

            if (empty($code) or ($code=="") or ($code==" ")){
                $code = null;
            }

            $sql = "SELECT CATEGORY_NAME FROM categories";
            $res= mysqli_query($mysqli, $sql);

            // Verifica si la categoría ya existe
            $sql = "SELECT CATEGORY_NAME FROM categories WHERE CATEGORY_NAME = '$nombreHojaActual'";
            $res = mysqli_query($mysqli, $sql);

            // Si no existe, la insertamos
            if (mysqli_num_rows($res) == 0) {
                $sql = "INSERT INTO categories(CATEGORY_NAME) VALUES ('$nombreHojaActual')";
                mysqli_query($mysqli, $sql);
            }

            // Establece la categoría
            $category = $nombreHojaActual;


            $sql = "INSERT INTO products (PRODUCT_NAME, COST, PERCENT, CODE, SUPPLIER, CATEGORY, UNIT, QUANTITY) 
                    VALUES ('$name', '$cost', '$percent', '$code', '$sup', '$category', '$unit','$quantity')";
            if (!mysqli_query($mysqli, $sql)) {
                die("Error al insertar datos: " . mysqli_error($mysqli));
            }
}}

echo "Datos importados exitosamente.";
echo "<a id='button' href='listSuppliers.php'>Volver al Inicio</a>";
// Cerrar la conexión
mysqli_close($mysqli);
?>
