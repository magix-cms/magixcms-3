$(function()
{

    // *** Add fl2uc method - First Letter To Upper Case
	String.prototype.fl2uc = function() {
		return this.charAt(0).toUpperCase() + this.slice(1);
	};

	// *** target_blank
	$('a.targetblank').click( function() {
		window.open($(this).attr('href'));
		return false;
	});
    // *** Cross effect on mobile
    var width = $(window).width();
    if(width < 768) {
        $('button.open-menu').click(function(){
			var target = $(this).data('target');

			$('button.open-menu').each(function(){
				var menu = $(this).data('target');

				if($(this).hasClass('open')){
					$(this).removeClass('open');
					$(menu).removeClass('open');
				}else{
					if(menu == target) {
						$(this).addClass('open');
						$(menu).addClass('open');
					}
				}
			});
        });

		$('#header .visible-xs .navbar-toggle').click(function(){
			var target = $($(this).data('target'));
			if($(this).hasClass('open') || $(target).hasClass('collapse in')){
				$(this).removeClass('open');
			}else{
				$(this).addClass('open');
			}
		});

		$('.has-submenu.dropdown > a').click(function(e){
			e.preventDefault();
			if(!$(this).prev('button').hasClass('open')) {
				$(this).prev('button').addClass('open').removeClass('collapsed').attr('aria-expanded',true);
				$(this).next('nav').attr('aria-expanded',true).css('height','auto');
			} else {
				$(this).prev('button').removeClass('open').addClass('collapsed').attr('aria-expanded',false);
				$(this).next('nav').attr('aria-expanded',false).css('height','0px');
			}

			$(this).next('nav').toggleClass('in');
			$(this).parent('li').toggleClass('active');
		});

		$('.has-submenu.dropdown > button').click(function(){
			$(this).parent('li').toggleClass('active');
		});
    }

    //Unlock input text
    $('.unlocked').on('click',function(event){
        event.preventDefault();
        var self = $(this);
        var lock = $('span.fa-lock',this);
        var unlock = $('span.fa-unlock',this);
        if (lock.length != 0) {
            self.parent().prev().removeAttr("readonly");
            self.parent().prev().removeAttr("disabled");
            lock.removeClass('fa-lock').addClass('fa-unlock');
        } else {
            self.parent().prev().attr("readonly","readonly");
            self.parent().prev().attr("disabled","disabled");
            unlock.removeClass('fa-unlock').addClass('fa-lock');
        }
    });

    //Dropdown lang & bootstrapToggle
    /*$('.dropdown-lang a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('.dropdown-menu li.active').removeClass('active');
        $(this).parent('li').addClass('active');
        $('.dropdown .lang').text($(this).text());
        $('[data-toggle="toggle"]').each(function(){
            $(this).bootstrapToggle('destroy');
        }).each(function(){
            $(this).bootstrapToggle();
        });
    });*/
});