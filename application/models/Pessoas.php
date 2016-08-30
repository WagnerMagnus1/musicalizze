<?php class Pessoas extends CI_Model
{
	public function get_pessoa($id_pessoa)
	{
		$this->db->where('pessoa_id', $id_pessoa);

		$pessoa = $this->db->get('pessoas');

		if($pessoa->num_rows())
		{	
			return $pessoa->row_array();
		}else{
			return false;
		}

		if($pessoa->num_rows)
		{
			return $pessoa->row_array();
		}else{
			return false;
		}
	}
}