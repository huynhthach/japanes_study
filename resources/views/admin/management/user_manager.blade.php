<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        margin-right: 5px;
        text-decoration: none;
        background-color: #3498db;
        color: #fff;
        border-radius: 4px;
    }

    .btn-danger {
        background-color: #e74c3c;
    }

</style>
@extends('admin.layout.app')
@section('content')

<body>
    <div class="container">
        <h1>Quản lý người dùng</h1>
        <form action="{{ route('users.index') }}" method="GET" class="form-group">
            <input type="text" id="searchInput" name="keyword" placeholder="Tìm kiếm theo tên...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->Role == 2 ? 'Người Dùng' : 'Admin' }}</td>
                    <td>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $user->id }}">Delete</button>
                    </td>
                </tr>
                <!-- Modal xác nhận xoá -->
                <div class="modal fade" id="confirmDeleteModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Xác nhận xoá</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc chắn muốn xoá người dùng này không?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xoá</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Huỷ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
@endsection