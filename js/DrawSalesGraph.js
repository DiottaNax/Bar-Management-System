document.addEventListener("DOMContentLoaded", () => {
  const chart = document.getElementById("salesChart").getContext("2d");
  let salesChart;

  function initChart(labels, data) {
    salesChart = new Chart(chart, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Cumulative Sales",
            data: data,
            fill: true,
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "rgba(75, 192, 192, 1)",
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
              text: "Cumulative Sales (€)",
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
            text: "Cumulative Sales Over Time",
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

    axios
      .get(
        "./api/get_sales_info.php?startDate=" +
          startDate +
          "&endDate=" +
          endDate
      )
      .then((response) => {
        console.log(response.data);
        return response.data;
      })
      .then((data) => {
        const labels = [];
        const salesData = [];
        let cumulativeTotal = 0;

        data.forEach((entry) => {
          labels.push(entry.receiptDate);
          //cumulativeTotal += entry.totalSum;
          salesData.push(/*cumulativeTotal*/ entry.totalSum);
        });

        if (salesChart) {
          salesChart.destroy();
        }
        initChart(labels, salesData);
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
  initChart([], []);
});
