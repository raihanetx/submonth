<div id="view-hotdeals" class="p-6">
    <form action="api.php" method="POST">
        <input type="hidden" name="action" value="update_hot_deals">
        <div class="bg-white p-6 rounded-lg border mb-6">
             <h3 class="text-lg font-semibold mb-4 text-gray-800">Hot Deals Settings</h3>
             <div>
                <label for="hot_deals_speed" class="block mb-1.5 font-medium text-gray-700 text-sm">Scroll Speed (in seconds)</label>
                <input type="number" id="hot_deals_speed" name="hot_deals_speed" class="form-input max-w-xs" value="<?= htmlspecialchars($site_config['hot_deals_speed'] ?? 40) ?>" placeholder="e.g., 40">
                <p class="text-xs text-gray-500 mt-1">A higher number means a slower scroll.</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg border">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Select & Customize Hot Deals Products</h3>
             <div class="space-y-4 max-h-[40rem] overflow-y-auto p-2 border rounded-md">
            <?php
                $selected_deals_map = array_column($all_hotdeals_data, null, 'productId');
                foreach ($all_products_for_js as $product):
                    $product_id = $product['id'];
                    $is_selected = isset($selected_deals_map[$product_id]);
                    $custom_title = $is_selected ? $selected_deals_map[$product_id]['customTitle'] : '';
            ?>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 transition-all" x-data="{ selected: <?= $is_selected ? 'true' : 'false' ?> }">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" :checked="selected" @change="selected = !selected" name="selected_deals[]" value="<?= htmlspecialchars($product_id) ?>" class="h-5 w-5 rounded border-gray-300 text-[var(--primary-color)] focus:ring-[var(--primary-color)] flex-shrink-0">
                        <label class="font-semibold text-gray-800"><?= htmlspecialchars($product['name']) ?></label>
                    </div>
                    <div x-show="selected" x-cloak class="mt-4 pl-8 space-y-3 border-l-2 border-purple-200 ml-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Custom Title (Optional)</label>
                            <input type="text" name="custom_titles[<?= htmlspecialchars($product_id) ?>]" value="<?= htmlspecialchars($custom_title) ?>" class="form-input" placeholder="Overrides product name">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Hot Deals Configuration</button>
        </div>
    </form>
</div>