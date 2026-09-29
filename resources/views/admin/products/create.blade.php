@extends('layouts.app')

@section('title', 'افزودن محصول | رایکا')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
    <span class="arrow">←</span>
    بازگشت به داشبورد
</a>

<div class="admin-form-page">
    
    <!-- هدر -->
    <div class="form-header">
        <a href="{{ route('admin.products.index') }}" class="back-link">← بازگشت به محصولات</a>
        <h1>➕ افزودن محصول جدید</h1>
    </div>
    
    <!-- خطاها -->
    @if($errors->any())
        <div class="alert-error">
            <strong>⚠️ خطاها:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- فرم -->
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="product-form">
        @csrf
        
        <div class="form-grid">
            <!-- ستون راست - اطلاعات اصلی -->
            <div class="form-main">
                <div class="form-card">
                    <h2 class="form-section-title">📝 اطلاعات اصلی</h2>
                    
                    <div class="form-group">
                        <label for="name">نام محصول *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="مثلاً: شکلات وانیلی جعبه ۱۵ تایی" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">توضیحات</label>
                        <textarea id="description" name="description" rows="5" placeholder="توضیحات محصول...">{{ old('description') }}</textarea>
                    </div>
                </div>
                
                <div class="form-card">
                    <h2 class="form-section-title">💰 قیمت و موجودی</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">قیمت (تومان) *</label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="100000" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">موجودی *</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" placeholder="10" min="0" required>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Variants (وزن/تعداد) -->
            <div class="form-card">
                <h2 class="form-section-title">📏 وزن / تعداد</h2>
                
                <p style="color: #825B32; font-size: 0.9em; margin-bottom: 15px;">
                    اگه محصولت چند تا وزن یا تعداد داره، اینجا اضافه کن. اگه نداری، خالی بذار.
                </p>
                
                <div id="variantsContainer">
                    <!-- Variant ها با JS اضافه میشن -->
                </div>
                
                <button type="button" onclick="addVariant()" class="btn-add-variant">
                    ➕ افزودن وزن/تعداد
                </button>
            </div>


            <!-- ستون چپ - دسته و عکس -->
            <div class="form-side">
                <div class="form-card">
                    <h2 class="form-section-title">📂 دسته‌بندی</h2>
                    
                    <div class="form-group">
                        <label for="category_id">انتخاب دسته *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- انتخاب کن --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-card">
                    <h2 class="form-section-title">🖼 تصویر اصلی محصول</h2>
                    
                    <div class="image-upload">
                        <input type="file" id="image" name="image" accept="image/*" class="file-input">
                        <label for="image" class="file-label">
                            <div class="file-icon">📷</div>
                            <span>انتخاب عکس اصلی</span>
                            <small>عکس شاخص محصول</small>
                        </label>
                        <div class="image-preview" id="imagePreview"></div>
                    </div>
                </div>

                <div class="form-card">
                    <h2 class="form-section-title">🖼 گالری تصاویر</h2>
                    
                    <div class="image-upload">
                        <input type="file" id="galleryImages" name="images[]" accept="image/*" multiple class="file-input">
                        <label for="galleryImages" class="file-label">
                            <div class="file-icon">📸</div>
                            <span>انتخاب چند عکس</span>
                            <small>میتونی چند تا عکس انتخاب کنی</small>
                        </label>
                        <div class="gallery-preview" id="galleryPreview"></div>
                    </div>
                </div>
                
                <div class="form-card">
                    <h2 class="form-section-title">⚙️ تنظیمات</h2>
                    
                    <label class="switch-label">
                        <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="switch"></span>
                        <span>محصول فعال</span>
                    </label>
                    
                    <label class="switch-label">
                        <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="switch"></span>
                        <span>محصول ویژه</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- دکمه‌ها -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                ✅ ذخیره محصول
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                ❌ انصراف
            </a>
        </div>
    </form>
    
</div>

