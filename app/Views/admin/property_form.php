<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="max-w-6xl mx-auto pb-12">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Add New Property</h2>
            <p class="text-gray-500 mt-1">Fill in the details, set pricing, upload media, and optimize for search.</p>
        </div>
        <a href="<?= base_url('admin/properties') ?>" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
            &larr; Back to Properties
        </a>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-r-lg shadow-sm flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-500 text-lg mt-0.5"></i>
            <div class="text-sm text-red-800 font-medium">
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-5 rounded-r-lg shadow-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
            <p class="text-sm text-green-800 font-bold"><?= esc(session()->getFlashdata('success')) ?></p>
        </div>
    <?php endif; ?>


<?php $actionUrl = isset($property) ? base_url('admin/properties/update/' . $property->id) : base_url('admin/properties/store'); ?>
    
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="propertyForm">

        <?= csrf_field() ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">1. Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="<?= old('title', $property->title ?? '') ?>" placeholder="e.g., Exquisite 5 Bedroom Mansion with Pool" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

               

                <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
    
    <?php $currentPurpose = old('purpose', $property->purpose ?? ''); ?>
    
    <select name="purpose" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white">
        <option value="">Select Purpose...</option>
        
        <option value="sale" <?= $currentPurpose === 'sale' ? 'selected' : '' ?>>For Sale</option>
        <option value="rent" <?= $currentPurpose === 'rent' ? 'selected' : '' ?>>For Rent (Yearly)</option>
        <option value="shortlet" <?= $currentPurpose === 'shortlet' ? 'selected' : '' ?>>Shortlet (Daily)</option>
    </select>
</div>
               <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
    
    <?php $currentType = old('property_type', $property->property_type ?? ''); ?>
    
    <select name="property_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white">
        <option value="Apartment" <?= $currentType === 'Apartment' ? 'selected' : '' ?>>Apartment</option>
        <option value="Detached Duplex" <?= $currentType === 'Detached Duplex' ? 'selected' : '' ?>>Detached Duplex</option>
        <option value="Semi-Detached Duplex" <?= $currentType === 'Semi-Detached Duplex' ? 'selected' : '' ?>>Semi-Detached Duplex</option>
        <option value="Terrace" <?= $currentType === 'Terrace' ? 'selected' : '' ?>>Terrace</option>
        <option value="Land" <?= $currentType === 'Land' ? 'selected' : '' ?>>Land</option>
    </select>
