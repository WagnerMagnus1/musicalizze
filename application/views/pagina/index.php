
<?php if(!$pessoa){
  redirect('pagina/index');
}?>
<link href="<?php echo base_url('assets/css/style.css')?>" rel="stylesheet" type="text/css">
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
       <?php if(@$alerta){ ?>
            <div class="alert alert-<?php echo $alerta["class"]; ?>"> 
            <?php echo $alerta["mensagem"]; ?>
           </div>  
          <?php }else{ ?><br><br><br>
      <?php if(@!$atividades_aberto) {?>
        <div class="col-md-12">
          <h1 class="text-center">Musicalizze</h1><br>
          <p class="text-center">Olá <?php echo $pessoa['pessoa_nome']?>, você ja pode começar a utilizar nosso site.</p>
          <p class="text-center">Desejamos muito sucesso nessa jornada.</p><br>
        </div>
      <?php }else {?>
        <div class="col-md-12">
          <h1 class="text-center">Musicalizze</h1><br>
          <p class="text-center"><?php echo $pessoa['pessoa_nome']?>, abaixo estão ordenados as suas próximas atividades.</p>
        </div>
      <?php } ?>
        <?php }?>
          <div class="row">
                <div class="col-md-6">
                  <button data-toggle="modal" data-target="#modalatividade" href="#" type="button" class="botao btn btn-block btn-lg">Criar Atividade</button>
                </div>
                <div class="col-md-6">
                  <button data-toggle="modal" data-target="#modalbanda" href="#" type="button" class="botao btn btn-block btn-lg">Criar Banda</button>
                </div>
          </div><br>  
      </div>
    </div>
  </div>
</div>

<!-- BUSCA LOCALIZAÇÃO DA PESSOA LOGADA -->
<script>
    function getLocation()
      {
      if (navigator.geolocation)
        {
        navigator.geolocation.getCurrentPosition(showPosition);
        }
      else{alert("O seu navegador não suporta Geolocalização.");}
      }
    function showPosition(position)
      {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
          var dados = {
            pessoa : '<?php echo $pessoa['pessoa_id'] ?>',
            latitude : latitude.toString(),
            longitude : longitude.toString()
          };

//SALVA A INFORMAÇÃO NA TABELA DA PESSOA LOGADA
          $.ajax({            
              type: "POST",
              data: { dados: JSON.stringify(dados)},
              datatype: 'json',
              url: "<?php echo site_url('pessoa/salvar_longitude_latitude'); ?>",      
              success: function(data){     
              },
              error: function(e){
                alert('Sua localização não foi salva! Verifique se o seu navegador esta configurado para aceitar essa busca, ou atualize o navegador.');
                  console.log(e.message);
              }
          }); 
      }
      getLocation();
</script>

