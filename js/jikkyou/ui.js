$(function(){
	function postComment(){
		var comment = $("#comment").val();
		jikkyou.postComment(comment);
		$("#comment").val("");
	}

	$('#comment').keypress(function(e){
		//Enterキー
		if(e.which == 13){
			postComment();
			return false;
		}
	});

	$("#postButton").click(function () {
		postComment();
	});

	$('#ui-tab').tabs();

	//キャンバスのサイズを設定
	function refreshCanvaseSize(){
		var w = $('.wrapper').width();
		var h = $('.wrapper').height();
		$('#commentView').attr('width', w);
		$('#commentView').attr('height', h);
	}

	//クリックしたときのファンクションをまとめて指定
	$('#ui-tab li').click(function(){
		//タブレットなど位置が崩れるので高さを設定
    	// var baseTop = $("#mainWindow").offset().top;
    	// $("#ui-tab").offset({ top: baseTop});
    	// $("#ui-tab ul").offset({ top: baseTop});
    	// $("#ui-tab .tabWindowSystem").offset({ top: baseTop + $("#ui-tab ul").height()});
    	// $("#ui-tab .tabWindowComment").offset({ top: baseTop + $("#ui-tab ul").height()});
    	// $("#leftWindow").offset({ top: baseTop});

		// $("#mainWindow").width("100%");
		// $("#mainWindow").height("80%");


		// //タブの空白
		// var offset = parseInt($('.tabWindow').css('padding-top'), 10) + parseInt($('.tabWindow').css('padding-bottom'), 10);
	});


	var w = $('.wrapper').width();
	var h = $('.wrapper').height();
	$('#commentView').attr('width', w);
	$('#commentView').attr('height', h);

	(function(){
		var widthRate = 0.6;
		var baseWidth = $("#mainWindow").width();
		$("#mainWindow").width(baseWidth);
		$("#leftWindow").width(parseInt(baseWidth * widthRate));
		$("#ui-tab").width(baseWidth - parseInt(baseWidth * widthRate));
		$("#ui-tab").css('margin-left', $("#leftWindow").width());

		var baseHeight = $("#mainWindow").height();
		$("#mainWindow").height(baseHeight);
		$("#leftWindow").height(baseHeight);
		$("#ui-tab").height(baseHeight);

		var uiHeight = $("#ui-tab ul").height();
		var paddingSpace = parseInt($('#fragment-comment').css('padding-top'), 10) + parseInt($('#fragment-comment').css('padding-bottom'), 10);

		$("#commentTable").height(baseHeight - uiHeight - paddingSpace);
		$("#fragment-comment").height(baseHeight - uiHeight - paddingSpace);
		$("#fragment-system").height(baseHeight - uiHeight - paddingSpace);

		refreshCanvaseSize();
	})();
	$("#ui-tab a[href='#fragment-comment']").trigger("click");
	jikkyou.init();
});