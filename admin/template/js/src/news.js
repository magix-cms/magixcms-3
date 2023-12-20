var news = (function ($, undefined) {
    function initGen(fd,edit,globalForm,tableForm){
        var progressBar = new ProgressBar({loader: {type:'text', icon:'etc', class: ''}});
        $.jmRequest({
            handler: "ajax",
            url: $('#add_img_news').attr('action'),
            method: 'POST',
            data:  fd,
            processData: false,
            contentType: false,
            beforeSend: function () {
                progressBar.init();
            },
            xhr: function() {
                var xhr = $.ajaxSettings.xhr();
                //Upload progress
                xhr.oldResponse = '';
                // Generation progress
                xhr.upload.addEventListener("progress", function(e){
                    if (e.lengthComputable) {
                        let percentComplete = (e.loaded / e.total);
                        //Do something with upload progress
                        // let total = Math.round((e.total / (1024*1024))*10)/10;
                        // let loaded = Math.round((e.loaded / (1024*1024))*10)/10;
                        let options = {
                            progress: percentComplete*30,
                            state: 'upload complete at '+Math.round(percentComplete*100)+'%',
                        }
                        progressBar.update(options);
                        if(percentComplete === 100) {
                            progressBar.init({state: ''});
                        }
                    }
                });
                xhr.addEventListener("progress", function(e){
                    if(!(xhr.readyState === 4 && xhr.status === 200)) {
                        let new_response = xhr.responseText.substring(xhr.oldResponse.length);
                        if(new_response.trim() !== '') {
                            let result = JSON.parse(new_response.trim());
                            let options = {
                                progress: result.progress,
                                state: result.message,
                            }
                            if(result.loader !== null) {
                                options['loader'] = result.loader;
                            }
                            /*if(result.rendering) {
                                options['loader'] = {type: 'fa', icon: 'cog', anim: 'spin', class: 'fa fa-cog fa-spin fa-fw'};
                            }*/
                            progressBar.update(options);
                            xhr.oldResponse = xhr.responseText;
                        }
                    }
                }, false);
                return xhr;
            },
            dataFilter: function (response) {
                var responses = response.split('{');
                response = '{'+responses.pop();
                return response;
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progressBar.updateState('danger');
                console.log(xhr);
                console.log(ajaxOptions);
                console.log(thrownError);
            },
            success: function (d) {
                if(d.status == 'success') {
                    progressBar.updateState('success');
                    progressBar.update({state: d.message+' <span class="fa fa-check"></span>',loader: false});

                    $.jmRequest({
                        handler: "ajax",
                        url: '/admin/index.php?controller=news&edit='+edit+'&action=getImages',
                        method: 'get',
                        success: function (d) {
                            $('.block-img').empty();
                            $('.block-img').html(d.result);
                            globalForm.initModals();
                            tableForm.run();
                            $('.block-img').find('.img-zoom').fancybox();
                            initDefaultImg(edit);
                            initSortable(edit);
                        }
                    });
                }
                else {
                    switch (d.error_code) {
                        case 'access_denied':
                            progressBar.updateState('danger');
                            progressBar.update({state: d.message+' <span class="fa fa-ban"></span>',loader: false});
                            break;
                        case 'error_data':
                            progressBar.updateState('warning');
                            progressBar.update({state: '<span class="fa fa-warning"></span> '+d.message,loader: false});
                            break;
                    }
                }
            },
            complete: function () {
                progressBar.update({progress: 100});
                progressBar.initHide();
                //progressBar.element.parent().next().removeClass('hide');
            }
        });
    }

    function initDropZone() {
        var dropZoneId = "drop-zone";
        var buttonId = "clickHere";
        var mouseOverClass = "mouse-over";
        var btnSend = $("#" + dropZoneId).find('button[type="submit"]');

        var dropZone = $("#" + dropZoneId);
        var ooleft = dropZone.offset().left;
        var ooright = dropZone.outerWidth() + ooleft;
        var ootop = dropZone.offset().top;
        var oobottom = dropZone.outerHeight() + ootop;
        var inputFile = dropZone.find('input[type="file"]');
        document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.addClass(mouseOverClass);
            var x = e.pageX;
            var y = e.pageY;

            if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
                inputFile.offset({ top: y - 15, left: x - 100 });
            } else {
                inputFile.offset({ top: -400, left: -400 });
            }

        }, true);

        if (buttonId !== "") {
            var clickZone = $("#" + buttonId);

            var oleft = clickZone.offset().left;
            var oright = clickZone.outerWidth() + oleft;
            var otop = clickZone.offset().top;
            var obottom = clickZone.outerHeight() + otop;

            $("#" + buttonId).mousemove(function (e) {
                var x = e.pageX;
                var y = e.pageY;
                if (!(x < oleft || x > oright || y < otop || y > obottom)) {
                    inputFile.offset({ top: y - 15, left: x - 160 });
                } else {
                    inputFile.offset({ top: -400, left: -400 });
                }
            });
        }

        $("#" + dropZoneId).find('input[type="file"]').change(function(){
            var inputVal = $(this).val();
            if(inputVal === '') {
                $(btnSend).prop('disabled',true);
            } else {
                $(btnSend).prop('disabled',false);
            }
        });

        document.getElementById(dropZoneId).addEventListener("drop", function (e) {
            $("#" + dropZoneId).removeClass(mouseOverClass);
        }, true);
    }

    function initDefaultImg(edit) {
        $('.make_default').off().on('click', function(){
            var self = this,
                dflt = $('.default.in'),
                id = $(this).data('id');

            $('.default').removeClass('in');
            $('.make-default').removeClass('hide');
            $(this).parent().addClass('hide').prev().addClass('in').find('.fa').attr('class','fa fa-spinner fa-pulse');

            $.jmRequest({
                handler: "ajax",
                url: '/admin/index.php?controller=news&edit='+edit+'&action=setImgDefault',
                data: {id_img: id},
                method: 'post',
                success: function (d) {
                    if(!d.status) {
                        $(self).parent().removeClass('hide').prev().removeClass('in');
                        dflt.addClass('in').next().addClass('hide');
                    }

                    $(self).parent().prev().find('.fa').attr('class','fa fa-check text-success');
                }
            });
            return false;
        });

        $('#gallery-pages .sortable').off().on('change',function(){
            var dflt = $('.default.in');
            if(!dflt.length) {
                $.jmRequest({
                    handler: "ajax",
                    url: '/admin/index.php?controller=news&edit='+edit+'&action=getImgDefault',
                    method: 'get',
                    success: function (d) {
                        $('#images_'+d).find('.default').addClass('in').next().addClass('hide');
                    }
                });
            }
        }); 
    }
    function initSortable(edit) {
        $( ".row.sortable" ).sortable({
            items: "> div",
            cursor: "move",
            update: function(){
                var serial = $( ".sortable" ).sortable('serialize');
                $.jmRequest({
                    handler: "ajax",
                    url: '/admin/index.php?controller=news&edit='+edit+'&action=orderImages',
                    method: 'POST',
                    data : serial,
                    success:function(e){
                        $.jmRequest.initbox(e,{
                                display: false
                            }
                        );
                    }
                });
            }
        });
        $( ".row.sortable" ).disableSelection();
    }
    return {
        run: function(controller,iso){
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

        },
        runEdit: function(controller,globalForm,tableForm,edit){
            $('.progress').hide();
            $('.form-gen').on('submit', function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                initGen(fd,edit,globalForm,tableForm);
                return false;
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
                var idLang = $(this).prev().data('lang');
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
                $('input#'+idTags).on('beforeItemRemove', function(event) {
                    var tag = event.item;
                    // Do some processing here
                    if (!event.options || !event.options.preventPost) {
                        $.jmRequest({
                            handler: "ajax",
                            url: controller+'&action=delete',
                            method: 'POST',
                            data: { 'name_tag': tag,'id': $('#id_news').val(), 'id_lang':idLang },
                            resetForm:false,
                            beforeSend:function(){},
                            success:function(data) {
                                $.jmRequest.initbox(data.notify, {
                                    display: false
                                });
                            }
                        });
                        return false;
                    }
                });
            });
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if($(e.target).attr('href') === '#images') {
                    //initDropZone();
                    initDefaultImg(edit);
                    initSortable(edit);
                }
            });
            if($('#images').hasClass('active')) {
                //initDropZone();
                initDefaultImg(edit);
                initSortable(edit);
            }
        }
    }
})(jQuery);