@extends('admin.layout.app')

@section('content')
<div class="container">
    <h1>Danh sách Comment</h1>

    <!-- Form tìm kiếm -->
    <form action="{{ route('comment.index') }}" method="GET" class="form-inline mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="keyword" placeholder="Tìm kiếm bình luận...">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Mã bình luận</th>
                <th>Tên người dùng</th>
                <th>Bài viết được bình luận</th>
                <th>Nội dung</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
            <tr>
                <td>{{ $comment->Comment_ID }}</td>
                <td>{{ $comment->users->name }}</td>
                <td>{{ $comment->news->title }}</td>
                <td>{{ $comment->Content }}</td>
                <td>{{ $comment->Created_at }}</td>
                <td>
                <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $comment->Comment_ID }}">Delete</button>
                </td>
            </tr>

            <div class="modal fade" id="confirmDeleteModal{{ $comment->Comment_ID }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $comment->Comment_ID }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Xác nhận xoá</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc chắn muốn xoá bình luận này không?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('comment.destroy', $comment->Comment_ID) }}" method="POST">
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
@endsection
