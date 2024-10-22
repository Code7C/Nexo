    <button onclick="openPopup('addCategoryPopup')">Agregar Categoría</button>
    <table>
        <tr>
            <th>Nombre de la Categoría</th>
            <th>Acciones</th>
        </tr>
        <?php while ($category = $result_categories->fetch_assoc()): ?>
            <tr>
                <td><?php echo $category['CATEGORY_NAME']; ?></td>
                <td>
                    <button onclick="openPopup('editCategoryPopup<?php echo $category['ID']; ?>')">Editar</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="category_id" value="<?php echo $category['ID']; ?>">
                        <button type="submit" name="delete_category" onclick="return confirmDelete('Eliminar Categoría')">Eliminar</button>
                    </form>
                </td>
            </tr>
            <div class="popup" id="editCategoryPopup<?php echo $category['ID']; ?>">
                <div class="popup-content">
                    <button class="close-btn" onclick="closePopup('editCategoryPopup<?php echo $category['ID']; ?>')">X</button>
                    <h2>Editar Categoría</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="category_id" value="<?php echo $category['ID']; ?>">
                        <label for="edit_category_name">Nombre de la Categoría:</label>
                        <input type="text" name="edit_category_name" value="<?php echo $category['CATEGORY_NAME']; ?>" required>
                        <button type="submit" name="edit_category">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </table>

    <!-- Pop-up para agregar categoría -->
    <div class="popup" id="addCategoryPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup('addCategoryPopup')">X</button>
            <h2>Agregar Categoría</h2>
            <form method="POST" action="">
                <label for="new_category_name">Nombre de la Categoría:</label>
                <input type="text" name="new_category_name" required>
                <button type="submit" name="add_category">Agregar Categoría</button>
            </form>
        </div>
    </div>