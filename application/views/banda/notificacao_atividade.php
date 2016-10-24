<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                    <div class="row">
                      <div class="col-md-12">
                        
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                         <div class="row">
                          <div class="col-md-2">
                            <img height="100" width="100" alt="Brand" src="<?php echo $adm[0]['pessoa_foto']?>">
                          </div>
                          <div class="col-md-10">
                            <h3 class="modal-title" id="myModalLabel"><a href="<?php echo base_url('pessoa/dados?pessoa_id=').$adm[0]['pessoa_id'].'&nome='.$adm[0]['pessoa_nome']?>"><?php echo $adm[0]['pessoa_nome']?></a> convidou "<?php echo $atividade[0]['banda_nome']?>" para participar da atividade "<?php echo $atividade[0]['atividade_titulo']?>"</h3>
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
                            </table><br>
                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <br>
            </div>
          </div>
          <div class="row espaco_baixo">         
                <div class="col-md-6">
                    <button data-toggle="modal" data-target="#recusaratividade" type="button" class="btn btn-block btn-lg"><span class="glyphicon glyphicon-remove"></span> Recusar</button>
                </div>
                <div class="col-md-6">
                  <button data-toggle="modal" data-target="#aceitaratividade" type="button" class="btn btn-block btn-info btn-lg"><span class="glyphicon glyphicon-ok"></span> Aceitar Convite</button>
                </div>                         
            <br>    
          </div> 
          <div class="row">
            <div class="col-md-12">
              <center><span class="glyphicon glyphicon-arrow-left"><h4 id="semquebralinha" ><a id="mao" href="<?php echo base_url('pagina/index')?>"> Voltar</a></h4></center>
            </div>
          </div>
        </div>
  

  <!-- MODAL PARA RECUSAR NOTIFICAÇÃO -->
                          <div class="modal fade" id="recusaratividade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  
                                  <center><h4 id="myModalLabel">Tem certeza que deseja RECUSAR a solicitação?</h4><center>
                                </div>
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label class="control-label" for="exampleInputPassword1"><h5>Justificativa:</h5></label>
                                    <textarea id="justificativa" class="form-control" rows="3" value=""></textarea>
                                  </div>
                                </div>
                      
                                <div class="modal-footer">
                                <div class="col-md-6">
                                  <center><button data-dismiss="modal" type="button" class="btn btn-default btn-block">Não</button></center>
                                </div>
                                <div class="col-md-6">
                                  <center><button id="recusar" type="button" class="btn btn-block btn-info">Sim</button></center>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <script>
                            $('#recusar').click(function() {
                                var dados = {
                                  atividade : "<?php echo $atividade[0]['Atividades_atividade_id'] ?>",
                                  integrante : "<?php echo $atividade[0]['integrante_id'] ?>",
                                  justificativa : $('#justificativa').val()
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('atividade/notificacao_banda_recusar'); ?>",      
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


                          <!-- MODAL PARA ACEITAR NOTIFICAÇÃO -->
                          <div class="modal fade" id="aceitaratividade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  
                                  <center><h4 id="myModalLabel">Tem certeza que deseja ACEITAR a solicitação?</h4><center>
                                </div>
                            
                                <div class="modal-footer">
                                <div class="col-md-6">
                                  <center><button data-dismiss="modal" type="button" class="btn btn-default btn-block">Não</button></center>
                                </div>
                                <div class="col-md-6">
                                  <center><button id="aceitar" type="button" class="btn btn-block btn-info">Sim</button></center>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <script>
                            $('#aceitar').click(function() { 
                                var dados = {
                                  atividade : "<?php echo $atividade[0]['Atividades_atividade_id'] ?>",
                                  integrante : "<?php echo $atividade[0]['Integrantes_integrante_id'] ?>"
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('atividade/notificacao_banda_aceitar'); ?>",      
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


            