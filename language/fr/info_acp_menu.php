<?php

/**
*
* Header Menu extension for the phpBB Forum Software package.
*
* @copyright (c) 2026 Vinny
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Language: French [fr]
* Translators: Fred rimbert (https://forums.caforum.fr) (1.0.0) (07.2026)
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
	'ACP_HEADER_MENU_TITLE'		=> 'Menu d’en-tête',
	'ACP_HEADER_MENU_MANAGE'	=> 'Gérer le menu d’en-tête',
	'ACP_HEADER_MENU_EXPLAIN'	=> 'Créez, réorganisez et gérez les éléments personnalisés du menu de navigation.',
]);
