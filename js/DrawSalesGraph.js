document.addEventListener("DOMContentLoaded", () => {
  const chart = document.getElementById("salesChart").getContext("2d");
  let salesCostsChart;

  function initChart(labels, salesData, costsData) {
    salesCostsChart = new Chart(chart, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Cumulative Sales",
            data: salesData,
            fill: true,
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "rgba(75, 192, 192, 1)",
            tension: 0.1,
          },
          {
            label: "Cumulative Costs",
            data: costsData,
            fill: true,
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            tension: 0.1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: "Cumulative Amount (€)",
            },
          },
          x: {
            title: {
              display: true,
              text: "Date",
            },
          },
        },
        plugins: {
          title: {
            display: true,
            text: "Cumulative Sales and Costs Over Time",
          },
        },
      },
    });
  }

  function updateChart() {
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;

    if (!startDate || !endDate) {
      alert("Please select both start and end dates.");
      return;
    }

    Promise.all([
      axios.get(
        `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=sales`
      ),
      axios.get(
        `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=costs`
      ),
    ])
      .then(([salesResponse, costsResponse]) => {
        return [salesResponse.data, costsResponse.data];
      })
      .then(([salesData, costsData]) => {
        const allDates = new Set(
          [
            ...salesData.map((entry) => entry.receiptDate),
            ...costsData.map((entry) => entry.orderDate),
          ].sort()
        );

        const labels = Array.from(allDates);
        const salesChartData = [];
        const costsChartData = [];
        let cumulativeSales = 0;
        let cumulativeCosts = 0;

        labels.forEach((date) => {
          const salesEntry = salesData.find(
            (entry) => entry.receiptDate === date
          );
          const costsEntry = costsData.find(
            (entry) => entry.orderDate === date
          );

          if (salesEntry) {
            cumulativeSales += salesEntry.totalSum;
          }
          if (costsEntry) {
            cumulativeCosts += costsEntry.totalSum;
          }

          salesChartData.push(cumulativeSales);
          costsChartData.push(cumulativeCosts);
        });

        if (salesCostsChart) {
          salesCostsChart.destroy();
        }
        initChart(labels, salesChartData, costsChartData);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        alert("An error occurred while fetching data. Please try again.");
      });
  }

  document
    .getElementById("update-chart")
    .addEventListener("click", updateChart);

  // Initialize chart with empty data
  initChart([], [], []);
});
