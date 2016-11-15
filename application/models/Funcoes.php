<?php class Funcoes extends CI_Model
{
	public function get_funcoes()
	{
		$query = $this->db->get('Funcoes');

		if($query->num_rows())
		{
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function get_funcao($id_funcao)
	{
		$this->db->from('Funcoes');
		$this->db->where('funcao_id', $id_funcao);

		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->row_array();
		}else{
			return false;
		}
	}

	public function cadastrar_funcao($nome, $especificacao)
	{
		$this->db->insert('Funcoes',$nome, $especificacao);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}
}