</div>

                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bedrooms</label>
                        <input type="number" name="bedrooms" min="0"  value="<?= old('bedrooms', $property->bedrooms ?? 0) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bathrooms</label>
                        <input type="number" name="bathrooms" min="0" value="<?= old('bathrooms', $property->bathrooms ?? 0) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Toilets</label>
                        <input type="number" name="toilets" min="0" value="<?= old('toilets', $property->toilets ?? 0) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 text-center font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Area (sqm)</label>
                        <input type="number" name="area_sqm" step="0.01" value="<?= old('area_sqm', $property->area_sqm ?? 0) ?>" placeholder="e.g. 450.5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 text-center font-bold">
                    </div>
                </div>
            </div>
        </div>

       

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900">2. Pricing Strategy</h3>
                <button type="button" id="addPriceBtn" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-md transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Pricing Option
                </button>
            </div>
            
            <div id="pricingContainer" class="space-y-4">
                <?php 
                // Handle both pre-filling on Edit, and default 1 row on Create
                $existingPrices = isset($propertyPrices) && !empty($propertyPrices) ? $propertyPrices : [ (object)['price'=>'', 'price_unit'=>'One Time', 'discount_price'=>''] ];
                foreach ($existingPrices as $index => $p): 
                ?>
                <div class="pricing-row relative grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-gray-50 p-4 rounded-xl border border-gray-100">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Base Price (<?= esc(config('Site')->currency) ?>) *</label>
                        <input type="number" name="prices[<?= $index ?>][price]" required value="<?= esc($p->price) ?>" class="base-price w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 font-bold text-gray-900">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pricing Unit *</label>
                        <select name="prices[<?= $index ?>][price_unit]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 bg-white font-medium">
                            <option value="One Time" <?= $p->price_unit === 'One Time' ? 'selected' : '' ?>>One Time</option>
                            <option value="Yearly" <?= $p->price_unit === 'Yearly' ? 'selected' : '' ?>>Yearly</option>
                            <option value="Monthly" <?= $p->price_unit === 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                            <option value="Daily" <?= $p->price_unit === 'Daily' ? 'selected' : '' ?>>Daily</option>
                            <option value="Per Night" <?= $p->price_unit === 'Per Night' ? 'selected' : '' ?>>Per Night</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Discounted Price</label>
                        <input type="number" name="prices[<?= $index ?>][discount_price]" value="<?= esc($p->discount_price) ?>" class="discount-price w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 font-bold text-green-700 bg-green-50">
                    </div>

                    <div class="flex items-center gap-2 pb-1">
                        <div class="discount-badge hidden items-center gap-1 px-3 py-1.5 rounded bg-red-100 border border-red-200">
                            <i class="fas fa-tags text-red-500 text-xs"></i>
                            <span class="text-xs font-bold text-red-700">-<span class="discount-percent">0</span>%</span>
                        </div>
                        <button type="button" class="remove-price-btn <?= $index === 0 ? 'hidden' : '' ?> ml-auto text-red-400 hover:text-red-600 p-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">3. Location Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Property Address</label>
                    <input type="text" name="address" placeholder="e.g., Plot 123, Diplomatic Drive"  value="<?= old('address', $property->address ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District / Neighborhood <span class="text-red-500">*</span></label>
                    <input type="text" name="location" required placeholder="e.g., Asokoro, Guzape"  value="<?= old('location', $property->location ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" required value="<?= old('city', $property->city ?? 'Abuja') ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-gray-50 text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Latitude (For Maps)</label>
                    <input type="text" name="latitude" placeholder="e.g., 9.0765" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50" value="<?= old('latitude', $property->latitude ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Longitude (For Maps)</label>
                    <input type="text" name="longitude" placeholder="e.g., 7.3986" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50" value="<?= old('longitude', $property->longitude ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">4. Description & SEO</h3>
            
           <div class="mb-8">
    <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Description <span class="text-red-500">*</span></label>
    
    <?php $currentDescription = old('description', $property->description ?? ''); ?>
    
    <div id="quillEditor" class="h-64 bg-white rounded-b-lg"><?= $currentDescription ?></div>
    
    <input type="hidden" name="description" id="hiddenDescription" value="<?= esc($currentDescription) ?>" required>
</div>

            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2"><i class="fab fa-google text-blue-500"></i> Search Engine Optimization</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Title (Max 60 chars)</label>
                        <input type="text" name="meta_title" value="<?= old('meta_title', $property->meta_title ?? '') ?>" placeholder="Buy 5 Bedroom Duplex in Guzape | Housebox" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                  <div>
    <label class="block text-xs font-medium text-gray-700 mb-1">Meta Description (Max 160 chars)</label>
    <textarea name="meta_description" rows="2" placeholder="Brief, compelling summary for Google search results..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"><?= esc(old('meta_description', $property->meta_description ?? '')) ?></textarea>
</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">5. Media & Tours</h3>
            

<?php if(isset($existingImages) && !empty($existingImages)): ?>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php foreach($existingImages as $img): ?>
                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                        <img src="<?= base_url($img->image_path) ?>" class="w-full h-full object-cover">
                        <?php if($img->is_primary): ?>
                            <span class="absolute top-2 left-2 bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">COVER</span>
                        <?php endif; ?>
                        <a href="<?= base_url('admin/properties/delete-image/' . $img->id) ?>" onclick="return confirm('Delete this image?');" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-trash text-xs"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="text-xs text-gray-500 mt-2">Uploading new images below will add them to this gallery.</p>
        </div>
    <?php endif; ?>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Property Images (Select Multiple) <span class="text-red-500">*</span></label>
                    <div class="relative flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-images text-3xl text-gray-400 mb-2"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2 py-1 shadow-sm border border-gray-200">
                                    <span>Upload files</span>
                                    <input id="images" name="images[]" type="file" multiple accept="image/jpeg, image/png, image/webp" class="sr-only">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="imagePreviewContainer" class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 hidden"></div>
                </div>

                <hr class="border-gray-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">YouTube Video URL</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50"><i class="fab fa-youtube text-red-500"></i></span>
                            <input type="url" name="video_url" id="youtube_url" placeholder="https://youtube.com/watch?v=..." value="<?= old('youtube_url', $property->youtube_url ?? '') ?>" class="flex-1 w-full px-4 py-2 rounded-r-md border border-gray-300 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Matterport / 3D Tour URL</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50"><i class="fas fa-vr-cardboard text-indigo-500"></i></span>
                            <input type="url" name="virtual_tour_url" value="<?= old('virtual_tour_url', $property->virtual_tour_url ?? '') ?>" placeholder="https://my.matterport.com/show/?m=..." class="flex-1 w-full px-4 py-2 rounded-r-md border border-gray-300 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                </div>

                <div id="videoPreviewContainer" class="hidden aspect-video w-full md:w-2/3 lg:w-1/2 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-900 mt-4">
                    <iframe id="youtubeIframe" class="w-full h-full" src="" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900">6. Amenities</h3>
                <label class="flex items-center cursor-pointer text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors bg-indigo-50 px-3 py-1.5 rounded-md">
                    <input type="checkbox" id="selectAllAmenities" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2"> Select All
                </label>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php if(!empty($amenities)): ?>
                    <?php foreach($amenities as $amenity): ?>
                        <label class="relative flex items-start p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-indigo-50 transition-all group has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500">
                            <div class="flex items-center h-5">
<input type="checkbox" name="amenities[]" value="<?= $amenity->id ?>" 
    <?= (isset($selectedAmenities) && in_array($amenity->id, $selectedAmenities)) ? 'checked' : '' ?> 
    class="amenity-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3 text-sm flex items-center gap-2">
                                <?php if($amenity->icon): ?>
                                    <i class="<?= esc($amenity->icon) ?> text-gray-400 group-has-[:checked]:text-indigo-600"></i>
                                <?php endif; ?>
                                <span class="font-medium text-gray-700 group-has-[:checked]:text-indigo-900"><?= esc($amenity->name) ?></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="sticky bottom-4 z-50 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] border border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-500 hidden md:block"><i class="fas fa-info-circle"></i> Check all fields before publishing.</p>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="submit" name="action" value="draft" class="flex-1 sm:flex-none px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all shadow-sm">
                    <i class="fas fa-save mr-2"></i> Save as Draft
                </button>
                <button type="submit" name="action" value="publish" class="flex-1 sm:flex-none px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i> Publish Listing
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. INITIALIZE WYSIWYG EDITOR ---
        var quill = new Quill('#quillEditor', {
            theme: 'snow',
            placeholder: 'Highlight the key features and selling points...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [2, 3, false] }],
                    ['clean']
                ]
            }
        });

        // Sync Quill HTML to hidden input before form submission
        document.getElementById('propertyForm').addEventListener('submit', function(e) {
            // Get the HTML content from Quill
            var htmlData = document.querySelector('.ql-editor').innerHTML;
            // Prevent submission if it's completely empty
            if (htmlData === '<p><br></p>') {
                alert('Please enter a description.');
                e.preventDefault();
                return false;
            }
            document.getElementById('hiddenDescription').value = htmlData;
        });

      // --- 2. LIVE PRICING AUTO-CALCULATOR (BUG FIXED) ---
