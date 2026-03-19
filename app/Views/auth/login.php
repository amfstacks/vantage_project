<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex">
    
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-16 lg:p-24 bg-white shadow-xl z-10">
        <div class="w-full max-w-md">
            
            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Welcome Back</h1>
                <p class="text-gray-500 mt-2 text-sm">Sign in to your admin dashboard to manage properties.</p>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                <?= esc(session()->getFlashdata('error')) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login/attempt') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required autofocus 
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required 
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition duration-150 ease-in-out">
                        Sign In to Dashboard
                    </button>
                </div>
            </form>
            
        </div>
    </div>

    <div class="hidden lg:block lg:w-1/2 relative bg-gray-900">
        <img class="absolute inset-0 h-full w-full object-cover opacity-60" 
             src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
             alt="Luxury Real Estate">
        
        <div class="absolute inset-0 flex flex-col justify-center items-center p-12 text-center">
            <h2 class="text-4xl font-extrabold text-white tracking-tight sm:text-5xl">
                Manage Prime Listings.
            </h2>
            <p class="mt-4 text-lg text-gray-200 max-w-lg">
                The centralized command center for approving properties, managing sellers, and overseeing the platform.
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>