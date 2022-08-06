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

    $stmt = $objUser->runQuery("SELECT * FROM payment_form WHERE ID_PAYMENT_FORM = :id");
    $stmt->execute(array(":id" => $id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);

}else{
  $id = null;
  $rowUser = null;
}

// POST
if(isset($_POST['btn_save'])){
  $description   = strip_tags($_POST['description']);


  try{
     if($id != null){
       if($objUser->updatePaymentForm($id,$description)){
         $objUser->redirect('payment.php?updated');
       }
     }else{
       if($objUser->insertPaymentForm($description)){
         $objUser->redirect('payment.php?inserted');
       }else{
         $objUser->redirect('payment.php?error');
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
                  <p>Campos Requeridos (*)</p>
                  <form  method="post">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input class="form-control" type="text" name="id" id="id" value="<?php print($rowUser['ID_PAYMENT_FORM'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição *</label>
                        <input  class="form-control" type="text" name="description" id="description" placeholder="Nome do modo de pagamento" value="<?php print($rowUser['DESCRIPTION'] ?? ''); ?>" required maxlength="100">
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
