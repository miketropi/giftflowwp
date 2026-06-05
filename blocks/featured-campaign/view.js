document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.giftflow-featured-campaign__image-area--video').forEach((container) => {
        const type = container.dataset.videoType;
        const playPauseBtn = container.querySelector('.giftflow-featured-campaign__play-pause');
        const timeDisplay = container.querySelector('.giftflow-featured-campaign__video-time');
        const playIcon = container.querySelector('.giftflow-featured-campaign__play-icon');
        const pauseIcon = container.querySelector('.giftflow-featured-campaign__pause-icon');

        if (!playPauseBtn || !timeDisplay || !playIcon || !pauseIcon) return;

        const showPlay = () => { playIcon.style.opacity = '1'; pauseIcon.style.opacity = '0'; };
        const showPause = () => { playIcon.style.opacity = '0'; pauseIcon.style.opacity = '1'; };

        const formatTime = (s) => {
            if (!isFinite(s) || s < 0) return '0:00';
            const m = Math.floor(s / 60);
            const sec = Math.floor(s % 60);
            return m + ':' + String(sec).padStart(2, '0');
        };

        let isPlaying = false;

        const updateTimeDisplay = (current, total) => {
            timeDisplay.textContent = formatTime(current) + ' / ' + formatTime(total);
        };

        if (type === 'html5') {
            const video = container.querySelector('video');
            if (!video) return;

            updateTimeDisplay(0, video.duration || 0);

            video.addEventListener('loadedmetadata', () => {
                updateTimeDisplay(video.currentTime, video.duration);
            });

            video.addEventListener('timeupdate', () => {
                if (isPlaying) {
                    updateTimeDisplay(video.currentTime, video.duration);
                }
            });

            video.addEventListener('play', () => { isPlaying = true; showPause(); });
            video.addEventListener('pause', () => { isPlaying = false; showPlay(); });
            video.addEventListener('ended', () => { isPlaying = false; showPlay(); });

            playPauseBtn.addEventListener('click', () => {
                if (video.paused) {
                    video.play().catch(() => {});
                } else {
                    video.pause();
                }
            });

            if (!video.paused) {
                isPlaying = true;
                showPause();
            } else {
                showPlay();
            }
        } else if (type === 'youtube') {
            const iframe = container.querySelector('iframe');
            if (!iframe) return;

            updateTimeDisplay(0, 0);
            showPlay();

            playPauseBtn.addEventListener('click', () => {
                if (isPlaying) {
                    iframe.contentWindow.postMessage(JSON.stringify({ event: 'command', func: 'pauseVideo', args: [] }), '*');
                } else {
                    iframe.contentWindow.postMessage(JSON.stringify({ event: 'command', func: 'playVideo', args: [] }), '*');
                }
            });

            window.addEventListener('message', (e) => {
                if (e.source !== iframe.contentWindow) return;
                try {
                    const data = JSON.parse(e.data);
                    if (data.event === 'infoDelivery' && data.info) {
                        if (data.info.playerState === 1) { isPlaying = true; showPause(); }
                        if (data.info.playerState === 2 || data.info.playerState === 0) { isPlaying = false; showPlay(); }
                        if (typeof data.info.currentTime === 'number' && typeof data.info.duration === 'number') {
                            updateTimeDisplay(data.info.currentTime, data.info.duration);
                        }
                    }
                } catch (_) {}
            });
        } else if (type === 'vimeo') {
            const iframe = container.querySelector('iframe');
            if (!iframe) return;

            updateTimeDisplay(0, 0);
            showPlay();

            playPauseBtn.addEventListener('click', () => {
                const action = isPlaying ? 'pause' : 'play';
                iframe.contentWindow.postMessage(JSON.stringify({ method: action }), '*');
            });

            window.addEventListener('message', (e) => {
                if (e.source !== iframe.contentWindow) return;
                try {
                    const data = JSON.parse(e.data);
                    if (data.method === 'timeupdate' && data.value) {
                        updateTimeDisplay(data.value.currentTime || 0, data.value.duration || 0);
                    }
                    if (data.event === 'play') { isPlaying = true; showPause(); }
                    if (data.event === 'pause' || data.event === 'ended') { isPlaying = false; showPlay(); }
                } catch (_) {}
            });
        }
    });
});
