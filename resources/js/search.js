$("#search").keyup(function(e) {
    var str = e.target.value;
    var route = e.target.name;
    var url = window.location.origin + '/' + route + '?key=';
    var xmlhttp=new XMLHttpRequest();

    if (str.length==0) {
        document.getElementById("data-search").innerHTML="";
        return;
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
        document.getElementById("data-search").style.zIndex="9999";
            document.getElementById("data-search").innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET", url + str, true);
    xmlhttp.send();
});
