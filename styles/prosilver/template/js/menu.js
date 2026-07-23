/**
* Header Menu extension responsive JS
*/
(function($) {
	'use strict';

	$(document).ready(function() {
		// Toggle main responsive menu
		$(document).on('click', '.vinny-mobile-toggle', function(e) {
			e.preventDefault();
			var $container = $(this).closest('.vinny-header-menu');
			$container.toggleClass('responsive-open');
		});

		// Submenu click toggle in mobile view or anchor links
		$(document).on('click', '.vinny-header-menu .has-dropdown > a, .vinny-header-menu .has-submenu > a', function(e) {
			var $parent = $(this).parent();
			var href = $(this).attr('href');
			var isMobile = $(window).width() <= 700;

			if (isMobile || href === '#' || !href) {
				if ($parent.children('.vinny-dropdown-menu, .vinny-subdropdown-menu').length > 0) {
					e.preventDefault();
					e.stopPropagation();
					$parent.toggleClass('open');
					return false;
				}
			}
		});
	});
})(jQuery);
