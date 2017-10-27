<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/**
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See https://github.com/mrclay/minify/blob/master/docs/CustomServer.wiki.md for other ideas
 **/

return array(
    'jquery' => array(
        '//libjs/vendor/jquery-3.0.0.min.js'
    ),
    'form' => array(
		'//libjs/vendor/jquery.form.4.2.1.min.js',
		'//libjs/vendor/jquery.validate.1.17.0.min.js',
		'//libjs/vendor/jimagine/plugins/jquery.jmRequest.js'
	),
    'formAdvanced' => array(
		'//libjs/vendor/jquery.form.4.2.1.min.js',
		'//libjs/vendor/additional-methods.1.17.0.min.js',
		'//libjs/vendor/jquery.validate.1.17.0.min.js',
		'//libjs/vendor/jimagine/plugins/jquery.jmRequest.js',
		'//libjs/vendor/redirect.min.js'
	),
    'globalize'=> array(
        '//libjs/vendor/cldr.js',
        '//libjs/vendor/globalize.js',
        '//libjs/vendor/globalize/message.js'
    ),
    'maxAge' => 31536000,
    'setExpires' => time() + 86400 * 365
);
