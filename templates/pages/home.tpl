{capture assign=content}
<div class="home-page">
    {if $categories|count > 0}
        {foreach $categories as $category}
            <section class="category-section">
                <div class="category-section__header">
                    <h2>{$category.name}</h2>
                    <a href="/category/{$category.slug}">View All</a>
                </div>

                <div class="post-grid">
                    {foreach $category.posts as $post}
                        <article class="post-card">
                            {if $post.image_url}
                                <a href="/post/{$post.slug}">
                                    <img src="{$post.image_url}" alt="{$post.title}">
                                </a>
                            {/if}

                            <h3>
                                <a href="/post/{$post.slug}">{$post.title}</a>
                            </h3>
                            <time datetime="{$post.published_at}">{$post.published_at|date_format:"%B %e, %Y"}</time>
                            <p>{$post.description}</p>
                            <a href="/post/{$post.slug}">Continue Reading</a>
                        </article>
                    {/foreach}
                </div>
            </section>
        {/foreach}
    {else}
        <section class="empty-state">
            <h1>No posts yet</h1>
            <p>Run the database seeder to add demo content.</p>
        </section>
    {/if}
</div>
{/capture}
{include file='layouts/main.tpl' content=$content}
