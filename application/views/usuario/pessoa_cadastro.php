<div class="col-md-12">
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center" id="dashboard-col">
                    <h2 class="text-center">Bem Vindo ao Muzicalizze</h2>
                    <?php if(@$alerta){?>
                    <p class="lead text-danger"><?php echo $alerta?></p>  
                    <?php }else{?> 
                    <p class="lead text-danger">Por favor, finalize seu cadastro preenchendo os dados abaixo:</p>  
                    <?php }?>        
                    <div class="col-md-12 text-justify">
                      <hr>
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
              </div>
            </div>
          <!--<div class="row">
            <div id="imgperfil" data-toggle="context" data-target="#context-menu" class="col-md-4">

                <img id="uploadPreview" src="http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png" class="img-responsive img-thumbnail"><br>

                <div id="gridbotaofoto">
                  <button id="photoadd" name="adicionarphoto" value="adicionar" type="submit" class="btn-info">Adicionar Foto</button>
                  <button id="editarphoto" name="editarphoto" data-toggle="modal" data-target="#myModal" value="editar" type="submit" class="btn-info" disabled>Editar/ Posicionar</button>
                  <button id="excluirphoto" name="excluirphoto" value="excluir" type="submit" class="btn-info" disabled>Excluir</button>
                </div>

                <div id="context-menu">
                 <ul class="dropdown-menu" role="menu">
                    <li><a>Buscar Foto</a></li>
                    <li><a>Editar/ Posicionar Foto</a></li>
                    <li><a>Excluir Foto</a></li>
                  </ul>
                </div>

                <!-- ELEMENTO INPUT INVISIVEL-->
                <input class="btn-block" id="input-1" type="file" name="myPhoto" onchange="PreviewImage();" /> 
                <!-- CARREGA A FOTO SELECIONADA PELO USUARIO E MOSTRA NA TELA-->
                <script>
                    function PreviewImage() { 

                        var oFReader = new FileReader(); 
                        oFReader.readAsDataURL(document.getElementById("input-1").files[0]);

                        oFReader.onload = function (oFREvent) { 
                            document.getElementById("uploadPreview").src = oFREvent.target.result; 
                            document.getElementById("imgperfilmodal").src = oFREvent.target.result;
                            document.getElementById("perfil").value = oFREvent.target.result;
                        $("#editarphoto").prop("disabled", false);
                        $("#excluirphoto").prop("disabled", false);

                        //FUNÇÃO DO JCROP PARA CORTAR A FOTO
                                $(function(){       
                                      $('#imgperfilmodal').Jcrop({
                                          aspectRatio: 1,
                                          onSelect: updateCoords
                                      });
                                });
                                function updateCoords(c){
                                    $('#x').val(c.x);
                                    $('#y').val(c.y);
                                    $('#w').val(c.w);
                                    $('#h').val(c.h);
                                };
                                function checkCoords(){
                                    if (parseInt($('#w').val())) return true;
                                    alert('Selecione a região para recortar.');
                                    return false;
                                }; 
                                };
                                };
                                $('.context').contextmenu();
                                $('#photoadd').click(function() {
                                  $('input[name=myPhoto]').click();
                                });
                                $('#excluirphoto').click(function() {
                                  document.getElementById("uploadPreview").src = "<?php echo base_url('public/imagens/perfil/perfil.png')?>"; 
                                  document.getElementById("imgperfilmodal").src = "<?php echo base_url('public/imagens/perfil/perfil.png')?>";
                                  $("#editarphoto").prop("disabled", true);
                                  $("#excluirphoto").prop("disabled", true);
                                });
                </script>

            </div> 
              <div class="col-md-12 text-left">

                    <form role="form" method="POST" name="cadastrar" action="<?php echo base_url('pessoa/cadastrar'); ?>">
                      <input type="hidden" name="captcha">
                      <input type="hidden" name="id_pessoa" value="<?php echo $_SESSION['id'];?>">
                     
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1">Nome</label>
                        <input class="form-control" name="nome" placeholder="Nome"
                        type="text" required>
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputEmail1">Sobrenome</label>
                        <input class="form-control" name="sobrenome" placeholder="Sobrenome"
                        type="text">
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Data de Nascimento</label>
                        <input class="form-control" name="nascimento" type="date" 
                        type="text" required>
                      </div>
                      <fieldset>
                        <label class="control-label">Sexo</label><br>
                        <select class="form-control selectpicker" name="sexo" id="genero" required>
                             <option value="" disabled selected hidden>Selecione o seu gênero</option>
                             <option>Masculino</option>
                             <option>Feminino</option>
                        </select><br>
                      </fieldset>

                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Telefone</label>
                        <input type="tel" name="txttelefone" id="txttelefone" pattern="\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}" class="form-control" placeholder="(xx) xxxx-xxxx" />
                      </div>
                      <script type="text/javascript">$("#txttelefone").mask("(00) 0000-00009");</script>
                      
                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Endereço</label>
                        <input class="form-control" name="endereco" placeholder="Ex.: Rua Luis Gonzala, 70"
                        type="text">
                      </div>

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
                        <input class="form-control" name="contato" placeholder="Digite aqui seus outros contatos, como emails, fax..."
                        type="text">
                      </div>

                      <div class="form-group">
                        <label class="control-label" for="exampleInputPassword1">Observação</label>
                        <textarea name="observacao" class="form-control" rows="3"></textarea>
                      </div>

                       <fieldset>
                       <hr>
                       <label class="control-label">Habilidades/ Funções</label>
                        <select class="form-control selectpicker" data-size="6" multiple name="funcao[]" required>
                
                          <?php foreach($funcao as $f) { ?>
                              <option value="<?php echo $f['funcao_id']?>"><?php echo $f['funcao_nome']?></option>
                          <?php } ?>

                          </select><br><br>
                          
                      
                      </fieldset>
                      <label id="semquebralinha" class="control-label">Caso não encontre sua função acima, encaminhe um pedido de cadastro para nós através do link abaixo, informando o nome da sua função e breve explicação.</label><a href="<?php echo base_url('administrador/formulario')?>"><h5 id="mao" id="semquebralinha">Solicitar nova função aqui</h5></a><br><hr>
                    <!-- ////////////////////////////////////////////////////////// -->

                      <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-block btn-info btn-lg">Salvar</button>
                      <button id="cancelar" type="button" class="btn btn-block btn-lg">Cancelar Alterações</button>
                    </form>
                    <script>
                      $('#cancelar').click(function() {
                        document.cadastrar.reset();
                      });
                    </script>
                  </div>
                </div>
              </div>
            </div>
            <div class="section">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-justify">
                    <hr>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel" class="modal-title">Sua foto de Perfil</h3>
            </div>
            <div class="modal-body">
               <div class="row">
                    <div id="divpreviewmodal" data-toggle="context" data-target="#context-menu" class="col-md-12">
                      <img id="imgperfilmodal" src="<?php echo base_url('public/imagens/perfil/perfil.png')?>" class="img-responsive img-thumbnail"><br>
                    </div>                   
                </div>
            </div>
            <div class="modal-footer">
            <!-- Formulário para realização do JCROP da imagem-->
              <form onsubmit="return checkCoords();">
                  <input type="hidden" id="x" name="x" />
                  <input type="hidden" id="y" name="y" />
                  <input type="hidden" id="w" name="w" />
                  <input type="hidden" id="h" name="h" />
              </form>
              <button id="salvarcrop" class="btn btn-info btn-lg">Salvar</button>
              <button id="cortar" class="btn btn-info btn-lg">Cortar</button>
              <button  class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            </div>
            </div>
          </div>

          <script>
          $('#salvarcrop').click(function (){
            var dados = {
              img : document.getElementById("imgperfilmodal").src.toString(),
              x : $('#x').val(),
              y : $('#y').val(),
              w : $('#w').val(),
              h : $('#h').val()
            };
            alert(dados['img']);
            alert(dados['x']);
            alert(dados['y']);
            alert(dados['w']);
            alert(dados['h']);
            var a;
                  $.ajax({            
                      type: "POST",
                      data: { dados: JSON.stringify(dados)},
                      datatype: 'json',
                      url: "<?php echo site_url('pessoa/crop'); ?>",      
                      success: function(data){     
                         document.getElementById("uploadPreview").src = '<?php base_url('public/imagens/perfil/wagner.png');?>';
                      },
                      error: function(e){
                        alert('aki2');
                          console.log(e.message);
                      }
                  }); 
          });


          $('#cortar').click(function (){
            var dados = {
              img : document.getElementById("imgperfilmodal").src.toString(),
              x : $('#x').val(),
              y : $('#y').val(),
              w : $('#w').val(),
              h : $('#h').val()
            }; 
          });
          </script>


