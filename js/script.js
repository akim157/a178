$(document).ready(function(){
    $('.click-search').on('click',function(){
        $('.main-search').toggle(400);
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
});