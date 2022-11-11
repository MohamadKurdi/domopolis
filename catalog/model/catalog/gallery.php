<?php 
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ModelCatalogGallery extends Model {
	public function getAlbum($album_id) {		
		$query = $this->db->query("SELECT * FROM albums WHERE `album_id` = '".(int)$album_id."' ORDER BY `sort_order`");
		if (!empty($query->row)) {
			$query->row['album_data'] = json_decode($query->row['album_data'], true);
		}
		return $query->row;
	}
	public function getAlbums(){
		$query = $this->db->query("SELECT * FROM albums WHERE `enabled` = '1' ORDER BY `sort_order`");
		foreach ($query->rows as $key => $value) {
			$query->rows[$key]['album_data'] = json_decode($value['album_data'], true);
		}
		return $query->rows;
	}

}
?>