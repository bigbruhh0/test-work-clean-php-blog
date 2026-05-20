<header class="site-header">
    <div class="container">
        <a href="{$appUrl}" class="site-logo">{$appName}</a>
        <div class="header-actions">
            <button type="button" data-modal-target="category-modal">Add Category</button>
            <button type="button" data-modal-target="post-modal">Add Post</button>
        </div>
    </div>
</header>

<div class="modal" id="category-modal" hidden>
    <div class="modal__backdrop" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
        <div class="modal__header">
            <h2 id="category-modal-title">Add Category</h2>
            <button type="button" data-modal-close>Close</button>
        </div>

        <form action="/categories" method="post" class="form">
            <label>
                Name
                <input type="text" name="name" required>
            </label>

            <label>
                Description
                <textarea name="description" rows="4"></textarea>
            </label>

            <button type="submit">Save Category</button>
        </form>
    </div>
</div>

<div class="modal" id="post-modal" hidden>
    <div class="modal__backdrop" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="post-modal-title">
        <div class="modal__header">
            <h2 id="post-modal-title">Add Post</h2>
            <button type="button" data-modal-close>Close</button>
        </div>

        <form action="/posts" method="post" enctype="multipart/form-data" class="form">
            <label>
                Title
                <input type="text" name="title" required>
            </label>

            <label>
                Description
                <textarea name="description" rows="3" required></textarea>
            </label>

            <label>
                Text
                <textarea name="content" rows="7" required></textarea>
            </label>

            <label>
                Image
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp,image/gif">
            </label>

            <fieldset>
                <legend>Categories</legend>
                {foreach $formCategories as $category}
                    <label>
                        <input type="checkbox" name="category_ids[]" value="{$category.id}">
                        {$category.name}
                    </label>
                {/foreach}
            </fieldset>

            <button type="submit">Save Post</button>
        </form>
    </div>
</div>
