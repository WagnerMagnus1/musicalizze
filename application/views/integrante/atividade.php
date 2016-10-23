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
                          <div class="col-md-12">
                            <center><h3 class="modal-title" id="myModalLabel">Banda "<?php echo $banda[0]['banda_nome']?>"</h3></center>
                          </div>
                         </div>

                          </div>
                          <div class="modal-body">
                          <div class="form-group">
                          <table class="table table-striped">
                          <tbody><br>
                      <center><h3 id="semquebralinha"><?php echo $banda[0]['banda_nome']?></h3><p id="semquebralinha"> esta participando da atividade</p><p>"<?php echo $banda[0]['atividade_titulo']?>"</p><br></center><br>
                            </tbody>
                            </table><br><hr>
                            
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
                <div class="col-md-12">
                  <center><button id="visualizado" type="button" class="btn btn-block btn-info btn-lg"><span class="glyphicon glyphicon-ok"></span> Visualizado</button></center> 
                </div>                        
            <br>    
          </div> 
          <div class="row">
            <div class="col-md-12">
              <center><span class="glyphicon glyphicon-arrow-left"><h4 id="semquebralinha" ><a id="mao" href="<?php echo base_url('pagina/index')?>"> Voltar</a></h4></center>
            </div>
          </div>
        </div>

<!-- MODAL PARA FINALIZAR TAREFA COMO EXECUTADO -->

                          <script>
                            $('#visualizado').click(function() {
                                var dados = {
                                  integrante_atividade_id : "<?php echo $banda[0]['integrante_atividade_id'] ?>",
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('integrante/visualizado_atividade'); ?>",      
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
