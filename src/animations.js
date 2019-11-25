const jquery = require("jquery");
require("particles.js");

jquery(document).ready(function ($) {

    if (animations.is_snowing) {
        particlesJS.load('animation', animations.snow, function () {
            const snow = document.createElement("div");
            const snow_img = document.createElement("img");
            snow_img.src = animations.snow_img;
            $(snow).append(snow_img);
            $("#animation").append(snow);
            $(snow).addClass('snow');
            console.log('Snow loaded');
        });
    }

});