<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h3 class="text-center"><?php echo $banda[0]['banda_nome']?>/ Editar</h3>      
                    <div class="col-md-12 text-justify">
                      <hr>
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <form role="form" method="POST" name="cadastrar" action="<?php echo base_url('banda/salvar_editar'); ?>">
                      <input type="hidden" name="captcha">
                      <input type="hidden" name="banda_id" value="<?php echo $banda[0]['banda_id'];?>">
                    
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1"><h4>Nome</h4></label>
                        <input value="<?php echo @$banda[0]['banda_nome'] ?>" class="form-control" name="nome" placeholder="Nome"
                        type="text" required>
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1"><h4>Explicação</h4></label>
                        <textarea value="<?php echo @$banda[0]['banda_explicacao'] ?>" class="form-control" name="explicacao" placeholder="Regras e ideais da banda" type="text" required><?php echo @$banda[0]['banda_explicacao'] ?></textarea>
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1"><h4>Telefone</h4></label>
                        <input value="<?php echo @$banda[0]['banda_telefone'] ?>" type="tel" name="txttelefone" id="txttelefone" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" placeholder="(xx) xxxx-xxxx" required/>
                      </div>
                      <script type="text/javascript">$("#txttelefone").mask("(00) 0000-00009");</script>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1"><h4>Outros Contatos</h4></label>
                        <textarea value="<?php echo @$banda[0]['contato'] ?>" class="form-control" name="contato" placeholder="Outros contatos como facebook, email..."
                        type="text"><?php echo @$banda[0]['contato'] ?></textarea>
                      </div>
                        <fieldset>
                          <label class="control-label"><h4>Estado</h4></label><br>
                          <select value="<?php echo @$banda[0]['banda_estado']?>" class="form-control" id="estado1" name="estado" required></select><br>
                          <label class="control-label"><h4>Cidade</h4></label><br>
                          <select value="<?php echo @$banda[0]['banda_cidade']?>" class="form-control" id="cidade1" name="cidade" required></select> <br>
                        </fieldset>
   
                      <script language="JavaScript" type="text/javascript" charset="utf-8">
                        new dgCidadesEstados({
                          estado: document.getElementById('estado1'),
                          cidade: document.getElementById('cidade1')
                        })
                      </script>

                       <fieldset>
                       <hr> 
                       <label class="control-label"><h4>Gêneros/ Estilo</h4>As opções selecionadas abaixo indicam os gêneros da banda atualmente:</label>
                       <br><label class="control-label">Obs.: O texto em vermelho indicam os gêneros que a banda desativou.</label><br><br>
                        <select id="all" class="form-control selectpicker" data-size="7" multiple="multiple" name="genero[]" required>
                          <?php foreach($generos_completo as $lista) { ?>
                              <option value="<?php echo $lista['genero_id']?>"><?php echo $lista['genero_nome']?></option>
                          <?php } ?>
                          </select>
                      </fieldset>
                      <script>                
                        var values = '<?php echo $generos_ativos; ?>';

                        $.each(values.split(","), function(i,e){
                            $("#all option[value='" + e + "']").prop("selected", true);
                        });

                        var values_inativa = '<?php echo $generos_inativos; ?>';

                        $.each(values_inativa.split(","), function(i,e){
                            $("#all option[value='" + e + "']").css('color','red');
                        });
                      </script>

                      

                      <br><br><hr>
                            <div class="row">
                            <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('banda/dados?banda=').$banda[0]['banda_id'].'&pessoa='.$pessoa['pessoa_id']?>'" type="button" class="btn btn-block btn-lg">Cancelar</button>
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


