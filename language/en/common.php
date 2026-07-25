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
	'MENU_SETTINGS_SAVED'				=> 'Header menu settings saved successfully.',
	'MENU_ADD_ITEM'						=> 'Add Menu Item',
	'MENU_EDIT_ITEM'					=> 'Edit Menu Item',
	'MENU_ITEMS_LIST'					=> 'Current Menu Items',
	'MENU_ITEM_NAME'					=> 'Link Name',
	'MENU_ITEM_NAME_EXPLAIN'			=> 'The label displayed in the navigation bar.',
	'MENU_ITEM_NAME_REQUIRED'			=> 'Link Name is required.',
	'MENU_ITEM_URL'						=> 'Target URL',
	'MENU_ITEM_URL_EXPLAIN'				=> 'Internal board path (e.g. index.php, viewforum.php?f=2), full URL (e.g. https://example.com), or # for dropdown header items without a direct link.',
	'MENU_ITEM_URL_PLACEHOLDER'			=> 'index.php',
	'MENU_ITEM_PARENT'					=> 'Parent Item',
	'MENU_ITEM_PARENT_EXPLAIN'			=> 'Select a parent item to create a sub-menu dropdown. Maximum hierarchy depth is 3 levels.',
	'MENU_ITEM_ICON'					=> 'FontAwesome Icon',
	'MENU_ITEM_ICON_EXPLAIN'			=> 'Icon class (e.g. fa-home, fa-star, fa-envelope). See <a href="https://fontawesome.com/v4/icons/" target="_blank" rel="noopener">available icons</a>. Optional.',
	'MENU_ITEM_ICON_PLACEHOLDER'		=> 'fa-home',
	'MENU_ITEM_TARGET'					=> 'Target Window',
	'MENU_TARGET_SELF'					=> 'Same Window (_self)',
	'MENU_TARGET_BLANK'					=> 'New Window (_blank)',
	'MENU_ITEM_ENABLED'					=> 'Enabled',
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
]);
