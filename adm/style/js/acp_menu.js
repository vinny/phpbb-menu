(function($) {
	'use strict';

	$(function() {
		// Live icon preview
		var $iconInput = $('input[name="item_icon"]');
		if ($iconInput.length) {
			var $previewContainer = $('.menu-icon-preview');
			var $previewIcon = $previewContainer.find('i');

			var updateIconPreview = function() {
				var value = $iconInput.val().trim();

				if (value === '') {
					$previewIcon.hide();
				} else {
					var displayClass = value.toLowerCase().replace(/^fa\s+/, '').trim();
					if (displayClass.indexOf('fa-') !== 0) {
						displayClass = 'fa-' + displayClass;
					}
					$previewIcon.attr('class', 'icon fa ' + displayClass + ' fa-fw');
					$previewIcon.css('display', 'inline-block');
				}
			};

			$iconInput.on('input', updateIconPreview);

			$iconInput.on('change', function() {
				var value = $iconInput.val().trim();
				if (value !== '') {
					value = value.toLowerCase().replace(/^fa\s+/, '').trim();
					if (value.indexOf('fa-') !== 0) {
						value = 'fa-' + value;
					}
					$iconInput.val(value);
				}
				updateIconPreview();
			});

			$iconInput.closest('form').on('reset', function() {
				setTimeout(updateIconPreview, 50);
			});

			updateIconPreview();
		}

		// Description field visibility based on parent depth (level 1 or 2)
		function checkDescVisibility(selectEl, rowEl) {
			if (!selectEl || !rowEl) {
				return;
			}
			var selectedOpt = selectEl.options[selectEl.selectedIndex];
			var level = selectedOpt ? selectedOpt.getAttribute('data-level') : '0';
			if (level === '1' || level === '2') {
				rowEl.style.display = '';
			} else {
				rowEl.style.display = 'none';
			}
		}

		var editSelect = document.getElementById('edit_parent_id');
		var editRow = document.getElementById('edit_item_desc_row');
		if (editSelect && editRow) {
			checkDescVisibility(editSelect, editRow);
			editSelect.addEventListener('change', function() {
				checkDescVisibility(editSelect, editRow);
			});
		}

		var addSelect = document.getElementById('add_parent_id');
		var addRow = document.getElementById('add_item_desc_row');
		if (addSelect && addRow) {
			checkDescVisibility(addSelect, addRow);
			addSelect.addEventListener('change', function() {
				checkDescVisibility(addSelect, addRow);
			});
		}

		var $dataEl = $('#vinny-menu-data');
		if (!$dataEl.length) {
			return;
		}

		var reorderUrl = $dataEl.attr('data-reorder-url');
		if (reorderUrl && reorderUrl.indexOf('&amp;') !== -1) {
			reorderUrl = reorderUrl.replace(/&amp;/g, '&');
		}

		var showError = function($icon) {
			$icon.removeClass('fa-arrows fa-spinner fa-spin fa-check')
				.addClass('fa-times')
				.css('color', '#e74c3c');
			setTimeout(function() {
				$icon.removeClass('fa-times')
					.addClass('fa-arrows')
					.css('color', '');
			}, 2000);
		};

		function getContainerLevel(olEl) {
			var level = 1;
			var current = olEl;
			while (current && current.id !== 'root-menu-tree') {
				if (current.tagName === 'OL') {
					level++;
				}
				current = current.parentElement;
			}
			return level;
		}

		function getItemSubtreeHeight(liEl) {
			var maxH = 0;
			var childrenOls = Array.prototype.filter.call(liEl.children, function(c) {
				return c.tagName === 'OL';
			});
			childrenOls.forEach(function(ol) {
				var lis = Array.prototype.filter.call(ol.children, function(c) {
					return c.tagName === 'LI';
				});
				lis.forEach(function(childLi) {
					var childH = 1 + getItemSubtreeHeight(childLi);
					if (childH > maxH) {
						maxH = childH;
					}
				});
			});
			return maxH;
		}

		function serializeNestedTree(container, parentId) {
			var items = [];
			var order = 1;
			var children = container.children;

			for (var i = 0; i < children.length; i++) {
				var li = children[i];
				if (li.tagName === 'LI' && li.hasAttribute('data-id')) {
					var itemId = parseInt(li.getAttribute('data-id'), 10);
					items.push({
						id: itemId,
						parent_id: parentId,
						order: order
					});
					order++;

					var subOl = Array.prototype.find.call(li.children, function(child) {
						return child.tagName === 'OL' && child.classList.contains('nested-sortable');
					});

					if (subOl) {
						var subItems = serializeNestedTree(subOl, itemId);
						items = items.concat(subItems);
					}
				}
			}
			return items;
		}

		var saveNestedOrder = function($activeHandle) {
			var rootOl = document.getElementById('root-menu-tree');
			if (!rootOl) return;

			var treeData = serializeNestedTree(rootOl, 0);
			if (treeData.length === 0) return;

			var $icon = $activeHandle.find('i');
			$icon.removeClass('fa-arrows').addClass('fa-spinner fa-spin');

			var formData = new FormData();
			formData.append('items_json', JSON.stringify(treeData));

			fetch(reorderUrl, {
				method: 'POST',
				body: formData
			})
				.then(function(res) { return res.json(); })
				.then(function(data) {
					if (data.success) {
						$icon.removeClass('fa-spinner fa-spin').addClass('fa-check').css('color', '#2ecc71');
						setTimeout(function() {
							$icon.removeClass('fa-check').addClass('fa-arrows').css('color', '');
						}, 1000);
					} else {
						showError($icon);
					}
				})
				.catch(function() {
					showError($icon);
				});
		};

		var nestedElements = document.querySelectorAll('.nested-sortable');
		if (nestedElements.length && typeof Sortable !== 'undefined') {
			nestedElements.forEach(function(el) {
				new Sortable(el, {
					group: 'nested',
					animation: 150,
					fallbackOnBody: true,
					swapThreshold: 0.65,
					handle: '.drag-handle',
					onMove: function(evt) {
						var targetLevel = getContainerLevel(evt.to);
						var draggedHeight = getItemSubtreeHeight(evt.dragged);
						if (targetLevel + draggedHeight > 3) {
							return false;
						}
					},
					onEnd: function(evt) {
						var $activeHandle = $(evt.item).find('.nested-item-row .drag-handle').first();
						if (!$activeHandle.length) {
							$activeHandle = $(evt.item).find('.drag-handle').first();
						}
						saveNestedOrder($activeHandle);
					}
				});
			});
		}
	});

	if (typeof phpbb !== 'undefined' && phpbb.addAjaxCallback) {
		phpbb.addAjaxCallback('menu_toggle_enabled', function(res) {
			var $el = $(this);
			var $icon = $el.find('i.icon');
			$icon.removeClass('fa-check-circle fa-times-circle')
				.addClass(res.icon_class)
				.css('color', res.icon_color)
				.attr('title', res.title)
				.attr('data-original-title', res.title);
		});

		phpbb.addAjaxCallback('row_delete', function(_res) {
			var $li = $(this).closest('li');
			$li.fadeOut(300, function() {
				$li.remove();
			});
		});
	}
})(jQuery);
