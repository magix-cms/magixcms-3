tinymce.PluginManager.add('lazyloadimage', function(editor, url) {
    editor.addButton('lazyloadimage', {
        text: 'LazyImg',
        icon: 'fa fas fa-spinner',
        onclick: function() {
            let el = tinymce.dom.Selection.getNode();
            if(el.is('img')) {
                let src = tinymce.DOM.getAttrib(el,'src');
                tinymce.DOM.addClass('lazyload');
                tinymce.DOM.setAttrib(el,'data-src',src);
                tinymce.DOM.setAttrib(el,'src','');
            }
        }
    });
});