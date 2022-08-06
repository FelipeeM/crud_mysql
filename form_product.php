<?php
// Show PHP errors
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once 'classes/user.php';

$objUser = new User();
// GET
if(isset($_GET['edit_id'])){
    $id = $_GET['edit_id'];

    $stmt = $objUser->runQuery("SELECT * FROM product WHERE ID_PRODUCT = :id");
    $stmt->execute(array(":id" => $id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);

}else{
  $id = null;
  $rowUser = null;
}

// POST
if(isset($_POST['btn_save'])){
  $descriptions   = strip_tags($_POST['descriptions']);
  $unitMeasure  = strip_tags($_POST['unit_measure']);
  $price  = strip_tags($_POST['price']);

  try{
     if($id != null){
       if($objUser->updateProduct($id,$descriptions, $unitMeasure,($price*100))){
         $objUser->redirect('products.php?updated');
       }
     }else{
       if($objUser->insertProduct($descriptions, $unitMeasure,($price*100))){
         $objUser->redirect('products.php?inserted');
       }else{
         $objUser->redirect('products.php?error');
       }
     }
  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Head metas, css, and title -->
        <?php require_once 'includes/head.php'; ?>
    </head>
    <body>
        <!-- Header banner -->
        <?php require_once 'includes/header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar menu -->
                <?php require_once 'includes/sidebar.php'; ?>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                  <h1 style="margin-top: 10px">Adicionar / Editar</h1>
                  <p>Campos Requeridos(*)</p>
                  <form  method="post">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input class="form-control" type="text" name="id" id="id" value="<?php print($rowUser['ID_PRODUCT'] ?? 'Id'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="descriptions">Descrição *</label>
                        <input  class="form-control" type="text" name="descriptions" id="descriptions"  placeholder="Nome do Produto" value="<?php print($rowUser['DESCRIPTION_P'] ?? ''); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="unit_measure">Unidade de Medida*</label>
                        <input  class="form-control" type="text" name="unit_measure" id="unit_measure"  placeholder="Unidade de medida do produto (KG)" value="<?php print($rowUser['UNIT_MEASURE'] ?? ''); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="price">Preço *</label>
                        <input  class="form-control" type="float" name="price" id="price"  placeholder="Preço (R$)" value="<?php 
                        if(isset(($rowUser['PRICE']))){
                          print(($rowUser['PRICE']/100));
                        }else {
                          print('');
                        }
                        ?>" required maxlength="100">
                    </div>
                    <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Salvar">
                  </form>
                </main>
            </div>
        </div>
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>
    </body>
</html>
