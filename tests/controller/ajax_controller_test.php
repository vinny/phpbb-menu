<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\tests\controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Unit Test for AJAX Controller
 */
class ajax_controller_test extends \PHPUnit\Framework\TestCase
{
	/** @var \PHPUnit\Framework\MockObject\MockObject|\vinny\menu\service\menu_operator */
	protected $operator;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\language\language */
	protected $language;

	/** @var \vinny\menu\controller\ajax_controller */
	protected $controller;

	protected function setUp(): void
	{
		$this->operator = $this->createMock(\vinny\menu\service\menu_operator::class);
		$this->language = $this->createMock(\phpbb\language\language::class);

		$this->controller = new \vinny\menu\controller\ajax_controller(
			$this->operator,
			$this->language
		);
	}

	public function test_reorder_with_valid_hierarchy()
	{
		$items = [
			['id' => 1, 'parent_id' => 0, 'order' => 1],
			['id' => 2, 'parent_id' => 1, 'order' => 1],
		];

		$request = new Request([], [], [], [], [], [], json_encode(['items' => $items]));

		$this->operator->expects($this->once())
			->method('update_hierarchy')
			->with($items);

		$response = $this->controller->reorder($request);
		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function test_reorder_returns_error_when_payload_empty()
	{
		$request = new Request();

		$this->language->expects($this->once())
			->method('lang')
			->with('MENU_NO_ORDER_DATA')
			->willReturn('No reorder data provided.');

		$response = $this->controller->reorder($request);
		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $response);
		$this->assertEquals(400, $response->getStatusCode());
	}
}
