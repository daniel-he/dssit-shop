<?php
class ModelLocalisationBuilding extends Model {
	public function getBuilding($building_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "building WHERE building_id = '" . (int)$building_id . "' AND status = '1'");
		
		return $query->row;
	}	
	
	public function getBuildings() {
		$building_data = $this->cache->get('building.status');
		
		if (!$building_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "building WHERE status = '1' ORDER BY name ASC");
	
			$building_data = $query->rows;
		
			$this->cache->set('building.status', $building_data);
		}

		return $building_data;
	}
}
?>