'use strict';

$( document ).ready(function() {
  //preloader js code
  $(".preloader").delay(300).animate({
    "opacity" : "0"
    }, 300, function() {
    $(".preloader").css("display","none");
  });
});

// Show or hide the sticky footer button
$(window).on("scroll", function() {
  if ($(this).scrollTop() > 200) {
    $(".scroll-to-top").fadeIn(200);
  } else {
    $(".scroll-to-top").fadeOut(200);
  }
});

// Animate the scroll to top
$(".scroll-to-top").on("click", function(event) {
  event.preventDefault();
  $("html, body").animate({scrollTop: 0}, 300);
});

// menu options custom affix
var fixed_top = $(".header");
$(window).on("scroll", function(){
    if( $(window).scrollTop() > 50){  
        fixed_top.addClass("animated fadeInDown menu-fixed");
    }
    else{
        fixed_top.removeClass("animated fadeInDown menu-fixed");
    }
});

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function() {
  const element = $(this).parent("li");
  if (element.hasClass("open")) {
    element.removeClass("open");
    element.find("li").removeClass("open");
  }
  else {
    element.addClass("open");
    element.siblings("li").removeClass("open");
    element.siblings("li").find("li").removeClass("open");
  }
});

// wow js init
new WOW().init();

// main wrapper calculator
var bodySelector = document.querySelector('body');
var header = document.querySelector('.header');
var footer = document.querySelector('.footer');
(function(){
  if(bodySelector.contains(header) && bodySelector.contains(footer)){
    var headerHeight = document.querySelector('.header').clientHeight;
    var footerHeight = document.querySelector('.footer').clientHeight;

    // if header isn't fixed to top
    var totalHeight = parseInt( headerHeight, 10 ) + parseInt( footerHeight, 10 ) + 'px'; 
    
    // if header is fixed to top
    // var totalHeight = parseInt( footerHeight, 10 ) + 'px'; 
    var minHeight = '100vh';
    document.querySelector('.main-wrapper').style.minHeight = `calc(${minHeight} - ${totalHeight})`;
  }
})();

$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    boundary: 'window'
  })
});

// with short level
$('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate).on('update.countdown', function(event) {
    var format = '%D days %H hr : %M mn : %S sec';
    $(this).html(event.strftime(format));
  }).on('finish.countdown', function(event) {
    var expireData = $(this).data('title');
    $(this).html(expireData).parent().addClass('disabled');
  });
});

// with Level
$('[data-clock]').each(function() {
  var $this = $(this), finalDate = $(this).data('clock');
  $this.countdown(finalDate)
  .on('update.countdown', function(event) {
    var format = ''+'<div><span>%D</span><p>days</p></div>'+'<div><span>%H</span><p>hours</p></div>'+'<div><span>%M</span><p>minutes</p></div>'+'<div><span>%S</span><p>seconds</p></div>';
    $(this).html(event.strftime(format));
  })
  .on('finish.countdown', function(event) {
    var expireData = $(this).data('title');
    $(this).html(expireData).addClass('disabled');
  });
});
/* ==============================
					slider area
================================= */

// testimonial-slider 
$('.testimonial-slider').slick({
  autoplay: false,
  autoplaySpeed: 2000,
  dots: false,
  infinite: true,
  speed: 300,
  slidesToShow: 3,
  arrows: false,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 3,
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1,
      }
    }
  ]
});

// payment-slider js 
$('.payment-slider').slick({
  dots: false,
  infinite: true,
  speed: 300,
  slidesToShow: 6,
  slidesToScroll: 1,
  arrows: false,
  autoplay: false,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
  ]
});