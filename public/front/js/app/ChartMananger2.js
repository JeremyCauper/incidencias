class ChartMananger {
    estadoAnteriorMap = null;
    sizeGraphic = null;

    constructor(params = {}) {
        this.id = params.id ?? null;
        this.data = params.data ?? [];
        this.config = params.config ?? {};
        this.config.order = params.config?.order ?? 'desc';

        if (!this.id) return console.error('ChartManager: ID o datos no válidos.');

        const container = document.getElementById(this.id);
        if (!container) return console.error(`ChartManager: No se encontró el contenedor con ID ${this.id}`);
        let altura = this.config?.altura || 40;
        container.style = `position: relative; height: ${altura}vh; overflow: hidden; width: 100%;`;

        this.chart = echarts.init(container, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });

        this.chart.setOption(this._buildOptionEstado());
    }

    _buildOptionEstado() {
        let option = null;
        switch (this.config.tipo) {
            case "estado":
                var total = this.data.total;
                var value = this.data.value;
                this.data.porcentaje = (value / total) * 100;

                option = {
                    series: [
                        {
                            type: 'gauge',
                            center: ['50%', '85%'],
                            startAngle: 180,
                            endAngle: 0,
                            min: 0,
                            max: 100,
                            splitNumber: 1,
                            itemStyle: {
                                color: this.config.bg
                            },
                            pointer: {
                                icon: 'path://M2090.36389,615.30999 L2090.36389,615.30999 C2091.48372,615.30999 2092.40383,616.194028 2092.44859,617.312956 L2096.90698,728.755929 C2097.05155,732.369577 2094.2393,735.416212 2090.62566,735.56078 C2090.53845,735.564269 2090.45117,735.566014 2090.36389,735.566014 L2090.36389,735.566014 C2086.74736,735.566014 2083.81557,732.63423 2083.81557,729.017692 C2083.81557,728.930412 2083.81732,728.84314 2083.82081,728.755929 L2088.2792,617.312956 C2088.32396,616.194028 2089.24407,615.30999 2090.36389,615.30999 Z',
                                length: '60%',
                                width: 3,
                                offsetCenter: [0, '5%']
                            },
                            progress: {
                                show: true,
                                roundCap: true,
                                width: 5
                            },
                            axisLine: {
                                roundCap: true,
                                lineStyle: {
                                    width: 5
                                }
                            },
                            axisTick: {
                                show: false
                            },
                            splitLine: {
                                show: false
                            },
                            axisLabel: {
                                show: false
                            },
                            anchor: {
                                show: false
                            },
                            title: {
                                show: false
                            },
                            detail: {
                                valueAnimation: true,
                                width: '50%',
                                lineHeight: 30,
                                borderRadius: 8,
                                offsetCenter: [3.5, '-150%'],
                                fontSize: 12,
                                fontWeight: 'bolder',
                                formatter: '{value} %',
                                color: 'inherit'
                            },
                            data: [
                                {
                                    value: this.data.porcentaje.toFixed(2)
                                }
                            ]
                        }
                    ]
                };
                break;

            case 'actividades':
                const buildSeries = () => {
                    const keys = Object.keys(this.data[0].series);
                    const totals = {};
                    keys.forEach(k => {
                        totals[k] = this.data.reduce((sum, item) => sum + item.series[k], 0);
                    });

                    return keys.map(key => ({
                        name: key.toUpperCase(),
                        type: "bar",
                        barGap: 0,
                        label: {
                            show: false,
                            position: "top",
                            distance: 4,
                            align: "center",
                            fontSize: 10,
                            color: "#fff",
                            formatter: (params) => {
                                const value = params.value;
                                return (value == 0) ? '' : value;
                            }
                        },
                        barMaxWidth: 30,
                        data: this.data.map(item => ({
                            value: item.series[key],
                            text: item.text,
                            data: item
                        }))
                    }));
                }

                option = {
                    // title: {
                    //     text: 'Cantidad de incidencias por fecha',
                    //     // left: 'center',
                    //     textStyle: {
                    //         color: '#9fa6b2',
                    //         fontFamily: 'Arial, sans-serif',
                    //         fontSize: 14,
                    //     }

                    // },
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        },
                        formatter: (params) => {
                            let result = `<strong style="font-size:.725rem;"><i class="${params[0].data.data.transporte}"></i> ${params[0].data.text}</strong><br>`;

                            params.forEach(item => {
                                const value = item.data.value;
                                const data = item.data.data;
                                result += `${item.marker} <span style="font-size:.7rem;">${item.seriesName}</span>: <b>${value}</b><br/>`;

                                if (item.seriesName == "INCIDENCIAS") {
                                    result += `<ul style="font-size:.7rem;">
                                        <li>N1 - REMOTO: ${data.niveles.n1}</li>
                                        <li>N2 - PRESENCIAL: ${data.niveles.n2}</li>
                                    </ul>`;
                                }
                            });
                            return result;
                        }
                    },
                    legend: {
                        show: false,
                    }
                    ,
                    grid: [
                        {
                            left: '5%',
                            right: '4%',
                            top: '5%',
                            bottom: '20%'
                        }
                    ],
                    xAxis: [
                        {
                            type: "category",
                            axisTick: {
                                show: false
                            },
                            data: this.data.map(item => item.name),
                            axisLabel: {
                                interval: 0,
                                rotate: 30,
                                textStyle: {
                                    color: "#fff",
                                    fontSize: 10.5,
                                    fontWeight: "bold"
                                }
                            }
                        }
                    ],
                    yAxis: [
                        {
                            type: "value",
                            axisLine: {
                                lineStyle: {
                                    color: "#757575"
                                }
                            },
                            splitLine: {
                                lineStyle: {
                                    color: "#757575",
                                    width: 1,
                                    type: "dotted"
                                }
                            }
                        }
                    ],
                    series: buildSeries()
                };
                break;

            case 'incidencia_fechas':
                option = {
                    title: {
                        text: 'Cantidad de incidencias por fecha',
                        // left: 'center',
                        textStyle: {
                            color: '#9fa6b2',
                            fontFamily: 'Arial, sans-serif',
                            fontSize: 14,
                        }

                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function (params) {
                            const p = params[0];
                            return `${p.axisValue} : ${p.data}`;
                        },
                        axisPointer: {
                            animation: false
                        }
                    },
                    grid: {
                        left: '10%',
                        right: '7%',
                        top: '15%',
                        bottom: '20%'
                    },
                    xAxis: {
                        type: 'category', // 👈 seguimos con category
                        data: this.data.fechas,
                        axisLabel: {
                            fontSize: 9,
                            showMaxLabel: true,
                            showMinLabel: true
                        }
                    },
                    yAxis: {
                        type: 'value',
                        min: 'dataMin',
                        splitLine: {
                            lineStyle: {
                                color: '#757575',
                                width: 1,
                                type: 'dotted'
                            }
                        }
                    },
                    dataZoom: [
                        {
                            type: 'inside'
                        },
                        {
                            type: 'slider',
                            top: '88%',
                            left: '19%',
                            right: '19%',
                            textStyle: {
                                fontSize: 10,
                            },
                        }
                    ],
                    series: [
                        {
                            type: 'line',
                            symbolSize: 0,
                            color: '#3b71ca',
                            areaStyle: {
                                color: '#3b72ca2a'
                            },
                            data: this.data.valores // 👈 solo valores
                        }
                    ]
                };
                break;

            case 'niveles':
                var total = this.data.total;
                var value = this.data.value;
                this.data.porcentaje = (value / total) * 100;

                option = {
                    tooltip: {
                        show: false
                    },
                    series: [
                        {
                            name: 'Pressure',
                            type: 'gauge',
                            startAngle: 225,
                            endAngle: -135,
                            min: 0,
                            max: 100,
                            itemStyle: {
                                color: this.config.color,
                                type: "dotted"
                            },
                            pointer: {
                                show: false
                            },
                            axisLine: {
                                lineStyle: {
                                    width: 25,
                                    color: [
                                        [1, '#0000000c'] // Dark: '#4e4e4eff' | Light: '#ebebebff'
                                    ]
                                }
                            },
                            progress: {
                                show: true,
                                width: 25,
                                roundCap: true,
                            },
                            axisTick: {
                                show: false
                            },
                            splitLine: {
                                show: false
                            },
                            axisLabel: {
                                show: false
                            },
                            detail: {
                                valueAnimation: true,
                                width: '60%',
                                offsetCenter: [0, 0],
                                fontSize: 25,
                                fontWeight: 'bolder',
                                formatter: '{value} %',
                                color: 'inherit'
                            },
                            data: [
                                {
                                    value: this.data.porcentaje.toFixed(2),
                                    // name: 'Porcentaje Unificado'
                                }
                            ]
                        }
                    ]
                };
                break;

            case 'problemas':
                var key = Object.keys(this.data[0].series);
                this.data.sort((a, b) => {
                    const aVal = a.series[key[0]];
                    const bVal = b.series[key[0]];
                    return this.config.order == 'asc' ? bVal - aVal : aVal - bVal;
                });

                option = {
                    title: {
                        text: 'Cantidad de incidencias por problemas',
                        // left: 'center',
                        textStyle: {
                            color: '#9fa6b2',
                            fontFamily: 'Arial, sans-serif',
                            fontSize: 14,
                        }

                    },
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        },
                        formatter: (params) => {
                            let result = `<strong style="font-size:.725rem;">${params[0].data.text}</strong><br>`;
                            params.forEach(item => {
                                const value = item.data.value;
                                result += `${item.marker} <span style="font-size:.7rem;">${item.seriesName}</span>: <b>${value}</b><br/>`;
                            });
                            return result;
                        }
                    },
                    legend: {
                        show: false,
                    }
                    ,
                    grid: [
                        {
                            left: '12%',
                            right: '4%',
                            top: '15%',
                            bottom: '20%'
                        }
                    ],
                    xAxis: [
                        {
                            type: "value",
                            axisLine: {
                                lineStyle: {
                                    color: "#757575"
                                }
                            },
                            splitLine: {
                                lineStyle: {
                                    color: "#757575",
                                    width: 1,
                                    type: "dotted"
                                }
                            }
                        }
                    ],
                    yAxis: [
                        {
                            type: "category",
                            axisTick: {
                                show: false
                            },
                            data: this.data.map(item => item.name),
                            axisLabel: {
                                interval: 0,
                                textStyle: {
                                    fontSize: 10.5,
                                    fontWeight: "bold"
                                }
                            }
                        }
                    ],
                    series: {
                        name: key[0].toUpperCase(),
                        type: "bar",
                        barGap: 0,
                        label: {
                            show: false,
                        },
                        barMaxWidth: 30,
                        data: this.data.map(item => ({
                            value: item.series[key[0]],
                            text: item.text,
                            itemStyle: {
                                color: item.tipo_soporte == 1 ? '#3498db' : '#7367f0' // 👈 aquí usas el color del dato
                            }
                        }))
                    }
                };
                break;

            case 'subproblemas':
                if (!this.data.length) {
                    option = {
                        title: {
                            text: 'Cantidad de incidencias por problemas',
                            textStyle: {
                                color: '#9fa6b2',
                                fontFamily: 'Arial, sans-serif',
                                fontSize: 14,
                            }
                        },
                        graphic: {
                            type: 'text',
                            left: 'center',
                            top: 'middle',
                            style: {
                                text: 'Sin datos disponibles',
                                fill: '#aaa',
                                fontSize: 16,
                                fontWeight: 'bold'
                            }
                        }
                    };
                } else {
                    var key = Object.keys(this.data[0].series);
                    this.data.sort((a, b) => {
                        const aVal = a.series[key[0]];
                        const bVal = b.series[key[0]];
                        return this.config.order == 'asc' ? bVal - aVal : aVal - bVal;
                    });

                    option = {
                        title: {
                            text: 'Cantidad de incidencias por problemas',
                            // left: 'center',
                            textStyle: {
                                color: '#9fa6b2',
                                fontFamily: 'Arial, sans-serif',
                                fontSize: 14,
                            }

                        },
                        tooltip: {
                            trigger: "axis",
                            axisPointer: {
                                type: "shadow"
                            },
                            formatter: (params) => {
                                let result = `<strong style="font-size:.725rem;">${params[0].data.text}</strong><br>`;
                                params.forEach(item => {
                                    const value = item.data.value;
                                    result += `${item.marker} <span style="font-size:.7rem;">${item.seriesName}</span>: <b>${value}</b><br/>`;
                                });
                                return result;
                            }
                        },
                        legend: {
                            show: false,
                        }
                        ,
                        grid: [
                            {
                                left: '25%',
                                right: '4%',
                                top: '15%',
                                bottom: '20%'
                            }
                        ],
                        xAxis: [
                            {
                                type: "value",
                                axisLine: {
                                    lineStyle: {
                                        color: "#757575"
                                    }
                                },
                                splitLine: {
                                    lineStyle: {
                                        color: "#757575",
                                        width: 1,
                                        type: "dotted"
                                    }
                                }
                            }
                        ],
                        yAxis: [
                            {
                                type: "category",
                                axisTick: {
                                    show: false
                                },
                                data: this.data.map(item => item.name),
                                axisLabel: {
                                    interval: 0,
                                    textStyle: {
                                        fontSize: 10.5,
                                        fontWeight: "bold"
                                    }
                                }
                            }
                        ],
                        series: {
                            name: key[0].toUpperCase(),
                            type: "bar",
                            barGap: 0,
                            label: {
                                show: false,
                            },
                            barMaxWidth: 30,
                            data: this.data.map(item => ({
                                value: item.series[key[0]],
                                text: item.text,
                                itemStyle: {
                                    color: '#e4a11b' // 👈 aquí usas el color del dato
                                }
                            }))
                        }
                    };
                }
                break;
        }

        return option;
    }

    updateOption(params = {}) {
        const deepMerge = (target, source) => {
            for (let key in source) {
                if (source[key] instanceof Object && key in target) {
                    deepMerge(target[key], source[key]);
                } else {
                    target[key] = source[key];
                }
            }
            return target;
        };
        deepMerge(this, params);

        if (this.chart) {
            this.chart.setOption(this._buildOptionEstado(), true);
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
            this.chart.setOption(this._buildOptionEstado(), true);
            this.estadoAnteriorMap = true;
        } else if (ancho >= this.sizeGraphic && estadoAnterior !== false) {
            this.chart.setOption(this._buildOptionEstado(), true);
            this.estadoAnteriorMap = false;
        }
    }
}