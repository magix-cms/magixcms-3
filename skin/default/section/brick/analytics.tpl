{if $aid}
    <link rel="preconnect" href="https://ssl.google-anaytics.com"/>
    <link rel="dns-prefetch" href="https://ssl.google-anaytics.com"/>
    <script type="text/javascript">
    //<![CDATA[
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', "{$aid}"]);
    _gaq.push(['_trackPageview']);
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
    //]]>
</script>{/if}