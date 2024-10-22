<?php include '../users/ifSession.php'; ?>

<?php
// Verificar si 'name' e 'id' están presentes en la solicitud
if (!isset($_REQUEST['name']) || !isset($_REQUEST['id'])) {
    echo "<p>Error: Parámetros 'name' o 'id' faltantes. <a href='listCategories.php'>Volver a la lista</a></p>";
    exit; // Salir si no se encuentran los parámetros necesarios
}
?>

<div>
  <h1>¿Está seguro/a de borrar la categoría: 
    <label id='nameProd'><?php echo htmlspecialchars($_REQUEST['name']); ?></label>?
  </h1>
  
  <form action="deleteCat_DB.php" method="GET">
    <input type="hidden" name="cat" value="<?php echo htmlspecialchars($_REQUEST['id']); ?>">
    <input type="hidden" name="nameCat" value="<?php echo htmlspecialchars($_REQUEST['name']); ?>">
    <input type="submit" name="eliminar" value="Aceptar">
  </form>
  
  <button class="button" onclick="location.href='listCategories.php'">Cancelar</button>
</div>
