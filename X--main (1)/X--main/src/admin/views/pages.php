<div id="view-pages" class="p-6">
    <form action="api.php" method="POST" class="space-y-8">
        <input type="hidden" name="action" value="update_page_content">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Manage Page Content</h2>

        <?php
        $pages_to_manage = [
            'about_us' => 'About Us',
            'terms' => 'Terms and Conditions',
            'privacy' => 'Privacy Policy',
            'refund' => 'Refund Policy'
        ];

        foreach ($pages_to_manage as $key => $title):
            $db_key = "page_content_{$key}";
            $content = $site_config[$db_key] ?? '';
        ?>
        <div class="bg-white p-6 rounded-lg border">
            <h3 class="text-lg font-semibold mb-4 text-gray-800"><?= htmlspecialchars($title) ?></h3>
            <div>
                <label class="block mb-1.5 font-medium text-gray-700 text-sm">Page Content</label>
                <textarea name="page_content[<?= $key ?>]" class="form-textarea" rows="10" placeholder="Enter content for the <?= htmlspecialchars($title) ?> page."><?= htmlspecialchars($content) ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Use **text** to make text bold. All line breaks and spacing will be preserved.</p>
            </div>
        </div>
        <?php endforeach; ?>

        <div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save All Pages</button>
        </div>
    </form>
</div>