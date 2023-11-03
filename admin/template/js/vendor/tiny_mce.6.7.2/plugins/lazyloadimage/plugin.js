tinymce.PluginManager.add('lazyloadimage', function(editor, url) {
    /*
    Add a custom icon to TinyMCE
     */
    editor.ui.registry.addIcon('lazyloading', '<svg width="24" height="24"><use xlink:href="'+url+'/img/lazyloading.svg#lazyloading"></use></svg>');
    editor.ui.registry.addMenuItem('lazyloadimage', {
        icon: 'lazyloading',
        text: "LazyImg",
        tooltip: "LazyImg",
        onAction: () => {
            let el = editor.selection.getNode();
            if(el.nodeName === 'IMG') {
                let src = tinymce.DOM.getAttrib(el,'src');
                editor.dom.addClass(el, 'lazyload');
                tinymce.DOM.setAttrib(el,'loading','lazy');
                //tinymce.DOM.setAttrib(el,'data-src',src);
                //tinymce.DOM.setAttrib(el,'src','');
            }
        }
    });
});