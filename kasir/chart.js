document.addEventListener("DOMContentLoaded", function () {
    fetchChartData('days', 'chartDays');
    fetchChartData('months', 'chartMonths');
});

function fetchChartData(type, canvasId) {
    fetch(`dashboard_kasir.php?chart_data=1&type=${type}`)
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById(canvasId).getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: "Sales",
                        data: data.map(d => d.penjualan),
                        borderColor: "blue",
                        fill: false
                    }]
                }
            });
        })
        .catch(error => console.error("Error fetching chart data:", error));
}
