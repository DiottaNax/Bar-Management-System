document.addEventListener("DOMContentLoaded", () => {
  // Get the graphs' canvases from the HTML
  const salesCostsChart = document
    .getElementById("salesChart")
    .getContext("2d");
  const servicesChart = document
    .getElementById("servicesChart")
    .getContext("2d");
  let salesCostsChartInstance, servicesChartInstance;

  // Initialize the sales and costs chart
  function initSalesAndCostsChart(labels, salesData, costsData) {
    salesCostsChartInstance = new Chart(salesCostsChart, {
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

  // Initialize the services chart
  function initServicesChart(labels, peopleServed, tablesServed) {
    servicesChartInstance = new Chart(servicesChart, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "People Served",
            data: peopleServed,
            backgroundColor: "rgba(0, 191, 255, 0.2)", // Light blue
            borderColor: "rgba(0, 191, 255, 1)", // Blue
            borderWidth: 1,
          },
          {
            label: "Tables Served",
            data: tablesServed,
            backgroundColor: "rgba(255, 215, 0, 0.2)", // Light yellow
            borderColor: "rgba(255, 215, 0, 1)", // Yellow
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: "Number of People/Tables",
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
            text: "Daily Services",
          },
        },
      },
    });
  }

  // Update the sales and costs chart
  function updateSalesAndCostsChart() {
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;

    if (!startDate || !endDate) {
      alert(
        "Please select both start and end dates for the sales and costs chart."
      );
      return;
    }

    // Get the sales and costs data from the appropriate API
    axios
      .get(
        // Get request for sales with start and end date as parameters
        `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=sales`
      )
      .then((salesResponse) => {
        return axios
          .get(
            // Get request for costs with start and end date as parameters
            `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=costs`
          )
          .then((costsResponse) => {
            return [salesResponse.data, costsResponse.data];
          });
      })
      .then(([salesData, costsData]) => {
        // Update dates for the sales chart
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
          // Find the sales and costs data for the current date
          const salesEntry = salesData.find(
            (entry) => entry.receiptDate === date
          );
          const costsEntry = costsData.find(
            (entry) => entry.orderDate === date
          );

          // If there's a sale or a cost for the specified day update the cumulative variables
          if (salesEntry) {
            cumulativeSales += salesEntry.totalSum;
          }
          if (costsEntry) {
            cumulativeCosts += costsEntry.totalSum;
          }

          // Update cumulative sales and costs in the chart data
          salesChartData.push(cumulativeSales);
          costsChartData.push(cumulativeCosts);
        });

        // Destroy the previous chart if there's one
        if (salesCostsChartInstance) {
          salesCostsChartInstance.destroy();
        }
        initSalesAndCostsChart(labels, salesChartData, costsChartData);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        alert(
          "An error occurred while fetching data for the sales and costs chart. Please try again."
        );
      });
  }

  function updateServicesChart() {
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;

    if (!startDate || !endDate) {
      alert("Please select both start and end dates for the services chart.");
      return;
    }

    axios
      .get(
        // Get request for services with start and end date as parameters
        `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=services`
      )
      .then((response) => {
        const labels = [];
        const peopleServed = [];
        const tablesServed = [];

        // For each entry in the response, add the service date, the number of people served and the number of tables served to the arrays
        response.data.forEach((entry) => {
          labels.push(entry.serviceDate);
          peopleServed.push(entry.peopleServed);
          tablesServed.push(entry.tablesServed);
        });

        // Destroy the previous chart if there's one
        if (servicesChartInstance) {
          servicesChartInstance.destroy();
        }
        initServicesChart(labels, peopleServed, tablesServed);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        alert(
          "An error occurred while fetching data for the services chart. Please try again."
        );
      });
  }

  // Update the sales and costs chart when the button is clicked
  document.getElementById("update-charts").addEventListener("click", () => {
    updateSalesAndCostsChart();
    updateServicesChart();
  });

  // Initialize charts with empty data
  initSalesAndCostsChart([], [], []);
  initServicesChart([], [], []);
});
