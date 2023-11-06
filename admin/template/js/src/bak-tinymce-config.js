;(function ( $, window, document, undefined ) {
    var tinyLanguage;
    switch(iso){
        case 'fr':
            tinyLanguage = 'fr_FR';
            break;
        case 'en':
            tinyLanguage = 'en_EN';
            break;
        default :
            tinyLanguage = iso;
            break;
    }
    $('.mceEditor').tinymce({
        // Location of TinyMCE script
        script_url : '/'+baseadmin+'/template/js/vendor/tiny_mce.'+editor_version+'/tinymce.min.js',
        theme: 'silver',
        skin: "oxide",
        mobile: {
            //theme: 'mobile',
            menubar: true,
            plugins: [ 'lists', 'autolink' ],
            toolbar: [ 'undo', 'bold', 'italic', 'styleselect' ]
        },
        relative_urls : false,
        //convert_urls: false,
        entity_encoding : "raw",
        plugins: [
            'hr advlist autolink lists clists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen textpattern wordcount directionality codesample',
            'insertdatetime media table paste template imagetools codesample youtube loremipsum responsivefilemanager mc_pages mc_cat mc_news mc_product lazyloadimage cryptmail tabpanel'
        ],
        toolbar1: 'fullscreen | code | removeformat | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | link unlink | bullist numlist | blockquote | forecolor | mc_pages mc_cat mc_news mc_product',
        imagetools_toolbar: "imageoptions",
        menu : {
            view   : {title : 'View'  , items : 'code | visualaid visualblocks | preview fullscreen'},
            edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall | searchreplace'},
            insert : {title : 'Insert', items : 'link anchor | image media youtube responsivefilemanager | tabpanel | template | table | hr | loremipsum | codesample'},
            format : {title : 'Format', items : 'formats | lazyloadimage cryptmail'},
            table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
            tools  : {title : 'Tools' , items : 'code'}
        },
        menubar: 'view edit insert format table tools',
        image_advtab: true,
        external_filemanager_path: '/'+baseadmin+'/template/js/vendor/filemanager/',
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            "filemanager" : '/'+baseadmin+'/template/js/vendor/filemanager/plugin.min.js'
        },
        min_height: 350,
        image_dimensions: true,
        image_class_list: [
            {title: 'None', value: ''},
            {title: 'Image Responsive', value: 'img-responsive'},
            {title: 'Image rounded', value: 'img-rounded'},
            {title: 'Image circle', value: 'img-circle'},
            {title: 'Image thumbnail', value: 'img-thumbnail'}
        ],
        link_class_list: [
            {title: 'None', value: ''},
            {title: 'TargetBlank', value: 'targetblank'},
            {title: 'link-arrow', value: 'link-arrow'},
            {title: 'btn-main', value: 'btn btn-main'},
            {title: 'btn-main-gradient', value: 'btn btn-main-gradient'},
            {title: 'btn-main-outline', value: 'btn btn-main-outline'},
            {title: 'btn-main-invert', value: 'btn btn-main-invert'},
            {title: 'btn-sd', value: 'btn btn-sd'},
            {title: 'btn-sd-outline', value: 'btn btn-sd-outline'},
            {title: 'btn-sd-invert', value: 'btn btn-sd-invert'},
            {title: 'btn-dark', value: 'btn btn-dark'},
            {title: 'btn-dark-outline', value: 'btn btn-dark-outline'},
            {title: 'btn-dark-invert', value: 'btn btn-dark-invert'}
        ],
        codesample_languages: [
            {text: 'HTML/XML', value: 'markup'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'CSS', value: 'css'},
            {text: 'PHP', value: 'php'},
            {text: 'Ruby', value: 'ruby'},
            {text: 'Python', value: 'python'},
            {text: 'Java', value: 'java'},
            {text: 'C', value: 'c'},
            {text: 'C#', value: 'csharp'},
            {text: 'C++', value: 'cpp'},
            {text: 'Smarty', value: 'smarty'},
            {text: 'Less', value: 'less'},
            {text: 'Sass (Sass)', value: 'sass'},
            {text: 'Sass (Scss)', value: 'sass'}
        ],
        formats: {
            underline: {inline : 'u'},
            strikethrough: {inline: 'del'},
            alignleft: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'text-left'},
            aligncenter: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'text-center'},
            alignright: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'text-right'},
            alignjustify: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'text-justify'}
        },
        style_formats: [
            {title: 'Link', items: [
                {title: 'TargetBlank', selector: 'a', classes: 'targetblank'}
            ]},
            {title: 'Image', items: [
                {title: 'Lightbox simple', selector: 'a', classes: 'img-zoom',
                    attributes: {
                        'data-fancybox-group': 'lightbox'
                    }
                },
                {title: 'Lightbox galery', selector: 'a', classes: 'img-gallery',
                    attributes: {
                        'data-fancybox-group': 'lightbox'
                    }
                },
                {title: 'Image Responsive', selector: 'img', classes: 'img-responsive'},
                /*{title: 'Image float left', selector: 'img', classes: 'img-float float-left'},
                {title: 'Image float right', selector: 'img', classes: 'img-float float-right'},*/
                {title: 'Image center', selector: 'img', classes: 'center-block'},
                {title: 'Image rounded', selector: 'img', classes: 'img-rounded'},
                {title: 'Image circle', selector: 'img', classes: 'img-circle'},
                {title: 'Image thumbnail', selector: 'img', classes: 'img-thumbnail'}
            ]},
            {title: 'Table', items: [
                {title: 'Table', selector: 'table', classes: 'table'},
                {title: 'Table Condensed', selector: 'table', classes: 'table-condensed'},
                {title: 'Table Bordered', selector: 'table', classes: 'table-bordered'},
                {title: 'Table Hover', selector: 'table', classes: 'table-hover'},
                {title: 'Table Striped', selector: 'table', classes: 'table-striped'},
                {title: 'TR', items: [
                    {title : 'Active', selector : 'tr', classes : 'active'},
                    {title : 'Success', selector : 'tr', classes : 'success'},
                    {title : 'Warning', selector : 'tr', classes : 'warning'},
                    {title : 'Danger', selector : 'tr', classes : 'danger'},
                    {title : 'Info', selector : 'tr', classes : 'info'}
                ]},
                {title: 'TD', items: [
                    {title : 'Active', selector : 'td', classes : 'active'},
                    {title : 'Success', selector : 'td', classes : 'success'},
                    {title : 'Warning', selector : 'td', classes : 'warning'},
                    {title : 'Danger', selector : 'td', classes : 'danger'},
                    {title : 'Info', selector : 'td', classes : 'info'}
                ]},
                {title: "Blocks", items: [
                    {title: "Div responsive", block: "div", classes: 'table-responsive'}
                ]}
            ]},
            {title: 'Helper classes', items: [
                {title: "Blocks", items: [
                    {title: "Div center", block: "div", classes: 'center-block'}
                ]},
                {title: "Header", items: [
                    {title: "Title 1", selector: "h1,h2,h3,h4,h5,h6,p", classes: 'h1'},
                    {title: "Title 2", selector: "h2,h1,h3,h4,h5,h6,p", classes: 'h2'},
                    {title: "Title 3", selector: "h3,h1,h2,h4,h5,h6,p", classes: 'h3'},
                    {title: "Title 4", selector: "h4,h1,h2,h3,h5,h6,p", classes: 'h4'},
                    {title: "Title 5", selector: "h5,h1,h2,h3,h4,h6,p", classes: 'h5'},
                    {title: "Title 6", selector: "h6,h1,h2,h3,h4,h5,p", classes: 'h6'}
                ]},
                {title: "Alignment", items: [
                    {title: "Text Center", selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table", classes: 'text-center'},
                    {title: "Clearfix", selector: "p,h1,h2,h3,h4,h5,h6,div,ul,ol,table", classes: 'clearfix'}
                ]},
                {title: "Paragraph", items: [
                    {title: "Text Center", block: "p", classes: 'text-center'},
                    {title: "Text Muted", block: "p", classes: 'text-muted'},
                    {title: "Text Primary", block: "p", classes: 'text-primary'},
                    {title: "Text Success", block: "p", classes: 'text-success'},
                    {title: "Text Info", block: "p", classes: 'text-info'},
                    {title: "Text Warning", block: "p", classes: 'text-warning'},
                    {title: "Text Danger", block: "p", classes: 'text-danger'},
                    {title: "Bg Primary", block: "p", classes: 'bg-primary'},
                    {title: "Bg Success", block: "p", classes: 'bg-success'},
                    {title: "Bg Info", block: "p", classes: 'bg-info'},
                    {title: "Bg Warning", block: "p", classes: 'bg-warning'},
                    {title: "Bg Danger", block: "p", classes: 'bg-danger'}
                ]},
                {title: "List", items: [
                    {title: "Bullet list", block: "ul", classes: 'bullet-list'},
                    {title: 'Circle List', block: "ul", classes: 'circle-list'},
                    {title: 'Square List', block: "ul", classes: 'square-list'},
                    {title: 'Arrow List', block: "ul", classes: 'arrow-list'},
                    {title: 'Label List', block: "ul", classes: 'label-list'}
                ]}
            ]},
            {title: 'Alert', items: [
                {title: "Blocks", items: [
                    {title: "Alert success", block: "div", classes: 'alert alert-success'},
                    {title: "Alert info", block: "div", classes: 'alert alert-info'},
                    {title: "Alert warning", block: "div", classes: 'alert alert-warning'},
                    {title: "Alert danger", block: "div", classes: 'alert danger-info'}
                ]},
                {title: "Paragraph", items: [
                    {title: "Alert success", block: "p", classes: 'alert alert-success'},
                    {title: "Alert info", block: "p", classes: 'alert alert-info'},
                    {title: "Alert warning", block: "p", classes: 'alert alert-warning'},
                    {title: "Alert danger", block: "p", classes: 'alert danger-info'}
                ]},
                {title: "Link", items: [
                    {title: 'Alert link', selector: 'a', classes: 'alert-link'}
                ]}
            ]},
            {title: 'Embed', items: [
                {title: "Blocks", items: [
                    {title: "Media 16:9", block: "div", classes: 'embed-responsive embed-responsive-16by9'},
                    {title: "Media 4:3", block: "div", classes: 'embed-responsive embed-responsive-4by3'}
                ]}
            ]}
        ],
        cbullet_styles: [
            'default',
            /*{title: 'disc', style: 'disc'},
            {title: 'circle', style: 'circle'},
            {title: 'square', style: 'square'},*/
            {title: 'Bullet List', block: "ul", classes: 'bullet-list'},
            {title: 'Circle List', block: "ul", classes: 'circle-list'},
            {title: 'Square List', block: "ul", classes: 'square-list'},
            {title: 'Arrow List', block: "ul", classes: 'arrow-list'},
            {title: 'Label List', block: "ul", classes: 'label-list'}
        ],
        templates : '/'+baseadmin+'/index.php?controller=setting&action=getSnippet',
        language : tinyLanguage,
        schema: "html5",
        extended_valid_elements: "+img[class|src|srcset|sizes|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|loading],+svg[*],+g[*],+path[*],+span[*],+i[*],+iframe[src|width|height|name|align|class],+strong[*]",
        content_css : content_css
    });
})( jQuery, window, document );