<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\service;

/**
 * Menu Operator Service for Header Menu
 */
class menu_operator
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $table_name;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param string                            $table_name
	 * @param \phpbb\user                       $user
	 * @param \phpbb\auth\auth                  $auth
	 * @param string                            $phpbb_root_path
	 * @param string                            $php_ext
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, $table_name, \phpbb\user $user, \phpbb\auth\auth $auth, $phpbb_root_path, $php_ext)
	{
		$this->db = $db;
		$this->table_name = $table_name;
		$this->user = $user;
		$this->auth = $auth;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Get a single menu item by ID.
	 *
	 * @param int $item_id
	 * @return array|bool
	 */
	public function get_item($item_id)
	{
		$sql = 'SELECT * FROM ' . $this->table_name . '
			WHERE item_id = ' . (int) $item_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row;
	}

	/**
	 * Fetch all menu items ordered by item_order and item_id.
	 *
	 * @return array
	 */
	public function get_all_items()
	{
		$sql = 'SELECT * FROM ' . $this->table_name . '
			ORDER BY item_order ASC, item_id ASC';
		$result = $this->db->sql_query($sql);
		$items = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$items[] = $row;
		}
		$this->db->sql_freeresult($result);

		return $items;
	}

	/**
	 * Fetch all items flattened in hierarchical sequence with depth metadata.
	 *
	 * @return array List of items with 'depth' key
	 */
	public function get_hierarchical_items()
	{
		$all_items = $this->get_all_items();
		$tree = [];
		$this->flatten_hierarchical_items($all_items, 0, 0, $tree);
		return $tree;
	}

	/**
	 * Recursive helper to flatten menu tree.
	 *
	 * @param array $items
	 * @param int   $parent_id
	 * @param int   $depth
	 * @param array &$result
	 */
	protected function flatten_hierarchical_items($items, $parent_id, $depth, &$result)
	{
		foreach ($items as $item)
		{
			if ((int) $item['parent_id'] === (int) $parent_id)
			{
				$item['depth'] = $depth;
				$result[] = $item;
				$this->flatten_hierarchical_items($items, $item['item_id'], $depth + 1, $result);
			}
		}
	}

	/**
	 * Fetch all items as a 3-level tree structure (for ACP or frontend).
	 *
	 * @return array
	 */
	public function get_tree()
	{
		$all_items = [];
		$sql = 'SELECT * FROM ' . $this->table_name . '
			ORDER BY item_order ASC, item_id ASC';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$row['formatted_url'] = $this->format_url($row['item_url']);
			$all_items[$row['item_id']] = $row;
			$all_items[$row['item_id']]['children'] = [];
		}
		$this->db->sql_freeresult($result);

		$tree = [];
		foreach ($all_items as $id => $item)
		{
			if ($item['parent_id'] > 0 && isset($all_items[$item['parent_id']]))
			{
				$all_items[$item['parent_id']]['children'][] = &$all_items[$id];
			}
			elseif ($item['parent_id'] == 0)
			{
				$tree[] = &$all_items[$id];
			}
		}

		return $tree;
	}

	/**
	 * Get potential parent items with hierarchical formatting.
	 *
	 * @param int $exclude_id Item ID to exclude (e.g. current item when editing)
	 * @return array Map of item_id => formatted_name
	 */
	public function get_parent_options($exclude_id = 0)
	{
		$sql = 'SELECT item_id, parent_id, item_name FROM ' . $this->table_name . '
			' . (($exclude_id > 0) ? 'WHERE item_id <> ' . (int) $exclude_id : '') . '
			ORDER BY item_order ASC, item_name ASC';
		$result = $this->db->sql_query($sql);
		$all = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$all[] = $row;
		}
		$this->db->sql_freeresult($result);

		$parents = [];
		$this->build_parent_tree($all, 0, 0, $parents);

		return $parents;
	}

	/**
	 * Recursive helper to build parent dropdown options with indentation.
	 *
	 * @param array  $items
	 * @param int    $parent_id
	 * @param int    $depth
	 * @param array  &$result
	 */
	protected function build_parent_tree($items, $parent_id, $depth, &$result)
	{
		foreach ($items as $item)
		{
			if ((int) $item['parent_id'] === (int) $parent_id)
			{
				$prefix = str_repeat('   ', $depth) . ($depth > 0 ? '└-- ' : '');
				$result[$item['item_id']] = $prefix . $item['item_name'];
				$this->build_parent_tree($items, $item['item_id'], $depth + 1, $result);
			}
		}
	}

	/**
	 * Save (insert or update) a menu item.
	 *
	 * @param array $data
	 * @param int   $item_id
	 * @return int
	 */
	public function save_item($data, $item_id = 0)
	{
		$sql_ary = [
			'parent_id'    => (int) ($data['parent_id'] ?? 0),
			'item_name'    => (string) ($data['item_name'] ?? ''),
			'item_url'     => (string) ($data['item_url'] ?? ''),
			'item_icon'    => (string) ($data['item_icon'] ?? ''),
			'item_target'  => (string) ($data['item_target'] ?? '_self'),
			'item_enabled' => (int) ($data['item_enabled'] ?? 1),
		];

		if ($item_id > 0)
		{
			$sql = 'UPDATE ' . $this->table_name . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
				WHERE item_id = ' . (int) $item_id;
			$this->db->sql_query($sql);
			return $item_id;
		}
		else
		{
			// Get highest order
			$sql = 'SELECT MAX(item_order) as max_order FROM ' . $this->table_name;
			$result = $this->db->sql_query($sql);
			$max_order = (int) $this->db->sql_fetchfield('max_order');
			$this->db->sql_freeresult($result);

			$sql_ary['item_order'] = $max_order + 1;

			$sql = 'INSERT INTO ' . $this->table_name . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);

			return (int) $this->db->sql_nextid();
		}
	}

	/**
	 * Bulk update item hierarchy (parent_id and item_order) from nested SortableJS output.
	 *
	 * @param array $items List of array('id' => int, 'parent_id' => int, 'order' => int)
	 */
	public function update_hierarchy(array $items)
	{
		if (empty($items))
		{
			return;
		}

		$this->db->sql_transaction('begin');

		foreach ($items as $item)
		{
			$id = (int) ($item['id'] ?? 0);
			$parent_id = (int) ($item['parent_id'] ?? 0);
			$order = (int) ($item['order'] ?? 0);

			if ($id > 0)
			{
				$sql = 'UPDATE ' . $this->table_name . '
					SET parent_id = ' . $parent_id . ',
						item_order = ' . $order . '
					WHERE item_id = ' . $id;
				$this->db->sql_query($sql);
			}
		}

		$this->db->sql_transaction('commit');
	}

	/**
	 * Bulk update item order from an array of item IDs in order.
	 *
	 * @param array $item_ids List of item_id in desired sequence
	 */
	public function update_orders(array $item_ids)
	{
		if (empty($item_ids))
		{
			return;
		}

		$this->db->sql_transaction('begin');

		$order = 1;
		foreach ($item_ids as $id)
		{
			$id = (int) $id;
			if ($id > 0)
			{
				$sql = 'UPDATE ' . $this->table_name . '
					SET item_order = ' . $order . '
					WHERE item_id = ' . $id;
				$this->db->sql_query($sql);
				$order++;
			}
		}

		$this->db->sql_transaction('commit');
	}

	/**
	 * Check if item has any child items.
	 *
	 * @param int $item_id
	 * @return bool
	 */
	public function has_children($item_id)
	{
		$sql = 'SELECT item_id FROM ' . $this->table_name . '
			WHERE parent_id = ' . (int) $item_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	 * Delete a menu item and all of its sub-items recursively.
	 *
	 * @param int $item_id
	 */
	public function delete_item($item_id)
	{
		$item_id = (int) $item_id;
		if ($item_id <= 0)
		{
			return;
		}

		$all_items = $this->get_all_items();
		$ids_to_delete = [$item_id];
		$this->collect_descendant_ids($all_items, $item_id, $ids_to_delete);

		$sql = 'DELETE FROM ' . $this->table_name . '
			WHERE ' . $this->db->sql_in_set('item_id', $ids_to_delete);
		$this->db->sql_query($sql);
	}

	/**
	 * In-memory recursive helper to collect child item IDs from cached array.
	 *
	 * @param array $all_items
	 * @param int   $parent_id
	 * @param array &$collected
	 */
	protected function collect_descendant_ids($all_items, $parent_id, &$collected)
	{
		foreach ($all_items as $item)
		{
			if ((int) $item['parent_id'] === (int) $parent_id)
			{
				$child_id = (int) $item['item_id'];
				$collected[] = $child_id;
				$this->collect_descendant_ids($all_items, $child_id, $collected);
			}
		}
	}

	/**
	 * Move item order up or down.
	 *
	 * @param int    $item_id
	 * @param string $direction 'up' or 'down'
	 */
	public function move_item($item_id, $direction)
	{
		$current = $this->get_item($item_id);
		if (!$current)
		{
			return;
		}

		$current_order = (int) $current['item_order'];

		if ($direction === 'up')
		{
			$sql = 'SELECT item_id, item_order FROM ' . $this->table_name . '
				WHERE item_order < ' . $current_order . '
				ORDER BY item_order DESC';
		}
		else
		{
			$sql = 'SELECT item_id, item_order FROM ' . $this->table_name . '
				WHERE item_order > ' . $current_order . '
				ORDER BY item_order ASC';
		}

		$result = $this->db->sql_query_limit($sql, 1);
		$target = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($target)
		{
			$target_id = (int) $target['item_id'];
			$target_order = (int) $target['item_order'];

			$this->db->sql_transaction('begin');

			// Swap order values
			$sql = 'UPDATE ' . $this->table_name . '
				SET item_order = ' . $target_order . '
				WHERE item_id = ' . (int) $item_id;
			$this->db->sql_query($sql);

			$sql = 'UPDATE ' . $this->table_name . '
				SET item_order = ' . $current_order . '
				WHERE item_id = ' . $target_id;
			$this->db->sql_query($sql);

			$this->db->sql_transaction('commit');
		}
	}

	/**
	 * Toggle item enabled state.
	 *
	 * @param int $item_id
	 * @return int New state (1 or 0)
	 */
	public function toggle_enabled($item_id)
	{
		$item = $this->get_item($item_id);
		if ($item)
		{
			$new_state = $item['item_enabled'] ? 0 : 1;
			$sql = 'UPDATE ' . $this->table_name . '
				SET item_enabled = ' . $new_state . '
				WHERE item_id = ' . (int) $item_id;
			$this->db->sql_query($sql);

			return $new_state;
		}
		return 0;
	}

	/**
	 * Get structured menu tree for frontend output.
	 *
	 * @return array
	 */
	public function get_visible_menu_tree()
	{
		$sql = 'SELECT * FROM ' . $this->table_name . '
			WHERE item_enabled = 1
			ORDER BY item_order ASC, item_id ASC';
		$result = $this->db->sql_query($sql);

		$all_items = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$row['formatted_url'] = $this->format_url($row['item_url']);
			$all_items[$row['item_id']] = $row;
			$all_items[$row['item_id']]['children'] = [];
		}
		$this->db->sql_freeresult($result);

		$tree = [];
		foreach ($all_items as $id => $item)
		{
			if ($item['parent_id'] > 0 && isset($all_items[$item['parent_id']]))
			{
				$all_items[$item['parent_id']]['children'][] = &$all_items[$id];
			}
			elseif ($item['parent_id'] == 0)
			{
				$tree[] = &$all_items[$id];
			}
		}

		return $tree;
	}

	/**
	 * Format URL (append append_sid if relative forum URL).
	 *
	 * @param string $url
	 * @return string
	 */
	protected function format_url($url)
	{
		$url = trim($url);

		if ($url === '')
		{
			return '#';
		}

		// External link, anchor, mailto or void javascript
		if (preg_match('#^(https?://|//|\#|mailto:|javascript:)#i', $url))
		{
			return $url;
		}

		// Relative forum URL
		$clean_url = ltrim($url, '/');
		return append_sid($this->phpbb_root_path . $clean_url);
	}
}
