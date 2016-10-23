<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                    <div class="row">
                      <div class="col-md-12">
                        
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">

                        <?php if(@$atividade[0]['pessoa_id'] == @$adm[0]['pessoa_id']){?>
                          <div class="row">
                          <div class="col-md-12">
                            <center><h3 id="semquebralinha" class="modal-title" id="myModalLabel">Atividade: <?php echo $atividade[0]['atividade_titulo']?></h3></center>
                          </div>
                         </div>
                         <?php }else{?>
                         <div class="row">
                          <div class="col-md-12">
                            <center><h3 id="semquebralinha" class="modal-title" id="myModalLabel">Atividade: <?php echo $atividade[0]['atividade_titulo']?></h3></center>
                          </div>
                         </div>
                        <?php }?>
                          <div class="row">
                          <div class="col-md-12">
                            <center><p id="semquebralinha">Vinculado a banda </p><h4 id="semquebralinha" class="modal-title" id="myModalLabel"><?php echo $atividade[0]['banda_nome']?></h4></center>
                          </div>
                         </div>

                          </div>
                          <div class="modal-body">
                          <div class="form-group">
                          <table class="table table-striped">
                          <tbody>
                           <tr>
                             <th scope="row">Contrato:</th>
                             <td><?php echo $atividade[0]['atividade_contrato'] ?></td>
                           </tr>
                           <tr>
                              <th scope="row">Explicação:</th>
                              <td><?php echo $atividade[0]['atividade_especificacao'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Data:</th>
                              <td><?php echo date('d/m/Y', strtotime($atividade[0]['atividade_data'])); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Horário:</th>
                              <td><?php echo date('H:i:s', strtotime($atividade[0]['atividade_data'])); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Endereço:</th>
                              <td><?php echo $atividade[0]['atividade_endereco']?> </td>
                            </tr>
                            <tr>
                              <th scope="row">Tipo:</th>
                               <td><?php echo $atividade[0]['atividade_tipo'] ?></td>
                            </tr>

                            <hr>
                            </tbody>
                            </table><br><hr>
                            <!-- Mostra qual o usuario criador da atividade -->           
                <?php if($atividade[0]['Pessoas_Funcoes_Pessoas_pessoa_id'] != $adm[0]['pessoa_id']) {?>
                        <center><footer>Essa atividade foi criado por  <a href="<?php echo base_url('pessoa/dados?pessoa_id=').$adm[0]['pessoa_id'].'&nome='.$adm[0]['pessoa_nome']?>" id="mao"><?php echo $adm[0]['pessoa_nome']?></a></footer></center>
                <?php }?><br>
                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <div class="row espaco_baixo">         
                <div class="col-md-6">
                    <button data-toggle="modal" data-target="#naoexecutado" type="button" class="btn btn-block btn-lg"><span class="glyphicon glyphicon-remove"></span> Não Executado</button>
                </div>
                <div class="col-md-6">
                  <button data-toggle="modal" data-target="#executado" type="button" class="btn btn-block btn-info btn-lg"><span class="glyphicon glyphicon-ok"></span> Executado</button>
                </div>                         
            <br>    
          </div> 
          <div class="row">
            <div class="col-md-12">
              <center><span class="glyphicon glyphicon-arrow-left"><h4 id="semquebralinha" ><a id="mao" href="<?php echo base_url('pagina/index')?>"> Voltar</a></h4></center>
            </div>
          </div>
        </div>
  

  <!-- MODAL PARA FINALIZAR TAREFA COMO NÃO EXECUTADO -->
                          <div class="modal fade" id="naoexecutado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  
                                  <center><h4 id="myModalLabel">Tem certeza disso?</h4><center>
                                </div>
                            
                                <div class="modal-footer">
                                <div class="col-md-6">
                                  <center><button data-dismiss="modal" type="button" class="btn btn-default btn-block">Não</button></center>
                                </div>
                                <div class="col-md-6">
                                  <center><button id="naoexe" type="button" class="btn btn-block btn-info">Sim</button></center>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <script>
                            $('#naoexe').click(function() {
                                var dados = {
                                  integrante_atividade : "<?php echo $atividade[0]['integrante_atividade_id'] ?>",
                                  integrante : "<?php echo $atividade[0]['Integrantes_integrante_id'] ?>",
                                  atividade : "<?php echo $atividade[0]['Atividades_atividade_id'] ?>"
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('atividade/executado_integrante_nao_executado'); ?>",      
                                    success: function(data){     
                                      window.location.href = "<?php echo base_url('pagina/index')?>";
                                    },
                                    error: function(e){
                                      alert('Erro! Por favor tente novamente.');
                                        console.log(e.message);
                                    }
                                }); 

                            });
                          </script> 

<!-- MODAL PARA FINALIZAR TAREFA COMO EXECUTADO -->
                          <div class="modal fade" id="executado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  
                                  <center><h4 id="myModalLabel">Tem certeza disso?</h4><center>

                                </div>
                               <div class="modal-body">
                               <div class="form-group">
                                 <label class="control-label" for="exampleInputEmail1">Valor:</label><br>
                                      <label>Obs.: Para inserir CUSTOS, informe o sinal negativo (-).</label>
                                      <input id="valor" name="valor" class="form-control" name="nometitulo"  
                                      type="text">
                                    <script>
                                      $(document).ready(function() {    
                                          $("#valor").maskMoney({allowZero:true, allowNegative:true, decimal:",",thousands:"."});
                                      });
                                    </script>
                               </div>
                               </div>
                            
                                <div class="modal-footer">
                                <div class="col-md-6">
                                  <center><button data-dismiss="modal" type="button" class="btn btn-default btn-block">Não</button></center>
                                </div>
                                <div class="col-md-6">
                                  <center><button id="exe" type="button" class="btn btn-block btn-info">Sim</button></center>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <script>
                            $('#exe').click(function() {

                                var dados = {
                                  integrante_atividade : "<?php echo $atividade[0]['integrante_atividade_id'] ?>",
                                  integrante : "<?php echo $atividade[0]['Integrantes_integrante_id'] ?>",
                                  atividade : "<?php echo $atividade[0]['Atividades_atividade_id'] ?>",
                                  valor : $('#valor').val()
                                };
                                

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('atividade/executado_integrante'); ?>",      
                                    success: function(data){     
                                      window.location.href = "<?php echo base_url('pagina/index')?>";
                                    },
                                    error: function(e){
                                      alert('Erro! Por favor tente novamente.');
                                        console.log(e.message);
                                    }
                                }); 
                            });
                          </script> 
