Plugin loremipsum for TinyMCE 5
======================

Insert Lorem Ipsum paragraph

### version 

[![release](https://img.shields.io/github/release/gtraxx/tinymce-lorem-ipsum.svg)](https://github.com/gtraxx/tinymce-lorem-ipsum/releases/latest)


Authors
-------

 * Gerits Aurelien (Author-Developer) aurelien[at]magix-cms[point]com

Official link in french :

### Installation
 * Download the archive
 * Unzip archive in tinyMCE plugin directory (tiny_mce/plugins/)

### Configuration
 ```html
<script type="text/javascript">
tinymce.init({
	selector: "textarea",
	plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste loremipsum"
			],
	toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image| loremipsum",
	});
</script>
```

<pre>
This file is part of tinyMCE.
YouTube for tinyMCE
Copyright (C) 2011 - 2019  Gerits Aurelien <aurelien[at]magix-cms[dot]com>

Redistributions of files must retain the above copyright notice.
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see .

####DISCLAIMER

Do not edit or add to this file if you wish to upgrade jimagine to newer
versions in the future. If you wish to customize jimagine for your
needs please refer to magix-dev.be for more information.
</pre>
