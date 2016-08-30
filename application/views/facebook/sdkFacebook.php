<script type="text/javascript" src="../fbapp/fb.js"></script>

  <script type="text/javascript">
    function conectarMusicalizze() 
     {
	      FB.api('/me', {fields: 'id,name,email,birthday'}, function(response) 
	      {  
			var dados = {id :response.id, nome:response.name, email:response.email}; 

	               $.ajax({
			            type: "POST",
			            data: {dados: JSON.stringify(dados)},
			            datatype: 'json',
			            url: "<?php echo site_url('facebook/index'); ?>",		          
			            success: function(result){
			                window.location.replace("<?php base_url('pagina/index');?>");
			            },
			            error: function(e){
			                console.log(e.message);
			            }
			        });
		  });
     };
  </script>

<button id="facebooklogin" class="btn btn-block btn-social btn-facebook" type="button" scope="public_profile,email" onclick="facebookLogin();"><span class="fa fa-facebook"></span>Fazer login com Facebook</button>

<span id="result1"></span>
<br> 
