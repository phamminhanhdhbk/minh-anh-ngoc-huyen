@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Menu: ' . $menu->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Menu: {{ $menu->name }}</h1>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Menu Settings -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cài đặt Menu</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Tên Menu <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $menu->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $menu->slug) }}" 
                                   required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Vị trí</label>
                            <select class="form-control @error('location') is-invalid @enderror" 
                                    id="location" 
                                    name="location">
                                <option value="">-- Chọn vị trí --</option>
                                <option value="header" {{ old('location', $menu->location) == 'header' ? 'selected' : '' }}>Header</option>
                                <option value="footer" {{ old('location', $menu->location) == 'footer' ? 'selected' : '' }}>Footer</option>
                                <option value="sidebar" {{ old('location', $menu->location) == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                <option value="mobile" {{ old('location', $menu->location) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $menu->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="order">Thứ tự</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', $menu->order) }}" 
                                   min="0">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Hiển thị menu
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Cập nhật Menu
                        </button>
                    </form>
                </div>
            </div>

            <!-- Add New Menu Item -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Thêm Mục Menu Mới</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.items.store', $menu->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Menu cha</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">-- Không có (Menu gốc) --</option>
                                @foreach($menu->allItems as $item)
                                    <option value="{{ $item->id }}">
                                        {{ str_repeat('—', substr_count($item->parent_id ? $item->parent->title : '', '—')) }} 
                                        {{ $item->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Chọn menu cha nếu đây là submenu</small>
                        </div>

                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com">
                            <small class="form-text text-muted">Hoặc sử dụng Route name bên dưới</small>
                        </div>

                        <div class="form-group">
                            <label for="route">Route Name</label>
                            <input type="text" class="form-control" id="route" name="route" placeholder="home">
                            <small class="form-text text-muted">Tên route trong Laravel (vd: home, products.index)</small>
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon (Font Awesome)</label>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="fas fa-home">
                            <small class="form-text text-muted">Vd: fas fa-home, fas fa-shopping-cart</small>
                        </div>

                        <div class="form-group">
                            <label for="target">Mở link</label>
                            <select class="form-control" id="target" name="target">
                                <option value="_self">Cùng tab (_self)</option>
                                <option value="_blank">Tab mới (_blank)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="css_class">CSS Class</label>
                            <input type="text" class="form-control" id="css_class" name="css_class">
                        </div>

                        <div class="form-group">
                            <label for="item_order">Thứ tự</label>
                            <input type="number" class="form-control" id="item_order" name="order" value="0" min="0">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="item_is_active" 
                                       name="is_active" 
                                       value="1" 
                                       checked>
                                <label class="custom-control-label" for="item_is_active">
                                    Hiển thị
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Thêm Mục Menu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Menu Items List -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Mục Menu</h6>
                </div>
                <div class="card-body">
                    @if($menu->allItems->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Tip:</strong> Bạn có thể sắp xếp lại thứ tự menu bằng cách kéo thả hoặc chỉnh sửa số thứ tự.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="25%">Tiêu đề</th>
                                        <th width="20%">URL/Route</th>
                                        <th width="10%">Icon</th>
                                        <th width="10%">Thứ tự</th>
                                        <th width="10%">Trạng thái</th>
                                        <th width="20%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menu->allItems->whereNull('parent_id') as $item)
                                        @include('admin.menus.partials.menu-item-row', ['item' => $item, 'level' => 0])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có mục menu nào. Thêm mục menu đầu tiên ở form bên trái.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa Mục Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_item_id" name="item_id">

                    <div class="form-group">
                        <label for="edit_title">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_parent_id">Menu cha</label>
                        <select class="form-control" id="edit_parent_id" name="parent_id">
                            <option value="">-- Không có (Menu gốc) --</option>
                            @foreach($menu->allItems as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_url">URL</label>
                        <input type="text" class="form-control" id="edit_url" name="url">
                    </div>

                    <div class="form-group">
                        <label for="edit_route">Route Name</label>
                        <input type="text" class="form-control" id="edit_route" name="route">
                    </div>

                    <div class="form-group">
                        <label for="edit_icon">Icon</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon">
                    </div>

                    <div class="form-group">
                        <label for="edit_target">Mở link</label>
                        <select class="form-control" id="edit_target" name="target">
                            <option value="_self">Cùng tab (_self)</option>
                            <option value="_blank">Tab mới (_blank)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_css_class">CSS Class</label>
                        <input type="text" class="form-control" id="edit_css_class" name="css_class">
                    </div>

                    <div class="form-group">
                        <label for="edit_order">Thứ tự</label>
                        <input type="number" class="form-control" id="edit_order" name="order" min="0">
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="edit_is_active" 
                                   name="is_active" 
                                   value="1">
                            <label class="custom-control-label" for="edit_is_active">
                                Hiển thị
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editMenuItem(itemId) {
    // Fetch item data via AJAX or use data attributes
    fetch(`{{ route('admin.menus.edit', $menu->id) }}?item_id=${itemId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_item_id').value = data.id;
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_parent_id').value = data.parent_id || '';
            document.getElementById('edit_url').value = data.url || '';
            document.getElementById('edit_route').value = data.route || '';
            document.getElementById('edit_icon').value = data.icon || '';
            document.getElementById('edit_target').value = data.target;
            document.getElementById('edit_css_class').value = data.css_class || '';
            document.getElementById('edit_order').value = data.order;
            document.getElementById('edit_is_active').checked = data.is_active;
            
            document.getElementById('editForm').action = `{{ route('admin.menus.edit', $menu->id) }}/items/${itemId}`;
            
            $('#editModal').modal('show');
        });
}
</script>
@endpush
@endsection
