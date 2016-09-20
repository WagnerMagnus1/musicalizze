<?php class Funcoes extends CI_Model
{
	public function get_funcoes()
	{
		$query = $this->db->get('funcoes');

		if($query->num_rows())
		{
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function cadastrar_funcao($nome, $especificacao, $id)
	{
		$this->db->insert('funcoes',$nome, $especificacao,$id);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}
}