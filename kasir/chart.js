document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("myChart").getContext("2d");

    // Fetch chart data from the server
    fetch("dashboard_kasir.php?chart_data=true")
        .then((response) => response.json())
        .then((data) => {
            const labels = data.map((item) => item.date);
            const sales = data.map((item) => item.penjualan);

            // Render the chart
            new Chart(ctx, {
                type: "bar", 
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Penjualan",
                            data: sales,
                            borderColor: "rgb(51, 143, 56)",
                            backgroundColor: "rgba(111, 241, 79, 0.56)",
                            borderWidth: 2,
                            tension: 0.3,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: "top",
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        })
        .catch((error) => {
            console.error("Error fetching chart data:", error);
        });
});
