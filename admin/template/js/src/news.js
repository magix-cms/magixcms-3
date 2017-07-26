var news = (function ($, undefined) {
    return {
        run: function(controller,iso){
            /*$( ".ui-sortable" ).sortable({
                items: "> tr",
                cursor: "move",
                axis: "y",
                update: function(){
                    var serial = $( ".ui-sortable" ).sortable('serialize');
                    $.jmRequest({
                        handler: "ajax",
                        url: controller+'&action=order',
                        method: 'POST',
                        data : serial,
                        success:function(e){
                            $.jmRequest.initbox(e,{
                                    display: false
                                }
                            );
                        }
                    });
                    //return false;
                }
            });
            $( ".ui-sortable" ).disableSelection();*/
            $('.date-input-picker').datetimepicker({
                format: 'YYYY/MM/DD',
                locale: iso,
                icons: {
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash-o',
                    close: 'fa fa-close'
                }
            });
            //Select input contain keyword (tags) separated by comma
            $('.tags-input + input[type="hidden"]').each(function(){
                var tagsString = $(this).val().split(',');
                // convert in json tring
                var tagsArray = JSON.stringify(tagsString);
                var tagsArray = JSON.parse(tagsArray);
                var datanames = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: $.map(tagsArray, function (item) {
                        return {
                            name: item
                        };
                    })
                });
                datanames.initialize();
                //select input for tagsinput
                var idTags = $(this).prev().attr('id');

                $('input#'+idTags).tagsinput({
                    typeaheadjs: [{
                        minLength: 1,
                        highlight: true,
                    },{
                        minlength: 1,
                        name: 'datanames',
                        displayKey: 'name',
                        valueKey: 'name',
                        source: datanames.ttAdapter()
                    }],
                    freeInput: true
                });
            });

        }
    }
})(jQuery);