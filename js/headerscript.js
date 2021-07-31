
    
function openNav() {
    document.getElementById("mySidenav").style.width = "170px";
    document.getElementById("mySidenav1").style.width = "170px";
    
    var s=$("#ul1 li.active").position().top;
                      // console.log(s);
                        if(s>=55)
                        { 
                        var el = document.querySelector('#mySidenav1');
                    // console.log(el.scrollTop);
                            while(s>=55){
                        el.scrollTop = (el.scrollTop + 192);
                        s=$("#ul1 li.active").position().top;
                            }
                        }
                    
                        if(s <= -27)
                        {   
                            var el = document.querySelector('#mySidenav1');
                            console.log("ya y u here");
                            while(s <= -27){
                            el.scrollTop = (el.scrollTop - 192);
                            s=$("#ul1 li.active").position().top;
                            }
                        }
}
function forcalender()
    {
        document.getElementById("mySidenav1").style.width="0px";
        $("#calender_button").hide();
        $("#fordate_div").show();
        
}
        
function aftersubmit() // for calender picker
    {   
        
        var pick_date=new Date(document.getElementById("fordate").value);// get
                                                                            // date
                                                                            // which
                                                                            // is
                                                                            // pickeded
        console.log(pick_date);
        var make_id="#";
        
        var pick_date_string1 = (pick_date.getFullYear() + '_'
                    + ('0' + (pick_date.getMonth() + 1)).slice(-2)
                    +'_' + ('0' + (pick_date.getDate())).slice(-2));
        
        var pick_date_String2 = pick_date_string1.replace(/_/g,"/")

        var date_new_picker = make_id.concat(pick_date_string1);
        console.log("date_new_picker");
        
        if($("#"+pick_date_string1).length == 0)
            {
                    div_not_present_picker(pick_date_string1);
            }
        else{
            div_present_picker(date_new_picker);
        }
       
    }
    

    
    function div_present_picker(a)
    {
        location.href=a;
    }
    
     function div_not_present_picker(b)
    {   
        var date_ago_string2 = b.replace(/_/g,"/"); // convert into yyyy/mm/dd
                                                    // format
        
        location.href='/index.php?uuid='+phpuuid+'&view=for_image_header&date='+date_ago_string2;
    }


function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav1").style.width = "0";
    $("#fordate_div").hide();
    $("#calender_button").show();
    
}
    

     $(function() {
            $( "#fordate").datepicker();
      
    });
        
     $("#himage").on("swipe",function(){
        alert("swipe");
       });
    
    $(document).ready(function() { 
        
        $('#fordate_div').hide();
    
    $(window).click(function(){
        closeNav();
    });
        $("#mySidenav").click(function(e){
            e.stopPropagation();
            return false;
        })
   
     $("#mySidenav1").click(function(e){
            e.stopPropagation();
            return false;
        })
     $("#calender1").click(function(e){
            e.stopPropagation();
            return false;
        })
   
      
 $("#ul1 li").click(function(){
        
        var href_of_list_date = "";
       
        href_of_date_list = $(this).find('a').attr('href');
        console.log(href_of_date_list);
        
        var matching_id = href_of_date_list.replace(/#/g,""); // remove # from
                                                                // href of date
                                                                // list to
                                                                // compare it
                                                                // with div id
         console.log(matching_id);      
        
        if($("#"+matching_id).length == 0) // when we clicked on a date then it
                                            // check that there is any div
                                            // containing the same id as clicked
                                            // date
           {
                
                div_not_present(matching_id);
                
            }
     else{
         div_present(href_of_date_list);
        }
 })
    
    
    function div_present(a)
    {
        location.href=a;
    }
    function div_not_present(y)
    {   
        var date_ago_string2 = y.replace(/_/g,"/"); // convert into yyyy/mm/dd
                                                    // format
        console.log(date_ago_string2);
        console.log(phpuuid);
        location.href='/index.php?uuid='+phpuuid+'&view=for_image_header&date='+date_ago_string2;
    }
    
    
         
        $('#formid').ajaxForm(function(data) { 
            console.log(data);
            if(data==1)
                {
                document.getElementById("uploading_gif").style.display="none";
                document.getElementById("uploaded").style.display="block";
                }
            else{
                 document.getElementById("not_uploaded").style.display="block";
                document.getElementById("uploading_gif").style.display="none";
            }
    
        }); 
  })
