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

var wepiCategMenu = function ( idName, className ) {

	this.id = idName;
	this.cls = className;

	this.style = {
		color : {
			front : rgb(0,0,0),
			back  : rgb(241,242,243),
			render : {
				light : rgb(255,255,255),
				dark  : rgb(150,150,150)
			}
		}
	}

	this.code = new Array();
	this.count = 0;

	this.begin = function () {
		this.code[this.count] = '<div id = " + idName + " class = "' + className + '">';
	}

	this.close = function () {
		this.code[this.count] = '</div>';
	}

	this.item = function( idLab, label ) {
		
	}

}
