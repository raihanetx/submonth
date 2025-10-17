<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --primary-color: #6D28D9; --primary-color-darker: #5B21B6; }
        body { font-family: 'Inter', sans-serif; }
        .form-input, .form-select, .form-textarea { width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.6rem 0.8rem; transition: all 0.2s ease-in-out; background-color: #F9FAFB; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px #E9D5FF; outline: none; background-color: white; }
        .btn { padding: 0.6rem 1.2rem; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-primary { background-color: var(--primary-color); color: white; } .btn-primary:hover { background-color: var(--primary-color-darker); }
        .btn-secondary { background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db; } .btn-secondary:hover { background-color: #e5e7eb; }
        .btn-danger { background-color: #fee2e2; color: #b91c1c; } .btn-danger:hover { background-color: #fecaca; color: #991b1b; }
        .btn-success { background-color: #dcfce7; color: #166534; } .btn-success:hover { background-color: #bbf7d0; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.875rem; }
        .tab { padding: 0.75rem 1rem; font-weight: 600; color: #4b5563; border-bottom: 3px solid transparent; }
        .tab-active { color: var(--primary-color); border-bottom-color: var(--primary-color); }
        .stats-filter-btn { padding: 0.5rem 1rem; border-radius: 9999px; font-weight: 500; transition: all 0.2s; border: 1px solid transparent; }
        .stats-filter-btn.active { background-color: var(--primary-color); color: white; }
        .stats-filter-btn:not(.active) { background-color: #f3f4f6; color: #374151; }
        .stats-filter-btn:not(.active):hover { background-color: #e5e7eb; }
        .card { background-color: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05); border: 1px solid #e5e7eb; }
        .hidden { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-4 md:p-6" x-data="manualEmailManager()">

        <?php if ($category_to_manage !== null): ?>
            <!-- Product Management View -->
            <a href="admin.php?view=categories" class="inline-flex items-center gap-2 mb-6 text-gray-600 font-semibold hover:text-[var(--primary-color)] transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Back to Categories
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Products: <span class="text-[var(--primary-color)]"><?= htmlspecialchars($category_to_manage['name']) ?></span></h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <div class="lg:col-span-1 card p-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Add New Product</h2>
                    <form action="api.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="add_product">
                        <input type="hidden" name="category_name" value="<?= htmlspecialchars($category_to_manage['name']) ?>">
                        <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Product Name</label><input type="text" name="name" class="form-input" required></div>
                        <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Short Description</label><textarea name="description" class="form-textarea" rows="3" required></textarea></div>
                        <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Long Description</label><textarea name="long_description" class="form-textarea" rows="5"></textarea><p class="text-xs text-gray-500 mt-1">Use **text** to make text bold.</p></div>
                        <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Pricing Type</label><select id="pricing-type" class="form-select"><option value="single">Single Price</option><option value="multiple">Multiple Durations</option></select></div>
                        <div id="single-price-container"><label class="block mb-1.5 font-medium text-gray-700 text-sm">Price (৳)</label><input type="number" name="price" step="0.01" class="form-input" value="0.00"></div>
                        <div id="multiple-pricing-container" class="space-y-3 hidden"><label class="block font-medium text-gray-700 text-sm">Durations & Prices</label><div id="duration-fields"></div><button type="button" id="add-duration-btn" class="btn btn-secondary btn-sm"><i class="fa-solid fa-plus"></i> Add Duration</button></div>
                        <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Product Image</label><input type="file" name="image" class="form-input" accept="image/*"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Stock Status</label><select name="stock_out" class="form-select"><option value="false">In Stock</option><option value="true">Out of Stock</option></select></div>
                            <div class="pt-7"><label class="flex items-center gap-2"><input type="checkbox" name="featured" id="featured" value="true" class="h-4 w-4 rounded border-gray-300 text-[var(--primary-color)] focus:ring-[var(--primary-color)]"> Featured?</label></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full mt-2"><i class="fa-solid fa-circle-plus"></i>Add Product</button>
                    </form>
                </div>

                <div class="lg:col-span-2 card p-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Existing Products</h2>
                    <div class="space-y-3">
                        <?php if (empty($category_to_manage['products'])): ?>
                            <p class="text-gray-500 text-center py-10">No products found in this category.</p>
                        <?php else: ?>
                            <?php foreach ($category_to_manage['products'] as $product): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-wrap gap-4">
                                <div class="flex items-center gap-4 flex-grow">
                                    <img src="<?= htmlspecialchars($product['image'] ? $product['image'] : 'https://via.placeholder.com/64/E9D5FF/5B21B6?text=N/A') ?>" class="w-16 h-16 object-cover rounded-md bg-gray-200">
                                    <div>
                                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($product['name']) ?></p>
                                        <p class="text-sm text-gray-600 font-semibold text-[var(--primary-color)]">৳<?= isset($product['pricing'][0]) ? number_format($product['pricing'][0]['price'], 2) : '0.00' ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="edit_product.php?category=<?= urlencode($category_to_manage['name']) ?>&id=<?= $product['id'] ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pencil"></i> Edit</a>
                                    <form action="api.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        <input type="hidden" name="action" value="delete_product">
                                        <input type="hidden" name="category_name" value="<?= htmlspecialchars($category_to_manage['name']) ?>">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Main Dashboard View -->
            <header class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
                <a href="logout.php" class="btn btn-secondary"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </header>
            <div class="card">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex gap-4 px-6 overflow-x-auto">
                        <a href="admin.php?view=dashboard" class="tab flex-shrink-0 <?= $current_view === 'dashboard' ? 'tab-active' : '' ?>"><i class="fa-solid fa-table-columns mr-2"></i>Dashboard</a>
                        <a href="admin.php?view=categories" class="tab flex-shrink-0 <?= $current_view === 'categories' ? 'tab-active' : '' ?>"><i class="fa-solid fa-list mr-2"></i>Categories</a>
                        <a href="admin.php?view=hotdeals" class="tab flex-shrink-0 <?= $current_view === 'hotdeals' ? 'tab-active' : '' ?>"><i class="fa-solid fa-fire mr-2"></i>Hot Deals</a>
                        <a href="admin.php?view=orders" class="tab flex-shrink-0 <?= $current_view === 'orders' ? 'tab-active' : '' ?>"><i class="fa-solid fa-bag-shopping mr-2"></i>Orders <?php if ($pending_orders_count > 0): ?><span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full px-2 py-0.5"><?= $pending_orders_count ?></span><?php endif; ?></a>
                        <a href="admin.php?view=reviews" class="tab flex-shrink-0 <?= $current_view === 'reviews' ? 'tab-active' : '' ?>"><i class="fa-solid fa-star mr-2"></i>Reviews <span class="ml-2 bg-purple-100 text-purple-700 text-xs font-bold rounded-full px-2 py-0.5"><?= count($all_reviews) ?></span></a>
                        <a href="admin.php?view=pages" class="tab flex-shrink-0 <?= $current_view === 'pages' ? 'tab-active' : '' ?>"><i class="fa-solid fa-file-lines mr-2"></i>Pages</a>
                        <a href="admin.php?view=settings" class="tab flex-shrink-0 <?= $current_view === 'settings' ? 'tab-active' : '' ?>"><i class="fa-solid fa-gear mr-2"></i>Settings</a>
                    </nav>
                </div>
                <div style="display: <?= $current_view === 'dashboard' ? 'block' : 'none' ?>">
                    <?php include 'dashboard.php'; ?>
                </div>
                <div style="display: <?= $current_view === 'categories' ? 'block' : 'none' ?>">
                    <?php include 'categories.php'; ?>
                </div>
                <div style="display: <?= $current_view === 'hotdeals' ? 'block' : 'none' ?>">
                    <?php include 'hotdeals.php'; ?>
                </div>
                <div style="display: <?= $current_view === 'orders' ? 'block' : 'none' ?>">
                    <?php include 'orders.php'; ?>
                </div>
                <div style="display: <?= $current_view === 'reviews' ? 'block' : 'none' ?>">
                    <?php include 'reviews.php'; ?>
                </div>
                <div style="display: <?= $current_view === 'pages' ? 'block' : 'none' ?>">
                    <?php include 'pages.php'; ?>
                </div>
                <div style="display: <?= $current_view === 'settings' ? 'block' : 'none' ?>">
                    <?php include 'settings.php'; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Manual Email Modal -->
        <div x-show="isModalOpen" x-cloak
            @keydown.escape.window="closeModal()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div @click.away="closeModal()" class="bg-white rounded-lg shadow-xl w-full max-w-lg">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Send Access Details for Order #<span x-text="currentOrderId"></span></h3>
                </div>
                <form action="api.php" method="POST" onsubmit="return confirm('Are you sure you want to send this email?');">
                    <div class="p-6 space-y-4">
                        <input type="hidden" name="action" value="send_manual_email">
                        <input type="hidden" name="order_id" :value="currentOrderId">
                        <input type="hidden" name="customer_email" :value="currentCustomerEmail">
                        <div>
                            <label class="block mb-1.5 font-medium text-gray-700 text-sm">Access Details & Information</label>
                            <textarea name="access_details" class="form-textarea" rows="6" placeholder="Enter login details, product keys, download links, instructions, etc." required></textarea>
                            <p class="text-xs text-gray-500 mt-1">The customer will receive this text in their confirmation email.</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-lg">
                        <button type="button" @click="closeModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Send Email</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

<script>
const allStats = { today: <?= json_encode($stats_today) ?>, '7days': <?= json_encode($stats_7_days) ?>, '30days': <?= json_encode($stats_30_days) ?>, '6months': <?= json_encode($stats_6_months) ?>, 'all': <?= json_encode($stats_all_time) ?> };
function updateStatsDisplay(period) { const stats = allStats[period]; const container = document.getElementById('stats-display-container'); container.innerHTML = `<div class="bg-gray-50 p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center"><i class="fa-solid fa-dollar-sign text-2xl text-blue-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Revenue</p><p class="text-xl font-bold text-gray-800">৳${stats.total_revenue.toFixed(2)}</p></div></div><div class="bg-gray-50 p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center"><i class="fa-solid fa-box-archive text-2xl text-purple-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Orders</p><p class="text-xl font-bold text-gray-800">${stats.total_orders}</p></div></div><div class="bg-gray-50 p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center"><i class="fa-solid fa-circle-check text-2xl text-green-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Confirmed</p><p class="text-xl font-bold text-gray-800">${stats.confirmed_orders}</p></div></div><div class="bg-gray-50 p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center"><i class="fa-solid fa-clock-rotate-left text-2xl text-yellow-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Pending</p><p class="text-xl font-bold text-gray-800">${stats.pending_orders}</p></div></div><div class="bg-gray-50 p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center"><i class="fa-solid fa-ban text-2xl text-red-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Cancelled</p><p class="text-xl font-bold text-gray-800">${stats.cancelled_orders}</p></div></div>`; }
document.addEventListener('DOMContentLoaded', function() {
    const pricingType = document.getElementById('pricing-type'); if (pricingType) { const singlePriceContainer = document.getElementById('single-price-container'); const multiplePricingContainer = document.getElementById('multiple-pricing-container'); const addDurationBtn = document.getElementById('add-duration-btn'); const durationFields = document.getElementById('duration-fields'); pricingType.addEventListener('change', function() { if (this.value === 'single') { singlePriceContainer.classList.remove('hidden'); multiplePricingContainer.classList.add('hidden'); } else { singlePriceContainer.classList.add('hidden'); multiplePricingContainer.classList.remove('hidden'); if (durationFields.children.length === 0) addDurationField(); } }); addDurationBtn.addEventListener('click', addDurationField); function addDurationField() { const fieldGroup = document.createElement('div'); fieldGroup.className = 'flex items-center gap-2 mb-2'; fieldGroup.innerHTML = `<input type="text" name="durations[]" class="form-input" placeholder="Duration (e.g., 1 Year)" required><input type="number" name="duration_prices[]" step="0.01" class="form-input" placeholder="Price" required><button type="button" class="btn btn-danger btn-sm remove-duration-btn"><i class="fa-solid fa-trash-can"></i></button>`; durationFields.appendChild(fieldGroup); } durationFields.addEventListener('click', function(e) { if (e.target && e.target.closest('.remove-duration-btn')) { e.target.closest('.flex').remove(); } }); }
    const statsFilterContainer = document.getElementById('stats-filter-container'); if (statsFilterContainer) { updateStatsDisplay('all'); statsFilterContainer.addEventListener('click', function(e) { if (e.target.matches('.stats-filter-btn')) { this.querySelectorAll('.stats-filter-btn').forEach(btn => btn.classList.remove('active')); e.target.classList.add('active'); const period = e.target.dataset.period; updateStatsDisplay(period); } }); }
    const couponScope = document.getElementById('coupon_scope'); if (couponScope) { const categoryContainer = document.getElementById('scope_category_container'); const productContainer = document.getElementById('scope_product_container'); couponScope.addEventListener('change', function() { categoryContainer.classList.add('hidden'); productContainer.classList.add('hidden'); if (this.value === 'category') categoryContainer.classList.remove('hidden'); if (this.value === 'single_product') productContainer.classList.remove('hidden'); }); }
    const toggleBtn = document.getElementById('toggle_password_btn');
    const passwordField = document.getElementById('new_password_field');
    if (toggleBtn && passwordField) {
        toggleBtn.addEventListener('click', function() {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            this.textContent = isPassword ? 'Hide' : 'Show';
        });
    }
});

function ordersManager() {
    return {
        allOrders: <?php echo json_encode($all_orders_data_raw); ?>,
        searchQuery: '',
        currentPage: 1,
        ordersPerPage: 20,
        get filteredOrders() {
            if (this.searchQuery.trim() === '') { return this.allOrders; }
            const query = this.searchQuery.toLowerCase().trim();
            return this.allOrders.filter(order => {
                const productNames = (order.items || []).map(item => item.name).join(' ').toLowerCase();
                const searchableText = `${order.order_id} ${order.customer.name} ${order.customer.phone} ${order.customer.email} ${productNames}`.toLowerCase();
                return searchableText.includes(query);
            });
        },
        get totalPages() { return Math.ceil(this.filteredOrders.length / this.ordersPerPage); },
        get paginatedOrders() {
            const start = (this.currentPage - 1) * this.ordersPerPage;
            const end = start + this.ordersPerPage;
            return this.filteredOrders.slice(start, end);
        },
        nextPage() { if (this.currentPage < this.totalPages) { this.currentPage++; } },
        prevPage() { if (this.currentPage > 1) { this.currentPage--; } },
        init() { this.$watch('searchQuery', () => { this.currentPage = 1; }); }
    }
}

function manualEmailManager() {
    return {
        isModalOpen: false,
        currentOrderId: null,
        currentCustomerEmail: null,
        openModal(orderId, customerEmail) {
            this.currentOrderId = orderId;
            this.currentCustomerEmail = customerEmail;
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
            this.currentOrderId = null;
            this.currentCustomerEmail = null;
        }
    }
}
</script>
</body>
</html>