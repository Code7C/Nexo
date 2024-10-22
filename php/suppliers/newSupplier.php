<?php include '../users/ifSession.php'?>
    <div class="container">
        <h1 class="title">Nuevo Proveedor</h1>
        <form method="POST" action="saveSup.php" enctype="multipart/form-data" class="supplier-form">
            <input type="text" name="prov" class="input" placeholder="Nombre de la Empresa" required>
            <input type="text" name="name" class="input" placeholder="Nombre del Representante" required>
            <input type="text" name="cont" class="input" placeholder="Contacto" required>
            <input type="submit" name="enviar" value="Guardar Proveedor" class="button">
            <button  class="button" onclick="location.href='listSuppliers.php'">Cancelar</button>
        </form>
    </div>