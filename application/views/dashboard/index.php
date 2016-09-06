<div class="col-md-6 text-center" id="dashboard-col">
  <h1 class="text-center">Musicalizze
    <br>
  </h1>
  <p>Controle as atividades de sua banda, encontre musicos e avalie grupos
    para seu evento</p>
  <br>
  <br>
  <a href="<?php echo base_url('usuario/cadastro')?>" class="btn btn-default btn-lg">Cadastrar-se<br></a>
</div>
<div class="col-md-6">
  <div class="row">
    <div class="col-md-12 dashboard-form">
      <h2 class="text-center">Login</h2>

      <?php if(@$alerta){ ?>
            <div class="alert alert-<?php echo $alerta["class"]; ?>">
            <?php echo $alerta["mensagem"]; ?>
            </div>
          <?php } ?>

      <form  class="form-horizontal dashboard-form" id="dashboard-form" action="<?php echo base_url('conta/entrar');?>" method="post"
          role="form">
          <input type="hidden" name="captcha">
            <div class="form-group">
              <div class="col-sm-2">
                <label for="inputEmail3" class="control-label">Email</label>
              </div>
              <div class="col-sm-10">
                <input id="emaillogin" type="email" title="Preencha o campo Email" required name=email class="form-control" placeholder="Email" name="email" value="<?php echo set_value('email')?>">
              </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                  $('#emaillogin').keydown(function () {
                  $('#senhalogin').attr("disabled", false);
                  });
                });  
            </script> 
            <script type="text/javascript">
                  $(document).ready(function() {
                    $('#emaillogin').blur(function () {

                      var dados = {email : $('#emaillogin').val()};

                        $.ajax({
                            type: "POST",
                            data: { dados: JSON.stringify(dados)},
                            datatype: 'json',
                            url: "<?php echo site_url('conta/verifica_email'); ?>",       
                            success: function(data){
                                var emaillogin = document.getElementById('emaillogin');     

                                    if (emaillogin.value == data) {
                                        emaillogin.setCustomValidity("");
                                    } else {
                                        emaillogin.setCustomValidity("Email não encontrado! Por favor, verifique.");
                                        return false;
                                    }
                              
                            },
                            error: function(e){
                                console.log(e.message);
                            }
                        });  
                    });
                  }); 
              </script> 

            <div class="form-group">
              <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">Senha</label>
              </div>
              <div class="col-sm-10">
                <input id="senhalogin" type="password" class="form-control" required name=senha name="senha" placeholder="Senha" value="<?php echo set_value('senha')?>" >
              </div>
            </div>

              <script type="text/javascript">
                  $(document).ready(function() {
                    $('#senhalogin').focus(function () {
                      var emaillogin = document.getElementById('emaillogin');
                      if(emaillogin.value == "")
                      {
                        $('#emaillogin').focus();
                        $('#senhalogin').attr("disabled", true);
                        $('#senhalogin').val("");
                      }
                        
                    });
                  });  
              </script> 
              <script type="text/javascript">
                  $(document).ready(function() {
                    $('#senhalogin').keyup(function () {

                      var dados = {senha : $('#senhalogin').val(), email : $('#emaillogin').val()};
 
                        $.ajax({
                            type: "POST",
                            data: { dados: JSON.stringify(dados)},
                            datatype: 'json',
                            url: "<?php echo site_url('conta/verifica_senha'); ?>",       
                            success: function(data){
                                var emaillogin = document.getElementById('emaillogin'); 
                                var senhalogin = document.getElementById('senhalogin');     

                                    if (senhalogin.value == data) {
                                        senhalogin.setCustomValidity("");
                                    } else {
                                        senhalogin.setCustomValidity("Senha incorreta! Por favor, verifique.");
                                        return false;
                                    }
                              
                            },
                            error: function(e){
                                console.log(e.message);
                            }
                        });  
                    });
                  }); 
              </script> 


            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox">Lembrar-me
                    <br>
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button  id="entrar" name="entrar" value="entrar" type="submit" class="btn btn-block btn-lg btn-primary">Entrar 
                  <br>
                </button>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <?php $this->load->view('facebook/sdkFacebook'); ?>
              </div>
            </div> 

            <progress class="progress"  max="100" aria-describedby="example-caption-10">
              <div class="progress">
                <span class="progress-bar" style="width: 50%;"></span>
              </div>
            </progress>
      </form>
    </div>
  </div>
</div>
</div>
<div class="row">
<div class="col-md-12 text-justify">
  <hr>
</div>
