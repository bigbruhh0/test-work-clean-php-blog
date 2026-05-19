{capture assign=content}
<article>
    <h1>{$post.title}</h1>
    <p>{$post.description}</p>
</article>
{/capture}
{include file='layouts/main.tpl' content=$content}

