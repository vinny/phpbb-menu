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

if (!function_exists('generate_link_hash'))
{
	function generate_link_hash($link_name)
	{
		global $user;
		if (!isset($user->data["hash_$link_name"]))
		{
			$salt = $user->data['user_form_salt'] ?? 'salt';
			$user->data["hash_$link_name"] = substr(sha1($salt . $link_name), 0, 8);
		}
		return $user->data["hash_$link_name"];
	}
}

if (!function_exists('check_link_hash'))
{
	function check_link_hash($token, $link_name)
	{
		return $token === generate_link_hash($link_name);
	}
}

/**
 * Unit Test for AJAX Controller
 */
class ajax_controller_test extends \PHPUnit\Framework\TestCase
{
	/** @var \PHPUnit\Framework\MockObject\MockObject|\vinny\menu\service\menu_operator */
	protected $operator;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\language\language */
	protected $language;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\auth\auth */
	protected $auth;

	/** @var \vinny\menu\controller\ajax_controller */
	protected $controller;

	/** @var string */
	protected $valid_hash;

	protected function setUp(): void
	{
		global $user;
		if (!isset($user) || !is_object($user))
		{
			$user = new \stdClass();
			$user->data = [];
		}

		if (!isset($user->data) || !is_array($user->data))
		{
			$user->data = [];
		}

		if (empty($user->data['user_form_salt']))
		{
			$user->data['user_form_salt'] = 'test_salt';
		}

		$this->valid_hash = generate_link_hash('vinny_menu_reorder');

		$this->operator = $this->createMock(\vinny\menu\service\menu_operator::class);
		$this->language = $this->createMock(\phpbb\language\language::class);
		$this->auth = $this->createMock(\phpbb\auth\auth::class);

		$this->auth->expects($this->any())
			->method('acl_get')
			->with('a_board')
			->willReturn(true);

		$this->controller = new \vinny\menu\controller\ajax_controller(
			$this->operator,
			$this->language,
			$this->auth
		);
	}

	public function test_reorder_returns_403_when_hash_invalid()
	{
		$request = new Request(['hash' => 'invalid_hash_value']);

		$this->language->expects($this->once())
			->method('lang')
			->with('FORM_INVALID')
			->willReturn('The submitted form was invalid.');

		$response = $this->controller->reorder($request);
		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $response);
		$this->assertEquals(403, $response->getStatusCode());
	}

	public function test_reorder_with_valid_hierarchy()
	{
		$items = [
			['id' => 1, 'parent_id' => 0, 'order' => 1],
			['id' => 2, 'parent_id' => 1, 'order' => 1],
		];

		$request = new Request(['hash' => $this->valid_hash], [], [], [], [], [], json_encode(['items' => $items]));

		$this->operator->expects($this->once())
			->method('update_hierarchy')
			->with($items);

		$response = $this->controller->reorder($request);
		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function test_reorder_returns_error_when_payload_empty()
	{
		$request = new Request(['hash' => $this->valid_hash]);

		$this->language->expects($this->once())
			->method('lang')
			->with('MENU_NO_ORDER_DATA')
			->willReturn('No reorder data provided.');

		$response = $this->controller->reorder($request);
		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $response);
		$this->assertEquals(400, $response->getStatusCode());
	}
}
