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
      <?php if(@$perfil){?>
      <img height="50" width="50" alt="Brand" src="<?php echo @$perfil?>">
      <?php }else{ ?>
        <img height="50" width="50" alt="Brand" src="http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png">
      <?php }?>
    </div>
    <!-- Apenas irá aparecer para o administrador -->
    <?php if($_SESSION['email'] == "admin@admin.com") {?>
       <div class="collapse navbar-collapse" id="navbar-ex-collapse">   
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a data-toggle="modal" data-target="#modalgenero" href="#">Cadastrar Gênero Musical</a>
        </li> 
        <li>
          <a data-toggle="modal" data-target="#modalfuncao" href="#">Cadastrar Função</a>
        </li>  
        <li>
          <a href="<?php echo base_url('conta/sair')?>">Sair</a>
        </li>
      </ul>
      <a href="<?php echo base_url('dashboard/index')?>"><p class="navbar-left navbar-text"><?php echo $_SESSION['email']; ?></p></a> 
    </div>
    <?php }else{ ?>
    <div class="collapse navbar-collapse" id="navbar-ex-collapse">  
      <ul class="nav navbar-nav navbar-right"> 
      <li>
          <a href="<?php echo base_url('pessoa/users')?>">Mapa</a>
        </li>
        <li>
          <a href="<?php echo base_url('pessoa/meusdados')?>">Meus Dados</a>
        </li>
       <!-- <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
              aria-expanded="false">Músicos <i class="fa fa-caret-down"></i></a>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="<?php echo base_url('pessoa/users')?>">Localizar músicos</a>
              <!--   </li>
                <li>
                  <a href="<?php echo base_url('pessoa/users')?>">Localizar por função</a>
             <!--    </li>
                <li>
                  <a href="<?php echo base_url('pessoa/users')?>">Localizar próximos</a>
             <!--    </li>
                <li class="divider"></li>
                <li>
                  <a href="#">Localizar bandas</a>
                </li>
              </ul>
            </li> -->
        <li class="dropdown"><div id="indice"></div>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
              aria-expanded="false"><i id="notificaatividade" class="fa fa-fw fa-bell"></i> Atividades</a>

          <ul id="atividade" class="dropdown-menu atividade" role="menu"> 
          </ul>
          
        </li>

        <li class="dropdown"><div id="indice_banda"></div>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
              aria-expanded="false"><i id="notificabanda" class="fa fa-fw fa-bell"></i> Bandas</a>
          <ul id="banda" class="dropdown-menu atividade" role="menu">    
          </ul>
        </li>
        <li>
          <a href="<?php echo base_url('conta/sair')?>">Sair</a>
        </li>
      </ul>
      <a href="<?php echo base_url('dashboard/index')?>"><p class="navbar-left navbar-text"><?php echo $_SESSION['email']; ?></p></a> 
          
                    <form action="<?php echo base_url('pessoa/users')?>" class="navbar-form navbar-left" role="search">
                      <div class="form-group">
                        <input id="input_menu" class="form-control" type="text" placeholder="Procure Pessoas, músicos e bandas..." autocomplete="off">
                          <!-- MOSTRA OS USUARIO DA BUSCA-->
                          <div id="box-s-h">
                            <ul class="src">

                            </ul>
                          </div>
                        <!--<button type="submit" class="pull-rigth">Ok</button>-->
                      </div>
                    </form>
                        
    </div>
    <?php } ?>
  </div>
</div>



<!-- Sistema de busca ao digitar algo no campo-->
            <script>
            $("document").ready(function(){
                $("#input_menu").keyup(function(){
                    var $this = $(this);
                    var val   = $this.val();
                    
                      if(val == ""){
                        $('.src').html("");
                      }else{
                        $.ajax({
                          url: "<?php echo base_url('pagina/busca')?>",
                          type: "POST",
                          data: {nome: val},
                          cache: false,
                          
                          success: function(res){
                              $('.src').html(res);
                          }
 
                        });
                      }
                    });
                    $('html, body').click(function(){
                    $('.src').html("");
                }); 
            });
            </script> 


          <script>
          $("#indice").hide();
          $("#indice_banda").hide();
                notifica_atividade();
                notifica_banda();
