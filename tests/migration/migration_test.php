<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\tests\migration;

/**
 * Migration Test for Header Menu Extension
 */
class migration_test extends \PHPUnit\Framework\TestCase
{
	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\config\config */
	protected $config;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\db\tools\tools_interface */
	protected $db_tools;

	protected function setUp(): void
	{
		$this->db = $this->createMock(\phpbb\db\driver\driver_interface::class);
		$this->config = $this->createMock(\phpbb\config\config::class);
		$this->db_tools = $this->createMock(\phpbb\db\tools\tools_interface::class);
	}

	public function test_v100_schema_depends_on()
	{
		$depends = \vinny\menu\migrations\v100\v100_schema::depends_on();
		$this->assertEquals(['\phpbb\db\migration\data\v330\v330'], $depends);
	}

	public function test_v100_acp_depends_on()
	{
		$depends = \vinny\menu\migrations\v100\v100_acp::depends_on();
		$this->assertEquals(['\vinny\menu\migrations\v100\v100_schema'], $depends);
	}

	public function test_v100_schema_update_schema()
	{
		$migration = new \vinny\menu\migrations\v100\v100_schema($this->config, $this->db, $this->db_tools, './', 'php', 'phpbb_');
		$schema = $migration->update_schema();

		$this->assertArrayHasKey('add_tables', $schema);
		$this->assertArrayHasKey('phpbb_vinny_menu_items', $schema['add_tables']);
		$this->assertArrayHasKey('COLUMNS', $schema['add_tables']['phpbb_vinny_menu_items']);
		$this->assertArrayHasKey('item_id', $schema['add_tables']['phpbb_vinny_menu_items']['COLUMNS']);
	}

	public function test_v100_schema_revert_schema()
	{
		$migration = new \vinny\menu\migrations\v100\v100_schema($this->config, $this->db, $this->db_tools, './', 'php', 'phpbb_');
		$schema = $migration->revert_schema();

		$this->assertArrayHasKey('drop_tables', $schema);
		$this->assertContains('phpbb_vinny_menu_items', $schema['drop_tables']);
	}

	public function test_v100_schema_update_data()
	{
		$migration = new \vinny\menu\migrations\v100\v100_schema($this->config, $this->db, $this->db_tools, './', 'php', 'phpbb_');
		$data = $migration->update_data();

		$this->assertIsArray($data);
		$this->assertTrue(count($data) >= 2);
		$this->assertEquals('config.add', $data[0][0]);
		$this->assertEquals('vinny_menu_enable', $data[0][1][0]);
	}
}
