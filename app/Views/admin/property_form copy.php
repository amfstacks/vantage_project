<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto pb-12">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Add New Property</h2>
            <p class="text-gray-500 mt-1">Fill in the details, upload media, and select amenities.</p>
        </div>
        <a href="<?= base_url('admin/properties') ?>" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
            &larr; Back to Properties
        </a>
    </div>

    <form action="<?= base_url('admin/properties/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="propertyForm">
        <?= csrf_field() ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">1. Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g., Exquisite 5 Bedroom Mansion" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
                    <select name="purpose" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">Select Purpose...</option>
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent (Yearly)</option>
                        <option value="shortlet">Shortlet (Daily)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                    <select name="property_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="Apartment">Apartment</option>
                        <option value="Detached Duplex">Detached Duplex</option>
                        <option value="Semi-Detached Duplex">Semi-Detached Duplex</option>
                        <option value="Terrace">Terrace</option>
                        <option value="Land">Land</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (<?= esc(config('Site')->currency) ?>) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required placeholder="85000000" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location / District <span class="text-red-500">*</span></label>
                    <input type="text" name="location" required placeholder="e.g., Guzape, Asokoro" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                    <input type="number" name="bedrooms" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                    <input type="number" name="bathrooms" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Detailed Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required placeholder="Highlight the key features and selling points..." 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">2. Property Media</h3>
            
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
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 5MB each</p>
                        </div>
                    </div>

                    <div id="imagePreviewContainer" class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 hidden">
                        </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">YouTube Video URL (Optional)</label>
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                            <i class="fab fa-youtube text-red-500"></i>
                        </span>
                        <input type="url" name="video_url" id="youtube_url" placeholder="https://www.youtube.com/watch?v=..." 
                               class="flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div id="videoPreviewContainer" class="mt-4 hidden aspect-video w-full md:w-2/3 lg:w-1/2 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-900">
                        <iframe id="youtubeIframe" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900">3. Amenities & Features</h3>
                
                <label class="flex items-center cursor-pointer text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                    <input type="checkbox" id="selectAllAmenities" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2">
                    Select All
                </label>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php if(!empty($amenities)): ?>
                    <?php foreach($amenities as $amenity): ?>
                        <label class="relative flex items-start p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all group has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="amenities[]" value="<?= $amenity->id ?>" class="amenity-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3 text-sm flex items-center gap-2">
                                <?php if($amenity->icon): ?>
                                    <i class="<?= esc($amenity->icon) ?> text-gray-400 group-hover:text-indigo-500 group-has-[:checked]:text-indigo-600 transition-colors"></i>
                                <?php endif; ?>
                                <span class="font-medium text-gray-700 group-has-[:checked]:text-indigo-900"><?= esc($amenity->name) ?></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-sm col-span-full">No amenities added yet. Please add them in the Amenities section first.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fas fa-save"></i> Save Property Listing
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. AMENITIES: SELECT ALL LOGIC ---
        const selectAllBtn = document.getElementById('selectAllAmenities');
        const checkboxes = document.querySelectorAll('.amenity-checkbox');

        selectAllBtn.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAllBtn.checked;
            });
        });

        // --- 2. YOUTUBE LIVE PREVIEW LOGIC ---
        const ytInput = document.getElementById('youtube_url');
        const ytContainer = document.getElementById('videoPreviewContainer');
        const ytIframe = document.getElementById('youtubeIframe');

        ytInput.addEventListener('input', function() {
            const url = this.value;
            // Regex to extract the 11-character YouTube video ID
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);

            if (match && match[2].length === 11) {
                const videoId = match[2];
                ytIframe.src = 'https://www.youtube.com/embed/' + videoId;
                ytContainer.classList.remove('hidden');
            } else {
                ytIframe.src = '';
                ytContainer.classList.add('hidden');
            }
        });

        // --- 3. MULTIPLE IMAGE PREVIEW & DELETION LOGIC ---
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('imagePreviewContainer');
        
        // We use a DataTransfer object to hold the files. This allows us to remove 
        // specific files and resync them with the native HTML input before submitting.
        let dt = new DataTransfer();

        imageInput.addEventListener('change', function(e) {
            // Append newly selected files to our DataTransfer object
            for (let i = 0; i < this.files.length; i++) {
                dt.items.add(this.files[i]);
            }
            // Update the UI
            renderImagePreviews();
        });

        function renderImagePreviews() {
            // Clear current previews
            previewContainer.innerHTML = '';
            
            const files = dt.files;
            if(files.length > 0) {
                previewContainer.classList.remove('hidden');
            } else {
                previewContainer.classList.add('hidden');
            }

            // Loop through the DataTransfer files and create thumbnail elements
            Array.from(files).forEach((file, index) => {
                // Ensure it's an image
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create the wrapper
                    const div = document.createElement('div');
                    div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm';
                    
                    // Create the image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    
                    // Create the delete button
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity';
                    btn.innerHTML = '<i class="fas fa-times text-xs"></i>';
                    
                    // Delete logic
                    btn.onclick = function() {
                        // Remove from DataTransfer array
                        dt.items.remove(index);
                        // Re-render
                        renderImagePreviews();
                    };

                    div.appendChild(img);
                    div.appendChild(btn);
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });

            // CRITICAL: Sync the DataTransfer files back to the native input element.
            // This ensures when the form is submitted via PHP, only the non-deleted files are sent.
            imageInput.files = dt.files;
        }
    });
</script>

<?= $this->endSection() ?>