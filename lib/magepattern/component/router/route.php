<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Mage Pattern.
# The toolkit PHP for developer
# Copyright (C) 2012 - 2013 Gerits Aurelien contact[at]aurelien-gerits[dot]be
#
# OFFICIAL TEAM MAGE PATTERN:
#
#   * Gerits Aurelien (Author - Developer) contact[at]aurelien-gerits[dot]be
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# Redistributions of source code must retain the above copyright notice,
# this list of conditions and the following disclaimer.
#
# Redistributions in binary form must reproduce the above copyright notice,
# this list of conditions and the following disclaimer in the documentation
# and/or other materials provided with the distribution.
#
# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade Mage Pattern to newer
# versions in the future. If you wish to customize Mage Pattern for your
# needs please refer to http://www.magepattern.com for more information.
#
# -- END LICENSE BLOCK -----------------------------------

class router_route {


	/**
	 * @var array The route patterns and their handling functions
	 */
	private $routeCollection = array();


	/**
	 * @var array The before middleware route patterns and their handling functions
	 */
	private $befores = array();


	/**
	 * @var object The function to be executed when no route has been matched
	 */
	private $notFound;


	/**
	 * @var string Current baseroute, used for (sub)route mounting
	 */
	private $baseroute = '';


	/**
	 * @var string The Request Method that needs to be handled
	 */
	private $method = '';

    /**
     * @var array pattern collection
     */
    private $patternCollection = array(
        '(:num)' => '([0-9]+)', 
        '(:any)' => '([a-zA-Z0-9\.\-_%=]+)', 
        '(:all)' => '(.*)', 
        '/(:num?)' => '(?:/([0-9]+))?', 
        '/(:any?)' => '(?:/([a-zA-Z0-9\.\-_%=]+))?', 
        '/(:all?)' => '(?:/(.*))?'
    );


	/**
	 * Store a before middleware route and a handling function to be executed when accessed using one of the specified methods
	 *
	 * @param string $methods Allowed methods, | delimited
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function before($methods, $pattern, $fn) {

		$pattern = $this->baseroute . '/' . trim($pattern, '/');
		$pattern = $this->baseroute ? rtrim($pattern, '/') : $pattern;

		foreach (explode('|', $methods) as $method) {
			$this->befores[$method][] = array(
				'pattern' => $pattern,
				'fn' => $fn
			);
		}

	}

	/**
	 * Store a route and a handling function to be executed when accessed using one of the specified methods
	 *
	 * @param string $methods Allowed methods, | delimited
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function match($methods, $pattern, $fn) {

		$pattern = $this->baseroute . '/' . trim($pattern, '/');
		$pattern = $this->baseroute ? rtrim($pattern, '/') : $pattern;

		foreach (explode('|', $methods) as $method) {
			$this->routeCollection[$method][] = array(
				'pattern' => $pattern,
				'fn' => $fn
			);
		}

	}


	/**
	 * Shorthand for a route accessed using GET
	 *
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function get($pattern, $fn) {
		$this->match('GET', $pattern, $fn);
	}


	/**
	 * Shorthand for a route accessed using POST
	 *
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function post($pattern, $fn) {
		$this->match('POST', $pattern, $fn);
	}


	/**
	 * Shorthand for a route accessed using PATCH
	 *
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function patch($pattern, $fn) {
		$this->match('PATCH', $pattern, $fn);
	}


	/**
	 * Shorthand for a route accessed using DELETE
	 *
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function delete($pattern, $fn) {
		$this->match('DELETE', $pattern, $fn);
	}


	/**
	 * Shorthand for a route accessed using PUT
	 *
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function put($pattern, $fn) {
		$this->match('PUT', $pattern, $fn);
	}


	/**
	 * Shorthand for a route accessed using OPTIONS
	 *
	 * @param string $pattern A route pattern such as /about/system
	 * @param object $fn The handling function to be executed
	 */
	public function options($pattern, $fn) {
		$this->match('OPTIONS', $pattern, $fn);
	}


	/**
	 * Mounts a collection of callables onto a base route
	 *
	 * @param string $baseroute The route subpattern to mount the callables on
	 * @param callable $fn The callabled to be called
	 */
	public function mount($baseroute, $fn) {

		// Track current baseroute
		$curBaseroute = $this->baseroute;

		// Build new baseroute string
		$this->baseroute .= $baseroute;

		// Call the callable
		call_user_func($fn);

		// Restore original baseroute
		$this->baseroute = $curBaseroute;

	}


