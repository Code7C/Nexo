<?php include '../users/ifSession.php'?>
<form action="load_products.php" method="post" enctype="multipart/form-data">
        <h1>Cargar productos</h1>
        <label for="excelFile">Subir archivo Excel:</label>
        <br><input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" required>
        <br><br><input type="submit" value="Subir Archivo" name="submit">
        <input type="hidden" name="supplier" value="<?php echo $sup; ?>"> 
</form>