<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Property Listings</h2>
            <p class="text-gray-500 mt-1 text-sm">Manage, edit, and monitor all properties on the platform.</p>
        </div>
        <a href="<?= base_url('admin/properties/create') ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-bold shadow-sm transition-colors">
            <i class="fas fa-plus"></i> Add New Property
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
            <p class="text-sm text-green-700 font-bold"><?= esc(session()->getFlashdata('success')) ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Image</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Property Details</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Price & Purpose</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <?php if (empty($properties)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <i class="fas fa-home text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">No properties found</h3>
                                <p class="text-gray-500 text-sm">Get started by creating your first listing.</p>
                                <a href="<?= base_url('admin/properties/create') ?>" class="mt-4 inline-block text-indigo-600 font-bold hover:text-indigo-800">Add Listing &rarr;</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($properties as $property): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($property->image_path): ?>
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 overflow-hidden shadow-sm border border-gray-200">
                                            <img src="<?= base_url($property->image_path) ?>" alt="Thumbnail" class="h-full w-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 truncate max-w-xs" title="<?= esc($property->title) ?>">
                                        <?= esc($property->title) ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                        <span class="font-semibold text-gray-700"><?= esc($property->property_type) ?></span>
                                        &bull;
                                        <span><?= $property->bedrooms ?> Beds, <?= $property->bathrooms ?> Baths</span>
                                    </div>
                                </td>

                             <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="mb-2">
                                        <?php if ($property->purpose === 'sale'): ?>
                                            <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-xs font-semibold border border-blue-100">For Sale</span>
                                        <?php elseif ($property->purpose === 'shortlet'): ?>
                                            <span class="text-purple-600 bg-purple-50 px-2 py-0.5 rounded text-xs font-semibold border border-purple-100">Shortlet</span>
                                        <?php else: ?>
                                            <span class="text-teal-600 bg-teal-50 px-2 py-0.5 rounded text-xs font-semibold border border-teal-100">For Rent</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <?php if (!empty($property->prices)): ?>
                                            <?php foreach (array_slice($property->prices, 0, 3) as $priceObj): ?>
                                                <div class="text-sm font-bold text-gray-900">
                                                    <?= esc(config('Site')->currency) ?><?= number_format($priceObj->price) ?> 
                                                    <span class="text-xs font-normal text-gray-500 text-opacity-80">/ <?= esc($priceObj->price_unit) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                            
                                            <?php if (count($property->prices) > 3): ?>
                                                <div class="text-[10px] uppercase font-bold text-indigo-500 pt-1">
                                                    + <?= count($property->prices) - 3 ?> more options
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-400 italic">No price set</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($property->location) ?></div>
                                    <div class="text-xs text-gray-500"><?= esc($property->city) ?></div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($property->status === 'active'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                                        </span>
                                    <?php elseif ($property->status === 'pending'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft/Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Sold
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
        <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors p-1" title="View Public Listing">
            <i class="fas fa-external-link-alt"></i>
        </a>
        <a href="<?= base_url('admin/properties/edit/' . $property->id) ?>" class="text-gray-400 hover:text-indigo-600 transition-colors p-1" title="Edit Property">
            <i class="fas fa-edit"></i>
        </a>
        <a href="<?= base_url('admin/properties/delete/' . $property->id) ?>" onclick="return confirm('Are you sure you want to permanently delete this property and all its images? This cannot be undone.');" class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Delete Property">
            <i class="fas fa-trash-alt"></i>
        </a>
    </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
        
        <?php if (!empty($properties) && $pager->getPageCount() > 1): ?>
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>