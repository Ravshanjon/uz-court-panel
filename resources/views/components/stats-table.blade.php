@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('disciplineChart', () => ({
                init() {
                    const ctx = document.getElementById('disciplinaryChart').getContext('2d');

                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, '#475569');  // yuqori: green-400
                    gradient.addColorStop(1, '#475569');  // pastki: green-600

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [
                                'ҚР', 'Андижон', 'Бухоро', 'Жиззах', 'Қашқадарё', 'Навоий', 'Наманган', 'Самарқанд',
                                'Сурхондарё', 'Сирдарё', 'Фарғона', 'Хоразм', 'Тошкент в.', 'Тошкент ш.', 'Олий суд', 'Ҳарбий суд',
                            ],
                            datasets: [{
                                label: 'Интизомий жазо қўлланганлар сони',
                                data: [6, 4, 13, 3, 17, 7, 1, 10, 5, 0, 6, 2, 5, 14, 0, 0],
                                backgroundColor: gradient,
                                borderRadius: 6,
                                borderSkipped: false,
                                barPercentage: 0.7,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#374151', // text-gray-700
                                        font: {
                                            size: 14,
                                            family: 'sans-serif'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#f9fafb',
                                    titleColor: '#111827',
                                    bodyColor: '#1f2937',
                                    borderColor: '#d1d5db',
                                    borderWidth: 1,
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: '#6b7280', // text-gray-500
                                        font: {
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        display: false,
                                    }
                                },
                                y: {
                                    ticks: {
                                        color: '#6b7280',
                                        font: {
                                            size: 12
                                        },
                                        beginAtZero: true,
                                        stepSize: 2
                                    },
                                    grid: {
                                        color: '#e5e7eb', // border-gray-200
                                    }
                                }
                            }
                        }
                    });
                }
            }));
        });
    </script>
@endpush
