/* --------------------------------------------------------------------------------

        (W)eb Creations (E)xtended (P)rogramming (I)nterface - WEPI
        JAVASCRIPT RELEASE
        FINAL 1.0
        COPYRIGHT ABDUEALZIZ KURT 2013(c)

        ------------------------------------------------------------------

        5M-Ware Web Solution License

        ------------------------------------------------------------------

        Part of the Web Creations API

   -------------------------------------------------------------------------------- */

var wepiCategorialSidebar = function ( idName, className ) {

	this.id = idName;
	this.cls = className;

	this.begin = function() {
		print( '<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%" class = "' + this.cls + '" id = "' + this.id + '" ' );
		print( 'onmouseout = "javascript:openMenu_' + this.id + '_off();"' );
		print( '>' );
	}

	this.close = function() {
		print( '</table>' );
		// *** //
		print(
			'<script type = "text/javascript">' +
			'function openMenu_' + this.id + '_off() {' +
			'  var ptr = 0;' +
			'  for ( ptr = 0; ptr < 100; ptr++ ) {' +
			'     if ( $_id("popup_' + this.id + '_" + ptr) ) {' +
			'          $_css("popup_' + this.id + '_" + ptr).display = "none";' +
			'     }' +
			'  }' +
			'}' +
			'function openMenu_' + this.id + '( idno ) {' +
			'  openMenu_' + this.id + '_off();' +
			'  if ( $_id("popup_' + this.id + '_" + idno) ) {' +
			'       $_css("popup_' + this.id + '_" + idno).display = "block";' +
			'  }' +
			'}' +
			'</script>'
		);
	}

	this.add = new function () {
		this.mCounter = 0;
		this.iCounter = 0;
		this.cCounter = 0;
		this.idNo = idName;
		this.collect = "";
		this.menu = function() {
				var ptr, key = "", lab = "", url = "", ttp = "";
				// *** //
				for ( ptr = 0; ptr < arguments.length; ptr++ ) {
					switch( ptr ) {
						case 0: key = arguments[ptr]; break;
						case 1: url = arguments[ptr]; break;
						case 2: lab = arguments[ptr]; break;
						case 3: ttp = arguments[ptr]; break;
					}
				}
				// *** //
				if ( ttp != "" ) {
					ttp = ' title = "' + ttp + '" ';
				}
				// *** //
				print( '<tr><td><div class = "menu" ' + ttp + ' id = "' + this.idNo + '_' + this.mCounter + '" onmouseover = "javascript:openMenu_' + this.idNo + '(' + this.mCounter + ');"><div onclick = "' + url + '">' + lab + '</div><div id = "popup_' + this.idNo + '_' + this.mCounter + '" style = "diplay:none;" class = "popup"></div></div></td></tr>' );
				// *** //
				this.mCounter++; this.iCounter = 0; this.cCounter = -1;
		}
		// *** //
		this.item = function() {
			var mC = this.mCounter; mC--;
			// *** //
			var e = 'popup_' + this.idNo + '_' + mC;
			// *** //
			var ptr, typ = "", key = "", lab = "", url = "", ttp = "";
			// *** //
			for ( ptr = 0; ptr < arguments.length; ptr++ ) {
				switch( ptr ) {
					case 0: typ = arguments[ptr]; break;
					case 1: key = arguments[ptr]; break;
					case 2: url = arguments[ptr]; break;
					case 3: lab = arguments[ptr]; break;
					case 4: ttp = arguments[ptr]; break;
				}
			}
			// *** //
			if ( ( typ == 'title' ) || ( typ == 'headline' ) ) {
				ttp = url;
			}
			// *** //
			if ( ttp != "" ) {
				ttp = ' title = "' + ttp + '" ';
			}
			// *** //
			switch ( typ ) {
				case 'begin': // <table
					this.collect  = '<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%"><tr>';
					break;
				case 'close': // </table
					this.collect += '</tr></table>';
					// *** //
					$_id(e).innerHTML = this.collect;
					// *** //
					break;
				case 'column': // <td
					if ( this.cCounter == -1 ) {
						this.collect += '<td valign = "top">';
					} else {
						this.collect += '<td valign = "top" class = "nextColumn">';
					}
					// *** //
					this.cCounter++;
					// *** //
					break;
				case 'ends': // </td
					this.collect += '</td>';
					// *** //
					break;
				case 'link': // Hypertext
					if ( ( this.iCounter == 0 ) || ( this.cCounter > 0 ) ) {
						this.collect += '<div class = "link" ' + ttp + ' onclick = "' + url + '" id = "' + key + '" style = "border-left: none; padding-left: 12px;">' + lab + '</div>';
					} else {
						this.collect += '<div class = "link" ' + ttp + ' onclick = "javascript:openMenu_' + this.idNo + '_off();' + url + '" id = "' + key + '">' + lab + '</div>';
					}
					// *** //
					this.iCounter++;
					// *** //
					break;
				case 'separator': // Trennzeichen
					this.collect += '<hr />';
					break;
				case 'title': // Haupt-Titel einer Spalte
					if ( ( this.iCounter == 0 ) || ( this.cCounter > 0 ) ) {
						this.collect += '<div class = "title" ' + ttp + ' style = "border-left: none; padding-left: 12px;">' + key + '</div>';
					} else {
						this.collect += '<div class = "title" ' + ttp + '>' + key + '</div>';
					}
					// *** //
					this.iCounter++;
					// *** //
					break;
				case 'headline': // Sub-Titel einer Spaltenabschnitt
					if ( ( this.iCounter == 0 ) || ( this.cCounter > 0 ) ) {
						this.collect += '<div class = "headline" ' + ttp + ' style = "border-left: none; padding-left: 12px;">' + key + '</div>';
					} else {
						this.collect += '<div class = "headline" ' + ttp + '>' + key + '</div>';
					}
					// *** //
					this.iCounter++;
					// *** //
					break;
				case 'label': // Text-Information
					if ( url == "" ) { url = " width: auto; "; } else { url = " width: " + url + "px; "; }
					// *** //
					if ( ( this.iCounter == 0 ) || ( this.cCounter > 0 ) ) {
						this.collect += '<div class = "label" style = "border-left: none; padding-left: 12px; ' + url + '">' + key + '</div>';
					} else {
						this.collect += '<div class = "label" style = "' + url + '">' + key + '</div>';
					}
					// *** //
					this.iCounter++;
					// *** //
					break;
			}
		}
	}

}
