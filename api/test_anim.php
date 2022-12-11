<html>
	<head>
		<title>Data</title>
	</head>

	<meta http-equiv="content-type" content="text/html; charset=utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<script type = "text/javascript" src = "wepi_core.js"></script>
	<script type = "text/javascript" src = "wepi_banner.js"></script>

	<style type = "text/css">
		body {
			padding: 0px;
			margin: 0px;
		}
	</style>

	<body>

		<div id = "hw" style = "background-color:rgb(30,55,95);color:#ffffff;padding:10px;opacity:0.0;">
			Hallo Welt
		</div>

		<script type = "text/javascript">

			var hw = new wepi_timer( 50, function() {
				if ( $_css('hw').opacity != 1.0 ) {
					$_css('hw').opacity = parseFloat($_css('hw').opacity) + 0.02;
				} else {
					hw.stop();
				}
			} );
			hw.play();

		</script>
<!--
		<br /><br />
		
		<div style = "background-color:rgb(30,55,95);color:#ffffff;padding:0px; margin: 0px; margin-left: 100px; width: 600px; height: 200px; overflow: hidden;">
			<table id = "dh" border = "0" cellspacing = "0" cellpadding = "0" style = "padding: 0px; margin: 0px;"><tr>
			<td valign = "top">
			<div  id = "d0" style = "background-color:rgb(30,55,195);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 1
			</div>
			</td><td valign = "top">
			<div  id = "d1" style = "background-color:rgb(30,155,95);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 2
			</div>
			</td><td valign = "top">
			<div  id = "d2" style = "background-color:rgb(130,55,95);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 3
			</div>
			</td><td valign = "top">
			<div  id = "d3" style = "background-color:rgb(130,155,95);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 4
			</div>
			</td>
			</tr></table>
		</div>

		<script type = "text/javascript">

			var dhm = 0;
			var dhw = 0;
			var siz = 0;
			var dh = new wepi_timer( 1, function() {
				switch ( dhm ) {
					case 0:
						$_css('dh').marginLeft = siz + "px";
						siz = siz - 10;
						if ( siz <= -1800 ) {
							siz = -1800;
							$_css('dh').marginLeft = siz + "px";
							dhw = 0;
							dhm = 10;
						}
						break;
					case 1:
						$_css('dh').marginLeft = siz + "px";
						siz = siz + 10;
						if ( siz >= -10 ) {
							siz = 0;
							$_css('dh').marginLeft = siz + "px";
							dhw = 0;
							dhm = 20;
						}
						break;
					case 10:
						dhw ++;
						if ( dhw == 200 ) { dhm = 1; }
						break;
					case 20:
						dhw ++;
						if ( dhw == 200 ) { dhm = 0; }
						break;
						
				}
			} );
			dh.play();

		</script>
