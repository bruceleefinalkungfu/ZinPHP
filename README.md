It's a PHP project that acts as a Front Controller for whatever APIs one may create. 

________________

It heavily works on the concept of <b>Convention over configuration</b>. Hence the application will have some <b>pre-defined, reserverd query parameters</b>. We're going to go through them. Let's assume the base_url is <b>http:\\\\localhost:80</b>

<b>1. app or a</b>

http:\\\\localhost:80?<b>app=</b>Xyz indicates Xyz class will be executed but its file has to be included in index.php. Since no method is passed. <b>default_method()</b> will be executed i.e. if it exists.

One predefined App is <b>db</b>

<b>2. method or m</b>

http:\\\\localhost:80?<b>method=</b>hello indicates hello() method will be executed. Since no class (app) is passed. <b>DefaultApp.hello()</b> will be executed i.e. if it exists.

Predefined methods are <b>get, create, update, delete</b>

<b>3. debug</b>

In your code call CommonUtil.debug($msg, $debugType) or CommonUtil.d($msg) to print log messages on the web page but the logs won't be visible until you add debug as the query parameter.

http:\\\\localhost:80?app=Xyz&method=hello&<b>debug</b> it will show debug messages produced by <b>CommonUtil.debug('xyz msg', '')</b> or <b>CommonUtil.d('xyz msg')</b>

http:\\\\localhost:80?app=Xyz&method=hello&<b>debug-all</b> it will show all the debug messages.

http:\\\\localhost:80?app=Xyz&method=hello&<b>debug-my</b> it will show debug messages produced by <b>CommonUtil.debug('xyz msg', 'my')</b>. But make an additional entry of <b>my</b> in GlobalConfig.php $debug_types array. <b>debug-my</b> can be written in many ways my_debug, debug_my, debugmy, mydebug, my-debug. 

<b>4. table or tab or t</b>

http:\\\\localhost:80?app=db&m=get&<b>table=tab-employee</b> It will fetch all the entries from tab-employee table, given the correct db configuration is available in <b>DBConfig.php</b>

<b>5. column1 or col1 or c1</b> and <b>value1 or val1 or v1</b>

http:\\\\localhost:80?app=db&m=get&table=tab-employee&<b>col1=name&val1=Anurag</b> It will fetch all the entries that have Anurg value in the name column
