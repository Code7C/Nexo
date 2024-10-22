<?php
include '../conexion.php';
include '../users/ifSession.php';
include 'hasPermissions.php';
if (!haspermission('ACCESS_ADMINISTRATION')){
    header("Location: ../main.php");
}
$mysqli = $cnx;

// Obtener datos de usuarios, roles, permisos, categorías y productos
$result_users = $mysqli->query("
    SELECT u.ID, u.NAME_USER, u.LASTNAME_USER,u.EMAIL, u.PASSWORD, u.ROLE_ID, r.ROLE_NAME FROM users u LEFT JOIN roles r ON u.ROLE_ID = r.ID
");
// Consulta para obtener roles
$result_categories = $mysqli->query("SELECT * FROM categories");
$result_products = $mysqli->query("SELECT * FROM products ORDER BY PRODUCT_NAME ASC");

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar usuario
    if (isset($_POST['add_user'])) {
        $new_name_user = $mysqli->real_escape_string($_POST['new_name_user']);
        $new_lastname_user = $mysqli->real_escape_string($_POST['new_lastname_user']);
        $new_role_id = (int)$_POST['new_role_id'];
        if ($mysqli->query("INSERT INTO users (NAME_USER, LASTNAME_USER, ROLE_ID) VALUES ('$new_name_user', '$new_lastname_user', '$new_role_id')") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Editar usuario
    if (isset($_POST['edit_user'])) {
        $user_id = (int)$_POST['user_id'];
        $edit_name_user = $mysqli->real_escape_string($_POST['edit_name_user']);
        $edit_lastname_user = $mysqli->real_escape_string($_POST['edit_lastname_user']);
        $edit_role_id = (int)$_POST['edit_role_id'];
        if ($mysqli->query("UPDATE users SET NAME_USER = '$edit_name_user', LASTNAME_USER = '$edit_lastname_user', ROLE_ID = '$edit_role_id' WHERE ID = $user_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Eliminar usuario
    if (isset($_POST['delete_user'])) {
        $user_id = (int)$_POST['user_id'];
        if ($mysqli->query("DELETE FROM users WHERE ID = $user_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

   // Agregar rol
if (isset($_POST['new_role_name'])) {
    $roleName = $mysqli->real_escape_string($_POST['new_role_name']);
    include 'roles/permissions.php'; // Asegúrate de que $permissions sea un array asociativo

    // Inicializa permisos a 0
    foreach ($permissions as $key => $value) {
        $permissions[$key] = 0; // Inicializa todos los permisos en 0
    }

    // Procesar permisos
    if (isset($_POST['permissions'])) {
        foreach ($_POST['permissions'] as $perm) {
            if (array_key_exists($perm, $permissions)) {
                $permissions[$perm] = 1; // Establece en 1 si está marcado
            }
        }
    }

    $query = "INSERT INTO roles (ROLE_NAME, VIEW_CATEGORIES, EDIT_CATEGORIES, VIEW_SUPPLIERS, EDIT_SUPPLIERS, 
        VIEW_COMPLETE_PRODUCTS, EDIT_PRODUCTS, VIEW_RECORDS, EDIT_RECORDS, VIEW_USERS, EDIT_USERS, 
        EDIT_ACCESS, CUSTOM, ACCESS_CART, ACCESS_ADMINISTRATION, ACCESS_ORDERS, ACCESS_STATISTICS, VIEW_ROLES, EDIT_ROLES) 
        VALUES ('$roleName', {$permissions['VIEW_CATEGORIES']}, {$permissions['EDIT_CATEGORIES']}, 
        {$permissions['VIEW_SUPPLIERS']}, {$permissions['EDIT_SUPPLIERS']}, {$permissions['VIEW_COMPLETE_PRODUCTS']}, 
        {$permissions['EDIT_PRODUCTS']}, {$permissions['VIEW_RECORDS']}, {$permissions['EDIT_RECORDS']}, 
        {$permissions['VIEW_USERS']}, {$permissions['EDIT_USERS']}, {$permissions['EDIT_ACCESS']}, 
        {$permissions['CUSTOM']}, {$permissions['ACCESS_CART']}, {$permissions['ACCESS_ADMINISTRATION']}, 
        {$permissions['ACCESS_ORDERS']}, {$permissions['ACCESS_STATISTICS']}, {$permissions['VIEW_PASSWORD']},
        {$permissions['VIEW_ROLES']},{$permissions['EDIT_ROLES']})";

    if ($mysqli->query($query) === TRUE) {
        //echo "Rol agregado exitosamente.";
    } else {
        echo "Error al agregar el rol: " . $mysqli->error;
    }
}

    // Eliminar rol
    if (isset($_POST['delete_role'])) {
        $role_id = (int)$_POST['role_id'];
        if ($mysqli->query("DELETE FROM roles WHERE ID = $role_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Agregar categoría
    if (isset($_POST['add_category'])) {
        $new_category_name = $mysqli->real_escape_string($_POST['new_category_name']);
        if ($mysqli->query("INSERT INTO categories (CATEGORY_NAME) VALUES ('$new_category_name')") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Editar categoría
    if (isset($_POST['edit_category'])) {
        $category_id = (int)$_POST['category_id'];
        $edit_category_name = $mysqli->real_escape_string($_POST['edit_category_name']);
        if ($mysqli->query("UPDATE categories SET CATEGORY_NAME = '$edit_category_name' WHERE ID = $category_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Eliminar categoría
    if (isset($_POST['delete_category'])) {
        $category_id = (int)$_POST['category_id'];
        if ($mysqli->query("DELETE FROM categories WHERE ID = $category_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Agregar proveedor
if (isset($_POST['add_supplier'])) {
    $new_supplier_name = $mysqli->real_escape_string($_POST['new_supplier_name']);
    $new_supplier_represent = $mysqli->real_escape_string($_POST['new_supplier_represent']);
    $new_supplier_contact = $mysqli->real_escape_string($_POST['new_supplier_contact']);

    // Inserta el nuevo proveedor en la base de datos
    $sql = "INSERT INTO suppliers (COMPANY, NAME, CONTACT) VALUES ('$new_supplier_name', '$new_supplier_represent', '$new_supplier_contact')";

    if ($mysqli->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}


// Editar proveedor
if (isset($_POST['edit_supplier'])) {
    $supplier_id = (int)$_POST['supplier_id'];
    $edit_supplier_name = $mysqli->real_escape_string($_POST['supplier_name']);
    $edit_supplier_represent = $mysqli->real_escape_string($_POST['supplier_represent']);
    $edit_supplier_contact = $mysqli->real_escape_string($_POST['supplier_contact']);

    // Actualiza los datos del proveedor en la base de datos
    $sql = "UPDATE suppliers SET COMPANY = '$edit_supplier_name', NAME = '$edit_supplier_represent', CONTACT = '$edit_supplier_contact' WHERE ID = $supplier_id";

    if ($mysqli->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}

// Eliminar proveedor
if (isset($_POST['delete_supplier'])) {
    $supplier_id = (int)$_POST['supplier_id'];
    if ($mysqli->query("DELETE FROM suppliers WHERE ID = $supplier_id") === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}



    // Agregar producto
    if (isset($_POST['add_product'])) {
        $new_product_name = $mysqli->real_escape_string($_POST['new_product_name']);
        $new_product_cost = (float)$_POST['new_product_cost'];
        $new_product_price = (float)$_POST['new_product_price'];
        $new_product_category_id = (int)$_POST['new_product_category_id'];
        if ($mysqli->query("INSERT INTO products (PRODUCT_NAME, COST, PRICE, CATEGORY_ID) VALUES ('$new_product_name', '$new_product_cost', '$new_product_price', '$new_product_category_id')") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Editar producto
    if (isset($_POST['edit_product'])) {
        $product_id = (int)$_POST['product_id'];
        $edit_product_name = $mysqli->real_escape_string($_POST['edit_product_name']);
        $edit_product_cost = (float)$_POST['edit_product_cost'];
        $edit_product_price = (float)$_POST['edit_product_price'];
        $edit_product_category_id = (int)$_POST['edit_product_category_id'];
        if ($mysqli->query("UPDATE products SET PRODUCT_NAME = '$edit_product_name', COST = '$edit_product_cost', PRICE = '$edit_product_price', CATEGORY_ID = '$edit_product_category_id' WHERE ID = $product_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Eliminar producto
    if (isset($_POST['delete_product'])) {
        $product_id = (int)$_POST['product_id'];
        if ($mysqli->query("DELETE FROM products WHERE ID = $product_id") === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

   

    if (isset($_POST['edit_role'])) {
        $role_id = (int)$_POST['role_id']; // ID del rol a editar
        $edit_role_name = $mysqli->real_escape_string($_POST['edit_role_name']); // Nombre del rol
    
        // Incluir los permisos desde roles/permissions.php
        include 'roles/permissions.php';
    
        // Reiniciar permisos a default (0)
        $permissions = array_map(function() { return 0; }, $permissions);
    
        // Actualizar permisos basado en la entrada del formulario
        if (isset($_POST['permissions'])) {
            foreach ($_POST['permissions'] as $permission_key) {
                if (array_key_exists($permission_key, $permissions)) {
                    $permissions[$permission_key] = 1; // Si está marcado, establecer en 1
                }
            }
        }
    
        // Ejecutar la consulta de actualización
        $query = "UPDATE roles SET 
            ROLE_NAME = '$edit_role_name', 
            VIEW_CATEGORIES = {$permissions['VIEW_CATEGORIES']}, 
            EDIT_CATEGORIES = {$permissions['EDIT_CATEGORIES']}, 
            VIEW_SUPPLIERS = {$permissions['VIEW_SUPPLIERS']}, 
            EDIT_SUPPLIERS = {$permissions['EDIT_SUPPLIERS']}, 
            VIEW_COMPLETE_PRODUCTS = {$permissions['VIEW_COMPLETE_PRODUCTS']}, 
            EDIT_PRODUCTS = {$permissions['EDIT_PRODUCTS']}, 
            VIEW_RECORDS = {$permissions['VIEW_RECORDS']}, 
            EDIT_RECORDS = {$permissions['EDIT_RECORDS']}, 
            VIEW_USERS = {$permissions['VIEW_USERS']}, 
            EDIT_USERS = {$permissions['EDIT_USERS']}, 
            EDIT_ACCESS = {$permissions['EDIT_ACCESS']}, 
            CUSTOM = {$permissions['CUSTOM']}, 
            ACCESS_CART = {$permissions['ACCESS_CART']}, 
            ACCESS_ADMINISTRATION = {$permissions['ACCESS_ADMINISTRATION']}, 
            ACCESS_ORDERS = {$permissions['ACCESS_ORDERS']}, 
            ACCESS_STATISTICS = {$permissions['ACCESS_STATISTICS']}, 
            VIEW_PASSWORD = {$permissions['VIEW_PASSWORD']},
            VIEW_ROLES = {$permissions['VIEW_ROLES']},
            EDIT_ROLES = {$permissions['EDIT_ROLES']}
            WHERE ID = $role_id";
    
        if ($mysqli->query($query) === TRUE) {
            // Consultar los permisos del rol actualizado para la sesión
            $sql = "SELECT 
                r.ID AS Role_ID,
                r.ROLE_NAME,
                r.VIEW_CATEGORIES,
                r.EDIT_CATEGORIES,
                r.VIEW_SUPPLIERS,
                r.EDIT_SUPPLIERS,
                r.VIEW_COMPLETE_PRODUCTS,
                r.EDIT_PRODUCTS,
                r.VIEW_RECORDS,
                r.EDIT_RECORDS,
                r.VIEW_USERS,
                r.EDIT_USERS,
                r.EDIT_ACCESS,
                r.CUSTOM,
                r.ACCESS_CART,
                r.ACCESS_ADMINISTRATION,
                r.ACCESS_ORDERS,
                r.ACCESS_STATISTICS,
                r.VIEW_PASSWORD,
                r.VIEW_ROLES,
                r.EDIT_ROLES
                FROM users u
                JOIN roles r ON u.ROLE_ID = r.ID
                WHERE u.ID = ?"; // Cambié a un placeholder
    
            $stmt = $cnx->prepare($sql);
            $stmt->bind_param("i", $role_id); // Solo un parámetro
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Guardar permisos en la sesión
            if ($result->num_rows > 0) {
                $permissions = $result->fetch_assoc();
                $_SESSION['permissions'] = $permissions; // Guardar permisos en la sesión
            }
    
            // Redirigir a la página de administración
            header("Location: admin.php");
            exit();
        } else {
            // Manejar el error
            echo "Error: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../../css/users/admin.css">
    <style>
        .section-container {
            display: none; /* Ocultar las secciones por defecto */
            margin-top: 10px;
        }

        .section-button {
            margin: 10px 0;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 28px;
            text-align: center;
        }

        .section-button:hover {
            background-color: #45a049;
        }

    </style>
    <script>
        function toggleSection(sectionId) {
            var section = document.getElementById(sectionId);
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block"; // Mostrar la sección
            } else {
                section.style.display = "none"; // Ocultar la sección
            }
        }
    </script>
</head>
<body>

<div class="admin-container">
    <a href="../main.php">Volver atrás</a>
    <a href="logout.php">Cerrar Sesión</a>
    <h1>Panel de Administración</h1>

    <!-- Botón para usuarios -->
    <?php if (haspermission('VIEW_USERS')): ?>
        <button class="section-button" onclick="toggleSection('users-section')">Usuarios</button>
        <div id="users-section" class="section-container">
            <?php include 'users.php'; ?>
        </div>
    <?php endif; ?>

    <!-- Botón para roles -->
    <?php if (haspermission('VIEW_ROLES')): ?>
        <button class="section-button" onclick="toggleSection('roles-section')">Roles</button>
        <div id="roles-section" class="section-container">
            <?php include 'roles/roles.php'; ?>
        </div>
    <?php endif; ?>

    <!-- Botón para categorías -->
    <?php if (haspermission('VIEW_CATEGORIES')): ?>
        <button class="section-button" onclick="toggleSection('categories-section')">Categorías</button>
        <div id="categories-section" class="section-container">
            <?php include '../categories/categories.php'; ?>
        </div>
    <?php endif; ?>

    <!-- Botón para proveedores -->
    <?php if (haspermission('VIEW_SUPPLIERS')): ?>
    <button class="section-button" onclick="toggleSection('suppliers-section')">Proveedores</button>
    <div id="suppliers-section" class="section-container">
        <?php include '../suppliers/suppliers.php'; ?>
    </div>
    <?php endif; ?>

    <!-- Botón para productos -->
    <button class="section-button" onclick="toggleSection('products-section')">Productos</button>
    <div id="products-section" class="section-container">
        <?php include '../products/products.php'; ?>
    </div>
</div>
</body>
</html>