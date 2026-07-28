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
	'HEADER_MENU_TOGGLE'						=> 'Menu',
	'MENU_GLOBAL_SETTINGS'						=> 'Paramètres du menu d’en-tête',
	'MENU_ENABLE'								=> 'Activer le menu d’en-tête',
	'MENU_ENABLE_EXPLAIN'						=> 'Active ou masque le menu d’en-tête sur votre forum.',
	'MENU_SEARCH'								=> 'Activer la zone de recherche',
	'MENU_SEARCH_EXPLAIN'						=> 'Affiche une zone de recherche à droite du menu et masque la zone de recherche par défaut de l’en-tête.',
	'MENU_CUSTOM_COLOURS'						=> 'Couleurs personnalisées du menu',
	'MENU_CUSTOM_COLOURS_EXPLAIN'				=> 'Personnalisez les couleurs du menu d’en-tête et des panneaux des menus déroulants.',
	'MENU_BG_COLOUR'							=> 'Arrière-plan du menu principal',
	'MENU_BG_COLOUR_EXPLAIN'					=> 'Couleur d’arrière-plan de la barre de navigation principale.',
	'MENU_BG_HOVER_COLOUR'						=> 'Arrière-plan au survol du menu principal',
	'MENU_BG_HOVER_COLOUR_EXPLAIN'				=> 'Couleur d’arrière-plan lors du survol des liens du menu principal.',
	'MENU_TEXT_COLOUR'							=> 'Couleur du texte du menu principal',
	'MENU_TEXT_COLOUR_EXPLAIN'					=> 'Couleur du texte des liens du menu principal.',
	'MENU_TEXT_HOVER_COLOUR'					=> 'Couleur du texte au survol du menu principal',
	'MENU_TEXT_HOVER_COLOUR_EXPLAIN'			=> 'Couleur du texte lors du survol des liens du menu principal.',
	'MENU_SUB_BG_COLOUR'						=> 'Arrière-plan du sous-menu déroulant',
	'MENU_SUB_BG_COLOUR_EXPLAIN'				=> 'Couleur d’arrière-plan du panneau du sous-menu déroulant.',
	'MENU_SUB_TEXT_COLOUR'						=> 'Couleur du texte du sous-menu',
	'MENU_SUB_TEXT_COLOUR_EXPLAIN'				=> 'Couleur du texte des éléments du sous-menu déroulant.',
	'MENU_SUB_BG_HOVER'							=> 'Arrière-plan au survol du sous-menu',
	'MENU_SUB_BG_HOVER_EXPLAIN'					=> 'Couleur d’arrière-plan d’un élément du sous-menu lors du survol.',
	'MENU_SUB_TEXT_HOVER'						=> 'Couleur du texte au survol du sous-menu',
	'MENU_SUB_TEXT_HOVER_EXPLAIN'				=> 'Couleur du texte d’un élément du sous-menu lors du survol.',
	'MENU_SUB_DESC_COLOUR'						=> 'Couleur du texte de description du sous-menu',
	'MENU_SUB_DESC_COLOUR_EXPLAIN'				=> 'Couleur du texte des descriptions affichées dans les sous-menus.',
	'MENU_SETTINGS_SAVED'						=> 'Les paramètres du menu d’en-tête ont été enregistrés avec succès.',
	'MENU_ADD_ITEM'								=> 'Ajouter un élément de menu',
	'MENU_EDIT_ITEM'							=> 'Modifier un élément de menu',
	'MENU_ITEMS_LIST'							=> 'Éléments de menu actuels',
	'MENU_ITEM_NAME'							=> 'Nom du lien',
	'MENU_ITEM_NAME_EXPLAIN'					=> 'Libellé affiché dans la barre de navigation.',
	'MENU_ITEM_DESC'							=> 'Description de l’élément',
	'MENU_ITEM_DESC_EXPLAIN'					=> 'Courte description facultative affichée sous le nom de l’élément dans les sous-menus déroulants (disponible pour les éléments de sous-menu de niveau 2 et de niveau 3, 60 caractères maximum).',
	'MENU_ITEM_NAME_REQUIRED'					=> 'Le nom du lien est obligatoire.',
	'MENU_ITEM_URL'								=> 'URL de destination',
	'MENU_ITEM_URL_EXPLAIN'						=> 'Chemin interne du forum (par ex. index.php, viewforum.php?f=2), URL complète (par ex. https://example.com), ou # pour les éléments servant uniquement d’en-tête de sous-menu sans lien direct.',
	'MENU_ITEM_URL_PLACEHOLDER'					=> 'index.php',
	'MENU_ITEM_PARENT'							=> 'Élément parent',
	'MENU_ITEM_PARENT_EXPLAIN'					=> 'Sélectionnez un élément parent pour créer un menu déroulant.',
	'MENU_ITEM_ICON'							=> 'Icône Font Awesome',
	'MENU_ITEM_ICON_EXPLAIN'					=> 'Classe de l’icône (par ex. fa-home, fa-star, fa-envelope). Consultez les <a href="https://fontawesome.com/v4/icons/" target="_blank" rel="noopener">icônes disponibles</a>. Facultatif.',
	'MENU_ITEM_ICON_PLACEHOLDER'				=> 'fa-home',
	'MENU_ITEM_TARGET'							=> 'Fenêtre cible',
	'MENU_ITEM_TARGET_EXPLAIN'					=> 'Choisissez si le lien doit s’ouvrir dans la même fenêtre ou dans une nouvelle fenêtre.',
	'MENU_TARGET_SELF'							=> 'Même fenêtre (_self)',
	'MENU_TARGET_BLANK'							=> 'Nouvelle fenêtre (_blank)',
	'MENU_ITEM_ENABLED'							=> 'Activé',
	'MENU_ITEM_ENABLED_EXPLAIN'					=> 'Active ou désactive cet élément de menu sur le forum.',
	'MENU_ROOT'									=> '-- Aucun (niveau racine) --',
	'MENU_ITEM_ADDED'							=> 'L’élément de menu a été ajouté avec succès.',
	'MENU_ITEM_UPDATED'							=> 'L’élément de menu a été mis à jour avec succès.',
	'MENU_ITEM_DELETED'							=> 'L’élément de menu a été supprimé avec succès.',
	'CONFIRM_DELETE_MENU_ITEM'					=> 'Êtes-vous sûr de vouloir supprimer cet élément de menu ?',
	'CONFIRM_DELETE_MENU_ITEM_WITH_CHILDREN'	=> 'Êtes-vous sûr de vouloir supprimer cet élément de menu ainsi que tous ses sous-éléments ?',
	'MENU_NO_ITEMS'								=> 'Aucun élément de menu n’a encore été ajouté.',
	'MENU_REORDER_EXPLAIN'						=> 'Faites glisser les éléments à l’aide de l’icône de déplacement (<i class="icon fa fa-arrows fa-fw"></i>) pour modifier leur ordre ou leur hiérarchie entre les éléments parents et les sous-menus.',
	'DRAG_TO_REORDER'							=> 'Faites glisser pour réorganiser ou modifier le niveau',
	'MENU_NO_ORDER_DATA'						=> 'Aucune donnée d’ordre valide reçue.',
	'MENU_HIDE_FOR_GROUPS'						=> 'Masquer pour les groupes d’utilisateurs',
	'MENU_HIDE_FOR_GROUPS_EXPLAIN'				=> 'Sélectionnez les groupes qui ne doivent PAS voir cet élément de menu.',
	'MENU_MAX_DEPTH_REACHED'					=> 'L’élément parent sélectionné dépasse la profondeur maximale autorisée de 3 niveaux.',
	'MENU_SUPPORT_STAR'							=> 'Si vous appréciez cette extension, n’hésitez pas à lui attribuer une étoile sur <a href="https://github.com/vinny/phpbb-menu" target="_blank" rel="noopener"><i class="icon fa fa-github fa-fw" aria-hidden="true"></i>GitHub</a>.',
	'MENU_SUPPORT_DONATE'						=> 'Si vous la trouvez utile, vous pouvez également soutenir son développement en effectuant un <a href="https://ko-fi.com/vinny1" target="_blank" rel="noopener"><i class="icon fa fa-heart fa-fw" aria-hidden="true"></i>don</a>.',
]);
