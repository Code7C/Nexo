<?php include '../users/ifSession.php'?>
    <div>
        <h1>¿Está seguro/a de borrar el producto: <span class='nameProd' id='nameProd'><?php echo htmlspecialchars($_REQUEST['prod']); ?></span>?</h1>
        <div class="img-container">
            <img class="product-img" src="<?php echo htmlspecialchars($_REQUEST['img']); ?>" alt="<?php echo htmlspecialchars($_REQUEST['prod']); ?>">
        </div>
        <form action="deleteProd_DB.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_REQUEST['id']); ?>">
            <input type="submit" name="eliminar" value="Aceptar"> 
        </form>
        <button onclick="location.href=''">Cancelar</button>
    </div>