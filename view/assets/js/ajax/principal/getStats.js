$(document).ready(function(){
    $.ajax({
        url: 'controller/ajax/getStats.php',
        method: 'POST',
        dataType: 'json',
        success: function(data) {
            const ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Urgente', 'Pendiente', 'Inmediato'],
                    datasets: [
                        {
                            label: 'Pendiente',
                            data: [
                                data.Urgente || 0, 
                                data.Pendiente || 0, 
                                data.Inmediato || 0
                            ],
                            backgroundColor: 'rgba(201, 34, 34, 0.5)',
                            borderColor: 'rgba(201, 34, 34, 1)',
                            borderWidth: 1,
                            borderSkipped: false,
                            hoverBackgroundColor: 'rgba(201, 34, 34, 0.7)'
                        },
                        {
                            label: 'Completado',
                            data: [
                                data.UrgenteComplete || 0, 
                                data.PendienteComplete || 0, 
                                data.InmediatoComplete || 0
                            ],
                            backgroundColor: 'rgba(11, 110, 74, 0.5)',
                            borderColor: 'rgba(11, 110, 74, 1)',
                            borderWidth: 1,
                            borderSkipped: false,
                            hoverBackgroundColor: 'rgba(11, 110, 74, 0.7)'
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        x: {
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 16
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 16
                            },
                            bodyFont: {
                                size: 14
                            },
                            footerFont: {
                                size: 12
                            },
                            borderColor: 'rgba(0, 0, 0, 0.7)',
                            borderWidth: 1
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeInOutBounce'
                    }
                }
            });
        },
    });
});
