/* --------------------------------------------------------------------------------

        (W)eb Creations (E)xtended (P)rogramming (I)nterface - WEPI
        JAVASCRIPT RELEASE
        BETA 1.1
        COPYRIGHT ABDUELAZIZ KURT, 5M-Ware 2010 - 2014(c)

        ------------------------------------------------------------------
        
        GPLv3 or MIT licance for commercial or non-commerical use
        
        ------------------------------------------------------------------
        
        Part of the Web Creations API

        ------------------------------------------------------------------

        Dependency: wepi_core.js
        
        ------------------------------------------------------------------

        Note: This is the JavaScript Client-Server-Interaction-Object.
        
        ------------------------------------------------------------------
        
        Attension: Run for the first time the function "wepiServInit()"

        ------------------------------------------------------------------

        Summery:
        
              m stays for a mode like '+', '-', '*', '/' or '.'
              
              IsSess()                Returns true, when a session-var exists, false if not
              Sess("")                Returns the value of a session-parameter
              Sess("",v)              Modifies the value of a session-parameter
              Sess("",v,m)            Modifies the value of a session-parameter by a mode

              Run("name","pars")      Calls a php-procedure
              Return("nm", "pr")      Calls a php-function and returns its output

              Write("file","val")     Creates and writes a file
              Append("file","val")    Appends into a existing file
              Read("file")            Returns the content of a file

              Browse("file","arg")    Searchs a file for an argument and returns all found
                                      lines in a JavaScript-Array

              Seek("file","arg")      Searchs a file for an argument and returns true
                                      when found or false when no match found

              Find("path","arg")      Browses all files in a path and returns the files
                                      with match's into a list

              GetDir("path")          Returns a list of sub-directories
              GetFiles("path","patt") Returns a list of files

              AddDir("path")          Creates a new sub-directory
              RemDir("path")          Removes a sub-directory

              IsDir("path")           Is directory exists?
              IsFile("path")          Is file exists?

              Console                 Runs a console command on the server's prompt

              Include("")             Include a file at the server-side or client-side.
                                      Samples:
                                        .Include("abc.js")    > Returns a JavaScript-Include
                                        .Include("abc.css")   > Returns a Stylesheet-Include
                                        .Include("abc.php")   > Includes a Server-Side source              

   -------------------------------------------------------------------------------- */

    function wepiServInit () { print('<span id = "wepiServReqTag" style = "display:none;"></span>'); }

    wepiServInit();

    function wepiServ () {

            this.Version = "1.1";
            this.Count = 0;
			this.sessionID = "";

            /* ---------------------------------------------------------
             *
             *    wepi.Sess()
             *    
             *    Allows to get or set a PHP-Session declaration
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Sess",
                    function( v ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=get_sess&get_sess=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return $_id("wepiServReqTag").innerHTML;
                    }
                );

                addMethod(
                    this,
                    "Sess",
                    function( v, c ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=set_sess&set_sess=" + v + "&set_val=" + c + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return r;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.MultiSess()
             *    
             *    Allows to get or set a PHP-Session declaration
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "MultiSess",
                    function( v ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReqEx( m, f, this.sessionID + "&wepi=get_multi_sess&get_multi_sess=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return $_id("wepiServReqTag").innerHTML;
                    }
                );

                addMethod(
                    this,
					"MultiSess",
                    function( v, c ) {
						var vv = ""; var cc = ""; var ii = 0;
						// *** //
						vv = v.replace( ' ', '' );
						vv = vv.replace( '\t', '' );
						vv = trim(vv);
						// *** //
						cc = c.replace( ' ', '' );
						cc = cc.replace( '\t', '' );
						cc = trim(cc);
						// *** //
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						// *** //
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReqEx( m, f, this.sessionID + "&wepi=set_multi_sess&set_multi_sess=" + vv + "&set_val=" + cc + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return r;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Read()
             *    
             *    Allows to read a file into your client-area
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Read",
                    function( v ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=read_file&read_file=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return $_id("wepiServReqTag").innerHTML;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Write()
             *    
             *    Allows to create and overwrite a file on server                         
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Write",
                    function( v, t ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        wepiServerReq( m, f, this.sessionID + "&wepi=write_file&write_file=" + v + "&set_val=" + t + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Append()
             *    
             *    Allows to append stream into an existing file                    
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Append",
                    function( v, t ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
                        wepiServerReq( m, f, this.sessionID + "&wepi=append_file&append_file=" + v + "&set_val=" + t + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Run()
             *    
             *    Allows to run a php-function without looking for an
             *	  return value.                        
             * 
               --------------------------------------------------------- */
    
                addMethod(                                        
                    this,                                         
                    "Run",                                       
                    function( f ) {
                        var m = "GET";
                        var s = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { s = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        var pp = new Array();
                        var i = 0; j = 4;
						pp[0] = this.sessionID;
                        pp[1] = "call_proc";
                        pp[2] = f;
                        pp[3] = "random_mode:" + this.Count + "_" + new Date().getTime();
                        for ( i = 1; i < arguments.length; i++ ) {
                            pp[j] = arguments[i];
                            if ( isStrNumOnly( pp[j] ) == false ) {
                              pp[j] == "创" + pp[j] + "创";
                            }
                            j++;
                        }
                        r = wepiServerReqEx( m, s, pp, false );
                        this.Count++;
					}
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Return()
             *    
             *    Allows to run a function and receives it's return value
             * 
               --------------------------------------------------------- */

                this.Return = function( f ) {
                        var m = "GET";
                        var s = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { s = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        var pp = new Array();
                        var i = 0; j = 4;
						pp[0] = this.sessionID;
                        pp[1] = "call_proc";
                        pp[2] = f;
                        pp[3] = "random_mode:" + this.Count + "_" + new Date().getTime();
                        for ( i = 1; i < arguments.length; i++ ) {
                            pp[j] = arguments[i];
                            if ( isStrNumOnly( pp[j] ) == false ) {
                              pp[j] == "创" + pp[j] + "创";
                            }
                            j++;
                        }
                        r = wepiServerReqEx( m, s, pp, false );
                        this.Count++;
                        return r;
                    };

            /* ---------------------------------------------------------
             *
             *    wepi.dataset()
             *    
             *    Allows to send an javascript-array or get an
             *	  php-array.
             *
             *	  One dimension only!
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "DataSet",
                    function( v ) {
                        var pp = new Array(); var pl = "";
                        var i = 0; j = 0;
                        for ( i = 1; i < v.length; i++ ) {
                            pp[j] = v[i];
                            if ( isStrNumOnly( pp[j] ) == false ) {
                              pp[j] == "创" + pp[j] + "创";
                            }
                            j++;
                        }
                        // *** //
                        pl += "&total=" + pp.length;
                        // *** //
                        for( i = 0; i < pp.length; i++ ) {
                        	pl += "&item" + i + "=" + pp[i]; 
                        }
                        // *** //
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=get_array&get_array=" + pl + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                    }
                );

                addMethod(
                    this,
                    "DataSet",
                    function( v, c ) {
                    	if ( this.__query(v,"isarray") == true ) {
                    		var i = 0; var t = this.__query(v,"sizeof");
                    		if ( t > 0 ) {
                    			for ( i = 0; i < t; i++ ) {
                    				c[i] = this.__query_str(v,i);
                    			}
                    		}
                    	}
                    }
                );

    }
