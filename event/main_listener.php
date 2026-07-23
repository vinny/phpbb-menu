<?php

/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\menu\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event Listener for Header Menu
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \vinny\menu\service\menu_operator */
	protected $menu_operator;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\user                       $user
	 * @param \phpbb\template\template          $template
	 * @param \vinny\menu\service\menu_operator $menu_operator
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\vinny\menu\service\menu_operator $menu_operator
	)
	{
		$this->config = $config;
		$this->user = $user;
		$this->template = $template;
		$this->menu_operator = $menu_operator;
	}

	/**
	 * Assign subscribed events.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'			=> 'load_language_on_setup',
			'core.page_header_after'	=> 'render_menu',
		];
	}

	/**
	 * Load language file during user setup.
	 *
	 * @param \phpbb\event\data $event
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'vinny/menu',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Render header navigation menu on page setup.
	 *
	 * @param \phpbb\event\data $event
	 */
	public function render_menu($event = null)
	{
		$enabled = isset($this->config['vinny_menu_enable']) ? (bool) $this->config['vinny_menu_enable'] : true;
		if (!$enabled)
		{
			return;
		}

		$header_items = $this->menu_operator->get_visible_menu_tree();

		$this->template->assign_vars([
			'S_VINNY_MENU_ENABLED' => true,
		]);

		// Assign Header Menu Items
		foreach ($header_items as $item)
		{
			$block_data = [
				'ID'			=> $item['item_id'],
				'NAME'			=> $item['item_name'],
				'URL'			=> $item['formatted_url'],
				'ICON'			=> $item['item_icon'],
				'TARGET'		=> $item['item_target'],
				'HAS_CHILDREN'	=> !empty($item['children']),
			];

			$this->template->assign_block_vars('vinny_menu_header', $block_data);

			if (!empty($item['children']))
			{
				foreach ($item['children'] as $child)
				{
					$this->template->assign_block_vars('vinny_menu_header.children', [
						'ID'			=> $child['item_id'],
						'NAME'			=> $child['item_name'],
						'URL'			=> $child['formatted_url'],
						'ICON'			=> $child['item_icon'],
						'TARGET'		=> $child['item_target'],
						'HAS_CHILDREN'	=> !empty($child['children']),
					]);

					if (!empty($child['children']))
					{
						foreach ($child['children'] as $subchild)
						{
							$this->template->assign_block_vars('vinny_menu_header.children.subchildren', [
								'ID'		=> $subchild['item_id'],
								'NAME'		=> $subchild['item_name'],
								'URL'		=> $subchild['formatted_url'],
								'ICON'		=> $subchild['item_icon'],
								'TARGET'	=> $subchild['item_target'],
							]);
						}
					}
				}
			}
		}
	}
}
