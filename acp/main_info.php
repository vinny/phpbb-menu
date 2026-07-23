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
 * ACP Module Info Class for Header Menu
 */
class main_info
{
	/**
	 * Returns module layout and configuration.
	 *
	 * @return array
	 */
	public function module()
	{
		return [
			'filename' => '\vinny\menu\acp\main_module',
			'title'    => 'ACP_HEADER_MENU_TITLE',
			'modes'    => [
				'manage' => [
					'title' => 'ACP_HEADER_MENU_MANAGE',
					'auth'  => 'ext_vinny/menu && acl_a_board',
					'cat'   => ['ACP_HEADER_MENU_TITLE'],
				],
			],
		];
	}
}
