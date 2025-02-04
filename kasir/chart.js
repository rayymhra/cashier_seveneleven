document.addEventListener("DOMContentLoaded", function () {
  fetchChartData("days", "chartDays");
  fetchChartData("months", "chartMonths");
});

function fetchChartData(type, canvasId) {
  fetch(`dashboard_kasir.php?chart_data=1&type=${type}`)
    .then((response) => response.json())
    .then((data) => {
      const ctx = document.getElementById(canvasId).getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: data.map((d) => d.date),
          datasets: [
            {
              label: "Pendapatan",
              data: data.map((d) => d.penjualan),
              borderColor: "#004f41",
              backgroundColor: "rgba(111, 241, 79, 0.56)",
              borderWidth: 1
            },
          ],
        },
      });
    })
    .catch((error) => console.error("Error fetching chart data:", error));
}
