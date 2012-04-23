/* Spring Message jQuery Plugin
 *  Daniel Schultz 2011
 *  Version 1.0
 */

(function( $ ) {
	$.fn.springMessage = function(opts) {
		var defaults = {
			heightOffset: 0,
			minTopOffset: 0,
		}
		opts = $.extend(defaults, opts);
		
		var springMessage = this.html();
		var springTrigger = this.offset().top;
		this.empty();
		
		var currentState = 0; // 0: Closed; 1: Open; 2: Perminantly Closed
		var $springMessageEl = $("<div/>");
		var $springMessageContainerEl = $("<div/>");
		var $springMessageCloseEl = $("<div/>");
		
		$springMessageContainerEl.css("position","fixed");
		$springMessageContainerEl.css("overflow", "hidden");
		
		$springMessageEl.appendTo($springMessageContainerEl);
		$springMessageEl.html(springMessage);
		$springMessageEl.addClass("springMessage");
		$springMessageEl.css("position", "relative");
		
		$springMessageCloseEl.addClass("springMessageClose");
		
		$springMessageCloseEl.appendTo($springMessageEl);
		$springMessageEl.appendTo($springMessageContainerEl);
		$springMessageContainerEl.appendTo("body");
		
		$springMessageEl.css("left", $springMessageEl.width());
		
		// Activate the spring
		$(window).bind('scroll resize', function() {
			$springMessageContainerEl.css("left", $(window).width() - $springMessageEl.width());
			$springMessageContainerEl.css("top", Math.max(opts.minTopOffset, $(window).height() - $springMessageEl.height() - opts.heightOffset));
			var visibleBottom = $(document).scrollTop() + $(window).height();
			if(visibleBottom >= springTrigger && currentState == 0) {
				// Open
				$springMessageEl.animate({left: 0}, 300);
				currentState = 1;
			}
			else if(visibleBottom < springTrigger && currentState == 1) {
				// Close
				$springMessageEl.animate({left: $(".springMessage").css("width")}, 300)
				currentState = 0;
			}
		});
		$(window).scroll();
		
		// Activate the "close" button
		$springMessageCloseEl.bind('click', function() {
			// Close
			$springMessageEl.animate({left: $(".springMessage").css("width")}, 300)
			currentState = 2;
		});
		
		return this;
	};
})( jQuery );