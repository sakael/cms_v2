window.jQuery = $;
window.$ = $;
Vue.config.devtools = true;
window.Vue = window.Vue = Vue;

window.toastr = toastr;

toastr.options = {
  closeButton: true,
  closeHtml: '<button><i class="fa fa-close"></i></button>',
  debug: false,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-bottom-right",
  preventDuplicates: false,
  onclick: null,
  showDuration: "3000",
  hideDuration: "1000",
  timeOut: 5000,
  extendedTimeOut: 5000,
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
  tapToDismiss: true,
};

axios.defaults.headers.common = {
  "X-Requested-With": "XMLHttpRequest",
  "X-CSRFToken": "example-of-custom-header",
};

var SITE = "https://beta.123bestdeal.nl/";

window.setTimeout(function () {
  $(".alert")
    .fadeTo(1000, 0)
    .slideUp(1000, function () {
      $(this).remove();
    });
}, 5000);

var app_live_search_site = new Vue({ el: "#live-search-site" });