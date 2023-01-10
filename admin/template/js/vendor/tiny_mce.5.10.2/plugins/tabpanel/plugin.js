tinymce.PluginManager.add('tabpanel', function(editor, url) {
    var walker = tinymce.dom.TreeWalker;
    editor.ui.registry.addNestedMenuItem('tabpanel', {
        //icon: 'tabpanel',
        text: "Tabs",
        tooltip: "Tabs",
        getSubmenuItems: function () {
            return [
                {
                    type: 'menuitem',
                    //icon: 'tab',
                    text: "New panel",
                    tooltip: "New panel",
                    onAction: function () {
                        let el = editor.selection.getNode();
                        let parent = el.parentNode;
                        let tabpanels = editor.dom.getParents(el,'.tabpanels')[0];
                        if(tabpanels !== undefined && tabpanels !== null) {
                            let tabs = editor.dom.select('.nav-tabs',tabpanels)[0],
                                panels = editor.dom.select('.tab-content',tabpanels)[0],
                                nb = tabs.children.length;

                            nb++;

                            editor.dom.add(tabs,'li',false,'<a role="tab" href="#tab'+nb+'" aria-controls="tab'+nb+'" data-toggle="tab"><img class="img-responsive" src="#" alt="color'+nb+'" width="250" height="250" /><span>Color'+nb+'</span></a>');
                            editor.dom.add(panels,'section',{id:'tab'+nb,class:'tab-pane',role:'tabpanel'},'<p>color'+nb+'</p>');
                        }
                    }
                },
                {
                    type: 'menuitem',
                    //icon: 'tab',
                    text: "Remove panel",
                    tooltip: "Remove panel",
                    onAction: function () {
                        let el = editor.selection.getNode();
                        let parent = el.parentNode;
                        let tabpanels = editor.dom.getParents(el,'.tabpanels')[0];
                        if(tabpanels !== undefined && tabpanels !== null) {
                            let tabs = editor.dom.select('.nav-tabs',tabpanels)[0],
                                panels = editor.dom.select('.tab-content',tabpanels)[0];

                            tinymce.activeEditor.dom.remove(tinymce.activeEditor.dom.select('li:last-child',tabs));
                            tinymce.activeEditor.dom.remove(tinymce.activeEditor.dom.select('section:last-child',panels));
                        }
                    }
                }
            ];
        }
    });
});
// Load the required translation files
tinymce.PluginManager.requireLangPack('tabpanel', 'en_EN,fr_FR');