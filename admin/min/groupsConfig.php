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
        '//libjs/vendor/jquery-3.0.0.min.js',
        '//'.PATHADMIN.'/template/js/vendor/bootstrap.min.js',
        '//libjs/vendor/Chart.bundle.min.js',
        '//libjs/vendor/bootstrap2-toggle.min.js',
        '//libjs/vendor/jquery.form.3.51.min.js',
        '//libjs/vendor/jquery.validate.1.15.0.min.js',
        '//libjs/vendor/redirect.min.js'
    ),
	'jimagine' => array(
        '//libjs/vendor/jimagine/config.js',
        '//libjs/vendor/jimagine/jmConstant.js',
	    '//libjs/vendor/jimagine/plugins/jquery.jmRequest.js',
        '//libjs/vendor/jimagine/plugins/jquery.jmShowIt.js'
    ),
    'globalize'=> array(
        '//libjs/vendor/cldr.js',
        '//libjs/vendor/globalize.js',
        '//libjs/vendor/globalize/message.js'
    ),
    'publiccss' => array(
        '//'.PATHADMIN.'/template/css/bootstrap/critical.min.css',
        '//'.PATHADMIN.'/template/css/bootstrap/bootstrap.min.css',
        '//'.PATHADMIN.'/template/css/bootstrap2-toggle.min.css'
    ),
	'pdfcss' => array(
		'//'.PATHADMIN.'/template/css/pdf/print.min.css',
	),
    'maxAge' => 31536000,
    'setExpires' => time() + 86400 * 365
);