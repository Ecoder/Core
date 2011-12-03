/* ecoder tabs */

function tabber_build ( controllerName, tabElement, panelElement ) {

   this.Name = controllerName;
   this.tabNumber = 0;
   this.currentHighPanel = null;
   this.currentHighTab = null;
   this.panelContainer = panelElement;
   this.tabContainer = tabElement;
   this.lowTabStyle = 'lowTab';
   this.highTabStyle = 'highTab';

   // add tab ##
   this.add = function ( tabName, iframe ) {

      // turn off all focused tabs ##
      this.defocus ();

      var tabID = this.Name + 'tab_' + this.tabNumber;
      var panelID = this.Name + 'panel_' + this.tabNumber;
      var panel = document.createElement ( 'div' );
      panel.style.left = '0px';
      panel.style.top = '0px';
      panel.style.width = '100%';
      panel.style.height = '100%';
      panel.style.display = 'none';
      panel.tabNum = this.tabNumber;
      panel.id = panelID;

      if ( this.panelContainer.insertAdjacentElement == null ) {
         this.panelContainer.appendChild ( panel )
      } else {
         this.panelContainer.insertAdjacentElement( "beforeEnd", panel ); // Internet Explorer ##
      }

      var cell = this.tabContainer.insertCell ( this.tabContainer.cells.length );
      cell.id = tabID;
      cell.className = this.lowTabStyle;
      //cell.title = path + tabName; // file + path ##
      cell.title = tabName; // just file ##
      cell.tabNum = this.tabNumber;
      cell.onclick = this.clicked;
      cell.innerHTML = tabName;
      cell.panelObj = this;
      this.click_el ( cell );

      // update trackers ##
      top.ecoder_track ( 'iframe', iframe ); // track top iframe ##
      top.ecoder_track ( 'tab', this.tabNumber ); // track top tab ##

      // update tab array ##


      this.tabNumber++; // iterate ##
      return panel;

   }

   // click event ##
   this.clicked = function( event ) {
   	if(!event) var event=window.event;
      var el = ( event.target == null ) ? window.event.srcElement : event.target; // internet explorer : other ##
      el.panelObj.click_el ( el );

   }

   // post-click event ##
   this.click_el = function ( element ) {

      if ( this.currentHighTab == element ) // clicked current tab, do nothing ##
         return;

      if ( this.currentHighTab != null ) // hide tab highlight ##
        this.currentHighTab.className = this.lowTabStyle;
        this.defocus(); // holy hack -- set all focused tabs to no highlight ##

      if ( this.currentHighPanel != null ) // hide panel ##
         this.currentHighPanel.style.display = 'none';

      this.currentHighPanel = null; // empty HL var ##
      this.currentHighTab = null; // empty tab var ##

      if ( element == null ) // stop if element not found ##
        return;

        this.currentHighPanel = document.getElementById( this.Name + 'panel_' + element.tabNum ); // set new highlight var ##
        var tab_title = document.getElementById( this.Name + 'tab_' + element.tabNum ).title; // get title from title tag ##
        top.ecoder_html_title ( tab_title ); // set page title ##

        // get id of iframe from panel ##
        var obj=document.getElementById( this.Name + 'panel_' + element.tabNum );
        for ( var c=0; c<obj.childNodes.length; c++ ) {
            if ( obj.childNodes[c].nodeType == 1 ) {
                //alert(obj.childNodes[c].id);
                var track_iframe = obj.childNodes[c].id;
            }
        }

        // update trackers ##
        top.ecoder_track ( 'iframe', track_iframe ); // track top iframe ##
        top.ecoder_track ( 'tab', element.tabNum ); // track tab ID ##

      if ( this.currentHighPanel == null ) { // if empty still, stop ##
         return;

      } else { // assign values to new HL element ##
          this.currentHighTab = element;
          this.currentHighTab.className = this.highTabStyle;
          this.currentHighPanel.style.display = '';

      }
   }

    // focus tab ##
    this.focus = function ( id, iframe ) {

        // defocus current tab ##
        this.currentHighTab.className = this.lowTabStyle; // direct try ##

        // holy hack -- set all focused tabs to no highlight ##
        this.defocus ();

        if ( id == null ) { // no element passed ##
            return;
            //alert ( "1 > no id passed" );
        } else { // element passed ##

            var focus_tab = this.Name + 'tab_' +id; // tab to focus ##
            var focus_panel = this.Name + 'panel_' +id; // panel to focus ##
            //alert ( "2 > focus tab: "+focus_tab );
            if ( this.currentHighTab == focus_tab ) { // clicked focused tab, do nothing ##
                //alert ( '3 > no focus required' );
                return;

            } else {

                if ( this.currentHighPanel != null ) { // hide live panel ##

                    // hide focused tab & panel ##
                    this.currentHighPanel.style.display = 'none';

                    // empty variables ##
                    this.currentHighPanel = null; // empty HL var ##
                    this.currentHighTab = null; // empty tab var ##

                    // get new focus panel ##
                    this.currentHighPanel = document.getElementById( focus_panel ); // set new highlight var ##
                    this.currentHighTab = document.getElementById( focus_tab ); // set new highlight var ##

                    if ( this.currentHighPanel == null ) { // if empty still, stop ##
                        return;

                    } else { // assign values to new HL element ##
                        this.currentHighTab = focus_tab;
                        document.getElementById( focus_tab ).className = this.highTabStyle;
                        this.currentHighPanel.style.display = '';
                        //alert ( "4 > focus tab: "+focus_tab );
                    }
                }

            // update trackers ##
            top.ecoder_track ( 'iframe', iframe ); // track top iframe ##
            top.ecoder_track ( 'tab', id ); // track top tab ##

            }
        }
    }

    // defocus tabs ##
    this.defocus = function () {

        // class function ##
        function getElementsByClassName( classname, node )  {
            if ( !node ) node = document.getElementsByTagName("body")[0];
            var a = [];
            var re = new RegExp('\\b' + classname + '\\b');
            var els = node.getElementsByTagName("*");
            for(var i=0,j=els.length; i<j; i++)
            if(re.test(els[i].className))a.push(els[i]);
            return a;
        }

        // loop array ##
        var arr = getElementsByClassName ( this.highTabStyle );
        for (i = 0; i < arr.length; i++) {
            arr[i].className = this.lowTabStyle;
        }

    }

   // close tab ##
   this.close = function ( element ) {

      if ( element == null ) { // no element passed ##
           return;
      }

      // TODO -- block any form of home tab deletion ##
      var panel = document.getElementById ( this.Name + 'panel_' + element.tabNum ); // standard close ##

      if ( element.tabNum == 0 ) { // closing home ##

        // note ##
        var e_note = "<p>the home tab cannot be closed</p>";
        top.ecoder.infodialog(e_note);

      } else if ( panel == null ) {

        // note ##
        var e_note = "<p><strong>error closing tab!</strong></p><p>to close this tab manually, return and use the top CLOSE button</p>"; //  - " + element.tabNum + "
        top.ecoder.infodialog(e_note);

      } else {

        this.panelContainer.removeChild ( panel ); // delete panel ##
        this.tabContainer.deleteCell ( element.cellIndex ); // delete tab ##

      }

      if ( element == this.currentHighTab ) { // closing active tab ##
          // this.click_el ( this.tabContainer.cells[0] ); // go home ##
          // top.inspectValues ( element );
          // focus home tab ##
          this.focus ( '0' );

          /*
          var i = -1;
          if ( this.tabContainer.cells.length > 1 ) { // check if tab being closed is the last tab ##
              i = element.cellIndex;
              if ( i == this.tabContainer.cells.length - 1 ) {
                  i = this.tabContainer.cells.length - 2;
                  alert ( i );
              } else {
                  i++;
                  alert ( 'looping...' );
                  //i = 0;
              }

          } else { // no other tabs -- go home ##
              i = 0;
          }

          //alert ( i );
          this.focus ( i );
          */
          /*
          if ( i >= 0 ) { // highlight found tab ##
              this.click_el ( this.tabContainer.cells[i] );
          } else { // nada ##
              this.click_el ( null );
          }
          */

      }

   }
}

/* tab functions */
function ecoder_tabs_add ( iframe, label, url ) { // create ##
    tabber.add ( label, iframe ).innerHTML =
    '<iframe src="'+ url +'" id="'+ iframe +'" name="'+ iframe +'" frameborder="0" style="height:100%; width:100%;"></iframe>';
}

function ecoder_tabs_focus ( file, iframe, id ) { // re-focus open tab ##
    tabber.focus ( id, iframe );
}

function ecoder_tabs_close ( ) { // close ##
    tabber.close ( tabber.currentHighTab );
}

var tabber;
var home;
$(document).ready(function() {
	/* init etabs */
	tabber=new tabber_build('tabber_',document.getElementById('mainTabArea'),document.getElementById('mainPanelArea'));

	/* build home tab */
	//Everywhere where it says home (in strings) in next two lines: $tabs['home']
	home = tabber.add('home','home_txt');
	home.innerHTML='<iframe src="editor.php?mode=edit&path=&file=home.txt&type=text&shut=0" id="home_txt" name="home_txt" frameborder="0" style="height:100%; width:100%;"></iframe>';
});