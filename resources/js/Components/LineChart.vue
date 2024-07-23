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
  
  
  const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      layout: {
        padding: {
          bottom: 50
        }
      }
  })
  </script>
  
  
  