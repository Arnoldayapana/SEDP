    var ctx = document.getElementById('userActivityChart').getContext('2d');
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(0, 60, 60, 0.5)');
    gradient.addColorStop(1, 'rgba(0, 60, 60, 0.1)');

    // Sample data for active user days over the months
    var userActivityData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Active User Days',
            data: [5, 10, 15, 20, 25, 30, 28, 22, 18, 15, 12, 20], 
            backgroundColor: gradient,
            borderColor: '#003c3c',
            borderWidth: 3,
            pointBackgroundColor: '#003c3c',
            pointRadius: 4,
            fill: true,
            tension: 0.4
        }]
    };

    var userActivityChart = new Chart(ctx, {
        type: 'line',
        data: userActivityData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month',
                        color: '#003c3c',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        color: '#003c3c'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Active User Days',
                        color: '#003c3c',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    beginAtZero: true,
                    ticks: {
                        color: '#003c3c'
                    },
                    grid: {
                        color: 'rgba(0, 60, 60, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#003c3c',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#003c3c',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    titleFont: {
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' days active';
                        },
                        title: function(tooltipItem) {
                            return 'Month: ' + tooltipItem[0].label;
                        }
                    }
                }
            }
        }
    });
