<link href="<?php echo base_url('assets/css/style.css')?>" rel="stylesheet" type="text/css">
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center" id="dashboard-col">
        <h2 class="text-center">Fale conosco</h2><h3> Envie sugestões, duvidas e questionamentos para nós.</small>
        </h3>
      </div> 
  </div><br>
  <div class="row">
   <form role="form" method="POST" name="cadastrar" action="<?php echo base_url('administrador/enviar_email'); ?>">
      <input type="hidden" name="captcha">
		<textarea name="email" class="form-control" rows="7" required></textarea><br>
		<button name="cadastrar" value="cadastrar" type="submit" class="btn btn-block btn-info btn-lg">Enviar</button>
    </form>
  </div>
</div>
</div>
