<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h2 class="text-center">Bem Vindo ao Muzicalizze</h2>
                    <p class="lead text-danger">Por favor, finalize seu cadastro preenchendo os dados abaixo:</p>           
                    <div class="col-md-12 text-justify">
                      <hr>
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
              </div>
            </div>

            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-left">
                    <form role="form" method="POST" name="cadastrar" action="&lt;?php echo base_url('usuario/Cadastrar'); ?&gt;">
                      <input type="hidden" name="captcha">
                      <input type="hidden" name="id_usuario" value="">
                        
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1">Nome</label>
                        <input class="form-control" name="email" placeholder="Nome"
                        type="text">
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1">Sobrenome</label>
                        <input class="form-control" name="sobrenome" placeholder="Sobrenome"
                        type="text">
                      </div>


                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Idade</label>
                        <input class="form-control" name="idade" placeholder="Idade"
                        type="text">
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Telefone</label>
                        <input class="form-control" name="telefone" placeholder="telefone"
                        type="text">
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Endereço</label>
                        <input class="form-control" name="endereco" placeholder="endereco"
                        type="text">
                      </div>
                      <fieldset>
                        <label class="control-label">Estado</label>:<select id="estado" name="estado"></select> 
                        <label class="control-label">Cidade</label>:<select id="cidade" name="cidade"></select> 
                      </fieldset>
                      <div class="form-group">
                        <label for="exampleInputFile">Foto para perfil</label>
                        <input type="file" id="exampleInputFile">
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Outros Contatos:</label>
                        <input class="form-control" name="endereco" placeholder="Digite aqui seus outros contatos, como emails, fax..."
                        type="text">
                      </div>
                    
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Observação</label>
                        <textarea class="form-control" rows="3"></textarea>
                      </div>
                      <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-block btn-info btn-lg">Salvar</button>
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
          </div>