@extends('layout')

@section('title', 'Quản lý lịch học')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    {{-- <link rel="stylesheet" href="/css/layout.css?v={{ time() }}"> --}}
    <style >
        .btn_cn {
            display: flex;
            margin: 0;
        }
    </style>
<main class="table" id="customers_table">

    <section class="table__header">
        <h1>Quản lý lịch học</h1>

        <div class="input-group">
            <input type="search" id="search_ma_mon_hoc" placeholder="Mã Môn Học">
        </div>
        <div class="input-group">
            <input type="search" id="search_lich_hoc" placeholder="Lịch Học">
        </div>
        <button id="btnTimKiem" type="button" style="border: none; background: transparent;">
    <i class="fa fa-search"></i>
</button>


        <div class="Insert">
            <a href="{{ url('admin/dslichhoc/create') }}">
                <button class="button-85" role="button">Thêm Lịch Học</button>
            </a>
        </div>
        <div>
            <button class="button-85" id="btnDongTatCa" role="button">Đóng Tất Cả</button>
        </div>
    </section>
    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>ID <span class="icon-arrow">&UpArrow;</span></th>
                    <th>Mã Môn Học <span class="icon-arrow">&UpArrow;</span></th>
                    <th>Còn Lại <span class="icon-arrow">&UpArrow;</span></th>
                    <th>Số Lượng Tối Đa <span class="icon-arrow">&UpArrow;</span></th>
                    <th>Lịch Học <span class="icon-arrow">&UpArrow;</span></th>
                    <th>Trạng Thái <span class="icon-arrow">&UpArrow;</span></th>
                    <th style="padding-left:50px">TOOL <span class="icon-arrow">&UpArrow;</span></th>
                </tr>
            </thead>
            <tbody id="lichHocTableBody">
                <!-- Dữ liệu sẽ được render bằng JS -->
            </tbody>
        </table>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    async function fetchLichHoc() {
        try {
            const response = await axios.get('/api/admin/dslichhoc', {
    headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token')
    }
        });

            const data = response.data;
            const tbody = document.getElementById('lichHocTableBody');
            tbody.innerHTML = '';

        data.forEach(row => {
    const tr = document.createElement('tr');

    let html = `
        <td>${row.id_lich_hoc}</td>
        <td>${row.ma_mon_hoc}</td>
        <td>${(row.so_luong_toi_da ?? 0) - (row.so_luong ?? 0)}</td>
        <td>${row.so_luong_toi_da ?? ''}</td>
        <td>${row.lich_hoc ?? ''}</td>
        <td>${row.trang_thai ?? ''}</td>
        <td class="btn_cn">
            <a href="/admin/dslichhoc/${row.id_lich_hoc}/edit">
                <button class="button-85" role="button">Sửa</button>
            </a>
    `;

    if (row.trang_thai === 'Đang Mở') {
        html += `<button class="button-85" onclick="dongLop(${row.id_lich_hoc})" role="button">Đóng lớp</button>`;
    }

    html += `</td>`;

    tr.innerHTML = html;
    tbody.appendChild(tr);
});
        } catch (error) {
            console.error('Lỗi khi load lịch học:', error);
        }
    }

    async function dongLop(id) {
    if (!confirm('Bạn có chắc muốn đóng lớp này không?')) return;

    try {
        await axios.put(`/api/admin/dslichhoc/${id}`, {
            trang_thai: 'Đóng'
        }, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        });

        alert('Đã đóng lớp thành công.');
        fetchLichHoc(); // Cập nhật lại bảng
    } catch (error) {
        console.error('Lỗi khi đóng lớp:', error);
        alert('Đóng lớp thất bại.');
    }
}
    document.addEventListener('DOMContentLoaded', () => {
    fetchLichHoc(); // gọi hàm load danh sách mặc định

    document.getElementById('btnDongTatCa').addEventListener('click', async () => {
        if (!confirm('Bạn có chắc muốn đóng tất cả lớp đang mở không?')) return;

        try {
            const response = await axios.put('/api/admin/dslichhoc/dongtatca', {}, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            });
            alert(response.data.message);
            fetchLichHoc();
        } catch (error) {
            console.error('Lỗi khi đóng tất cả lớp:', error);
            alert('Đóng tất cả lớp thất bại.');
            console.log('Lỗi chi tiết:', error.response.data);
        }
    });
    // ✅ Thêm đoạn này vào trong DOMContentLoaded
    document.getElementById('btnTimKiem').addEventListener('click', async () => {
        token = localStorage.getItem('token');
        const maMon = document.getElementById('search_ma_mon_hoc').value.trim();
        const lichHoc = document.getElementById('search_lich_hoc').value.trim();

        let params = new URLSearchParams();
        if (maMon) params.append('ma_mon_hoc', maMon);
        if (lichHoc) params.append('lich_hoc', lichHoc);

        try {
            const response = await axios.get(`/api/admin/dslichhoc?${params.toString()}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const data = response.data;
            const tbody = document.getElementById('lichHocTableBody');
            tbody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                let html = `
                    <td>${row.id_lich_hoc}</td>
                    <td>${row.ma_mon_hoc}</td>
                    <td>${(row.so_luong_toi_da ?? 0) - (row.so_luong ?? 0)}</td>
                    <td>${row.so_luong_toi_da ?? ''}</td>
                    <td>${row.lich_hoc ?? ''}</td>
                    <td>${row.trang_thai ?? ''}</td>
                    <td class="btn_cn">
                        <a href="/admin/dslichhoc/${row.id_lich_hoc}/lichhocsua">
                            <button class="button-85" role="button">Sửa</button>
                        </a>`;
                if (row.trang_thai === 'Đang Mở') {
                    html += `<button class="button-85" onclick="dongLop(${row.id_lich_hoc})" role="button">Đóng lớp</button>`;
                }
                html += `</td>`;
                tr.innerHTML = html;
                tbody.appendChild(tr);
            });
        } catch (error) {
            console.error('Lỗi khi tìm kiếm:', error);
            alert('Không thể tìm kiếm dữ liệu.');
        }
    });
});
</script>
@endsection
