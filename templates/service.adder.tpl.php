<?php function drawServiceForm(array $categories) { ?>
<div class="service-form-container">
    <h2>Create New Service</h2>
    <form id="serviceForm" action="../actions/action_create_service.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Service Title*</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
                <select id="category" name="category_id">
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
        </select>
        </div>

        <div class="form-group">
            <label for="description">Service Description*</label>
            <textarea id="description" name="description" rows="6" required></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Price ($)*</label>
                <input type="number" id="price" name="price" min="5" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="delivery_time">Delivery Time (days)*</label>
                <input type="number" id="delivery_time" name="delivery_time" min="1" required>
            </div>
        </div>

        <div class="form-group">
            <label for="images">Upload Images (Max 5)</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*">
            <small>First image will be used as primary</small>
        </div>

        <div class="form-group">
            <label for="video">Upload Video (Optional)</label>
            <input type="file" id="video" name="video" accept="video/*">
        </div>

        <button type="submit" class="submit-btn">Create Service</button>
    </form>
</div>
<?php } ?>