/*!

 =========================================================
 * Awesome Landing Page - v1.2.2
 =========================================================
 
 * Product Page: https://www.creative-tim.com/product/awesome-landing-page
 * Copyright 2017 Creative Tim (http://www.creative-tim.com)
 * Licensed under MIT (https://github.com/creativetimofficial/awesome-landing-page/blob/master/LICENSE.md)
 
 =========================================================
 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 */

        var big_image;
        $().ready(function() { 
            
            /*Slide homepage*/
            $('#slide .flexslider').flexslider({
                animation: "fade",
                slideshowSpeed: 5000,
                directionNav: true,
                start: function(){
                    setTimeout(function(){
                        $('.slider-text').removeClass('animated fadeInUp');
                        $('.flex-active-slide').find('.slider-text').addClass('animated fadeInUp');
                        $('.flex-active-slide').css("z-index", "unset");
                    }, 500);
                },
                before: function(){
                    setTimeout(function(){
                        $('.slider-text').removeClass('animated fadeInUp');
                        $('.flex-active-slide').find('.slider-text').addClass('animated fadeInUp');
                        $('.flex-active-slide').css("z-index", "unset");
                    }, 500);
                }
    
              }); 
            
            /*menu  click scroll */
            $('#navbar a:not([class="external"])').click(function(event){
                var section = $(this).data('nav-section'),
                    navbar = $('#navbar');
    
                    if ( $('[data-section="' + section + '"]').length ) {
                        $('html, body').animate({
                            scrollTop: $('[data-section="' + section + '"]').offset().top - 85                            
                        }, 500);                        
                    }
    
                if ( navbar.is(':visible')) {
                    navbar.removeClass('in');
                    navbar.attr('aria-expanded', 'false');
                    $('.js-colorlib-nav-toggle').removeClass('active');
                }
    
                event.preventDefault();
                return false;
            });
            /*fim*/

            $('.selector').click(function() {
                SelectColor(this);
            });
            var selectCol = 0;
            if (selectCol == 0) {
                if ($('body').hasClass('landing-page1')) {

                }
            }



            var formCompany = $('.form-company');
            var animateBox = $('.animate-box');
            var cnpj = $('#cnpj');
            var animateBox2 = $('.animate-box2');
            var city = $('#city');
            var animateBox3 = $('.animate-box3');
            var email = $('#email');
            var animateBox4 = $('.animate-box-4');
            var animateBox5 = $('.animate-box5');
            var faleConosco = $('.fale-conosco');
            var animateBox6 = $('.animate-box6');
            var animateBox7 = $('.animate-box7');


            formCompany.waypoint(function (){
                animateBox.addClass('js-form-control-animate fadeInLeft animated');
            }, {offset: '50%'}
            );

            cnpj.waypoint(function (){
                animateBox2.addClass('js-form-control-animate fadeInRight animated');
            }, {offset: '50%'}
            );

            city.waypoint(function (){
                animateBox3.addClass('js-form-control-animate fadeInLeft animated');
            }, {offset: '50%'}
            );

            email.waypoint(function (){
                animateBox4.addClass('js-form-control-animate fadeInLeft animated');
                animateBox5.addClass('js-form-control-animate fadeInRight animated');

            }, {offset: '50%'}
            );

            faleConosco.waypoint(function (){
                animateBox6.addClass('js-form-control-animate fadeInLeft animated');
                animateBox7.addClass('js-form-control-animate fadeInRight animated');

            }, {offset: '50%'}
            );


        });

        $(window).on('scroll', function() {
            responsive = $(window).width();
            if (responsive >= 768) {
                parallax();
            }
        });

        $(window).scroll(function() {
            var st = $(this).scrollTop();
            if(st > 100){
                $("#navbar-menu").removeClass('navbar-transparent');
                $("#navbar-menu").addClass('navbar-menu-background');
                $("#navbar > li > a").addClass('text-menu');
                $(".dropbtn > a").addClass('text-menu');
                $(".brand").addClass('text-menu');
            } else{
                $("#navbar-menu").addClass('navbar-transparent');
                $("#navbar-menu").removeClass('navbar-menu-background');
                $("#navbar > li > a").removeClass('text-menu');
                $(".dropbtn > a").removeClass('text-menu');
                $(".brand").removeClass('text-menu');


            }
        });

        function SelectColor(btn) {
            oldColor = $('.filter-gradient').attr('data-color');
            newColor = $(btn).attr('data-color');

            oldButton = $('a[id^="Demo"]').attr('data-button');
            newButton = $(btn).attr('data-button');

            $('.filter-gradient').removeClass(oldColor).addClass(newColor).attr('data-color', newColor);

            $('a[id^="Demo"]').removeClass("btn-" + oldButton).addClass("btn-" + newButton).attr('data-button', newButton);

            $('.carousel-indicators').removeClass("carousel-indicators-" + oldColor).addClass("carousel-indicators-" + newColor);

            $('.card').removeClass("card-" + oldColor).addClass("card-" + newColor);

            $('.selector').removeClass('active');
            $(btn).addClass('active');
        }

        $('.switch').each(function() {
            var selector = $(this).parent('li')
            $(this).click(function() {
                if (selector.siblings().hasClass('active')) {
                    selector.addClass('active');
                    selector.siblings().removeClass('active');
                    var slide = $(this).attr('data-slide')
                    var lastClass = $('body').attr('class').split(' ').pop();
                    $('body').removeClass(lastClass);
                    $('body').addClass('landing-page' + slide);
                }
            });
        });

        var parallax = debounce(function() {
            no_of_elements = 0;
            $('.parallax').each(function() {
                var $elem = $(this);

                if (isElementInViewport($elem)) {
                    var parent_top = $elem.offset().top;
                    var window_bottom = $(window).scrollTop();
                    var $image = $elem.find('.parallax-background-image')
                    var $oVal = ((window_bottom - parent_top) / 3);
                    $image.css('margin-top', $oVal + 'px');
                }
            });
        }, 6)

        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this,
                    args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                }, wait);
                if (immediate && !timeout) func.apply(context, args);
            };
        };


        function isElementInViewport(elem) {
            var $elem = $(elem);

            // Get the scroll position of the page.
            var scrollElem = ((navigator.userAgent.toLowerCase().indexOf('webkit') != -1) ? 'body' : 'html');
            var viewportTop = $(scrollElem).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            // Get the position of the element on the page.
            var elemTop = Math.round($elem.offset().top);
            var elemBottom = elemTop + $elem.height();

            return ((elemTop < viewportBottom) && (elemBottom > viewportTop));
        }

        