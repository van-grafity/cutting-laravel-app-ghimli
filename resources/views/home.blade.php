@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <h1 class="chart-title">Status Layer</h1>
                                    </div>
                                </div>
                                <div class="small-box mb-3 bg-success">
                                    <div class="inner">
                                        <h3>{{ $countStatusLayerCompleted }}</h3>
                                        <p>Completed</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                                <div class="small-box mb-3 bg-warning">
                                    <div class="inner">
                                        <h3>{{ $countStatusLayerOverLayer }}</h3>
                                        <p>Over Layer</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                                <div class="small-box mb-3 bg-danger">
                                    <div class="inner">
                                        <h3>{{ $countStatusLayerNotCompleted }}</h3>
                                        <p>Not Completed</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <h1 class="chart-title">Status Cut</h1>
                                </div>

                                <div class="small-box mb-3 bg-success">
                                    <div class="inner">
                                        <h3>{{ $countStatusCutCompleted }}</h3>
                                        <p>Completed</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                                <div class="small-box mb-3 bg-danger">
                                    <div class="inner">
                                        <h3>{{ $countStatusCutNotCompleted }}</h3>
                                        <p>Not Completed</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h1 class="chart-title">Monthly Cutting Order</h1>
                                <div class="chart-container-pie">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <h1 class="chart-title">Monthly Cutting Order</h1>
                        <div class="chart-container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chart-container-pie {
            margin: auto;
            width: 100%;
            height: 90%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .chart-title {
            text-align: center;
        }
        .chart-container {
            margin: auto;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
@endsection

@push('js')
<script type="text/javascript">
    var labels =  {{ Js::from($labels) }};
    var users =  {{ Js::from($data) }};
  
    const data = {
        labels: labels,
        datasets: [{
            label: 'Cutting Order',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: users,
        }]
    };
  
    const config = {
        type: 'line',
        data: data,
        options: {}
    };

    Chart.defaults.font.size = 16;
    Chart.defaults.font.weight = 'bold';
  
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );

    var labelsPie =  {{ Js::from($labels) }};
    var usersPie =  {{ Js::from($data) }};

    const dataPie = {
        labels: labelsPie,
        datasets: [{
            label: 'Cutting Order',
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
            ],
            data: usersPie,
            hoverOffset: 4

        }]
    };

    const configPie = {
        type: 'pie',
        data: dataPie,
        options: {}
    };

    const myPieChart = new Chart(
        document.getElementById('myPieChart'),
        configPie
    );
</script>
@endpush