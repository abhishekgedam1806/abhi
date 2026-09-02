"use strict";
/* ==== Jquery Functions ==== */
(function($) {
		
	
	/* ==== Testimonials Slider ==== */	
  	$(".testimonialsList").owlCarousel({      
	   loop:true,
		margin:30,
		nav:false,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:false
			},
			768:{
				items:1,
				nav:false
			},
			1170:{
				items:1,
				nav:false,
				loop:true
			}
		}
  	});
	
	/* ==== employerList Slider ==== */	
  	$(".employerList").owlCarousel({      
	   loop:true,
		margin:20,
		nav:true,
		responsiveClass:true,
		responsive:{
			0:{
				items:3,
				nav:true
			},
			768:{
				items:5,
				nav:true
			},
			1170:{
				items:10,
				nav:true,
				loop:true
			}
		}
  	});
	
	
	
	
	//Top search bar open/close
    if (!$('.srchbox').hasClass("searchStayOpen")) {
        $("#jbsearch").click(function() {
            $(".srchbox").addClass("openSearch");
            $(".additional_fields").slideDown();
        });


        $(".srchbox").click(function(e) {
            e.stopPropagation();
        });
    }
	
})(jQuery);