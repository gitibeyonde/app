(function() {
	var icon = $('#icon');
 	var title = $('input[name=logo-title]');
 	var iconColor = $('select[name=icon-color]');
 	var secondaryColor = $('select[name=secondary-color]');
 	var baseColor = $('select[name=base-color]');
 	var font = $('select[name=font]');

 	icon.bind('change keyup', function (e) {
		$('#icon-output').html('<i class="icon fa ' + icon.val() + '"></i>' );
		$('#icon-output > i.icon').css('color', iconColor.val());
	});

	iconColor.on('change', function (event) {
		$('#icon-output > i.icon').css('color', iconColor.val());
	});

	baseColor.on('change', function () {
		$('#logo-output > h1').css('color', baseColor.val());
	});

	secondaryColor.bind('change', function () {
		var color = secondaryColor.val();
		var oldname = $('#logo-output > h1');
		var oldlogo = $('#icon-output').html();
		
		var subTitle = window.prompt('Type the letter(s) you wish to apply the secondary color to e.g. We in WeWorks!');
        oldname.html(function () {
                        var withSpan = '<span style="color: ' + color + '">' + subTitle + '</span>';
                        return $(this).html().replace(subTitle, withSpan); 
                    });
	});

 	title.on('keyup', function () {
 		var newname = '<span id="icon-output">' + '<i class="icon fa ' + icon.val() + '"></i></span>' + title.val();
		$('#logo-output > h1').html( newname );
	});

	$('button#preview').on('click', function () {
	console.log(this.clientWidth);
	console.log("Size="+ 30*title.val().length);
		html2canvas(document.getElementById('logo-output'), {
		    width: 35*title.val().length + 40,
			letterRendering: true,
			background: undefined,
		    onrendered: function(canvas) {
		        document.getElementById('img_val').value = canvas.toDataURL("image/png");
	            document.getElementById("downloader").submit();
		    }
		});
	});

	font.bind('change keyup', function () {
		$('#logo-output > h1').css('font-family', font.val());
	});
})(jQuery);