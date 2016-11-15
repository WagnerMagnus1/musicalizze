<?php class Facebooks extends CI_Model
{
	public function check_login($id)
	{
		$this->db->from('Users_Facebook');
		$this->db->where('facebook_id', $id);
		$usuarios = $this->db->get();
		if($usuarios->num_rows())
		{
			$usuarios = $usuarios->result_array();
			return $usuarios[0];
		}else{
			return false;
		}
	}

	public function cadastrar_usuario($dados_usuario)
	{

		$this->db->insert('Users_Facebook',$dados_usuario);
					
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function get_usuario_email($user_id)
	{
		$this->db->where('facebook_id', $user_id);

		$usuario = $this->db->get('Users_Facebook');

		if($usuario->num_rows())
		{	
			return $usuario->row_array();
		}else{
			return false;
		}
	}
}