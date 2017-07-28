var webservice = (function ($, undefined) {
    /**
     * Function generates a random string for use in unique IDs, etc
     *
     * @param <int> n - The length of the string
     */
    function randString(n)
    {
        if(!n)
        {
            n = 32;
        }

        var uuid = '';
        var random = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for(var i=0; i < n; i++)
        {
            uuid += random.charAt(Math.floor(Math.random() * random.length));
        }

        return uuid;
    }
    return {
        run: function(){
            $(document).on('click','#key_generator',function() {
                $('#key_ws').val(randString(32));
            });
        }
    }
})(jQuery);