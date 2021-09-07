var domain = (function ($, undefined) {
    function initGen(fd,globalForm,tableForm){
        var progressBar = new ProgressBar({loader: {type:'text', icon:'etc', class: ''}});
        $.jmRequest({
            handler: "ajax",
            url: $('#create_sitemap').attr('action'),
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
                    $("#sitemap-url").html(d.result);
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
    return {
        run: function(globalForm,tableForm){
            $('.progress').hide();
            $('.form-gen').on('submit', function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                initGen(fd,globalForm,tableForm);
                return false;
            });
        }
    }
})(jQuery);