<footer id="footer">
    <div class="text-center">
        <span class="fa fa-copyright"></span> 2008{if 'Y'|date != '2008'} - {'Y'|date}{/if}
        <a href="http://www.magix-cms.com/" class="targetblank">Magix CMS</a> | {#all_right_reserved#} | {if is_array($releaseData) && !empty($releaseData)} v {$releaseData.version} &dash; {$releaseData.phase} | {/if}
        <ul class="list-inline">
            <li><a href="https://www.facebook.com/magix.cms" title="Facebook" class="targetblank"><span class="fab fa-facebook"></span><span class="sr-only">Facebook</span></a></li>
            <li><a href="https://twitter.com/magixcms" title="Twitter" class="targetblank"><span class="fab fa-twitter"></span><span class="sr-only">Twitter</span></a></li>
            <li><a href="https://github.com/magix-cms/magixcms-3" title="Github" class="targetblank"><span class="fab fa-github"></span><span class="sr-only">Github</span></a></li>
        </ul>
    </div>
</footer>