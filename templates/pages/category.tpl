{capture assign=content}
<div class="category-page">
    <section class="category-hero">
        <h1>{$category.name}</h1>
        <p>{$category.description}</p>
    </section>

    <div class="category-toolbar">
        <div>
            {$pagination.total} articles
        </div>

        <div class="sort-links">
            <a href="/category/{$category.slug}?sort=date"{if $sort === 'date'} aria-current="page"{/if}>Newest</a>
            <a href="/category/{$category.slug}?sort=views"{if $sort === 'views'} aria-current="page"{/if}>Most Viewed</a>
        </div>
    </div>

    {if $posts|count > 0}
        <div class="post-grid">
            {foreach $posts as $post}
                <article class="post-card">
                    {if $post.image_url}
                        <a href="/post/{$post.slug}">
                            <img src="{$post.image_url}" alt="{$post.title}">
                        </a>
                    {/if}

                    <h2>
                        <a href="/post/{$post.slug}">{$post.title}</a>
                    </h2>
                    <time datetime="{$post.published_at}">{$post.published_at|date_format:"%B %e, %Y"}</time>
                    <p>{$post.description}</p>
                    <span>{$post.views} views</span>
                    <a href="/post/{$post.slug}">Continue Reading</a>
                </article>
            {/foreach}
        </div>
    {else}
        <section class="empty-state">
            <h2>No articles</h2>
            <p>There are no articles in this category yet.</p>
        </section>
    {/if}

    {if $pagination.pages > 1}
        <nav class="pagination" aria-label="Pagination">
            {if $pagination.page > 1}
                <a href="/category/{$category.slug}?sort={$sort}&page={$pagination.page - 1}">Previous</a>
            {/if}

            {for $page=1 to $pagination.pages}
                <a href="/category/{$category.slug}?sort={$sort}&page={$page}"{if $page === $pagination.page} aria-current="page"{/if}>{$page}</a>
            {/for}

            {if $pagination.page < $pagination.pages}
                <a href="/category/{$category.slug}?sort={$sort}&page={$pagination.page + 1}">Next</a>
            {/if}
        </nav>
    {/if}
</div>
{/capture}
{include file='layouts/main.tpl' content=$content}
