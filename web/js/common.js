function addClassBody(){
	exist=$(".main-content").length
	if(exist){
		$("body").addClass("page")
	}
}

function addClassBodySetting(){
	exist=$(".cont-section.setting").length
	if(exist){
		$("body").addClass("setting")
	}
}

/*---SCRIPT MENU---*/
function navTopMobile() {
    $('.lst-menu li').each(function() {
        var nb = $(this).find('> ul').length
        if (nb) {
            $(this).addClass('s-menu')
            $(this).append("<span class='btn-action-nav'>")
        }
    });
    $(".btn-nav-mobile").click(function() {
        W = $(window).width()
        if (W < 1023) {
            iz = $(this)
            isOpen = iz.hasClass("open")
            if (!isOpen) {
                 $(".blc-menu .lst-menu").slideDown()
          		 iz.addClass("open")
            } else {
              $(".blc-menu .lst-menu, .lst-menu li ul").slideUp()
            	iz.removeClass("open")
				$(".btn-action-nav").removeClass("open")
            }
            return false
        }
    });

    $(".lst-menu .s-menu .btn-action-nav").click(function() {
        W = $(window).width()
        if (W < 1023) {
            iz = $(this)
            isOpen = iz.hasClass("open")
            if (!isOpen) {
				$(".btn-action-nav").removeClass("open")
				$(".lst-menu li ul").slideUp()
              	$(this).prev("ul").slideDown()
				iz.addClass("open")
            }
            else {
               $(this).prev("ul").slideUp()
				iz.removeClass("open")
            }
            return false
        }
    });
}

/* RESET NAV */
function resetNav() {
    W = $(window).width()
    if (W >= 1023) {	
		$("ul.lst-menu li.s-menu a, .btn-nav-mobile,.btn-action-nav").removeClass("open")
        $(".lst-menu,ul.lst-menu li ul").removeAttr("style")
    }
}
/* RESET NAV */
function menuNom(){
	
			$(".profil-info a").click(function() {
			W = $(window).width()
					if (W > 1023) {	
						iz=$(this)
						var parent=iz.parent('.profil-info')
						isopen=iz.hasClass('open')
						if(!isopen){
							parent.next(".blc-deconex").slideDown()
							iz.addClass('open')
						}
						else{
							parent.next(".blc-deconex").slideUp()
							iz.removeClass('open')
						}
					}
		})
    
	
}
function resetMenuName(){
 	W = $(window).width()
    if (W < 1024) {	
		$(".profil-info a").removeClass("open")
        $(".blc-deconex").removeAttr("style")
    }
}

$(document).ready(function() {
	addClassBody()
	addClassBodySetting()
	navTopMobile()
	resetNav()
	menuNom()
	resetMenuName()
});

$(window).resize(function() {
	resetNav()
	resetMenuName()
	
});