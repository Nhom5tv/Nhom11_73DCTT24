<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hồ sơ sinh viên</title>

    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/layout.css?v={{ time() }}">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="fa-solid fa-school"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">University of Transport and Technology</a>
                </div>
            </div>

            <ul class="sidebar-nav">
                <!-- Mục chung -->
                <li class="sidebar-item">
                    <a href="/trangchu" class="sidebar-link">
                        <i class="fa-solid fa-house"></i><span>Trang chủ</span>
                    </a>
                </li>

                <!-- Admin only -->
                <li class="sidebar-item" data-role="admin">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#QLTK">
                        <i class="fa-solid fa-user"></i><span>Quản lý hệ thống</span>
                    </a>
                    <ul id="QLTK" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a href="admin/taikhoan" class="sidebar-link">Quản lý tài khoản</a></li>
                        <li class="sidebar-item"><a href="/admin/giaovien" class="sidebar-link">Quản lý giảng viên</a></li>
                        <li class="sidebar-item"><a href="admin/sinhvien" class="sidebar-link">Quản lý sinh viên</a></li>
                        <li class="sidebar-item"><a href="admin/khoa" class="sidebar-link">Quản lý khoa</a></li>
                        <li class="sidebar-item"><a href="/DSNganh" class="sidebar-link">Quản lý ngành</a></li>
                    </ul>
                </li>

                <!-- Admin only -->
                <li class="sidebar-item" data-role="admin">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#QLTC">
                        <i class="fa-solid fa-coins"></i><span>Quản lý tài chính</span>
                    </a>
                    <ul id="QLTC" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a href="/admin/khoanthu" class="sidebar-link">Khoản thu chung</a></li>
                        <li class="sidebar-item"><a href="/DSKhoanthusv" class="sidebar-link">Khoản thu sinh viên</a></li>
                        <li class="sidebar-item"><a href="/admin/miengiam" class="sidebar-link">Miễn giảm</a></li>
                        <li class="sidebar-item"><a href="/DSHoadon" class="sidebar-link">Hóa đơn</a></li>
                    </ul>
                </li>

                <li class="sidebar-item" data-role="admin">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#QLHT">
                        <i class="fa-solid fa-coins"></i><span>Hệ thống đăng ký tín chỉ</span>
                    </a>
                    <ul id="QLHT" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a href="/dsdkmonhoc" class="sidebar-link">Danh sách đăng ký môn học</a></li>
                        <li class="sidebar-item"><a href="/admin/dslichhoc" class="sidebar-link">Quản lý lịch học</a></li>
                        <li class="sidebar-item"><a href="/admin/dslophoc" class="sidebar-link">Quản lý lớp học</a></li>
                        <li class="sidebar-item"><a href="/dsmonhoc" class="sidebar-link">Quản lý môn học</a></li>
                    </ul>
                </li>

                <!-- Giáo viên only -->
                <li class="sidebar-item" data-role="giaovien">
                    <a href="/giaovien/DSdiemgv" class="sidebar-link">
                        <i class="fa-solid fa-medal"></i><span>Quản lý điểm sinh viên</span>
                    </a>
                </li>

                <!-- Sinh viên only -->
                <li class="sidebar-item" data-role="sinhvien">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#SVTK">
                        <i class="fa-solid fa-user"></i><span>Tài khoản</span>
                    </a>
                    <ul id="SVTK" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a href="/ThongTinSinhVien" class="sidebar-link">Profile</a></li>
                    </ul>
                </li>

                <li class="sidebar-item" data-role="sinhvien">
                    <a href="/DSdiem" class="sidebar-link">
                        <i class="fa-solid fa-medal"></i><span>Kết quả học tập</span>
                    </a>
                </li>
                <li class="sidebar-item" data-role="sinhvien">
                    <a href="/Taichinh" class="sidebar-link">
                        <i class="fa-solid fa-coins"></i><span>Tài chính và học phí</span>
                    </a>
                </li>
                <li class="sidebar-item" data-role="sinhvien">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#SVTC">
                        <i class="fas fa-graduation-cap"></i><span>Đăng ký tín chỉ</span>
                    </a>
                    <ul id="SVTC" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a href="/dktinchi" class="sidebar-link">Đăng ký tín chỉ</a></li>
                    </ul>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="#" onclick="logout()" class="sidebar-link">
                    <i class="fa-solid fa-person-walking-arrow-right"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>

        </aside>

        <div class="main p-3">
            <div class="title"></div>
            <div class="main-content">
                <div class="main-content">
                    @yield(section: 'content')
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.querySelector(".toggle-btn").addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("expand");
        });

        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        } else {
            fetch('/api/me', {
                headers: { 'Authorization': 'Bearer ' + token }
            })
            .then(res => res.json())
            .then(user => {
                const role = user.role;
                document.querySelectorAll('[data-role]').forEach(item => {
                    item.style.display = (item.getAttribute('data-role') === role) ? 'block' : 'none';
                });
            })
            .catch(() => {
                localStorage.removeItem('token');
                window.location.href = '/login';
            });
        }
    </script>
    <script>
        function logout() {
            localStorage.removeItem('token'); // Xoá token
            window.location.href = '/login';  // Chuyển về trang login
        }
    </script>

</body>
</html>
