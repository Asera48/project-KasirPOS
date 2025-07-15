<div 
    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg h-full"
    x-data="salesChart()"
    x-init="initChart()"
>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Pendapatan 7 Hari Terakhir</h3>
        <div x-show="loading" style="display: none;">
            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
    </div>
    
    {{-- Elemen <canvas> untuk merender grafik --}}
    <div class="h-64">
        <canvas x-ref="chartCanvas"></canvas>
    </div>
</div>

@push('scripts')
<script>
    function salesChart() {
        return {
            loading: true,
            chart: null,
            initChart() {
                fetch('{{ route("api.reports.sales-chart") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.renderChart(data);
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                    this.loading = false;
                });
            },
            renderChart(apiData) {
                const ctx = this.$refs.chartCanvas.getContext('2d');
                const isDarkMode = document.documentElement.classList.contains('dark');
                
                // Opsi styling untuk dark mode
                const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const textColor = isDarkMode ? '#cbd5e1' : '#6b7280';

                if (this.chart) {
                    this.chart.destroy();
                }

                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: apiData.labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: apiData.data,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    callback: function(value, index, values) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            x: {
                                ticks: {
                                    color: textColor
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }
</script>
@endpush