-->
<!--
		<script type = "text/javascript">

			var wepi_banner = function ( id ) {
				this.id = id;
				this.width = 600;
				this.height = 200;
				this.interval = 1;
				this.stepval = 10;
				this.steps = 600;
				/* If OPAQUE is false, then we animate rolling-effect from
				 * right to left floating. But if OPAQUE is true, then we
				 * do not apply rolling-effect, instead we simulate opaque-
				 * on or opaque-off effect. THIS FLAG HAS TO BE SET BEFORE
				 * CALLING THE GET-METHOD, BECAUSE IT DECIDES IN WHICH WAY
				 * THE BANNER-IMPLEMENTATION WILL BE REALIZED BY CODE!  */
				this.opaque = false;
				this.styleClass = "";
				this.style = "";
				this.timer;
				// *** //
				this.storage = new function () {
					this.opaque = new function () {
						this.valueUp = 0.0;
						this.valueDown = 0.0;
						this.direction = 0;
					}
					this.cycle = new function () {
						this.total = 0;
						this.direction = 0;
						this.index = 0;
						this.steps = 0;
						this.expanding = 0;
						this.part1 = "";
						this.part2 = "";
					}
					this.unit = new Array();
				}
				// *** //
				this.add = function () {
					var n, picture = "", content = "";
					// *** //
					for ( n = 0; n < arguments.length; n++ ) {
						switch( n ) {
							case 0:
								picture = arguments[n]; break;
							case 1:
								content = arguments[n]; break;
						}
					}
					// *** //
					var total = this.storage.unit.length;
					// *** //
					this.storage.unit[total] = new Array();
					// *** //
					this.storage.unit[total][0] = picture;
					this.storage.unit[total][1] = content;
					// *** //
					this.storage.total = total + 1;
				}
				// *** //
				this.get = function () {
					var code; var cls = "";
					// *** //
					if ( this.styleClass != "" ) {
						cls = ' class = "' + this.styleClass + '" ';
					}
					// *** //
					var arraySet = "", startCopy = "", pos, opaque;
					// *** //
					if ( this.opaque == false ) {
							for ( pos = 0; pos < this.storage.unit.length; pos ++ ) {
								arraySet += '<td valign = "top">'+"\n"+'<div id = "unit_' + this.id + '_' + pos + '" ' +
											 'style = "padding: 0px; margin: 0px; ' +
											 'width: ' + this.width + 'px; height: ' + this.height + 'px; ' +
											 'background: url(' + this.storage.unit[pos][0] + ') no-repeat top left; ' +
											 this.style + '" class = "unit">' + this.storage.unit[pos][1] + '</div>'+"\n"+'</td>'+"\n";
							}
							// *** //
							startCopy  = '<div id = "unit_' + this.id + '_startcopy" ' +
										 'style = "padding: 0px; margin: 0px; ' +
										 'width: ' + this.width + 'px; height: ' + this.height + 'px; ' +
										 'background: url(' + this.storage.unit[0][0] + ') no-repeat top left; ' +
										 this.style + '" class = "unit">' + this.storage.unit[0][1] + '</div>'+"\n";
							// *** //
							code = '<div style = "' +
								   'padding: 0px; width: ' + this.width + 'px; height: ' + this.height + 'px;' + this.style + '">'+"\n" +
								   '<div style = "position: absolute; margin: 0px;' +
								   'padding: 0px; width: ' + this.width + 'px; height: ' + this.height + 'px; overflow: hidden; ' + this.style + '" ' +
								   cls + '>'+"\n"+'<table id = "unit_' + this.id + '_arrayset" border = "0" cellspacing = "0" cellpadding = "0" ' +
								   'style = "z-index:1; padding: 0px; margin: 0px; position: absolute;"><tr>'+"\n" +
								   arraySet + '</tr></table>'+"\n" + startCopy + '</div></div>'+"\n";
							// *** //
							this.storage.part1 = "unit_" + this.id + "_arrayset";
							this.storage.part2 = "unit_" + this.id + "_startcopy";
					} else {
							for ( pos = 0; pos < this.storage.unit.length; pos ++ ) {
								if ( pos == 0 ) {
									opaque = ' display: block; opacity: 1.0; ';
								} else {
									opaque = ' display: none;  opacity: 0.0; ';
								}
								// *** //
								arraySet += '<div id = "unit_' + this.id + '_' + pos + '" ' +
											 'style = "position: absolute; padding: 0px; margin: 0px; ' + opaque + 
											 'width: ' + this.width + 'px; height: ' + this.height + 'px; ' +
											 'background: url(' + this.storage.unit[pos][0] + ') no-repeat top left; ' +
											 this.style + '" class = "unit">' + this.storage.unit[pos][1] + '</div>'+"\n";
							}
							// *** //
							code = '<div style = "' +
								   'padding: 0px; width: ' + this.width + 'px; height: ' + this.height + 'px;' + this.style + '">'+"\n" +
								   '<div style = "position: absolute; margin: 0px;' +
								   'padding: 0px; width: ' + this.width + 'px; height: ' + this.height + 'px; overflow: hidden; ' + this.style + '" ' +
								   cls + '>'+"\n" + arraySet + "\n" + '</div></div>'+"\n";
							// *** //
							this.storage.part1 = "unit_" + this.id + "_arrayset";
					}
					// *** //
					return code;
				}
				// *** //
				this.play = function () {
					if ( this.opaque == false ) {
						eval(   'var ' + this.id + '_' + 'dsm = 11; ' +
								'var ' + this.id + '_' + 'dsw = 0; ' +
								'var ' + this.id + '_' + 'ssz = 0; ' +
								'var ' + this.id + '_' + 'dsp = 0; ' +
								'var ' + this.id + '_' + 'ds = new wepi_timer( ' + this.interval + ', function() { ' +
								'	switch ( ' + this.id + '_' + 'dsm ) { ' +
								'		case 0: ' +
								'			$_css("unit_' + this.id + '_' + 'arrayset").marginLeft = ' + this.id + '_' + 'ssz + "px"; ' +
								'			' + this.id + '_' + 'ssz = ' + this.id + '_' + 'ssz - ' + this.stepval + '; ' +
								'			$_css("unit_' + this.id + '_' + 'startcopy").marginLeft = ( parseInt($_css("unit_' + this.id + '_' + 'arrayset").marginLeft) + (' + this.storage.total + ' * ' + this.steps + ') ) + "px"; ' +
								'			if ( ' + this.id + '_' + 'ssz <= -((' + this.steps + ' + ' + this.stepval + ') * ' + this.id + '_' + 'dsp) + ((' + this.id + '_' + 'dsp - 1) * ' + this.stepval + ') ) { ' +
								'				' + this.id + '_' + 'ssz = -((' + this.steps + ' + ' + this.stepval + ') * ' + this.id + '_' + 'dsp) + (' + this.id + '_' + 'dsp * ' + this.stepval + '); ' +
								'				$_css("unit_' + this.id + '_' + 'arrayset").marginLeft = ' + this.id + '_' + 'ssz + "px"; ' +
								'				' + this.id + '_' + 'dsw = 0; ' +
								'				if ( ' + this.id + '_' + 'dsp == ' + this.storage.total + ' ) { ' +
								'					' + this.id + '_' + 'dsm = 12; ' +
								'					' + this.id + '_' + 'dsw = 0; ' +
								'				} else { ' +
								'					' + this.id + '_' + 'dsm = 11; ' +
								'				} ' +
								'			} ' +
								'			break; ' +
								'		case 10: ' +
								'			' + this.id + '_' + 'dsw ++; ' +
								'			if ( ' + this.id + '_' + 'dsw == ' + this.steps + ' ) { ' + this.id + '_' + 'dsm = 1; ' + this.id + '_' + 'dsp = 0; } ' +
								'			break; ' +
								'		case 11: ' +
								'			' + this.id + '_' + 'dsw ++; ' +
								'			if ( ' + this.id + '_' + 'dsw == ' + this.steps + ' ) { ' + this.id + '_' + 'dsp ++; ' + this.id + '_' + 'dsm = 0; } ' +
								'			break; ' +
								'		case 12: ' +
								'			' + this.id + '_' + 'dsw ++; ' +
								'			if ( ' + this.id + '_' + 'dsw == ' + this.steps + ' ) { ' +
								'				$_css("unit_' + this.id + '_' + 'startcopy").marginLeft = (' + this.steps + ' + ' + this.stepval + ') + "px"; ' +
								'				$_css("unit_' + this.id + '_' + 'arrayset").marginLeft = "' + this.steps + 'px"; ' +
								'				' + this.id + '_' + 'dsp = 0; ' +
								'				' + this.id + '_' + 'dsw = 0; ' +
								'				' + this.id + '_' + 'dsm = 0; ' +
								'			} ' +
								'			break; ' +
								'	} ' +
								'} ); ' +
								this.id + '_' + 'ds.play(); '
						);
					} else {
						eval(   '   var nextpo = 0; var lastpo = -1; ' +
								'   var total = ' + this.storage.total + '; ' +
								'   var decna = "unit_' + this.id + '_"; ' +
								'   var steps = ' + this.steps + '; ' +
								'   var stepg = 0; ' +
								'   var timer = new wepi_timer( ' + this.interval + ', function() { ' +
								'   if ( stepg < steps ) { ' +
								'        stepg++; ' +
								'   } else { ' +
								'   if ( $_css(decna + nextpo).opacity != 1.0 ) { ' +
								'      if ( $_css(decna + nextpo).display != "block" ) { $_css(decna + nextpo).display = "block"; } ' +
								'      $_css(decna + nextpo).opacity = parseFloat($_css(decna + nextpo).opacity) + 0.02; ' +
								'   } ' +
								'   if ( lastpo > -1 ) { ' +
								'      if ( $_css(decna + nextpo).opacity != 0.0 ) { ' +
								'         $_css(decna + lastpo).opacity = parseFloat($_css(decna + lastpo).opacity) - 0.02; ' +
								'      } ' +
								'   } ' +
								'   if ( ( $_css(decna + nextpo).opacity >= 1.0 ) && ( lastpo == -1 ) || ' +
								'      ( $_css(decna + nextpo).opacity >= 1.0 ) && ( lastpo != -1 ) || ' +
								'      ( $_css(decna + lastpo).opacity <= 0.0 ) && ( lastpo != -1 ) ) { ' +
								'      $_css(decna + nextpo).opacity == 1.0;' +
								'      if ( lastpo != -1 ) { $_css(decna + lastpo).opacity = 0.0; } ' +
								'      lastpo = nextpo; ' +
								'      nextpo++; ' +
								'      if ( nextpo > total -1 ) { ' +
								'         nextpo = 0; ' +
								'      } ' +
								'      $_css(decna + nextpo).display = "none"; ' + 
								'      stepg = 0; ' +
								'   } ' +
								'   } ' +
								' } ); ' +
								' timer.play(); '
						);
					}
				}
				// *** //
				this.stop = function () {
					if ( this.opaque == false ) {
						eval ( this.id + '_' + 'ds.stop();' );
					} else {
						eval( '' );
					}
				}
			}

		</script>
