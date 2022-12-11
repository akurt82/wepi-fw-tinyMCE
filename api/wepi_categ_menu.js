/* --------------------------------------------------------------------------------

        (W)eb Creations (E)xtended (P)rogramming (I)nterface - WEPI
        JAVASCRIPT RELEASE
        FINAL 1.0
        COPYRIGHT ABDUEALZIZ KURT 2012(c)

        ------------------------------------------------------------------

        5M-Ware Web Solution License

        ------------------------------------------------------------------

        Part of the Web Creations API

   -------------------------------------------------------------------------------- */

var wepiCategMenu = function ( idName ) {

	this.id = idName;
	this.cls = idName;

	this.style = new function() {
		this.menu = "left";
		this.dock = "top";
		this.fixed = false; // Die Menüleiste wird fixiert, wenn der Content gescrollt wird und gegebenenfalls der Banner auch ins unsichtbare gescrollt wurde.
		this.autoroll = false; // Wird nicht direkt angezeigt, sondern nur wenn der Mauszeiger sich nähert.
		this.wide = new function() {
			this.width = "100%";
			this.bar = false;
			this.popup = false;
		}
		this.size = new function() {
			this.bar = 24;
			this.popupItem = 24;
		}
		this.banner = new function() {
			this.activate = false;
			this.height = 0;
			this.fixed = false; // Der Banner wird ebenfalls nicht gescrollt, wenn true.
		}
		this.effect = new function() {
			this.opacity = 0.0;
			this.rolling = false;
			this.shadow = new function() {
				this.activate = false;
				this.x = 0;
				this.y = 0;
			}
			this.rounded = new function() {
				this.top = new function() {
					this.right = 0;
					this.right = 0;
				}
				this.bottom = new function() {
					this.right = 0;
					this.right = 0;
				}
			}
		}
		this.margin = new function() {
			this.popup = new function() {
				this.x = 0;
				this.y = 0;
			}
		}
		this.image = new function() {
			this.bar = new function() {
				this.src = "";
				this.way = "repeat-x";
				this.v = "center";
				this.h = "center";
				this.item = new function() {
					this.normal = new function() {
						this.src = "";
						this.way = "repeat-x";
						this.v = "center";
						this.h = "center";
					}
					this.hover = new function() {
						this.src = "";
						this.way = "repeat-x";
						this.v = "center";
						this.h = "center";
					}
					this.active = new function() {
						this.src = "";
						this.way = "repeat-x";
						this.v = "center";
						this.h = "center";
					}
				}
			}
			this.popup = new function() {
				this.src = "";
				this.way = "repeat-x";
				this.v = "center";
				this.h = "center";
				this.item = new function() {
					this.normal = new function() {
						this.src = "";
						this.way = "repeat-x";
						this.v = "center";
						this.h = "center";
					}
					this.hover = new function() {
						this.src = "";
						this.way = "repeat-x";
						this.v = "center";
						this.h = "center";
					}
					this.active = new function() {
						this.src = "";
						this.way = "repeat-x";
						this.v = "center";
						this.h = "center";
					}
				}
			}
			
		}
		this.font = new function() {
			this.bar = new function() {
				this.name = "Verdana, Arial";
				this.size = 12;
				this.weight = "normal";
			}
			this.popup = new function() {
				this.name = "Verdana, Arial";
				this.size = 12;
				this.weight = "normal";
			}
		}
		this.padding = new function() {
			this.bar = 5;
			this.popup = 2;
			this.item = 3;
		}
		this.color = new function() {
			this.front = "rgb(0,0,0)";
			this.back  = "rgb(241,242,243)";
			this.shadow = "rgb(0,0,0)";
			this.render = new function() {
				this.light = "rgb(255,255,255)";
				this.dark  = "rgb(150,150,150)";
			}
			this.hover = new function() {
				this.front = "rgb(0,0,0)";
				this.back = "rgb(230,230,230)";
				this.render = new function() {
					this.light = "rgb(255,255,255)";
					this.dark  = "rgb(150,150,150)";
				}
			}
			this.active = new function() {
				this.front = "rgb(0,0,0)";
				this.back = "rgb(220,220,220)";
				this.render = new function() {
					this.light = "rgb(150,150,150)";
					this.dark  = "rgb(255,255,255)";
				}
			}
			this.popup = new function() {
				this.shadow = "rgb(0,0,0)";
				this.item = new function() {
					this.front = "rgb(0,0,0)";
					this.back  = "rgb(241,242,243)";
					this.render = new function() {
						this.light = "rgb(255,255,255)";
						this.dark  = "rgb(150,150,150)";
					}
					this.hover = new function() {
						this.front = "rgb(0,0,0)";
						this.back  = "rgb(241,242,243)";
						this.render = new function() {
							this.light = "rgb(255,255,255)";
							this.dark  = "rgb(150,150,150)";
						}
					}
					this.active = new function() {
						this.front = "rgb(0,0,0)";
						this.back  = "rgb(241,242,243)";
						this.render = new function() {
							this.light = "rgb(255,255,255)";
							this.dark  = "rgb(150,150,150)";
						}
					}
				}
				this.render = new function() {
					this.light = "rgb(255,255,255)";
					this.dark  = "rgb(150,150,150)";
				}
			}
		}
	}

	this.rootItem = new Array();
	this.rootCount = -1;

	this.show = function() {}
	this.hide = function() {}

	this.event = new function () {
		this.click;
		this.hover;
		this.leave;
	}

	/* ************************************************** *
	 *
	 * .item( "item", "datei", "Datei" )                  -> Root
	 * .item( "item", "datei.neu", "Neu" )                -> Sub
	 * .item( "item", "datei.[0].neu", "Datei" )          -> Sub auf erster Spalte
	 * .item( "item", "datei.[1].projekte", "Projekte" )  -> Sub auf zweiter Spalte
	 *
	 * Wenn der Label leergelassen wird, so wird der
	 * Eintrag zum Separator.
	 *
	 * type:
	 *       item					Eintrag
	 *		 headline				Klickbarer Titel
	 *		 label					Titeltext (Nicht klickbar)
	 *		 separator				Trennlinie
	 *
	 * ************************************************** */
	this.item = function() {
		var pi, type, key, label, ttip, icon;
		// *** //
		for ( pi = 0; pi < arguments.length; pi++ ) {
			switch( pi ) {
				case 0: type  = arguments[pi]; break;
				case 1: key   = arguments[pi]; break;
				case 2: label = arguments[pi]; break;
				case 3: ttip  = arguments[pi]; break;
				case 4: icon  = arguments[pi]; break;
			}
		}
		// *** //
		var a = "", b = "", c = "", ptr, col;
		// *** //
		if ( isChar(key,'.') == true ) {
			if ( isChar(key,'[') == true ) {
				a = take( 0, '.', key );
				c = take( 1, '.', key );
				b = take( 2, '.', key );
				// *** //
				c = take( 1, '[', c );
				c = take( 0, ']', c );
				// *** //
				col = this.rootItem[this.rootCount]['content'].length;
				col--;
				// *** //
				if ( col < parseInt(c) ) {
					col = c;
					// *** //
					this.rootItem[this.rootCount]['content'][col] = new Array();
				}
				// ** //
				ptr = this.rootItem[this.rootCount]['content'][col].length;
				// *** //
				this.rootItem[this.rootCount]['content'][col][ptr] = new Array();
				// *** //
				this.rootItem[this.rootCount]['content'][col][ptr]['type'] = type;
				this.rootItem[this.rootCount]['content'][col][ptr]['key']  = key;
				this.rootItem[this.rootCount]['content'][col][ptr]['label'] = label;
				this.rootItem[this.rootCount]['content'][col][ptr]['tooltip'] = ttip;
				this.rootItem[this.rootCount]['content'][col][ptr]['icon'] = icon;
			} else {
				a = take( 0, '.', key );
				b = take( 1, '.', key );
				// *** //
				ptr = this.rootItem[this.rootCount]['content'][0].length;
				// *** //
				this.rootItem[this.rootCount]['content'][0][ptr] = new Array();
				// *** //
				this.rootItem[this.rootCount]['content'][0][ptr]['type'] = type;
				this.rootItem[this.rootCount]['content'][0][ptr]['key']  = key;
				this.rootItem[this.rootCount]['content'][0][ptr]['label'] = label;
				this.rootItem[this.rootCount]['content'][0][ptr]['tooltip'] = ttip;
				this.rootItem[this.rootCount]['content'][0][ptr]['icon'] = icon;
			}
		} else {
			this.rootCount++;
			this.rootItem[this.rootCount] = new Array();
			this.rootItem[this.rootCount]['type'] = type;
			this.rootItem[this.rootCount]['key']  = key;
			this.rootItem[this.rootCount]['label'] = label;
			this.rootItem[this.rootCount]['tooltip'] = ttip;
			this.rootItem[this.rootCount]['icon'] = icon;
			// *** //
			if ( ( type != "separator" ) && ( type != "label" ) ) {
				this.rootItem[this.rootCount]['content'] = new Array();
				this.rootItem[this.rootCount]['content'][0] = new Array();
			}
		}
	}

	this.get = function() {
		var code = "", loopUp, loopIn, value, pid = 0, elm, eid, marg = 0, ttip;
		// *** //
		/* *************************************** *
		 * STYLE
		 * *************************************** */
		code += '<style type = "text/css">';
		// *** //
		code += '.' + this.cls + ' {';
		// *** //
		switch ( this.style.dock ) {
			case 'top':
				if ( this.style.fixed == false ) {
					// Do nothing;
				} else {
					code += 'position: fixed;';
				}
				// *** //
				break;
			case 'bottom':
				if ( this.style.fixed == false ) {
					code += 'position: absolute; bottom: 0;';
				} else {
					code += 'position: fixed; bottom: 0;';
				}
				// *** //
				break;
			case 'left':
				if ( this.style.fixed == false ) {
					// Do nothing;
				} else {
					code += 'position: fixed;';
				}
				// *** //
				break;
			case 'right':
				if ( this.style.fixed == false ) {
					code += 'position: absolute; right: 0;';
				} else {
					code += 'position: fixed; right: 0;';
				}
				// *** //
				break;
		}
		// *** //
		code += 'font-family:' + this.style.font.bar.name + ';';
		code += 'font-size:' + this.style.font.bar.size + 'px;';
		code += 'font-weight:' + this.style.font.bar.weight + ';';
		code += 'color:' + this.style.color.front + ';';
		code += 'padding:1px; margin: 0px; line-height: ' + this.style.size.bar + 'px;';
		// *** //
		if ( this.style.color.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.render.light + ';';
			code += 'border-left:   1px solid ' + this.style.color.render.light + ';';
		}
		// *** //
		if ( this.style.color.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.render.dark + ';';
		}
		// *** //
		if ( this.style.image.bar.src != "" ) {
			code += 'background: url(' + this.style.image.bar.src + ') ' + this.style.image.bar.way + ' ' + this.style.image.bar.v + ' ' + this.style.image.bar.h + ' ' + this.style.color.back;
		} else {
			code += 'background-color:' + this.style.color.back + ';';
		}
		// *** //
		if ( this.style.wide.bar == true ) {
			if ( ( this.style.dock == "top" ) || ( this.style.dock == "bottom" ) ) {
				code += 'width: 100%;';
			} else if ( ( this.style.dock == "left" ) || ( this.style.dock == "right" ) ) {
				code += 'height: 100%;';
			}
		} else {
			if ( ( this.style.dock == "top" ) || ( this.style.dock == "bottom" ) ) {
				code += 'width: ' + this.style.wide.width + 'px;';
			} else if ( ( this.style.dock == "left" ) || ( this.style.dock == "right" ) ) {
				code += 'height: ' + this.style.wide.width + 'px;';
			}
		}
		// *** //
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		if ( this.style.padding.bar <= 0 ) { this.style.padding.bar = 1; }
		// *** //
		code += '.' + this.cls + ' .item {';
		code += 'margin: 0px; padding: ' + this.style.padding.bar + ';';
		code += 'cursor: pointer;';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .item:hover {';
		code += 'padding: ' + ( this.style.padding.bar - 1 ) + ';';
		// *** //
		if ( this.style.color.hover.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.hover.render.light + ';';
			code += 'border-left:   1px solid ' + this.style.color.hover.render.light + ';';
		}
		// *** //
		if ( this.style.color.hover.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.hover.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.hover.render.dark + ';';
		}
		// *** //
		code += 'background-color: ' + this.style.color.hover.back + ';';
		code += 'color: ' + this.style.color.hover.front + ';';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup {';
		// *** //
		if ( this.style.wide.popup == true ) {
			code += 'width: 100%;';
		}
		// *** //
		code += 'position: absolute;';
		// *** //
		switch ( this.style.menu ) {
			case 'left':
				code += 'margin-left: -' + this.style.padding.bar + 'px;';
				break;
			case 'center':
				code += 'margin-left: -' + this.style.padding.bar + 'px;';
				break;
			case 'right':
				code += 'right: 0%;';
				break;
		}
		// *** //
		switch ( this.style.dock ) {
			case 'top': 
				code += 'margin-top: ' + this.style.padding.bar + 'px;';
				break;
			case 'bottom':
				code += 'bottom: 0; margin-bottom: ' + (this.style.size.bar + this.style.padding.bar) + 'px;';
				break;
			case 'left':
			case 'right':
		}
		// *** //
		code += 'padding: ' + this.style.padding.popup + 'px;';
		code += 'cursor: default;';
		code += '';
		code += '';
		// *** //
		code += 'font-family:' + this.style.font.popup.name + ';';
		code += 'font-size:' + this.style.font.popup.size + 'px;';
		code += 'font-weight:' + this.style.font.popup.weight + ';';
		// *** //
		code += 'color:' + this.style.color.popup.front + ';';
		// *** //
		if ( this.style.color.popup.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.popup.back + ';';
			code += 'border-left:   1px solid ' + this.style.color.popup.back + ';';
		}
		// *** //
		if ( this.style.color.popup.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.popup.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.popup.render.dark + ';';
		}
		// *** //
		if ( this.style.image.popup.src != "" ) {
			code += 'background: url(' + this.style.image.popup.src + ') ' + this.style.image.popup.way + ' ' + this.style.image.popup.v + ' ' + this.style.image.popup.h + ' ' + this.style.color.popup.item.back;
		} else {
			code += 'background-color:' + this.style.color.popup.item.back + ';';
		}
		// *** //
		code += '';
		code += '';
		code += '';
		code += '}';		
		// *** //
		code += '.' + this.cls + ' .popup .label {';
		code += 'margin: 0px; padding: ' + this.style.padding.bar + '; padding-right: 30px;';
		code += 'cursor: default; font-size: ' + ( this.style.font.popup.size - 2 ) + 'px;';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup .item {';
		code += 'margin: 0px; padding: ' + this.style.padding.bar + '; padding-right: 30px;';
		code += 'cursor: pointer;';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup .item:hover {';
		code += 'padding: ' + ( this.style.padding.bar - 1 ) + '; padding-right: 29px;';
		// *** //
		if ( this.style.color.hover.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.hover.render.light + ';';
			code += 'border-left:   1px solid ' + this.style.color.hover.render.light + ';';
		}
		// *** //
		if ( this.style.color.hover.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.hover.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.hover.render.dark + ';';
		}
		// *** //
		code += 'background-color: ' + this.style.color.hover.back + ';';
		code += 'color: ' + this.style.color.hover.front + ';';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup .item:active {';
		code += 'padding: ' + ( this.style.padding.bar - 1 ) + '; padding-right: 29px;';
		// *** //
		if ( this.style.color.active.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.active.render.light + ';';
			code += 'border-left:   1px solid ' + this.style.color.active.render.light + ';';
		}
		// *** //
		if ( this.style.color.active.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.active.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.active.render.dark + ';';
		}
		// *** //
		code += 'background-color: ' + this.style.color.active.back + ';';
		code += 'color: ' + this.style.color.active.front + ';';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup .headline, .' + this.cls + ' .popup .topic {';
		code += 'margin: 0px; padding: ' + this.style.padding.bar + '; padding-right: 30px;';
		code += 'cursor: pointer; font-weight: bold;';
		code += 'border-bottom: 1px solid ' + this.style.color.popup.render.dark + '; padding-bottom: ' + (this.style.padding.bar - 1) + 'px;';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup .headline:hover {';
		code += 'padding: ' + ( this.style.padding.bar - 1 ) + '; padding-right: 29px;';
		// *** //
		if ( this.style.color.hover.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.hover.render.light + ';';
			code += 'border-left:   1px solid ' + this.style.color.hover.render.light + ';';
		}
		// *** //
		if ( this.style.color.hover.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.hover.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.hover.render.dark + ';';
		}
		// *** //
		code += 'background-color: ' + this.style.color.hover.back + ';';
		code += 'color: ' + this.style.color.hover.front + ';';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '.' + this.cls + ' .popup .headline:active {';
		code += 'padding: ' + ( this.style.padding.bar - 1 ) + '; padding-right: 29px;';
		// *** //
		if ( this.style.color.active.render.light != "" ) {
			code += 'border-top:    1px solid ' + this.style.color.active.render.light + ';';
			code += 'border-left:   1px solid ' + this.style.color.active.render.light + ';';
		}
		// *** //
		if ( this.style.color.active.render.dark != "" ) {
			code += 'border-right:  1px solid ' + this.style.color.active.render.dark + ';';
			code += 'border-bottom: 1px solid ' + this.style.color.active.render.dark + ';';
		}
		// *** //
		code += 'background-color: ' + this.style.color.active.back + ';';
		code += 'color: ' + this.style.color.active.front + ';';
		code += '';
		code += '';
		code += '';
		code += '';
		code += '}';
		// *** //
		code += '</style>';
		// *** //
		/* *************************************** *
		 * JAVASCRIPT
		 * *************************************** */
		// *** //
		for ( loopUp = 0; loopUp < this.rootItem.length; loopUp++ ) {
			elm = this.rootItem[loopUp]['content'][0];
			if ( elm.length ) {pid++;}
		}
		// *** //
		code += '\n\n<script type = "text/javascript">\n';
		code += 'function eventClick_' + this.id + ' ( key ) { ' + this.event.click + '(key); }\n';
		code += 'function eventLeft_' + this.id + ' ( key ) {\n';
		code += '  var pos, count = ' + pid + ';\n';
		code += '  for ( pos = 0; pos < ( count + 1 ); pos++ ) {\n';
		code += '     if ( document.getElementById("popup_' + this.id + '_" + pos) ) {\n';
		code += '         document.getElementById("popup_' + this.id + '_" + pos).style.display = "none";\n';
		code += '     }\n';
		code += '  }\n';
		code += '}\n\n';
		code += 'function eventOver_' + this.id + ' ( key, pid ) {\n';
		code += '  eventLeft_' + this.id + '( key );\n';
		code += '  if ( pid > -1 ) {\n';
		code += '     if ( document.getElementById("popup_' + this.id + '_" + pid) ) {\n';
		code += '         document.getElementById("popup_' + this.id + '_" + pid).style.display = "block";\n';
		code += '     }\n';
		code += '  }\n';
		code += '}\n';
		code += '</script>\n\n';
		/* *************************************** *
		 * HTML
		 * *************************************** */
		code += '<div id = "' + this.id + '" class = "' + this.cls + '"><table border = "0" cellspacing = "0" cellpadding = "0" width = "100%"><tr>';
		// *** //
		if ( this.style.menu == "right" ) {
			code += '<td width = "100%">&nbsp;</td>';
		} else if ( this.style.menu == "center" ) {
			code += '<td width = "50%">&nbsp;</td>';
		}
		// *** //
		pid = 0;
		// *** //
		for ( loopUp = 0; loopUp < this.rootItem.length; loopUp++ ) {
			elm = this.rootItem[loopUp]['content'][0];
			// *** //
			code += '<td><div class = "item" id = "' + this.rootItem[loopUp]['key'] + '" ';
			if ( elm.length > 0 ) {
				code += 'onmouseover = "javascript:eventOver_' + this.id + '(\'' + this.rootItem[loopUp]['key'] + '\',' + pid + ');" ';
			}
			code += 'onmouseout = "javascript:eventLeft_' + this.id + '(\'' + this.rootItem[loopUp]['key'] + '\');" ';
			code += '>';
			code += '<div onclick = "javascript:eventClick_' + this.id + '(\'' + this.rootItem[loopUp]['key'] + '\');">';
			code += wideSpaceLess(this.rootItem[loopUp]['label']);
			code += '</div>';
			// *** //
			if ( this.style.wide.popup == false ) {
					code += '<div id = "popup_' + this.id + '_' + pid + '" class = "popup" style = "display:none;"><div style = "width: 100%; height: 100%; border-top: 1px solid ' + this.style.color.popup.render.light + '; border-left: 1px solid ' + this.style.color.popup.render.light + ';">';
					// *** //
					code += '<table border = "0" cellspacing = "0" cellpadding = "0" width = "100%"><tr>';
					// *** //
					for ( loopDown = 0; loopDown < this.rootItem[loopUp]['content'].length; loopDown++ ) {
						elm = this.rootItem[loopUp]['content'][loopDown];
						// *** //
						marg = 0;
						// *** //
						if ( loopDown < ( this.rootItem[loopUp]['content'].length - 1 ) ) {
							code += '<td valign = "top" style = "border-right: 1px solid ' + this.style.color.popup.render.dark + '; padding-right: 2px;">';
						} else {
							if ( this.rootItem[loopUp]['content'].length > 1 ) {
								code += '<td valign = "top" style = "border-left: 1px solid ' + this.style.color.popup.render.light + '; padding-left: 2px;">';
							} else {
								code += '<td valign = "top">';
							}
						}
						// *** //
						for ( eid = 0; eid < elm.length; eid++ ) {
							ttip = elm[eid]['tooltip'];
							// *** //
							if ( ( ttip ) && ( ttip != "" ) ) {
								ttip = ' title = "' + ttip + '" ';
							}
							// *** //
							switch ( elm[eid]['type'] ) {
								case 'item':
									code += '<div class = "item" onclick = "javascript:eventClick_' + this.id + '(\'' + elm[eid]['key'] + '\'); eventLeft_' + this.id + '(\'' + this.rootItem[loopUp]['key'] + '\');"' + ttip + '>';
									code += wideSpaceLess(elm[eid]['label']);
									code += '</div>';
									// *** //
									break;
								case 'headline':
									if ( marg == 0 ) {
										code += '<div style = "border-bottom: 1px solid ' + this.style.color.popup.render.light + '; margin-bottom: 10px;">';
									} else {
										code += '<div style = "border-bottom: 1px solid ' + this.style.color.popup.render.light + '; margin-bottom: 10px; margin-top: 10px;">';
									}
									// *** //
									code += '<div class = "headline" onclick = "javascript:eventClick_' + this.id + '(\'' + elm[eid]['key'] + '\'); eventLeft_' + this.id + '(\'' + this.rootItem[loopUp]['key'] + '\');"' + ttip + '>';
									code += wideSpaceLess(elm[eid]['label']);
									code += '</div></div>';
									// *** //
									marg++;
									// *** //
									break;
								case 'topic':
									if ( marg == 0 ) {
										code += '<div style = "border-bottom: 1px solid ' + this.style.color.popup.render.light + '; margin-bottom: 10px;">';
									} else {
										code += '<div style = "border-bottom: 1px solid ' + this.style.color.popup.render.light + '; margin-bottom: 10px; margin-top: 10px;">';
									}
									// *** //
									code += '<div class = "topic" style = "cursor: default;"' + ttip + '>';
									code += wideSpaceLess(elm[eid]['label']);
									code += '</div></div>';
									// *** //
									marg++;
									// *** //
									break;
								case 'label':
									code += '<div class = "label"' + ttip + '>';
									code += wideSpaceLess(elm[eid]['label']);
									code += '</div>';
									// *** //
									break;
								case 'separator':
									code += '<div style = "cursor: default; font-style:normal; font-size: 8px; font-family: Arial; border-bottom: 1px solid ' + this.style.color.popup.render.light + '; margin-bottom: 14px;">';
									code += '<div style = "border-bottom: 1px solid ' + this.style.color.popup.render.dark + '; padding: 0px; margin: 0px;">';
									code += '&nbsp;';
									code += '</div></div>';
									// *** //
									break;
							}
						}
						// *** //
						code += '</td>';
					}
					// *** //
					code += '</tr></table>';
					// *** //
					code += '</div></div>';
			}
			// *** //
			code += '</div>';
			code += '</td>';
			// *** //
			if ( elm.length > 0 ) { pid++; }
		}
		// *** //
		if ( this.style.menu == "left" ) {
			code += '<td width = "100%">&nbsp;</td>';
		} else if ( this.style.menu == "center" ) {
			code += '<td width = "50%">&nbsp;</td>';
		}
		// *** //
		code += '</tr></table></div>';
		// *** //
		return code;
	}

}
