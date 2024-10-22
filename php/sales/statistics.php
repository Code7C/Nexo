<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        #chart-container, #table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        canvas {
            display: block;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div id="chart-container">
    <h2>Ventas por Mes</h2>
    <canvas id="salesChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Ingresos Totales</h2>
    <canvas id="incomeChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Egresos Totales</h2>
    <canvas id="expensesChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Costos Totales</h2>
    <canvas id="costsChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Productos Más Vendidos</h2>
    <canvas id="topProductsChart" width="400" height="200"></canvas>
</div>

<div id="table-container">
    <h2>Proveedores</h2>
    <table id="suppliersTable">
        <thead>
            <tr>
                <th>Proveedor</th>
                <th>Número de Productos</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se llenará con PHP -->
        </tbody>
    </table>
</div>

<div id="chart-container">
    <h2>Total de Ventas Acumuladas</h2>
    <canvas id="totalSalesChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Comparación de Ventas</h2>
    <canvas id="comparisonChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Distribución de Ventas por Categoría</h2>
    <canvas id="categoryDistributionChart" width="400" height="200"></canvas>
</div>

<div id="chart-container">
    <h2>Tendencias de Ventas</h2>
    <canvas id="salesTrendChart" width="400" height="200"></canvas>
</div>

<?php
// Conexión a la base de datos
include '../conexion.php';

// 1. Ventas por mes
$sql = "SELECT MONTH(DATE) as mes, SUM(VOUCHER) as total_ventas FROM record GROUP BY mes";
$resultado = mysqli_query($cnx, $sql);
$ventasMensuales = array_fill(0, 12, 0); 

while($fila = mysqli_fetch_assoc($resultado)) {
    $ventasMensuales[intval($fila['mes']) - 1] = $fila['total_ventas'];
}

// 2. Ingresos totales
$sql_ingresos = "SELECT SUM(VOUCHER) as total_ingresos FROM record";
$result_ingresos = mysqli_fetch_assoc(mysqli_query($cnx, $sql_ingresos));
$total_ingresos = $result_ingresos['total_ingresos'];

// 3. Egresos totales
$sql_egresos = "SELECT SUM(COST) as total_egresos FROM record_products";
$result_egresos = mysqli_fetch_assoc(mysqli_query($cnx, $sql_egresos));
$total_egresos = $result_egresos['total_egresos'];

// 4. Costos totales
$sql_costos = "SELECT SUM(PRICE * QUANTITY) as total_costos FROM record_products";
$result_costos = mysqli_fetch_assoc(mysqli_query($cnx, $sql_costos));
$total_costos = $result_costos['total_costos'];

// 5. Productos más vendidos
$sql_top_products = "SELECT p.PRODUCT_NAME, SUM(rp.QUANTITY) as total_vendido FROM record_products rp JOIN products p ON rp.PRODUCT = p.ID GROUP BY p.PRODUCT_NAME ORDER BY total_vendido DESC LIMIT 5";
$result_top_products = mysqli_query($cnx, $sql_top_products);
$topProducts = [];
while($row = mysqli_fetch_assoc($result_top_products)) {
    $topProducts[] = $row;
}

// 6. Proveedores
$sql_suppliers = "SELECT s.COMPANY, COUNT(p.ID) as total_productos FROM suppliers s LEFT JOIN products p ON s.ID = p.SUPPLIER GROUP BY s.COMPANY";
$result_suppliers = mysqli_query($cnx, $sql_suppliers);

// 7. Total de ventas acumuladas
$total_ventas_acumuladas = array_sum($ventasMensuales);

// 8. Comparación de Ventas
$mes_actual = date('m');
$mes_anterior = $mes_actual - 1;
if ($mes_anterior < 1) $mes_anterior = 12; // Retroceder al año anterior
$ventas_mes_anterior = isset($ventasMensuales[$mes_anterior - 1]) ? $ventasMensuales[$mes_anterior - 1] : 0;

// 9. Distribución de Ventas por Categoría
$sql_categoria = "SELECT c.CATEGORY_NAME, SUM(rp.PRICE * rp.QUANTITY) as total_ventas FROM record_products rp JOIN products p ON rp.PRODUCT = p.ID JOIN categories c ON p.CATEGORY = c.ID GROUP BY c.CATEGORY_NAME";
$result_categoria = mysqli_query($cnx, $sql_categoria);
$categorias = [];
while($row = mysqli_fetch_assoc($result_categoria)) {
    $categorias[] = $row;
}

