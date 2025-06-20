@extends('admin.layouts.master')
@section('main_section')
<style>
    :root {
        --primary: #007bff;
        --secondary: #6c757d;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #ffc107;
        --info: #17a2b8;
        --light: #f8f9fa;
        --dark: #343a40;
    }
    
    .container-xxl {
        background-color: #f1f1f1;
        padding: 20px;
    }
    
    .card {
        border: none;
        border-radius: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        background: #fff;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #555;
    }
    
    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 5px;
        color: #333;
    }
    
    .form-control, .form-select {
        border-radius: 3px;
        padding: 8px 12px;
        font-size: 0.9rem;
        border: 1px solid #ddd;
        height: auto;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        border-color: #007bff;
    }
    
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }
    
    .nav-tabs .nav-link {
        border: none;
        padding: 10px 20px;
        font-weight: 500;
        color: #6c757d;
        border-radius: 0;
    }
    
    .nav-tabs .nav-link.active {
        color: #007bff;
        background: transparent;
        border-bottom: 3px solid #007bff;
    }
    
    .variation-card {
        background: #fff;
        padding: 20px;
        border: 1px solid #ddd;
    }
    
    .variation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .variation-item {
        background: #f8fafc;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    
    .variation-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e2e8f0;
    }
    
    .variation-item-title {
        font-weight: 600;
        color: #007bff;
        font-size: 0.95rem;
    }
    
    .remove-variation {
        background: #fff;
        border: 1px solid #dc3545;
        color: #dc3545;
        border-radius: 50%;
        width: 28px;
        height: 28px;
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
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }
    
    .add-variation:hover {
        background: #0069d9;
    }
    
    .add-variation i {
        margin-right: 6px;
    }
    
    .variation-image-upload {
        position: relative;
        margin-top: 8px;
    }
    
    .variation-image-preview {
        width: 60px;
        height: 60px;
        background: #f8f9fa;
        border: 1px dashed #cbd5e1;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
    }
    
    .variation-image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    
    .variation-image-preview i {
        color: #94a3b8;
        font-size: 18px;
    }
    
    .sticky-variations {
        position: sticky;
        top: 20px;
    }
    
    .variation-field-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .submit-section {
        background: #fff;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #eaeef2;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
    }
    
    .image-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
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
        background: rgba(0,0,0,0.7);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .error-message {
        font-size: 0.8rem;
        color: #dc3545;
        margin-top: 5px;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .woocommerce-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
    }
    
    .woocommerce-main {
        background: #fff;
        padding: 20px;
        border: 1px solid #ddd;
    }
    
    .woocommerce-sidebar {
        background: #fff;
        padding: 20px;
        border: 1px solid #ddd;
    }
    
    .woocommerce-section {
        margin-bottom: 30px;
    }
    
    .woocommerce-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    @media (max-width: 992px) {
        .woocommerce-layout {
            grid-template-columns: 1fr;
        }
        
        .sticky-variations {
            position: static;
        }
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-cube me-2"></i>Add New Product</h4>
        </div>
        
        <div class="card-body">
            <form id="productForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
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
                                    <div class="woocommerce-section-title">Product Details</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Name *</label>
                                            <input type="text" name="products_name" class="form-control" value="{{ old('products_name') }}" required>
                                            <div class="error-message" id="error-products_name"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Short Description</label>
                                            <input type="text" name="products_short_description" class="form-control" value="{{ old('products_short_description') }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Full Description</label>
                                            <textarea name="products_description" class="form-control" rows="4">{{ old('products_description') }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Catalog No.</label>
                                            <input type="text" name="catelog_no" class="form-control" value="{{ old('catelog_no') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Slug</label>
                                            <input type="text" name="products_slug" class="form-control" value="{{ old('products_slug') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">Product Images</div>
                                    <div class="row">
                                      <!-- Featured Image Section -->
<div class="col-md-6 mb-3">
    <label class="form-label">Product Image *</label>
    <input type="file" name="featured_image" class="d-none" id="featuredImageInput" accept="image/*">

    <div class="image-preview-container">
        <div class="image-preview-item" id="featuredImagePreview" style="cursor:pointer;">
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f8f9fa;">
                <i class="fas fa-plus"></i>
            </div>
        </div>
    </div>
    <div class="error-message" id="error-featured_image"></div>
</div>


                                    </div>
                                </div>
                            </div>
                            
                            <!-- Inventory Tab -->
                            <div class="tab-pane fade" id="inventory">
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">Pricing</div>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Regular Price (₹) *</label>
                                                <input type="number" step="0.01" name="products_price" class="form-control" value="{{ old('products_price') }}">
                                                <div class="error-message" id="error-products_price"></div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Products Price1</label>
                                                <input type="number" step="0.01" name="products_price1" class="form-control" value="{{ old('products_price1') }}">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Products Price2</label>
                                                <input type="number" step="0.01" name="products_price2" class="form-control" value="{{ old('products_price2') }}">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Products Price3</label>
                                                <input type="number" step="0.01" name="products_price3" class="form-control" value="{{ old('products_price3') }}">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Products Price4</label>
                                                <input type="number" step="0.01" name="products_price4" class="form-control" value="{{ old('products_price4') }}">
                                            </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">SKU *</label>
                                            <input type="text" name="products_sku" class="form-control" value="{{ old('products_sku') }}" required>
                                            <div class="error-message" id="error-products_sku"></div>
                                        </div>

                                         <div class="col-md-3 mb-3">
                                            <label class="form-label">Quantity *</label>
                                            <input type="number" name="products_quantity" class="form-control" value="{{ old('products_quantity', 0) }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Model *</label>
                                            <input type="text" name="products_model" class="form-control" value="{{ old('products_model') }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Build Product Type *</label>
                                            <select name="build_product_type" id="build_product_type" class="form-select">
                                                <option value="yes" {{ old('build_product_type') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="no" {{ old('build_product_type') == 'no' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Products Related Items</label>
                                            <input type="text" name="products_related_items" class="form-control" value="{{ old('products_related_items') }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Related Master Sku</label>
                                            <input type="text" name="related_master_sku" class="form-control" value="{{ old('related_master_sku') }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Certified Lab</label>
                                            <input type="text" name="certified_lab" class="form-control" value="{{ old('certified_lab') }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Certificate Number</label>
                                            <input type="text" name="certificate_number" class="form-control" value="{{ old('certificate_number') }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Default Size</label>
                                            <input type="text" name="default_size" class="form-control" value="{{ old('default_size') }}">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Sort Order</label>
                                            <input type="text" name="sort_order" class="form-control" value="{{ old('sort_order') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">Inventory</div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Stock Quantity</label>
                                            <input type="number" name="products_quantity" class="form-control" value="{{ old('products_quantity', 0) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Stock Status</label>
                                            <select name="available" class="form-select">
                                                <option value="yes" {{ old('available') == 'yes' ? 'selected' : '' }}>In stock</option>
                                                <option value="no" {{ old('available') == 'no' ? 'selected' : '' }}>Out of stock</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Weight (g)</label>
                                            <input type="number" step="0.01" name="products_weight" class="form-control" value="{{ old('products_weight') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Shipping Tab -->
                            <div class="tab-pane fade" id="shipping">
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">Shipping Details</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Delivery Days</label>
                                            <input type="text" name="delivery_days" class="form-control" value="{{ old('delivery_days') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ready to Ship</label>
                                            <select name="ready_to_ship" class="form-select">
                                                <option value="">Select Option</option>
                                                <option value="1" {{ old('ready_to_ship') == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('ready_to_ship') == '0' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            
                            <!-- Attributes Tab -->
                            <div class="tab-pane fade" id="attributes">
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">Product Attributes</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Metal Type *</label>
                                            <select name="metal_type_id" class="form-select" required>
                                                <option value="">Select Metal Type</option>
                                                @foreach($metal_types as $id => $name)
                                                    <option value="{{ $id }}" {{ old('metal_type_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-metal_type_id"></div>
                                        </div>
                                        {{-- <div class="col-md-6 mb-3">
                                            <label class="form-label">Metal Color *</label>
                                            <select name="metal_color_id" class="form-select" required>
                                                <option value="">Select Metal Color</option>
                                                @foreach($metal_colors as $id => $name)
                                                    <option value="{{ $id }}" {{ old('metal_color_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-metal_color_id"></div>
                                        </div> --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Stone Type</label>
                                            <select name="stone_type_id" class="form-select">
                                                <option value="">Select Stone Type</option>
                                                @foreach($stone_types as $id => $name)
                                                    <option value="{{ $id }}" {{ old('stone_type_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Diamond Quality</label>
                                            <select name="diamond_quality_id" class="form-select">
                                                <option value="">Select Diamond Quality</option>
                                                @foreach($diamond_qualities as $id => $name)
                                                    <option value="{{ $id }}" {{ old('diamond_quality_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-6 mb-3">
                                            <label class="form-label">Style Category</label>
                                            <select name="style_category_id" class="form-select">
                                                <option value="">Select Style Category</option>
                                                @foreach($style_categories as $id => $name)
                                                    <option value="{{ $id }}" {{ old('style_category_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Shape *</label>
                                            <select name="shape_id" class="form-select" required>
                                                <option value="">Select Shape</option>
                                                @foreach($shapes as $id => $name)
                                                    <option value="{{ $id }}" {{ old('shape_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-shape_id"></div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Shop Zone *</label>
                                            <select name="shop_zone_id" id="shop_zone_id" class="form-control">
                                                <option value="">Select Shop Zone</option>
                                                @foreach($shopZones as $id => $name)
                                                    <option value="{{ $id }}" {{ old('shop_zone_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shop_zone_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Vendor Price</label>
                                            <select name="vendor_price" class="form-control">
                                                <option value="">-- Select Price --</option>
                                                @foreach($vendor_prices as $vendor_pri)
                                                    <option value="{{ $vendor_pri->vendor_price }}" {{ old('vendor_price') == $vendor_pri->vendor_price ? 'selected' : '' }}>
                                                        ₹{{ number_format($vendor_pri->vendor_price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Tab -->
                            <div class="tab-pane fade" id="advanced">
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">Advanced Settings</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Category *</label>
                                            <select name="categories_id" class="form-select" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->category_id }}" {{ old('categories_id') == $category->category_id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-categories_id"></div>
                                        </div>

                                        {{-- <div class="col-md-6 mb-3">
                                            <label for="product_category_id">Product To Category</label>
                                            <select name="product_category_id" id="product_category_id" class="form-control">
                                                <option value="">-- Select Style Category --</option>
                                                @foreach($product_to_categories as $id => $name)
                                                    <option value="{{ $id }}" {{ old('product_category_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Status</label>
                                            <select name="products_status" class="form-select">
                                                <option value="1" selected>Published</option>
                                                <option value="0">Draft</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Vendor</label>
                                            <select name="vendor_id" class="form-select">
                                                <option value="">Select Vendor</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->vendorid }}" {{ old('vendor_id') == $vendor->vendorid ? 'selected' : '' }}>
                                                        {{ $vendor->vendor_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Country of Origin</label>
                                            <select name="country_of_origin" class="form-select">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('country_of_origin') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->country_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Purchase Note</label>
                                            <textarea name="product_promotion" class="form-control" rows="2">{{ old('product_promotion') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="woocommerce-section">
                                    <div class="woocommerce-section-title">SEO Settings</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">SEO Title</label>
                                            <input type="text" name="products_meta_title" class="form-control" value="{{ old('products_meta_title') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">SEO Keywords</label>
                                            <input type="text" name="products_meta_keyword" class="form-control" value="{{ old('products_meta_keyword') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Keywords</label>
                                            <input type="text" name="product_keywords" class="form-control" value="{{ old('product_keywords') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Promotion</label>
                                            <input type="text" name="product_promotion" class="form-control" value="{{ old('product_promotion') }}">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">SEO Description</label>
                                            <textarea name="products_meta_description" class="form-control" rows="3">{{ old('products_meta_description') }}</textarea>
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
                                <div class="variation-item">
                                    <div class="variation-item-header">
                                        <div class="variation-item-title">Variation #1</div>
                                        <div class="remove-variation">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="variation-field-group">
                                        <div class="form-group">
                                            <label class="form-label">Carat *</label>
                                            <input type="number" step="0.01" name="variations[0][carat]" class="form-control" required placeholder="0.00">
                                            <div class="error-message" id="error-variations-0-carat"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label">Price (₹) *</label>
                                            <input type="number" step="0.01" name="variations[0][price]" class="form-control" required placeholder="0.00">
                                            <div class="error-message" id="error-variations-0-price"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="variation-field-group">
                                        <div class="form-group">
                                            <label class="form-label">SKU *</label>
                                            <input type="text" name="variations[0][sku]" class="form-control" required placeholder="SKU">
                                            <div class="error-message" id="error-variations-0-sku"></div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Mster SKU *</label>
                                            <input type="text" name="variations[0][master_sku]" class="form-control" placeholder="Master SKU">
                                            <div class="error-message" id="error-variations-0-master-sku"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="variation-field-group">
                                        <div class="form-group">
                                            <label class="form-label">Weight (g)</label>
                                            <input type="number" step="0.01" name="variations[0][weight]" class="form-control" value="0" placeholder="0.00">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Stock</label>
                                            <input type="number" name="variations[0][stock]" class="form-control" value="0" placeholder="0">
                                        </div>
                                    </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label">Shape *</label>
                                            <select name="variations[0][shape_id]" class="form-select" required>
                                                <option value="">Select Shape</option>
                                                @foreach($shapes as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="error-message" id="error-variations-0-shape_id"></div>
                                        </div>
                                
                                    
                                    <div class="form-group">
                                        <label class="form-label">Category *</label>
                                        <select name="variations[0][category_id]" class="form-select" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}">
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                <label class="form-label">Metal Color *</label>
                                <select name="variations[0][metal_color_id]" class="form-select" required>
                                    <option value="">Select Metal Color</option>
                                    @foreach($metalColor as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <div class="error-message" id="error-variations-0-metal_color_id"></div>
                            </div>
                            
                            <div class="form-group">
                                        <label class="form-label">Vendor *</label>
                                        <select name="variations[0][vendor_id]" class="form-select" required>
                                            <option value="">Select Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->vendorid }}">
                                                    {{ $vendor->vendor_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Variation Images (in variation template) -->
                                <div class="form-group">
    <label class="form-label">Variation Images *</label>
    <div class="variation-image-upload">
        <div class="variation-image-preview" style="display: flex; gap: 5px; flex-wrap: wrap;"></div>
        <input type="file" name="variations[0][images][]" multiple>
    </div>
</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="submit-section mt-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Publish</button>
                                <button type="button" class="btn btn-secondary btn-lg">Save Draft</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    let variationCount = $('.variation-item').length || 1;

    // Add Variation
    $('#addVariation').click(function () {
        const newVariation = `
            <div class="variation-item">
                <div class="variation-item-header">
                    <div class="variation-item-title">Variation #${variationCount + 1}</div>
                    <div class="remove-variation">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                
                <div class="variation-field-group">
                    <div class="form-group">
                        <label class="form-label">Carat *</label>
                        <input type="number" step="0.01" name="variations[${variationCount}][carat]" class="form-control" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price (₹) *</label>
                        <input type="number" step="0.01" name="variations[${variationCount}][price]" class="form-control" required placeholder="0.00">
                    </div>
                </div>

                <div class="variation-field-group">
                    <div class="form-group">
                        <label class="form-label">SKU *</label>
                        <input type="text" name="variations[${variationCount}][sku]" class="form-control" required placeholder="SKU">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock</label>
                        <input type="number" name="variations[${variationCount}][stock]" class="form-control" value="0" placeholder="0">
                    </div>
                </div>

                <div class="variation-field-group">
                    <div class="form-group">
                        <label class="form-label">Weight (g)</label>
                        <input type="number" step="0.01" name="variations[${variationCount}][weight]" class="form-control" value="0" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shape *</label>
                        <select name="variations[${variationCount}][shape_id]" class="form-select" required>
                            <option value="">Select Shape</option>
                            @foreach($shapes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="variations[${variationCount}][category_id]" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Metal Color *</label>
                    <select name="variations[${variationCount}][metal_color_id]" class="form-select" required>
                        <option value="">Select Metal Color</option>
                        @foreach($metalColor as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <div class="error-message" id="error-variations-${variationCount}-metal_color_id"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Vendor *</label>
                    <select name="variations[${variationCount}][vendor_id]" class="form-select" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendorid }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Variation Images *</label>
                    <div class="variation-image-upload">
                        <div class="variation-image-preview" style="display: flex; gap: 5px; flex-wrap: wrap;"></div>
                        <input type="file" name="variations[${variationCount}][images][]" multiple>
                    </div>
                </div>
            </div>
        `;
        $('.variations-scroll').append(newVariation);
        variationCount++;
    });

    // Remove Variation
    $(document).on('click', '.remove-variation', function () {
        if ($('.variation-item').length > 1) {
            $(this).closest('.variation-item').remove();
            $('.variation-item').each(function (index) {
                $(this).find('.variation-item-title').text(`Variation #${index + 1}`);
            });
        } else {
            alert('At least one variation is required');
        }
    });

    // Featured Image - Click to Upload
    $('#featuredImagePreview').click(function () {
        $('#featuredImageInput').click();
    });

    // Featured Image - Show Preview
    $('#featuredImageInput').change(function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#featuredImagePreview').html(`
                    <img src="${e.target.result}" alt="Featured Image" style="max-height: 150px;">
                    <div class="remove-image">×</div>
                `);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Remove Featured Image - Event Delegation
    $(document).on('click', '.remove-image', function (e) {
        e.preventDefault();
        $('#featuredImagePreview').html(`
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f8f9fa;">
                <i class="fas fa-plus"></i>
            </div>
        `);
        $('#featuredImageInput').val('');
    });

    // Variation Images - Preview
    $(document).on('change', '.variation-image-upload input[type="file"]', function () {
        const preview = $(this).siblings('.variation-image-preview');
        preview.html('');

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.append(`
                    <div class="image-preview-item">
                        <img src="${e.target.result}" alt="Variation Image" style="width:60px;height:60px;object-fit:cover;">
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        });
    });

    // Submit Form with AJAX
    $('#productForm').on('submit', function (e) {
        e.preventDefault();
        $('.error-message').text('');
        $('.is-invalid').removeClass('is-invalid');

        const formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    window.location.href = "{{ route('product.index') }}";
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        const message = errors[field][0];
                        const input = $(`[name="${field}"]`);
                        const errorEl = $(`#error-${field.replace(/\[|\]/g, '-').replace(/-$/, '')}`);
                        if (errorEl.length) {
                            errorEl.text(message);
                            input.addClass('is-invalid');
                        }
                    }
                } else {
                    alert('Something went wrong');
                }
            }
        });
    });
});
</script>


@endsection 