<!-- MODAL PARA ADICIONAR ATIVIDADE -->
                          <div class="modal fade" id="modalatividade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <center><h3 class="modal-title" id="myModalLabel">Cadastre a atividade:</h3></center>
                                </div>
                                  <div class="modal-body">
                                  <div class="form-group">
                          <form id="formatividade" role="form" method="POST" name="cadastrar" action="<?php echo base_url('atividade/salvar'); ?>">
                            <input type="hidden" name="captcha">
                            <input type="hidden" name="pessoa_id" value="<?php echo $pessoa['pessoa_id'];?>">
                                      <label class="control-label" for="exampleInputEmail1">Título:</label>
                                      <input name="nometitulo" class="form-control" placeholder="Aqui você podera informar o nome da festa ou do evento."
                                      type="text" required>
                                      <label class="control-label" for="exampleInputPassword1">Contrato:</label>
                                      <textarea name="contrato" class="form-control" rows="3" placeholder="Regras a serem cumpridas."></textarea>
                                      <label class="control-label" for="exampleInputPassword1">Explicação:</label>
                                      <textarea name="explicacao" class="form-control" rows="3" placeholder="Digite aqui uma breve explicação da atividade." required></textarea>
                                     
                                      <fieldset>
                                        <label class="control-label">Tipo</label><br>
                                        <select class="form-control selectpicker" name="tipo" required>
                                             <option value="" disabled selected>Sem classificação</option>
                                             <option>Ensaio</option>
                                             <option>Reunião</option>
                                             <option>Show</option>
                                             <option>Festival</option>
                                             <option>Evento</option>
                                             <option>Outros</option>
                                        </select><br>
                                      </fieldset>

                                      <label class="control-label" for="exampleInputEmail1">Valor:</label><br>
                                      <label>Obs.: Para inserir CUSTOS, informe o sinal negativo (-).</label>
                                      <input id="valor" name="valor" class="form-control" name="nometitulo"  
                                      type="text">
                                    <script>
                                      $(document).ready(function() {    
                                          $("#valor").maskMoney({allowZero:true, allowNegative:true, decimal:",",thousands:"."});
                                      });
                                    </script>

                                      <label class="control-label" for="exampleInputEmail1">Data/ Hora:</label>
                                      <input name="data" class="form-control" name="data" type="datetime-local" required>

                                      <label class="control-label" for="exampleInputEmail1">Endereço:</label>
                                      <input name="endereco" class="form-control" name="endereco" placeholder="Referencia do local onde ocorrerá a atividade."
                                      type="text" required>  
                                      <?php if($funcaoativa) { ?> 
                                       <fieldset>
                                       <br>
                                       <label class="control-label">Selecione a sua função nessa atividade</label>
                                        <select class="form-control selectpicker" data-size="7" name="funcao" required>

                  <?php foreach($funcaoativa as $f) { ?>
                      <option value="<?php echo $f['funcao_id']?>"><?php echo $f['funcao_nome']?></option>
                  <?php } ?>

                                          </select>
                                      </fieldset>  
                                       </div>
                                </div>
                                <div class="modal-footer">
                                  <button id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <script>
                                      $('#cancelar').click(function() {
                                          $('#formatividade')[0].reset();
                                          $('option:selected').removeAttr('selected');
                                      }); 
                                    </script> 
                                  <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                          </form>
                                </div>
                              </div>
                            </div>
                          </div>
                  <?php }else{?>
                            <br>
                                <div class="alert alert-danger">
                                  <center><h3>Atenção!</h3></center> <h5>Você não possui função ativa para poder continuar, por favor habilite ao menos uma função em seus dados.</h5>
                                </div>
                                 </div>
                                </div>
                                <div class="modal-footer">
                                  <button id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <script>
                                      $('#cancelar').click(function() {
                                          $('#formatividade')[0].reset();
                                          $('option:selected').removeAttr('selected');
                                      }); 
                                    </script> 
                                  <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-primary" disabled>Salvar</button>
                                </div>
                          </form>
                                </div>
                              </div>
                            </div>
                          </div>
                  <?php } ?>

<!-- MODAL PARA ADICIONAR BANDAS -->
                          <div class="modal fade" id="modalbanda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <center><h3 class="modal-title" id="myModalLabel">Cadastre a sua Banda:</h3></center>
                                </div>
                                  <div class="modal-body">
                                  <div class="form-group">
                          <form id="formbanda" role="form" method="POST" name="cadastrar" action="<?php echo base_url('banda/salvar'); ?>">
                            <input type="hidden" name="captcha">
                            <input type="hidden" name="pessoa_id" value="<?php echo $pessoa['pessoa_id'];?>">
                                <label class="control-label" for="exampleInputEmail1">Nome:</label>
                                <input name="nomebanda" class="form-control" placeholder="Digite aqui o nome da sua banda."
                                type="text" required>
                                <label class="control-label" for="exampleInputPassword1">Explicação:</label>
                                <textarea name="explicacaobanda" class="form-control" rows="3" placeholder="Regras a serem cumpridas e explicação da visão que a banda possui." required></textarea>
                               
                                <fieldset>
                                   <hr>
                                   <label class="control-label">Gênero/ Estilo da Banda</label>
                                    <select class="form-control selectpicker" data-size="7" multiple name="genero[]" required>
                            
                                      <?php foreach($generos as $f) { ?>
                                          <option value="<?php echo $f['genero_id']?>"><?php echo $f['genero_nome']?></option>
                                      <?php } ?>

                                      </select><br><br>
                                  </fieldset>
                                <div class="form-group">
                                  <label class="control-label" for="exampleInputPassword1">Telefone</label>
                                  <input type="tel" name="telefonebanda" id="txttelefone" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" placeholder="(xx) xxxx-xxxx" required/>
                                </div>
                                <script type="text/javascript">$("#txttelefone").mask("(00) 0000-00009");</script>
                                  <fieldset>
                                    <label class="control-label">Estado</label><br>
                                    <select class="form-control" id="estado1" name="estado" required></select><br>
                                    <label class="control-label">Cidade</label><br>
                                    <select class="form-control" id="cidade1" name="cidade" required></select> <br>
                                  </fieldset>
             
                                  <script language="JavaScript" type="text/javascript" charset="utf-8">
                                    new dgCidadesEstados({
                                      estado: document.getElementById('estado1'),
                                      cidade: document.getElementById('cidade1')
                                    })
                                  </script>
                                  
                                  <div class="form-group">
                                    <label class="control-label" for="exampleInputPassword1">Outros Contatos</label>
                                    <input class="form-control" name="contatobanda" placeholder="Digite aqui outros contatos, como email, site..."
                                    type="text">
                                  </div>
                        <hr>
                  <?php if($funcaoativa) { ?> 
                             <fieldset>
                             <br>
                             <label class="control-label">Selecione a sua função na Banda</label>
                              <select class="form-control selectpicker" data-size="7" name="funcaobanda" required>
                               <option value="" disabled selected>Selecione uma opção</option>
                        <?php foreach($funcaoativa as $f) { ?>
                            <option value="<?php echo $f['funcao_id']?>"><?php echo $f['funcao_nome']?></option>
                        <?php } ?>
                              </select>
                            </fieldset>  
                                <div class="modal-footer">
                                  <button id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <script>
                                      $('#cancelar').click(function() {
                                          $('#formatividade')[0].reset();
                                          $('option:selected').removeAttr('selected');
                                      }); 
                                    </script> 
                                  <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                  <?php }else{?>
                            <br>
                                <div class="alert alert-danger">
                                  <center><h3>Atenção!</h3></center> <h5>Você não possui função ativa para poder continuar, por favor habilite ao menos uma função em seus dados.</h5>
                                </div>
                                <div class="modal-footer">
                                  <button id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <script>
                                      $('#cancelar').click(function() {
                                          $('#formatividade')[0].reset();
                                          $('option:selected').removeAttr('selected');
                                      }); 
                                    </script> 
                                  <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-primary" disabled>Salvar</button>
                                </div>
                  <?php } ?>
                        </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>




