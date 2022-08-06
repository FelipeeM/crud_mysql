<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<?php
// Show PHP errors
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once 'classes/user.php';
global $pricePrd;
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
  $pricePrd = strip_tags($_POST['prdselect']);


  try{
     if($id != null){
       if($objUser->updateProduct($id,$descriptions, $unitMeasure,$price)){
         $objUser->redirect('products.php?updated');
       }
     }else{
       if($objUser->insertProduct($descriptions, $unitMeasure,$price)){
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
                  <p>Campos requeridos (*)</p>
                  <form  id="form_validation" method="post">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input class="form-control" type="text" name="id" id="id" value="<?php print($rowUser['ID_PRODUCT'] ?? 'Id'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="descriptions">Cliente *</label>                        
                        <select name="select" name="selectClient" class="form-control">
                          <option>Selecione</option>
                          <?php
                                $query = "SELECT * FROM client";
                                $stmt = $objUser->runQuery($query);
                                $stmt->execute();
                              ?>
                          <?php if($stmt->rowCount() > 0){
                                    while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                                  ?>
                          <option class="selectProduct" value="<?php print($rowUser['CLIENT_NAME']); ?>"><?php print($rowUser['CLIENT_NAME']); ?></option>
                          
                          <?php } } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unit_measure">Produto*</label>
                        
                          <select placeholder="Nome do Produto" name="select_prds" id="select_prd" required class="form-control">
                            <option>Selecione</option>
                            <?php
                                  $query = "SELECT * FROM product";
                                  $stmt = $objUser->runQuery($query);
                                  $stmt->execute();
                                ?>
                            <?php if($stmt->rowCount() > 0){
                                      while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                            <option class="selectProduct" value="<?php print($rowUser['PRICE']); ?>"><?php print($rowUser['DESCRIPTION_P']); ?></option>
                            
                            <?php } } ?>
                          </select>
                        
                    </div>
                    <div class="form-group">
                      <label for="unit_measure">Preço</label>
                      <input  class="form-control" type="text" name="prd_price" id="prd_price" required readonly maxlength="100">                                                             
                    </div>
                    <div class="form-group">
                        <label for="unit_measure">Quantidade*</label>
                        <input  class="form-control" type="text" name="amount" id="amount" required maxlength="100">
                    </div>                                                  
                   
                    <div class="form-group">
                        <label for="unit_measure">Valor Total*</label>
                        <input  class="form-control" type="text" name="total_value" id="total_value" value="" readonly>
                    </div>
                    <input class="btn btn-primary mb-2" type="submit" id="btn_add" name="btn_save" value="Adicionar" >  
                    <div class="form-group">
                        <label for="unit_measure">Forma de Pagamento*</label>
                        <select name="select" name="selectPrd" class="form-control">
                          <option>Selecione</option>
                          <?php
                                $query = "SELECT * FROM payment_form";
                                $stmt = $objUser->runQuery($query);
                                $stmt->execute();
                              ?>
                          <?php if($stmt->rowCount() > 0){
                                    while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                                  ?>
                          <option class="selectProduct" value="<?php print($rowUser['DESCRIPTION']); ?>"><?php print($rowUser['DESCRIPTION']); ?></option>
                          
                          <?php } } ?>
                        </select>
                    </div>                    
                    
                    <input class="btn btn-primary mb-2" type="submit" id="btn_save" name="btn_save" value="Salvar" readonly>
                  </form>
                </main>
            </div>
        </div>
        <script>
                      let price = null
                      let amount = null     
                             
                      $(document).ready( function ()
                      {
                      $('#btn_save').prop('disabled', true);
                      $('#btn_add').prop('disabled', true);
                      $("#select_prd").on('change', function() {
                        price = $(this).find('option:selected').val();
                        $('#prd_price').val("R$ "+price); 
                        $('#total_value').val("R$ "+(amount*price));  
                        
                      });
                      $("#amount").blur( function() {                      
                        amount = $('#amount').val();                        
                        //alert("O input perdeu o foco.");
                        $('#total_value').val("R$ "+(amount*price));    
                      });
                      
                      
                      
                      });

                   
                             
                  </script> 
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>
    </body>
</html>