//ATUALIZA AS NOTIFICAÇÕES DE ATIVIDADES ---------------------------------------------------
                function notifica_atividade()
                { 
                   var dados = {pessoa_id : <?php echo $pessoa['pessoa_id']?>};
                          $.ajax({
                              type: "POST",
                              data: { id_notifica: JSON.stringify(dados)},
                              datatype: 'json',
                              url: "<?php echo site_url('pagina/atualiza_notificacao_atividade'); ?>",       
                              success: function(data){
                                var atividades = 0;
                                var resultado = JSON.parse(data);
                                $("#atividade").empty();
                                $("#indice").hide();$("#indice").empty();

                                
                                //ATIVIDADES QUE FALTA FINALIZAR
                                if(resultado[1].length){
                                  
                                  for (var a = 0; a < resultado[1].length; a++) { 
                                    $("#notificaatividade").css("color","#FF0000");
                                      $("#atividade").prepend("<li><a id='semquebralinha' href='<?php echo base_url('pessoa/pendente?atividade=')?>"+resultado[1][a].atividade_id+"'><i class='glyphicon glyphicon-alert text-danger'></i>&nbsp&nbsp&nbsp Por favor, finalize a atividade "+resultado[1][a].atividade_titulo+"</a></li>");
                                      atividades = atividades + 1;
                                      $("#indice").empty();
                                      $("#indice").append("<p id='valor_indice'>"+atividades+"</p>");
                                      $("#indice").show(); 
                                  }  
                                  $("#atividade").prepend("<li class='divider'></li>");                               
                                }
                                
                                //$("#atividade").prepend("<li class='divider'></li>");
                                //PENDENCIAS 
                                if(resultado[0].length){
                                   
                                  for (var i = 0; i < resultado[0].length; i++) {  
                                    for (var s = 0; s < resultado[2].length; s++){
                                        if(resultado[0][i].atividade_id == resultado[2][s][0].atividade_id){
                                           $("#notificaatividade").css("color","#FF0000");
                                           $("#atividade").prepend("<li><a id='semquebralinha' href='<?php echo base_url('pessoa/notificacao?atividade=')?>"+resultado[0][i].atividade_id+"'><i class='glyphicon glyphicon-ok-circle text-success'></i>&nbsp&nbsp&nbsp"+resultado[2][s][0].pessoa_nome+' te convidou para participar da  atividade '+resultado[0][i].atividade_titulo+"</a></li>"); 
                                           atividades = atividades + 1;
                                          $("#indice").empty();
                                          $("#indice").append("<p id='valor_indice'>"+atividades+"</p>");
                                          $("#indice").show(); 
                                        }
                                    }
                                  }
                                  $("#atividade").prepend("<li class='divider'></li>");  
                                }

                                //RESPOSTAS AS NOTIFICAÇÕES ENVIADAS 
                                if(resultado[3].length)
                                {
                                  
                                   for (var a = 0; a < resultado[3].length; a++) { 
                                        $("#notificaatividade").css("color","#FF0000");
                                        $("#atividade").prepend("<li><a id='semquebralinha' href='<?php echo base_url('pessoa/resposta?atividade=')?>"+resultado[3][a].atividade_id+"&pessoa="+resultado[3][a].pessoa_id+"'><i class='glyphicon glyphicon-bookmark text-info'></i>&nbsp&nbsp&nbsp"+resultado[3][a].pessoa_nome+" respondeu a sua solicitação '"+resultado[3][a].atividade_titulo+"'</a></li>");
                                        atividades = atividades + 1;
                                        $("#indice").empty();
                                        $("#indice").append("<p id='valor_indice'>"+atividades+"</p>");
                                        $("#indice").show(); 
                                    } 
                                    $("#atividade").prepend("<li class='divider'></li>");
                                }
                              },
                              error: function(e){
                                  console.log(e.message);
                              }
                          }); 
                          setTimeout('notifica_atividade()', 10000);
                } 

