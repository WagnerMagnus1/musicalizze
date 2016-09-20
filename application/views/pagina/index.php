<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center" id="dashboard-col">
        <h2 class="text-center">Bem vindo
          <small><?php echo $_SESSION['email'];?></small>
        </h2>
         <?php if(@$alerta){ ?>
            <div class="alert alert-<?php echo $alerta["class"]; ?>"> 
            <?php echo $alerta["mensagem"]; ?>
           </div>  
          <?php } ?>
      </div>
    </div>
  </div>
</div>