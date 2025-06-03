class ChartMananger {
    estadoAnteriorMap = null;
    sizeGraphic = null;

    constructor(params = {}) {
        this.id = params.id ?? null;
        this.title = params.title ?? null;
        this.name = params.name ?? null;
        this.type = params.type ?? 'pie';
        this.data = params.data ?? [];
        this.config = params.config ?? null;

        if (this.type === 'bar' && !this.config) {
            this.config = {
                xAxis: 'category',
                yAxis: 'value'
            };
        }

        if (!this.id) {
            console.error('ChartManager: ID o datos no válidos.');
            return;
        }

        if (!this.data.length) {
            const valor = () => this.type === 'bar' ? { series: { sin_datos: 0 } } : { value: 0 }
            this.data = [{
                name: 'Sin Datos',
                ...valor()
            }];
        }

        const container = $(this.id).get(0);
        if (!container) {
            console.error(`ChartManager: No se encontró el contenedor con ID ${this.id}`);
            return;
        }

        this.chart = echarts.init(container, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });

        this.chart.setOption(this._buildOption());
    }

    _buildOption() {
        const getColor = (cssVar) =>
            getComputedStyle(document.documentElement).getPropertyValue(cssVar).trim();

        const buildTitle = () => this.title ? {
            title: {
                text: this.title,
                left: 'center',
                textStyle: {
                    color: '#999',
                    fontWeight: 'bold',
                    fontSize: 14
                }
            }
        } : {};

        const buildTooltip = () => {
            const totals = {};
            if (this.type == 'bar') {
                const keys = Object.keys(this.data[0].series);
                
                keys.forEach(k => {
                    totals[k] = this.data.reduce((sum, item) => sum + item.series[k], 0);
                });
            }
            const trigger = this.type === 'bar' ? 'axis' : 'item';
            const barTooltip = this.type === 'bar' ? {
                axisPointer: { type: 'shadow' },
                formatter: (params) => {
                    let result = `<strong style="font-size:.725rem;">${params[0].data.text}</strong><br>`;

                    params.forEach(item => {
                        const value = item.data.value;
                        const total = totals[item.seriesName.toLowerCase()];
                        const percent = total > 0 ? ((value / total) * 100) : 0;

                        result += `${item.marker} <span style="font-size:.7rem;">${item.seriesName}</span>: <b>${value} (${percent.toFixed(1)}%)</b><br/>`;
                    });
                    return result;
                }
            } : {};

            return { tooltip: { trigger, ...barTooltip } };
        };

        const buildLegend = () => {
            let legend = {};

            if (this.type === 'bar') {
                legend.data = Object.keys(this.data[0].series).map(k => k.toUpperCase());
            } else if (this.type === 'pie') {
                legend.selected = {};
                this.data.forEach(item => {
                    legend.selected[item.name] = item.value !== 0;
                });
            }

            return {
                legend: {
                    top: '6%',
                    ...legend,
                    textStyle: {
                        color: getColor('--mdb-surface-color'),
                        fontSize: 11,
                        fontFamily: 'Arial'
                    },
                    itemWidth: 14,
                    itemHeight: 14
                }
            };
        };

        const buildBarAxisConfig = (axis) => {
            return axis === 'value' ? {
                axisLine: {
                    lineStyle: { color: '#757575' }
                },
                splitLine: {
                    lineStyle: {
                        color: '#757575',
                        width: 1,
                        type: 'dotted'
                    }
                }
            } : {
                axisTick: { show: false },
                data: this.data.map(item => item.name),
                axisLabel: {
                    interval: 0,
                    rotate: this.config?.xAxis === 'category' ? 30 : 0,
                    textStyle: {
                        color: getColor('--mdb-surface-color'),
                        fontSize: 10.5,
                        fontWeight: 'bold'
                    }
                }
            };
        };

        const buildGrid = () => ({
            grid: [{
                top: this.config?.xAxis === 'value' ? '20%' : '35%',
                bottom: '10%',
                left: '5%',
                right: this.config?.xAxis === 'value' ? '18%' : '5%',
                containLabel: true
            }],
            xAxis: [{
                type: this.config?.xAxis,
                ...buildBarAxisConfig(this.config?.xAxis)
            }],
            yAxis: [{
                type: this.config?.yAxis,
                ...buildBarAxisConfig(this.config?.yAxis)
            }]
        });

        const buildSeries = () => {
            if (this.type === 'bar') {
                const keys = Object.keys(this.data[0].series);
                const totals = {};
                keys.forEach(k => {
                    totals[k] = this.data.reduce((sum, item) => sum + item.series[k], 0);
                });

                return keys.map(key => ({
                    name: key.toUpperCase(),
                    type: 'bar',
                    barGap: 0,
                    label: {
                        show: true,
                        position: this.config?.xAxis === 'value' ? 'right' : 'top',
                        distance: 4,
                        ...(this.config?.xAxis === 'value' ? {} : {
                            align: 'left',
                            verticalAlign: 'middle',
                            rotate: 90,
                        }),
                        formatter: (params) => {
                            const value = params.value;
                            const total = totals[key];
                            const percent = ((value / total) * 100).toFixed(1);

                            return (value == 0) ? '' : `${value} (${percent}%)`;
                        },
                        fontSize: 10,
                        color: getColor('--mdb-surface-color')
                    },
                    emphasis: { focus: 'series' },
                    data: this.data.map(item => ({
                        value: item.series[key],
                        text: item.text
                    }))
                }));
            }

            if (this.type === 'pie') {
                return [{
                    name: this.name,
                    type: 'pie',
                    top: '15%',
                    left: 'center',
                    width: this.chart?.getWidth() < this.sizeGraphic ? '100%' : '60%',
                    radius: this.chart?.getWidth() < this.sizeGraphic ? ['25%', '45%'] : ['35%', '60%'],
                    avoidLabelOverlap: true,
                    itemStyle: { borderRadius: 2 },
                    label: {
                        alignTo: 'edge',
                        formatter: '{name|{b}}\n{time|{c} ({d}%)}',
                        minMargin: 5,
                        edgeDistance: 10,
                        lineHeight: 15,
                        color: getColor('--mdb-surface-color'),
                        rich: {
                            time: {
                                fontSize: 10,
                                color: '#999'
                            }
                        },
                        position: 'outside'
                    },
                    labelLine: {
                        length: 40,
                        length2: 15,
                        smooth: true,
                        maxSurfaceAngle: 80
                    },
                    labelLayout: (params) => {
                        const isLeft = params.labelRect.x < this.chart.getWidth() / 2;
                        const points = params.labelLinePoints;
                        points[2][0] = isLeft
                            ? params.labelRect.x
                            : params.labelRect.x + params.labelRect.width;
                        return { labelLinePoints: points };
                    },
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    data: this.data
                }];
            }

            return [];
        };

        return {
            ...buildTitle(),
            ...buildTooltip(),
            ...buildLegend(),
            ...(this.type === 'bar' ? buildGrid() : {}),
            series: buildSeries()
        };
    }

    updateOption(data = this.data) {
        this.data = data;
        if (!this.data.length) {
            const valor = () => this.type === 'bar' ? { series: { sin_datos: 0 } } : { value: 0 }
            this.data = [{
                name: 'Sin Datos',
                ...valor()
            }];
        }

        if (this.chart) {
            this.chart.setOption(this._buildOption(), true);
        }
    }

    resize() {
        this.chart.resize();
    }

    resizeGraphic(size) {
        this.sizeGraphic = size;
        const ancho = this.chart.getWidth();
        const estadoAnterior = this.estadoAnteriorMap;

        if (estadoAnterior === undefined) return;

        if (ancho < this.sizeGraphic && estadoAnterior !== true) {
            this.chart.setOption(this._buildOption(), true);
            this.estadoAnteriorMap = true;
        } else if (ancho >= this.sizeGraphic && estadoAnterior !== false) {
            this.chart.setOption(this._buildOption(), true);
            this.estadoAnteriorMap = false;
        }
    }
}