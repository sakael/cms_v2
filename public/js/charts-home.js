/*global $, document, Chart, LINECHART, data, options, window*/
$(document).ready(function() {

  'use strict';

  // ------------------------------------------------------- //
  // Line Chart
  // ------------------------------------------------------ //
  var legendState = true;
  if ($(window).outerWidth() < 576) {
    legendState = false;
  }

  var LINECHART = $('#lineCahrt');
  var myLineChart = new Chart(LINECHART, {
    type: 'line',
    options: {
      scales: {
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          }
        }],
        yAxes: [{
          display: true,
          gridLines: {
            display: false
          }
        }]
      },
      legend: {
        display: legendState
      }
    },
    data: {
      labels: ["Eergisteren", "Gisteren","Vandaag"],
      datasets: [{
          label: "123bestdeal.nl",
          fill: true,
          lineTension: 0,
          backgroundColor: "transparent",
          borderColor: '#f15765',
          pointBorderColor: '#da4c59',
          pointHoverBackgroundColor: '#da4c59',
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          borderWidth: 3,
          pointBackgroundColor: "#da4c59",
          pointBorderWidth: 3,
          pointHoverRadius: 5,
          pointHoverBorderColor: "#ffc36d",
          pointHoverBorderWidth: 10,
          pointRadius: 5,
          pointHitRadius: 20,
          data: [nlBeforeYesterday, nlYesterday,nlToday],
          spanGaps: false
        },
       /* {
          label: "123bestdeal.com",
          fill: true,
          lineTension: 0,
          backgroundColor: "transparent",
          borderColor: "#54e69d",
          pointHoverBackgroundColor: "#44c384",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          borderWidth: 3,
          pointBorderColor: "#44c384",
          pointBackgroundColor: "#44c384",
          pointBorderWidth: 5,
          pointHoverRadius: 5,
          pointHoverBorderColor: "#0000ff",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: [comToday, comYesterday, comBeforeYesterday],
          spanGaps: false
        },*/
        /*{
          label: "bol.com",
          fill: true,
          lineTension: 0,
          backgroundColor: "transparent",
          borderColor: "#0000ff",
          pointHoverBackgroundColor: "#0000ff",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          borderWidth: 3,
          pointBorderColor: "#0000ff",
          pointBackgroundColor: "#0000ff",
          pointBorderWidth: 5,
          pointHoverRadius: 5,
          pointHoverBorderColor: "#ffc36d",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: [bolToday, bolYesterday, bolBeforeYesterday],
          spanGaps: false
        },*/
        {
          label: "Totaal",
          fill: true,
          lineTension: 0,
          backgroundColor: "transparent",
          borderColor: '#ffc36d',
          pointBorderColor: '#ffc36d',
          pointHoverBackgroundColor: '#ffc36d',
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          borderWidth: 3,
          pointBackgroundColor: "#ffc36d",
          pointBorderWidth: 3,
          pointHoverRadius: 5,
          pointHoverBorderColor: "#da4c59",
          pointHoverBorderWidth: 3,
          pointRadius: 5,
          pointHitRadius: 20,
          data: [nlBeforeYesterday , nlYesterday , nlToday ],
          // data: [nlToday + comToday + bolToday, nlYesterday + comYesterday + bolYesterday, nlBeforeYesterday + comBeforeYesterday + bolBeforeYesterday],
          spanGaps: false
        }
      ]
    }
  });



  // ------------------------------------------------------- //
  // Line Chart 1
  // ------------------------------------------------------ //
  var LINECHART1 = $('#lineChart1');
  var myLineChart = new Chart(LINECHART1, {
    type: 'line',
    options: {
      scales: {
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          }
        }],
        yAxes: [{
          ticks: {
            max: 40,
            min: 0,
            stepSize: 0.5
          },
          display: false,
          gridLines: {
            display: false
          }
        }]
      },
      legend: {
        display: false
      }
    },
    data: {
      labels: ["A", "B", "C", "D", "E", "F", "G"],
      datasets: [{
        label: "Total Overdue",
        fill: true,
        lineTension: 0,
        backgroundColor: "transparent",
        borderColor: '#6ccef0',
        pointBorderColor: '#59c2e6',
        pointHoverBackgroundColor: '#59c2e6',
        borderCapStyle: 'butt',
        borderDash: [],
        borderDashOffset: 0.0,
        borderJoinStyle: 'miter',
        borderWidth: 3,
        pointBackgroundColor: "#59c2e6",
        pointBorderWidth: 0,
        pointHoverRadius: 4,
        pointHoverBorderColor: "#fff",
        pointHoverBorderWidth: 0,
        pointRadius: 4,
        pointHitRadius: 0,
       // data: [nlToday + comToday + bolToday, nlYesterday + comYesterday + bolYesterday, nlBeforeYesterday + comBeforeYesterday + bolBeforeYesterday],
        data: [nlBeforeYesterday, nlYesterday , nlToday ],
        spanGaps: false
      }]
    }
  });



  // ------------------------------------------------------- //
  // Pie Chart
  // ------------------------------------------------------ //
  var PIECHART = $('#pieChart');
  var myPieChart = new Chart(PIECHART, {
    type: 'doughnut',
    options: {
      cutoutPercentage: 80,
      legend: {
        display: false
      }
    },
    data: {
      labels: [
        "First",
        "Second",
        "Third",
        "Fourth"
      ],
      datasets: [{
        data: [300, 50, 100, 60],
        borderWidth: [0, 0, 0, 0],
        backgroundColor: [
          '#44b2d7',
          "#59c2e6",
          "#71d1f2",
          "#96e5ff"
        ],
        hoverBackgroundColor: [
          '#44b2d7',
          "#59c2e6",
          "#71d1f2",
          "#96e5ff"
        ]
      }]
    }
  });


  // ------------------------------------------------------- //
  // Bar Chart
  // ------------------------------------------------------ //
  var BARCHARTHOME = $('#barChartHome');
  var barChartHome = new Chart(BARCHARTHOME, {
    type: 'bar',
    height:'400px',
    options: {
      scales: {
        xAxes: [{
          display: false
        }],
        yAxes: [{
          display: false
        }],
      },
      legend: {
        display: false
      }
    },
    data: {
      labels: ["7","6","5", "4", "3", "2", "1"],
      datasets: [{
        label: "Data Set 1",
        backgroundColor: [
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)'
        ],
        borderColor: [
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)',
          'rgb(121, 106, 238)'
        ],
        borderWidth: 1,
        data:[ nlBefore6Yesterday,nlBefore5Yesterday , nlBefore4Yesterday , nlBefore3Yesterday, nlBeforeYesterday, nlYesterday , nlToday]
        //data:[ comBefore6Yesterday + nlBefore6Yesterday + bolBefore6Yesterday, comBefore4Yesterday + nlBefore5Yesterday + bolBefore5Yesterday, comBefore5Yesterday + nlBefore4Yesterday + bolBefore4Yesterday, nlBefore3Yesterday + comBefore3Yesterday + bolBefore3Yesterday, nlBeforeYesterday + comBeforeYesterday + bolBeforeYesterday, nlYesterday + comYesterday + bolYesterday, nlToday + comToday + bolToday]
      }]
    }
  });

});
