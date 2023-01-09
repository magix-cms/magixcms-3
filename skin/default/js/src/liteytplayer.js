window.addEventListener('load',() => {
    document.querySelectorAll('.ytb-video-preview').forEach((yvp) => {
        let params = JSON.parse(yvp.dataset.ytb);
        let liteplayer = document.createElement('lite-youtube');
        liteplayer.setAttribute('videoid',params.videoId);
        let fallBack = document.createElement('a');
        fallBack.setAttribute('className',"lite-youtube-fallback");
        fallBack.href = "https://www.youtube.com/watch?v="+params.videoId;
        fallBack.text = 'Watch on YouTube';
        liteplayer.appendChild(fallBack);
        yvp.parentNode.insertBefore(liteplayer,yvp);
        yvp.remove();
    });

    let firstScriptTag = document.getElementsByTagName('script')[0];
    let tag = document.createElement('script');
    tag.src = "https://cdn.jsdelivr.net/npm/@justinribeiro/lite-youtube@1.4.0/lite-youtube.js";
    tag.type = 'module';
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
});