<?php
class ModelExtensionbarbaraBlogPostmodify extends Model {
	public function createPost($data) {
		$this->event->trigger('pre.admin.post.add', $data);

		$post_author = $this->user->getId() ? $this->user->getId() : '';
		$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post SET post_author = '" . $this->db->escape($post_author) . "', sort_order = '" . (int)$data['sort_order'] . "', post_status = '" . $this->db->escape($data['status']) . "', date_available = '" . $this->db->escape($data['date_available']) . "', date_added = NOW()");

		$post_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "barbara_blog_post SET post_thumb = '" . $this->db->escape($data['image']) . "' WHERE ID = '" . (int)$post_id . "'");
		}

		foreach ($data['post_description'] as $language_id => $description) {
			$query = "INSERT INTO " . DB_PREFIX . "barbara_blog_post_description SET ";
			$query .= "post_id = '" . (int)$post_id . "', ";
			$query .= "language_id = '" . (int)$language_id . "', ";
			$inc = 0;
			foreach ($description as $key => $value) {
				if($inc == 0) {
					$query .= $key . "='" . $this->db->escape($value) . "'";
				} else {
					$query .= ", " . $key . "='" . $this->db->escape($value) . "' ";
				}
				$inc++;
			}
			$this->db->query($query);
		}

		if (isset($data['post_category'])) {
			foreach ($data['post_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_to_category SET post_id = '" . (int)$post_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['post_filter'])) {
			foreach ($data['post_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_filter SET post_id = '" . (int)$post_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['post_store'])) {
			foreach ($data['post_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_to_store SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['related_product'])) {
			foreach ($data['related_product'] as $product_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_related_product WHERE post_id = '" . (int)$post_id . "' AND product_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_related_product SET post_id = '" . (int)$post_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['post_image'])) {
			foreach ($data['post_image'] as $post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_postmeta SET 
					post_id = '" . (int)$post_id . "', 
					meta_key = 'image', 
					meta_value = '" . $this->db->escape($post_image['meta_value']) . "', 
					sort_order = '" . (int)$post_image['sort_order'] . "'");
			}
		}

		if (isset($data['post_layout'])) {
			foreach ($data['post_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_to_layout SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'barbara_post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('post');
		$this->cache->delete('barbara_blog_category.seopath');
		$this->cache->delete('barbara_post.seopath');
		$this->cache->delete('seopro');

		$this->event->trigger('post.admin.post.add', $post_id);

		return $post_id;
	}

	public function editPost($post_id, $data) {
		$this->event->trigger('pre.admin.post.edit', $data);

		$post_author = $this->user->getId() ? $this->user->getId() : '';
		$this->db->query("UPDATE " . DB_PREFIX . "barbara_blog_post SET post_author = '" . $this->db->escape($post_author) . "', sort_order = '" . (int)$data['sort_order'] . "', post_status = '" . $this->db->escape($data['status']) . "', date_available = '" . $this->db->escape($data['date_available']) . "', date_modified = NOW() WHERE ID = '" . (int)$post_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "barbara_blog_post SET post_thumb = '" . $this->db->escape($data['image']) . "' WHERE ID = '" . (int)$post_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_description WHERE post_id = '" . (int)$post_id . "'");
		foreach ($data['post_description'] as $language_id => $description) {
			$query = "INSERT INTO " . DB_PREFIX . "barbara_blog_post_description SET ";
			$query .= "post_id = '" . (int)$post_id . "', ";
			$query .= "language_id = '" . (int)$language_id . "', ";
			$inc = 0;
			foreach ($description as $key => $value) {
				if($inc == 0) {
					$query .= $key . "='" . $this->db->escape($value) . "'";
				} else {
					$query .= ", " . $key . "='" . $this->db->escape($value) . "' ";
				}
				$inc++;
			}
			$this->db->query($query);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_to_category WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_category'])) {
			foreach ($data['post_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_to_category SET post_id = '" . (int)$post_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_filter WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_filter'])) {
			foreach ($data['post_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_filter SET post_id = '" . (int)$post_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_to_store WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_store'])) {
			foreach ($data['post_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_to_store SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_related_product WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['related_product'])) {
			foreach ($data['related_product'] as $product_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_related_product WHERE post_id = '" . (int)$post_id . "' AND product_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_related_product SET post_id = '" . (int)$post_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_postmeta WHERE post_id = '" . (int)$post_id . "' AND meta_key='image'");
		if (isset($data['post_image'])) {
			foreach ($data['post_image'] as $post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_postmeta SET 
					post_id = '" . (int)$post_id . "', 
					meta_key = 'image', 
					meta_value = '" . $this->db->escape($post_image['meta_value']) . "', 
					sort_order = '" . (int)$post_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_to_layout WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_layout'])) {
			foreach ($data['post_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_post_to_layout SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'barbara_post_id=" . (int)$post_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'barbara_post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('post');
		$this->cache->delete('barbara_blog_category.seopath');
		$this->cache->delete('barbara_post.seopath');
		$this->cache->delete('seopro');

		$this->event->trigger('post.admin.post.edit', $post_id);
	}

	public function deletePost($post_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post WHERE ID = '" . (int)$post_id . "' LIMIT 1");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_description WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_filter WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_to_category WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_postmeta WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_to_store WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_related_product WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "barbara_blog_post_to_layout WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'barbara_post_id=" . (int)$post_id . "'");

		$this->cache->delete('post');
		$this->cache->delete('barbara_blog_category.seopath');
		$this->cache->delete('barbara_post.seopath');
		$this->cache->delete('seopro');

		return true;
	}

}