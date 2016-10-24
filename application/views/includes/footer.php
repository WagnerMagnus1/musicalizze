<br><br><br>
    <footer class="section section-primary">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <h2>www.musicalizze.com.br</h2>
            <p>Um sistema web feito para administrar as atividades e integrantes de sua
              banda, localizar músicos ou bandas disponiveis e organizar eventos.</p>
          </div>
          <div class="col-sm-6">
            <p class="text-info text-right">
              <br>
              <br>
            </p><br>
            <?php if($this->session->userdata('logado')){?>
             <div class="row">
              <div class="col-md-12 text-right">
                <p id="semquebralinha"><a class="text-branco" href="<?php echo base_url('administrador/formulario')?>">Envie sua dúvida ou sugestão para nós <h3 id="semquebralinha"><i class="glyphicon glyphicon-comment"></i></h3></a></p>
              </div>
            <!--<div class="row">
              <div class="col-md-12 hidden-lg hidden-md hidden-sm text-left">
                <a href="#"><i class="fa fa-3x fa-fw fa-instagram text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-twitter text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-facebook text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-github text-inverse"></i></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 hidden-xs text-right">
                <a href="#"><i class="fa fa-3x fa-fw fa-instagram text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-twitter text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-facebook text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-github text-inverse"></i></a>
              </div>-->
            </div>
            <?php }?>
          </div>
        </div>
      </div>
    </footer>