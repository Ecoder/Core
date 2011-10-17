// tree scripts ##

// loader & script test ##
function ecoder_loaded_tree() {
   thediv = 'load_tree';
   if ( document.removeChild ) {
       var div = document.getElementById( thediv );
           div.parentNode.removeChild( div );           
   } else if ( document.getElementById ) {
       document.getElementById( thediv ).style.display = "none";
   }
}

// onload events ##
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}
