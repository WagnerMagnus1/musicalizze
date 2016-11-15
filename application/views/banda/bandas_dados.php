<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                    <div class="row">
                      <div class="col-md-12">
                          <center>
                          <div data-toggle="context" data-target="#context-menu" class="col-md-12 espaco_cima">
                                <img width="600" id="uploadPreview" src="<?php echo $banda[0]['banda_foto']?>" class="img-responsive img-thumbnail"><br>
                          </div>
                          </center>
                      </div>
                    </div>
                    <div class="col-md-12 text-center" id="dashboard-col">
                      <h4 class="text-center"><?php echo $banda[0]['banda_nome']?></h4>      
                    </div>
                    <br>
                  <br>
                </div>
              </div>
              <input id="id_banda" type="hidden" name="id_pessoa" value="<?php echo $banda[0]['banda_id']?>"><br><br>
              <div class="row">
                  <div class="col-md-2"></div>
                 <div class="col-md-8">
                  <table class="table table-striped">
                          <tbody>
                            <tr>
                              <th scope="row">Explicação:</th>
                              <td><label id="semquebralinha"><?php echo @$banda[0]['banda_explicacao'] ?></label></td>
                            </tr>
                             <tr>
                              <th scope="row">Telefone:</th>
                              <td><label id="semquebralinha"><?php echo @$banda[0]['banda_telefone'] ?></label></td>
                            </tr>
                             <tr>
                              <th scope="row">Outros contatos:</th>
                              <td><label id="semquebralinha"><?php echo @$banda[0]['banda_contato'] ?></label></td>
                            </tr>
                            <tr>
                              <th scope="row">Integrantes:</th>
                               <td>
                                 <?php if($integrantes) { ?>
                                  <?php for($i=0;$i<count($integrantes);$i++) { ?>
                                    <a href="<?php echo base_url('pessoa/dados?pessoa_id=').$integrantes[$i]['pessoa_id'].'&nome='.$integrantes[$i]['pessoa_nome']?>" id="mao"><h4 id='semquebralinha'><?php echo $integrantes[$i]['pessoa_nome']?></h4 id='semquebralinha'></a> (<?php echo $integrantes[$i]['funcao_nome']?>)
                                        <?php if(@$integrantes[$i+1]['pessoa_nome']){?>
                                          <br>
                                        <?php } ?>
                                    
                                  <?php } ?>
                                  <?php } ?>
                               </td>
                            </tr>   
                          </tbody>
                        </table><hr>
                 </div>
                 <div class="col-md-2"></div>
              </div>
              <?php if(@$generos){?>
                <div class="row">
                  <div class="col-md-12">
                    <center><p>Essa banda vem da cidade de <?php echo $banda[0]['banda_cidade'].'/ '.$banda[0]['banda_estado']?>.</p></center>          
                    <center><p>Atualmente se encaixa nos seguintes gêneros/ estilos musicais:</center>
                    <?php foreach($generos as $f){?><center><h3 id="semquebralinha" class="glyphicon glyphicon-hand-right"> <?php echo $f['genero_nome'];}?></h3></center></p>
                  </div>
                </div>
                <?php }else{?>
                  <div class="row">
                    <div class="col-md-12">
                       <center><p>Essa banda vem da cidade de <?php echo $banda[0]['banda_cidade'].'/ '.$banda[0]['banda_estado']?>.</p></center>   
                      <center><p>Atualmente, a banda não esta tocando.</p></center>
                    </div>
                  </div>
                <?php }?>

                    <br><br><br><div class="row espaco_baixo"> 
                        <?php if($componente == '5') {?>
                           <?php if($atividade) {?>
                            <div class="col-md-12">
                              <button data-toggle="modal" data-target="#modalconviteatividade" type="button" class="btn btn-block btn-info btn-lg"><span class="glyphicon glyphicon-send"></span> Convidar para atividade</button>
                              <button data-toggle="modal" data-target="#cancelarnotificacao" type="button" class="btn btn-block btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancelar Convite</button>    
                            </div>
                          <?php }else{?>
                            <div class="col-md-12">
                              <center><h4><a id="mao" href="<?php echo base_url('pagina/index')?>">Crie uma atividade para poder convidar</a></h4><center>
                            </div>   
                          <?php }?> 
                          
                        <div class="col-md-12">
                              <button onclick="window.location.href='<?php echo base_url('banda/relatorio?banda=').$banda[0]['banda_id']?>'" type="button" class="btn btn-block btn-info">Relatórios</button>
                        </div>  
                        
                        <?php }else{?> 
                              <?php if($atividade) {?>
                              <div class="col-md-6">
                                <button data-toggle="modal" data-target="#modalconviteatividade" type="button" class="btn btn-block btn-info btn-lg"><span class="glyphicon glyphicon-send"></span> Convidar para atividade</button>
                                <button data-toggle="modal" data-target="#cancelarnotificacao" type="button" class="btn btn-block btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancelar Convite</button>    
                              </div>
                            <?php }else{?>
                              <div class="col-md-6">
                                <center><h4><a id="mao" href="<?php echo base_url('pagina/index')?>">Crie uma atividade para poder convidar</a></h4><center>
                              </div>   
                            <?php }?> 
                              <?php if($funcoes) {?>
                                <?php if($banda[0]['banda_status'] == '1') {?>
                                  <div class="col-md-6">
                                    <button data-toggle="modal" data-target="#modalconvitebanda" type="button" value="cadastrar" class="btn btn-block btn-info btn-lg"><span class="glyphicon glyphicon-send"></span> Gostaria de participar da banda</button>
                                    <button data-toggle="modal" data-target="#cancelarnotificacaobanda" type="button" class="btn btn-block btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancelar Pedido</button>
                                  </div>  
                              <?php }else{?>
                                 <div class="col-md-6">
                                  <center><h4><a id="mao" href="<?php echo base_url('pagina/index')?>">Habilite uma função em seus dados, para poder enviar uma solicitação para a banda</a></h4><center>
                                </div>
                              <?php }?>   
                          <?php }?>  
                       <?php }?>           
                      <br>    
                    </div> 
              </div>
            </div>


