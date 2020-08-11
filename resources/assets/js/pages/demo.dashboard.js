/**
 * Theme: Hyper - Responsive Bootstrap 4 Admin Dashboard
 * Author: Coderthemes
 * Module/App: Dashboard
 */

!(function ($) {
  "use strict";

  var Dashboard = function () {
    (this.$body = $("body")), (this.charts = []);
  };

  (Dashboard.prototype.initCharts = function () {
    window.Apex = {
      chart: {
        parentHeightOffset: 0,
        toolbar: {
          show: false,
        },
      },
      grid: {
        padding: {
          left: 0,
          right: 0,
        },
      },
      colors: ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"],
    };

    // --------------------------------------------------
    var colors = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"];
    var dataColors = $("#average-sales").data("colors");
    if (dataColors) {
      colors = dataColors.split(",");
    }
    var options = {
      chart: {
        height: 213,
        type: "donut",
      },
      legend: {
        show: false,
      },
      stroke: {
        colors: ["transparent"],
      },
      series: [44, 55, 41, 17],
      labels: ["Direct", "Affilliate", "Sponsored", "E-mail"],
      colors: colors,
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 200,
            },
            legend: {
              position: "bottom",
            },
          },
        },
      ],
    };

    var chart = new ApexCharts(document.querySelector("#average-sales"), options);

    chart.render();
  }),
    // inits the map
    (Dashboard.prototype.initMaps = function () {
      //various examples
      if ($("#world-map-markers").length > 0) {
        $("#world-map-markers").vectorMap({
          map: "world_mill_en",
          normalizeFunction: "polynomial",
          hoverOpacity: 0.7,
          hoverColor: false,
          regionStyle: {
            initial: {
              fill: "#e3eaef",
            },
          },
          markerStyle: {
            initial: {
              r: 9,
              fill: "#727cf5",
              "fill-opacity": 0.9,
              stroke: "#fff",
              "stroke-width": 7,
              "stroke-opacity": 0.4,
            },

            hover: {
              stroke: "#fff",
              "fill-opacity": 1,
              "stroke-width": 1.5,
            },
          },
          backgroundColor: "transparent",
          markers: [
            {
              latLng: [40.71, -74.0],
              name: "New York",
            },
            {
              latLng: [37.77, -122.41],
              name: "San Francisco",
            },
            {
              latLng: [-33.86, 151.2],
              name: "Sydney",
            },
            {
              latLng: [1.3, 103.8],
              name: "Singapore",
            },
          ],
          zoomOnScroll: false,
        });
      }
    }),
    //initializing various components and plugins
    (Dashboard.prototype.init = function () {
      var $this = this;
      // font
      // Chart.defaults.global.defaultFontFamily = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';

      //default date range picker
      $("#dash-daterange").daterangepicker({
        singleDatePicker: true,
      });

      // init charts
      this.initCharts();

      //init maps
      this.initMaps();
    }),
    //init flotchart
    ($.Dashboard = new Dashboard()),
    ($.Dashboard.Constructor = Dashboard);
})(window.jQuery),
  //initializing Dashboard
  (function ($) {
    "use strict";
    $(document).ready(function (e) {
      $.Dashboard.init();
    });
  })(window.jQuery);
