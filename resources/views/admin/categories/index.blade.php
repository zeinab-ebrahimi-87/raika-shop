@extends('layouts.app')

@section('title', 'مدیریت دسته‌بندی | رایکا')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
    <span class="arrow">←</span>
    بازگشت به داشبورد
</a>

<div class="admin-categories-page">
    
    <div class="page-header">
        <div>
            <h1>📂 مدیریت دسته‌بندی</h1>
            <p class="subtitle">مجموع: {{ $categories->count() }} دسته فعال</p>
        </div>
    </div>
    
    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif
    
    <!-- فرم افزودن -->
    <div class="add-category-card">
        <h2 class="card-title">➕ افزودن دسته‌بندی جدید</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="add-form">
            @csrf
            <input type="text" 
                   name="name" 
                   placeholder="مثلاً: شکلات تختهای" 
                   required
                   maxlength="255"
                   value="{{ old('name') }}">
            <button type="submit" class="btn-add">➕ افزودن</button>
        </form>
        @error('name')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="showTab('active')">
            ✅ دسته‌های فعال ({{ $categories->count() }})
        </button>
        <button class="tab-btn" onclick="showTab('trashed')">
            🗑 آرشیو ({{ $trashedCategories->count() }})
        </button>
    </div>
    
    <!-- دسته‌های فعال -->
    <div class="tab-content active" id="tab-active">
        <div class="categories-list-card">
            @if($categories->count() > 0)
                <div class="categories-grid">
                    @foreach($categories as $category)
                        <div class="category-card {{ !$category->is_active ? 'inactive' : '' }}">
                            <div class="category-info">
                                <div class="category-icon">📂</div>
                                <div>
                                    <div class="category-name">
                                        {{ $category->name }}
                                        @if(!$category->is_active)
                                            <span class="badge-inactive">غیرفعال</span>
                                        @endif
                                    </div>
                                    <div class="category-count">
                                        📦 {{ $category->products_count }} محصول
                                    </div>
                                </div>
                            </div>
                            
                            <div class="category-actions">
                                {{-- فعال/غیرفعال --}}
                                <form method="POST" 
                                      action="{{ route('admin.categories.toggleActive', $category->id) }}" 
                                      style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="btn-action btn-toggle"
                                            title="{{ $category->is_active ? 'غیرفعال کن' : 'فعال کن' }}">
                                        {{ $category->is_active ? '👁' : '🚫' }}
                                    </button>
                                </form>
                                
                                {{-- ویرایش --}}
                                <button type="button" 
                                        onclick="openEdit({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                        class="btn-action btn-edit"
                                        title="ویرایش">✏️</button>
                                
                                {{-- حذف --}}
                                @if($category->slug !== 'uncategorized')
                                    <button type="button" 
                                            onclick="openDelete({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->products_count }})"
                                            class="btn-action btn-delete"
                                            title="حذف">🗑</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>هنوز دسته‌بندی نساختی</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- آرشیو -->
    <div class="tab-content" id="tab-trashed">
        <div class="categories-list-card">
            @if($trashedCategories->count() > 0)
                <div class="categories-grid">
                    @foreach($trashedCategories as $category)
                        <div class="category-card trashed">
                            <div class="category-info">
                                <div class="category-icon">🗑</div>
                                <div>
                                    <div class="category-name">{{ $category->name }}</div>
                                    <div class="category-count">
                                        📦 {{ $category->products_count }} محصول
                                    </div>
                                    <div class="trashed-date">
                                        حذف شده در {{ $category->deleted_at->format('Y/m/d') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="category-actions">
                                {{-- بازگردانی --}}
                                <form method="POST" 
                                      action="{{ route('admin.categories.restore', $category->id) }}" 
                                      style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="btn-action btn-restore"
                                            title="بازگردانی">↩️</button>
                                </form>
                                
                                {{-- حذف کامل --}}
                                <form method="POST" 
                                      action="{{ route('admin.categories.forceDelete', $category->id) }}" 
                                      onsubmit="return confirm('مطمئنی؟ این دیگه برنمیگرده!')"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-action btn-force-delete"
                                            title="حذف کامل">💥</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">✨</div>
                    <p>آرشیو خالیه</p>
                </div>
            @endif
        </div>
    </div>
    
</div>

<!-- ============================================
     Modal ویرایش
     ============================================ -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <h2>✏️ ویرایش دسته‌بندی</h2>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <input type="text" name="name" id="editName" required maxlength="255">
            <div class="modal-actions">
                <button type="submit" class="btn-save">💾 ذخیره</button>
                <button type="button" onclick="closeModal('editModal')" class="btn-cancel">❌ انصراف</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================
     Modal حذف (با Reassign)
     ============================================ -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <h2>🗑 حذف دسته‌بندی</h2>
        <p class="modal-desc" id="deleteDesc"></p>
        
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            
            <div class="reassign-section" id="reassignSection" style="display: none;">
                <label>محصولاتش رو منتقل کن به:</label>
                <select name="target_category_id" id="targetCategory" class="form-select">
                    <option value="">-- انتخاب کن --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-id="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <p class="hint">⚠️ اگه انتخاب نکنی، حذف انجام نمیشه.</p>
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="btn-delete-confirm">🗑 تأیید حذف</button>
                <button type="button" onclick="closeModal('deleteModal')" class="btn-cancel">❌ انصراف</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-categories-page {
    max-width: 1000px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

.page-header { margin-bottom: 25px; }
.page-header h1 { color: #603F26; font-size: 2em; margin-bottom: 5px; }
.subtitle { color: #825B32; font-size: 0.95em; }

.alert-success, .alert-error {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    animation: slideDown 0.4s ease-out;
}
.alert-success { background: #d4edda; color: #155724; border-right: 4px solid #28a745; }
.alert-error { background: #f8d7da; color: #721c24; border-right: 4px solid #d32f2f; }

/* کارت افزودن */
.add-category-card {
    background: linear-gradient(135deg, #fffbf2, #FFEAC5);
    border: 2px dashed #825B32;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
}
.card-title { color: #603F26; font-size: 1.2em; margin-bottom: 18px; }

.add-form { display: flex; gap: 10px; flex-wrap: wrap; }
.add-form input {
    flex: 1;
    min-width: 200px;
    padding: 14px 18px;
    border: 2px solid #f0e0c8;
    border-radius: 12px;
    font-family: 'IRANSans', Tahoma;
    font-size: 1em;
    background: white;
    color: #2C1C10;
}
.add-form input:focus {
    outline: none;
    border-color: #603F26;
    box-shadow: 0 0 0 4px rgba(96, 63, 38, 0.1);
}

.btn-add {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 14px 30px;
    border-radius: 12px;
    font-family: 'IRANSans', Tahoma;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    white-space: nowrap;
}
.btn-add:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4); }

.field-error { color: #d32f2f; font-size: 0.85em; margin-top: 10px; padding-right: 10px; }

/* Tabs */
.tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    border-bottom: 2px solid #f0e0c8;
}

.tab-btn {
    background: transparent;
    border: none;
    padding: 12px 20px;
    font-family: 'IRANSans', Tahoma;
    font-weight: 600;
    font-size: 0.95em;
    color: #825B32;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
    margin-bottom: -2px;
}

.tab-btn:hover { color: #603F26; }
.tab-btn.active {
    color: #603F26;
    border-bottom-color: #603F26;
}

.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s ease-out; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* لیست */
.categories-list-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
}

.category-card {
    background: #fffbf2;
    border: 2px solid #f0e0c8;
    border-radius: 15px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.category-card:hover {
    border-color: #FFEAC5;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(96, 63, 38, 0.1);
}

.category-card.inactive {
    opacity: 0.7;
    background: #f5f5f5;
    border-style: dashed;
}

.category-card.trashed {
    background: #fff5f5;
    border-color: #f8d7da;
    border-style: dashed;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex: 1;
}

.category-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #603F26, #825B32);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3em;
    flex-shrink: 0;
}

.category-card.trashed .category-icon {
    background: linear-gradient(135deg, #999, #666);
}

.category-name {
    font-weight: 700;
    color: #603F26;
    font-size: 1em;
    margin-bottom: 3px;
    word-break: break-word;
}
.category-card:hover .category-name{
    color: #fffbf2;

}

.badge-inactive {
    display: inline-block;
    background: #f8d7da;
    color: #721c24;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.7em;
    font-weight: 700;
    margin-right: 5px;
}

.category-count {
    color: #825B32;
    font-size: 0.8em;
    font-weight: 600;
}
.category-card:hover .category-count{
    color: #cc9f6fff;
}
.trashed-date {
    color: #999;
    font-size: 0.7em;
    margin-top: 3px;
    font-style: italic;
}

.category-actions { display: flex; gap: 6px; flex-shrink: 0; }

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.btn-toggle { background: #e0d0b8; color: #603F26; }
.btn-toggle:hover { background: #603F26; color: white; transform: scale(1.1); }

.btn-edit { background: #cce5ff; color: #004085; }
.btn-edit:hover { background: #004085; color: white; transform: scale(1.1); }

.btn-delete { background: #f8d7da; color: #721c24; }
.btn-delete:hover { background: #d32f2f; color: white; transform: scale(1.1); }

.btn-restore { background: #d4edda; color: #155724; }
.btn-restore:hover { background: #28a745; color: white; transform: scale(1.1); }

.btn-force-delete { background: #fff3cd; color: #856404; }
.btn-force-delete:hover { background: #d32f2f; color: white; transform: scale(1.1); }

/* Empty */
.empty-state { text-align: center; padding: 50px 20px; color: #825B32; }
.empty-icon { font-size: 3.5em; margin-bottom: 12px; opacity: 0.5; }

/* Modal */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal.open { display: flex; animation: fadeIn 0.3s ease-out; }

.modal-content {
    background: white;
    border-radius: 20px;
    padding: 30px;
    max-width: 480px;
    width: 100%;
    animation: scaleIn 0.3s ease-out;
}

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.modal-content h2 { color: #603F26; margin-bottom: 15px; font-size: 1.3em; }
.modal-desc { color: #825B32; margin-bottom: 20px; line-height: 1.6; }

.modal-content input[type="text"],
.form-select {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #f0e0c8;
    border-radius: 12px;
    font-family: 'IRANSans', Tahoma;
    font-size: 1em;
    background: #fffbf2;
    color: #2C1C10;
    margin-bottom: 15px;
    box-sizing: border-box;
}

.modal-content input[type="text"]:focus,
.form-select:focus {
    outline: none;
    border-color: #603F26;
    background: white;
    box-shadow: 0 0 0 4px rgba(96, 63, 38, 0.1);
}

.reassign-section {
    background: #fffbf2;
    border: 2px dashed #825B32;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 20px;
}

.reassign-section label {
    display: block;
    color: #603F26;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 0.95em;
}

.hint {
    color: #d32f2f;
    font-size: 0.8em;
    margin-top: 8px;
}

.modal-actions { display: flex; gap: 10px; }

.btn-save, .btn-cancel, .btn-delete-confirm {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-family: 'IRANSans', Tahoma;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-save { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3); }

.btn-delete-confirm { background: linear-gradient(135deg, #d32f2f, #b71c1c); color: white; }
.btn-delete-confirm:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3); }

.btn-cancel { background: #f0e0c8; color: #603F26; }
.btn-cancel:hover { background: #e0d0b8; }

/* موبایل */
@media (max-width: 768px) {
    .page-header h1 { font-size: 1.5em; }
    
    .add-category-card, .categories-list-card { padding: 18px; }
    
    .add-form { flex-direction: column; }
    .add-form input { min-width: auto; width: 100%; }
    .btn-add { width: 100%; }
    
    .tabs { overflow-x: auto; }
    .tab-btn { white-space: nowrap; font-size: 0.85em; padding: 10px 15px; }
    
    .categories-grid { grid-template-columns: 1fr; }
    
    .category-card { padding: 12px; }
    .category-icon { width: 40px; height: 40px; font-size: 1.1em; }
    .category-name { font-size: 0.9em; }
    
    .btn-action { width: 34px; height: 34px; font-size: 14px; }
    
    .modal-content { padding: 20px; }
    .modal-actions { flex-direction: column; }
}
</style>

<script>
// ============================================
// Tab ها
// ============================================
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    
    document.getElementById('tab-' + tab).classList.add('active');
    event.target.classList.add('active');
}

// ============================================
// Modal ها
// ============================================
function openModal(id) {
    document.getElementById(id).classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

// ============================================
// ویرایش
// ============================================
function openEdit(id, name) {
    document.getElementById('editForm').action = '/admin/categories/' + id;
    document.getElementById('editName').value = name;
    openModal('editModal');
    setTimeout(() => document.getElementById('editName').focus(), 100);
}

// ============================================
// حذف
// ============================================
function openDelete(id, name, productCount) {
    var desc = document.getElementById('deleteDesc');
    var section = document.getElementById('reassignSection');
    var targetSelect = document.getElementById('targetCategory');
    
    document.getElementById('deleteForm').action = '/admin/categories/' + id;
    
    if (productCount > 0) {
        desc.innerHTML = 'دستهی <strong>«' + name + '»</strong> تعداد <strong>' + productCount + ' محصول</strong> داره.';
        section.style.display = 'block';
        
        // حذف خود دسته از لیست مقصد
        Array.from(targetSelect.options).forEach(opt => {
            opt.disabled = (opt.dataset.id == id);
        });
        
        targetSelect.value = '';
    } else {
        desc.innerHTML = 'مطمئنی میخوای دستهی <strong>«' + name + '»</strong> رو حذف کنی؟';
        section.style.display = 'none';
    }
    
    openModal('deleteModal');
}

// بستن با ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.open').forEach(m => m.classList.remove('open'));
    }
});

// بستن با کلیک بیرون
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
        }
    });
});
</script>

@endsection