$(document).ready(function(){
    $.ajax({
        url: 'controller/ajax/getStats.php',
        method: 'POST',
        dataType: 'json',
        success: function(data) {
            
            $('.numberUsers').text(data.numberUsers);
            $('.numberSchools').text(data.numberSchools);
            $('.numberAreas').text(data.numberAreas);
            $('.numberPendients').text(data.numberPendients);
            
            const ctx = document.getElementById('myChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Urgente', 'En espera', 'Inmediato'],
                    datasets: [
                        {
                            label: 'Pendiente',
                            data: [data.Urgente || 0, data.Espera || 0, data.Inmediato || 0],
                            backgroundColor: '#4e73df',  // Color azul claro
                            borderRadius: 15,  // Bordes redondeados para las barras
                            barThickness: 15,  // Grosor controlado de las barras
                            maxBarThickness: 20,  // Limitar el grosor máximo de las barras
                            borderSkipped: false,
                            hoverBackgroundColor: '#2e59d9',
                        },
                        {
                            label: 'Completado',
                            data: [data.UrgenteComplete || 0, data.EsperaComplete || 0, data.InmediatoComplete || 0],
                            backgroundColor: '#1cc88a',  // Color verde claro
                            borderRadius: 15,
                            barThickness: 15,
                            maxBarThickness: 20,
                            borderSkipped: false,
                            hoverBackgroundColor: '#17a673',
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1,  // Mostrar solo números enteros
                            grid: {
                                borderDash: [3, 3],  // Líneas punteadas
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;  // Solo mostrar números enteros
                                    }
                                },
                                font: {
                                    size: 14,
                                    family: "'Roboto', sans-serif"  // Fuente elegante
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false  // Elimina las líneas de la cuadrícula en el eje x
                            },
                            ticks: {
                                font: {
                                    size: 14,
                                    family: "'Roboto', sans-serif"  // Fuente elegante
                                }
                            },
                            barPercentage: 0.5,  // Espaciado entre barras dentro de una categoría
                            categoryPercentage: 0.5  // Espaciado entre categorías
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 16
                            },
                            bodyFont: {
                                size: 14
                            },
                            footerFont: {
                                size: 12
                            },
                            borderColor: 'rgba(0, 0, 0, 0.8)',
                            borderWidth: 1
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutBounce'
                    }
                }
            });
        },
    });
});