-->
		<br /><br />
		
		<div style = "position:absolute;background-color:rgb(30,55,95);color:#ffffff;padding:0px; margin: 0px; margin-left: 100px; width: 600px; height: 200px; overflow: hidden;">
			<table id = "ds" border = "0" cellspacing = "0" cellpadding = "0" style = "display:block;z-index:1;padding: 0px; margin: 0px; position: absolute;"><tr>
			<td valign = "top">
			<div  id = "d0" style = "background-color:rgb(30,55,195);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 1
			</div>
			</td><td valign = "top">
			<div  id = "d1" style = "background-color:rgb(30,155,95);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 2
			</div>
			</td><td valign = "top">
			<div  id = "d2" style = "background-color:rgb(130,55,95);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 3
			</div>
			</td><td valign = "top">
			<div  id = "d3" style = "background-color:rgb(130,155,95);color:#ffffff;padding:0px; margin: 0px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 4
			</div>
			</td>
			</tr></table>
			<div  id = "dx" style = "display:block;z-index:0;position:absolute;background-color:rgb(30,55,195);color:#ffffff;padding:0px; margin: 0px; margin-left:610px; width: 600px; height: 200px;">
				<br />&nbsp;&nbsp;Feld 1
			</div>
		</div>

		<script type = "text/javascript">

			var dsm = 11;
			var dsw = 0;
			var ssz = 0;
			var dsp = 0;
			var ds = new wepi_timer( 1, function() {
				switch ( dsm ) {
					case 0:
						$_css('ds').marginLeft = ssz + "px";
						ssz = ssz - 10;
						$_css('dx').marginLeft = ( parseInt($_css('ds').marginLeft) + (4 * 600) ) + "px";
						// *** //
						if ( ssz <= -(610 * dsp) + ((dsp - 1) * 10) ) {
							ssz = -(610 * dsp) + (dsp * 10);
							$_css('ds').marginLeft = ssz + "px";
							dsw = 0;
							if ( dsp == 4 ) {
								dsm = 12;
								dsw = 0;
							} else {
								dsm = 11;
							}
						}
						break;
					case 10:
						dsw ++;
						if ( dsw == 600 ) { dsm = 1; dsp = 0; }
						break;
					case 11:
						dsw ++;
						if ( dsw == 600 ) { dsp ++; dsm = 0; }
						break;
					case 12:
						dsw ++;
						if ( dsw == 600 ) {
							$_css('dx').marginLeft = "610px";
							$_css('ds').marginLeft = "600px";
							dsp = 0;
							dsw = 0;
							dsm = 0;
						}
						break;
				}
			} );
			ds.play();

		</script>

		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<script type = "text/javascript">
			var ani = new wepi_banner( "slider" );
				ani.add( "a", '<div style = "width:100%;height:100%;background-color:rgb(212,224,236);">&nbsp;&nbsp;Hallo Welt</div>' );
				ani.add( "b", '<div style = "width:100%;height:100%;background-color:rgb(236,224,212);">&nbsp;&nbsp;Ha Ha Ha :-) ...</div>' );
				ani.add( "c", '<div style = "width:100%;height:100%;background-color:rgb(224,212,236);">&nbsp;&nbsp;Alles wird gut!!</div>' );
				ani.add( "d", '<div style = "width:100%;height:100%;background-color:rgb(212,236,224);">&nbsp;&nbsp;Last but not Least!</div>' );
				ani.style = "background-color: rgb(212,224,236); font-size: 80px;";
				ani.opaque = true;
				print( ani.get() );
				ani.play();
		</script>

	</body>
</html>
