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
                              document.getElementById("uploadPreview").src = "http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png"; 
                              $('#perfil').val('http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png');
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
                              <button onclick="window.location.href='<?php echo base_url('pagina/index')?>'" type="button" class="btn btn-block btn-lg">Voltar</button>
                            </div>
                             <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('banda/editar?banda=').$banda[0]['banda_id'].'&pessoa='.$pessoa['pessoa_id']?>'" type="button" class="btn btn-block btn-info btn-lg">Editar Dados</button>
                             </div>
                          </div><br>    
                    </div>
                  </div>
                </div>
              </div>
            </div>
</div>


