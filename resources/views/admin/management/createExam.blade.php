@extends('admin.layout.app')

@section('content')
<h1>Quản lý các bộ đề</h1>

<div class="sort">
    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addExamModal">Thêm bộ đề</a>
    <form action="{{ route('exams.index') }}" method="GET">
        <div class="form-row">
            <div class="form-group col-md-5">
                <label for="year">Lọc theo năm:</label>
                <select class="form-control" id="year" name="year">
                    <option value="">Chọn năm</option>
                    @for ($i = date('Y'); $i >= 2000; $i--)
                        <option value="{{ $i }}" {{ request()->input('year') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </div>
    </form>
</div>


@if ($exams->isEmpty())
<p>Hiện tại chưa có bộ đề nào.</p>
@else
<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Tên bộ đề</th>
            <th scope="col">Cấp độ</th>
            <th scope="col">Năm</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($exams as $exam)
        <tr>
            <th scope="row">{{ $exam->id }}</th>
            <td>{{ $exam->title }}</td>
            <td>{{ $exam->level }}</td>
            <td>{{ $exam->year }}</td>
            <td>
                <a href="{{ route('exams.show',$exam->id) }}" class="btn btn-info btn-sm">Xem</a>
                <a href="{{ route('exams.edit',$exam->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal{{ $exam->id }}">Xóa</button>
                <!-- Modal xác nhận xoá -->
                <div class="modal fade" id="confirmDeleteModal{{ $exam->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $exam->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmDeleteModalLabel{{ $exam->id }}">Xác nhận xoá</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Bạn có chắc chắn muốn xoá bộ đề này không?
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('exams.destroy', $exam->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xoá</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Huỷ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    @if($totalPages > 1)
        <div class="pagination">
            @for ($i = 1; $i <= $totalPages; $i++)
                <a href="{{ route('exams.index', ['page' => $i]) }}" class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
            @endfor
        </div>
    @endif
@endif

<!-- Modal thêm bộ đề -->
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
                <form id="addExamForm" action="{{ route('create_exams') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exam_title">Tên bộ đề</label>
                        <input type="text" class="form-control" id="exam_title" name="exam_title" required>
                    </div>
                    <div class="form-group">
                        <label for="exam_level">Cấp độ:</label>
                        <select name="exam_level" id="exam_level" class="form-control" required>
                            <option value="N5">N5</option>
                            <option value="N4">N4</option>
                            <option value="N3">N3</option>
                            <option value="N2">N2</option>
                            <option value="N1">N1</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="num_questions">Số lượng câu hỏi</label>
                        <input type="number" class="form-control" id="num_questions" name="num_questions" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tạo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script để điều khiển modal -->
<script>
    $(document).ready(function() {
        // Xác nhận khi click nút xoá
        $('.btn-danger').click(function() {
            var id = $(this).data('id');
            $('#confirmDeleteModal' + id).modal('show');
        });
    });
</script>

@endsection
