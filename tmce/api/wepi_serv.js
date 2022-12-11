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
        
              Var("")                 Returns the value of a declaration
              Var("",v)               Modifies the value of a declaration
              Var("",v,m)             Modifies the value of a declaration by a mode

              Con("")                 Returns the value of an constant
              Env("")                 Returns the value of an enviorment constant

              Login()                 Returns true, if logged in, false if not
              MyIP()                  Returns current client-ip
              
              IsSess()                Returns true, when session-started, false if not
              Sess("")                Returns the value of a session-parameter
              Sess("",v)              Modifies the value of a session-parameter
              Sess("",v,m)            Modifies the value of a session-parameter by a mode
              SessQuit()              Runs session_destroy() on the server

              Get("")                 Returns the value of a Get-Method-Item
              Post("")                Returns the value of a Post-Method-Item

              Run("name","pars")      Calls a php-procedure
              Return("nm", "pr")      Calls a php-function and returns its output

              Print("")               Prints by php-echo
                                      Server-side print is important to get real
                                      HTML-source into the current instance. In example
                                      to include further JavaScript-files or Style-Sheets

              Write("file","val")     Creates and writes a file
              Append("file","val")    Appends into a existing file
              Read("file")            Returns the content of a file

              Browse("file","arg")    Searchs a file for an argument and returns all found
                                      lines in a JavaScript-Array

              Seek("file","arg")      Searchs a file for an argument and returns true
                                      when found or false when no match found

              NewData("file")         Creates an INI-file
              GetData("file","block") Returns a Block of the INI-file
              SetData("f","b","new")  Modifes a Block of the INI-file
              RemData("file")         Removes an INI-file
              GetD("fl","bl","entry") Returns the value of an Entry
              SetD("fl","b","e","v")  Modifies the value of an Entry
              RemD("fl","b","e")      Removes an entry

              Find("path","arg")      Browses all files in a path and returns the files
                                      with match's into a list

              GetDir("path")          Returns a list of sub-directories
              GetFiles("path","patt") Returns a list of files

              AddDir("path")          Creates a new sub-directory
              RemDir("path")          Removes a sub-directory

              IsDir("path")           Is directory exists?
              IsFile("path")          Is file exists?

              DBGetTable("select..")  Returns the content of a table
              DBGetTable("se",mode)   Returns the content of a table by a mode of export
                                      modes: "table", "list", "blank"
              DBCreateTable("t")      Create a table
              DBRemoveTable("")       Remove a table
              DBInsert(tbl,args)      Inserts in a table
              DBUpdate(tbl,args,whr)  Updates a table
              DBWrite(tbl,args,whr)   Inserts in a table or updates a table
              DBRemove(tbl,whr)       Deletes from a table
              DBLook("tbl",whr)       Looks, if the argument exists. Returns true or false
              DBGetText("tbl",arg)    Returns the data as text
              DBGetPict("tbl",arg)    Returns the data as picture
              DBGetMedia("tbl",arg)   Returns the data as a media data stream

              DBGetThis(              Returns a value from database formated by code. Sample:
                "arg",                DBGetThis("tbl", "select ...", "user", "t.Add('???');" );
                "col",
                "kod",
              )

              DBGetThis(              Returns a value from database formated by code. Sample:
                                      var u = new Array( "firstname", "surname", "gender", "email" );
                "arg",                var c = new Array( "t.Add('???', ", "'???'", "'???'", "'???' );" );
                colarray,             DBGetThis("tbl", "select ...", u, c );
                codarray,
              )

              CGI                     Runs a CGI-Script

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
			this.path = "";

            /* ---------------------------------------------------------
             *
             *    wepi.Var()
             *    
             *    Allows to get the value of a PHP-Variable or to
             *    overwrite it's value or increment or decrement it's
             *    value.
             *    
             *    x = wepi.Var(n)
             *    wepi.Var(n, v)
             *    wepi.Var(n, v, '+')                          
             * 
             * --------------------------------------------------------- */

                addMethod(
                    this,
                    "Var",
                    function( v ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=get_var&get_var=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return $_id("wepiServReqTag").innerHTML;
                    }
                );
    
                addMethod(
                    this,
                    "Var",
                    function( v, c ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
                        var r = false;
                        var pp = c;
                        pp = pp.split("'").join("创");
                        pp = pp.split('"').join("创");
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=set_var&set_var=" + v + "&set_val=" + pp + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return r;
                    }
                );
    
                addMethod(
                    this,
                    "Var",
                    function( v, c, t ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
                        var r = false;
                        switch( t ) {
                            case '+': r = wepiServerReq( m, f, this.sessionID + "&wepi=set_add_var&set_add_var=" + v + "&set_val=" + c + "&random_mode=" + this.Count + "_" + new Date().getTime(), false ); break;
                            case '-': r = wepiServerReq( m, f, this.sessionID + "&wepi=set_sub_var&set_sub_var=" + v + "&set_val=" + c + "&random_mode=" + this.Count + "_" + new Date().getTime(), false ); break;
                            case '.': r = wepiServerReq( m, f, this.sessionID + "&wepi=set_str_var&set_str_var=" + v + "&set_val=" + c + "&random_mode=" + this.Count + "_" + new Date().getTime(), false ); break;
                            case '*': r = wepiServerReq( m, f, this.sessionID + "&wepi=set_mul_var&set_mul_var=" + v + "&set_val=" + c + "&random_mode=" + this.Count + "_" + new Date().getTime(), false ); break;
                            case '/': r = wepiServerReq( m, f, this.sessionID + "&wepi=set_div_var&set_div_var=" + v + "&set_val=" + c + "&random_mode=" + this.Count + "_" + new Date().getTime(), false ); break;
                        };
                        this.Count++;
                        return r;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Con()
             *    
             *    Allows to get a PHP-Constant                          
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Con",
                    function( v ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=get_con&get_con=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return r;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Env()
             *    
             *    Allows to get a environment value by PHP                          
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Env",
                    function( v ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=get_env&get_env=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return r;
                    }
                );

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
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
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
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
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
                        var f = this.path + "wepi_dyn_req.php";
						// *** //
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
//alert(vv+"\n"+cc);
                        var r = false;
                        r = wepiServerReqEx( m, f, this.sessionID + "&wepi=set_multi_sess&set_multi_sess=" + vv + "&set_val=" + cc + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return r;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Get()
             *    
             *    Allows to get a environment value by PHP                          
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Get",
                    function( v ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
                        var r = false;
                        r = wepiServerReq( m, f, this.sessionID + "&wepi=get_get&get_get=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                        return $_id("wepiServReqTag").innerHTML;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Post()
             *    
             *    Allows to get a environment value by PHP                          
             * 
               --------------------------------------------------------- */
/*
                addMethod(
                    this,
                    "Post",
                    function( v ) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
                        var r = false;
                        r = wepiServerReq( m, f, "wepi=get_post&get_post=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++:
                        return $_id("wepiServReqTag").innerHTML;
                    }
                );
*/
            /* ---------------------------------------------------------
             *
             *    wepi.Read()
             *    
             *    Allows to get a environment value by PHP                          
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Read",
                    function( v ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
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
             *    Allows to get a environment value by PHP                          
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Write",
                    function( v, t ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { f = this.path + "wepi_dyn_req_sess.php"; }
                        wepiServerReq( m, f, this.sessionID + "&wepi=write_file&write_file=" + v + "&set_val=" + t + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Append()
             *    
             *    Allows to get an environment value by PHP                          
             * 
               --------------------------------------------------------- */

                addMethod(
                    this,
                    "Append",
                    function( v, t ) {
                        var m = "GET";
                        var f = this.path + "wepi_dyn_req.php";
                        wepiServerReq( m, f, this.sessionID + "&wepi=append_file&append_file=" + v + "&set_val=" + t + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++;
                    }
                );

            /* ---------------------------------------------------------
             *
             *    wepi.Run()
             *    
             *    Allows to get an environment value by PHP                          
             * 
               --------------------------------------------------------- */
    
                addMethod(                                        
                    this,                                         
                    "Run",                                       
                    function( f ) {
                        var m = "GET";
                        var s = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { s = this.path + "wepi_dyn_req_sess.php"; }
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
             *    Allows to get an environment value by PHP                          
             * 
               --------------------------------------------------------- */

                this.Return = function( f ) {
                        var m = "GET";
                        var s = this.path + "wepi_dyn_req.php";
						if ( this.sessionID != "" ) { s = this.path + "wepi_dyn_req_sess.php"; }
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
                    }

            /* ---------------------------------------------------------
             *
             *    wepi.print()
             *    
             *    Allows to get a environment value by PHP                          
             * 
               --------------------------------------------------------- */
/*
              addMethod(
                  "Print",
                  this,
                  function(v) {
                        var m = "GET";
                        var f = "wepi_dyn_req.php";
                        var r = false;
                        r = wepiServerReq( m, f, "wepi=print&print=" + v + "&random_mode=" + this.Count + "_" + new Date().getTime(), false );
                        this.Count++:
                        return $_id("wepiServReqTag").innerHTML;
                  }
              );
*/
    }
