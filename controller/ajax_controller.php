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

/**
 * Controller for handling AJAX actions in Header Menu
 */
class ajax_controller
{
	/** @var \vinny\menu\service\menu_operator */
	protected $menu_operator;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param \vinny\menu\service\menu_operator $menu_operator
	 * @param \phpbb\language\language          $language
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\request\request_interface  $request
	 */
	public function __construct(
		\vinny\menu\service\menu_operator $menu_operator,
		\phpbb\language\language $language,
		\phpbb\auth\auth $auth,
		\phpbb\request\request_interface $request
	)
	{
		$this->menu_operator = $menu_operator;
		$this->language = $language;
		$this->auth = $auth;
		$this->request = $request;
	}

	/**
	 * AJAX endpoint to update item order & hierarchy
	 *
	 * @return JsonResponse
	 */
	public function reorder()
	{
		$this->language->add_lang('common', 'vinny/menu');

		$hash = $this->request->variable('hash', '');

		// Explicit ACL Permission Check and CSRF token verification
		if (!$this->auth->acl_get('a_board') || !check_link_hash($hash, 'vinny_menu_reorder'))
		{
			return new JsonResponse(['success' => false, 'error' => $this->language->lang('FORM_INVALID')], 403);
		}

		$items_str = $this->request->variable('items_json', '', true);
		if (!empty($items_str))
		{
			$items_data = json_decode(htmlspecialchars_decode($items_str, ENT_COMPAT), true);
			if (is_array($items_data))
			{
				$this->menu_operator->update_hierarchy($items_data);
				return new JsonResponse(['success' => true]);
			}
		}

		return new JsonResponse(['success' => false, 'error' => $this->language->lang('MENU_NO_ORDER_DATA')], 400);
	}
}
