// Safe version of script.js with proper checks

// >>-- 01 Simple-bar JS --<<
if (document.getElementById('app-simple-bar')) {
  const myElement = document.getElementById('app-simple-bar');
  if (myElement && window.SimpleBar) {
    new SimpleBar(myElement, { autoHide: true });
  }
}

// >>-- 02 feather icon JS --<<
if (typeof feather !== 'undefined') {
  feather.replace();
}

// >>-- 03 Tooltip JS --<<
if (typeof bootstrap !== 'undefined') {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

// >>-- 04 Loader JS --<<
$(document).ready(function() {
  $('.loader-wrapper').fadeOut('slow', function () {
    $(this).remove();
  });
});

// >>-- 05 tap on top (scroll to top button) --<<
let calcScrollValue = () => {
  const $scrollProgress = document.getElementsByClassName("go-top")[0];
  if (!$scrollProgress) return; // Exit if element doesn't exist

  const $progressValue = document.getElementsByClassName("progress-value")[0];
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

  $scrollProgress.style.background = `conic-gradient(rgba(var(--primary), 1) ${scrollValue}%, rgba(var(--primary), 1) ${scrollValue}%)`;
};

window.onscroll = calcScrollValue;
window.onload = calcScrollValue;

// >>-- 06 Sidebar Toggle --<<
$(document).ready(function() {
  $('.sidebar-toggle').on('click', function() {
    $('.sidebar').toggleClass('show');
    $('.page-wrapper').toggleClass('sidebar-collapsed');
  });

  // Close sidebar on mobile when clicking outside
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.sidebar, .sidebar-toggle').length) {
      if ($(window).width() < 992) {
        $('.sidebar').removeClass('show');
      }
    }
  });
});

// >>-- 07 Dark Mode Toggle --<<
const themeToggle = document.querySelector("#theme-toggle");
if (themeToggle) {
  themeToggle.addEventListener("click", function() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
  });
}

// >>-- 08 Collapse submenu --<<
const closeCollaps = document.querySelectorAll('.sidebar-nav li a[data-bs-toggle="collapse"]');
if (closeCollaps.length > 0) {
  closeCollaps.forEach(function (item) {
    item.addEventListener('click', function (e) {
      const all = document.querySelectorAll('.sidebar-nav ul.collapse');
      const target = e.target.getAttribute('href');

      all.forEach(function (element) {
        if ('#' + element.id !== target) {
          const bsCollapse = bootstrap.Collapse.getInstance(element);
          if (bsCollapse) {
            bsCollapse.hide();
          }
        }
      });
    });
  });
}
