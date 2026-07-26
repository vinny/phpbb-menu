<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\tests\event;

/**
 * Unit Test for Main Event Listener
 */
class main_listener_test extends \PHPUnit\Framework\TestCase
{
	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\config\config */
	protected $config;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\user */
	protected $user;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\template\template */
	protected $template;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\vinny\menu\service\menu_operator */
	protected $operator;

	/** @var \vinny\menu\event\main_listener */
	protected $listener;

	protected function setUp(): void
	{
		$this->config = $this->createMock(\phpbb\config\config::class);
		$this->user = $this->createMock(\phpbb\user::class);
		$this->template = $this->createMock(\phpbb\template\template::class);
		$this->operator = $this->createMock(\vinny\menu\service\menu_operator::class);

		$this->listener = new \vinny\menu\event\main_listener(
			$this->config,
			$this->user,
			$this->template,
			$this->operator
		);
	}

	public function test_get_subscribed_events()
	{
		$events = \vinny\menu\event\main_listener::getSubscribedEvents();
		$this->assertArrayHasKey('core.user_setup', $events);
		$this->assertArrayHasKey('core.page_header_after', $events);
	}

	public function test_load_language_on_setup()
	{
		$event = new \Symfony\Component\EventDispatcher\GenericEvent();
		$event['lang_set_ext'] = [];

		$this->listener->load_language_on_setup($event);

		$lang_set_ext = $event['lang_set_ext'];
		$this->assertCount(1, $lang_set_ext);
		$this->assertEquals('vinny/menu', $lang_set_ext[0]['ext_name']);
		$this->assertEquals('common', $lang_set_ext[0]['lang_set']);
	}

	public function test_render_menu_disabled()
	{
		$this->config->expects($this->any())
			->method('offsetExists')
			->with('vinny_menu_enable')
			->willReturn(true);

		$this->config->expects($this->any())
			->method('offsetGet')
			->with('vinny_menu_enable')
			->willReturn(0);

		$this->template->expects($this->never())
			->method('assign_vars');

		$this->operator->expects($this->never())
			->method('get_visible_menu_tree');

		$this->listener->render_menu();
	}

	public function test_render_menu_enabled_with_search()
	{
		$this->config->expects($this->any())
			->method('offsetExists')
			->willReturn(true);

		$this->config->expects($this->any())
			->method('offsetGet')
			->willReturnOnConsecutiveCalls(1, 1);

		$this->operator->expects($this->once())
			->method('get_visible_menu_tree')
			->willReturn([]);

		$this->template->expects($this->once())
			->method('assign_vars');

		$this->listener->render_menu();
	}
}
