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
 * Migration 1: Create database schema and configs for Header Menu
 */
class v100_schema extends \phpbb\db\migration\migration
{
	/**
	 * Check if migration is effectively installed.
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'vinny_menu_items') && isset($this->config['vinny_menu_enable']) && isset($this->config['vinny_menu_search']);
	}

	/**
	 * Define migration dependencies.
	 *
	 * @return array
	 */
	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	/**
	 * Apply schema changes (create table).
	 *
	 * @return array
	 */
	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'vinny_menu_items' => [
					'COLUMNS' => [
						'item_id'		=> ['UINT', null, 'auto_increment'],
						'parent_id'		=> ['UINT', 0],
						'item_name'		=> ['VCHAR:255', ''],
						'item_url'		=> ['VCHAR:255', ''],
						'item_icon'		=> ['VCHAR:100', ''],
						'item_target'	=> ['VCHAR:20', '_self'],
						'item_order'	=> ['UINT', 0],
						'item_enabled'	=> ['BOOL', 1],
						'item_hide_groups' => ['VCHAR:255', ''],
						'item_desc'		=> ['VCHAR:255', ''],
					],
					'PRIMARY_KEY' => 'item_id',
					'KEYS' => [
						'parent_id' 	=> ['INDEX', 'parent_id'],
						'item_order'	=> ['INDEX', 'item_order'],
					],
				],
			],
		];
	}

	/**
	 * Revert schema changes (drop table).
	 *
	 * @return array
	 */
	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'vinny_menu_items',
			],
		];
	}

	/**
	 * Add default configs.
	 *
	 * @return array
	 */
	public function update_data()
	{
		return [
			['config.add', ['vinny_menu_enable', 1]],
			['config.add', ['vinny_menu_search', 0]],
			['config.add', ['vinny_menu_bg_colour', '#12a3eb']],
			['config.add', ['vinny_menu_bg_hover_colour', '#0076b1']],
			['config.add', ['vinny_menu_text_colour', '#ffffff']],
			['config.add', ['vinny_menu_text_hover_colour', '#d31141']],
			['config.add', ['vinny_menu_sub_bg_colour', '#12a3eb']],
			['config.add', ['vinny_menu_sub_text_colour', '#ffffff']],
			['config.add', ['vinny_menu_sub_bg_hover', '#0076b1']],
			['config.add', ['vinny_menu_sub_text_hover', '#d31141']],
			['config.add', ['vinny_menu_sub_desc_colour', '#000000']],
		];
	}

	/**
	 * Revert default configs.
	 *
	 * @return array
	 */
	public function revert_data()
	{
		return [
			['config.remove', ['vinny_menu_enable']],
			['config.remove', ['vinny_menu_search']],
			['config.remove', ['vinny_menu_bg_colour']],
			['config.remove', ['vinny_menu_bg_hover_colour']],
			['config.remove', ['vinny_menu_text_colour']],
			['config.remove', ['vinny_menu_text_hover_colour']],
			['config.remove', ['vinny_menu_sub_bg_colour']],
			['config.remove', ['vinny_menu_sub_text_colour']],
			['config.remove', ['vinny_menu_sub_bg_hover']],
			['config.remove', ['vinny_menu_sub_text_hover']],
			['config.remove', ['vinny_menu_sub_desc_colour']],
		];
	}
}
