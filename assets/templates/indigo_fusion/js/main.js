'use strict';

$('table td[colspan]').addClass('justify-content-center text-center');

// menu options custom affix
var fixed_top = $(".header");
$(window).on("scroll", function () {
  if ($(window).scrollTop() > 50) {
    fixed_top.addClass("animated fadeInDown menu-fixed");
  }
  else {
    fixed_top.removeClass("animated fadeInDown menu-fixed");
  }
});

function showAmount(number) {
  var str = number.toString().split(".");
  str[0] = str[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return str.join(".");
}


function snakeCase(string) {
  return string.replace(/ /g, "_").toLowerCase();
};

function titleCase(string) {
  return string.replace("_", ' ').replace(/\w\S*/g, function (txt) { return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); });
};

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function () {
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

//preloader js code
$(".preloader").delay(300).animate({
  "opacity": "0"
}, 300, function () {
  $(".preloader").css("display", "none");
});


// wow js init
new WOW().init();

// main wrapper calculator
var bodySelector = document.querySelector('body');
var header = document.querySelector('.header');
var footer = document.querySelector('.footer');
(function () {
  if (bodySelector.contains(header) && bodySelector.contains(footer)) {
    var headerHeight = document.querySelector('.header').clientHeight;
    var footerHeight = document.querySelector('.footer').clientHeight;

    // if header isn't fixed to top
    // var totalHeight = parseInt( headerHeight, 10 ) + parseInt( footerHeight, 10 ) + 'px'; 

    // if header is fixed to top
    var totalHeight = parseInt(footerHeight, 10) + 'px';
    var minHeight = '100vh';
    document.querySelector('.main-wrapper').style.minHeight = `calc(${minHeight} - ${totalHeight})`;
  }
})();

// Animate the scroll to top
$(".scroll-top").on("click", function (event) {
  event.preventDefault();
  $("html, body").animate({ scrollTop: 0 }, 300);
});



var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});


const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

$('.table-responsive').on('click', '[data-bs-toggle="dropdown"]', function (e) {
  const { top, left } = $(this).next(".dropdown-menu")[0].getBoundingClientRect();
  $(this).next(".dropdown-menu").css({
    position: "fixed",
    inset: "unset",
    transform: "unset",
    top: top + "px",
    left: left + "px",
  });
});

if ($('.table-responsive').length) {
  $(window).on('scroll', function (e) {
    $('.table-responsive .dropdown-menu').removeClass('show');
    $('.table-responsive [data-bs-toggle="dropdown"]').removeClass('show');
  });
}





