<tr data-item-id="{{ $item->id }}" data-level="{{ $level }}">
    <td class="text-center">
        @if($level == 0)
            <i class="fas fa-grip-vertical drag-handle" style="cursor: move;"></i>
        @endif
    </td>
    <td>{{ $item->id }}</td>
    <td>
        <div style="padding-left: {{ $level * 20 }}px;">
            @if($level > 0)
                <i class="fas fa-level-up-alt fa-rotate-90 text-muted mr-1"></i>
            @endif
            @if($item->icon)
                <i class="{{ $item->icon }} mr-1"></i>
            @endif
            <strong>{{ $item->title }}</strong>
        </div>
    </td>
    <td>
        @if($item->url)
            <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-primary">
                <small>{{ Str::limit($item->url, 30) }}</small>
            </a>
        @elseif($item->route)
            <code>{{ $item->route }}</code>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>
        @if($item->icon)
            <i class="{{ $item->icon }}"></i>
            <br><small class="text-muted">{{ $item->icon }}</small>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>{{ $item->order }}</td>
    <td>
        @if($item->is_active)
            <span class="badge badge-success">Hiện</span>
        @else
            <span class="badge badge-danger">Ẩn</span>
        @endif
    </td>
    <td>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button"
                    class="btn btn-info"
                    onclick="editMenuItem({{ $item->id }})"
                    title="Chỉnh sửa">
                <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.menus.items.destroy', [$menu->id, $item->id]) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa mục menu này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>

@if($item->children->count() > 0)
    @foreach($item->children as $child)
        @include('admin.menus.partials.menu-item-row', ['item' => $child, 'level' => $level + 1])
    @endforeach
@endif
