<template>
    <canvas id="donut" ref="donut" width="400" height="400"></canvas>
</template>

<script>
  import Chart from 'chart.js'

  export default {
    name: 'JobsDonutComponent',
    mounted () {
      let ctx = this.$refs.donut.getContext('2d')
      let config = {
        type: 'pie',
        data: {
          datasets: [{
            data: [
              56,
              45,
              20
            ],
            backgroundColor: [
              '#F7464A',
              '#46BFBD',
              '#FDB45C'
            ]
          }],
          labels: [
            'Principal Amount',
            'Interest Amount',
            'Processing Fee'
          ]
        },
        options: {
          responsive: true
        }
      }
      new Chart(ctx, config)
    }
  }
</script>
