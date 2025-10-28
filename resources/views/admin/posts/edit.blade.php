@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Bài viết')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Chỉnh sửa Bài viết</h2>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug (URL)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                   id="slug" name="slug" value="{{ old('slug', $post->slug) }}"
                                   placeholder="Để trống để tự động tạo từ tiêu đề">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="excerpt">Tóm tắt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                      id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mô tả ngắn gọn về nội dung bài viết</small>
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="15">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search"></i> SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $post->seoData->meta_title ?? '') }}">
                            <small class="form-text text-muted">Để trống để sử dụng tiêu đề bài viết</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $post->seoData->meta_description ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $post->seoData->meta_keywords ?? '') }}">
                            <small class="form-text text-muted">Phân cách bằng dấu phẩy</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Xuất bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Xuất bản</option>
                                <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="published_at">Ngày xuất bản</label>
                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                   id="published_at" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Để trống để xuất bản ngay</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="featured"
                                       name="featured" value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="featured">
                                    <i class="fas fa-star text-warning"></i> Bài viết nổi bật
                                </label>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Cập nhật bài viết
                        </button>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="blog_category_id">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-control @error('blog_category_id') is-invalid @enderror"
                                    id="blog_category_id" name="blog_category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('blog_category_id', $post->blog_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blog_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Ảnh đại diện</h5>
                    </div>
                    <div class="card-body">
                        @if($post->featured_image)
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="img-thumbnail" style="max-width: 200px;">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="featured_image_visible" 
                                           name="featured_image_visible" value="1" 
                                           {{ $post->featured_image_visible ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured_image_visible">
                                        Hiển thị ảnh đại diện
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('featured_image') is-invalid @enderror"
                                       id="featured_image" name="featured_image" accept="image/*" onchange="previewImage(this)">
                                <label class="custom-file-label" for="featured_image">Chọn ảnh...</label>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div id="imagePreview" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="img-thumbnail w-100">
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @foreach($tags as $tag)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                                           {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tag_{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
class MyUploadAdapter {
    constructor( loader ) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then( file => {
                return new Promise( ( resolve, reject ) => {
                    const data = new FormData();
                    data.append( 'upload', file );
                    data.append( '_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content') );

                    fetch( '{{ route("admin.posts.upload-image") }}', {
                        method: 'POST',
                        body: data
                    } )
                    .then( response => response.json() )
                    .then( data => {
                        if ( data.uploaded ) {
                            resolve( {
                                default: data.url
                            } );
                        } else {
                            reject( data.error.message );
                        }
                    } )
                    .catch( error => reject( error ) );
                } );
            } );
    }

    abort() {
        // Reject promise returned from upload() method;
    }
}

function MyCustomUploadAdapterPlugin( editor ) {
    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
        return new MyUploadAdapter( loader );
    };
}

ClassicEditor
    .create(document.querySelector('#content'), {
        extraPlugins: [ MyCustomUploadAdapterPlugin ],
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'blockQuote', '|', 'imageUpload', 'insertTable', '|', 'bulletedList', 'numberedList', 'undo', 'redo'],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
            ]
        }
    })
    .catch(error => {
        console.error(error);
    });

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').show();
        }
        reader.readAsDataURL(input.files[0]);

        var fileName = input.files[0].name;
        $(input).next('.custom-file-label').html(fileName);
    }
}

$(document).ready(function() {
    // Handle form submission - sync CKEditor content to textarea
    $('#postForm').on('submit', function(e) {
        // Check if CKEditor is initialized
        if (window.ckeditorInstance) {
            const editorContent = window.ckeditorInstance.getData();
            
            // Validate content is not empty
            if (!editorContent || editorContent.trim() === '' || editorContent === '<p></p>') {
                e.preventDefault();
                alert('Vui lòng nhập nội dung bài viết');
                return false;
            }
            
            // Sync editor content to textarea
            document.getElementById('content').value = editorContent;
            console.log('Content synced to textarea:', editorContent.substring(0, 50) + '...');
        } else {
            // If editor not initialized, check textarea content
            const textareaContent = document.getElementById('content').value.trim();
            if (!textareaContent) {
                e.preventDefault();
                alert('Vui lòng nhập nội dung bài viết');
                return false;
            }
        }
        
        // Allow form submission
        return true;
    });
    
    // Auto-generate slug from title
    function generateSlug(text) {
        console.log('generateSlug called with:', text);
        // Convert to lowercase
        let slug = text.toLowerCase().trim();
        
        // Remove Vietnamese diacritics
        const diacriticsMap = {
            'à': 'a', 'á': 'a', 'ạ': 'a', 'ả': 'a', 'ã': 'a',
            'ă': 'a', 'ằ': 'a', 'ắ': 'a', 'ặ': 'a', 'ẳ': 'a', 'ẵ': 'a',
            'â': 'a', 'ầ': 'a', 'ấ': 'a', 'ậ': 'a', 'ẩ': 'a', 'ẫ': 'a',
            'è': 'e', 'é': 'e', 'ẹ': 'e', 'ẻ': 'e', 'ẽ': 'e',
            'ê': 'e', 'ề': 'e', 'ế': 'e', 'ệ': 'e', 'ể': 'e', 'ễ': 'e',
            'ì': 'i', 'í': 'i', 'ị': 'i', 'ỉ': 'i', 'ĩ': 'i',
            'ò': 'o', 'ó': 'o', 'ọ': 'o', 'ỏ': 'o', 'õ': 'o',
            'ô': 'o', 'ồ': 'o', 'ố': 'o', 'ộ': 'o', 'ổ': 'o', 'ỗ': 'o',
            'ơ': 'o', 'ờ': 'o', 'ớ': 'o', 'ợ': 'o', 'ở': 'o', 'ỡ': 'o',
            'ù': 'u', 'ú': 'u', 'ụ': 'u', 'ủ': 'u', 'ũ': 'u',
            'ư': 'u', 'ừ': 'u', 'ứ': 'u', 'ự': 'u', 'ử': 'u', 'ữ': 'u',
            'ỳ': 'y', 'ý': 'y', 'ỵ': 'y', 'ỷ': 'y', 'ỹ': 'y',
            'đ': 'd'
        };
        
        // Replace Vietnamese characters
        for (let char in diacriticsMap) {
            slug = slug.replace(new RegExp(char, 'g'), diacriticsMap[char]);
        }
        
        console.log('After removing diacritics:', slug);
        
        // Remove special characters (keep only alphanumeric and spaces)
        slug = slug.replace(/[^a-z0-9\s-]/g, '');
        
        console.log('After removing special chars:', slug);
        
        // Split by spaces and filter empty strings
        let words = slug.split(/\s+/).filter(word => word.length > 0);
        
        console.log('Words array:', words);
        
        // Join words with hyphens
        slug = words.join('-');
        
        console.log('After joining with hyphens:', slug);
        
        // Remove multiple consecutive hyphens
        slug = slug.replace(/-+/g, '-');
        
        // Remove leading and trailing hyphens
        slug = slug.replace(/^-+|-+$/g, '');
        
        console.log('Final slug:', slug);
        return slug;
    }
    
    // Delay to ensure elements are ready
    setTimeout(function() {
        let userEditedSlug = false;
        
        // On title input change
        $('#title').on('input', function() {
            console.log('Title input triggered');
            const titleValue = $(this).val().trim();
            console.log('Title value:', titleValue);
            
            // Auto-generate slug unless user has manually edited it
            if (titleValue && !userEditedSlug) {
                const newSlug = generateSlug(titleValue);
                $('#slug').val(newSlug);
                console.log('Slug set to:', newSlug);
            }
        });
        
        // Track if user manually edits slug
        $('#slug').on('input', function() {
            userEditedSlug = true;
            console.log('Slug manually edited by user:', $(this).val());
        });
    }, 500);
});
</script>
@endpush
@endsection
