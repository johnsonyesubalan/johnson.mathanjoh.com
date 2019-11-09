/*!
 * Black Moon 1.0 by @johnson
 * Copyright 2016 MathanJoh.com (http://mathanjoh.com)
 */

var App = function () { 
	//bannerSlider
	function bannerSlider() {
		jQuery('.banner-slider').owlCarousel({ 
		    items: 1
		    // nav: true
		});

		// Shop slider height
		if(jQuery(window).width() > 768){
		    if (jQuery('.banner-slider').length) {
		        if (jQuery(window).width() > 1024) {
		            jQuery('.banner-slider').css({
		                'height': jQuery(window).height() - jQuery('header').height() - 100
		            });
		        }
		    }
		}

	    jQuery('.banner .social-share').css({ 'right':  (jQuery(window).width() - jQuery('.container').width() + 30) / 2}).show();
	} 

	//handleShare
	function handleShare() {
		jQuery('.social-share a.share').on('click', function(e){
			e.preventDefault();
			jQuery('.social-share').addClass('open'); 
		});

		jQuery('.social-share a.close-share').on('click', function(e){
			e.preventDefault();
			jQuery('.social-share').removeClass('open'); 
		});
	}  

	// portfolio filter
	function portfolioFilter() {   

        var jQueryportfolioContainer = jQuery('.portfolio-items').imagesLoaded(function() {
        jQuery('.portfolio-items').removeClass('hide');
        jQuery('footer').removeClass('hide');
            jQueryportfolioContainer.isotope({
                itemSelector: '.portfolio-item',
                layoutMode: 'masonry'
            }); 
        });
        jQuery('.filters').on('click', 'a', function(e) {
            e.preventDefault();
            var filterValue = jQuery(this).attr('data-filter');

            jQuery('.filters li').removeClass('active');
            jQuery(this).parent().addClass('active');


            jQueryportfolioContainer.isotope({filter: filterValue});
        }); 
	}

	// My Skills
	function mySkills() {   

        jQuery("body").on("inview", ".progress-bar", function() {
            var jQuerythis = jQuery(this);
            jQuery(this).css('width',  function() {
                return (jQuery(this).attr('aria-valuenow')+'%');
            });
        }); 

        jQuery("body").on("inview", ".dial", function() { 

            var jQuerythis = jQuery(this); 
            var myVal = jQuerythis.attr("value");
            var color = jQuerythis.attr("data-color");
            jQuerythis.knob({
                readOnly: true,
                width: 200,
                rotation: 'anticlockwise',
                thickness: .05,
                inputColor: '#232323',
                fgColor: color,
                bgColor: '#e8e8e8',
                'draw' : function () { 
                    jQuery(this.i).val(this.cv + '%')
                }
            });
            jQuery({
                value: 0
            }).animate({
                value: myVal
            }, {
                duration: 1000,
                easing: 'swing',
                step: function() {
                    jQuerythis.val(Math.ceil(this.value)).trigger('change');
                }
            }); 
        });
	}

    function testimonialSlider () {
        jQuery('.testimonial-slider').owlCarousel({
            items: 1,
            autoplay: true,
            loop: true
        });
    }

	// contactForm
	function googleMap () {

        // google map
        var map;
        function initialize_map() { 
            var lat = jQuery('.map').attr('data-lat');
            var lng = jQuery('.map').attr('data-lng'); 
            var markerImg = jQuery('.map').attr('data-marker-img'); 

            var myLatLng = new google.maps.LatLng(lat, lng);
            var mapOptions = {
                zoom: 5,
                center: myLatLng,
                scrollwheel: false,
                panControl: false,
                zoomControl: true,
                scaleControl: false,
                mapTypeControl: false,
                streetViewControl: false,
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'Envato',
                icon: markerImg
            }); 
        }
        google.maps.event.addDomListener(window, 'load', initialize_map); 
	}

	return {
		init: function () { 
			bannerSlider(); 
			handleShare(); 
		},

		portfolio: function () {  
			portfolioFilter();  
		},

		skills: function () {  
			mySkills();
		},

		contact: function () {  
			googleMap();
		},

        profile: function () {
            testimonialSlider();
        }

	};

}();