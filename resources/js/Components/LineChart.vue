<template>
    <Line :data="chartData" :options="chartOptions" />
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { Line } from 'vue-chartjs'
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
  } from 'chart.js'
  
  // Register Chart.js components
  ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)
  
  // Data and options for the chart
  const chartData = ref({
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
      {
        label: 'Received',
        backgroundColor: 'rgba(75,192,192,0.4)',
        borderColor: 'rgba(75,192,192,1)',
        data: [40, 39, 10, 40, 39, 80, 40]
      },
      {
        label: 'Processing',
        backgroundColor: 'rgba(75,192,192,0.4)',
        borderColor: 'rgba(75,0,192,1)',
        data: [40, 39, 10, 40, 9, 80, 40]
      },
      {
        label: 'Produced',
        backgroundColor: 'rgba(75,192,192,0.4)',
        borderColor: 'rgba(75,0,192,1)',
        data: [40, 39, 10, 40, 9, 80, 40]
      },
      {
        label: 'Delivered',
        backgroundColor: 'rgba(75,192,192,0.4)',
        borderColor: 'rgba(75,0,192,1)',
        data: [40, 39, 10, 40, 9, 80, 40]
      },
      {
        label: 'Packaged',
        backgroundColor: 'rgba(75,192,192,0.4)',
        borderColor: 'rgba(75,0,192,1)',
        data: [40, 39, 10, 40, 9, 80, 40]
      },
      {
        label: 'Dispatched',
        backgroundColor: 'rgba(75,192,192,0.4)',
        borderColor: 'rgba(75,0,192,1)',
        data: [40, 39, 10, 40, 9, 80, 40]
      }
    ]
  })
  
  const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false
  })
  </script>
  
  
  