<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h2 class="text-center"><?php echo $banda[0]['banda_nome']?></h2>     
                    <div class="col-md-12 text-justify">
                      <hr>
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                  <div id="imgperfil" data-toggle="context" data-target="#context-menu" class="col-md-4">
                        
                        <img id="uploadPreview" src="<?php echo $banda[0]['banda_foto']?>" class="img-circle img-responsive img-thumbnail circular"><br>

                        <div id="gridbotaofoto">
                          <button id="addphoto" name="adicionarphoto" value="adicionar" type="submit" class="btn-info">Adicionar uma Foto</button>
                          <button id="salvarphoto" name="salvarphoto" value="editar" type="submit" class="btn-info" disabled>Salvar a foto</button>
                          <button id="excluirphoto" name="excluirphoto" value="excluir" type="submit" class="btn-info">Deletar</button>
                        </div>
                        
                        <!-- ELEMENTO INPUT INVISIVEL-->
                        <input class="btn-block" id="input-1" type="file" name="myPhoto" onchange="PreviewImage();" /> 
                         <!-- ELEMENTO INPUT INVISIVEL-->
                        <input class="btn-block" id="perfil" type="hidden" value="" /> 
                        <!-- CARREGA A FOTO SELECIONADA PELO USUARIO E MOSTRA NA TELA-->
                        <script>
                            function PreviewImage() { 

                                var oFReader = new FileReader(); 
                                oFReader.readAsDataURL(document.getElementById("input-1").files[0]);

                                oFReader.onload = function (oFREvent) { 
                                    document.getElementById("uploadPreview").src = oFREvent.target.result; 
                              };
                            };

                            $('#addphoto').click(function() {
                              $('input[name=myPhoto]').click();
                              $("#salvarphoto").prop("disabled", false);
                            });

                             $('#excluirphoto').click(function() {
                              document.getElementById("uploadPreview").src = "<?php echo base_url('public/imagens/perfil/perfil_banda.jpg')?>"; 
                              $('#perfil').val('<?php echo base_url('public/imagens/perfil/perfil_banda.jpg')?>');
                              $("#salvarphoto").prop("disabled", false);
                            });
                           
                            $('#salvarphoto').click(function() {
                              var dado = { 
                                banda_id : '<?php echo $banda[0]['banda_id']?>', 
                                banda_foto : $('#perfil').val(),
                                img : document.getElementById("uploadPreview").src.toString()
                              }; 

                                  $.ajax({            
                                      type: "POST",
                                      data: { dado: JSON.stringify(dado)},
                                      datatype: 'json',
                                      url: "<?php echo site_url('banda/salvar_foto'); ?>",      
                                      success: function(data){     
                                        $("#salvarphoto").prop("disabled", true);
                                        alert('Salvo com sucesso!');
                                        document.location.reload();
                                      },
                                      error: function(e){
                                          alert("Erro. A foto não foi salva!", "error");
                                      }
                                  }); 
                            });
                           
                        </script>
                  </div>
                  <div class="col-md-8">
                        <input id="id_banda" type="hidden" name="id_banda" value="<?php echo $banda[0]['banda_id']?>">
                        <input id="id_pessoa" type="hidden" name="id_pessoa" value="<?php echo $pessoa['pessoa_id']?>">
                          <table class="table table-striped">
                          <tbody>
                           <tr>
                             <th scope="row">Nome:</th>
                             <td><?php echo @$banda[0]['banda_nome'] ?></td>
                           </tr>
                            <tr>
                             <th scope="row">Explicação:</th>
                             <td><?php echo @$banda[0]['banda_explicacao'] ?></td>
                           </tr>
                            <tr>
                              <th scope="row">Data de Criação:</th>
                              <td><?php echo date('d/m/Y', strtotime(@$banda[0]['banda_data']));?></td>
                            </tr>
                            <tr>
                              <th scope="row">Telefone:</th>
                               <td><?php echo @$banda[0]['banda_telefone'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Cidade:</th>
                               <td><?php echo @$banda[0]['banda_cidade'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Estado:</th>
                               <td><?php echo @$banda[0]['banda_estado']?></td>
                            </tr>
                            <tr>
                              <th scope="row">Outros Contatos:</th>
                              <td><?php echo @$banda[0]['banda_contato'] ?></td>
                            </tr><hr>
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
                            <tr>
                              <th scope="row">Gêneros/Estilos Musicais:</th>
                               <td>
                                 <?php if($generos) { ?>
                                  <?php for($i=0;$i<count($generos);$i++) { ?>
                                    <?php echo $generos[$i]['genero_nome']?>
                                        <?php if(@$generos[$i+1]['genero_id']){ echo ", ";} ?>
                                   
                                  <?php } ?>
                                  <?php } ?>
                               </td>
                            </tr>  

                          </tbody>
                          </table><br>
                          <div class="row">  
                             <div class="col-md-6">
                              <button data-toggle="modal" data-target="#modalconviteatividade" type="button" class="btn btn-block btn-info btn-lg">Convidar para Atividade</button>
                             </div>
                             <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('banda/editar?banda=').$banda[0]['banda_id'].'&pessoa='.$pessoa['pessoa_id']?>'" type="button" class="btn btn-block btn-info btn-lg">Editar Dados</button>
                             </div>
                          </div><br>
                          <div class="row">
                            <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('banda/relatorio?banda=').$banda[0]['banda_id']?>'" type="button" class="btn btn-block btn-info btn-lg">Relatórios</button>
                             </div>
                              <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('banda/editar_integrante?banda=').$banda[0]['banda_id'].'&pessoa='.$pessoa['pessoa_id']?>'" type="button" class="btn btn-block btn-info btn-lg">Editar Integrantes</button>
                             </div>
                          </div><br> <br><br> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
</div>
<div class="row">
  <div class="col-md-12">
    <center><span class="glyphicon glyphicon-arrow-left"><h4 id="semquebralinha" ><a id="mao" href="<?php echo base_url('pagina/index')?>"> Voltar</a></h4></center>
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

                                        <?php foreach($atividade as $a) { ?>
                                           <?php for($i=0;$i<count($pendente_completo);$i++){?>
                                             <?php if($a['atividade_id'] == $pendente_completo[$i]['atividade_id']){ ?>
                                                <tr>
                                                  <td>
                                                    <label id="semquebralinha" value="<?php echo $a['atividade_id']?>">Atividade "<?php echo $a['atividade_titulo']?> - <?php echo $a['atividade_tipo']?> (<?php echo date('d/m/Y H:i:s', strtotime($a['atividade_data']));?>)"</label>
                                                    <button id="cancelaratividade<?php echo $a['atividade_id']?>" type="button" class="btn pull-right btn-danger"><span class="glyphicon glyphicon-remove"></span> excluir solicitação</button>
                                                  </td>
                                                </tr>

                                                <script>
                                                $('#cancelaratividade<?php echo $a['atividade_id']?>').click(function() {
                                                    var dados = {
                                                      pessoa : "<?php echo $pendente_completo[$i]['pessoa_id'] ?>",
                                                      atividade : "<?php echo $pendente_completo[$i]['atividade_id'] ?>",
                                                      funcao : "<?php echo $pendente_completo[$i]['Funcoes_funcao_id'] ?>"
                                                    };

                                                    $.ajax({            
                                                        type: "POST",
                                                        data: { dados: JSON.stringify(dados)},
                                                        datatype: 'json',
                                                        url: "<?php echo site_url('atividade/cancelarconviteatividade'); ?>",      
                                                        success: function(data){     
                                                          window.location.href = "<?php echo base_url('pessoa/dados')?>";
                                                        },
                                                        error: function(e){
                                                          alert('Erro! Por favor tente novamente.');
                                                            console.log(e.message);
                                                        }
                                                    }); 
                                                });
                                              </script> 
                                              
                                            <?php } ?>
                                          <?php } ?>
                                        <?php } ?>
                                      </tbody>
                                    </table>
                                  </div>
                              </div>
                            </div>
                          </div>