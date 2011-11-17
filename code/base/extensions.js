/*
 * Features some extensions to ECMAScript 5/Javascript 1.8.5
 */

/*
 * Object.defineProperty: https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Object/defineProperty
 */

/*
 * String.format(var v1,...)
 * Format w {0}, {1}, ...
 * Based on: http://stackoverflow.com/questions/610406/javascript-equivalent-to-printf-string-format/4673436#4673436
 */
Object.defineProperty(String.prototype,'format',{
	value:function(replacements) {
		var result=this;
		$.each(replacements,function(k,v) {
			var regex=new RegExp("{"+k+"}","g");
			result=result.replace(regex,v);
		});
		return result;
	}
});

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