    <button onclick="openPopup('addProductPopup')">Agregar Producto</button>
    <table>
        <tr>
            <th>Código</th>
            <th>Nombre del Producto</th>
            <th>Costo</th>
            <th>Categoría</th>
            <th>Proveedor</th>
            <th>Acciones</th>
        </tr>
        <?php while ($product = $result_products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $product['CODE']; ?></td>
                <td><?php echo $product['PRODUCT_NAME']; ?></td>
                <td><?php echo $product['COST']; ?></td>
                <td><?php echo $product['CATEGORY']; ?></td>
                <td><?php echo $product['SUPPLIER']; ?></td>
                <td>
                    <button onclick="openPopup('editProductPopup<?php echo $product['ID']; ?>')">Editar</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['ID']; ?>">
                        <button type="submit" name="delete_product" onclick="return confirmDelete('Eliminar Producto')">Eliminar</button>
                    </form>
                </td>
            </tr>
            <div class="popup" id="editProductPopup<?php echo $product['ID']; ?>">
                <div class="popup-content">
                    <button class="close-btn" onclick="closePopup('editProductPopup<?php echo $product['ID']; ?>')">X</button>
                    <h2>Editar Producto</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product['ID']; ?>">
                        <label for="edit_product_name">Nombre del Producto:</label>
                        <input type="text" name="edit_product_name" value="<?php echo $product['PRODUCT_NAME']; ?>" required>
                        <label for="edit_product_cost">Costo:</label>
                        <input type="number" step="0.01" name="edit_product_cost" value="<?php echo $product['PRODUCT_COST']; ?>" required>
                        <label for="edit_category_id">Categoría:</label>
                        <select name="edit_category_id" required>
                            <?php
                            $result_categories = $mysqli->query("SELECT * FROM categories");
                            while ($category = $result_categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['ID']; ?>" <?php echo $category['ID'] == $product['CATEGORY_ID'] ? 'selected' : ''; ?>><?php echo $category['CATEGORY_NAME']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" name="edit_product">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </table>

    <!-- Pop-up para agregar producto -->
    <div class="popup" id="addProductPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup('addProductPopup')">X</button>
            <h2>Agregar Producto</h2>
            <form method="POST" action="">
                <label for="new_product_name">Nombre del Producto:</label>
                <input type="text" name="new_product_name" required>
                <label for="new_product_cost">Costo:</label>
                <input type="number" step="0.01" name="new_product_cost" required>
                <label for="new_category_id">Categoría:</label>
                <select name="new_category_id" required>
                    <?php
                    $result_categories = $mysqli->query("SELECT * FROM categories");
                    while ($category = $result_categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['ID']; ?>"><?php echo $category['CATEGORY_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="add_product">Agregar Producto</button>
            </form>
        </div>
    </div>