<?php class Integrantes extends CI_Model
{

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
}