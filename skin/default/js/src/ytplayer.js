window.addEventListener('load',() => {
    let firstScriptTag = document.getElementsByTagName('script')[0];
    let tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
});

function onYouTubeIframeAPIReady() {
    document.querySelectorAll('.ytb-video-preview').forEach((yvp) => {
        let params = JSON.parse(yvp.dataset.ytb);
        /*params = Object.assign({},params,{
            events: {
                'onReady': onPlayerReady,
                'onPlaybackQualityChange': onPlayerPlaybackQualityChange,
                'onStateChange': onPlayerStateChange,
                'onError': onPlayerError
            }
        });*/
        new YT.Player(yvp, params);
    });
}