$(document).ready(function(){
    $('.ajax').click(function(){
       $.get($(this).children('span').data("ajaxroute"));
       $(this).toggleClass("btn-success");
    });
});


