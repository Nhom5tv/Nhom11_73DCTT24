@extends('layout')

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Danh sách điểm của sinh viên</h2>
            </div>
            
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm môn học...">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="scoreTable">
                        <thead class="thead-light">
                            <tr>
                                <th>STT</th>
                                <th>Mã môn</th>
                                <th>Tên môn</th>
                                <th>Số tín chỉ</th>
                                <th>Lần học</th>
                                <th>Điểm hệ 10</th>
                                <th>Điểm hệ 4</th>
                                <th>Điểm chữ</th>
                                <th>Đánh giá</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dữ liệu sẽ được tải bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
        const studentId = localStorage.getItem('ma_sinh_vien');
        document.addEventListener('DOMContentLoaded', function() {
           
            fetchScores(studentId);

            // Xử lý tìm kiếm
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#scoreTable tbody tr');
                
                rows.forEach(row => {
                    const subjectName = row.cells[2].textContent.toLowerCase();
                    row.style.display = subjectName.includes(searchValue) ? '' : 'none';
                });
            });
        });

        function fetchScores(studentId) {
            axios.get(`/api/sinhvien/diem/${studentId}`)
                .then(response => {
                    const scores = response.data;
                    const tableBody = document.querySelector('#scoreTable tbody');
                    tableBody.innerHTML = '';

                    scores.forEach((score, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${score.ma_mon || 'N/A'}</td>
                            <td>${score.ten_mon || 'N/A'}</td>
                            <td>${score.so_tin_chi || 'N/A'}</td>
                            <td>${score.lan_hoc || 'N/A'}</td>
                            <td>${score.diem_he_10 || 'N/A'}</td>
                            <td>${score.diem_he_4 || 'N/A'}</td>
                            <td>${score.diem_chu || 'N/A'}</td>
                            <td>${score.danh_gia || 'N/A'}</td>
                            <td>
                                <a href="/sinhvien/diem-chi-tiet?ma_sinh_vien=${studentId}&ma_lop=${score.ma_lop}" class="btn btn-sm btn-info">
                                    Xem chi tiết
                                </a>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching scores:', error);
                    alert('Có lỗi xảy ra khi tải dữ liệu điểm');
                });
        }
    </script>
@endsection