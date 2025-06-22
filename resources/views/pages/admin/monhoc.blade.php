@extends('layout')

@section('content')
{{-- resources/views/monhocV.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý môn học</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" type="text/css" href="/css/styleDT.css?v={{ time() }}">
    <style>
        .btn_cn {
            display: flex;
            margin: 0;
        }
    </style>
</head>
<body>
    <form method="post" action="#">
        @csrf
    </form>
    <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Quản lý môn học</h1>
            <div class="input-group">
                <form action="#" method="post">
                    @csrf
                    <input type="search" placeholder="Tên môn" name="txtTKTenMon" value="">
                </form>
            </div>
            <div class="input-group">
                <form action="#" method="post">
                    @csrf
                    <input type="search" placeholder="Mã môn" name="txtTKMaMon" value="">
                </form>
            </div>
            <button style="border: none; background: transparent;" type="submit" name="btnTimkiem">
                <i class="fa fa-search"></i>
            </button>

            <div class="Insert">
                <form action="#" method="post">
                    @csrf
                    <button class="button-85" role="button">Thêm môn học</button>
                </form>
            </div>

            <div class="Upload">
                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="txtFile">
                    <button class="button-85" role="button">Upload</button>
                </form>
            </div>

            <div class="export__file">
                <label for="export-file" class="export__file-btn" title="Export File">
                    <img src="{{ asset('Public/Picture/export.png') }}" alt="" width="20">
                </label>
                <input type="checkbox" id="export-file">
                <div class="export__file-options">
                    <label>Export As &nbsp; &#10140;</label>
                    <form action="#" method="post">
                        @csrf
                        <button style="width: 176px;" name="btnXuatExcel">
                            <label for="export-file" id="toEXCEL">EXCEL</label>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Mã môn <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Tên môn <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Mã ngành <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Số tín chỉ <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Số tiết <span class="icon-arrow">&UpArrow;</span></th>
                        <th style="padding-left:50px">Chức năng <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    {{-- Dữ liệu sẽ được load bằng Axios sau --}}
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>

@endsection
