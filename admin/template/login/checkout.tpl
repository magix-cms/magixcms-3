<!DOCTYPE html>
<!--[if lt IE 7]><html lang="fr" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="fr" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="fr" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html lang="fr"><!--<![endif]-->
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
    <title itemprop="headline">Magix CMS | Admin</title>
    <meta itemprop="description" name="description" content="">
    <meta name="robots" content="no-index">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/skin/img/favicon.png" />
    <!--[if IE]>
    <link rel="shortcut icon" type="image/x-icon" href="/skin/img/favicon.ico" />
    <![endif]-->
    {capture name="scriptHtml5"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/html5shiv.js,
        libjs/vendor/respond.min.js
    {/strip}{/capture}
{strip}<!--[if lt IE 9]>{script src=$smarty.capture.scriptHtml5 type="javascript"}<![endif]-->{/strip}
</head>
<body id="checkout">
{script src="/{baseadmin}/min/?g=publicjs,globalize,jimagine" type="javascript"}
<script>
    var baseadmin = "{baseadmin}";
    var kpl = {if isset($kpl)}{$kpl}{else}false{/if};

    function store(kpl) {
        // Store
        //console.log(kpl);
        console.log(window.location);
        localStorage.setItem("m", kpl.m);
        localStorage.setItem("k", kpl.k);
        localStorage.setItem("t", kpl.t);

        if((window.location.pathname + window.location.search) == '/'+baseadmin+'/index.php?controller=login' || (window.location.pathname + window.location.search) == '/'+baseadmin+'/') {
            window.location.href = '/'+baseadmin+'/index.php?controller=dashboard';
        } else {
            //window.location.reload();
            console.log(window.location);
        }
    }

    function checkout() {
        // Retrieve
        var m = localStorage.getItem("m"),
            k = localStorage.getItem("k"),
            t = localStorage.getItem("t");

        var ticket = new Object();
        ticket['k'] = k;
        ticket['m'] = m;
        ticket['t'] = t;

        var data = new Object();
        data.ticket = ticket;
        $.jmRequest({
            handler: "ajax",
            url: '/'+baseadmin+'/index.php?controller=login',
            method: 'post',
            data: data,
            success: function (d) {
                if(!d) {
                    localStorage.clear();
                    window.location.href = '/'+baseadmin+'/index.php?controller=login';
                } else {
                    store(d);
                }
            }
        });
    }

    if (typeof(Storage) !== "undefined") {
        // Code for localStorage/sessionStorage.
        if(kpl) {
            store(kpl);
        } else {
            checkout()
        }
    } else {
        console.log('Sorry! No Web Storage support..');
    }
</script>
</body>
</html>