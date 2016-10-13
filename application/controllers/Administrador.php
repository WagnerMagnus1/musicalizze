<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Administrador extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}											
	}
	public function cadastrarFuncao()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Funcoes');

		$this->Funcoes->cadastrar_funcao($dados);
		$funcoes = $this->Funcoes->get_funcoes();
		echo $funcoes;
	}

	public function cadastrarGenero()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Generos');

		$this->Generos->cadastrar_genero($dados);
		$generos = $this->Generos->get_generos();
		echo $generos;
	}
}
