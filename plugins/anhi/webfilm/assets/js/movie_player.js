var site_name = 'HDfilme.TV - Kino stream HD'

var site_url = 'http://hdfilme.tv'

function initPlayer (movieId, currentEpisode, isVip, linkTrigger, linkVip) {

    var player = jwplayer("my-video");

    var episode = currentEpisode

    lastPosition = 0; 

    $.get( "/movie/getlink/"+movieId+"/"+episode, function( data ) {

        data=JSON.parse(atob(data));

        if (data.watch_info > 0) {

            isResume = true;
            play_movie(player, data.playinfo, isVip, linkTrigger, linkVip);

            player.onPlay( function() {

                duration = player.getDuration();
                if(duration>data.watch_info){
                    player.seek(data.watch_info);
                    lastPosition = data.watch_info;
                }
            });

        } else {

            play_movie(player, data.playinfo, isVip, linkTrigger, linkVip);
        }

        // On complete

        player.on("complete",function(){

            lastPosition = 0 ;
            $.post( "/movie/updatewatchlink/"+movieId+"/"+episode, { watched_time: 0 } );

        })

        // Enable Adsblock
        // if (!isVip) {

        //     player.on("adBlock",function(event){

        //          setTimeout(function(){
        //             $('.film-screen-wrapper .film-screen-inner').addClass('adblockDetected');
        //             $("#mediaplayer").hide();
                    
        //             player.stop();
        //         }, 39000);
        //     });
        
        // }

        // Update every 60 minutes 
        setInterval(function() {

            if (player.getState() == "playing") {

                position = player.getPosition();

                if (position - lastPosition >= 5 ) {

                    lastPosition = position;
                }
            }

        }, 60000);
        

    });
}
function isMobile () {

    return /Mobi/i.test(navigator.userAgent)
}
function play_movie(player, movieSource, isVip, linkTrigger, linkVip)
{

var advertising = {}
        tag = 'https://an.facebook.com/v1/instream/vast.xml?placementid=216091832455840_220088212056202&pageurl='+ window.location.href;

    if (isMobile())
        tag = 'https://an.facebook.com/v1/instream/vast.xml?placementid=216091832455840_216092445789112&pageurl='+ window.location.href
        
    if (!isVip) {
        var advertising = { 
            client: "vast",
            schedule: {
                "myAds": {
                    "offset":"pre",
                    "tag": tag
                },
				"myAds1": {
                    "offset":"666",
                    "tag": tag
                },
				"myAds2": {
                    "offset":"5555",
                    "tag": tag
                }
            }
        }
    }

    var config = {
        'skin':                     {name: "vapor"},
        'abouttext':        site_name,
        'aboutlink':        site_url,
        'width':            '100%',
        'height':           '100%',
        'autostart':        'true',
        'stretching':       'uniform',
        'sources': movieSource ,
        'advertising': advertising,

        'preload': 'auto',
        'ga': {
            // label: 'player'
        },
        events:{
            onComplete: function() {
                var nextVideo = document.getElementById('nextEpisodeToPlay');
                if (nextVideo && nextVideo.href) {
                    window.location = nextVideo.href;
                }
            }
        }
    };
    // setup jwplayer
    player.setup(config);
    player.addButton(
            //This portion is what designates the graphic used for the button
            "/themes/hdfilme/assets/img/icon-download.png",
            "Click to DOWNLOAD!",
            function() {

                if (isVip) {

                    var htmlContent = $("#movie_download").html();
                    $.colorbox({html:htmlContent});

                } else {
                    window[linkTrigger](linkVip);
                }
            },
            "download"
    );
}