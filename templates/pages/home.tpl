{capture assign=content}
<section>
    <h1>Blog homepage</h1>
    <p>Application bootstrap and template rendering are connected.</p>
</section>
{/capture}
{include file='layouts/main.tpl' content=$content}

