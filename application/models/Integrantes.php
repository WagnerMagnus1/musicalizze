<?php class Integrantes extends CI_Model
{
	public function cadastrar($dados)
	{
		$this->db->insert('integrantes',$dados);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function inserir_integrante_banda($dados)
	{
		$this->db->insert('integrantes_bandas',$dados);
		$insert_id = $this->db->insert_id();
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function deletar_integrante($id)
	{
		$this->db->where('integrante_id', $id);
		$this->db->delete('Integrantes');
	}

	public function get_pessoa_integrante($pessoa_id)
	{
		$this->db->from('Integrante');
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}
	//Verifica se o integrante e pessoa existem
	public function get_integrante_banda($banda, $integrante)
	{
		$this->db->from('Integrantes_bandas');
		$this->db->where('Bandas_banda_id', $banda);
		$this->db->where('Integrantes_integrante_id', $integrante);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}

	//Busca as bandas que o usuario participa atualmente, juntamente com as informações como Nome da banda, localização e foto
	public function get_pessoa_bandas_ativo($pessoa_id)
	{
		$this->db->select('integrante_id,banda_id,administrador,banda_nome,banda_foto,banda_estado,banda_cidade');
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_bandas','Integrantes.integrante_id = Integrantes_bandas.Integrantes_integrante_id');
		$this->db->join('Bandas', 'Integrantes_bandas.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa_id));
		$this->db->where(array('Integrantes_bandas.integrante_status' => '5'));

		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}

	//Busca simples sobre os integrantes da banda, juntamente com as suas respectivas funções
	public function get_integrantes_banda_generos($banda_id)
	{
		$this->db->select('pessoa_nome, pessoa_id, funcao_id, funcao_nome, integrante_id');
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_bandas','Integrantes.integrante_id = Integrantes_bandas.Integrantes_integrante_id');
		$this->db->join('Pessoas','Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id = Pessoas.pessoa_id');
		$this->db->join('Funcoes','Funcoes.funcao_id = Integrantes.Pessoas_Funcoes_Funcoes_funcao_id');
		$this->db->where(array('Integrantes_bandas.Bandas_banda_id' => $banda_id));
		$this->db->where(array('Integrantes_bandas.integrante_status' => '5'));

		$dados = $this->db->get();

		if($dados->num_rows())
		{	
			return $dados->result_array();
		}else{
			return false;
		}
	}

	//Verifica se a pessoa é administrador da banda
	public function get_administrador_banda_pessoa($banda, $pessoa)
	{
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_bandas','Integrantes.integrante_id = Integrantes_bandas.Integrantes_integrante_id');
		$this->db->where('Bandas_banda_id', $banda);
		$this->db->where('integrante_id', $pessoa);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}
}