// 10. Tendencias de Ventas
$sql_tendencias = "SELECT DATE_FORMAT(DATE, '%Y-%m') as mes, SUM(VOUCHER) as total_ventas FROM record GROUP BY mes ORDER BY mes";
$result_tendencias = mysqli_query($cnx, $sql_tendencias);
$tendencias = [];
while($row = mysqli_fetch_assoc($result_tendencias)) {
    $tendencias[] = $row;
}

mysqli_close($cnx);
?>

<script>
    // Datos obtenidos desde PHP
    const ventasMensuales = <?php echo json_encode(array_values($ventasMensuales)); ?>;
    const totalIngresos = <?php echo $total_ingresos; ?>;
    const totalEgresos = <?php echo $total_egresos; ?>;
    const totalCostos = <?php echo $total_costos; ?>;
    const totalVentasAcumuladas = <?php echo $total_ventas_acumuladas; ?>;
    const ventasMesAnterior = <?php echo $ventas_mes_anterior; ?>;

    // Productos más vendidos
    const topProducts = <?php echo json_encode($topProducts); ?>;
    const productNames = topProducts.map(p => p.PRODUCT_NAME);
    const productSales = topProducts.map(p => p.total_vendido);

    // Distribución de Ventas por Categoría
    const categoriesData = <?php echo json_encode($categorias); ?>;
    const categoryNames = categoriesData.map(c => c.CATEGORY_NAME);
    const categorySales = categoriesData.map(c => c.total_ventas);

    // Tendencias de Ventas
    const tendenciasData = <?php echo json_encode($tendencias); ?>;
    const tendenciasMeses = tendenciasData.map(t => t.mes);
    const tendenciasVentas = tendenciasData.map(t => t.total_ventas);

    // Ventas por mes
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Ventas por Mes',
                data: ventasMensuales,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Ingresos Totales
    const ctxIncome = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(ctxIncome, {
        type: 'bar',
        data: {
            labels: ['Ingresos Totales'],
            datasets: [{
                label: 'Total Ingresos',
                data: [totalIngresos],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Egresos Totales
    const ctxExpenses = document.getElementById('expensesChart').getContext('2d');
    const expensesChart = new Chart(ctxExpenses, {
        type: 'bar',
        data: {
            labels: ['Egresos Totales'],
            datasets: [{
                label: 'Total Egresos',
                data: [totalEgresos],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Costos Totales
    const ctxCosts = document.getElementById('costsChart').getContext('2d');
    const costsChart = new Chart(ctxCosts, {
        type: 'bar',
        data: {
            labels: ['Costos Totales'],
            datasets: [{
                label: 'Total Costos',
                data: [totalCostos],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Productos Más Vendidos
    const ctxTopProducts = document.getElementById('topProductsChart').getContext('2d');
    const topProductsChart = new Chart(ctxTopProducts, {
        type: 'pie',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Productos Más Vendidos',
                data: productSales,
                backgroundColor: [
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        }
    });

    // Total de Ventas Acumuladas
    const ctxTotalSales = document.getElementById('totalSalesChart').getContext('2d');
    const totalSalesChart = new Chart(ctxTotalSales, {
        type: 'bar',
        data: {
            labels: ['Total Ventas Acumuladas'],
            datasets: [{
                label: 'Ventas Acumuladas',
                data: [totalVentasAcumuladas],
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Comparación de Ventas
    const ctxComparison = document.getElementById('comparisonChart').getContext('2d');
    const comparisonChart = new Chart(ctxComparison, {
        type: 'bar',
        data: {
            labels: ['Este Mes', 'Mes Pasado'],
            datasets: [{
                label: 'Comparación de Ventas',
                data: [ventasMensuales[new Date().getMonth()], ventasMesAnterior],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Distribución de Ventas por Categoría
    const ctxCategoryDistribution = document.getElementById('categoryDistributionChart').getContext('2d');
    const categoryDistributionChart = new Chart(ctxCategoryDistribution, {
        type: 'pie',
        data: {
            labels: categoryNames,
            datasets: [{
                label: 'Distribución de Ventas por Categoría',
                data: categorySales,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        }
    });

    // Tendencias de Ventas
    const ctxSalesTrend = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(ctxSalesTrend, {
        type: 'line',
        data: {
            labels: tendenciasMeses,
            datasets: [{
                label: 'Tendencias de Ventas',
                data: tendenciasVentas,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Rellenar tabla de proveedores
    const suppliersTable = document.getElementById('suppliersTable').querySelector('tbody');
    const suppliersData = <?php echo json_encode(mysqli_fetch_all($result_suppliers, MYSQLI_ASSOC)); ?>;
    suppliersData.forEach(supplier => {
        const row = document.createElement('tr');
        row.innerHTML = `<td>${supplier.COMPANY}</td><td>${supplier.total_productos}</td>`;
        suppliersTable.appendChild(row);
    });
</script>
</body>
</html>