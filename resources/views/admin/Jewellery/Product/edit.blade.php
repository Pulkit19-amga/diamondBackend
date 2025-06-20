@extends('admin.layouts.master')
@section('main_section')

<style>
    :root {
        --primary: #4b6cb7;
        --secondary: #6c757d;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #ffc107;
        --info: #17a2b8;
        --light: #f8f9fa;
        --dark: #343a40;
    }
    
    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container-xxl {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
        margin-bottom: 30px;
    }
    
    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 1px 10px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        background: #fff;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        color: white;
        border-bottom: none;
        padding: 15px 20px;
    }
    
    .card-header h4 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .card-header h4 i {
        margin-right: 12px;
        font-size: 1.4rem;
    }
    
    .card-body {
        padding: 25px;
    }
    
    .form-section-title {
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eaeef5;
        color: #2c3e50;
        display: flex;
        align-items: center;
    }
    
    .form-section-title i {
        margin-right: 10px;
        color: #4b6cb7;
    }
    
    .form-label {
        font-weight: 600;
        font-size: 0.92rem;
        margin-bottom: 7px;
        color: #34495e;
    }
    
    .form-control, .form-select {
        border-radius: 5px;
        padding: 10px 15px;
        font-size: 0.93rem;
        border: 1px solid #dce1e8;
        height: auto;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(75, 108, 183, 0.2);
        border-color: #4b6cb7;
    }
    
    .nav-tabs {
        border-bottom: 2px solid #e0e6ed;
        margin-bottom: 25px;
    }
    
    .nav-tabs .nav-link {
        border: none;
        padding: 12px 24px;
        font-weight: 500;
        color: #5a6a85;
        border-radius: 0;
        transition: all 0.2s;
        position: relative;
    }
    
    .nav-tabs .nav-link:hover {
        color: #4b6cb7;
    }
    
    .nav-tabs .nav-link.active {
        color: #4b6cb7;
        background: transparent;
        border-bottom: 3px solid #4b6cb7;
    }
    
    .variation-card {
        background: #fff;
        padding: 25px;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .variation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeef5;
    }
    
    .variation-header h5 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .variation-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        position: relative;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }
    
    .variation-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #e2e8f0;
        cursor: pointer;
    }
    
    .variation-item-title {
        font-weight: 600;
        color: #4b6cb7;
        font-size: 1rem;
    }
    
    .variation-toggle {
        background: #fff;
        border: 1px solid #4b6cb7;
        color: #4b6cb7;
        border-radius: 4px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .variation-toggle:hover {
        background: #4b6cb7;
        color: #fff;
    }
    
    .remove-variation {
        background: #fff;
        border: 1px solid #dc3545;
        color: #dc3545;
        border-radius: 4px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .remove-variation:hover {
        background: #dc3545;
        color: #fff;
    }
    
    .add-variation {
        background: #4b6cb7;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 9px 18px;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s;
        font-weight: 500;
    }
    
    .add-variation:hover {
        background: #3a5aa0;
        transform: translateY(-2px);
    }
    
    .add-variation i {
        margin-right: 8px;
    }
    
    .variation-image-upload {
        position: relative;
        margin-top: 12px;
    }
    
    .variation-image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 8px;
    }
    
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 12px;
    }
    
    .image-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border: 1px solid #e0e6ed;
        border-radius: 6px;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .image-preview-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.85);
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
    }
    
    .error-message {
        font-size: 0.82rem;
        color: #dc3545;
        margin-top: 6px;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .woocommerce-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 25px;
    }
    
    .woocommerce-main {
        background: #fff;
        padding: 25px;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
    }
    
    .woocommerce-sidebar {
        background: #fff;
        padding: 0;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .woocommerce-section {
        margin-bottom: 35px;
    }
    
    .woocommerce-section-title {
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eaeef5;
        color: #2c3e50;
    }
    
    .sticky-variations {
        position: sticky;
        top: 20px;
    }
    
    .variation-field-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-group {
        margin-bottom: 1.2rem;
    }
    
    .submit-section {
        background: #fff;
        padding: 25px;
        margin-top: 20px;
        border-top: 1px solid #eaeef2;
    }
    
    .btn-primary {
        background: #4b6cb7;
        border: none;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background: #3a5aa0;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    
    .d-grid {
        display: grid;
        gap: 15px;
    }
    
    .preview-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        padding: 20px;
        text-align: center;
    }
    
    .preview-placeholder i {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .variation-content {
        display: block;
    }
    
    .variation-content.collapsed {
        display: none;
    }
    
    .image-upload-btn {
        background: #4b6cb7;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
    }
    
    .image-upload-btn i {
        margin-right: 5px;
    }
    
    .image-upload-btn:hover {
        background: #3a5aa0;
    }
    
    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .badge-active {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .badge-inactive {
        background-color: #f8d7da;
        color: #842029;
    }
    
    @media (max-width: 1200px) {
        .woocommerce-layout {
            grid-template-columns: 1fr;
        }
        
        .sticky-variations {
            position: static;
        }
    }
    
    @media (max-width: 768px) {
        .variation-field-group {
            grid-template-columns: 1fr;
        }
        
        .nav-tabs .nav-link {
            padding: 10px 15px;
            font-size: 0.9rem;
        }
    }
    .variation-image-upload {
        position: relative;
        margin-top: 12px;
    }
    
    .variation-image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 8px;
    }
    
    .variation-image-preview-item {
        position: relative;
        width: 80px;
        height: 80px;
        border: 1px solid #e0e6ed;
        border-radius: 6px;
        overflow: hidden;
        background: #f8f9fa;
    }
    
    .variation-image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .variation-image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.85);
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.7rem;
    }
    
    .variation-image-upload-btn {
        background: #4b6cb7;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
        font-size: 0.85rem;
    }
    
    .variation-image-upload-btn:hover {
        background: #3a5aa0;
    }
