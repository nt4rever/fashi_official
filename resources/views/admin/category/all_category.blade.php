@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh mục sản phẩm</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        <form action="{{ URL::to('/search-category') }}" method="POST">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                @csrf
                                <input type="text" class="form-control float-right" placeholder="Search" name="keyword"
                                    required>

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Ảnh</th>
                                <th>Danh mục cha</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <style>
                            #category_order .ui-state-highlight {
                                padding: 24px;
                                background-color: #ffffcc;
                                border: 1px dotted #ccc;
                                cursor: move;
                                margin-top: 12px;
                            }
                        </style>
                        <tbody id="category_order">
                            {{-- @if (count($all_category) > 0) --}}
                            @isset($all_category)
                            @foreach ($all_category as $item => $value )
                            <tr id="{{ $value ->category_id }}">
                                <td>{{ $value ->category_id }}</td>
                                <td><a href="{{ URL::to('/list-category-detail/'.$value ->category_id) }}"
                                        target="_blank">{{ $value ->category_name }}</a></td>
                                <td>{!! $value ->category_desc !!}</td>
                                <td>
                                    @php
                                    if ($value ->category_status==0){
                                    @endphp
                                    <a href="{{ URL::to('/inactive-category/'.$value ->category_id) }}"
                                        title="Click để ẩn">Hiển thị</a>
                                    @php
                                    }
                                    else {
                                    @endphp
                                    <a href="{{ URL::to('/active-category/'.$value ->category_id) }}"
                                        title="Click để hiển thị">Ẩn</a>
                                    @php
                                    }
                                    @endphp
                                </td>
                                @if ($value->category_image)
                                <td><img src="{{ URL::asset('/uploads/category/'.$value->category_image) }}" alt=""
                                        style="width: 100px;"></td>
                                @else
                                <td>null</td>
                                @endif

                                <td>{{ $value->category_parentId }}</td>
                                @hasrole(['admin','author'])
                                <td>
                                    <button type="button" class="btn btn-outline-warning" name="delete_category"
                                        data-categoryid="{{ $value->category_id }}" title="Xoá">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    &nbsp;
                                    <a href="{{ URL::to('/edit-category/'.$value ->category_id) }}" title="Sửa"><button
                                            type="button" class="btn btn-outline-info"><i
                                                class="fas fa-edit"></i></button></a>
                                </td>
                                @endhasrole
                            </tr>
                            @endforeach
                            @endisset
                            {{-- @endif --}}
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                @hasrole(['admin','author'])
                <a href="{{ URL::to('/add-category') }}"><button class="btn btn-outline-info">Thêm danh mục</button></a>
                @endhasrole
                {!! $all_category->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>
@push('custom-scripts-ajax')
<script src="{{ URL::asset('/backend/js/all_category.js') }}"></script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.category').addClass('active');
            
        });
</script>
@endpush
@endsection