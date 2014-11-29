$("#header").click(function(){
    window.location=$(this).find("a#homelink").attr("href");
    return false;
});