<?php class Generos extends CI_Model
{
	public function get_generos()
	{
		$query = $this->db->get('Generos');

		if($query->num_rows())
		{
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function cadastrar_genero($nome, $especificacao)
	{
		$this->db->insert('Generos',$nome, $especificacao);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}
}