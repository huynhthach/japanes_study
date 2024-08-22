<style>
    .sort {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 auto; /* Để canh giữa .sort */
    }

    .sort .form-group {
        margin-bottom: 0; /* Loại bỏ margin dư thừa */
    }

    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 5px 10px;
        margin-right: 5px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
    }

    .pagination a.active {
        background-color: #007bff;
        color: #fff;
    }
</style>

@extends('admin.layout.app')

@section('content')
    <h1>Quản lý các bài viết</h1>

    <div class="sort">
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addExamModal">Thêm bộ bài viết</a>
        <form action="{{ route('topic.index') }}" method="GET" class="form-inline">
            <div class="input-group">
                <input type="text" id="searchInput" name="search_name" class="form-control" placeholder="Tìm kiếm theo tên...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>
    

    @if ($topics->isEmpty())
        <p>Hiện tại chưa có bài viết nào.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topics as $topic)
                    <tr>
                        <td>{{ $topic->id }}</td>
                        <td>{{ $topic->name }}</td>
                        <td>{{ $topic->category == 1 ? 'Post' : 'Vocab' }}</td>
                        <td>
                            @foreach($topic->post as $post)
                                {{ $post->title }}<br>
                            @endforeach
                        </td>
                        <td>
                        @foreach($topic->post as $post)
                            <div class="description" data-id="{{ $post->id }}">
                                {{ Str::limit($post->description, 100) }} <!-- Hiển thị tối đa 100 ký tự -->
                            </div>
                        @endforeach
                        </td>
                        <td>{{ $topic->created_at }}</td>
                        <td>
                            <a href="{{ route('topic.edit', $topic->id) }}" class="btn btn-warning">Edit</a>
                            <!-- Button to trigger modal -->
                            <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $topic->id }}">Delete</button>
                        </td>
                    </tr>
                    
                    <!-- Modal xác nhận xoá -->
                    <div class="modal fade" id="confirmDeleteModal{{ $topic->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $topic->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Xác nhận xoá</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc chắn muốn xoá bài viết này không?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('topic.destroy', $topic->id) }}" method="POST">
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
        @if($totalPages > 1)
            <div class="pagination">
                @for ($i = 1; $i <= $totalPages; $i++)
                    <a href="{{ route('topic.index', ['page' => $i]) }}" class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
            </div>
        @endif
    @endif

    <!-- Modal thêm bài viết -->
    <div class="modal fade" id="addExamModal" tabindex="-1" role="dialog" aria-labelledby="addExamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExamModalLabel">Thêm bộ đề mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addExamForm" action="{{ route('post_create') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="post_title">Tên bộ đề</label>
                            <input type="text" class="form-control" id="post_title" name="post_title" required>
                        </div>
                        <div class="form-group">
                            <label for="post_level">Loại bài đăng:</label>
                            <select name="post_level" id="post_level" class="form-control" required>
                                <option value="1">Tips/Fact</option>
                                <option value="2">Từ vựng</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="level">Cấp độ:</label>
                            <select name="level" id="level" class="form-control" required>
                                <option value="N5">N5</option>
                                <option value="N4">N4</option>
                                <option value="N3">N3</option>
                                <option value="N2">N2</option>
                                <option value="N1">N1</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tạo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    

@endsection