// --- 2. DYNAMIC PRICING MATRIX LOGIC ---
        const pricingContainer = document.getElementById('pricingContainer');
        const addPriceBtn = document.getElementById('addPriceBtn');
        let priceIndex = document.querySelectorAll('.pricing-row').length;

        // Add new row
        addPriceBtn.addEventListener('click', function() {
            // Clone the first row
            const firstRow = pricingContainer.querySelector('.pricing-row');
            const newRow = firstRow.cloneNode(true);
            
            // Update the name attributes with the new index (prices[1][price], etc.)
            newRow.querySelectorAll('input, select').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${priceIndex}]`);
                if(input.tagName === 'INPUT') input.value = ''; // Clear cloned values
            });
            
            // Hide the badge, show the delete button
            newRow.querySelector('.discount-badge').style.display = 'none';
            newRow.querySelector('.remove-price-btn').classList.remove('hidden');
            
            pricingContainer.appendChild(newRow);
            priceIndex++;
        });

        // Event Delegation for Remove Buttons and Live Calculations
        pricingContainer.addEventListener('input', calculateRowDiscount);
        pricingContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-price-btn')) {
                e.target.closest('.pricing-row').remove();
            }
        });

        function calculateRowDiscount(e) {
            if (e.target.classList.contains('base-price') || e.target.classList.contains('discount-price')) {
                const row = e.target.closest('.pricing-row');
                const base = parseFloat(row.querySelector('.base-price').value) || 0;
                const discount = parseFloat(row.querySelector('.discount-price').value) || 0;
                const badge = row.querySelector('.discount-badge');
                
                if (base > 0 && discount > 0 && discount < base) {
                    const percentage = Math.round(((base - discount) / base) * 100);
                    row.querySelector('.discount-percent').innerText = percentage;
                    badge.style.display = 'inline-flex'; 
                } else {
                    badge.style.display = 'none';
                }
            }
        }
        
        // Trigger calc on load for pre-filled edit data
        document.querySelectorAll('.pricing-row').forEach(row => {
            row.querySelector('.base-price').dispatchEvent(new Event('input', { bubbles: true }));
        });

        // --- 3. AMENITIES SELECT ALL ---
        const selectAllBtn = document.getElementById('selectAllAmenities');
        const checkboxes = document.querySelectorAll('.amenity-checkbox');
        selectAllBtn.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAllBtn.checked);
        });

        // --- 4. YOUTUBE LIVE PREVIEW ---
        const ytInput = document.getElementById('youtube_url');
        const ytContainer = document.getElementById('videoPreviewContainer');
        const ytIframe = document.getElementById('youtubeIframe');

        ytInput.addEventListener('input', function() {
            const url = this.value;
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);

            if (match && match[2].length === 11) {
                ytIframe.src = 'https://www.youtube.com/embed/' + match[2];
                ytContainer.classList.remove('hidden');
            } else {
                ytIframe.src = '';
                ytContainer.classList.add('hidden');
            }
        });

        // --- 5. MULTIPLE IMAGE PREVIEW & DELETE ---
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('imagePreviewContainer');
        let dt = new DataTransfer();

        imageInput.addEventListener('change', function(e) {
            for (let i = 0; i < this.files.length; i++) {
                dt.items.add(this.files[i]);
            }
            renderImagePreviews();
        });

        function renderImagePreviews() {
            previewContainer.innerHTML = '';
            const files = dt.files;
            if(files.length > 0) {
                previewContainer.classList.remove('hidden');
            } else {
                previewContainer.classList.add('hidden');
            }

            Array.from(files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity';
                    btn.innerHTML = '<i class="fas fa-times text-xs"></i>';
                    
                    btn.onclick = function() {
                        dt.items.remove(index);
                        renderImagePreviews();
                    };

                    div.appendChild(img);
                    div.appendChild(btn);
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
            imageInput.files = dt.files; // Sync back to input
        }
    });
</script>
<?= $this->endSection() ?>