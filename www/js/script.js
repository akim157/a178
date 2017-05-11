$(document).ready(function(){
    $('.click-search').on('click',function(){
        $('.main-search').toggle(400);
    });
    $('.entrance').on('click',function(){
        $('.auto').toggle(400);
    });
    $('#marka').on('change', function() {
        if(this.value != 0){
            $('#model-avto').removeAttr('disabled');
        } else {
            $('#model-avto').attr('disabled','disabled');
            $('#model-avto').val(0);
            /*-----------------------*/
            $('#group-avto').attr('disabled','disabled');
            $('#group-avto').val(0);
            /*----------------------*/
            $('#detail-avto').attr('disabled','disabled');
            $('#detail-avto').val(0);
        }
    });
    $('#model-avto').on('change', function() {
        if(this.value != 0){
            $('#group-avto').removeAttr('disabled');
        } else {
            $('#group-avto').attr('disabled','disabled');
            $('#group-avto').val(0);
            /*----------------------*/
            $('#detail-avto').attr('disabled','disabled');
            $('#detail-avto').val(0);
        }
    });
    $('#group-avto').on('change', function() {
        if(this.value != 0){
            $('#detail-avto').removeAttr('disabled');
        } else {
            $('#detail-avto').attr('disabled','disabled');
            $('#detail-avto').val(0);
        }
    });
    $('.reset-filter').on('click', function(){
        $('#marka').val(0);
        /*------------------------*/
        $('#model-avto').attr('disabled','disabled');
        $('#model-avto').val(0);
        /*-----------------------*/
        $('#group-avto').attr('disabled','disabled');
        $('#group-avto').val(0);
        /*----------------------*/
        $('#detail-avto').attr('disabled','disabled');
        $('#detail-avto').val(0);
    });
    //$( window ).resize(function() {
    //    var h_content = $('#content').height();
    //    $('header').css('height', h_content + 100);
    //});
    //$('#button-menu').on('click', function(){
    //    var w_menu = $(window).width();
    //    console.log(w_menu);
    //    if(w_menu <= 746){
    //        var b_w = $('#button-menu').attr('aria-expanded');
    //        console.log(b_w);
    //        if(b_w == 'false'){
    //            $('header').css('height', '100%');
    //        } else {
    //            $('header').css('height', '100vh');
    //        }
    //    }
    //});
    height_window();
    $(window).resize(function(){
        height_window();
    });
    $('.pop').on('click', function() {
        $('.imagepreview').attr('src', $(this).find('img').attr('src'));
        $('#imagemodal').modal('show');
    });

    $('#marka').on('change',function(){
        var id = $('#marka option:selected').val();
        if(id < 1) return false;
        var url = '/parts?id='+id+'&sin=1';
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json'
        }).done(function(data) {
            if(typeof data == 'undefined') return false;
            $('#model-avto').empty();
            var select = '<option value="0">Модель авто</option>';
            if(data.length > 0) {
                for(var i = 0; i < data.length; i++) {
                    select += '<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>';
                }
            }
            if(select == '') return false;
            $('#model-avto').append(select);
        });
    });

    $('#search').focus(function(){
        if($('.warning-search').attr('style') == 'display: block;')
            $('.warning-search').css( "display", "block" ).fadeOut( 1000 );
    });

    $('#marka').focus(function(){
        if($('.warning-extended-search').attr('style') == 'display: block;')
            $('.warning-extended-search').css( "display", "block" ).fadeOut( 1000 );
    });

    $('.warning-search .close').on('click', function(){
        $('.warning-search').css( "display", "block" ).fadeOut( 1000 );
    });
    $('.warning-extended-search .close').on('click', function(){
        $('.warning-extended-search').css( "display", "block" ).fadeOut( 1000 );
    });

    $('[data-toggle="tooltip"]').tooltip();
});

function height_window(){
    var body_h = $('body').height();
    var window_h = $('#content').height();
    console.log(body_h);
    console.log(window_h);
    if(body_h < window_h) {
        $('header').css('height', window_h + 70);
    }
    if(body_h > window_h){
        $('header').css('height', body_h);
    }
}

function validatorSearch(){
    var search = $('#search').val();
    if(search == '') {
        $('.warning-search').show(400);
        return false;
    }
    return true;
}

function validatorExtendedSearch(){
    var marka = $('#marka option:selected').val();
    if(marka < 1) {
        $('.warning-extended-search').show(400);
        return false;
    }
    return true;
}