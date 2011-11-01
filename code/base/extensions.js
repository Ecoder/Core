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
	value:function() {
		var args = arguments;
		return this.replace(/{([A-Za-z0-9_]+)}/g, function(match, number) { 
			return typeof args.number != 'undefined' ? args.number : match;
		})
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