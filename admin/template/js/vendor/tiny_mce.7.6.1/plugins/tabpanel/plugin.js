(function () {
    'use strict';
    tinymce.PluginManager.requireLangPack("tabpanel");
    tinymce.PluginManager.add('tabpanel', function (editor, url) {

        // On utilise la fonction de traduction native
        const _ = (text) => editor.translate(text);

        const createTabSystem = () => {
            const html = `
                <div class="tabpanels">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a role="tab" href="#tab1" aria-controls="tab1" data-toggle="tab">
                                <span>${_('Tab')} 1</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <section id="tab1" class="tab-pane active" role="tabpanel">
                            <p>Contenu 1</p>
                        </section>
                    </div>
                </div><p>&nbsp;</p>`;
            editor.insertContent(html);
        };

        const addPanel = () => {
            const el = editor.selection.getNode();
            const tabpanels = editor.dom.getParent(el, '.tabpanels');
            if (tabpanels) {
                const tabs = editor.dom.select('.nav-tabs', tabpanels)[0];
                const panels = editor.dom.select('.tab-content', tabpanels)[0];
                let nb = tabs.children.length + 1;
                editor.dom.add(tabs, 'li', { role: 'presentation' },
                    `<a role="tab" href="#tab${nb}" aria-controls="tab${nb}" data-toggle="tab"><span>${_('Tab')} ${nb}</span></a>`
                );
                editor.dom.add(panels, 'section', { id: 'tab' + nb, class: 'tab-pane', role: 'tabpanel' }, `<p>Contenu ${nb}</p>`);
                editor.nodeChanged();
            }
        };

        editor.ui.registry.addNestedMenuItem('tabpanel', {
            text: _('Tabs'),
            icon: 'table-insert-column-after',
            getSubmenuItems: () => [
                { type: 'menuitem', text: _('Create Tab System'), icon: 'table-insert-row-after', onAction: createTabSystem },
                { type: 'menuitem', text: _('New panel'), icon: 'plus', onAction: addPanel },
                { type: 'menuitem', text: _('Remove panel'), icon: 'remove', onAction: () => { /* ... code suppression ... */ } }
            ]
        });
    });
})();