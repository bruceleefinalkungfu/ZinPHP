It's a PHP project that acts as a Front Controller for whatever APIs one may create

________________

Let's assume the base_url is <b>http:\\\\localhost:80</b>

The application will have some pre-defined query parameters.

<b>1. app or a</b>

http:\\\\localhost:80?<b>app=</b>Xyz indicates Xyz class will be executed but its file has to be included in index.php. Since no method is passed. <b>default_method()</b> will be executed i.e. if it exists.

<b>2. method or m</b>

http:\\\\localhost:80?<b>method=</b>hello indicates hello() method will be executed. Since no class (app) is passed. <b>DefaultApp.hello()</b> will be executed i.e. if it exists.

<b>3. debug</b>

In your code call CommonUtil.debug($msg, $debugType) or CommonUtil.d($msg) to print log messages on the web page but the logs won't be visible until you add debug as the query parameter. Let's say 

http:\\\\localhost:80?app=Xyz&method=hello&<b>debug</b> it will show debug messages produced by <b>CommonUtil.debug('xyz msg', '')</b> or <b>CommonUtil.d('xyz msg')</b>

http:\\\\localhost:80?app=Xyz&method=hello&<b>debug-all</b> it will show all the debug messages.

http:\\\\localhost:80?app=Xyz&method=hello&<b>debug-my</b> it will show debug messages produced by <b>CommonUtil.debug('xyz msg', 'my')</b>. But make an additional entry of <b>my</b> in GlobalConfig.php $debug_types array
