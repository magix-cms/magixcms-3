function bin2hex(s){
    var i = 0, f = 0, a = [];
    s += '';
    f = s.length;

    for (i; i<f; i++) {
        a[i] = '%'+s.charCodeAt(i).toString(16).replace(/^([\da-f])$/,"0$1");
    }

    return a.join('');
}
function hex2bin(hex){
    var i = 0, f = 0, a = [];
    hex += '';
    f = hex.length;

    do {
        let char = hex.charAt(i) === '%' ? String.fromCharCode(parseInt(hex.substr(i+1, 2), 16)) : hex.charAt(i);
        a.push(char);
        i += hex.charAt(i) === '%' ? 3 : 1;
    } while(i<f);

    return a.join('');
}
tinymce.PluginManager.add('cryptmail', function(editor, url) {
    /*
    Add a custom icon to TinyMCE
     */
    editor.ui.registry.addIcon('cryptmail', '<svg width="24" height="24"><use xlink:href="'+url+'/img/cryptmail.svg#cryptmail"></use></svg>');
    editor.ui.registry.addMenuItem('cryptmail', {
        icon: 'cryptmail',
        text: "Un/Crypt e-mail",
        tooltip: "cryptmail",
        onAction: () => {
            let el = editor.selection.getNode();
            if(el.nodeName === 'A') {
                let href = tinymce.DOM.getAttrib(el,'href');

                if(href.indexOf('mailto:') === 0) {
                    let hrefParts = href.split(':');
                    let address = hrefParts[1];
                    let address_encode = '';
                    if(address.charAt(0) !== '%') {
                        for (var i = 0; i < address.length; i++) {
                            let char = address.charAt(i);
                            address_encode += char.match(/\w/u) ? bin2hex(char) : char;
                        }
                    }
                    else {
                        address_encode = hex2bin(address);
                    }
                    let newHref = 'mailto:' + address_encode;
                    tinymce.DOM.setAttrib(el,'href',newHref);
                    tinymce.DOM.setAttrib(el,'data-mce-href',newHref);
                }
            }
        }
    });
});