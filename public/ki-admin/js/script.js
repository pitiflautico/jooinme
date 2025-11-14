// Ki-Admin Script - Clean Version for Laravel

// >>-- 01 Simple-bar JS --<<
const myElement = document.getElementById('app-simple-bar');
if (myElement && typeof SimpleBar !== 'undefined') {
  new SimpleBar(myElement, { autoHide: true });
}

// >>-- 02 Tooltip JS --<<
if (typeof bootstrap !== 'undefined') {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

// >>-- 03 Loader JS --<<
$(document).ready(function() {
  $('.loader-wrapper').fadeOut('slow', function () {
    $(this).remove();
  });
});

// >>-- 04 Header Toggle (Mobile Menu) --<<
$(document).on('click', '.header-toggle', function() {
  $('nav').toggleClass('nav-open');
  $('.app-wrapper').toggleClass('nav-open');
});

// >>-- 05 Semi Nav Toggle --<<
$(document).on('click', '.toggle-semi-nav', function() {
  $('.app-wrapper').toggleClass('semi-nav');
  const icon = $(this).find('i');

  if ($('.app-wrapper').hasClass('semi-nav')) {
    icon.removeClass('ti-chevron-right').addClass('ti-chevron-left');
  } else {
    icon.removeClass('ti-chevron-left').addClass('ti-chevron-right');
  }
});

// >>-- 06 Collapse submenu --<<
$('.main-nav li a[data-bs-toggle="collapse"]').on('click', function (e) {
  const target = $(this).attr('href');

  // Close other open submenus
  $('.main-nav ul.collapse').each(function () {
    if ('#' + this.id !== target && $(this).hasClass('show')) {
      $(this).collapse('hide');
    }
  });
});

// >>-- 07 Scroll to Top Button --<<
let calcScrollValue = () => {
  const $scrollProgress = document.getElementsByClassName("go-top")[0];
  if (!$scrollProgress) return; // Exit if element doesn't exist

  const docElement = document.documentElement;
  const pos = docElement.scrollTop;
  const calcHeight = docElement.scrollHeight - docElement.clientHeight;
  const scrollValue = Math.round((pos * 100) / calcHeight);

  if (pos > 100) {
    $scrollProgress.style.display = 'grid';
  } else {
    $scrollProgress.style.display = 'none';
  }

  $scrollProgress.addEventListener("click", () => {
    docElement.scrollTop = 0;
  });

  if ($scrollProgress.style.background !== undefined) {
    $scrollProgress.style.background = `conic-gradient(var(--bs-primary) ${scrollValue}%, transparent ${scrollValue}%)`;
  }
};

window.addEventListener('scroll', calcScrollValue);
window.addEventListener('load', calcScrollValue);

// >>-- 08 Dark Mode Toggle --<<
$(document).ready(function() {
  // Check for saved theme preference
  const savedTheme = localStorage.getItem('theme-mode') || 'light';

  if (savedTheme === 'dark') {
    $('body').addClass('dark-mode');
  }

  // Theme toggle handler
  $(document).on('click', '.theme-toggle, .header-dark', function() {
    $('body').toggleClass('dark-mode');

    const newTheme = $('body').hasClass('dark-mode') ? 'dark' : 'light';
    localStorage.setItem('theme-mode', newTheme);

    // Update icon
    if (newTheme === 'dark') {
      $('.sun-logo').removeClass('sun').addClass('moon');
    } else {
      $('.moon-logo').removeClass('moon').addClass('sun');
    }
  });
});

// >>-- 09 Close dropdown on outside click --<<
$(document).on('click', function(e) {
  if (!$(e.target).closest('.dropdown').length) {
    $('.dropdown-menu').removeClass('show');
  }
});

// >>-- 10 Active menu item based on current URL --<<
$(document).ready(function() {
  const currentPath = window.location.pathname;

  $('.main-nav a').each(function() {
    const href = $(this).attr('href');
    if (href && currentPath.includes(href) && href !== '/') {
      $(this).closest('li').addClass('active');
      $(this).closest('.collapse').addClass('show');
    }
  });
});
