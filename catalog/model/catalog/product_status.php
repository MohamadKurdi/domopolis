<?php

class ModelCatalogProductStatus extends Model
{
    public function getProductStatuses($product_id)
    {   

        if (!$this->config->get('config_additional_html_status_enable')){
            return [];
        }     

        $sql = "SELECT ps.product_show, ps.category_show, ps.sort_order, ps.product_id, s.* FROM product_status ps LEFT JOIN status s ON ps.status_id = s.status_id WHERE s.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ps.product_id = '" . (int)$product_id . "' ORDER BY ps.sort_order ASC";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getHTMLProductStatuses($product_id)
    {

         if (!$this->config->get('config_additional_html_status_enable')){
            return [];
        } 
        
        $a2f46db88ca4254088096fe3f37f1274b = $this->getProductStatuses($product_id);
        $a776f5c4135b037603b74c97cdbebba18 = $this->config->get('product_status_options');
        $ac333fbb97cbe8625a23ebcde093be86e = array('product' => '', 'category' => '');
        foreach ($a2f46db88ca4254088096fe3f37f1274b as $a7af59dbff38171e395fda52dbb3028f3) {
            foreach ($ac333fbb97cbe8625a23ebcde093be86e as $a06dee0aab9da3a856736a38f2d30ee3f => $a5b0c35e690110e5c52d65df716846bdd) {
                if ($a7af59dbff38171e395fda52dbb3028f3[$a06dee0aab9da3a856736a38f2d30ee3f . '_show']) {
                    $ac2b58574c9be213976ad15f8a14bed73 = $this->getThumb($a7af59dbff38171e395fda52dbb3028f3['image'], $a06dee0aab9da3a856736a38f2d30ee3f);
                    if ($a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['name_display'] == 'tip') {
                        $a6874f7c2401a9d59a2fbeac64a17e7a8 = $a7af59dbff38171e395fda52dbb3028f3['name'];
                    } else {
                        $a6874f7c2401a9d59a2fbeac64a17e7a8 = '';
                    }
                    $a7a33f61f16f67c1be945e4ea4b01c484 = "<img src=" . $ac2b58574c9be213976ad15f8a14bed73 . " title='" . $a6874f7c2401a9d59a2fbeac64a17e7a8 . "'/>";
                    if ($a7af59dbff38171e395fda52dbb3028f3['url']) {
                        $a7a33f61f16f67c1be945e4ea4b01c484 = "<a href=" . $a7af59dbff38171e395fda52dbb3028f3['url'] . ">" . $a7a33f61f16f67c1be945e4ea4b01c484 . "</a>";
                    }
                    if ($a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['name_display'] == 'next') {
                        if ($a7af59dbff38171e395fda52dbb3028f3['url']) {
                            $a333d3a8e05417e016d924bc44baa9a3d = "<a href=" . $a7af59dbff38171e395fda52dbb3028f3['url'] . ">" . $a7af59dbff38171e395fda52dbb3028f3['name'] . "</a>";
                        } else {
                            $a333d3a8e05417e016d924bc44baa9a3d = $a7af59dbff38171e395fda52dbb3028f3['name'];
                        }
                        $a333d3a8e05417e016d924bc44baa9a3d = "<span class='status-name'>" . $a333d3a8e05417e016d924bc44baa9a3d . "</span>";
                    } else {
                        $a333d3a8e05417e016d924bc44baa9a3d = '';
                    }
                    if ($a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['status_display'] == 'inline') {
                        $a268c53eae88635e3a9539b2e9678acb9 = "<span class='product-status product-status-" . $a7af59dbff38171e395fda52dbb3028f3['status_id'] . "'>" . $a7a33f61f16f67c1be945e4ea4b01c484 . $a333d3a8e05417e016d924bc44baa9a3d . "</span>";
                    } else {
                        $a268c53eae88635e3a9539b2e9678acb9 = "<div class='product-status product-status-" . $a7af59dbff38171e395fda52dbb3028f3['status_id'] . "'>" . $a7a33f61f16f67c1be945e4ea4b01c484 . $a333d3a8e05417e016d924bc44baa9a3d . "</div>";
                    }
                    $ac333fbb97cbe8625a23ebcde093be86e[$a06dee0aab9da3a856736a38f2d30ee3f] .= $a268c53eae88635e3a9539b2e9678acb9;
                }
            }
        }
        foreach ($ac333fbb97cbe8625a23ebcde093be86e as $a06dee0aab9da3a856736a38f2d30ee3f => &$ada238414c3192eb43f0782ad274ce544) {
            $ada238414c3192eb43f0782ad274ce544 .= "<style>" . $a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['css'] . "</style>";
        }				
		
        return $ac333fbb97cbe8625a23ebcde093be86e;
				
    }

    public function getThumb($a7a33f61f16f67c1be945e4ea4b01c484, $a06dee0aab9da3a856736a38f2d30ee3f = 'product')
    {
        $this->load->model('tool/image');
        $a776f5c4135b037603b74c97cdbebba18 = $this->config->get('product_status_options');
        $ac2b58574c9be213976ad15f8a14bed73 = $this->config->get('config_url') . 'image/' . $a7a33f61f16f67c1be945e4ea4b01c484;
        if (!isset($a7a33f61f16f67c1be945e4ea4b01c484) || !$a7a33f61f16f67c1be945e4ea4b01c484 || !file_exists(DIR_IMAGE . $a7a33f61f16f67c1be945e4ea4b01c484)) {
            $a7a33f61f16f67c1be945e4ea4b01c484 = 'no_image.jpg';
        }
        if ($a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['image_width'] || $a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['image_height']) {
            $ac2b58574c9be213976ad15f8a14bed73 = $this->model_tool_image->resize($a7a33f61f16f67c1be945e4ea4b01c484, $a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['image_width'], $a776f5c4135b037603b74c97cdbebba18[$a06dee0aab9da3a856736a38f2d30ee3f]['image_height']);
        }
        return $ac2b58574c9be213976ad15f8a14bed73;
    }
}