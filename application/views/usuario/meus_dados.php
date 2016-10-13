<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h2 class="text-center">Meus Dados</h2>      
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
                        <img id="uploadPreview" src="<?php echo $dados['pessoa_foto']?>" class="img-responsive img-thumbnail"><br>

                        <div id="gridbotaofoto">
                          <button id="addphoto" name="adicionarphoto" value="adicionar" type="submit" class="btn-info">Adicionar uma Foto</button>
                          <button id="salvarphoto" name="salvarphoto" value="editar" type="submit" class="btn-info" disabled>Salvar a foto</button>
                          <button id="excluirphoto" name="excluirphoto" value="excluir" type="submit" class="btn-info">Deletar</button>
                        </div>
                        
                        <!-- ELEMENTO INPUT INVISIVEL-->
                        <input class="btn-block" id="input-1" type="file" name="myPhoto" onchange="PreviewImage();" /> 
                         <!-- ELEMENTO INPUT INVISIVEL-->
                        <input class="btn-block" id="perfil" type="hidden"/> 
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
                                pessoa_id : $('#id_pessoa').val(), 
                                pessoa_foto : $('#perfil').val(),
                                pessoa_nome : '<?php echo $dados['pessoa_nome'] ?>',
                                img : document.getElementById("uploadPreview").src.toString()
                              }; 

                                  $.ajax({            
                                      type: "POST",
                                      data: { dado: JSON.stringify(dado)},
                                      datatype: 'json',
                                      url: "<?php echo site_url('pessoa/salvar_foto'); ?>",      
                                      success: function(data){     
                                        alert("Salvo com sucesso!", "success");
                                        $("#salvarphoto").prop("disabled", true);
                                        window.location.href = "<?php echo base_url('pessoa/meusdados')?>";
                                      },
                                      error: function(e){
                                          alert("Erro. A foto não foi salva!", "error");
                                      }
                                  }); 
                            });
                           
                        </script>
                  </div>
                    <div class="col-md-8">
                        <input id="perfil" type="hidden" name="perfil" value="<?php echo $dados['pessoa_foto']?>">
                        <input id="id_pessoa" type="hidden" name="id_pessoa" value="<?php echo $dados['pessoa_id']?>">
                          <table class="table table-striped">
                          <tbody>
                           <tr>
                             <th scope="row">Nome:</th>
                             <td><?php echo @$dados['pessoa_nome'] ?></td>
                           </tr>
                           <tr>
                              <th scope="row">Sobrenome:</th>
                              <td><?php echo @$dados['pessoa_sobrenome'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Data de Nascimento:</th>
                              <td><?php echo date('d/m/Y', strtotime(@$dados['pessoa_nascimento']));?></td>
                            </tr>
                            <tr>
                              <th scope="row">Idade:</th>
                              <td><?php echo @$pessoa_idade?></td>
                            </tr>
                            <tr>
                              <th scope="row">Gênero:</th>
                               <td><?php echo @$dados['pessoa_sexo'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Telefone:</th>
                               <td><?php echo @$dados['pessoa_telefone'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Endereço:</th>
                               <td><?php echo @$dados['pessoa_endereco'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Estado:</th>
                               <td><?php echo @$dados['pessoa_estado']?></td>
                            </tr>
                            <tr>
                              <th scope="row">Cidade:</th>
                               <td><?php echo @$dados['pessoa_cidade'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Observação:</th>
                              <td><?php echo @$dados['pessoa_obs'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Outros Contatos:</th>
                              <td><?php echo @$dados['pessoa_contato'] ?></td>
                            </tr><hr>
                            <tr>
                              <th scope="row">Habilidades/ Funções:</th>
                               <td>
                                 <?php if($funcao) { ?>
                                  <?php foreach($funcao as $f) { ?>
                                    <h4><?php echo $f['funcao_nome']?></h4> 
                                  <?php } ?>
                                  <?php } ?>
                               </td>
                            </tr>    

                          </tbody>
                          </table><br>
                          <div class="row">
                            <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('pagina/index');?>'" type="button" class="btn btn-block btn-lg">Voltar</button>
                            </div>
                             <div class="col-md-6">
                              <button onclick="window.location.href='<?php echo base_url('pessoa/editar');?>'" value="cadastrar" class="btn btn-block btn-info btn-lg">Editar Dados</button>
                             </div>
                          </div><br>    
                    </div>
                  </div>
                </div>
              </div>
            </div>
</div>


