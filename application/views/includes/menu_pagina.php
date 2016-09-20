<div class="navbar navbar-default navbar-inverse navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="<?php echo base_url('dashboard/index')?>" class="navbar-brand"><img height="25" width="50" alt="Brand" src="<?php echo base_url('public/imagens/icone_clave.png')?>"></a>
    </div>
    <div class="collapse navbar-collapse" id="navbar-ex-collapse">
    <!-- Apenas irá aparecer para o administrador -->
      <ul class="nav navbar-nav navbar-right">
      <?php if($_SESSION['email'] == "admin@admin.com") {?>
        <li>
          <a data-toggle="modal" data-target="#modalfuncao" href="#">Cadastrar Função</a>
        </li>
      <?php } ?>
        <li>
          <a href="<?php echo base_url('pessoa/meusdados')?>">Meus Dados</a>
        </li>
        <li>
          <a href="#">Músicos</a>
        </li>
        <li>
          <a href="#"><i class="fa fa-bell fa-fw"></i>Atividades</a>
        </li>
        <li>
          <a href="#"><i class="fa fa-fw fa-bell"></i>Bandas</a>
        </li>
        <li>
          <a href="<?php echo base_url('conta/sair')?>">Sair</a>
        </li>
      </ul>
      <a href="<?php echo base_url('dashboard/index')?>"><p class="navbar-left navbar-text"><?php echo $_SESSION['email']; ?></p></a> 
    </div>
  </div>
</div>





<!-- MODAL PARA ADICIONAR FUNÇÃO -->
                          <div class="modal fade" id="modalfuncao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Cadastre uma nova Função abaixo:</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                    <label class="control-label" for="exampleInputEmail1">Nome da Função</label>
                                    <input id="nomefuncao" class="form-control" name="nomefuncao" placeholder="Ex.: Guitarrista, Tecladista, Vocalista..."
                                    type="text" required>
                                    <label class="control-label" for="exampleInputPassword1">Explicação</label>
                                    <textarea id="expecificacaofuncao" class="form-control" rows="3" placeholder="Digite aqui uma breve explicação das atividades e requisitos dessa função."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                  <button id="cadastrarfuncao" type="button" class="btn btn-primary">Cadastrar</button>
                                </div>
                              </div>
                            </div>
                          </div>



                          <script>
                            $('#cadastrarfuncao').click(function() {
                                var dados = {
                                  funcao_nome : $('#nomefuncao').val(),
                                  funcao_explicacao : $('#expecificacaofuncao').val()
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('funcao/cadastrar'); ?>",      
                                    success: function(data){     
                                     document.location.reload();
                                    },
                                    error: function(e){
                                      alert('Não salvou');
                                        console.log(e.message);
                                    }
                                }); 

                            });
                          </script> 