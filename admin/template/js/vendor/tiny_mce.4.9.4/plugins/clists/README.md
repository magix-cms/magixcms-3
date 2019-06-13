Plugin clists for TinyMCE 4
===========================

Override the bullet list button to allow custom bullet list styles

clists plugin is not compatible with tinymce 5 for now

### version 

![GitHub release](https://img.shields.io/github/release/xarksass/tinymce-plugin-clists.svg?style=for-the-badge)

Authors
-------

 * Salvatore Di Salvo (Author-Developer) disalvo.infographiste[at]gmail[point]com

### Installation
 * Download the dist/clists.zip archive
 * Unzip archive in tinyMCE plugin directory (tiny_mce/plugins/)

### Configuration
 ```html
<script type="text/javascript">
tinymce.init({
	selector: "textarea",
	plugins: ["lists clists"],
	toolbar1: 'bullist',
	cbullet_styles: [
        'default',
        {title: 'disc', style: 'disc'},
        {title: 'circle', style: 'circle'},
        {title: 'square', style: 'square'},
        {title: 'Bullet List', classes: 'bullet-list'},
        {title: 'Circle List', classes: 'circle-list'},
        {title: 'Square List', classes: 'square-list'},
        {title: 'Arrow List', classes: 'arrow-list'},
        {title: 'Label List', classes: 'label-list'}
    ]
	});
</script>
```

The default style will remove any style passed to the UL list.
For custom style you can:
 * Add a string name, this will add this string as a class
 * Add an object with a title (used for the select list) and either
    * a style, the value (a string) will be used as value for the list-style-type
    * a classes, the value (a string) will be added to the UL list as a class

### Languages
 * English
 * French
 
You can send me translations in other languages

<pre>
This file is part of tinyMCE.
clists for tinyMCE
Copyright (C) 2019  Salvatore Di Salvo <disalvo.infographiste[at]gmail[dot]com>

Redistributions of files must retain the above copyright notice.
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
</pre>