<style>
.admin-form-page {
    max-width: 1300px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

.form-header {
    margin-bottom: 30px;
}

.back-link {
    color: #825B32;
    text-decoration: none;
    font-size: 0.95em;
    transition: all 0.3s;
    display: inline-block;
    margin-bottom: 15px;
}

.back-link:hover {
    color: #603F26;
    transform: translateX(-5px);
}

.form-header h1 {
    font-size: 2.2em;
    color: #603F26;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    border-right: 4px solid #d32f2f;
}

.alert-error ul {
    margin-top: 10px;
    padding-right: 20px;
}

.alert-error li {
    margin: 5px 0;
}

/* گرید فرم */
.form-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

.form-main, .form-side {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.form-card:hover {
    border-color: #FFEAC5;
}

.form-section-title {
    color: #603F26;
    font-size: 1.2em;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px dashed #FFEAC5;
}

.form-group {
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    color: #603F26;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 0.95em;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #f0e0c8;
    border-radius: 12px;
    font-family: 'IRANSans', Tahoma;
    font-size: 1em;
    transition: all 0.3s;
    background: #fffbf2;
    box-sizing: border-box;
    color: #2C1C10;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #603F26;
    background: white;
    box-shadow: 0 0 0 4px rgba(96, 63, 38, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* آپلود عکس */
.image-upload {
    text-align: center;
}

.file-input {
    display: none;
}

.file-label {
    display: block;
    padding: 40px 20px;
    border: 3px dashed #825B32;
    border-radius: 15px;
    background: #fffbf2;
    cursor: pointer;
    transition: all 0.3s;
}

.file-label:hover {
    border-color: #603F26;
    background: #FFEAC5;
    transform: scale(1.02);
}

.file-icon {
    font-size: 3em;
    margin-bottom: 10px;
}

.file-label span {
    display: block;
    color: #603F26;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 1.05em;
}

.file-label small {
    color: #825B32;
    font-size: 0.85em;
}

.image-preview {
    margin-top: 15px;
}

.image-preview img {
    max-width: 100%;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

/* Switch */
.switch-label {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    cursor: pointer;
    color: #603F26;
    font-weight: 500;
}

.switch-label input[type="checkbox"] {
    display: none;
}

.switch {
    position: relative;
    width: 50px;
    height: 28px;
    background: #ccc;
    border-radius: 30px;
    transition: background 0.3s;
    flex-shrink: 0;
}

.switch::after {
    content: '';
    position: absolute;
    top: 3px;
    right: 3px;
    width: 22px;
    height: 22px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.switch-label input:checked + .switch {
    background: #28a745;
}

.switch-label input:checked + .switch::after {
    transform: translateX(-22px);
}

/* دکمه‌ها */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn-submit {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 16px 40px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05em;
    cursor: pointer;
    font-family: 'IRANSans', Tahoma;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
}

.btn-cancel {
    background: white;
    color: #603F26;
    border: 2px solid #603F26;
    padding: 16px 40px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.05em;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-block;
}

.btn-cancel:hover {
    background: #603F26;
    color: white;
    transform: translateY(-3px);
}

/* موبایل */
@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .form-header h1 {
        font-size: 1.6em;
    }
    
    .form-card {
        padding: 20px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .btn-submit, .btn-cancel {
        width: 100%;
        padding: 14px 20px;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
}

.gallery-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
    justify-content: center;
}

</style>

<script>
// پیش‌نمایش عکس اصلی
document.getElementById('image').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var preview = document.getElementById('imagePreview');
            preview.innerHTML = '<img src="' + event.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
});

// پیش‌نمایش گالری با قابلیت حذف
var galleryInput = document.getElementById('galleryImages');
var selectedFiles = [];

if (galleryInput) {
    galleryInput.addEventListener('change', function(e) {
        var newFiles = Array.from(e.target.files);
        selectedFiles = selectedFiles.concat(newFiles);
        updateGalleryPreview();
    });
}

function updateGalleryPreview() {
    var preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';
    
    selectedFiles.forEach(function(file, index) {
        var wrapper = document.createElement('div');
        wrapper.style.cssText = 'position: relative; display: inline-block;';
        
        var reader = new FileReader();
        reader.onload = function(event) {
            var img = document.createElement('img');
            img.src = event.target.result;
            img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 8px; display: block;';
            wrapper.appendChild(img);
            
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = '✕';
            btn.style.cssText = 'position: absolute; top: 3px; right: 3px; width: 22px; height: 22px; border-radius: 50%; background: #d32f2f; color: white; border: none; cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center;';
            btn.onclick = function() {
                removeGalleryFile(index);
            };
            wrapper.appendChild(btn);
        };
        reader.readAsDataURL(file);
        
        preview.appendChild(wrapper);
    });
    
    syncFilesToInput();
}

function removeGalleryFile(index) {
    selectedFiles.splice(index, 1);
    updateGalleryPreview();
}

function syncFilesToInput() {
    var dataTransfer = new DataTransfer();
    selectedFiles.forEach(function(file) {
        dataTransfer.items.add(file);
    });
    galleryInput.files = dataTransfer.files;
}


// ============================================
// Variants (وزن/تعداد)
// ============================================

var variantIndex = 0;

function addVariant(label = '', price = '', stock = '') {
    var container = document.getElementById('variantsContainer');
    
    var div = document.createElement('div');
    div.className = 'variant-row';
    div.innerHTML = `
        <input type="text" name="variants[${variantIndex}][label]" placeholder="مثلاً: ۲۵۰ گرم" value="${label}" class="variant-input">
        <input type="number" name="variants[${variantIndex}][price]" placeholder="قیمت" value="${price}" class="variant-input">
        <input type="number" name="variants[${variantIndex}][stock]" placeholder="موجودی" value="${stock}" class="variant-input">
        <button type="button" onclick="this.parentElement.remove()" class="btn-remove-variant">✕</button>
    `;
    
    container.appendChild(div);
    variantIndex++;
}

// اگه اولین باره، یه ردیف خالی نشون بده
document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('variantsContainer');
    if (container && container.children.length === 0) {
        addVariant();
    }
});
</script>

@endsection