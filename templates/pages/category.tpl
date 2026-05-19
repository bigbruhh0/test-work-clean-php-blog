{capture assign=content}
<section>
    <h1>{$category.name}</h1>
    <p>{$category.description}</p>
    <p>Sort: {$sort}</p>
</section>
{/capture}
{include file='layouts/main.tpl' content=$content}

