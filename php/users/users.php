    <?php 
    if (!haspermission('VIEW_USERS')){
        header("Location: ../main.php");
    }
    ?>
    <button onclick="openPopup('addUserPopup')">Agregar Usuario</button>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <?php if (hasPermission('VIEW_PASSWORD')) {echo '<th>Contraseña</th>';}?>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php while ($user = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['NAME_USER'];?></td>
                <td><?php echo $user['LASTNAME_USER'];?></td>
                <td><?php echo $user['EMAIL']; ?></td>
                <?php if (hasPermission('VIEW_PASSWORD')) {echo '<td>' . $user['PASSWORD'] . '</td>';}?>
                <td><?php echo $user['ROLE_NAME'];?></td>
                <td>
                    <button onclick="openPopup('editUserPopup<?php echo $user['ID']; ?>')">Editar</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['ID']; ?>">
                        <button type="submit" name="delete_user" onclick="return confirmDelete('Eliminar Usuario')">Eliminar</button>
                    </form>
                </td>
            </tr>
            <div class="popup" id="editUserPopup<?php echo $user['ID']; ?>">
                <div class="popup-content">
                    <button class="close-btn" onclick="closePopup('editUserPopup<?php echo $user['ID']; ?>')">X</button>
                    <h2>Editar Usuario</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?php echo $user['ID']; ?>">
                        <label for="edit_name_user">Nombre:</label>
                        <input type="text" name="edit_name_user" value="<?php echo $user['NAME_USER']; ?>" required>
                        <label for="edit_lastname_user">Apellido:</label>
                        <input type="text" name="edit_lastname_user" value="<?php echo $user['LASTNAME_USER']; ?>" required>
                        <label for="edit_role_id">Rol:</label>
                        <select name="edit_role_id" required>
                            <?php
                            $result_roles = $mysqli->query("SELECT * FROM roles");
                            while ($role = $result_roles->fetch_assoc()): ?>
                                <option value="<?php echo $role['ID']; ?>" <?php echo $role['ID'] == $user['ROLE_ID'] ? 'selected' : ''; ?>><?php echo $role['ROLE_NAME']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" name="edit_user">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </table>

    <!-- Pop-up para agregar usuario -->
    <div class="popup" id="addUserPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup('addUserPopup')">X</button>
            <h2>Agregar Usuario</h2>
            <form method="POST" action="">
                <label for="new_name_user">Nombre:</label>
                <input type="text" name="new_name_user" required>
                <label for="new_lastname_user">Apellido:</label>
                <input type="text" name="new_lastname_user" required>
                <label for="new_role_id">Rol:</label>
                <select name="new_role_id" required>
                    <?php
                    $result_roles = $mysqli->query("SELECT * FROM roles");
                    while ($role = $result_roles->fetch_assoc()): ?>
                        <option value="<?php echo $role['ID']; ?>"><?php echo $role['ROLE_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="add_user">Agregar Usuario</button>
            </form>
        </div>
    </div>