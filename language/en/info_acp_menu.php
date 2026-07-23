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
	'ACP_HEADER_MENU_TITLE'		=> 'Header Menu',
	'ACP_MENU_BUILDER_TITLE'	=> 'Header Menu',
	'ACP_HEADER_MENU_MANAGE'	=> 'Manage Header Menu',
	'ACP_MENU_BUILDER_MANAGE'	=> 'Manage Header Menu',
	'ACP_HEADER_MENU_EXPLAIN'	=> 'Create, reorder, and manage custom navigation menu items.',
	'ACP_MENU_BUILDER_EXPLAIN'	=> 'Create, reorder, and manage custom navigation menu items.',
]);
