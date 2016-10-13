<?php class Generos extends CI_Model
{
	public function get_generos()
	{
		$query = $this->db->get('generos');

		if($query->num_rows())
		{
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function cadastrar_genero($nome, $especificacao)
	{
		$this->db->insert('generos',$nome, $especificacao);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}
}