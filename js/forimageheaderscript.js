$(document).ready(function() {

    var i = 0; // for date counter
    var m = 1; // use as flag if there is ajax call executed at bottom
    var n = 1; // use as flag if there is ajax call executed at bottom
    var k = 0; // use as flag if there is ajax call executed at top
    var temp = 1; // use as flag if there is ajax call executed at top

    var now = new Date(pickerdate);

    function load_date(x) {
        var now = new Date(pickerdate);
        var date_ago = new Date(now - i * 86400000);
        var date_ago_string = (date_ago.getFullYear() + '/' + ('0' + (date_ago.getMonth() + 1)).slice(-2) + '/' + ('0' + (date_ago.getDate())).slice(-2));
        return date_ago_string;

    }

    function toAppend(y) {
        var now = new Date(pickerdate);
        var date_ago = new Date(now - i * 86400000);
        var date_ago_string = (date_ago.getFullYear() + '/' + ('0' + (date_ago.getMonth() + 1)).slice(-2) + '/' + ('0' + (date_ago.getDate())).slice(-2));
        var txt2 = '<h3>' + date_ago_string + '</h3>';
        var txt3 = '<hr>';
        var txttotal = txt2 + txt3;
        return txttotal;
    }

    function forn(z) {
        n = m;
        return n;
    }

    function load_date_new(s) {

        var date_ago = new Date(now - i * 86400000);
        var date_ago_string = (date_ago.getFullYear() + '_' + ('0' + (date_ago.getMonth() + 1)).slice(-2) + '_' + ('0' + (date_ago.getDate())).slice(-2));
        return date_ago_string;
    }

    $(window).scroll(function() {

        if (($(window).scrollTop() + $(window).height() >= ($(document).height() * 0.75) && n == 1) || ($(window).scrollTop() == 0 && $(window).height() == 645 && $(document).height() == 645)) {

            i++;
            n = 0;

            var date_new_url = load_date(i);
            var date_new_id = load_date_new(i);
            $("#dayone1").append("<div class='row' style='margin-top:50px' id='" + date_new_id + "'></div>");

            $.ajax({

                dataType : 'html',
                url : '/index.php?uuid=' + phpuuid + '&view=for_image&date=' + date_new_url,

                type : "GET",
                beforeSend : function() {
                    $('#Loading').show();
                },
                complete : function() {
                    $('#Loading').hide();
                },
                success : function(data) {

                    var appendtext = toAppend(i);
                    $("#dayone1").append(appendtext);
                    $(data).appendTo("#dayone1");

                    var n = forn(m);

                },
                error : function() {
                }
            });

        }

        function up_load_date(g) {

            var date_after = new Date(now.getTime() + (k * (24 * 60 * 60 * 1000)));
            var date_after_string = (date_after.getFullYear() + '/' + ('0' + (date_after.getMonth() + 1)).slice(-2) + '/' + ('0' + (date_after.getDate())).slice(-2));
            return date_after_string;

        }

        function up_load_date_new(g) {

            var date_ago = new Date(now.getTime() + (k * (24 * 60 * 60 * 1000)));

            var date_ago_string = (date_ago.getFullYear() + '_' + ('0' + (date_ago.getMonth() + 1)).slice(-2) + '_' + ('0' + (date_ago.getDate())).slice(-2));

            return date_ago_string;
        }

        function up_toAppend(h) {

            var date_ago = new Date(now.getTime() + ((k - 1) * (24 * 60 * 60 * 1000)));

            var date_ago_string = (date_ago.getFullYear() + '/' + ('0' + (date_ago.getMonth() + 1)).slice(-2) + '/' + ('0' + (date_ago.getDate())).slice(-2));

            var txt2 = '<h3>' + date_ago_string + '</h3>';
            var txt3 = '<hr>';
            var txttotal = txt2 + txt3;
            return txttotal;
        }

        function up_forn(d) {
            temp = m;
            // console.log(n);
            return temp;
        }
        var current_date = new Date();
        var current_date1 = (current_date.getFullYear() + '_' + ('0' + (current_date.getMonth() + 1)).slice(-2) + '_' + ('0' + (current_date.getDate())).slice(-2));

        if (($(window).scrollTop() == 0 && n == 1)) {

            if (($("#" + current_date1).length == 0)) {
                k++;
                console.log("k=" + k);
                temp = 0;

                var date_new_url = up_load_date(k);
                var date_new_id = up_load_date_new(i);
                $("#dayone1").prepend("<div class='row' style='margin-top:50px' id='" + date_new_id + "'></div>");

                $.ajax({

                    dataType : 'html',
                    url : '/index.php?uuid=' + phpuuid + '&view=for_image&date=' + date_new_url,

                    type : "GET",
                    beforeSend : function() {
                        $('#Loading').show();
                    },
                    complete : function() {
                        $('#Loading').hide();
                    },

                    success : function(data) {

                        var appendtext = up_toAppend(k);
                        $("#" + date_new_id).prepend(appendtext);
                        $(data).prependTo("#" + date_new_id);

                        var temp = up_forn(m);

                    },
                    error : function() {
                    }
                });

            }
        }
    });
});