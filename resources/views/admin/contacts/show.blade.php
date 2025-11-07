@extends('admin.layouts.app')

@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết tin nhắn liên hệ #{{ $contact->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Thông tin người gửi -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user me-2"></i>Thông tin người gửi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Họ tên:</strong>
                                            <p>{{ $contact->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Email:</strong>
                                            <p><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                                        </div>
                                        @if($contact->phone)
                                        <div class="col-md-6">
                                            <strong>Số điện thoại:</strong>
                                            <p><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></p>
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <strong>Thời gian gửi:</strong>
                                            <p>{{ $contact->created_at->format('d/m/Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nội dung tin nhắn -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-envelope me-2"></i>Nội dung tin nhắn
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Tiêu đề:</strong>
                                        <h4>{{ $contact->subject }}</h4>
                                    </div>
                                    <div>
                                        <strong>Nội dung:</strong>
                                        <div class="border rounded p-3 mt-2 bg-light">
                                            {{ $contact->message }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Trạng thái và thao tác -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-cog me-2"></i>Thao tác
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Trạng thái:</strong>
                                        @if($contact->is_read)
                                            <span class="badge bg-success ms-2">
                                                <i class="fas fa-check"></i> Đã đọc
                                            </span>
                                        @else
                                            <span class="badge bg-warning ms-2">
                                                <i class="fas fa-exclamation"></i> Chưa đọc
                                            </span>
                                        @endif
                                    </div>

                                    <div class="d-grid gap-2">
                                        <!-- Đánh dấu đã đọc/chưa đọc -->
                                        @if(!$contact->is_read)
                                            <form method="POST" action="{{ route('admin.contacts.mark-read', $contact) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Đánh dấu đã đọc
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.contacts.mark-unread', $contact) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fas fa-undo"></i> Đánh dấu chưa đọc
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Trả lời email -->
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}&body=Xin chào {{ $contact->name }},%0D%0A%0D%0ACảm ơn bạn đã liên hệ với chúng tôi." 
                                           class="btn btn-primary">
                                            <i class="fas fa-reply"></i> Trả lời email
                                        </a>

                                        <!-- Gọi điện (nếu có số) -->
                                        @if($contact->phone)
                                        <a href="tel:{{ $contact->phone }}" class="btn btn-info">
                                            <i class="fas fa-phone"></i> Gọi điện
                                        </a>
                                        @endif

                                        <!-- Xóa -->
                                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Xóa tin nhắn
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin thêm -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Thông tin thêm
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">
                                        <strong>Ngày tạo:</strong> {{ $contact->created_at->format('d/m/Y H:i:s') }}<br>
                                        <strong>Cập nhật:</strong> {{ $contact->updated_at->format('d/m/Y H:i:s') }}<br>
                                        <strong>IP:</strong> {{ request()->ip() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection