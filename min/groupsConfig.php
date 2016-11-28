<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
    'publicjs' => array(
        '//skin/js/vendor/jquery-3.0.0.min.js',
        '//skin/js/vendor/bootstrap.min.js',
        '//skin/js/vendor/Chart.bundle.min.js',
        '//skin/js/vendor/bootstrap2-toggle.min.js',
        '//skin/js/vendor/jquery.form.3.51.min.js',
        '//skin/js/vendor/jquery.validate.1.15.0.min.js',
        '//skin/js/vendor/redirect.js'
    ),
	'jimagine' => array(
        '//skin/js/vendor/jimagine/config.js',
        '//skin/js/vendor/jimagine/jmConstant.js',
	    '//skin/js/vendor/jimagine/plugins/jquery.nicenotify.js',
        '//skin/js/vendor/jimagine/plugins/jquery.jmShowIt.js'
    ),
    'globalize'=> array(
        '//skin/js/vendor/cldr.js',
        '//skin/js/vendor/globalize.js',
        '//skin/js/vendor/globalize/message.js'
    ),
    'publiccss' => array(
        '//skin/css/bootstrap/critical.min.css',
        '//skin/css/bootstrap/bootstrap.min.css',
        '//skin/css/bootstrap2-toggle.min.css'
    ),
	'pdfcss' => array(
		'//skin/css/pdf/print.min.css',
	),
    'maxAge' => 31536000,
    'setExpires' => time() + 86400 * 365
);