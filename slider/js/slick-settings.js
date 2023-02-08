$(document).ready(function(){

    images.forEach(element => {
        let img = '<img src=\"img/slider/' + element +'\">';
        let slide = '<div class="slider__item">' + img + '</div>';

        $("#team .slider").append(slide);
    })
    
	$('#team .slider').slick({
		arrows:true,
		dots:true,
		slidesToShow:3,
		autoplay:true,
		speed:1000,
		autoplaySpeed:800,
		responsive:[
			{
				breakpoint: 768,
				settings: {
					slidesToShow:2
				}
			},
			{
				breakpoint: 550,
				settings: {
					slidesToShow:1
				}
			}
		]
	});
});
