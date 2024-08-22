@extends('admin.layout.app')

@section('content')
<div class="container mt-4">
    <h1 style="text-align: center;">Chào mừng bạn đến admin</h1>

    <div>
        <canvas id="registrationChart" width="400" height="200"></canvas>
    </div>

    <div>
        <canvas id="newsChart" width="400" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('registrationChart').getContext('2d');
        var registrationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Số lượng đăng ký',
                    data: {!! json_encode($data) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
