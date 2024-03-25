var product = (function ($, undefined) {
    function initGen(fd,edit,globalForm,tableForm){
        var progressBar = new ProgressBar({loader: {type:'text', icon:'etc', class: ''}});
        $.jmRequest({
            handler: "ajax",
            url: $('#add_img_product').attr('action'),
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
                            progress: percentComplete*(30),
                            state: 'upload complete at '+Math.round(percentComplete*(100))+'%',
                        }
                        progressBar.update(options);
                        /*if(percentComplete === 100) {
                            progressBar.init({state: ''});
                        }*/
                    }
                });
                xhr.addEventListener("progress", function(e){
                    if(!(xhr.readyState === 4 && xhr.status === 200)) {
                        let new_response = xhr.responseText.substring(xhr.oldResponse.length);
                        console.log(new_response.trim());
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
                        url: '/admin/index.php?controller=product&edit='+edit+'&action=getImages',
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

    function initTree() {
        $('.tree-toggle').each(function(){
            $(this).on('click',function(){
                var targetDiv = $(this).attr('href');

                if($(this).hasClass('open')) {
                    $(this).removeClass('open').find('.fa').removeClass('fa-folder-open').addClass('fa-folder');
                    $(targetDiv).collapse('hide');
                }
                else {
                    $(this).addClass('open').find('.fa').removeClass('fa-folder').addClass('fa-folder-open');
                    $(targetDiv).collapse('show');
                }
            });
        });

        $('.tree-actions').each(function(){
            $(this).click(function(){
                var action = $(this).data('action');

                $('.tree-toggle').each(function(){
                    var self = this,
                        targetDiv = $(this).attr('href');
                    if($(this).parents('div.cat-tree').length === 0) {
                        if(action === 'toggle-down') {
                            $(this).addClass('open').find('.fa').removeClass('fa-folder').addClass('fa-folder-open');
                            $(targetDiv).collapse('show');
                        } else if(action === 'toggle-up') {
                            $(this).removeClass('open').find('.fa').removeClass('fa-folder-open').addClass('fa-folder');
                            $(targetDiv).collapse('hide');
                        }
                    } else {
                        if(action === 'toggle-down') {
                            $(this).parents('div.cat-tree').on('shown.bs.collapse',function(){
                                $(self).addClass('open').find('.fa').removeClass('fa-folder').addClass('fa-folder-open');
                                $(targetDiv).collapse('show');
                                $(this).off();
                            });
                        } else if(action === 'toggle-up') {
                            $(this).parents('div.cat-tree').on('hidden.bs.collapse',function(){
                                $(self).removeClass('open').find('.fa').removeClass('fa-folder-open').addClass('fa-folder');
                                $(targetDiv).collapse('hide');
                                $(this).off();
                            });
                        }
                    }
                });
            });
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
                url: '/admin/index.php?controller=product&edit='+edit+'&action=setImgDefault',
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

        $('#gallery-product .sortable').off().on('change',function(){
            var dflt = $('.default.in');
            if(!dflt.length) {
                $.jmRequest({
                    handler: "ajax",
                    url: '/admin/index.php?controller=product&edit='+edit+'&action=getImgDefault',
                    method: 'get',
                    success: function (d) {
                        $('#image_'+d).find('.default').addClass('in').next().addClass('hide');
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
                    url: '/admin/index.php?controller=product&edit='+edit+'&action=orderImages',
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

    function editPrice(value, vat) {
        let price = document.getElementById('price_p');
        price.value = Math.round(Math.round((value / vat) * 1000) / 10) / 100;
    }
    function editPriceTaxInc(value, vat) {
        let priceTaxInc = document.getElementById('price_ttc');
        priceTaxInc.value = Math.round(Math.round((value * vat) * 1000) / 10) / 100;
    }

    function priceHandler() {
        let price = document.getElementById('price_p');
        let priceTaxInc = document.getElementById('price_ttc');
        let vat_rate = parseFloat(priceTaxInc.dataset.vat)/100 + 1;

        if(vat_rate !== null && vat_rate !== '') {
            price.addEventListener('change',(e) => { editPriceTaxInc(parseFloat(price.value),vat_rate); });
            price.addEventListener('input',(e) => { editPriceTaxInc(parseFloat(price.value),vat_rate); });
            priceTaxInc.addEventListener('change',(e) => { editPrice(parseFloat(priceTaxInc.value),vat_rate); });
            priceTaxInc.addEventListener('input',(e) => { editPrice(parseFloat(priceTaxInc.value),vat_rate); });
        }
    }
    function editPromo(value, vat) {
        let promo = document.getElementById('price_promo_p');
        promo.value = Math.round(Math.round((value / vat) * 1000) / 10) / 100;
    }
    function editPromoTaxInc(value, vat) {
        let promoTaxInc = document.getElementById('promo_ttc');
        promoTaxInc.value = Math.round(Math.round((value * vat) * 1000) / 10) / 100;
    }

    function promoHandler() {
        let promo = document.getElementById('price_promo_p');
        let promoTaxInc = document.getElementById('promo_ttc');
        let vat_rate = parseFloat(promoTaxInc.dataset.vat)/100 + 1;

        if(vat_rate !== null && vat_rate !== '') {
            promo.addEventListener('change',(e) => { editPromoTaxInc(parseFloat(promo.value),vat_rate); });
            promo.addEventListener('input',(e) => { editPromoTaxInc(parseFloat(promo.value),vat_rate); });
            promoTaxInc.addEventListener('change',(e) => { editPromo(parseFloat(promoTaxInc.value),vat_rate); });
            promoTaxInc.addEventListener('input',(e) => { editPromo(parseFloat(promoTaxInc.value),vat_rate); });
        }
    }

    return {
        run: function(globalForm,tableForm,edit){
            priceHandler();
            promoHandler();
            $('.progress').hide();
            $('.form-gen').on('submit', function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                initGen(fd,edit,globalForm,tableForm);
                return false;
            });
            initTree();

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

            $('.catlisting input[type="checkbox"]').change(function(){
                var radio = $(this).next().next().find('input[type="radio"]');
                var enabledRadios = null;

                if($(this).prop('checked')) {
                    radio.prop('disabled',false);

                    enabledRadios = $('.catlisting input[type="radio"]:enabled');

                    if(enabledRadios.length === 1) {
                        radio.prop('checked',true);
                    }
                } else {
                    enabledRadios = $('.catlisting input[type="radio"]:enabled');

                    if(enabledRadios.length > 1) {
                        var rad = enabledRadios.index(radio) ? 0 : 1;
                        $(enabledRadios[rad]).prop('checked',true);
                    }

                    $(this).next().next().find('input[type="radio"]').prop('checked',false).prop('disabled',true);
                }
            });
        },
        runAdd: function(){
            priceHandler();
            promoHandler();
            if($('#product_id').val() !== ''){
                var id = $('#product_id').val();
                var cus = $('#filter-pages').find('li[data-value="'+id+'"]');
                //console.log(cus);
                if(!cus.length) {
                    $('#product').bootstrapSelect('clear');
                } else {
                    var cu = $(cus[0]);
                    $('#product').bootstrapSelect('select',cu);
                }
            }
            $('#product_id').on('focusout',function(){
                var id = $(this).val();
                if(id !== '') {
                    var cus = $('#filter-pages').find('li[data-value="'+id+'"]');
                    //console.log(cus);
                    if(!cus.length) {
                        $('#product').bootstrapSelect('clear');
                    } else {
                        var cu = $(cus[0]);
                        $('#product').bootstrapSelect('select',cu);
                    }
                }
            });
        }
    }
})(jQuery);