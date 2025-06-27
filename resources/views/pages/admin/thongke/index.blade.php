@extends('layout')

@section('content')
<div class="container" style="max-width: 900px; margin: auto; padding-top: 20px;">
    <h2 style="text-align: center;">📊 Thống kê học lực sinh viên & loại miễn giảm</h2>

    <div style="display: flex; flex-wrap: wrap; justify-content: space-around; margin-top: 40px;">
        <div style="width: 400px;">
            <h4 style="text-align: center;">🎓 Phân loại học lực</h4>
            <canvas id="chartHocLuc"></canvas>
        </div>
        <div style="width: 400px;">
            <h4 style="text-align: center;">💰 Loại miễn giảm</h4>
            <canvas id="chartMienGiam"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Axios CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Vẽ biểu đồ học lực
    axios.get('/api/admin/thongke/diem',{
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            const labels = res.data.map(item => item.hoc_luc);
            const data = res.data.map(item => item.so_luong);

            new Chart(document.getElementById('chartHocLuc'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#4caf50', '#2196f3', '#ffc107', '#ff9800', '#f44336'],
                    }]
                }
            });
        });

    // Vẽ biểu đồ miễn giảm
    axios.get('/api/admin/thongke/miengiam',{
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            const labels = res.data.map(item => item.loai_mien_giam);
            const data = res.data.map(item => item.so_luong);

            new Chart(document.getElementById('chartMienGiam'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#03a9f4', '#8bc34a', '#ffeb3b', '#e91e63', '#9c27b0', '#ff5722'],
                    }]
                }
            });
        });
</script>
@endsection