<template>
    <div>
        <canvas id="donut" ref="donut"></canvas>
    </div>
</template>

<script>
  import Chart from 'chart.js'

  export default {
    name: 'DonutComponent',
    props: {
      labels: {
        type: Array,
        required: true
      },
      title: {
        type: String,
        default: 'Donut graph'
      }
    },
    mounted () {
      let ctx = this.$refs.donut.getContext('2d')
      let config = {
        type: 'doughnut',
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
          labels: this.labels
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          title: {
            display: true,
            text: this.title
          }
        }
      }
      new Chart(ctx, config)
    }
  }
</script>