//ATUALIZA AS NOTIFICAÇÕES DE BANDAS ---------------------------------------------------
                function notifica_banda()
                {
                   var dados = {pessoa_id : <?php echo $pessoa['pessoa_id']?>};
                          $.ajax({
                              type: "POST",
                              data: { id_notifica: JSON.stringify(dados)},
                              datatype: 'json',
                              url: "<?php echo site_url('pagina/atualiza_notificacao_banda'); ?>",       
                              success: function(data){ 
                                var atividades_banda = 0;
                                var resultado = JSON.parse(data);
                                $("#banda").empty();
                                $("#indice_banda").hide();$("#indice_banda").empty();
                                //PENDENCIAS 
                                if(resultado[0].length){
                                   
                                  for (var i = 0; i < resultado[0].length; i++) {  
                                    for (var s = 0; s < resultado[2].length; s++){
                                        if(resultado[0][i].banda_id == resultado[2][s][0].bandas_banda_id){
                                           $("#notificabanda").css("color","#FF0000");
                                           $("#banda").prepend("<li><a id='semquebralinha' href='<?php echo base_url('integrante/notificacao?banda=')?>"+resultado[0][i].banda_id+"'><i class='glyphicon glyphicon-ok-circle text-success'></i>&nbsp&nbsp&nbsp"+resultado[2][s][0].pessoa_nome+' te convidou para participar da  banda '+resultado[0][i].banda_nome+"</a></li>");
                                           atividades_banda = atividades_banda + 1; 
                                          $("#indice_banda").empty();
                                          $("#indice_banda").append("<p id='valor_indice'>"+atividades_banda+"</p>");
                                          $("#indice_banda").show();
                                        }
                                    }
                                  }
                                  $("#banda").prepend("<li class='divider'></li>");  
                                }
                                //PEDIDOS PARA PARTICIPAR DA BANDA
                                if(resultado[1].length)
                                {
                                  
                                   for (var a = 0; a < resultado[1].length; a++) { 
                                        $("#notificabanda").css("color","#FF0000");
                                        $("#banda").prepend("<li><a id='semquebralinha' href='<?php echo base_url('integrante/pedido?banda=')?>"+resultado[1][a].banda_id+"&pessoa="+resultado[1][a].pessoa_id+"'><i class='glyphicon glyphicon-bookmark text-info'></i>&nbsp&nbsp&nbsp"+resultado[1][a].pessoa_nome+" deseja participar na banda '"+resultado[1][a].banda_nome+"'</a></li>");
                                        atividades_banda = atividades_banda + 1; 
                                        $("#indice_banda").empty();
                                        $("#indice_banda").append("<p id='valor_indice'>"+atividades_banda+"</p>");
                                        $("#indice_banda").show();
                                    }
                                    $("#banda").prepend("<li class='divider'></li>"); 
                                }
                                //RESPOSTAS AS NOTIFICAÇÕES ENVIADAS 
                                if(resultado[3].length)
                                {
                                  
                                   for (var a = 0; a < resultado[3].length; a++) { 
                                        $("#notificabanda").css("color","#FF0000");
                                        $("#banda").prepend("<li><a id='semquebralinha' href='<?php echo base_url('integrante/resposta?banda=')?>"+resultado[3][a].banda_id+"&pessoa="+resultado[3][a].pessoa_id+"'><i class='glyphicon glyphicon-bookmark text-info'></i>&nbsp&nbsp&nbsp"+resultado[3][a].pessoa_nome+" respondeu a sua solicitação '"+resultado[3][a].banda_nome+"'</a></li>");
                                        atividades_banda = atividades_banda + 1; 
                                        $("#indice_banda").empty();
                                        $("#indice_banda").append("<p id='valor_indice'>"+atividades_banda+"</p>");
                                        $("#indice_banda").show();
                                    } 
                                    $("#banda").prepend("<li class='divider'></li>");
                                }
                                 //RESPOSTAS AOS PEDIDOS ENVIADOS PARA PARTICIPAR EM OUTRAS BANDAS
                                if(resultado[4].length)
                                {
                                  
                                   for (var a = 0; a < resultado[4].length; a++) { 
                                        $("#notificabanda").css("color","#FF0000");
                                        $("#banda").prepend("<li><a id='semquebralinha' href='<?php echo base_url('integrante/resposta_banda?banda=')?>"+resultado[4][a].banda_id+"&pessoa="+resultado[4][a].pessoa_id+"'><i class='glyphicon glyphicon-bookmark text-info'></i>&nbsp&nbsp&nbsp"+resultado[4][a].banda_nome+" respondeu a sua solicitação</a></li>");
                                        atividades_banda = atividades_banda + 1;
                                        $("#indice_banda").empty(); 
                                        $("#indice_banda").append("<p id='valor_indice'>"+atividades_banda+"</p>");
                                        $("#indice_banda").show();
                                    } 
                                    $("#banda").prepend("<li class='divider'></li>");
                                }
                              },
                              error: function(e){
                                  console.log(e.message);
                              }
                          });  
                          setTimeout('notifica_banda()', 10000);   
                } 
                </script>


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
                                    url: "<?php echo site_url('administrador/cadastrarfuncao'); ?>",      
                                    success: function(data){ 
                                     alert('Salvo com Sucesso');      
                                     document.location.reload();
                                    },
                                    error: function(e){
                                      alert('Não salvou');
                                        console.log(e.message);
                                    }
                                }); 

                            });
                          </script> 

<!-- MODAL PARA ADICIONAR GÊNERO -->
                          <div class="modal fade" id="modalgenero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Cadastre um novo Gênero abaixo:</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                    <label class="control-label" for="exampleInputEmail1">Nome do Gênero</label>
                                    <input id="nomegenero" class="form-control" name="nomegenero" placeholder="Ex.: Guitarrista, Tecladista, Vocalista..."
                                    type="text" required>
                                    <label class="control-label" for="exampleInputPassword1">Explicação</label>
                                    <textarea id="expecificacaogenero" class="form-control" rows="3" placeholder="Digite aqui uma breve explicação das atividades e requisitos dessa função."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                  <button id="cadastrargenero" type="button" class="btn btn-primary">Cadastrar</button>
                                </div>
                              </div>
                            </div>
                          </div>

                          <script>
                            $('#cadastrargenero').click(function() {
                                var dados = {
                                  genero_nome : $('#nomegenero').val(),
                                  genero_explicacao : $('#expecificacaogenero').val()
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('administrador/cadastrargenero'); ?>",      
                                    success: function(data){  
                                    alert('Salvo com Sucesso');   
                                     document.location.reload();
                                    },
                                    error: function(e){
                                      alert('Não salvou');
                                        console.log(e.message);
                                    }
                                }); 

                            });
                          </script> 
                        