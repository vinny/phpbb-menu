<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\acp;

/**
 * ACP Module Class for Header Menu
 */
class main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	/**
	 * Main execution logic for ACP module.
	 *
	 * @param int    $id
	 * @param string $mode
	 */
	public function main($id, $mode)
	{
		global $phpbb_container, $language, $template, $request, $config, $phpbb_root_path, $phpEx;

		$language->add_lang(['info_acp_menu', 'common'], 'vinny/menu');

		/** @var \vinny\menu\service\menu_operator $menu_operator */
		$menu_operator = $phpbb_container->get('vinny.menu.operator');

		$this->tpl_name = 'acp_menu_body';
		$this->page_title = $language->lang('ACP_HEADER_MENU_TITLE');

		$action = $request->variable('action', '');
		$item_id = $request->variable('item_id', 0);

		add_form_key('vinny_menu_manage');

		// Handle actions
		switch ($action)
		{
			case 'save_settings':
				if ($request->is_set_post('submit_settings'))
				{
					if (!check_form_key('vinny_menu_manage'))
					{
						trigger_error($language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$config->set('vinny_menu_enable', $request->variable('vinny_menu_enable', 1));

					trigger_error($language->lang('MENU_SETTINGS_SAVED') . adm_back_link($this->u_action));
				}
			break;

			case 'save':
				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('vinny_menu_manage'))
					{
						trigger_error($language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$item_data = [
						'parent_id'    => $request->variable('parent_id', 0),
						'item_name'    => $request->variable('item_name', '', true),
						'item_url'     => $request->variable('item_url', '', true),
						'item_icon'    => $request->variable('item_icon', ''),
						'item_target'  => $request->variable('item_target', '_self'),
						'item_enabled' => $request->variable('item_enabled', 1),
					];

					if (empty($item_data['item_name']))
					{
						trigger_error($language->lang('MENU_ITEM_NAME_REQUIRED') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$menu_operator->save_item($item_data, $item_id);

					$msg = ($item_id > 0) ? $language->lang('MENU_ITEM_UPDATED') : $language->lang('MENU_ITEM_ADDED');
					trigger_error($msg . adm_back_link($this->u_action));
				}
			break;

			case 'delete':
				if (confirm_box(true))
				{
					$menu_operator->delete_item($item_id);

					if ($request->is_ajax())
					{
						$json_response = new \phpbb\json_response();
						$json_response->send(['success' => true]);
					}

					trigger_error($language->lang('MENU_ITEM_DELETED') . adm_back_link($this->u_action));
				}
				else
				{
					$confirm_key = $menu_operator->has_children($item_id) ? 'CONFIRM_DELETE_MENU_ITEM_WITH_CHILDREN' : 'CONFIRM_DELETE_MENU_ITEM';
					confirm_box(false, $language->lang($confirm_key), build_hidden_fields([
						'i'       => $id,
						'mode'    => $mode,
						'action'  => 'delete',
						'item_id' => $item_id,
					]));
				}
			break;

			case 'move_up':
			case 'move_down':
				$direction = ($action === 'move_up') ? 'up' : 'down';
				$menu_operator->move_item($item_id, $direction);

				if ($request->is_ajax())
				{
					$json_response = new \phpbb\json_response();
					$json_response->send(['success' => true]);
				}

				meta_refresh(0, $this->u_action);
			break;

			case 'toggle':
				if (!check_link_hash($request->variable('hash', ''), 'toggle_enabled'))
				{
					if ($request->is_ajax())
					{
						$json_response = new \phpbb\json_response();
						$json_response->send(['success' => false, 'error' => $language->lang('FORM_INVALID')]);
					}
					trigger_error($language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$new_status = $menu_operator->toggle_enabled($item_id);

				if ($request->is_ajax())
				{
					$icon_class = ($new_status) ? 'fa-check-circle' : 'fa-times-circle';
					$icon_color = ($new_status) ? '#228822' : '#bcbcbc';
					$title      = ($new_status) ? $language->lang('YES') : $language->lang('NO');

					$json_response = new \phpbb\json_response();
					$json_response->send(['success' => true, 'icon_class' => $icon_class, 'icon_color' => $icon_color, 'title' => $title]);
				}

				meta_refresh(0, $this->u_action);
			break;
		}

		// Prepare Edit mode data if editing
		$edit_item = [];
		if ($action === 'edit' && $item_id > 0)
		{
			$edit_item = $menu_operator->get_item($item_id);
		}

		// Fetch nested tree items for display
		$tree = $menu_operator->get_tree();
		$parents = $menu_operator->get_parent_options($item_id);

		$toggle_hash = generate_link_hash('toggle_enabled');

		$count = count($tree);
		foreach ($tree as $index => $item)
		{
			$template->assign_block_vars('items', [
				'ID'          => $item['item_id'],
				'NAME'        => $item['item_name'],
				'URL'         => $item['item_url'],
				'ICON'        => $item['item_icon'],
				'TARGET'      => $item['item_target'],
				'IS_ENABLED'  => (bool) $item['item_enabled'],
				'S_FIRST_ROW' => ($index === 0),
				'S_LAST_ROW'  => ($index === $count - 1),
				'U_EDIT'      => $this->u_action . '&amp;action=edit&amp;item_id=' . $item['item_id'],
				'U_DELETE'    => $this->u_action . '&amp;action=delete&amp;item_id=' . $item['item_id'],
				'U_MOVE_UP'   => $this->u_action . '&amp;action=move_up&amp;item_id=' . $item['item_id'],
				'U_MOVE_DOWN' => $this->u_action . '&amp;action=move_down&amp;item_id=' . $item['item_id'],
				'U_TOGGLE'    => $this->u_action . '&amp;action=toggle&amp;item_id=' . $item['item_id'] . '&amp;hash=' . $toggle_hash,
			]);

			if (!empty($item['children']))
			{
				$sub_count = count($item['children']);
				foreach ($item['children'] as $sub_index => $child)
				{
					$template->assign_block_vars('items.children', [
						'ID'          => $child['item_id'],
						'NAME'        => $child['item_name'],
						'URL'         => $child['item_url'],
						'ICON'        => $child['item_icon'],
						'TARGET'      => $child['item_target'],
						'IS_ENABLED'  => (bool) $child['item_enabled'],
						'S_FIRST_ROW' => ($sub_index === 0),
						'S_LAST_ROW'  => ($sub_index === $sub_count - 1),
						'U_EDIT'      => $this->u_action . '&amp;action=edit&amp;item_id=' . $child['item_id'],
						'U_DELETE'    => $this->u_action . '&amp;action=delete&amp;item_id=' . $child['item_id'],
						'U_MOVE_UP'   => $this->u_action . '&amp;action=move_up&amp;item_id=' . $child['item_id'],
						'U_MOVE_DOWN' => $this->u_action . '&amp;action=move_down&amp;item_id=' . $child['item_id'],
						'U_TOGGLE'    => $this->u_action . '&amp;action=toggle&amp;item_id=' . $child['item_id'] . '&amp;hash=' . $toggle_hash,
					]);

					if (!empty($child['children']))
					{
						$subsub_count = count($child['children']);
						foreach ($child['children'] as $subsub_index => $subchild)
						{
							$template->assign_block_vars('items.children.subchildren', [
								'ID'          => $subchild['item_id'],
								'NAME'        => $subchild['item_name'],
								'URL'         => $subchild['item_url'],
								'ICON'        => $subchild['item_icon'],
								'TARGET'      => $subchild['item_target'],
								'IS_ENABLED'  => (bool) $subchild['item_enabled'],
								'S_FIRST_ROW' => ($subsub_index === 0),
								'S_LAST_ROW'  => ($subsub_index === $subsub_count - 1),
								'U_EDIT'      => $this->u_action . '&amp;action=edit&amp;item_id=' . $subchild['item_id'],
								'U_DELETE'    => $this->u_action . '&amp;action=delete&amp;item_id=' . $subchild['item_id'],
								'U_MOVE_UP'   => $this->u_action . '&amp;action=move_up&amp;item_id=' . $subchild['item_id'],
								'U_MOVE_DOWN' => $this->u_action . '&amp;action=move_down&amp;item_id=' . $subchild['item_id'],
								'U_TOGGLE'    => $this->u_action . '&amp;action=toggle&amp;item_id=' . $subchild['item_id'] . '&amp;hash=' . $toggle_hash,
							]);
						}
					}
				}
			}
		}

		// Populate parent dropdown options for form
		foreach ($parents as $p_id => $p_name)
		{
			$template->assign_block_vars('parent_options', [
				'ID'       => $p_id,
				'NAME'     => $p_name,
				'SELECTED' => (!empty($edit_item) && $edit_item['parent_id'] == $p_id),
			]);
		}

		// AJAX Reorder URL
		/** @var \phpbb\controller\helper $controller_helper */
		$controller_helper = $phpbb_container->get('controller.helper');
		$u_reorder_ajax = $controller_helper->route('vinny_menu_reorder');

		$template->assign_vars([
			'U_ACTION'           => $this->u_action,
			'U_REORDER_AJAX'     => $u_reorder_ajax,
			'S_MENU_ENABLE'      => (bool) ($config['vinny_menu_enable'] ?? 1),
			'S_EDIT'             => !empty($edit_item),
			'ITEM_ID'            => !empty($edit_item) ? $edit_item['item_id'] : 0,
			'ITEM_NAME'          => !empty($edit_item) ? $edit_item['item_name'] : '',
			'ITEM_URL'           => !empty($edit_item) ? $edit_item['item_url'] : '',
			'ITEM_ICON'          => !empty($edit_item) ? $edit_item['item_icon'] : '',
			'ITEM_TARGET'        => !empty($edit_item) ? $edit_item['item_target'] : '_self',
			'ITEM_ENABLED'       => !empty($edit_item) ? $edit_item['item_enabled'] : 1,
			'PARENT_ID'          => !empty($edit_item) ? $edit_item['parent_id'] : 0,

			'ICON_MOVE_UP'       => '<i class="icon acp-icon fa-arrow-up fa-fw" aria-hidden="true" title="' . $language->lang('MOVE_UP') . '"></i>',
			'ICON_MOVE_DOWN'     => '<i class="icon acp-icon fa-arrow-down fa-fw" aria-hidden="true" title="' . $language->lang('MOVE_DOWN') . '"></i>',
			'ICON_EDIT'          => '<i class="icon acp-icon fa-pencil fa-fw" aria-hidden="true" title="' . $language->lang('EDIT') . '"></i>',
			'ICON_DELETE'        => '<i class="icon acp-icon fa-trash fa-fw" aria-hidden="true" title="' . $language->lang('DELETE') . '"></i>',
		]);
	}
}
