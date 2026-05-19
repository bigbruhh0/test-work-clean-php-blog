{capture assign=content}
<div class="post-page">
    <article class="post-detail">
        {if $post.image_url}
            <img src="{$post.image_url}" alt="{$post.title}">
        {/if}

        <header>
            <h1>{$post.title}</h1>
            <p>{$post.description}</p>

            <div class="post-meta">
                <time datetime="{$post.published_at}">{$post.published_at|date_format:"%B %e, %Y"}</time>
                <span>{$post.views} views</span>
            </div>

            {if $post.categories|count > 0}
                <div class="category-links">
                    {foreach $post.categories as $category}
                        <a href="/category/{$category.slug}">{$category.name}</a>
                    {/foreach}
                </div>
            {/if}
        </header>

        <div class="post-content">
            {foreach $post.paragraphs as $paragraph}
                <p>{$paragraph}</p>
            {/foreach}
        </div>
    </article>

    {if $relatedPosts|count > 0}
        <section class="related-posts">
            <h2>Related articles</h2>

            <div class="post-grid">
                {foreach $relatedPosts as $relatedPost}
                    <article class="post-card">
                        {if $relatedPost.image_url}
                            <a href="/post/{$relatedPost.slug}">
                                <img src="{$relatedPost.image_url}" alt="{$relatedPost.title}">
                            </a>
                        {/if}

                        <h3>
                            <a href="/post/{$relatedPost.slug}">{$relatedPost.title}</a>
                        </h3>
                        <time datetime="{$relatedPost.published_at}">{$relatedPost.published_at|date_format:"%B %e, %Y"}</time>
                        <p>{$relatedPost.description}</p>
                        <a href="/post/{$relatedPost.slug}">Continue Reading</a>
                    </article>
                {/foreach}
            </div>
        </section>
    {/if}
</div>
{/capture}
{include file='layouts/main.tpl' content=$content}
