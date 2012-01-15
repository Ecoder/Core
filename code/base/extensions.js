/*
 * Features some extensions to ECMAScript 5/Javascript 1.8.5
 */

/*
 * Array.last
 */
Object.defineProperty(Array.prototype, 'lastVal', {
	value:function() {
		return this[this.length - 1];
	}
});

/*
 * $().center
 */
jQuery.fn.center = function () {
	this.css("position","absolute");
	this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
	this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
	return this;
};
