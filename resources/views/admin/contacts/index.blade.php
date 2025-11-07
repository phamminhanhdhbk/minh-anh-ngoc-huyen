@extends('admin.layouts.app')

@section('title', 'Quản lý liên hệ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách tin nhắn liên hệ</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}">Tất cả</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filter' => 'unread']) }}">Chưa đọc</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filter' => 'read']) }}">Đã đọc</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Trạng thái</th>
                                    <th>Người gửi</th>
                                    <th>Email</th>
                                    <th>Tiêu đề</th>
                                    <th>Thời gian</th>
                                    <th style="width: 120px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                <tr class="{{ !$contact->is_read ? 'table-warning' : '' }}">
                                    <td>{{ $contact->id }}</td>
                                    <td>
                                        @if($contact->is_read)
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Đã đọc</span>
                                        @else
                                            <span class="badge bg-warning"><i class="fas fa-exclamation"></i> Chưa đọc</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $contact->name }}</strong>
                                        @if($contact->phone)
                                            <br><small class="text-muted">{{ $contact->phone }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                            {{ $contact->email }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.contacts.show', $contact) }}" class="text-decoration-none">
                                            {{ Str::limit($contact->subject, 50) }}
                                        </a>
                                        @if(!$contact->is_read)
                                            <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $contact->created_at->format('d/m/Y H:i') }}</small>
                                        <br><small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.contacts.show', $contact) }}" 
                                               class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$contact->is_read)
                                                <form method="POST" action="{{ route('admin.contacts.mark-read', $contact) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Đánh dấu đã đọc">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.contacts.mark-unread', $contact) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-warning btn-sm" title="Đánh dấu chưa đọc">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có tin nhắn liên hệ nào</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($contacts->hasPages())
                <div class="card-footer">
                    {{ $contacts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection