@extends('layouts.admin')

@section('title', 'Create Banner')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle"></i> Create New Banner
        </h1>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Banner Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subtitle -->
                        <div class="form-group">
                            <label for="subtitle">Subtitle</label>
                            <input type="text" 
                                   name="subtitle" 
                                   id="subtitle" 
                                   class="form-control @error('subtitle') is-invalid @enderror"
                                   value="{{ old('subtitle') }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Type -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="slider" {{ old('type') == 'slider' ? 'selected' : '' }}>Slider</option>
                                        <option value="banner" {{ old('type', 'banner') == 'banner' ? 'selected' : '' }}>Banner</option>
                                        <option value="popup" {{ old('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Position -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="position">Position <span class="text-danger">*</span></label>
                                    <select name="position" id="position" class="form-control @error('position') is-invalid @enderror" required>
                                        <option value="home-top" {{ old('position', 'home-top') == 'home-top' ? 'selected' : '' }}>Home Top</option>
                                        <option value="home-middle" {{ old('position') == 'home-middle' ? 'selected' : '' }}>Home Middle</option>
                                        <option value="home-bottom" {{ old('position') == 'home-bottom' ? 'selected' : '' }}>Home Bottom</option>
                                        <option value="sidebar" {{ old('position') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                        <option value="category" {{ old('position') == 'category' ? 'selected' : '' }}>Category Page</option>
                                        <option value="product" {{ old('position') == 'product' ? 'selected' : '' }}>Product Page</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Order -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order">Display Order</label>
                                    <input type="number" 
                                           name="order" 
                                           id="order" 
                                           class="form-control @error('order') is-invalid @enderror"
                                           value="{{ old('order', 0) }}"
                                           min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <!-- Main Image -->
                        <div class="form-group">
                            <label for="image">Main Image <span class="text-danger">*</span></label>
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   class="form-control-file @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this, 'imagePreview')"
                                   required>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Recommended: 1920x600px for slider, 1200x400px for banner</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <!-- Mobile Image -->
                        <div class="form-group">
                            <label for="mobile_image">Mobile Image (Optional)</label>
                            <input type="file" 
                                   name="mobile_image" 
                                   id="mobile_image" 
                                   class="form-control-file @error('mobile_image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this, 'mobileImagePreview')">
                            @error('mobile_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Recommended: 800x600px for mobile devices</small>
                            <div id="mobileImagePreview" class="mt-2"></div>
                        </div>

                        <!-- Link -->
                        <div class="form-group">
                            <label for="link">Link URL</label>
                            <input type="url" 
                                   name="link" 
                                   id="link" 
                                   class="form-control @error('link') is-invalid @enderror"
                                   value="{{ old('link') }}"
                                   placeholder="https://example.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Button Text -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="button_text">Button Text</label>
                                    <input type="text" 
                                           name="button_text" 
                                           id="button_text" 
                                           class="form-control @error('button_text') is-invalid @enderror"
                                           value="{{ old('button_text') }}"
                                           placeholder="e.g. Shop Now">
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Open in New Tab -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               name="open_new_tab" 
                                               id="open_new_tab" 
                                               class="custom-control-input"
                                               value="1"
                                               {{ old('open_new_tab') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="open_new_tab">
                                            Open link in new tab
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Background Color -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="background_color">Background Color</label>
                                    <input type="color" 
                                           name="background_color" 
                                           id="background_color" 
                                           class="form-control @error('background_color') is-invalid @enderror"
                                           value="{{ old('background_color', '#ffffff') }}">
                                    @error('background_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Text Color -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="text_color">Text Color</label>
                                    <input type="color" 
                                           name="text_color" 
                                           id="text_color" 
                                           class="form-control @error('text_color') is-invalid @enderror"
                                           value="{{ old('text_color', '#000000') }}">
                                    @error('text_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Start Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="datetime-local" 
                                           name="start_date" 
                                           id="start_date" 
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="datetime-local" 
                                           name="end_date" 
                                           id="end_date" 
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       class="custom-control-input"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    <strong>Active</strong> (Show this banner on the website)
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Banner
                            </button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Banner Types
                    </h6>
                </div>
                <div class="card-body">
                    <h6><span class="badge bg-primary text-white">Slider</span></h6>
                    <p class="small">Full-width rotating banners on homepage. Recommended size: 1920x600px</p>

                    <h6><span class="badge bg-success text-white">Banner</span></h6>
                    <p class="small">Static promotional banners. Recommended size: 1200x400px</p>

                    <h6><span class="badge bg-warning text-dark">Popup</span></h6>
                    <p class="small mb-0">Modal popups for special promotions. Recommended size: 800x600px</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt"></i> Positions
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li><strong>Home Top:</strong> Main slider at top of homepage</li>
                        <li><strong>Home Middle:</strong> Mid-page promotional banner</li>
                        <li><strong>Home Bottom:</strong> Bottom promotional section</li>
                        <li><strong>Sidebar:</strong> Vertical sidebar banner</li>
                        <li><strong>Category Page:</strong> Banner on category pages</li>
                        <li><strong>Product Page:</strong> Banner on product pages</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.maxHeight = '200px';
                preview.appendChild(img);
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
