
var currentDate = new Date();
var dateScroll = currentDate.getDate();
var dateScroll1 = currentDate.getDate();
var currentMonth = currentDate.getMonth();
var currentYear = currentDate.getFullYear();
var currentDate = currentDate.getDate();
var monthObj = {0:31, 1:28, 2:31, 3:30, 4:31, 5:30, 6:31, 7:31, 8:30, 9:31, 10:30, 11:31};
var n = 1;
$(document).ready(function() {

    $(".leftul").append("<li class='nav-item'><input type='range' min='1' max='23' value='0' class='slider' id='myRange'><small style='color: white'>Time(Hr.) :</small>  <span style='color: white;' id='demo'></span>");

    getDays(currentMonth);
    $(".next-month").click(function(){
        if(currentMonth < 12){
            currentMonth =  currentMonth+1;
            getDays(currentMonth);
        }
    });

    $(".prev-month").click(function(){
        if(currentMonth > 0){
            currentMonth =  currentMonth-1;
            getDays(currentMonth);
        }
    });

    $(".next-year").click(function(){

        currentYear = currentYear + 1;
        getDays(currentYear);


    });

    $(".prev-year").click(function(){
        currentYear = currentYear - 1;
        getDays(currentYear);

    });


    if(currentDate <= 7){
        for(var l = 1; l <= 7; l++)
        {
            $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
        }
    }

    for(var l = currentDate-7; l <= currentDate; l++)
    {
        if(l==0)
        {
            break;
        }
        if(l<0){
            break;
        }
        $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
    }

    $(".prev-date").click(function(){
        var dateFirstValue = ($("#days-ul li:first-child").val()-1);
        if(dateFirstValue > 0){
            $("#days-ul li").remove();
            for(var l = dateFirstValue-7; l <= (dateFirstValue); l++)
            {
                if(l <= 0){
                    for (var l = 1; l <= dateFirstValue; l++)
                    {
                        $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
                    }
                    break;
                }
                $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
            }
        }
    });

    $(".next-date").click(function(){
        var dateLastValue = ($("#days-ul li:last-child").val()+1);

        if (dateLastValue<monthObj[currentMonth])
        {
            $("#days-ul li").remove();
            if(l+7 > monthObj[currentMonth]){
                for (; l <= monthObj[currentMonth]; l++)
                {
                    $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
                }

            }
            else {
                for (var l = dateLastValue; l <= (dateLastValue + 7); l++) {

                    if(l == monthObj[currentMonth]){
                        $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
                        break;
                    }
                    $("#days-ul").append('<li value="'+l+'"><a class="date-title" >'+l+'</a></li>');
                }
            }


        }



    });


    $("#days-ul").on('click', 'li a', function(){

        console.log("hit");
        l=1;
        var clickedMonth = currentMonth+1;
        var clickedYear = currentYear;
        var clickedDate = $(this).parent().val();
        dateScroll1 = clickedDate; //for time picker 

        if(clickedDate < 10){
            clickedDate = "0"+clickedDate;
        }
        if(clickedMonth < 10){
            clickedMonth = "0"+clickedMonth;
        }

        dateScroll = clickedDate;
        var appendId = clickedYear.toString()+"_"+clickedMonth.toString()+"_"+clickedDate.toString();
        var urlAppendId = clickedYear.toString()+"/"+clickedMonth.toString()+"/"+clickedDate.toString();


        if($("#"+appendId).length == 0) {
            $(".anything").remove();

            $.ajax({

                dataType : 'html',
                url : '/index.php?uuid=' + phpuuid + '&view=for_image&date=' + urlAppendId,

                type : "GET",
                beforeSend : function() {
                    $('#loading').show();
                    $("#body:not(#loading)").css("filter","blur(3px)");

                },

                complete : function() {
                    $('#loading').hide();
                    $("#body:not(#loading)").css("filter","blur(0px)");
                },

                success : function(data) {
                    $(data).appendTo("#dayone1");


                },
                error : function() {

                }
            });

        }
        else {
            $(this).attr("href", "#"+appendId);
        }
    });



    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        console.log(dateScroll1);
        var datescr1 = dateScroll1;
        var currM1 = currentMonth+1;

        if(datescr1 < 10){
            datescr1 = "0"+datescr1;
        }
        if(currentMonth < 10){
            currM1 = "0"+currM1;
        }
        
        var urlAppendId1 = currentYear.toString()+"/"+currM1.toString()+"/"+datescr1.toString();
        
        console.log(urlAppendId1);

        output.innerHTML = this.value;
        var selectedTime = this.value;
        window.location = '/index.php?uuid=' + phpuuid + '&view=history_view&date='+ urlAppendId1 + '&time=' + selectedTime;

    }

    function getDays(month,year,days){
        var mos=['January','February','March','April','May','June','July','August','September','October','Novemeber','Decemeber'];
        $("#month-title").html(mos[currentMonth]);
        $("#year-title").html(currentYear);

    }

    $(window).scroll(function() {

        if (($(window).scrollTop() + $(window).height() >= ($(document).height() * 0.75) && n == 1) || ($(window).scrollTop() == 0 && $(window).height() == 645 && $(document).height() == 645)) {


            n = 0;
            console.log(dateScroll);
            dateScroll = dateScroll - 1;
            if(dateScroll == 0){
                currentMonth = currentMonth - 1;
                dateScroll = monthObj[currentMonth];
                getDays(currentMonth, currentYear);
                }

            var datescr = dateScroll;
            var currM = currentMonth+1;
            if(dateScroll < 10){
                datescr = "0"+dateScroll;
            }
            if(currentMonth < 10){
                currM = "0"+currM;
            }



            var appendId = currentYear.toString()+"_"+currM.toString()+"_"+datescr.toString();
            var urlAppendId = currentYear.toString()+"/"+currM.toString()+"/"+datescr.toString();


            $.ajax({

                dataType : 'html',
                url : '/index.php?uuid=' + phpuuid + '&view=for_image&date=' + urlAppendId,

                type : "GET",
                beforeSend : function() {
                    $('#loading').show();
                    $("#body:not(#loading)").css("filter","blur(3px)");


                },

                complete : function() {
                    $('#loading').hide();
                    $("#body:not(#loading)").css("filter","blur(0px)");
                },

                success : function(data) {
                    $(data).appendTo("#dayone1");
                    $("#dayone1").children().last().attr("id", ""+appendId);
                    n = 1;


                },
                error : function() {
                }
            });

        }
    });
});