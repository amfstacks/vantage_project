<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Amenity Management</h2>
        <p class="text-gray-500 mt-1">Add and manage the features available for property listings.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
            <p class="text-sm text-green-700 font-medium"><?= esc(session()->getFlashdata('success')) ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mt-0.5"></i>
            <div class="text-sm text-red-700 font-medium"><?= session()->getFlashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 id="form-title" class="text-lg font-bold text-gray-900 mb-4">Add New Amenity</h3>
                
                <form action="<?= base_url('admin/amenities/save') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="amenity_id" value="">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Amenity Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="amenity_name" required placeholder="e.g., 24/7 Security"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors sm:text-sm">
                    </div>

                   <div>
    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">FontAwesome Class (Optional)</label>
    <input type="text" name="icon" id="amenity_icon" placeholder="e.g., fas fa-wifi, fab fa-netflix"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors sm:text-sm">
    <p class="text-xs text-gray-400 mt-1">Search free icons at <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-yellow-500 hover:underline">fontawesome.com</a></p>
</div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit" id="submit-btn" class="flex-1 bg-gray-900 text-white py-2 px-4 rounded-lg font-semibold hover:bg-gray-800 transition-colors shadow-sm text-sm">
                            Save Amenity
                        </button>
                        <button type="button" id="cancel-btn" onclick="resetForm()" class="hidden px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition-colors text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold w-16 text-center">Icon</th>
                                <th class="px-6 py-4 font-semibold">Amenity Name</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            
                            <?php if (empty($amenities)): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                        No amenities added yet.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($amenities as $amenity): ?>
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                       <td class="px-6 py-4 text-center text-yellow-500 text-lg">
    <?php if ($amenity->icon): ?>
        <i class="<?= esc($amenity->icon) ?>"></i>
    <?php else: ?>
        <i class="fas fa-check text-gray-300"></i>
    <?php endif; ?>
</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <?= esc($amenity->name) ?>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button onclick="editAmenity(<?= $amenity->id ?>, '<?= esc(addslashes($amenity->name)) ?>', '<?= esc(addslashes($amenity->icon)) ?>')" 
                                                    class="text-gray-400 hover:text-yellow-600 transition-colors p-1" title="Edit">
                                               <i class="fas fa-pen"></i>
                                            </button>
                                            
                                            <a href="<?= base_url('admin/amenities/delete/' . $amenity->id) ?>" 
                                               onclick="return confirm('Are you sure you want to delete this amenity? It will be removed from all properties.');"
                                               class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Delete">

                                                <i class="fas fa-trash" class="w-4 h-4 text-red"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function editAmenity(id, name, icon) {
        // Populate the form fields
        document.getElementById('amenity_id').value = id;
        document.getElementById('amenity_name').value = name;
        document.getElementById('amenity_icon').value = icon;
        
        // Update UI to show "Edit" state
        document.getElementById('form-title').innerText = 'Edit Amenity';
        document.getElementById('submit-btn').innerText = 'Update Amenity';
        document.getElementById('cancel-btn').classList.remove('hidden');
        
        // Scroll to form if on mobile
        document.getElementById('form-title').scrollIntoView({ behavior: 'smooth' });
    }

    function resetForm() {
        // Clear fields
        document.getElementById('amenity_id').value = '';
        document.getElementById('amenity_name').value = '';
        document.getElementById('amenity_icon').value = '';
        
        // Revert UI to "Add" state
        document.getElementById('form-title').innerText = 'Add New Amenity';
        document.getElementById('submit-btn').innerText = 'Save Amenity';
        document.getElementById('cancel-btn').classList.add('hidden');
    }
</script>
<?= $this->endSection() ?>