<!-- ______________AQUI IRÃO SER MOSTRADOS AS ATIVIDADES DO USUARIO, ORDENADOS DE ACORDO COM A DATA DE EXECUÇÃO_______________________________-->
<?php if(@$atividades_aberto) {?>
  <link href="<?php echo base_url('assets/css/style.css')?>" rel="stylesheet" type="text/css">
    <div class="row">
      <div class="col-md-12 text-center" id="dashboard-col">
          <?php for($i=0;$i<count($atividades_aberto);$i++) { ?>
              <div class="jumbotron" id="lista-atividade">
                <blockquote>
                  <p><h3 id="semquebralinha"><?php echo $atividades_aberto[$i]['atividade_titulo']?></h3><p id="semquebralinha">  (<?php echo $atividades_aberto[$i]['atividade_tipo']?>)</p></p>
                  <footer><?php echo $atividades_aberto[$i]['atividade_especificacao']?></footer>
                </blockquote>
                <div class="row"><hr>
                 <div class="col-md-4">
                   <footer>Minha função:</footer><p><?php echo $atividades_aberto[$i]['funcao_nome']?></p>
                </div>
                <div class="col-md-4">
                   <footer>Data/ Horário:</footer><p><?php echo date('d/m/Y H:i:s', strtotime($atividades_aberto[$i]['atividade_data']));?></p>
                </div>
                <div class="col-md-4">
                   <footer>Local:</footer><p><?php echo $atividades_aberto[$i]['atividade_endereco']?></p>
                </div>
                </div>
                <div class="row">
                <?php if($atividades_aberto[$i]['funcao_administrador'] == '1') {?>
                  <div class="col-md-4">
                    <button id="editar<?php echo $i?>" data-toggle="modal" data-target="#modaleditaratividade<?php echo $i ?>" href="#"  type="button" class="btn-block btn-info"><h5>Editar</h5></button>
                  </div>
                   <div class="col-md-4">
                    <button data-toggle="modal" data-target="#cancelaratividade<?php echo $i ?>" type="button" class="btn-block btn-info"><h5>Cancelar</h5></button>
                  </div>
                  <div class="col-md-4">
                    <button id="informacoes<?php echo $i?>" data-toggle="modal" data-target="#modalinformacoesatividade<?php echo $i ?>" href="#" type="button" class="btn-block btn-info"><h5>Mais informações...</h5></button>
                  </div>
                <?php }else{ ?>
                   <div class="col-md-12">
                    <button id="informacoes<?php echo $i?>" data-toggle="modal" data-target="#modalinformacoesatividade<?php echo $i ?>" href="#" type="button" class="btn-block btn-info"><h5>Mais informações...</h5></button>
                  </div>
                    <?php } ?>
                 
                   
                </div><hr>
                <!-- Mostra qual o usuario criador da atividade -->           
                <?php if(!$atividades_aberto[$i]['funcao_administrador'] == '1') {?>
                    <?php foreach ($lista_integrantes[$i]['integrantes'] as $lista) { ?> 
                          <?php if($lista['funcao_administrador'] == '1') {?>
                              <footer>Essa atividade foi criado por  <a href="<?php echo base_url('pessoa/dados?pessoa_id=').$lista['pessoa_id'].'&nome='.$lista['pessoa_nome']?>" id="mao"><?php echo $lista['pessoa_nome']?></a></footer>
                          <?php  } ?>
                    <?php  } ?>
                <?php }?><br>
              </div>        
          <?php } ?>
      </div>
    </div>
  </div>

  <?php for($a=0;$a<count($atividades_aberto);$a++) { ?>
<!-- MODAL PARA EDITAR ATIVIDADE ______________________________________________________-->

                          <div class="modal fade bd-example-modal-lg" id="modaleditaratividade<?php echo $a ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Editar a atividade:</h4>
                                </div>
                                  <div class="modal-body">
                                  <div class="form-group">
                          <form id="editar" role="form" method="POST" name="editar" action="<?php echo base_url('atividade/editar'); ?>">
                            <input type="hidden" name="captcha">
                            <input type="hidden" name="pessoa_id" value="<?php echo $pessoa['pessoa_id'];?>">
                            <input type="hidden" name="atividade_id<?php echo $a ?>" id="atividade_id<?php echo $a ?>" value="<?php echo $atividades_aberto[$a]['atividade_id']?>">

                                      <label class="control-label" for="exampleInputEmail1">Título:</label>
                                      <input name="nometitulo<?php echo $a ?>" id="nometitulo" class="form-control" 
                                      type="text" required value="<?php echo $atividades_aberto[$a]['atividade_titulo']?>">
                                      <label class="control-label" for="exampleInputPassword1">Contrato:</label>
                                      <textarea name="contrato<?php echo $a ?>" id="contrato" class="form-control" rows="3" disabled="disabled"><?php echo $atividades_aberto[$a]['atividade_contrato']?></textarea>
                                      <label class="control-label" for="exampleInputPassword1">Explicação:</label>
                                      <textarea name="explicacao<?php echo $a ?>" id="explicacao" class="form-control" rows="3" required><?php echo $atividades_aberto[$a]['atividade_especificacao']?></textarea>
                                      
                                     

                                      <fieldset>
                                        <label class="control-label">Tipo</label><br>
                                        <select class="form-control selectpicker" id="tipo_editar<?php echo $a ?>" name="tipo<?php echo $a ?>" required>
                                             <option value="" disabled selected></option>
                                             <option value="Ensaio">Ensaio</option>
                                             <option value="Reunião">Reunião</option>
                                             <option value="Show">Show</option>
                                             <option value="Festival">Festival</option>
                                             <option value="Evento">Evento</option>
                                             <option value="Outros">Outros</option>
                                        </select><br>
                                      </fieldset>
                                      <script> 
                                      var values = "<?php echo $atividades_aberto[$a]['atividade_tipo']?>";
                                       $.each(values.split(","), function(i,e){
                                            $("#tipo_editar<?php echo $a ?> option[value='" + e + "']").prop("selected", true);
                                        }); 
                                      </script> 


                                      <label class="control-label" for="exampleInputEmail1">Valor:</label>
                                      <input id="valor2" name="valor<?php echo $a ?>" class="form-control"  
                                      type="text" value="<?php echo $atividades_aberto[$a]['atividade_valor']?>">
                                     <script>
                                      $(document).ready(function() {    
                                          $("#valor2").maskMoney({allowZero:true, allowNegative:true, decimal:",",thousands:"."});
                                      });
                                    </script>

                                      <label class="control-label" for="exampleInputEmail1">Data/ Hora:</label>
                                      <input name="data<?php echo $a ?>" id="datahora" class="form-control" type="datetime-local" value="<?php echo substr($atividades_aberto[$a]['atividade_data'],0,10)?>T<?php echo substr($atividades_aberto[$a]['atividade_data'],11,8)?>" required>

                                      <label class="control-label" for="exampleInputEmail1">Endereço:</label>
                                      <input name="endereco<?php echo $a ?>" id="endereco" class="form-control" 
                                      type="text" value="<?php echo $atividades_aberto[$a]['atividade_endereco'] ?>" required> 
                                      <br>
                                      <!--<label class="control-label" for="exampleInputEmail1">Sua função na atividade:</label>
                                      <input name="funcao2" id="funcao" class="form-control" type="text" disabled="disable"> -->
                                       </div>
                                </div>
                                <div class="modal-footer">
                                  <button id="cancelar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <script>
                                      $('#cancelar').click(function() {
                                          $('#formatividade')[0].reset();
                                          $('option:selected').removeAttr('selected');
                                      }); 
                                    </script> 
                                  <button name="editar" value="editar<?php echo $a ?>" type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                          </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

      <!-- MODAL PARA MOSTRAR AS INFORMACOES DA ATIVIDADE _______________________________________________________-->

                    <div class="modal fade bd-example-modal-lg" id="modalinformacoesatividade<?php echo $a ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><center><?php echo $atividades_aberto[$a]['atividade_titulo']?></center></h4>
                          </div>
                          <div class="modal-body">
                          <div class="form-group">
                          <table class="table table-striped">
                          <tbody>
                           <tr>
                             <th scope="row">Contrato:</th>
                             <td><?php echo $atividades_aberto[$a]['atividade_contrato'] ?></td>
                           </tr>
                           <tr>
                              <th scope="row">Explicação:</th>
                              <td><?php echo $atividades_aberto[$a]['atividade_especificacao'] ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Data:</th>
                              <td><?php echo date('d/m/Y', strtotime($atividades_aberto[$a]['atividade_data'])); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Horário:</th>
                              <td><?php echo date('H:i:s', strtotime($atividades_aberto[$a]['atividade_data'])); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Endereço:</th>
                              <td><?php echo $atividades_aberto[$a]['atividade_endereco']?> </td>
                            </tr>
                        <?php if($atividades_aberto[$a]['funcao_administrador'] == '1') { ?>
                            <tr>
                              <th scope="row">Valor:</th>
                               <td>R$<?php echo $atividades_aberto[$a]['atividade_valor'] ?></td>
                            </tr>
                        <?php } ?>
                            <tr>
                              <th scope="row">Tipo:</th>
                               <td><?php echo $atividades_aberto[$a]['atividade_tipo'] ?></td>
                            </tr>
                            
                            <hr>
                            <tr>
                              <th scope="row">Sua função:</th>
                               <td><?php echo $atividades_aberto[$a]['funcao_nome'] ?></td>
                            </tr><hr>

                             <!-- Aqui serão inseridos os nomes de cada integrante da atividade, com a sua respectiva função -->
                             <tr>
                              <th scope="row">Participantes:</th>
                              <td>
                                <?php foreach ($lista_integrantes[$a]['integrantes'] as $lista) { ?>   
                                        <a href="<?php echo base_url('pessoa/dados?pessoa_id=').$lista['pessoa_id'].'&nome='.$lista['pessoa_nome']?>" id="mao"><?php echo $lista['pessoa_nome']?></a> atuando como <?php echo $lista['funcao_nome']; ?><br> 
                                <?php  } ?>
                                  
                                  <?php if(@$lista_integrantes_bandas[$a][0]['banda_nome']){?>
                                     <?php foreach ($lista_integrantes_bandas[$a] as $lista) { ?> 
                                    <p id="semquebralinha">Com a participação da banda </p><a id="semquebralinha" href="<?php echo base_url('banda/dados?banda=').$lista['banda_id'].'&pessoa='.$pessoa['pessoa_id']?>" id="mao"><h4 id="semquebralinha"><?php echo $lista['banda_nome']?></h4></a><br> 
                                      <?php  } ?>
                                  <?php }?>
                               
                              </td>
                            </tr>

                            </tbody>
                            </table><br>
                          </div>
                          </div>
                        </div>
                      </div>
                    </div>

<!--___________________________________ MODAL PARA CANCELAR ATIVIDADE -->
                          <div class="modal fade" id="cancelaratividade<?php echo $a ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  
                                  <center><h4 id="myModalLabel">Tem certeza que deseja CANCELAR a atividade "<?php echo $atividades_aberto[$a]['atividade_titulo']?>"?</h4><center>
                                  <p>IMPORTANTE: Essa atividade será cancelada também para todos os participantes.</p>
                                </div>
                            
                                <div class="modal-footer">
                                <div class="col-md-6">
                                  <center><button data-dismiss="modal" type="button" class="btn btn-default btn-block">Não</button></center>
                                </div>
                                <div class="col-md-6">
                                  <center><button id="cancelarativi<?php echo $a?>" type="button" class="btn btn-block btn-info">Sim</button></center>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <script>
                            $('#cancelarativi<?php echo $a?>').click(function() {
                                var dados = {
                                  pessoa : "<?php echo $atividades_aberto[$a]['pessoa_id'] ?>",
                                  atividade : "<?php echo $atividades_aberto[$a]['atividade_id'] ?>",
                                  funcao : "<?php echo $atividades_aberto[$a]['funcoes_funcao_id'] ?>"
                                };

                                $.ajax({            
                                    type: "POST",
                                    data: { dados: JSON.stringify(dados)},
                                    datatype: 'json',
                                    url: "<?php echo site_url('atividade/cancelar'); ?>",      
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


  <?php } ?>
<?php } ?>



<!-- ______________AQUI IRÃO SER MOSTRADOS TODAS AS BANDAS QUE O USUARIO PARTICIPA ATUALMENTE_______________________________-->
  
    <?php if(@$bandas_participo){?>
      <br><br><div class="col-md-12">
        <h3 class="text-center">Minha Banda</h3>
        <p class="text-center">Logo abaixo estão todas as bandas em que você participa atualmente:</p><hr>
        </div>

        
      <?php $w=-1;?>
      <?php while($w<count($bandas_participo)){?>
          <div class="section">
            <div class="container">   
              <div class="row" id="espaco_cima">
                <?php $w++;?>
                  <?php if(@$bandas_participo[$w]['banda_id']){?>
                      <div class="col-md-2">
                        <img src="<?php echo $bandas_participo[$w]['banda_foto']?>"
                        class="img-circle img-responsive img-thumbnail circular">
                      </div>
                      <div class="col-md-2">
                        <a href="<?php echo base_url('banda/dados?banda=').$bandas_participo[$w]['banda_id'].'&pessoa='.$bandas_participo[$w]['Pessoas_Funcoes_Pessoas_pessoa_id']?>" id="mao"><h4 class="text-left"><?php echo $bandas_participo[$w]['banda_nome']?></h4></a>
                        <label class="text-left"><?php echo $bandas_participo[$w]['banda_cidade']?>/ <?php echo $bandas_participo[$w]['banda_estado']?></label>
                      </div>
                  <?php }else{?>
                    <div class="col-md-4"></div>
                    <?php }?>

                  <?php $w++;?>
                  <?php if(@$bandas_participo[$w]['banda_id']){?>
                      <div class="col-md-2">
                        <img src="<?php echo $bandas_participo[$w]['banda_foto']?>"
                        class="img-circle img-responsive img-thumbnail circular">
                      </div>
                      <div class="col-md-2">
                        <a href="<?php echo base_url('banda/dados?banda=').$bandas_participo[$w]['banda_id'].'&pessoa='.$bandas_participo[$w]['Pessoas_Funcoes_Pessoas_pessoa_id']?>" id="mao"><h4 class="text-left"><?php echo $bandas_participo[$w]['banda_nome']?></h4></a>
                        <label class="text-left"><?php echo $bandas_participo[$w]['banda_cidade']?>/ <?php echo $bandas_participo[$w]['banda_estado']?></label>
                      </div>
                  <?php }else{?>
                    <div class="col-md-4"></div>
                    <?php }?>

                  <?php $w++;?>
                  <?php if(@$bandas_participo[$w]['banda_id']){?>
                      <div class="col-md-2">
                        <img src="<?php echo $bandas_participo[$w]['banda_foto']?>"
                        class="img-circle img-responsive img-thumbnail circular">
                      </div>
                      <div class="col-md-2">
                        <a href="<?php echo base_url('banda/dados?banda=').$bandas_participo[$w]['banda_id'].'&pessoa='.$bandas_participo[$w]['Pessoas_Funcoes_Pessoas_pessoa_id']?>" id="mao"><h4 class="text-left"><?php echo $bandas_participo[$w]['banda_nome']?></h4></a>
                        <label class="text-left"><?php echo $bandas_participo[$w]['banda_cidade']?>/ <?php echo $bandas_participo[$w]['banda_estado']?></label>
                      </div>
                  <?php }else{?>
                    <div class="col-md-4"></div>
                    <?php }?> 
                </div>
             </div>
        </div>
        
    <?php }?>
  <?php }?>
      

