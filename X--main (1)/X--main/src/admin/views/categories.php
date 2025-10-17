<div id="view-categories" class="p-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-gray-50 p-6 rounded-lg border">
            <h3 class="text-lg font-semibold mb-4">Add New Category</h3>
            <form action="api.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="add_category">
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Category Name</label><input type="text" name="name" class="form-input" required></div>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Font Awesome Icon Class</label><input type="text" name="icon" class="form-input" placeholder="e.g., fa-solid fa-book-open" required></div>
                <div><button type="submit" class="btn btn-primary"><i class="fa-solid fa-circle-plus"></i> Add Category</button></div>
            </form>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg border">
            <h3 class="text-lg font-semibold mb-4">Existing Categories</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                <?php if(!empty($all_products_data)): foreach ($all_products_data as $category): ?>
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border flex-wrap gap-2">
                    <div class="flex items-center gap-4">
                        <i class="<?= htmlspecialchars($category['icon']) ?> text-xl w-8 text-center text-[var(--primary-color)]"></i>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($category['name']) ?></span>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="admin.php?category=<?= urlencode($category['name']) ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pencil"></i> Manage (<?= count($category['products'] ?? []) ?>)</a>
                        <a href="edit_category.php?name=<?= urlencode($category['name']) ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pencil"></i></a>
                        <form action="api.php" method="POST" onsubmit="return confirm('Delete this category and all its products?');">
                            <input type="hidden" name="action" value="delete_category">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($category['name']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>