<!--  MODAL PARA NOTIFICAR ATIVIDADE-->
                          <div class="modal fade" id="modalconviteatividade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Escolha a atividade da banda <?php echo $banda[0]['banda_nome']?></h4>
                                </div>
                                  <div class="modal-body">
                                      <form id="notificaratividade" role="form" method="POST" name="notificaratividade" action="<?php echo base_url('banda/notificar_atividade'); ?>">
                                       <input type="hidden" name="captcha">
                                       <input id="id_pessoa" type="hidden" name="id_pessoa" value="<?php echo $pessoa['pessoa_id']?>">
                                       <input id="id_banda" type="hidden" name="id_banda" value="<?php echo $banda[0]['banda_id']?>">
                                        <fieldset><br>
                                            <p class="control-label">Selecione a atividade:</p>
                                            <label class="control-label"> Obs.: As atividades em AZUL ja possuem a participação da banda</label><br>
                                            <label class="control-label"> Obs.: As atividades em CINZA estão pendentes</label><br>
                                          <select id="all" class="form-control selectpicker" data-size="7" name="atividade" required> 
                                          <option value="" disabled selected>Selecione uma atividade</option>     
                                                <?php foreach($atividade as $a) { ?>
                                                    <option value="<?php echo $a['atividade_id']?>"><?php echo $a['atividade_titulo']?> - <?php echo $a['atividade_tipo']?> (<?php echo date('d/m/Y H:i:s', strtotime($a['atividade_data']));?>)</option>
                                                <?php } ?>
                                          </select>
                                        </fieldset>
                                        
                                         <script>                
                                          var valueparticipa = '<?php echo $participa; ?>';

                                          $.each(valueparticipa.split(","), function(i,e){
                                              $("#all option[value='" + e + "']").css('color','blue');
                                              $("#all option[value='" + e + "']").prop('disabled',true);
                                              $("#all option[value='']").css('color','silver');
                                          });

                                          var valuependente = '<?php echo $pendente; ?>';

                                          $.each(valuependente.split(","), function(i,e){
                                              $("#all option[value='" + e + "']").css('color','#A9A9A9');
                                              $("#all option[value='" + e + "']").prop('disabled',true);
                                              $("#all option[value='']").css('color','silver');
                                          });

                                        </script>
                                        
                                        <div class="modal-footer"><hr>
                                            <button type="button" id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                              <script>
                                                $('#cancelar').click(function() {
                                                    $('#convidaratividade')[0].reset();
                                                    $('option:selected').removeAttr('selected');
                                                }); 
                                              </script> 
                                            <button name="notificaratividade" type="submit" class="btn btn-primary" value="Notificar">Notificar</button>
                                        </div>
                                      </form>
                                  </div>
                              </div>
                            </div>
                          </div>



<!-- MODAL PARA CANCELAR NOTIFICAÇÃO DE ATIVIDADE-->
 <div class="modal fade" id="cancelarnotificacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <center><h3 class="modal-title" id="myModalLabel">Notificações Enviadas:</h3></center>
                                </div>
                                  <div class="modal-body">
                                      <table class="table table-striped">
                                      <tbody>
    
                                        <?php if($pendente_completo){?>
                                          <?php $i=0; foreach($pendente_completo as $a) {?>
                                                <tr>
                                                  <td>
                                                    <label id="semquebralinha" value="<?php echo $a['atividade_id']?>">Atividade "<?php echo $a['atividade_titulo']?> - <?php echo $a['atividade_tipo']?> (<?php echo date('d/m/Y H:i:s', strtotime($a['atividade_data']));?>)"</label>
                                                    <button id="cancelaratividade<?php echo $a['atividade_id']?>" type="button" class="btn pull-right btn-danger"><span class="glyphicon glyphicon-remove"></span> excluir solicitação</button>
                                                  </td>
                                                </tr>

                                                <script>
                                                $('#cancelaratividade<?php echo $a['atividade_id']?>').click(function() {
                                                    var dados = {
                                                      integrante_atividade_id : "<?php echo $pendente_completo[$i]['integrante_atividade_id'] ?>"
                                                    };

                                                    $.ajax({            
                                                        type: "POST",
                                                        data: { dados: JSON.stringify(dados)},
                                                        datatype: 'json',
                                                        url: "<?php echo site_url('atividade/cancelarconviteatividadebanda'); ?>",      
                                                        success: function(data){     
                                                          window.location.reload();
                                                        },
                                                        error: function(e){
                                                          alert('Erro! Por favor tente novamente.');
                                                            console.log(e.message);
                                                        }
                                                    }); 
                                                });
                                              </script> 
                                        <?php $i++; } ?>
                                        <?php }?>
                                      </tbody>
                                    </table>
                                  </div>
                              </div>
                            </div>
                          </div>



