// import $ from 'jquery'
// Vue.prototype.jQuery = jQuery
// var $ = require('jquery')
// window.jQuery = jQuery
import jQuery from 'jquery'

// $ = jQuery;

(function($) { // work as an alias for jQuery()
var obj 		= obj || {};
/* document ready.... magic happens here */
$(document).ready(function(){
	
	obj.init();
	// $('.line-heading').click(function (e) {
	// 	alert('working');
	// });
});
	
$.fn.setCustomSticky = function (btnMenuId) {
	
	var bottmLimit = $('.footer-copyright').offset().top,
		runTag = false;
	function resizeChanges(){
			var ww = $(window).width();
			if(ww<=767){
				runTag = false;
			}else{
				runTag = true;
			}
	};
	$(window).resize(function () {
		resizeChanges();
	});
	resizeChanges();
	
    return this.each(function () {
		var elem = $(this),
			elmBound = elem.offset().top+elem.outerHeight();
		
        if (elmBound>bottmLimit) {
			if(runTag){
				setTimeout(function () {
					elem.addClass("nonsticky");
				}, 200);
			}
		}
		
		$.event.add(window, "scroll", function () {
			
			if(runTag){
	console.log();
				if (($(window).scrollTop() + $(window).height()) > bottmLimit) {
					elem.addClass("nonsticky");
				} else {
					elem.removeClass("nonsticky");
				}
			}
		});	
    });	

};
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
obj.init = function() {
	
	if(jQuery('html').hasClass('lt-ie9')) {
	   $('img.lazyload').each(function () {
			var $this = $(this);
			if($this.data('srcset')){
				var res = /(?:([^"'\s,]+)\s*(?:\s+\d+[wx])(?:,\s*)?)+/g.exec($this.data('srcset'))
				$this.attr('src', res[res.length-1]);
			} else {
				$this.attr('src', $this.data('src'));
			}
		});
	}
	
	//obj.responsiveInit();
	
	//obj.initHeader();
	
	// main menu dropdown
	if($('#menu-primary').length)
		this.setAllMenu();
	
	// 
	if($('.banner-testimonial').length){
		this.sectionSlider();
	}
		
	if($('.thumb-carousel').length)
		this.carouselImg();
		
	if($('.box-carousel').length)
		this.carouselBox();
	
	if($('.testimonial-carousel').length)
		this.carouselTesti();
	
	if($('.gallthumb').length){
		this.productSlider();
	}
	
	if($('.gallery-carousel').length)
		this.galleryCarousel();
	
	/*
	if($('#newslist').length){
		this.ajaxMore();
	}*/
		
	if($('.acf-map').length)
		this.CustomGoogleMap();
	
	if($('.filter-btn').length){
		$('.filter-btn').click(function() {
			$('a.filter-btn').removeClass('slct');
			var filterValue = $(this).attr('data-filter');
			$('.case-list').isotope({ filter: filterValue });
			$(this).addClass('slct');
			return false;
		});
	}
	
	//custom dynamic botton
	if($('.fixedwidget:last-child a.btn2').length){
	   var clne = $('.fixedwidget:last-child a.btn2').clone().addClass('d-block d-lg-none cbtn');
	   //clne.appendTo('.site-logo');
	   clne.prependTo('#primary');
	   $('.fixedwidget:last-child a.btn2').addClass('d-none d-lg-block');
	}
	
	////if($('#galleries').length)
	//	this.gallerySlider();
		
		
	//if($('.swipebox-video').length)
	//	$( '.swipebox-video' ).swipebox();

	//if($('a.readmore').length)
	//	this.readmore();
	//obj.clickCall();
	
	if($('.flexslider').length)
		this.flexi();
	
	if($('.accordion').length){
			$('.accordion .list_accordion').each(function(){
				var btn = $('a.ca_btn', this),
					hdn = $('.hidden_content', this);
				btn.click(function(){
					if($(this).parent().hasClass('open')){
						$(this).parent().removeClass('open');
					}else{
						$(this).parent().addClass('open');
					}
					return false;
				});
			});
		}
	
	if($('.ginput_container_radio .gfield_radio li,.ginput_container_checkbox .gfield_checkbox li').length)
		this.customcheckbox();
	
		
	if($('.f-logos').length)
		this.footerlogos();
	
	//if($('.fixedwidget').length)
		//$('.fixedwidget').setCustomSticky();

		// $( ".btn2" ).click(function() {
		// 	alert( "Handler for .click() called." );
		//   });
			
}
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////



	obj.galleryCarousel = function() {
		 var owl = $('.gallery-carousel.owl-carousel'),
		 	 time = owl.data('timeout')||2000,
		 	 nav = owl.data('nav')||false,
		 	 dot = owl.data('dot')||false,
		 	 dsk = owl.data('d')||5,
		 	 tab = owl.data('t')||3,
		 	 mob = owl.data('m')||1;
              owl.owlCarousel({
                margin: 10,
                nav: nav,
                dots: dot,
                //autoWidth: true,
				navText: ['<i class="far fa-chevron-left"></i>','<i class="far fa-chevron-right"></i>'],
                loop: true,
				autoplay:true,
				  autoplayTimeout:time,
                responsive: {
                  0: {
                    items: mob,
            nav:true
                  },
                  600: {
                    items: tab,
            nav:true
                  },
                  1000: {
                    items: dsk,
            nav:true
                  }
                }
              })
	};
	
	
	obj.footerlogos = function() {
		 var owl = $('.f-logos.owl-carousel'),
		 	 dsk = 4,
		 	 tab = 3,
		 	 mob = 2;
              owl.owlCarousel({
                margin: 30,
                nav: false,
              // center: true,
                dots: false,
				 // autoHeight: true,
				  //fluidSpeed: true,
                //autoWidth: true,
				//navText: ['<i class="fal fa-arrow-circle-left"></i>','<i class="fal fa-arrow-circle-right"></i>'],
                loop: true,
				autoplay:true,
				  autoplayTimeout:2000,
                responsive: {
                  0: {
                    items: mob,
                margin: 80,
            nav:true
                  },
                  600: {
                    items: tab,
            nav:true
                  },
                  1000: {
                    items: dsk,
            nav:true
                  }
                }
              })
	};
	
	obj.productSlider = function(){
		$('.mainproductimg').flexslider({
			animation: "slide",
			animationLoop: false,
			smoothHeight : true,
			controlNav: false,
			start: function(slider){
			  $('body').removeClass('loading');
			}
		});
		$('.gallthumb').flexslider({
			animation: "slide",
			animationLoop: false,
			itemWidth: 150,
			itemMargin: 5,
			controlNav: false,
			directionNav: false,
			//pausePlay: true,
			//mousewheel: true,
			asNavFor:'.flexslider'
		});
	};
	
	obj.customcheckbox = function(){
		$('.ginput_container_radio .gfield_radio,.ginput_container_checkbox .gfield_checkbox').each(function(){
			$(this).find('input').each(function(){
				$('<span class="checkmark"></span>').appendTo($(this).next());
				$(this).prependTo($(this).next());
				if($(this).is(':checked')){
					$(this).parent().addClass('checked');
				}
			});
			$(this).find('input[type="radio"]').click(function(){
				$(this).closest('ul').find('label.checked').removeClass('checked');
				$(this).parent().addClass('checked');
			});
			$(this).find('input[type="checkbox"]').click(function(){
				if($(this).is(':checked'))
					$(this).parent().addClass('checked');
				else
					$(this).parent().removeClass('checked');
			});
		});
	};

	obj.flexi = function() {
		$('.flexslider').flexslider({
	      animation: "fade",
	      animationLoop: true,
	      directionNav: false,
	      move: 1,
	      minItems: 1,
	      maxItems: 1,
	      before: function(slider){
				$(slider).find(".flex-active-slide").find('.content').each(function(){
					
					$(this).addClass("animated bounceIn");
				});
			},
			after: function(slider){
				$(slider).find(".flex-active-slide").find('.content').removeClass("animated bounceIn");
				
			},   
	    });
	}
	obj.ajaxMore = function() {
	var page = [],
		itemParent = $('#newslist');
	$('a.getMore').each(function(e){
			var moreLink = $(this);
			//moreLink.hide();
			page[e] = {};
			page[e].page = '';
			page[e].total = '';
			page[e].loading = false;
			// click event
			moreLink.click(function(){
				moreLink.show().addClass('loading'); // show loading
				if(page[e].loading) return false;
				page[e].loading = true;
				
				// ajax call
				$.get(moreLink.attr('href')+'&page='+page[e].page,function( data ){
					if(data.type == "success"){
						moreLink.removeClass('loading'); // remove loading
						page[e].page = parseInt(data.page); // get current page
						page[e].page++; // increament page for next loading
						page[e].total = parseInt(data.post_total);
						$(data.post_html).appendTo('#newslist');
						$('#newslist').find('.thumb-blocks').fadeIn('slow');
						page[e].loading = false; // set not loading tag
						//moreLink.hide();
						//wall.fitWidth();
						// if all posted hide more link
						if(itemParent.find('div.thumb-blocks').length == data.post_total)
							moreLink.parent().remove();
					}
				},'json');
				return false;
			});
		});
		/*
		var wall = new Freewall("#newslist");
				wall.reset({
					selector: '.grid-item',
					animate: true,
					cellW: 300,
					//delay : 5,
					cellH: 'auto',
					cacheSize: false,
					onResize: function() {
						wall.fitWidth();
					}
				});
		function resetWall(){
			//	alert('test');
			// call twice to fix the issue
			setTimeout(function(){ wall.fitWidth(); }, 2000);		
			setTimeout(function(){
				wall.fitWidth();
				$(window).trigger('resize'); 
				//alert('test');
			},5000);
		};
		//resetWall();
		*/
		$('#newslist img').imgpreload({
			  all: function(){
				$('.thumb-blocks').fadeIn('slow');
			  	// wall.fitWidth();
				// resetWall();
			 }
		});
		
	};
	
	obj.carouselTesti = function() {
		 var owl = $('.testimonial-carousel.owl-carousel'),
		 	 dsk = 1,
		 	 tab = 1,
		 	 mob = 1;
              owl.owlCarousel({
                margin: 10,
              	nav: true,
                dots: false,
                //autoWidth: true,
				autoHeight: true,
				navText: ['<i class="fal fa-long-arrow-left"></i>','<i class="fal fa-long-arrow-right"></i>'],
                loop: true,
				autoplay:true,
				  autoplayTimeout:7000,
                responsive: {
                  0: {
                    items: mob,
            nav:true
                  },
                  600: {
                    items: tab,
            nav:true
                  },
                  1000: {
                    items: dsk,
            nav:true
                  }
                }
              })
	};

	obj.carouselBox = function() {
		 var owl = $('.box-carousel.owl-carousel'),
		 	 dsk = owl.data('d')||3,
		 	 tab = owl.data('t')||2,
		 	 mob = owl.data('m')||1;
              owl.owlCarousel({
                margin: 10,
              	nav: true,
                dots: true,
                //autoWidth: true,
				autoHeight: true,
				navText: ['<i class="far fa-chevron-left"></i>','<i class="far fa-chevron-right"></i>'],
                loop: true,
				autoplay:true,
				  autoplayTimeout:4000,
                responsive: {
                  0: {
                    items: mob,
            nav:true
                  },
                  600: {
                    items: tab,
            nav:true
                  },
                  1000: {
                    items: dsk,
            nav:true
                  }
                }
              })
	};

	obj.carouselImg = function() {
		 var owl = $('.thumb-carousel.owl-carousel'),
		 	 dsk = owl.data('d')||5,
		 	 tab = owl.data('t')||3,
		 	 mob = owl.data('m')||1;
              owl.owlCarousel({
                margin: 10,
              //  nav: true,
                dots: false,
                //autoWidth: true,
				navText: ['<i class="fal fa-arrow-circle-left"></i>','<i class="fal fa-arrow-circle-right"></i>'],
                loop: true,
				autoplay:true,
				  autoplayTimeout:2000,
                responsive: {
                  0: {
                    items: mob,
            nav:true
                  },
                  600: {
                    items: tab,
            nav:true
                  },
                  1000: {
                    items: dsk,
            nav:true
                  }
                }
              })
	};

	obj.modalVid = function() {
		var $videoSrc;  
		$('#home_videos a').click(function() {
			$videoSrc = $(this).attr('href');
		});
		  
		// when the modal is opened autoplay it  
		$('#videomodal').on('shown.bs.modal', function (e) {		
			// set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
			$("#modalvideo").attr('src','https://www.youtube.com/embed/' + $videoSrc + '?rel=0&amp;showinfo=0&amp;modestbranding=1&amp;autoplay=1').load(function(){
				$("#modalvideo").fadeIn();
			}); 
			//$("#modalvideo").fadeIn('slow');
		})		  
		  
		// stop playing the youtube video when I close the modal
		$('#videomodal').on('hide.bs.modal', function (e) {
			// a poor man's stop video
			$("#modalvideo").attr('src',$videoSrc); 
			$("#modalvideo").hide();			
		}) 
	};
	
	
	obj.rotate = function() {
		var TxtRotate = function(el, toRotate, period) {
		  this.toRotate = toRotate;
		  this.el = el;
		  this.loopNum = 0;
		  this.period = parseInt(period, 10) || 2000;
		  this.txt = '';
		  this.tick();
		  this.isDeleting = false;
		};
		
		TxtRotate.prototype.tick = function() {
		  var i = this.loopNum % this.toRotate.length;
		  var fullTxt = this.toRotate[i];
		
		  if (this.isDeleting) {
			this.txt = fullTxt.substring(0, this.txt.length - 1);
		  } else {
			this.txt = fullTxt.substring(0, this.txt.length + 1);
		  }
		
		  this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';
		
		  var that = this;
		  var delta = 300 - Math.random() * 100;
		
		  if (this.isDeleting) { delta /= 4; }
		
		  if (!this.isDeleting && this.txt === fullTxt) {
			delta = this.period;
			this.isDeleting = true;
		  } else if (this.isDeleting && this.txt === '') {
			this.isDeleting = false;
			this.loopNum++;
			delta = 500;
		  }
		
		  setTimeout(function() {
			that.tick();
		  }, delta);
		};
		
		window.onload = function() {
		  var elements = document.getElementsByClassName('txt-rotate');
		  for (var i=0; i<elements.length; i++) {
			var toRotate = elements[i].getAttribute('data-rotate');
			var period = elements[i].getAttribute('data-period');
			if (toRotate) {
			  new TxtRotate(elements[i], JSON.parse(toRotate), period);
			}
		  }
		  // INJECT CSS
		  var css = document.createElement("style");
		  css.type = "text/css";
		  css.innerHTML = ".txt-rotate > .wrap { border-right: 0.08em solid #666 }";
		  document.body.appendChild(css);
		};
	};

	obj.initHeader = function() {
		var fixadent = $("#masthead"),
			body =  $("body"),
			posTop = 95,
			runTag = true;
			
		if ($(window).scrollTop() > posTop) {
			if(runTag){
				setTimeout(function () {
					fixadent.addClass("sticky animated slideInDown");
					body.addClass("fixhead animated");
				}, 200);
			}
		}
		
		$.event.add(window, "scroll", function () {
			//console.log(runTag);
			if(runTag){
				if ($(window).scrollTop() > posTop) {
					fixadent.addClass("sticky animated slideInDown");
					body.addClass("fixhead animated");
				} else if ($(window).scrollTop() <= posTop) {
					fixadent.removeClass("sticky animated slideInDown");
					body.removeClass("fixhead animated");
				}
			}
		});
		
		function resizeChanges(){
			var ww = $(window).width();
			if(ww>992){
				runTag = true;
			}
			if(ww<991){
				runTag = false;
			}
		};

		$(window).resize(function () {
			resizeChanges();
		});
		resizeChanges();
		
	};
	
	obj.instagramFeed = function(){
		// 144052633
		var elem_gal = '#carousel.flexslider .slides',
			insUsrId = $(elem_gal).attr('data-userid'),
			insAcTkn = $(elem_gal).attr('data-token'),
			count = 6,
			galPage = false;
			
		var url = "https://api.instagram.com/v1/users/" + insUsrId + "/media/recent?access_token=" + insAcTkn + "&count=" + count + "&callback=?";
		
		function parseImages(data){
			var cntr = 0,
			slider = $(document.createElement('div')).addClass('items'),
			sep = '',
			d_lngth = data.data.length;
			$.each(data.data, function (i, val) {
				var li = $('<li></li>').appendTo(elem_gal),
					a = $("<a/>", {"href": val.link, "rel": "instaView", 'target': '_blank'}).appendTo(li);
					//a = $("<a/>", {"href": val.images.standard_resolution.url, class: 'swipebox', "rel": "instaView"}).appendTo(li);
					//a.swipebox();
					$("<img/>", {"src": val.images.standard_resolution.url}).appendTo(a);
					if (val.caption){
						a.attr("title", val.caption.text);
					}
            });
		};
        
		$.getJSON(url, function(data){ parseImages(data); }).done(function() {
			var getGridSize = function() {
			  return (window.innerWidth < 600) ? 2 :
					 (window.innerWidth < 900) ? 5 : 6;
			};
			 $('#carousel').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: true,
				mousewheel: true,
				slideshow: false,
				itemWidth: 170,
				minItems: getGridSize(), // use function to pull in initial value
				maxItems: getGridSize(), // use function to pull in initial value
				itemMargin: 0,
				//asNavFor: '#slider'
			  });
		});
		
		
		// bottom slides //http://flexslider.woothemes.com/thumbnail-slider.html
		if($('#content-bottom .flexslider img').length){
		// tiny helper function to add breakpoints
			
			
		}
	};
	
/**
	*/
	obj.readmore = function(){
		$('a.readmore').each(function(){
			if($('a.readmore').parent().prev('div.hidetext').length){
				$(this).click(function(){
					var r_imgs = '';
					if($(this).parent().parent('div.left_panel').length){
						r_imgs = $(this).closest('div.left_panel').prev('div.right_panel');
					}
					
					if($(this).hasClass('actv')){
						$(this).removeClass('actv');
						$(this).parent().prev('div.hidetext').fadeOut();
						if(r_imgs){
							r_imgs.find('.inc-hdn').fadeOut();
						}
					}else{
						$(this).addClass('actv');
						$(this).parent().prev('div.hidetext').fadeIn('slow');
						if(r_imgs){
							r_imgs.find('.inc-hdn').fadeIn('slow');
						}
					}
				});
			}
		});
	};
	
/**
	*/
	obj.gallerySlider = function(){
		
		$('.gallerylist a:not(.swipebox-video)').click(function(){
			var __this = $(this);
			$('.gallerylist a').removeClass('active');
			$(__this).addClass('active');
			$('.items').removeAttr('style');
			$(__this.attr('href')).fadeIn('slow');
			var slider1 = $(__this.attr('href')).find('.flexslider').data('flexslider');
			slider1.resize();
			$(window).trigger( 'resize' );
		});
		
		var query = location.href.split('#'),
			hash  = query[1];
		
		
		var getGridSize = function() {
			return (window.innerWidth < 600) ? 2 :
				 (window.innerWidth < 900) ? 5 : 5;
		};
		
		$('.items').each(function(i){
			
			if(!hash && !i){
				$(this).fadeIn('slow');
				$('.gallerylist a:eq(0)').addClass('active');
			}else{
				if(hash==$(this).attr('id')){
					$(this).fadeIn('slow');
					// find link
					$('.gallerylist a').each(function(){
						if($(this).attr('href')=='#'+hash){
							$(this).addClass('active');
						}
					});
				}
			}
			$('.gallerythumbs_'+i).flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 145,
				itemMargin: 3,
				minItems: getGridSize(),
				maxItems: getGridSize(),
				asNavFor: '.heroimgs_'+i
			  });
			 
			  $('.heroimgs_'+i).flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				sync: '.gallerythumbs_'+i
			  });
		});
	};
	/**
	*	slides section
	*/
	obj.sectionSlider = function(){
		if($('.banner-testimonial.owl-carousel > .item').length>1){
		var owl = $('.banner-testimonial.owl-carousel'),
		 	 dsk = 1,
		 	 tab = 1,
		 	 mob = 1;
              owl.owlCarousel({
                margin: 10,
              	nav: true,
                dots: false,
				autoHeight: true,
				navText: ['<i class="fal fa-long-arrow-left"></i>','<i class="fal fa-long-arrow-right"></i>'],
                loop: true,
				autoplay:true,
				  autoplayTimeout:7000,
                responsive: {
                  0: {
                    items: mob,
            nav:true
                  },
                  600: {
                    items: tab,
            nav:true
                  },
                  1000: {
                    items: dsk,
            nav:true
                  }
                }
              });
		}else{
			$('.banner-testimonial.owl-carousel').show();
		}
		/*
		if($('#main-slider .slide').length>1){
			$('.cnav').show();
			$('a.cprv').click(function(){ $(".hidden-navigation a.flex-prev").trigger('click'); });
			$('a.cnxt').click(function(){ $(".hidden-navigation a.flex-next").trigger('click'); });
		}
		$('#main-slider').flexslider({
			directionNav: true,      
			controlNav: false,
			selector: ".slides > div",
			customDirectionNav: $(".hidden-navigation a")
		});*/
		
	};
	
	obj.CustomGoogleMap = function() {
		/*
		*  new_map
		*
		*  This function will render a Google Map onto the selected jQuery element
		*
		*  @param	$el (jQuery element)
		*  @return	n/a
		*/
		
		function new_map( $el ) {
			
			// var
			var $markers = $el.find('.marker');			
			// vars
			var args = {
				zoom		: 15,
				center		: new google.maps.LatLng(0, 0),
				mapTypeId	: google.maps.MapTypeId.ROADMAP,
				/*styles 		: [
  {
    "featureType": "landscape",
    "stylers": [
      {
        "color": "#f9f7eb"
      }
    ]
  },
  {
    "featureType": "poi",
    "stylers": [
      {
        "color": "#beefd1"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#f2efe0"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#cbc8b9"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "labels.icon",
    "stylers": [
      {
        "visibility": "on"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#cbc8b9"
      }
    ]
  }
]*/
			};
			
			// create map	        	
			var map = new google.maps.Map( $el[0], args);
			
			
			// add a markers reference
			map.markers = [];
			
			
			// add markers
			$markers.each(function(){
				
				add_marker( $(this), map );
				
			});
			
			
			// center map
			center_map( map );
			
			
			// return
			return map;
			
		}
		
		/*
		*  add_marker
		*
		*  This function will add a marker to the selected Google Map
		*
		*  @type	function
		*  @date	8/11/2013
		*  @since	4.3.0
		*
		*  @param	$marker (jQuery element)
		*  @param	map (Google Map object)
		*  @return	n/a
		*/
		
		function add_marker( $marker, map ) {
		
			// var
			var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
		
			// create marker
			var marker = new google.maps.Marker({
				position	: latlng,
				map			: map,
				title:    'Find A Translation - ' + $marker.attr('data-address'),
				icon:     '/wp-content/themes/fat/assets/img/custom-marker.png' 
			});
		
			// add to array
			map.markers.push( marker );
					
			// if marker contains HTML, add it to an infoWindow
			if( $marker.html() ){
				// create info window
				var infowindow = new google.maps.InfoWindow({
					content		: $marker.html()
				});
		
				// show info window when marker is clicked
				google.maps.event.addListener(marker, 'click', function() {
		
					infowindow.open( map, marker );
		
				});
			}
		
		}
		
		/*
		*  center_map
		*
		*  This function will center the map, showing all markers attached to this map
		*
		*  @type	function
		*  @date	8/11/2013
		*  @since	4.3.0
		*
		*  @param	map (Google Map object)
		*  @return	n/a
		*/
		
		function center_map( map ) {
		
			// vars
			var bounds = new google.maps.LatLngBounds();
		
			// loop through all markers and create bounds
			$.each( map.markers, function( i, marker ){
		
				var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
		
				bounds.extend( latlng );
		
			});
		
			// only 1 marker?
			if( map.markers.length == 1 )
			{
				// set center of map
				map.setCenter( bounds.getCenter() );
				map.setZoom( 13 );
			}
			else
			{
				// fit to bounds
				map.fitBounds( bounds );
			}
		
		}
		
		/*
		*  document ready
		*
		*  This function will render each map when the document is ready (page has loaded)
		*
		*  @type	function
		*  @date	8/11/2013
		*  @since	5.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/
		// global var
		var map = null;
		$('.acf-map').each(function(){
		
				// create map
				map = new_map( $(this) );
		
			});
	};
	
obj.setBannerBackground = function () {
	var defaults      = {
					  selector:             '[data-adaptive-background="1"]',
					  parent:               null,
					  exclude:              [ 'rgb(0,0,0)', 'rgba(255,255,255)' ],
					  normalizeTextColor:   false,
					  normalizedTextColors:  {
						light:      "#fff",
						dark:       "#000"
					  },
					  success: function($img, data) {
									$img.remove();
				  				},
					  lumaClasses:  {
						light:      "ab-light",
						dark:       "ab-dark"
					  }
					};
	$.adaptiveBackground.run(defaults);
};

// obj.clickCall = function(){
// 	if (/(Series60|Nokia)/i.test(navigator.userAgent)){
// 		$('a.m_call').attr('href') = $('a.m_call').attr('href').replace("tel:", "wtai://wp/mc;");
// 	} /* else if (navigator.userAgent.indexOf("iPhone") != -1) {
// 		$('a.m_call').attr('href') = $('a.m_call').attr('href').replace("tel:", "callto:");
// 	}*/
// };

obj.responsiveInit = function () {
	var waitForFinalEvent = (function () {
		var timers = {};
		return function (callback, ms, uniqueId) {
			if (!uniqueId) {
				uniqueId = "uniqueIdTag"; // Don't call this twice without a uniqueId
			}
			if (timers[uniqueId]) {
				clearTimeout (timers[uniqueId]);
			}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();
	
	if (Modernizr.mq('(min-width: 0px)')) {
		this.mq = true;
	} else {
		this.mq = false;
	}
	
	$(window).resize(function () {
		// fire after resize is completed
		waitForFinalEvent(function(){
			obj.resizeChanges();
		}, 500);
	});
	obj.resizeChanges();
};

obj.resizeChanges = function(){	
	var ww = $(window).width();
	
	// if modinizer support
	if(this.mq){
		if(ww>600){
			// remove mobile dropdown menu - this happened when the menu dropdown is visible and then you resized it from desktop view
			if($('#mainMobileMenu').is(':visible')){
				$('#mainMobileMenu').focusout();
			}
			if($('#mainFooterMobileMenu').is(':visible')){
				$('#mainFooterMobileMenu').focusout();
			}
			// menu
			var diff = ($(window).width() - $('#head .wrapper').width()) / 2;
			$("ul#menu li.level-0 ul.level-1:before").addRule({left: '-'+diff+'px'});
			$("ul#menu li.level-0 ul.level-1:after").addRule({right: '-'+diff+'px'});
		}
		if(ww<600){			
		}
	}else{
		// add action here
	}
	//Cufon.refresh();
};


obj.setAllMenu = function() {
	$('#menu').mnmenu({duration:1,delay:1,arrowCharacter:''});
	
	$('#menu li:has(ul)').each(function(){
		$('ul:first', this).next('span').appendTo($(this).find('a:first'));
		if($('ul:first', this).parent("li.level-0").length) {
			$('<span class="dropArrow"></span>').appendTo(this);
		}
	});
	
	obj.mobileNav();
	
	function extendAnchorLink(){
		var $root = $('html, body');
		
		// click event
		$("a[href*='#']:not([href='#'])").click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			  var target = $(this.hash);
			  target = target.length ? target : $('[name=' + this.hash.slice(1) +']'),
			  referrer =  document.referrer;
			  //console.log(referrer + ' ' + window.location.href);
			  if (target.length) {
				$root.animate({
				  scrollTop: target.offset().top - $('#masthead').height()
				}, 500);
				return false;
			  }
			}
		  });
	};
	// add scroll for anchor link
	extendAnchorLink();
	
	//$('#menu-primary').show();
};

obj.columnize = function(col,elm) {
	var columns = col||2;
	
	var $ul = $(elm),
		$elements = $ul.children('li'),
		breakPoint = Math.round($elements.length/columns);
		$ordered = $('<div></div>');
	
	function appendToUL(i) {
		if ($ul.children().eq(i).length > 0) {
			$ordered.append($ul.children().eq(i).clone());
		} 
		else $ordered.append($('<li></li>'));
	};
	
	function reorder($el) {
		$el.each(function(){
			$item = $(this);
			
			if ($item.index() >= breakPoint) return false;
	
			appendToUL($item.index());
			for (var i = 1; i < columns; i++) {
				appendToUL(breakPoint*i+$item.index());
			}
		});
	
		$ul.html($ordered.children().css('width',100/columns+'%'));
	};
	
	reorder($elements);
};

obj.mobileNav = function() {
		
		// 3rd level destop menu
		$('#menu .sub-menu li').unbind('mouseenter mouseleave'); 
		toggleMenu('#menu .sub-menu');
		
			
		// final set
		toggleMenu('#mainMobileMenu');
		$('#mainMobileMenu').setMenuEvent('#mobile_slctd_menu');
			
		function parseNav(elem){
			var mList, subMenus, mMenuWpr;
				
			if(elem.find('> li').length){
				mMenuWpr = $('<ul></ul>');
				elem.find('> li').each(function(i){
					
					mList = $('<li></li>').addClass($(this).attr('class'));
					mList.appendTo(mMenuWpr);
						$(this).find('a:first').clone().prependTo(mList);
						
						if($(this).find('ul:first').length){
							subMenus = parseNav($(this).find('ul:first'));
							subMenus.appendTo(mList);
						}
					
					});
				}
				return mMenuWpr;
		  };
	
	function cloneParentLink(id){
		$(id+' > li a:parent').each(function(){
			if($(this).next().is('ul') &&  $(this).attr('href')!=undefined && $(this).attr('href')!='javascript:void(0);'){
				var cln_lnk = $(this).clone().removeAttr('class').prependTo($(this).next());
				cln_lnk.find('span.arrow').remove();
				cln_lnk.wrap('<li />');
			}
		});
	};
	
	function toggleMenu(elem_name){
		$(elem_name+' li a').click(function() {
			var checkElement = $(this).next();
			var $_elm = $(this);
			var lvl = 'level-'+(checkElement.parents('ul').length - 1);
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				$(this).next().slideUp('fast');
				$(this).removeClass('drop');
				$(this).parent().removeClass('open');
				checkElement.find('ul').each(function(){
					$(this).slideUp('fast');
					$(this).prev('a').removeClass('drop');
					$(this).parent().removeClass('open');
				});
				return false;
			}
				
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				//alert(checkElement.parents('ul').length);
				$(elem_name+' li.'+lvl+' ul:visible').each(function(){
					$(this).slideUp('fast')
					$(this).prev('a').removeClass('drop');
				});
				/*$(elem_name+' ul:visible').each(function(){
					if($(this).text()!=checkElement.parents('ul:first').text()){
						$(this).slideUp('fast')
						$(this).prev('a').removeClass('drop');
					}
				});*/
				$(this).addClass('drop').next().slideToggle('fast');
				$(this).parent().addClass('open');
				return false;
			}
		});
	};
};

})( jQuery );