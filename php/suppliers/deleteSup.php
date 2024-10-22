<?php include '../users/ifSession.php'?>
  <div>
    	<h1>¿Esta seguro/a de borrar el proveedor: <label id='nameProd'><?php echo $_REQUEST['prov']?></label>?</h1>
			<form action="deleteSup_DB.php" method="GET">
				<input type="hidden" name="prove" value="<?php echo $_REQUEST['prov']; ?>">
				<input type="submit" name="eliminar" value="Aceptar"> 
			</form>
			<button  class="button" onclick="location.href='listSuppliers.php'">Cancelar</button>
  </div>