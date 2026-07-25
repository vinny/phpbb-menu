<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\tests\service;

/**
 * Unit Test for Menu Operator Service
 */
class menu_operator_test extends \PHPUnit\Framework\TestCase
{
	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\user */
	protected $user;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\auth\auth */
	protected $auth;

	/** @var \vinny\menu\service\menu_operator */
	protected $operator;

	protected function setUp(): void
	{
		$this->db = $this->createMock(\phpbb\db\driver\driver_interface::class);
		$this->user = $this->createMock(\phpbb\user::class);
		$this->auth = $this->createMock(\phpbb\auth\auth::class);

		$this->operator = new \vinny\menu\service\menu_operator(
			$this->db,
			'phpbb_vinny_menu_items',
			$this->user,
			$this->auth,
			'./',
			'php'
		);
	}

	public function test_get_item()
	{
		$expected = [
			'item_id'      => 1,
			'item_name'    => 'Home',
			'item_url'     => 'index.php',
			'item_icon'    => 'fa-home',
			'item_enabled' => 1,
		];

		$this->db->expects($this->once())
			->method('sql_query')
			->willReturn('result_handle');

		$this->db->expects($this->once())
			->method('sql_fetchrow')
			->with('result_handle')
			->willReturn($expected);

		$this->db->expects($this->once())
			->method('sql_freeresult')
			->with('result_handle');

		$result = $this->operator->get_item(1);
		$this->assertEquals($expected, $result);
	}

	public function test_get_all_items()
	{
		$items = [
			['item_id' => 1, 'item_name' => 'Home', 'item_order' => 1],
			['item_id' => 2, 'item_name' => 'Rules', 'item_order' => 2],
		];

		$this->db->expects($this->once())
			->method('sql_query')
			->willReturn('result_handle');

		$this->db->expects($this->atLeastOnce())
			->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls($items[0], $items[1], false);

		$result = $this->operator->get_all_items();
		$this->assertCount(2, $result);
		$this->assertEquals('Home', $result[0]['item_name']);
		$this->assertEquals('Rules', $result[1]['item_name']);
	}

	public function test_has_children_returns_true()
	{
		$this->db->expects($this->once())
			->method('sql_query_limit')
			->willReturn('result_handle');

		$this->db->expects($this->once())
			->method('sql_fetchrow')
			->with('result_handle')
			->willReturn(['item_id' => 2]);

		$this->assertTrue($this->operator->has_children(1));
	}

	public function test_has_children_returns_false()
	{
		$this->db->expects($this->once())
			->method('sql_query_limit')
			->willReturn('result_handle');

		$this->db->expects($this->once())
			->method('sql_fetchrow')
			->with('result_handle')
			->willReturn(false);

		$this->assertFalse($this->operator->has_children(1));
	}

	public function test_toggle_enabled()
	{
		$existing_item = [
			'item_id'      => 5,
			'item_enabled' => 1,
		];

		$this->db->expects($this->exactly(2))
			->method('sql_query')
			->willReturn('result_handle');

		$this->db->expects($this->once())
			->method('sql_fetchrow')
			->with('result_handle')
			->willReturn($existing_item);

		$new_state = $this->operator->toggle_enabled(5);
		$this->assertEquals(0, $new_state);
	}

	public function test_get_user_group_ids()
	{
		$this->db->expects($this->once())
			->method('sql_query')
			->willReturn('result_handle');

		$this->db->expects($this->atLeastOnce())
			->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(['group_id' => 2], ['group_id' => 4], false);

		$groups = $this->operator->get_user_group_ids(5);
		$this->assertEquals([2, 4], $groups);
	}

	public function test_get_all_groups()
	{
		$raw_groups = [
			['group_id' => 1, 'group_name' => 'GUESTS', 'group_type' => 3],
			['group_id' => 2, 'group_name' => 'REGISTERED', 'group_type' => 3],
		];

		$this->db->expects($this->once())
			->method('sql_query')
			->willReturn('result_handle');

		$this->db->expects($this->atLeastOnce())
			->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls($raw_groups[0], $raw_groups[1], false);

		$result = $this->operator->get_all_groups('2');
		$this->assertCount(2, $result);
		$this->assertTrue($result[1]['selected']);
	}

	public function test_get_item_level_and_subtree_height()
	{
		$items = [
			1 => ['item_id' => 1, 'parent_id' => 0],
			2 => ['item_id' => 2, 'parent_id' => 1],
			3 => ['item_id' => 3, 'parent_id' => 2],
		];

		$this->assertEquals(1, $this->operator->get_item_level(1, $items));
		$this->assertEquals(2, $this->operator->get_item_level(2, $items));
		$this->assertEquals(3, $this->operator->get_item_level(3, $items));

		$this->assertEquals(2, $this->operator->get_subtree_height(1, $items));
		$this->assertEquals(1, $this->operator->get_subtree_height(2, $items));
		$this->assertEquals(0, $this->operator->get_subtree_height(3, $items));
	}
}
