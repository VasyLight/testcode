$(document).ready(function(){
    
    images.forEach(element => {
    let img = '<img src=\"img/slider/' + element +'\">';
    let slide = '<div class="slider__item">' + img + '</div>';

    $("#team .slider").append(slide);
})

});