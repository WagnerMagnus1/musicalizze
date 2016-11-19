<script type="text/javascript" src="<?php echo base_url('fbapp/fb.js')?>"></script>

  <script type="text/javascript"> 
    function conectarMusicalizze()  
     {
	      FB.login(function(){
	      	  FB.api('me?fields=id,name,email,picture', function(response) 
		      {  
				var dados = {id :response.id, nome:response.name, email:response.email, perfil: response.picture.data.url}; 
		               $.ajax({
				            type: "POST",
				            data: {dados: JSON.stringify(dados)},
				            datatype: 'json',
				            url: "<?php echo site_url('facebook/index'); ?>",		          
				            success: function(result){
				            	NProgress.done();
				                window.location.replace("<?php base_url('pagina/index');?>");
				            },
				            error: function(e){
				            	alert('Não foi possivel logar com o seu facebook, por favor verifique sua conta.');
				                console.log(e.message);
				            }
				        });
			  });
	      },{scope: 'public_profile,email'});
     };
  </script>

<button id="facebooklogin" class="btn btn-block btn-social btn-facebook" type="button" scope="public_profile,email" onclick="facebookLogin();"><span class="fa fa-facebook"></span>Fazer login com Facebook</button>

<span id="result1"></span>
<br> 
