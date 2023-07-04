<?
	
	class ControllerYaMarketData extends Controller {
		private $fileRemote 	= 'https://download.cdn.yandex.net/market/market_categories.xls';
		private $fileLocal 		= '';
		private $removePrefix 	= 'Все товары/';
		
		public function __construct($registry){
			parent::__construct($registry);
			$this->fileLocal = DIR_CACHE . 'market_categories.xls';
			
			$this->clearTable();
		}
		
		private function parseYandexCategory($name){
			
			return explode('/', $name);
			
		}
		
		private function getYandexCategoryName($line){
			$tmp = $this->parseYandexCategory($line);
			
			return array_pop($tmp);
			
		}
		
		private function findCategory($name){
			
			$query = $this->db->ncquery("SELECT * FROM category_yam_tree WHERE name LIKE '" . $this->db->escape($name) . "'");
			
			if ($query->num_rows){
				return $query->row['category_id'];
				} else {
				return false;
			}
			
		}
		
		private function findCategoryByFullName($full_name){
			
			$query = $this->db->ncquery("SELECT * FROM category_yam_tree WHERE full_name LIKE '" . $this->db->escape($full_name) . "'");
			
			if ($query->num_rows){
				return $query->row['category_id'];
				} else {
				return false;
			}
			
		}
		
		private function createCategory($name, $full_name){			
			$query = $this->db->ncquery("SELECT MAX(category_id) as last_category_id FROM category_yam_tree WHERE 1");			
			
			$last_category_id = 0;
			if ($query->num_rows && $query->row['last_category_id']){
				$last_category_id = $query->row['last_category_id'];
			}
			
			$this->db->ncquery("INSERT INTO category_yam_tree SET category_id = '" . (int)($last_category_id + 1) . "', name = '" . $this->db->escape($name) . "', full_name = '" . $this->db->escape($full_name) . "'");
			
			return ($last_category_id + 1);
		}
		
		private function updateParent($category_id, $name, $full_name){
			$parent_name = mb_substr($full_name, 0, (mb_strlen($full_name) - (mb_strlen($name) + 1)));
			
			echoLine('Категория: ' . $name);
			echoLine('Ищем родителя: ' . $parent_name);
			
			if ($parent_id = $this->findCategoryByFullName($parent_name)){
				$this->db->query("UPDATE category_yam_tree SET parent_id = '" . $parent_id . "' WHERE category_id = '" . $category_id . "'");
			}
			
		}
		
		private function updateFinalCategories(){
			$query = $this->db->query("SELECT DISTINCT(yam2.parent_id) as parent_id FROM category_yam_tree yam2");
			
			$parentIDList = [];
			foreach ($query->rows as $row){
				$parentIDList[] = $row['parent_id'];
			}
			
			$this->db->query("UPDATE category_yam_tree yam1 SET final_category = 1 WHERE category_id NOT IN (" . implode(',', $parentIDList) . ")");
			
		}
		
		private function clearTable(){
			
			$this->db->query("TRUNCATE category_yam_tree");
			
		}
		
		public function parsecategoriescron(){
			
			echoLine('[YAMCATEGORY] загружаем файлик', 's');
			
			$file = file_get_contents($this->fileRemote);
			file_put_contents($this->fileLocal, $file);
			
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->fileLocal);
			$reader->setReadDataOnly(true);
			$spreadsheet  = $reader->load($this->fileLocal);
			$worksheet = $spreadsheet->getActiveSheet();
			
			foreach ($worksheet->getRowIterator() as $row) {			
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(true);
				foreach ($cellIterator as $cell) {					
					echoLine('Категория: ' . $cell->getValue());
					
					$category_id = $this->createCategory($this->getYandexCategoryName($cell->getValue()), $cell->getValue());					
					$this->updateParent($category_id, $this->getYandexCategoryName($cell->getValue()), $cell->getValue());
					
				}			
			}
			
			$this->updateFinalCategories();
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}				