jQuery(function($) {
	// For older IE implementations - SUCK!
	// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Object/keys (expanded for readability)
	if (!Object.keys) {
		Object.keys = function(o){
			if (o !== Object(o)) {
				throw new TypeError('Object.keys called on non-object');
			}
			var ret=[],p;
			for(p in o) {
				if(Object.prototype.hasOwnProperty.call(o,p)) {
					ret.push(p);
				}
			}
			return ret;
		};
	}
	
// Objects
	var CF = CF || {};

// attach to add buttons for image search and services
	$('.cfp-add-link').popover({
		my: 'right top',
		at: 'center bottom',
		offset: '27px 0',
		collision: 'none none'
	}).bind('popover-show', function() {
		$(this).addClass('open');
	}).bind('popover-hide', function() {
		$(this).removeClass('open');
	});
	
// Image selector

	CF.imgs = function($) {
		var $search = $('#cfp-img-search-popover'),
			$imgActions = $('#cfp-popover-image-actions'),
			$list = $('#cfp-about-imgs-input ul');
		
		// add image to carousel button handler
		$('#cfp-add-img').bind('popover-show', function() {
			CF.imgs.initSearch();
			$search.find('input#cfp-img-search-term').focus();
		}).bind('popover-hide', function() {
			$search.find('input#cfp-img-search-term').val('');
		});
		
		$('.cfp-del-image').live('click', function(e) {
			if (confirm(cfcp_about_settings.image_del_confirm)) {
				CF.imgs.removeImage(this);
			}
			e.preventDefault();
			e.stopPropagation();
		});
		
		// init sortables
		$list.sortable({
			axis: 'x',
			items: 'li',
			cursor: 'crosshair',
			helper: 'clone',
			placeholder: 'cfp-about-img-placeholder'
		});
				
		return {
			isVisible: function () {
				return $search.is(':visible');
			},
			
			initSearch: function() {
				$search.find('input#cfp-img-search-term').unbind().oTypeAhead({
					searchParams: {
						action: 'cfcp_about',
						cfcp_about_action: 'cfcp_image_search',
						cfcp_search_exclude: this.getSelectedIds()
					},
					form: '#cfp-img-search',
					url: ajaxurl,
					target: '#cfp-img-search-results',
					resultsCallback: function(target) {
						$('.cfp-search-result img').unbind('click').click(function() {
							CF.imgs.selectImg($(this).closest('li').clone());
						});
						$(target).unbind().bind('o-typeahead-select', function() {
							CF.imgs.selectImg($(this).find('li.otypeahead-current').clone());
						});
					}
				});

				// Hide popup when esc key is pressed and search results display is empty
				$search.on('keydown', '#cfp-img-search-term', function(e) {
					if (e.which == 27 && $('#cfp-img-search-results').is(':hidden')) {
						$('body').click();
						return false;
					}
				});
			},
			
			getSelectedIds: function() {
				var ids = [];
				$list.find('li input[type="hidden"]').each(function() {
					ids.push($(this).val());
				});
				return ids;
			},
			
			refreshSortables: function() {
				$list.sortable('refresh');
			},
			
			handleEmptyLi: function() {
				if ($list.find('img').size() > 0) {
					$list.find('li.no-image-item').hide();
				}
				else {
					$list.find('li.no-image-item').show();
				}
			},
			
			selectImg: function(imgLi) {
				$(imgLi).attr('class', false).appendTo($list);
				this.handleEmptyLi();
				this.refreshSortables();
				$search.find('input#cfp-img-search-term').val('').focus();
			},
			
			removeImage: function(del) {
				$(del).closest('li')
					.animate({'width': 0}, 500, function() {
						$(this).remove();
						CF.imgs.handleEmptyLi();
					});
			}
			
		};
	}(jQuery);

// Init
	
	// hide all pop-overs when hitting ESC
	$(document).keyup(function(e) {
		switch (e.which) {
			case 27: // esc
				$('body').click();
				break;
		}
	});

// this is a good idea but pop-over menus get disconnected when the animation happens
// 	$('.cf-updated-message-fade')
// 		.animate({'opacity': 1.0}, 8000) // faux timeout, animates nothing for 8 seconds
// 		.slideUp('slow');
});