	/**
	 * Get all request headers
	 * @return array The request headers
	 */
	public function getRequestHeaders() {

		// getallheaders available, use that
		if (function_exists('getallheaders')) return getallheaders();

		// getallheaders not available: manually extract 'm
		$headers = array();
		foreach ($_SERVER as $name => $value) {
			if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
				$headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;

	}


	/**
	 * Get the request method used, taking overrides into account
	 * @return string The Request method to handle
	 */
	public function getRequestMethod() {

		// Take the method as found in $_SERVER
		$method = $_SERVER['REQUEST_METHOD'];

		// If it's a HEAD request override it to being GET and prevent any output, as per HTTP Specification
		// @url http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.4
		if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
			ob_start();
			$method = 'GET';
		}

		// If it's a POST request, check for a method override header
		else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$headers = $this->getRequestHeaders();
			if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
				$method = $headers['X-HTTP-Method-Override'];
			}
		}

		return $method;

	}


    /**
     * Execute the router: Loop all defined before middlewares and routes, and execute the handling function if a mactch was found
     *
     * @param object $callback Function to be executed after a matching route was handled (= after router middleware)
     * @return bool
     */
	public function build($callback = null) {
        // In case one is using PHP 5.4's built-in server
        $filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
        if (php_sapi_name() === 'cli-server' && is_file($filename)) {
            return false;
        }

		// Define which method we need to handle
		$this->method = $this->getRequestMethod();

		// Handle all before middlewares
		if (isset($this->befores[$this->method]))
			$this->handle($this->befores[$this->method]);

		// Handle all routes
		$numHandled = 0;
		if (isset($this->routeCollection[$this->method]))
			$numHandled = $this->handle($this->routeCollection[$this->method], true);

		// If no route was handled, trigger the 404 (if any)
		if ($numHandled == 0) {
			if ($this->notFound && is_callable($this->notFound)) call_user_func($this->notFound);
			else header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		}
		// If a route was handled, perform the finish callback (if any)
		else {
			if ($callback) {
                if(is_callable($callback)){
                    $args = func_get_args();
                    call_user_func(
                        $callback,
                        $args
                    );
                }
            }
		}

		// If it originally was a HEAD request, clean up after ourselves by emptying the output buffer
		if ($_SERVER['REQUEST_METHOD'] == 'HEAD') ob_end_clean();

	}


	/**
	 * Set the 404 handling function
	 * @param object $fn The function to be executed
	 */
	public function set404($fn) {
		$this->notFound = $fn;
	}


	/**
	 * Handle a a set of routes: if a match is found, execute the relating handling function
	 * @param array $routes Collection of route patterns and their handling functions
	 * @param boolean $quitAfterRun Does the handle function need to quit after one route was matched?
	 * @return int The number of routes handled
	 */
	private function handle($routes, $quitAfterRun = false) {

		// Counter to keep track of the number of routes we've handled
		$numHandled = 0;

		// The current page URL
		$uri = $this->getCurrentUri();

		// Variables in the URL
		$urlvars = array();

		// Loop all routes
		foreach ($routes as $route) {

			// Load automatic pattern
			$patterns = $this->patternCollection;
            
            $route['pattern'] = str_replace(array_keys($patterns), array_values($patterns), $route['pattern']);
			// we have a match!
			if (preg_match_all('#^' . $route['pattern'] . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)) {

				// Rework matches to only contain the matches, not the orig string
				$matches = array_slice($matches, 1);

				// Extract the matched URL parameters (and only the parameters)
				$params = array_map(function($match, $index) use ($matches) {

					// We have a following parameter: take the substring from the current param position until the next one's position (thank you PREG_OFFSET_CAPTURE)
					if (isset($matches[$index+1]) && isset($matches[$index+1][0]) && is_array($matches[$index+1][0])) {
						return trim(substr($match[0][0], 0, $matches[$index+1][0][1] - $match[0][1]), '/');
					}

					// We have no following paramete: return the whole lot
					else {
						return (isset($match[0][0]) ? trim($match[0][0], '/') : null);
					}

				}, $matches, array_keys($matches));

				// call the handling function with the URL parameters
				call_user_func_array($route['fn'], $params);

				// increment numHandled
				$numHandled++;

				// If we need to quit, then quit
				if ($quitAfterRun) break;

			}

		}

		// Return the number of routes handled
		return $numHandled;

	}


	/**
	 * Define the current relative URI
	 * @return string
	 */
	private function getCurrentUri() {

		// Get the current Request URI and remove rewrite basepath from it (= allows one to run the router in a subfolder)
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));

		// Don't take query params into account on the URL
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));

		// Remove trailing slash + enforce a slash at the start
		$uri = '/' . trim($uri, '/');

		return $uri;

	}

}

// EOF
