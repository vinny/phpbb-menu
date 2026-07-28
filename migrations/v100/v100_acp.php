<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\migrations\v100;

/**
 * Migration 2: Register ACP module for Header Menu
 */
class v100_acp extends \phpbb\db\migration\migration
{
	/**
	 * Check if migration is effectively installed.
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		$sql = 'SELECT module_id FROM ' . MODULES_TABLE . "
			WHERE module_class = 'acp'
				AND module_langname = 'ACP_HEADER_MENU_TITLE'";
		$result = $this->db->sql_query($sql);
		$installed = (bool) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		return $installed;
	}

	/**
	 * Define migration dependencies.
	 *
	 * @return array
	 */
	public static function depends_on()
	{
		return ['\vinny\menu\migrations\v100\v100_schema'];
	}

	/**
	 * Add ACP modules.
	 *
	 * @return array
	 */
	public function update_data()
	{
		return [
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_HEADER_MENU_TITLE',
			]],
			['module.add', [
				'acp',
				'ACP_HEADER_MENU_TITLE',
				[
					'module_basename' => '\vinny\menu\acp\main_module',
					'modes'           => ['manage'],
				],
			]],
		];
	}

	/**
	 * Revert ACP modules.
	 *
	 * @return array
	 */
	public function revert_data()
	{
		return [
			['module.remove', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_HEADER_MENU_TITLE',
			]],
		];
	}
}
