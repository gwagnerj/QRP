<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />

<style>

/* <link rel="stylesheet" href="plyr-master/src/sass/plyr.scss" /> */

</style>

</head>
<body>

<video id="my-player" class = "video-js" playsinline preload = "auto"  data-setup="{}"  liveui="false"   width="640"   height="264" controls = "controls">
  <source src="Video/2021-02-09 17-43-44.mp4" type="video/mp4" />
  

  <!-- Captions are optional 
  <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />-->
 
</video>
<button onclick="skip(-10)">Rewind 10 s</button>
<script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
<script>

    const pausetime = 50;

    const myPlayer = videojs('my-player', {
  playbackRates: [0.5,0.75, 1, 1.25, 1.5],
  controlBar: {
    playToggle: true,
    captionsButton: true,
    chaptersButton: false,            
    subtitlesButton: true,
    remainingTimeDisplay: true,
    progressControl: {
      seekBar: false
    },
    fullscreenToggle: false,
    playbackRateMenuButton: true,
  }
});
  
myPlayer.on('timeupdate', function(e) {
    if (myPlayer.currentTime() >= pausetime) {
        myPlayer.pause();
    }
});
function skip(t) {
 myPlayer.currentTime(myPlayer.currentTime() +t);
}


/* videojs('my-player', {
  playbackRates: [0.5,0.75, 1, 1.25, 1.5],
  controlBar: {
    playToggle: true,
    captionsButton: true,
    chaptersButton: false,            
    subtitlesButton: false,
    remainingTimeDisplay: false,
    progressControl: {
      seekBar: false
    },
    fullscreenToggle: false,
    playbackRateMenuButton: true,
  }
});
  
});
 */
</script>
</body>


  
</html>