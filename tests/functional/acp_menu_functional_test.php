<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\tests\functional;

/**
 * Functional ACP Test for Header Menu Extension
 */
class acp_menu_functional_test extends \PHPUnit\Framework\TestCase
{
	/** @var \vinny\menu\acp\main_module */
	protected $module;

	protected function setUp(): void
	{
		$this->module = new \vinny\menu\acp\main_module();
	}

	public function test_module_instantiation()
	{
		$this->assertInstanceOf(\vinny\menu\acp\main_module::class, $this->module);
	}
}
