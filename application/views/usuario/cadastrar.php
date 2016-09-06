<div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center" id="dashboard-col">
            <h2 class="text-center">Musicalizze
              <small>&nbsp;seus sonhos</small>
            </h2>
            <p class="lead">Controle as atividades de sua banda, encontre musicos e avalie grupos
              para seus eventos</p>
            <br>
            <br>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="text-center" contenteditable="true">Cadastro</h2>
            <div class="col-md-12 text-justify">
              <hr>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">

          <?php if(@$alerta){ ?>
            <div class="alert alert-<?php echo $alerta["class"]; ?>"> 
            <?php echo $alerta["mensagem"]; ?>
           </div>  
          <?php } ?>
    
            <form role="form" method="POST" name="cadastrar" action="<?php echo base_url('usuario/Cadastrar'); ?>">
              <input type="hidden" name="captcha">
              <input type="hidden" name="id_usuario" value="">

              <div class="form-group">
                <label class="control-label" for="exampleInputEmail1">Email</label>
                <input id="emailcadastrar" class="form-control" name="email" value="<?php echo set_value('email') ?: ''?>" placeholder="Email" required
                type="email">

              </div>

              <script type="text/javascript">
                  $(document).ready(function() {
                    $('#emailcadastrar').blur(function () {

                      var dados = {email : $('#emailcadastrar').val()};

                        $.ajax({
                            type: "POST",
                            data: { dados: JSON.stringify(dados)},
                            datatype: 'json',
                            url: "<?php echo site_url('usuario/email_disponivel'); ?>",       
                            success: function(data){
                                var emailcadastrar = document.getElementById('emailcadastrar');     

                                    if (emailcadastrar.value == data) {
                                        emailcadastrar.setCustomValidity("Esse email ja existe! Por favor, tente outro.");
                                    } else {
                                        emailcadastrar.setCustomValidity("");
                                    }
                              
                            },
                            error: function(e){
                                console.log(e.message);
                            }
                        });  
                    });
                  });  
              </script> 
              <script type="text/javascript">
                  $(document).ready(function() {
                    $('#emailcadastrar').keydown(function () {
                    $('#senha').attr("disabled", false);
                    $('#senha2').attr("disabled", false);
                    });
                  });  
              </script> 

              <div class="form-group">
                <label class="control-label" for="exampleInputPassword1">Senha</label>
                <input id="senha" class="form-control" name="senha" required="senha" placeholder="Senha"
                type="password" onchange="try{setCustomValidity('')}catch(e){}">
              </div>

              <script type="text/javascript">
                  $(document).ready(function() {
                    $('#senha').focus(function () {
                      var emailcadastrar = document.getElementById('emailcadastrar');
                      if(emailcadastrar.value == "")
                      {
                        $('#emailcadastrar').focus();
                        $('#senha').attr("disabled", true);
                        $('#senha').val("");
                        $('#senha2').attr("disabled", true);
                        $('#senha2').val("");
                      }
                        
                    });
                  });  
              </script> 

              <div class="form-group">
                <label class="control-label" for="exampleInputPassword1">Confirmar senha</label>
                <input id="senha2" class="form-control" name="confirmasenha" 
                placeholder="Confirmar senha" required="senha" type="password" onchange="try{setCustomValidity('')}catch(e){}">
              </div>

              
               <script type="text/javascript">
                  $(document).ready(function() {
                    $('#senha2').blur(function () {
                      var senha1 = document.getElementById('senha');
                      var senha2 = document.getElementById('senha2');

                      if(senha1 == senha2)
                      {
                        senha2.setCustomValidity('');
                      }else{
                        senha2.setCustomValidity('A senha esta diferente. Por favor, verifique.');
                      }
                    });
                  });  
              </script> 

              <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-block btn-info btn-lg">Cadastrar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-justify">
            <hr>
          </div>
        </div>
      </div>
    </div>