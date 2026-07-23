<?php

/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling AJAX actions in Header Menu
 */
class ajax_controller
{
	/** @var \vinny\menu\service\menu_operator */
	protected $menu_operator;

	/** @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param \vinny\menu\service\menu_operator $menu_operator
	 * @param \phpbb\language\language          $language
	 */
	public function __construct(\vinny\menu\service\menu_operator $menu_operator, \phpbb\language\language $language)
	{
		$this->menu_operator = $menu_operator;
		$this->language = $language;
	}

	/**
	 * AJAX endpoint to update item order & hierarchy
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function reorder(Request $request)
	{
		$this->language->add_lang('common', 'vinny/menu');

		// Check raw JSON payload
		$content = $request->getContent();
		if (!empty($content))
		{
			$data = json_decode($content, true);
			if (isset($data['items']) && is_array($data['items']))
			{
				$this->menu_operator->update_hierarchy($data['items']);
				return new JsonResponse(['success' => true]);
			}
			if (isset($data['order']) && is_array($data['order']))
			{
				$this->menu_operator->update_orders($data['order']);
				return new JsonResponse(['success' => true]);
			}
		}

		// Check POST array 'order' (FormData)
		$order = $request->request->get('order', []);
		if (!empty($order) && is_array($order))
		{
			$this->menu_operator->update_orders($order);
			return new JsonResponse(['success' => true]);
		}

		// Check POST parameter 'items_json'
		$items_str = $request->request->get('items_json', '');
		if (!empty($items_str))
		{
			$items_data = json_decode($items_str, true);
			if (is_array($items_data))
			{
				$this->menu_operator->update_hierarchy($items_data);
				return new JsonResponse(['success' => true]);
			}
		}

		return new JsonResponse(['success' => false, 'error' => $this->language->lang('MENU_NO_ORDER_DATA')], 400);
	}
}
