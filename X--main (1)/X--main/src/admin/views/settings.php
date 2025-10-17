<div id="view-settings" class="p-6 space-y-8 max-w-5xl mx-auto">
    <!-- Site Identity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <form action="api.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg border">
            <input type="hidden" name="action" value="update_site_logo">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Site Logo</h3>
            <?php if (!empty($site_config['site_logo']) && file_exists($site_config['site_logo'])): ?>
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-600 mb-2">Current Logo:</p>
                    <img src="<?= htmlspecialchars($site_config['site_logo']) ?>" class="h-10 bg-gray-200 p-1 rounded-md border shadow-sm">
                    <div class="flex items-center gap-2 mt-3">
                        <input type="checkbox" name="delete_site_logo" id="delete_site_logo" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="delete_site_logo" class="text-sm text-red-600 font-medium">Delete current logo</label>
                    </div>
                </div>
            <?php endif; ?>
            <div>
                <label class="block mb-1.5 font-medium text-gray-700 text-sm">Upload New Logo</label>
                <input type="file" name="site_logo" class="form-input" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Logo</button>
        </form>
        <form action="api.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg border">
            <input type="hidden" name="action" value="update_favicon">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Site Favicon</h3>
             <?php if (!empty($site_config['favicon']) && file_exists($site_config['favicon'])): ?>
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-600 mb-2">Current Favicon:</p>
                    <img src="<?= htmlspecialchars($site_config['favicon']) ?>" class="h-10 w-10 rounded-md border shadow-sm">
                    <div class="flex items-center gap-2 mt-3">
                        <input type="checkbox" name="delete_favicon" id="delete_favicon" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="delete_favicon" class="text-sm text-red-600 font-medium">Delete current favicon</label>
                    </div>
                </div>
            <?php endif; ?>
            <div>
                <label class="block mb-1.5 font-medium text-gray-700 text-sm">Upload New Favicon (.png, .ico)</label>
                <input type="file" name="favicon" class="form-input" accept="image/png, image/x-icon">
            </div>
            <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Favicon</button>
        </form>
    </div>

    <!-- Hero Banner Section -->
    <div class="bg-white p-6 rounded-lg border">
        <form action="api.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_hero_banner">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Hero Section Banners (Slider)</h3>
            <p class="text-sm text-gray-600 mb-4">You can upload up to 10 images for the homepage slider.</p>
             <div class="mb-6">
                <label for="hero_slider_interval" class="block mb-1.5 font-medium text-gray-700 text-sm">Slider Interval (in seconds)</label>
                <input type="number" id="hero_slider_interval" name="hero_slider_interval" class="form-input max-w-xs" value="<?= htmlspecialchars(($site_config['hero_slider_interval'] ?? 5000) / 1000) ?>" placeholder="e.g., 5">
            </div>
            <div class="space-y-6">
                <?php
                $current_banners = $site_config['hero_banner'] ?? [];
                for ($i = 0; $i < 10; $i++):
                    $banner_path = $current_banners[$i] ?? null;
                ?>
                <div class="p-4 border rounded-md bg-gray-50">
                    <label class="block font-medium text-gray-700 text-sm mb-2">Slider Image #<?= $i + 1 ?></label>
                    <?php if ($banner_path && file_exists($banner_path)): ?>
                        <div class="mb-2">
                            <img src="<?= htmlspecialchars($banner_path) ?>" class="max-h-24 rounded border">
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="delete_hero_banners[<?= $i ?>]" id="delete_banner_<?= $i ?>" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <label for="delete_banner_<?= $i ?>" class="text-sm text-red-600 font-medium">Delete this image</label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="hero_banners[<?= $i ?>]" class="form-input text-sm" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Uploading an image here will replace the existing one for this slot.</p>
                </div>
                <?php endfor; ?>
            </div>
            <button type="submit" class="btn btn-primary mt-6"><i class="fa-solid fa-floppy-disk"></i> Save Banner Settings</button>
        </form>
    </div>

    <!-- Email & SMTP Settings -->
    <form action="api.php" method="POST" class="bg-white p-6 rounded-lg border">
        <input type="hidden" name="action" value="update_smtp_settings">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Email & SMTP Settings</h3>
        <div class="space-y-4">
            <div>
                <label class="block mb-1.5 font-medium text-gray-700 text-sm">Admin Email Address</label>
                <input type="email" name="admin_email" class="form-input" value="<?= htmlspecialchars($site_config['smtp_settings']['admin_email'] ?? '') ?>" placeholder="e.g., admin@yourdomain.com">
                <p class="text-xs text-gray-500 mt-1">This email receives new order notifications and is used to send emails to customers.</p>
            </div>
            <div>
                <label class="block mb-1.5 font-medium text-gray-700 text-sm">Gmail App Password</label>
                <input type="password" name="app_password" class="form-input" placeholder="Leave blank to keep current password">
                <p class="text-xs text-gray-500 mt-1">Enter the 16-character App Password from your Google Account settings.</p>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-6"><i class="fa-solid fa-floppy-disk"></i> Save SMTP Settings</button>
    </form>

    <!-- Payment Gateway Settings -->
    <div class="bg-white p-6 rounded-lg border">
        <form action="api.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_payment_methods">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Gateway Settings</h3>
            <div class="space-y-6">
                <?php
                $payment_methods_config = $site_config['payment_methods'] ?? [];
                $default_methods = ['bKash', 'Nagad', 'Binance Pay'];
                foreach ($default_methods as $method_name):
                    $method_details = $payment_methods_config[$method_name] ?? [];
                    $is_binance = ($method_name === 'Binance Pay');
                    $id_field_name = $is_binance ? 'pay_id' : 'number';
                ?>
                <div class="p-4 border rounded-md bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-3"><?= htmlspecialchars($method_name) ?></h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1.5 font-medium text-gray-700 text-sm"><?= $is_binance ? 'Pay ID' : 'Number' ?></label>
                            <input type="text" name="payment_methods[<?= $method_name ?>][<?= $id_field_name ?>]" class="form-input" value="<?= htmlspecialchars($method_details[$id_field_name] ?? '') ?>">
                        </div>
                        <div>
                            <label class="block mb-1.5 font-medium text-gray-700 text-sm">Logo</label>
                            <?php if (!empty($method_details['logo_url']) && file_exists($method_details['logo_url'])): ?>
                                <div class="mb-2">
                                    <img src="<?= htmlspecialchars($method_details['logo_url']) ?>" class="h-10 border bg-white p-1 rounded-md">
                                    <div class="flex items-center gap-2 mt-2">
                                        <input type="checkbox" name="delete_logos[<?= $method_name ?>]" id="delete_logo_<?= str_replace(' ', '', $method_name) ?>" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <label for="delete_logo_<?= str_replace(' ', '', $method_name) ?>" class="text-sm text-red-600 font-medium">Delete current logo</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="payment_logos[<?= $method_name ?>]" class="form-input text-sm" accept="image/*">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary mt-6"><i class="fa-solid fa-floppy-disk"></i> Save Payment Settings</button>
        </form>
    </div>

     <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <form action="api.php" method="POST" class="bg-white p-6 rounded-lg border">
            <input type="hidden" name="action" value="update_currency_rate">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Currency Settings</h3>
            <div>
                <label class="block mb-1.5 font-medium text-gray-700 text-sm">1 USD = ? BDT</label>
                <input type="number" step="0.01" name="usd_to_bdt_rate" class="form-input" value="<?= htmlspecialchars($site_config['usd_to_bdt_rate'] ?? '110') ?>" placeholder="e.g., 110.50">
            </div>
            <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Rate</button>
        </form>

        <form action="api.php" method="POST" class="bg-white p-6 rounded-lg border">
            <input type="hidden" name="action" value="update_contact_info">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Help Center Contacts</h3>
            <div class="space-y-4">
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Phone Number</label><input type="text" name="phone_number" class="form-input" value="<?= htmlspecialchars($site_config['contact_info']['phone'] ?? '') ?>" placeholder="+8801234567890"></div>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-input" value="<?= htmlspecialchars($site_config['contact_info']['whatsapp'] ?? '') ?>" placeholder="8801234567890 (without +)"></div>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Email Address</label><input type="email" name="email_address" class="form-input" value="<?= htmlspecialchars($site_config['contact_info']['email'] ?? '') ?>" placeholder="contact@example.com"></div>
            </div>
             <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Contacts</button>
        </form>
    </div>

    <form action="api.php" method="POST" class="bg-white p-6 rounded-lg border">
        <input type="hidden" name="action" value="update_admin_password">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Change Admin Password</h3>
        <div>
            <label for="new_password_field" class="block mb-1.5 font-medium text-gray-700 text-sm">New Password</label>
            <div class="relative">
                <input type="password" id="new_password_field" name="new_password" class="form-input pr-16" placeholder="Leave blank to keep current password">
                <button type="button" id="toggle_password_btn" class="absolute top-1/2 right-2 -translate-y-1/2 text-xs font-semibold text-gray-600 bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded-md transition-colors">Show</button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Password</button>
    </form>
</div>