<?
class ModelKpContent extends Model {

	public function addContent($data){

		if ($this->user->isLogged() && $this->user->getCountContent()){

			$this->db->query("INSERT INTO user_content SET 
				user_id 	= '" . (int)$this->user->getID() . "',
				datetime 	= NOW(),
				date 		= NOW(),
				action    	= '" . $this->db->escape($data['action']) . "',
				entity_type = '" . $this->db->escape($data['entity_type']) . "',
				entity_id 	= '" . (int)$data['entity_id'] . "'
				");

		}
	}
}
