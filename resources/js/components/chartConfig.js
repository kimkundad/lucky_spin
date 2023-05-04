import axios from 'axios';

let datalabels = null

const users = 'data_labels';

   const getData = async () => {
      const response = await axios.get(`data_labels`);
      return response.data
    };


    getData()
    .then(data => {
      datalabels = data
    })
    
  console.log('---', datalabels)

export const data = {
    labels: datalabels,
    datasets: [
      {
        backgroundColor: [
        "#8b35bc",
        "#b163da",
        "#8b35bc",
        "#b163da",
        "#8b35bc",
        "#b163da"
        ],
        data: [16, 16, 16, 16, 16, 16]
      }
    ]
  }
  
  export const options = {
    responsive: true,
    maintainAspectRatio: false,
    animation: { duration: 0 },
    plugins: {
        //hide tooltip and legend
        tooltip: false,
        legend: {
          display: false,
        },
        //display labels inside pie chart
        datalabels: {
          rotation: (context) =>
                  context.dataIndex * (360 / context.chart.data.labels.length) +
                  360 / context.chart.data.labels.length / 2 +
                  270 +
                  context.chart.options.rotation,
          color: "#ffffff",
          formatter: (_, context) => context.chart.data.labels[context.dataIndex],
          font: { 
            size: 16,
            family: 'Kanit',
           },
           align: 'start',
           anchor: 'end',
        },
      },
  }
  