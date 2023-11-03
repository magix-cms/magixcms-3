/*!
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of tinyMCE.
 # clists for tinyMCE
 # Copyright (C) 2019  Salvatore Di Salvo <disalvo.infographiste[at]gmail[dot]com>
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program. If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 */
/**
 * TinyMCE clists Plugin
 * This module, based on the advlist plugin, overwrite the bulletlist button and allow the add custom styles.
 * v 1.1.0 Stable
 */
(function() {
    tinymce.PluginManager.requireLangPack("clists");
    var clists = (function () {
        "use strict";

        let global = tinymce.util.Tools.resolve("tinymce.PluginManager"),
            global$1 = tinymce.util.Tools.resolve("tinymce.util.Tools"),
            constant = function (value) {
                return () => value;
            },
            never = constant(false),
            always = constant(true),
            never$1 = never,
            always$1 = always,
            none = () => NONE,
            NONE = function () {
                let eq = (o) => o.isNone(),
                    call = (thunk) => thunk(),
                    id = (n) => n,
                    noop = function () {},
                    nul = () => null,
                    undef = () => undefined,
                    me = {
                        fold: (n, s) => n(),
                        isSome: never$1,
                        isNone: always$1,
                        getOr: id,
                        getOrThunk: call,
                        getOrDie: function (msg) {
                            throw new Error(msg || 'error: getOrDie called on none.');
                        },
                        getOrNull: nul,
                        getOrUndefined: undef,
                        or: id,
                        orThunk: call,
                        map: none,
                        ap: none,
                        each: noop,
                        bind: none,
                        flatten: none,
                        exists: never$1,
                        forall: always$1,
                        filter: none,
                        equals: eq,
                        equals_: eq,
                        toArray: function () {
                            return [];
                        },
                        toString: constant('none()')
                    };

                if (Object.freeze)
                    Object.freeze(me);
                return me;
            }(),
            some = function (a) {
                let constant_a = () => a,
                    self = () => me,
                    map = (f) => some(f(a)),
                    bind = (f) => f(a),
                    me = {
                        fold: (n, s) => s(a),
                        is: (v) => a === v,
                        isSome: always$1,
                        isNone: never$1,
                        getOr: constant_a,
                        getOrThunk: constant_a,
                        getOrDie: constant_a,
                        getOrNull: constant_a,
                        getOrUndefined: constant_a,
                        or: self,
                        orThunk: self,
                        map: map,
                        ap: (optfab) => optfab.fold(none, (fab) => some(fab(a))),
                        each: (f) => f(a),
                        bind: bind,
                        flatten: constant_a,
                        exists: bind,
                        forall: bind,
                        filter: (f) => f(a) ? me : NONE,
                        equals: (o) => o.is(a),
                        equals_: (o, elementEq) => o.fold(never$1, (b) => elementEq(a, b)),
                        toArray: () => [a],
                        toString: () => 'some(' + a + ')'
                    };
                return me;
            },
            from = (value) => value === null || value === undefined ? NONE : some(value),
            Option = {
                some: some,
                none: none,
                from: from
            },
            getBulletStyles = function (editor) {
                let styles = editor.getParam('cbullet_styles', [
                    'default',
                    {title: 'disc', style: 'disc'},
                    {title: 'circle', style: 'circle'},
                    {title: 'square', style: 'square'},
                    {title: 'bullet-list', classes: 'bullet-list'},
                    {title: 'circle-list', classes: 'circle-list'},
                    {title: 'square-list', classes: 'square-list'},
                    {title: 'arrow-list', classes: 'arrow-list'},
                    {title: 'label-list', classes: 'label-list'}
                ]);
                return styles ? styles : [];
            },
            getBulletStylesList = function (editor) {
                let styles = Settings.getBulletStyles(editor);
                let stylesList = [];

                if(styles.length > 0) {
                    var n = 0;
                    for(var i=0;i < styles.length;i++) {
                        if(typeof styles[i] === 'string')  {
                            stylesList[n] = styles[i];
                            n++;
                        }
                        if(typeof styles[i] === 'object' && styles[i].title !== undefined)  {
                            stylesList[n] = styles[i]['title'];
                            n++;
                        }
                    }
                }
                return stylesList;
            },
            getBulletClassList = function (editor) {
                let styles = Settings.getBulletStyles(editor);
                let classesList = [];

                if(styles.length > 0) {
                    var n = 0;
                    for(var i=0;i < styles.length;i++) {
                        if(typeof styles[i] === 'object' && styles[i].classes !== undefined)  {
                            classesList[n] = styles[i]['classes'];
                            n++;
                        }
                    }
                }
                return classesList;
            },
            Settings = {
                getBulletStyles: getBulletStyles,
                getBulletStylesList: getBulletStylesList,
                getBulletClassList: getBulletClassList
            },
            applyListFormat = function (editor, listName, styleValue) {
                let bulletStyles = Settings.getBulletStyles(editor);
                bulletStyles.forEach(function(style) {
                    if(typeof style === 'string' && style === styleValue) styleValue = style;
                    if(typeof style === 'object' && style.title === styleValue) styleValue = style;
                });

                if(styleValue === '' || styleValue === undefined) styleValue = {style: styleValue};
                if(typeof styleValue !== 'object') styleValue = {classes: styleValue};

                if(styleValue.classes === undefined) Actions.applyStyle(editor, styleValue.style);
                else Actions.applyClass(editor, styleValue.classes);
            },
            applyClass = function (editor, className) {
                let r = editor.execCommand('InsertUnorderedList', false, { 'list-style-type': '' });

                if(r) {
                    let listElm = editor.selection.getNode();
                    let el = (elem) => elem.nodeName;

                    if(className !== false && className !== undefined) {
                        if(el(listElm) !== 'UL') {
                            listElm = editor.dom.getParent(editor.selection.getNode(), 'ul');
                            editor.selection.setCursorLocation(listElm);
                        }

                        if(el(listElm) === 'UL') {
                            let classList = Settings.getBulletClassList(editor);
                            classList.forEach(function (clss) {
                                editor.dom.removeClass(editor.selection.getNode(), clss);
                            });
                            editor.dom.addClass(editor.selection.getNode(), className);
                            editor.selection.setCursorLocation(listElm.firstChild);
                        }
                    }
                }
            },
            applyStyle = function (editor, styleValue) {
                let r = editor.execCommand('InsertUnorderedList', false, styleValue === false ? null : { 'list-style-type': styleValue });
                if(r) {
                    let listElm = editor.selection.getNode();
                    let el = (elem) => elem.nodeName;

                    if(el(listElm) === 'LI') {
                        listElm = editor.dom.getParent(editor.selection.getNode(), 'ul');
                        editor.selection.setCursorLocation(listElm);
                    }

                    if(el(listElm) === 'UL') {
                        let classList = Settings.getBulletClassList(editor);
                        classList.forEach(function (clss) {
                            editor.dom.removeClass(editor.selection.getNode(), clss);
                        });
                        editor.selection.setCursorLocation(listElm.firstChild);
                    }
                }
            },
            Actions = {
                applyListFormat: applyListFormat,
                applyClass: applyClass,
                applyStyle: applyStyle
            },
            register = function (editor) {
                editor.addCommand('ApplyUnorderedListStyle', function (ui, value) {
                    Actions.applyListFormat(editor, 'UL', value['class']);
                });
            },
            Commands = { register: register },
            isChildOfBody = (editor, elm) => editor.$.contains(editor.getBody(), elm),
            isTableCellNode = (node) => node && /^(TH|TD)$/.test(node.nodeName),
            isListNode = (editor) => (node) => node && /^(UL)$/.test(node.nodeName) && isChildOfBody(editor, node),
            getSelectedType = function (editor) {
                let listElm = editor.dom.getParent(editor.selection.getNode(), 'ul');
                let active_class = editor.dom.getAttrib(listElm, 'class');
                let active_style = editor.dom.getStyle(listElm, 'listStyleType');
                let actives = {classes: active_class,style: active_style};
                return Option.from(actives);
            },
            isEmpty = (value) => value === '' || value === undefined || value === false || value === 0,
            ListUtils = {
                isTableCellNode: isTableCellNode,
                isListNode: isListNode,
                getSelectedType: getSelectedType,
                isEmpty: isEmpty
            },
            findIndex = function (list, predicate) {
                for (var index = 0; index < list.length; index++) {
                    var element = list[index];
                    if (predicate(element)) {
                        return index;
                    }
                }
                return -1;
            },
            styleValueToText = (styleValue) => styleValue.replace(/\-/g, ' ').replace(/\b\w/g, (chr) => chr.toUpperCase()),
            isWithinList = function (editor, e, nodeName) {
                let tableCellIndex = findIndex(e.parents, ListUtils.isTableCellNode);
                let parents = tableCellIndex !== -1 ? e.parents.slice(0, tableCellIndex) : e.parents;
                let lists = global$1.grep(parents, ListUtils.isListNode(editor));
                return lists.length > 0 && lists[0].nodeName === nodeName;
            },
            addButton = function (editor, type, id, tooltip, cmd, nodeName, styles) {
                let settings = {
                    active: false,
                    tooltip: tooltip,
                    icon: 'unordered-list',
                    onSetup: (api) => {
                        var nodeChangeHandler = function (e) {
                            api.setActive(isWithinList(editor, e, nodeName));
                        };
                        editor.on("NodeChange", nodeChangeHandler);
                        return function () {
                            return editor.off('NodeChange', nodeChangeHandler);
                        };
                    },
                    onAction: () => {
                        editor.execCommand(cmd)
                    }
                };

                if(type === 'splitbutton') {
                    //settings.presets = 'normal';
                    //settings.columns = 3;
                    settings.fetch = function (callback) {
                        var items = global$1.map(styles, function (styleValue) {
                            //let iconStyle = 'bull';
                            //let iconName = styleValue === 'disc' || styleValue === 'decimal' ? 'default' : styleValue;
                            let itemValue = styleValue === 'default' ? '' : styleValue;
                            let displayText = styleValueToText(styleValue);
                            return {
                                type: 'choiceitem',
                                value: itemValue,
                                //icon: 'list-' + iconStyle + '-' + iconName,
                                text: displayText
                            };
                        });
                        callback(items);
                    };
                    settings.onItemAction = function (splitButtonApi, value) {
                        Actions.applyListFormat(editor, nodeName, value);
                    };
                    settings.select = function(value) {
                        value = value.replace(/\s+/g, '-').toLowerCase();
                        let listStyleType = ListUtils.getSelectedType(editor);
                        return listStyleType.map(function (listType) {
                            let classes = listType.classes;
                            let style = listType.style;
                            if( ListUtils.isEmpty(classes) && ListUtils.isEmpty(style) ) return value === '';
                            classes = classes.length > 0 ? classes.split(' ') : [];
                            return (value !== '' && (value === style || classes.indexOf(value) !== -1 ));
                        }).getOr(false);
                    };

                    editor.ui.registry.addSplitButton(id, settings);
                }
                else {
                    editor.ui.registry.addToggleButton(id, settings);
                }
            },
            addControl = function (editor, id, tooltip, cmd, nodeName, styles) {
                addButton(editor, styles.length > 0 ? 'splitbutton' : 'button', id, tooltip, cmd, nodeName, styles);
            },
            register$1 = function (editor) {
                addControl(editor, 'bullist', 'Bullet list', 'InsertUnorderedList', 'UL', Settings.getBulletStylesList(editor));
            },
            Buttons = { register: register$1 };

        global.add('clists', function (editor) {
            let hasPlugin = function (editor, plugin) {
                let plugins = editor.options.get('plugins') ? editor.options.get('plugins') : '';
                //console.log(plugins);
                return global$1.inArray(plugins.toString().split(/[ ,]/), plugin) !== -1;
            };

            if (hasPlugin(editor, 'lists')) {
                Buttons.register(editor);
                Commands.register(editor);
            }
        });

        function Plugin () {
        }

        return Plugin;
    })();
})();