</style>

<div class="container-xxl">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-edit me-2"></i>Edit Product - {{ $product->products_name }}</h4>
        </div>
        
        <div class="card-body">
            <form id="productForm" method="POST" action="{{ route('product.update', $product->products_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="woocommerce-layout">
                    <!-- Main Content Area -->
                    <div class="woocommerce-main">
                        <ul class="nav nav-tabs" id="productTabs">
                            <li class="nav-item">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general">General</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory">Inventory</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping">Shipping</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="attributes-tab" data-bs-toggle="tab" data-bs-target="#attributes">Attributes</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced">Advanced</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-info-circle"></i>Product Details
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Name *</label>
                                            <input type="text" name="products_name" class="form-control" value="{{ old('products_name', $product->products_name) }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Status</label>
                                            <div>
                                                <select name="products_status" class="form-select">
                                                    <option value="1" {{ old('products_status', $product->products_status) == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('products_status', $product->products_status) == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Short Description</label>
                                            <input type="text" name="products_short_description" class="form-control" value="{{ old('products_short_description', $product->products_short_description) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">SKU</label>
                                            <input type="text" name="products_sku" class="form-control" value="{{ old('products_sku', $product->products_sku) }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Full Description</label>
                                            <textarea name="products_description" class="form-control" rows="4">{{ old('products_description', $product->products_description) }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Catalog No.</label>
                                            <input type="text" name="catelog_no" class="form-control" value="{{ old('catelog_no', $product->catelog_no) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Slug</label>
                                            <input type="text" name="products_slug" class="form-control" value="{{ old('products_slug', $product->products_slug) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-images"></i>Product Images
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Product Images</label>
                                            <div class="image-preview-container" id="preview-container">
                                                @if($product->images instanceof \Illuminate\Database\Eloquent\Collection)
                                                    @foreach($product->images as $image)
                                                        <div class="image-preview-item position-relative d-inline-block me-2 mb-2">
                                                            <img src="{{ url('storage/' . $image->image_path) }}" class="rounded border" width="100" height="100" />
                                                            <div class="remove-image position-absolute top-0 end-0 bg-danger text-white rounded-circle px-1" style="cursor:pointer;" data-image-id="{{ $image->id }}">
                                                                <i class="fas fa-times"></i>
                                                            </div>
                                                            <input type="hidden" name="old_images[]" value="{{ $image->id }}">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <input type="file" name="gallery_images[]" id="image-upload" multiple class="d-none">
                                            <button type="button" class="btn btn-outline-primary image-upload-btn">
                                                <i class="fas fa-plus"></i> Add More Images
                                            </button>
                                            <input type="hidden" name="remove_images" id="removed-images" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Inventory Tab -->
                            <div class="tab-pane fade" id="inventory">
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-tags"></i>Pricing
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Regular Price (₹) *</label>
                                            <input type="number" step="0.01" name="products_price" class="form-control" value="{{ old('products_price', $product->products_price) }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Price 1</label>
                                            <input type="number" step="0.01" name="products_price1" class="form-control" value="{{ old('products_price1', $product->products_price1) }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Price 2</label>
                                            <input type="number" step="0.01" name="products_price2" class="form-control" value="{{ old('products_price2', $product->products_price2) }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Price 3</label>
                                            <input type="number" step="0.01" name="products_price3" class="form-control" value="{{ old('products_price3', $product->products_price3) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Stock Quantity</label>
                                            <input type="number" name="products_quantity" class="form-control" value="{{ old('products_quantity', $product->products_quantity) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Model</label>
                                            <input type="text" name="products_model" class="form-control" value="{{ old('products_model', $product->products_model) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Weight (g)</label>
                                            <input type="number" step="0.01" name="products_weight" class="form-control" value="{{ old('products_weight', $product->products_weight) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Shipping Tab -->
                            <div class="tab-pane fade" id="shipping">
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-truck"></i>Shipping Details
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Delivery Days</label>
                                            <input type="text" name="delivery_days" class="form-control" value="{{ old('delivery_days', $product->delivery_days) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ready to Ship</label>
                                            <select name="ready_to_ship" class="form-select">
                                                <option value="0" {{ old('ready_to_ship', $product->ready_to_ship) == 0 ? 'selected' : '' }}>No</option>
                                                <option value="1" {{ old('ready_to_ship', $product->ready_to_ship) == 1 ? 'selected' : '' }}>Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Country of Origin</label>
                                            <select name="country_of_origin" class="form-select">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->country_id }}" {{ old('country_of_origin', $product->country_of_origin) == $country->country_id ? 'selected' : '' }}>
                                                        {{ $country->country_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Shipping Zone</label>
                                            <select name="shop_zone_id" class="form-select">
                                                <option value="">Select Zone</option>
                                                @foreach($shopZones as $zone)
                                                    <option value="{{ $zone->zone_id }}" {{ old('shop_zone_id', $product->shop_zone_id) == $zone->zone_id ? 'selected' : '' }}>
                                                        {{ $zone->zone_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Attributes Tab -->
                            <div class="tab-pane fade" id="attributes">
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-gem"></i>Diamond Attributes
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Quality *</label>
                                            <select name="diamond_quality_id" class="form-select" required>
                                                <option value="">Select Diamond Quality</option>
                                                @foreach($diamond_qualities as $id => $name)
                                                    <option value="{{ $id }}" {{ old('diamond_quality_id', $product->diamond_quality_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Clarity</label>
                                            <select name="diamond_clarity_id" class="form-select">
                                                <option value="">Select Clarity</option>
                                                @foreach($diamond_clarities as $id => $name)
                                                    <option value="{{ $id }}" {{ old('diamond_clarity_id', $product->diamond_clarity_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Color</label>
                                            <select name="diamond_color_id" class="form-select">
                                                <option value="">Select Color</option>
                                                @foreach($diamond_colors as $id => $name)
                                                    <option value="{{ $id }}" {{ old('diamond_color_id', $product->diamond_color_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cut</label>
                                            <select name="diamond_cut_id" class="form-select">
                                                <option value="">Select Cut</option>
                                                @foreach($diamond_cuts as $id => $name)
                                                    <option value="{{ $id }}" {{ old('diamond_cut_id', $product->diamond_cut_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Carat Weight</label>
                                            <input type="number" step="0.01" name="total_carat_weight" class="form-control" value="{{ old('total_carat_weight', $product->total_carat_weight) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-ring"></i>Metal Attributes
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Metal Type *</label>
                                            <select name="metal_type_id" class="form-select" required>
                                                <option value="">Select Metal Type</option>
                                                @foreach($metal_types as $id => $name)
                                                    <option value="{{ $id }}" {{ old('metal_type_id', $product->metal_type_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Metal Color *</label>
                                            <select name="metal_color_id" class="form-select" required>
                                                <option value="">Select Metal Color</option>
                                                @foreach($metal_colors as $id => $name)
                                                    <option value="{{ $id }}" {{ old('metal_color_id', $product->metal_color_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-metal_color_id"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Stone Type</label>
                                            <select name="stone_type_id" class="form-select">
                                                <option value="">Select Stone Type</option>
                                                @foreach($stone_types as $id => $name)
                                                    <option value="{{ $id }}" {{ old('stone_type_id', $product->stone_type_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Shape *</label>
                                            <select name="shape_id" class="form-select" required>
                                                <option value="">Select Shape</option>
                                                @foreach($shapes as $id => $name)
                                                    <option value="{{ $id }}" {{ old('shape_id', $product->shape_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-shape_id"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Tab -->
                            <div class="tab-pane fade" id="advanced">
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-cog"></i>Advanced Settings
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Category *</label>
                                            <select name="categories_id" class="form-select" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('categories_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Vendor</label>
                                            <select name="vendor_id" class="form-select">
                                                <option value="">Select Vendor</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->vendorid }}" {{ old('vendor_id', $product->vendor_id) == $vendor->vendorid ? 'selected' : '' }}>
                                                        {{ $vendor->vendor_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Build Product Type</label>
                                            <select name="build_product_type" class="form-select">
                                                <option value="yes" {{ old('build_product_type', $product->build_product_type) == 'yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="no" {{ old('build_product_type', $product->build_product_type) == 'no' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Is Featured?</label>
                                            <select name="is_featured" class="form-select">
                                                <option value="1" {{ old('is_featured', $product->is_featured) == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('is_featured', $product->is_featured) == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Keywords</label>
                                            <input type="text" name="product_keywords" class="form-control" value="{{ old('product_keywords', $product->product_keywords) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="woocommerce-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-search"></i>SEO Settings
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">SEO Title</label>
                                            <input type="text" name="products_meta_title" class="form-control" value="{{ old('products_meta_title', $product->products_meta_title) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">SEO Keywords</label>
                                            <input type="text" name="products_meta_keyword" class="form-control" value="{{ old('products_meta_keyword', $product->products_meta_keyword) }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">SEO Description</label>
                                            <textarea name="products_meta_description" class="form-control" rows="3">{{ old('products_meta_description', $product->products_meta_description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="woocommerce-sidebar sticky-variations">
                        <div class="variation-card">
                            <div class="variation-header">
                                <h5>Product Variations</h5>
                                <button type="button" class="add-variation" id="addVariation">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                            
                            <div class="variations-scroll mt-3">
                                @foreach($product->variations as $index => $variation)
                                    <div class="variation-item" data-index="{{ $index }}">
                                        <div class="variation-item-header">
                                            <div class="variation-item-title">Variation #{{ $index + 1 }}</div>
                                            <div class="d-flex">
                                                <div class="variation-toggle me-2" data-action="toggle">
                                                    <i class="fas fa-minus"></i>
                                                </div>
                                                <div class="remove-variation">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="variation-content">
                                            <div class="variation-field-group">
                                                <div class="form-group">
                                                    <label class="form-label">Carat *</label>
                                                    <input type="number" step="0.01" name="variations[{{ $index }}][carat]" class="form-control" required value="{{ old('variations.'.$index.'.carat', $variation->carat) }}">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">Price (₹) *</label>
                                                    <input type="number" step="0.01" name="variations[{{ $index }}][price]" class="form-control" required value="{{ old('variations.'.$index.'.price', $variation->price) }}">
                                                </div>
                                            </div>
                                            
                                            <div class="variation-field-group">
                                                <div class="form-group">
                                                    <label class="form-label">SKU *</label>
                                                    <input type="text" name="variations[{{ $index }}][sku]" class="form-control" required value="{{ old('variations.'.$index.'.sku', $variation->sku) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Master SKU</label>
                                                    <input type="text" name="variations[{{ $index }}][master_sku]" class="form-control" value="{{ old('variations.'.$index.'.master_sku', $variation->master_sku) }}">
                                                </div>
                                            </div>
                                            
                                            <div class="variation-field-group">
                                                <div class="form-group">
                                                    <label class="form-label">Weight (g)</label>
                                                    <input type="text" name="weight[carat]" value="{{ $productVariation->weight['carat'] ?? '' }}">
                                                    <input type="text" name="weight[grams]" value="{{ $productVariation->weight['grams'] ?? '' }}">

                                                    {{-- <input type="number" step="0.01" name="variations[{{ $index }}][weight]" class="form-control" value="{{ old('variations.'.$index.'.weight', $variation->weight) }}"> --}}
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" name="variations[{{ $index }}][stock]" class="form-control" value="{{ old('variations.'.$index.'.stock', $variation->stock) }}">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Shape *</label>
                                                <select name="variations[{{ $index }}][shape_id]" class="form-select" required>
                                                    <option value="">Select Shape</option>
                                                    @foreach($shapes as $id => $name)
                                                        <option value="{{ $id }}" {{ $variation->shape_id == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Product Category *</label>
                                                <select name="categories_id" class="form-select" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('categories_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Metal Color *</label>
                                                <select name="variations[{{ $index }}][metal_color_id]" class="form-select" required>
                                                    <option value="">Select Metal Color</option>
                                                    @foreach($metalColor as $id => $name)
                                                        <option value="{{ $id }}" {{ $variation->metal_color_id == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Vendor *</label>
                                                <select name="variations[{{ $index }}][vendor_id]" class="form-select" required>
                                                    <option value="">Select Vendor</option>
                                                    @foreach($vendors as $vendor)
                                                        <option value="{{ $vendor->vendorid }}" {{ $variation->vendor_id == $vendor->vendorid ? 'selected' : '' }}>
                                                            {{ $vendor->vendor_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
    <label class="form-label">Variation Images</label>
    <div class="variation-image-preview" id="variation-preview-{{ $index }}">
        @foreach($variation->images as $img)
            <div class="variation-image-preview-item">
                <img src="{{ url('storage/' . $img->image_path) }}" alt="Variation Image">
                <div class="remove-image" data-image-id="{{ $img->id }}">
                    <i class="fas fa-times"></i>
                </div>
                <input type="hidden" name="variations[{{ $index }}][existing_images][]" value="{{ $img->id }}">
            </div>
        @endforeach
    </div>
    <input type="file" name="variations[{{ $index }}][images][]" class="variation-image-upload variation-upload-{{ $index }}" multiple style="display: none">
    <button type="button" class="variation-image-upload-btn" data-variation="{{ $index }}">
        <i class="fas fa-plus"></i> Add Images
    </button>
</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="submit-section">
                            <div class="d-grid gap-2">
                                <button type="submit" id="updateProduct" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Product
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-file-alt me-2"></i>Save Draft
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="bg-success text-white d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
                <h4 class="mb-3">Product Updated Successfully!</h4>
                <p class="text-muted mb-4">The product details have been successfully updated in the system.</p>
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Continue</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    // Prevent tabs from reloading page
    $('#productTabs button').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Track removed images
    $('.image-upload-btn').on('click', function () {
        $('#image-upload').click();
    });

    // File selection handler
    $('#image-upload').on('change', function () {
        const files = this.files;
        const previewContainer = $('#preview-container');
        
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imageHtml = `
                    <div class="image-preview-item position-relative d-inline-block me-2 mb-2">
                        <img src="${e.target.result}" class="rounded border" width="100" height="100" />
                        <div class="remove-image position-absolute top-0 end-0 bg-danger text-white rounded-circle px-1" style="cursor:pointer;">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                `;
                previewContainer.append(imageHtml);
            };
            reader.readAsDataURL(file);
        });
        
        // Reset file input
        this.value = '';
    });

    // Remove image handler
    $(document).on('click', '.remove-image', function() {
        const $previewItem = $(this).closest('.image-preview-item');
        const imageId = $(this).data('image-id');
        
        if (imageId) {
            const removedIds = $('#removed-images').val();
            $('#removed-images').val(removedIds ? `${removedIds},${imageId}` : imageId);
        }
        
        $previewItem.remove();
    });
    
    // Form submission handler
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#successModal').modal('show');
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 2000);
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                alert('An error occurred while updating the product.');
            }
        });
    });
    
    // Variation functionality
    let variationCount = {{ count($product->variations) }};
    
    // Add Variation
    $('#addVariation').click(function () {
        const variationHtml = generateVariationHtml(variationCount);
        $('.variations-scroll').append(variationHtml);
        variationCount++;
    });
    
    // Generate variation HTML
    function generateVariationHtml(index) {
        return `
            <div class="variation-item" data-index="${index}">
                <div class="variation-item-header">
                    <div class="variation-item-title">Variation #${index + 1}</div>
                    <div class="d-flex">
                        <div class="variation-toggle me-2" data-action="toggle">
                            <i class="fas fa-minus"></i>
                        </div>
                        <div class="remove-variation">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
                
                <div class="variation-content">
                    <div class="variation-field-group">
                        <div class="form-group">
                            <label class="form-label">Carat *</label>
                            <input type="number" step="0.01" name="variations[${index}][carat]" class="form-control" required placeholder="0.00">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Price (₹) *</label>
                            <input type="number" step="0.01" name="variations[${index}][price]" class="form-control" required placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="variation-field-group">
                        <div class="form-group">
                            <label class="form-label">SKU *</label>
                            <input type="text" name="variations[${index}][sku]" class="form-control" required placeholder="SKU">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Master SKU</label>
                            <input type="text" name="variations[${index}][master_sku]" class="form-control" placeholder="Master SKU">
                        </div>
                    </div>
                    
                    <div class="variation-field-group">
                        <div class="form-group">
                            <label class="form-label">Weight (g)</label>
                            <input type="number" step="0.01" name="variations[${index}][weight]" class="form-control" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock</label>
                            <input type="number" name="variations[${index}][stock]" class="form-control" placeholder="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Shape *</label>
                        <select name="variations[${index}][shape_id]" class="form-select" required>
                            <option value="">Select Shape</option>
                            @foreach($shapes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Metal Color *</label>
                        <select name="variations[${index}][metal_color_id]" class="form-select" required>
                            <option value="">Select Metal Color</option>
                            @foreach($metalColor as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Vendor *</label>
                        <select name="variations[${index}][vendor_id]" class="form-select" required>
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->vendorid }}">{{ $vendor->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Variation Images</label>
                        <div class="variation-image-preview" id="variation-preview-${index}"></div>
                        <input type="file" name="variations[${index}][images][]" class="variation-image-upload variation-upload-${index}" multiple style="display: none">
                        <button type="button" class="variation-image-upload-btn" data-variation="${index}">
                            <i class="fas fa-plus"></i> Add Images
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Remove Variation
    $(document).on('click', '.remove-variation', function () {
        if ($('.variation-item').length > 1) {
            $(this).closest('.variation-item').remove();
            // Reindex variations
            $('.variation-item').each(function (index) {
                $(this).find('.variation-item-title').text(`Variation #${index + 1}`);
                $(this).attr('data-index', index);
            });
        } else {
            alert('At least one variation is required');
        }
    });
    
    // Toggle Variation Content
    $(document).on('click', '.variation-toggle[data-action="toggle"]', function () {
        const content = $(this).closest('.variation-item').find('.variation-content');
        const icon = $(this).find('i');
        
        if (content.hasClass('collapsed')) {
            content.removeClass('collapsed');
            icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
            content.addClass('collapsed');
            icon.removeClass('fa-minus').addClass('fa-plus');
        }
    });
    
    // Variation Image Upload Trigger
    $(document).on('click', '.variation-image-upload-btn', function() {
        const variationIndex = $(this).data('variation');
        $(`.variation-upload-${variationIndex}`).click();
    });
    
    // Variation Image Change Handler
    $(document).on('change', '.variation-image-upload', function() {
        const variationIndex = $(this).attr('class').split(' ').find(cls => cls.startsWith('variation-upload-')).split('-')[2];
        const files = this.files;
        const previewContainer = $(`#variation-preview-${variationIndex}`);
        
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imageHtml = `
                    <div class="variation-image-preview-item">
                        <img src="${e.target.result}" alt="Variation Image">
                        <div class="remove-image">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                `;
                previewContainer.append(imageHtml);
            };
            reader.readAsDataURL(file);
        });
        
        // Reset file input to allow selecting same files again
        this.value = '';
    });
    
    // Remove Variation Image
    $(document).on('click', '.variation-image-preview-item .remove-image', function() {
        $(this).closest('.variation-image-preview-item').remove();
    });
});
</script>
@endsection