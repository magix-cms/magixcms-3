<script>
    {if $setting.ssl.value && $setting.service_worker.value}
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.min.js').then(function(registration) {
                // Registration was successful
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
    else {
        window.addEventListener('load', function() {
            console.log('serviceWorker is not available');
        });
    }
    {else}
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for(let registration of registrations) {
                registration.unregister()
            }}).catch(function(err) {
            console.log('Service Worker registration failed: ', err);
        });
    }
    {/if}
</script>