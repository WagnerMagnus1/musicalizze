<!-- Header da Página-->
<?php $this->load->view('includes/header')?>
	<body>
	<script>
	  $('body').show();
	  $('.version').text(NProgress.version);
	  NProgress.start();
	  setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 1000);
	</script>
	  <!-- Menu da Página-->
	  <?php $this->load->view($view_menu)?>
		<div class="cover">
		 <div class="container">
		  <div class="row">

			<!-- Corpo da Página-->
			<?php $this->load->view($view)?>

	      </div>
	    </div>
	  </div>

		<!-- Carregando os Modals-->
		<?php $this->load->view('includes/modal');?> 

		<!-- Footer da Página-->
		<?php $this->load->view('includes/footer')?>

	</body>
</html>
<!-- Java Script-->

<?php $this->load->view('includes/javascript')?>  
		    