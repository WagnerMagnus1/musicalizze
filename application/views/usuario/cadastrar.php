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
                <div id="teste"></div>
              </div>

              <script type="text/javascript">
                  $(document).ready(function() {
                    $('#emailcadastrar').click(function () {
                    $("#teste").html('');
 
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



              <div class="form-group">
                <label class="control-label" for="exampleInputPassword1">Senha</label>
                <input class="form-control" name="senha" required="senha" placeholder="Senha"
                type="password" oninvalid="setCustomValidity('Por favor, preencha este campo')" onchange="try{setCustomValidity('')}catch(e){}">
              </div>
              <div class="form-group">
                <label class="control-label" for="exampleInputPassword1">Confirmar senha</label>
                <input class="form-control" name="confirmasenha" 
                placeholder="Confirmar senha" required="senha" type="password" oninvalid="setCustomValidity('Por favor, preencha este campo')" onchange="try{setCustomValidity('')}catch(e){}">
              </div>
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