<!--  MODAL PARA NOTIFICAR BANDA-->
                          <div class="modal fade" id="modalconvitebanda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">"<?php echo $banda[0]['banda_nome']?>" receberá sua notificação</h4>
                                </div>
                                  <div class="modal-body">
                                  <?php if($componente == '0'){?>
                                    <h4>Seu pedido para participar na banda "<?php echo $banda[0]['banda_nome']?>" foi enviado, agora é só aguardar a resposta.</h4><hr><br>
                                  <?php }else{?>
                                    <?php if($componente == '1'){?>
                                       <h4>Você possui uma solicitação para participar na banda "<?php echo $banda[0]['banda_nome']?>", por favor verifique na caixa de notificações acima.</h4><hr><br>
                                    <?php }else{?>
                                           <form id="notificarbanda" role="form" method="POST" name="notificarbanda" action="<?php echo base_url('integrante/usuario_notifica_banda'); ?>">
                                           <input id="banda" type="hidden" name="banda" value="<?php echo $banda[0]['banda_id']?>">
                                           <input id="pessoa" type="hidden" name="pessoa" value="<?php echo $pessoa['pessoa_id']?>">

                                            <fieldset><br>
                                                <p class="control-label">Selecione a função onde deseja atuar:</p>
                                              <select class="form-control selectpicker" data-size="7" name="funcao" required>  
                                              <option value="" disabled selected>Selecione uma função</option>  
                                                    <?php foreach($funcoes as $a) { ?>
                                                        <option value="<?php echo $a['funcao_id']?>"><?php echo $a['funcao_nome']?></option>
                                                    <?php } ?>
                                              </select>
                                            </fieldset>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <label class="control-label" for="exampleInputPassword1"><h5>Mensagem:</h5></label>
                                                <textarea name="justificativa" class="form-control" rows="3" value=""></textarea>
                                              </div>
                                            </div>

                                            <div class="modal-footer"><hr>
                                                <button type="button" id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                  <script>
                                                    $('#cancelar').click(function() {
                                                        $('#convidaratividade')[0].reset();
                                                        $('option:selected').removeAttr('selected');
                                                    }); 
                                                  </script> 
                                                <button name="notificarbanda" type="submit" class="btn btn-primary" value="Notificar">Notificar</button>
                                            </div>
                                          </form>
                                      <?php }?> 
                                  <?php }?>    
                                  </div>
                              </div>
                            </div>
                          </div>



<!-- MODAL PARA CANCELAR NOTIFICAÇÃO BANDA-->
 <div class="modal fade" id="cancelarnotificacaobanda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <center><h3 class="modal-title" id="myModalLabel">Notificações Enviadas:</h3></center>
                                </div>
                                  <div class="modal-body">
                                      <table class="table table-striped">
                                      <tbody>

                                      <?php if(@$pedido_participar_banda){?>
                                         <?php foreach($pedido_participar_banda as $a) { ?>
                                                <tr>
                                                  <td>
                                                    <label id="semquebralinha" value="<?php echo $a['integrante_id']?>">Banda "<?php echo $a['banda_nome']?>"</label>
                                                    <button id="cancelarconvitebanda<?php echo $a['integrante_id']?>" type="button" class="btn pull-right btn-danger"><span class="glyphicon glyphicon-remove"></span> excluir pedido</button>
                                                  </td>
                                                </tr>

                                                <script>
                                                $('#cancelarconvitebanda<?php echo $a['integrante_id']?>').click(function() {
                                                    var dados = {
                                                      integrante : "<?php echo $a['integrante_id'] ?>"
                                                    };

                                                    $.ajax({            
                                                        type: "POST",
                                                        data: { dados: JSON.stringify(dados)},
                                                        datatype: 'json',
                                                        url: "<?php echo site_url('integrante/cancelarconviteatividade'); ?>",      
                                                        success: function(data){ 
                                                          window.location.reload();
                                                        },
                                                        error: function(e){
                                                          alert('Erro! Por favor tente novamente.');
                                                            console.log(e.message);
                                                        }
                                                    }); 
                                                });
                                              </script> 
                                        <?php } ?>
                                      <?php }?>
                                      </tbody>
                                    </table>
                                  </div>
                              </div>
                            </div>
                          </div>
                                              