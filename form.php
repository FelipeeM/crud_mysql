<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
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

    $stmt = $objUser->runQuery("SELECT * FROM client WHERE ID_CLIENT = :id");
    $stmt->execute(array(":id" => $id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);

}else{
  $id = null;
  $rowUser = null;
}

// POST
if(isset($_POST['btn_save'])){
  $name   = strip_tags($_POST['name']);
  $cpfcnpj  = strip_tags($_POST['cpfcnpj']);
  $birth  = strip_tags($_POST['birth']);
  $fone  = strip_tags($_POST['fone']);

  try{
     if($id != null){
       if($objUser->update($id,$name, $cpfcnpj,$birth, $fone)){
         $objUser->redirect('index.php?updated');
       }
     }else{
       if($objUser->insert($name, $cpfcnpj,$birth, $fone)){
         $objUser->redirect('index.php?inserted');
       }else{
         $objUser->redirect('index.php?error');
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
                  <p>Required fields are in (*)</p>
                  <form  method="post">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input class="form-control" type="text" name="id" id="id" placeholder="ID" value="<?php print($rowUser['ID_CLIENT'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input  class="form-control" type="text" name="name" id="name" placeholder="Nome do cliente" value="<?php print($rowUser['CLIENT_NAME'] ?? ''); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="cpfcnpj">CPF/CNPJ *</label>
                        <input  class="form-control" type="text" name="cpfcnpj" id="cpfcnpj" placeholder="CPF ou CNPJ" value="<?php print($rowUser['CPF_CNPJ'] ?? ''); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="birth">Data Nascimento *</label>
                        <input  class="form-control" type="data" name="birth" id="birth" placeholder="0000-00-00" value="<?php print($rowUser['BIRTH'] ?? ''); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="fone">Telefone *</label>
                        <input  class="form-control" type="text" name="fone" id="fone" placeholder="(00) 0000-0000" value="<?php print($rowUser['FONE'] ?? ''); ?>" required maxlength="100">
                    </div>
                    <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Salvar">
                  </form>
                </main>
            </div>
            <script>
              jQuery(function($){
                $("#birth").mask("9999-99-99");
                $("#fone").mask("(99) 99999-9999");  
                $("#cpfcnpj").keydown(function(){
                try {
                    $("#cpfcnpj").unmask();
                } catch (e) {}

                var tamanho = $("#cpfcnpj").val().length;

                if(tamanho < 11){
                    $("#cpfcnpj").mask("999.999.999-99");
                } else {
                    $("#cpfcnpj").mask("99.999.999/9999-99");
                }

                // ajustando foco
                var elem = this;
                setTimeout(function(){
                    // mudo a posição do seletor
                    elem.selectionStart = elem.selectionEnd = 10000;
                }, 0);
                // reaplico o valor para mudar o foco
                var currentValue = $(this).val();
                $(this).val('');
                $(this).val(currentValue);
            });                           
              });
            </script>
        </div>
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>
    </body>
</html>
