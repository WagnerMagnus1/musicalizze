<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h3 class="text-center">Meus Dados/ Editar</h3>      
                    <div class="col-md-12 text-justify">
                      <hr>
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <form role="form" method="POST" name="cadastrar" action="<?php echo base_url('pessoa/salvar_editar'); ?>">
                      <input type="hidden" name="captcha">
                      <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id'];?>">
                      <input type="hidden" name="pessoa_id" value="<?php echo $dados['pessoa_id'];?>">
                    
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1"><h4>Nome</h4></label>
                        <input value="<?php echo @$dados['pessoa_nome'] ?>" class="form-control" name="nome" placeholder="Nome"
                        type="text" required>
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1"><h4>Sobrenome</h4></label>
                        <input value="<?php echo @$dados['pessoa_sobrenome'] ?>" class="form-control" name="sobrenome" placeholder="Sobrenome"
                        type="text">
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1"><h4>Data de Nascimento</h4></label>
                        <input value="<?php echo @$dados['pessoa_nascimento'] ?>" class="form-control" name="nascimento" type="date" 
                        type="text" required>
                      </div>

                      <fieldset>
                        <label class="control-label"><h4>Sexo</h4></label><br>
                        <select class="form-control selectpicker" name="sexo" id="genero" required>
                             <option value="" disabled hidden>Selecione o seu gênero</option>
                             <option value="Masculino">Masculino</option>
                             <option value="Feminino">Feminino</option>
                        </select><br>
                      </fieldset>

                      <script>
                            var values = "<?php echo @$dados['pessoa_sexo']?>";

                            $.each(values.split(","), function(i,e){
                                $("#genero option[value='" + e + "']").prop("selected", true);
                            });
                      </script>

                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1"><h4>Telefone</h4></label>
                        <input value="<?php echo @$dados['pessoa_telefone'] ?>" type="tel" name="txttelefone" id="txttelefone" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" placeholder="(xx) xxxx-xxxx" />
                      </div>
                      <script type="text/javascript">$("#txttelefone").mask("(00) 0000-00009");</script>
                      
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1"><h4>Endereço</h4></label>
                        <input value="<?php echo @$dados['pessoa_endereco'] ?>" class="form-control" name="endereco" placeholder="Ex.: Rua Luis Gonzala, 70"
                        type="text">
                      </div>

                        <fieldset>
                          <label class="control-label"><h4>Estado</h4></label><br>
                          <select value="<?php echo @$dados['pessoa_estado']?>" class="form-control" id="estado1" name="estado" required></select><br>
                          <label class="control-label"><h4>Cidade</h4></label><br>
                          <select value="<?php echo @$dados['pessoa_cidade']?>" class="form-control" id="cidade1" name="cidade" required></select> <br>
                        </fieldset>
   
                      <script language="JavaScript" type="text/javascript" charset="utf-8">
                        new dgCidadesEstados({
                          estado: document.getElementById('estado1'),
                          cidade: document.getElementById('cidade1')
                        })
                      </script>
                      
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1"><h4>Outros Contatos</h4></label>
                        <input value="<?php echo @$dados['pessoa_contato'] ?>" class="form-control" name="contato" placeholder="Digite aqui seus outros contatos, como emails, outros números..."
                        type="text">
                      </div>

                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1"><h4>Observação</h4></label>
                        <textarea name="observacao" class="form-control" rows="3"><?php echo @$dados['pessoa_obs'] ?></textarea>
                      </div>

                       <fieldset>
                       <hr> 

                       <label class="control-label"><h4>Habilidades/ Funções</h4>As opções selecionadas abaixo são as funções escolhidas por você e que estão em atividade atualmente:</label>
                       <br><label class="control-label">Obs.: O texto em vermelho indicam as funções que você desativou.</label><br><br>
                        <select id="all" class="form-control selectpicker" data-size="7" multiple="multiple" name="funcao[]">
                          <?php foreach(@$funcao as $f) { ?>
                              <option value="<?php echo $f['funcao_id']?>"><?php echo $f['funcao_nome']?></option>
                          <?php } ?>
                          </select>
                      </fieldset>
                      <script>                
                        var values = '<?php echo $funcao_ativa; ?>';

                        $.each(values.split(","), function(i,e){
                            $("#all option[value='" + e + "']").prop("selected", true);
                        });

                        var values_inativa = '<?php echo $funcao_inativa; ?>';

                        $.each(values_inativa.split(","), function(i,e){
                            $("#all option[value='" + e + "']").css('color','red');
                        });
                      </script>
                      <br><br><hr>
                            <div class="row">
                            <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('pessoa/meusdados')?>';" type="button" class="btn btn-block btn-lg">Cancelar</button>
                            </div>
                             <div class="col-md-6">
                              <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-block btn-info btn-lg">Salvar Alterações</button>
                             </div>
                          </div> <br> 
                    </form><br>    
                  </div>
                </div>
              </div>
            </div>
</div>


