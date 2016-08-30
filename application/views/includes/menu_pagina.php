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
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="#">Meu perfil</a>
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