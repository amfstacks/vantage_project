<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back, <?= esc(session()->get('first_name') ?? 'Admin') ?>! 👋</h2>
            <p class="text-gray-500 mt-1">Here is a quick overview of your real estate platform today.</p>
        </div>
        <a href="<?= base_url('admin/properties/create') ?>" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-lg font-semibold transition-all shadow-sm">
            <i class="fas fa-plus w-4 h-4 text-center"></i> Add Listing
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Properties</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2"><?= number_format($totalProperties) ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Active Shortlets</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2"><?= number_format($activeShortlets) ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-yellow-600">
                    <i class="fas fa-key text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pending Approval</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2"><?= number_format($pendingApproval) ?></p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Sales Portfolio</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-2 truncate max-w-[120px]" title="<?= number_format($salesPortfolio) ?>">
                        <?= esc(config('Site')->currency) ?><?= number_format($salesPortfolio) ?>
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Recent Platform Activity</h3>
            <a href="<?= base_url('admin/properties') ?>" class="text-sm font-bold text-yellow-600 hover:text-yellow-800 transition-colors">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Property Details</th>
                        <th class="px-6 py-4 font-semibold">Location</th>
                        <th class="px-6 py-4 font-semibold">Price</th>
                        <th class="px-6 py-4 font-semibold">Purpose</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    
                    <?php if (empty($recentProperties)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No properties added yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentProperties as $property): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 truncate max-w-xs"><?= esc($property->title) ?></div>
                                    <div class="text-gray-500 text-xs mt-1"><?= $property->bedrooms ?> Beds • <?= esc($property->property_type) ?></div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium"><?= esc($property->location) ?></td>
                                
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    <?php if (!empty($property->prices)): ?>
                                        <?= esc(config('Site')->currency) ?><?= number_format($property->prices[0]->price) ?> 
                                        <span class="text-xs text-gray-400 font-normal">/<?= esc($property->prices[0]->price_unit) ?></span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 font-normal italic">Not set</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <?php if ($property->purpose === 'sale'): ?>
                                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-md text-xs font-bold border border-blue-100">Sale</span>
                                    <?php elseif ($property->purpose === 'shortlet'): ?>
                                        <span class="bg-purple-50 text-yellow-700 px-3 py-1 rounded-md text-xs font-bold border border-purple-100">Shortlet</span>
                                    <?php else: ?>
                                        <span class="bg-teal-50 text-teal-700 px-3 py-1 rounded-md text-xs font-bold border border-teal-100">Rent</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?php if ($property->status === 'active'): ?>
                                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1 rounded-md text-xs font-bold border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-md text-xs font-bold border border-amber-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <a href="<?= base_url('admin/properties/edit/' . $property->id) ?>" class="text-gray-400 hover:text-yellow-600 transition-colors p-1" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
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
<?= $this->endSection() ?>