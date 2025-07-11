@extends('layout')

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Chi tiết điểm</h2>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Thông tin cơ bản</h4>
                        <p><strong>Lần học:</strong> <span id="attempt"></span></p>
                    </div>
                    
                    <div class="col-md-6">
                        <h4>Thành phần điểm</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Điểm chuyên cần</th>
                                <td id="attendanceScore"></td>
                            </tr>
                            <tr>
                                <th>Điểm giữa kỳ</th>
                                <td id="midtermScore"></td>
                            </tr>
                            <tr>
                                <th>Điểm cuối kỳ</th>
                                <td id="finalScore"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="/sinhvien/diem" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- <script>
        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

        document.addEventListener('DOMContentLoaded', function() {
            const studentId = localStorage.getItem('ma_sinh_vien');
            fetchScoreDetails(studentId);
        });

        function fetchScoreDetails(studentId) {
            axios.get(`/api/sinhvien/diem-chi-tiet/${studentId}`)
                .then(response => {
                    const scoreDetail = response.data;
                    
                    // Hiển thị thông tin cơ bản
                    document.getElementById('attempt').textContent = scoreDetail.lan_hoc || 'N/A';
                    
                    // Hiển thị thành phần điểm
                    document.getElementById('attendanceScore').textContent = scoreDetail.diem_chuyen_can || 'N/A';
                    document.getElementById('midtermScore').textContent = scoreDetail.diem_giua_ky || 'N/A';
                    document.getElementById('finalScore').textContent = scoreDetail.diem_cuoi_ky || 'N/A';
                })
                .catch(error => {
                    console.error('Error fetching score details:', error);
                    alert('Có lỗi khi tải chi tiết điểm: ' + error.message);
                });
        }
    </script> --}}
    <script>
    // Gắn token vào header (nếu dùng xác thực JWT)
    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

    // Hàm lấy tham số từ URL (query string)
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ma_sinh_vien = getQueryParam('ma_sinh_vien');
        const ma_lop = getQueryParam('ma_lop');

        if (ma_sinh_vien && ma_lop) {
            fetchScoreDetails(ma_sinh_vien, ma_lop);
        } else {
            alert('Thiếu mã sinh viên hoặc mã lớp trong URL');
        }
    });

    // Hàm gọi API và hiển thị thông tin chi tiết điểm
    function fetchScoreDetails(ma_sinh_vien, ma_lop) {
        axios.get(`/api/sinhvien/diem-chi-tiet?ma_sinh_vien=${ma_sinh_vien}&ma_lop=${ma_lop}`)
            .then(response => {
                const scoreDetail = response.data;

                document.getElementById('attempt').textContent = scoreDetail.lan_hoc || '';
                document.getElementById('attendanceScore').textContent = scoreDetail.diem_chuyen_can || '';
                document.getElementById('midtermScore').textContent = scoreDetail.diem_giua_ky || '';
                document.getElementById('finalScore').textContent = scoreDetail.diem_cuoi_ky || '';
            })
            .catch(error => {
                console.error('Lỗi khi lấy chi tiết điểm:', error);
                alert('Không thể tải dữ liệu. Vui lòng thử lại.');
            });
    }
</script>
@endsection