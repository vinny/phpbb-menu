<?php
/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'HEADER_MENU_TOGGLE'				=> 'Menu',
	'MENU_GLOBAL_SETTINGS'				=> 'Header Menu Settings',
	'MENU_ENABLE'						=> 'Enable Header Menu',
	'MENU_ENABLE_EXPLAIN'				=> 'Master toggle to display or hide the header menu on your board.',
	'MENU_SEARCH'						=> 'Enable Search Box',
	'MENU_SEARCH_EXPLAIN'				=> 'Displays a search box on the right side of the menu and hides the default header search box.',
	'MENU_CUSTOM_COLOURS'				=> 'Custom menu colours',
	'MENU_CUSTOM_COLOURS_EXPLAIN'		=> 'Customise the colours of the header menu and dropdown panels.',
	'MENU_BG_COLOUR'					=> 'Main menu background',
	'MENU_BG_COLOUR_EXPLAIN'			=> 'Background colour of the main navigation menu bar.',
	'MENU_BG_HOVER_COLOUR'				=> 'Main menu item hover background',
	'MENU_BG_HOVER_COLOUR_EXPLAIN'		=> 'Background colour when hovering over main menu links.',
	'MENU_TEXT_COLOUR'					=> 'Main menu text colour',
	'MENU_TEXT_COLOUR_EXPLAIN'			=> 'Text colour of main menu links.',
	'MENU_TEXT_HOVER_COLOUR'			=> 'Main menu hover text colour',
	'MENU_TEXT_HOVER_COLOUR_EXPLAIN'	=> 'Text colour when hovering over main menu links.',
	'MENU_SUB_BG_COLOUR'				=> 'Submenu dropdown background',
	'MENU_SUB_BG_COLOUR_EXPLAIN'		=> 'Background colour of the dropdown submenu panel.',
	'MENU_SUB_TEXT_COLOUR'				=> 'Submenu text colour',
	'MENU_SUB_TEXT_COLOUR_EXPLAIN'		=> 'Text colour of item titles in the dropdown submenu.',
	'MENU_SUB_BG_HOVER'					=> 'Submenu item hover background',
	'MENU_SUB_BG_HOVER_EXPLAIN'			=> 'Background colour of a submenu item card on hover.',
	'MENU_SUB_TEXT_HOVER'				=> 'Submenu item hover text colour',
	'MENU_SUB_TEXT_HOVER_EXPLAIN'		=> 'Text colour of a submenu item title on hover.',
	'MENU_SUB_DESC_COLOUR'				=> 'Submenu description text colour',
	'MENU_SUB_DESC_COLOUR_EXPLAIN'		=> 'Text colour of the subtitle descriptions in submenus.',
	'MENU_SETTINGS_SAVED'				=> 'Header menu settings saved successfully.',
	'MENU_ADD_ITEM'						=> 'Add Menu Item',
	'MENU_EDIT_ITEM'					=> 'Edit Menu Item',
	'MENU_ITEMS_LIST'					=> 'Current Menu Items',
	'MENU_ITEM_NAME'					=> 'Item name',
	'MENU_ITEM_NAME_EXPLAIN'			=> 'The label displayed in the navigation bar.',
	'MENU_ITEM_DESC'					=> 'Item description',
	'MENU_ITEM_DESC_EXPLAIN'			=> 'Optional short subtitle displayed under the item name in dropdown submenus (available for level 2 and level 3 submenu items, maximum 60 characters).',
	'MENU_ITEM_NAME_REQUIRED'			=> 'Link Name is required.',
	'MENU_ITEM_URL'						=> 'Target URL',
	'MENU_ITEM_URL_EXPLAIN'				=> 'Internal board path (e.g. index.php, viewforum.php?f=2), full URL (e.g. https://example.com), or # for dropdown header items without a direct link.',
	'MENU_ITEM_URL_PLACEHOLDER'			=> 'index.php',
	'MENU_ITEM_PARENT'					=> 'Parent Item',
	'MENU_ITEM_PARENT_EXPLAIN'			=> 'Select a parent item to create a sub-menu dropdown. Maximum hierarchy depth is 3 levels.',
	'MENU_ITEM_ICON'					=> 'Icon',
	'MENU_ITEM_ICON_EXPLAIN'			=> 'FontAwesome icon class (e.g. fa-home, fa-star, fa-envelope). See <a href="https://fontawesome.com/v4/icons/" target="_blank" rel="noopener">available icons</a>. Optional.',
	'MENU_ITEM_ICON_PLACEHOLDER'		=> 'fa-home',
	'MENU_ITEM_TARGET'					=> 'Target Window',
	'MENU_ITEM_TARGET_EXPLAIN'			=> 'Choose whether the link opens in the same window or a new window.',
	'MENU_TARGET_SELF'					=> 'Same Window',
	'MENU_TARGET_BLANK'					=> 'New Window',
	'MENU_ITEM_ENABLED'					=> 'Enabled',
	'MENU_ITEM_ENABLED_EXPLAIN'			=> 'Enable or disable this menu item on the board.',
	'MENU_ROOT'							=> '-- None (Root Level) --',
	'MENU_ITEM_ADDED'					=> 'Menu item added successfully.',
	'MENU_ITEM_UPDATED'					=> 'Menu item updated successfully.',
	'MENU_ITEM_DELETED'					=> 'Menu item deleted successfully.',
	'CONFIRM_DELETE_MENU_ITEM'			=> 'Are you sure you want to delete this menu item?',
	'CONFIRM_DELETE_MENU_ITEM_WITH_CHILDREN' 	=> 'Are you sure you want to delete this menu item and ALL of its sub-items?',
	'MENU_NO_ITEMS'						=> 'No menu items have been added yet.',
	'MENU_REORDER_EXPLAIN'				=> 'Drag and drop items using the handle icon (<i class="icon fa fa-arrows fa-fw"></i>) to reorder or change hierarchy between parents and submenus. Maximum hierarchy depth is 3 levels.',
	'DRAG_TO_REORDER'					=> 'Drag to reorder or change level',
	'MENU_NO_ORDER_DATA'				=> 'No valid order data received.',
	'MENU_HIDE_FOR_GROUPS'				=> 'Hide for User Groups',
	'MENU_HIDE_FOR_GROUPS_EXPLAIN'		=> 'Select groups that should NOT see this menu item.',
	'MENU_MAX_DEPTH_REACHED'			=> 'The selected parent item exceeds the maximum menu depth limit of 3 levels.',
	'MENU_SUPPORT_STAR'					=> 'If you like this extension, please give it a star on <a href="https://github.com/vinny/phpbb-menu" target="_blank" rel="noopener"><i class="icon fa fa-github fa-fw" aria-hidden="true"></i>GitHub</a>.',
	'MENU_SUPPORT_DONATE'				=> 'If you find it useful, you can also support its development with an optional <a href="https://ko-fi.com/vinny1" target="_blank" rel="noopener"><i class="icon fa fa-heart fa-fw" aria-hidden="true"></i>donation</a>.',
]);
