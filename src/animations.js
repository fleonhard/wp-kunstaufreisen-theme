const jquery = require("jquery");
require("particles.js");

function getAssetPath(file) {
    return animations.assets_dir + file;
}

jquery(document).ready(function ($) {
    if (animations.is_snowing) {
        particlesJS.load('animation', getAssetPath('snow_config'), function () {
            const animation = $("#animation");

            const snow = document.createElement("div");
            const snow_img = document.createElement("img");

            snow_img.src = getAssetPath('snow.png');

            $(snow).append(snow_img);
            $(snow).addClass('snow');

            animation.append(snow);
            animation.css('cursor', `url(${getAssetPath('snowball.png')}) 28 28, auto`);
        });
    }

});