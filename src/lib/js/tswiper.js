function tswiper_init(id, options){

    options = Object.assign(attributes, JSON.parse( options) );
    
    $('#'+id).swiper(options);
}