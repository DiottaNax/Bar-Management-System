document.addEventListener("DOMContentLoaded", () => {
  const salesCostsChart = document
    .getElementById("salesChart")
    .getContext("2d");
  const servicesChart = document
    .getElementById("servicesChart")
    .getContext("2d");
  let salesCostsChartInstance, servicesChartInstance;

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

  function initServicesChart(labels, peopleServed, tablesServed) {
    servicesChartInstance = new Chart(servicesChart, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "People Served",
            data: peopleServed,
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 1,
          },
          {
            label: "Tables Served",
            data: tablesServed,
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
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

  function updateSalesAndCostsChart() {
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;

    if (!startDate || !endDate) {
      alert(
        "Please select both start and end dates for the sales and costs chart."
      );
      return;
    }

    axios
      .get(
        `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=sales`
      )
      .then((salesResponse) => {
        return axios
          .get(
            `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=costs`
          )
          .then((costsResponse) => {
            return [salesResponse.data, costsResponse.data];
          });
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
        `./api/get_financial_info.php?startDate=${startDate}&endDate=${endDate}&type=services`
      )
      .then((response) => {
        const labels = [];
        const peopleServed = [];
        const tablesServed = [];

        response.data.forEach((entry) => {
          labels.push(entry.serviceDate);
          peopleServed.push(entry.peopleServed);
          tablesServed.push(entry.tablesServed);
        });

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

  document.getElementById("update-charts").addEventListener("click", () => {
    updateSalesAndCostsChart();
    updateServicesChart();
  });

  // Initialize charts with empty data
  initSalesAndCostsChart([], [], []);
  initServicesChart([], [], []);
});
