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
	'HEADER_MENU_TOGGLE'					=> 'Menu',
	'MENU_GLOBAL_SETTINGS'					=> 'Configurações do Header Menu',
	'MENU_ENABLE'							=> 'Ativar Menu Superior',
	'MENU_ENABLE_EXPLAIN'					=> 'Opção principal para exibir ou ocultar o menu superior no seu fórum.',
	'MENU_SEARCH'							=> 'Ativar Campo de Busca',
	'MENU_SEARCH_EXPLAIN'					=> 'Exibe um campo de busca no canto direito do menu e oculta o campo de busca padrão do cabeçalho.',
	'MENU_SETTINGS_SAVED'					=> 'Configurações do menu superior salvas com sucesso.',
	'MENU_ADD_ITEM'							=> 'Adicionar Item de Menu',
	'MENU_EDIT_ITEM'						=> 'Editar Item de Menu',
	'MENU_ITEMS_LIST'						=> 'Itens do Menu Atuais',
	'MENU_ITEM_NAME'						=> 'Nome do Link',
	'MENU_ITEM_NAME_EXPLAIN'				=> 'O rótulo exibido na barra de navegação.',
	'MENU_ITEM_NAME_REQUIRED'				=> 'O Nome do Link é obrigatório.',
	'MENU_ITEM_URL'							=> 'URL de Destino',
	'MENU_ITEM_URL_EXPLAIN'					=> 'Caminho interno do fórum (ex: index.php, viewforum.php?f=2), URL completa (ex: https://exemplo.com) ou # para itens de menu suspenso sem link direto.',
	'MENU_ITEM_URL_PLACEHOLDER'				=> 'index.php',
	'MENU_ITEM_PARENT'						=> 'Item Pai',
	'MENU_ITEM_PARENT_EXPLAIN'				=> 'Selecione um item pai para criar um submenu suspenso. A profundidade máxima da hierarquia é de 3 níveis.',
	'MENU_ITEM_ICON'						=> 'Ícone',
	'MENU_ITEM_ICON_EXPLAIN'				=> 'Classe de ícone FontAwesome (ex: fa-home, fa-star, fa-envelope). Veja os <a href="https://fontawesome.com/v4/icons/" target="_blank" rel="noopener">ícones disponíveis</a>. Opcional.',
	'MENU_ITEM_ICON_PLACEHOLDER'			=> 'fa-home',
	'MENU_ITEM_TARGET'						=> 'Janela de Destino',
	'MENU_ITEM_TARGET_EXPLAIN'				=> 'Escolha se o link será aberto na mesma janela ou em uma nova janela.',
	'MENU_TARGET_SELF'						=> 'Mesma Janela',
	'MENU_TARGET_BLANK'						=> 'Nova Janela',
	'MENU_ITEM_ENABLED'						=> 'Ativado',
	'MENU_ITEM_ENABLED_EXPLAIN'				=> 'Ativar ou desativar este item de menu no fórum.',
	'MENU_ROOT'								=> '-- Nenhum (Nível Principal) --',
	'MENU_ITEM_ADDED'						=> 'Item de menu adicionado com sucesso.',
	'MENU_ITEM_UPDATED'						=> 'Item de menu atualizado com sucesso.',
	'MENU_ITEM_DELETED'						=> 'Item de menu excluído com sucesso.',
	'CONFIRM_DELETE_MENU_ITEM'				=> 'Tem certeza de que deseja excluir este item de menu?',
	'CONFIRM_DELETE_MENU_ITEM_WITH_CHILDREN' => 'Tem certeza de que deseja excluir este item de menu e TODOS os seus subitens associados?',
	'MENU_NO_ITEMS'							=> 'Nenhum item de menu foi adicionado ainda.',
	'MENU_REORDER_EXPLAIN'					=> 'Arraste e solte os itens usando o ícone de alça (<i class="icon fa fa-arrows fa-fw"></i>) para reordenar ou alterar a hierarquia entre pai e submenus. A profundidade máxima da hierarquia é de 3 níveis.',
	'DRAG_TO_REORDER'						=> 'Arraste para reordenar ou alterar nível',
	'MENU_NO_ORDER_DATA'					=> 'Nenhum dado de ordenação válido foi recebido.',
	'MENU_HIDE_FOR_GROUPS'					=> 'Ocultar para Grupos de Usuários',
	'MENU_HIDE_FOR_GROUPS_EXPLAIN'			=> 'Selecione os grupos que NÃO devem ver este item de menu.',
	'MENU_MAX_DEPTH_REACHED'				=> 'O item pai selecionado excede o limite máximo de 3 níveis de profundidade do menu.',
	'MENU_SUPPORT_STAR'						=> 'Se você gosta desta extensão, por favor dê uma estrela no <a href="https://github.com/vinny/phpbb-menu" target="_blank" rel="noopener"><i class="icon fa fa-github fa-fw" aria-hidden="true"></i>GitHub</a>.',
	'MENU_SUPPORT_DONATE'					=> 'Se você a considera útil, também pode apoiar o desenvolvimento com uma <a href="https://ko-fi.com/vinny1" target="_blank" rel="noopener"><i class="icon fa fa-heart fa-fw" aria-hidden="true"></i>doação</a> (opcional).',
]);
