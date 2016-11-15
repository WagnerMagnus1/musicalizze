<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h3 class="text-center"><?php echo $banda[0]['banda_nome']?>/ Integrantes</h3>      
                    <div class="col-md-12 text-justify">
                      <hr>
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
               
                         <?php $i=0; foreach($integrantes as $lista) {?>
                          <?php if($lista['pessoa_id'] != $pessoa['pessoa_id']){ ?>
                          
                                <div class="row">
                                  <div class="col-md-4">
                                  <img height="100" width="100" src="<?php echo $lista['pessoa_foto']?>" class="pull-left">
                                  </div>
                                    <div class="col-md-4">
                                        <a href="<?php echo base_url('pessoa/dados?pessoa_id=').$lista['pessoa_id'].'&nome='.$lista['pessoa_nome']?>" id="mao"><h4 id='semquebralinha'><?php echo $lista['pessoa_nome']?></h4 id='semquebralinha'></a>
                                    </div>
                                    <div class="col-md-4">
                                      <button data-toggle="modal" data-target="#cancelaratividade<?php echo $i ?>" id="inativarintegrante<?php echo $lista['integrante_id']?>" type="button" class="btn pull-right btn-danger"><span class="glyphicon glyphicon-remove"></span> Inativar Integrante</button>
                                    </div>
                                </div>
                                <hr>

<!--___________________________________ MODAL PARA INATIVAR INTEGRANTE -->
                          <div class="modal fade" id="cancelaratividade<?php echo $i ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  
                                  <center><h4 id="myModalLabel">Tem certeza que deseja INATIVAR o integrante "<?php echo $lista['pessoa_nome']?>"?</h4><center>
                                  <p>IMPORTANTE: Esse integrante será desvinculado da banda, porém as atividades em aberto até o momento continuarão com a sua participação.</p>
                                </div>
                               <div class="col-md-12">
                                  <div class="form-group">
                                    <label class="control-label" for="exampleInputPassword1"><h5>Justificativa:</h5></label>
                                    <textarea id="justificativa" class="form-control" rows="3"></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                <div class="col-md-6">
                                  <center><button data-dismiss="modal" type="button" class="btn btn-default btn-block">Não</button></center>
                                </div>
                                <div class="col-md-6">
                                  <center><button id="inativar<?php echo $lista['integrante_id']?>" type="button" class="btn btn-block btn-info">Sim</button></center>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                                <script>
                                $('#inativar<?php echo $lista['integrante_id']?>').click(function() {
                                    var dados = {
                                      integrante : "<?php echo $lista['integrante_id'] ?>",
                                      justificativa : $('#justificativa').val()
                                    };

                                    $.ajax({            
                                        type: "POST",
                                        data: { dados: JSON.stringify(dados)},
                                        datatype: 'json',
                                        url: "<?php echo site_url('integrante/inativar_integrante'); ?>",      
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
                        <?php $i++; } ?>
                 
              </div>
            </div>
</div>


