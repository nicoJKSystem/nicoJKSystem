(function(global){
	function getGetParam(){
		var obj = {};
		var elements = location.search.substring(1).split('&');
		for(var i = 0; i < elements.length; i++) {
	    	var k = elements[i].split('=');
	    	if(k.length == 2)
	    		obj[k[0]] = decodeURIComponent(k[1]);
	    	else
	    		obj[k[0]] = void 0;
	    }
	    return obj;
	}

	var getParam = getGetParam();

	//idが不正な値なら初期化しない
	if(getParam["id"] === void 0){
		alert("idが入力されていません。");
		return;
	}

	var jkKey = getParam["id"];
	var postComment = function(comment) {
	    if(comment == "") return false;

	    $.getJSON("./getwritekey.php", function(postJson){
	    	if(postJson["info"]["status"] != "200") return;
           	var writeKey = postJson["info"]["writeKey"];

	        $.getJSON("./post.php", { comment: comment, channel_id: jkKey, writeKey: writeKey }, function(json){

    		});
    	});

    	return true;
  	};

	var nowComment = -100;

	var addCommentTable = function(date, comment, no){
		// $('#commentTable tbody > tr:last').after('<tr><td>山下</td><td>' + data + '</td></tr>')
		$("#commentTable").append(
    		$("<tr></tr>")
        		.append($("<td></td>").text(date))
        		.append($("<td></td>").text(comment))
        		.append($("<td></td>").text(no))
		);

    	$('#commentTable').scrollTop($('#commentTable')[0].scrollHeight);
		    	// $('#commentTable2').animate({scrollTop: $('#commentTable2')[0].scrollHeight}, 'fast');
		// $('#commentTable').scrollTop($('#commentTable').scrollTop() + $('#commentTable').height());
		//

		$('#commentTable td').off('click');
		$("#commentTable td").on('click', function() {
		    //var td = $(this)[0];
		  	//var tr = $(this).parent()[0];

		  	var index = $(this).closest('tr').index();

		  	$("#comment-dialog p").html("ユーザー名:" + chatData[index].userid + "<br>"
		  				+ "コメント:" + chatData[index].comment);

		    $("#comment-dialog").dialog({
		    	resizable: false,
		    	height:350,
		    	modal: true,
		    	buttons: {
			        "ＮＧユーザーに追加": function() {
			        	NG.addUserNg(chatData[index]);
			          	$( this ).dialog( "close" );
			        },
			        "ＮＧコメントに追加": function() {
			        	NG.addUserCommentNg(chatData[index]);
			          	$( this ).dialog( "close" );
			        },
			        Cancel: function() {
			          $( this ).dialog( "close" );
			        }
		      	}
		    });
		});
	}

	var sleepMode = false;
	var sleepCount = 0;

	var CommentDraw = (function(){
		var commentData = [];
		var self;

		function CommentDraw(){
			self = this;
		}

		CommentDraw.prototype.init = function(canvas){
			self.canvas = canvas;

	    	console.log(canvas.width + " " + canvas.height);
	    	setTimeout(function(){
				self.draw();
			},16);
		}

		CommentDraw.prototype.canvas = null;
		CommentDraw.prototype.addCount = 0;
		CommentDraw.prototype.fontSize = 40;
		var commentHeightMax = 10;	//10段

		CommentDraw.prototype.addComment = function(date, comment, no){
			var ctx = self.canvas.getContext('2d');
			var y = (self.addCount / (commentHeightMax + 0.0) * self.canvas.height + self.fontSize);
			self.addCount = (self.addCount + 1) % commentHeightMax;

			var index = -1;
			for (var i in commentData) {
				if(!commentData[i].flag){
					index = i;
					break;
				}
			}

			var commentInfo = {
				"x" : self.canvas.width,
				"y" : y,
				"end" : -ctx.measureText(comment).width,	//消すx座標
				"comment" : comment,
				"speed" : (self.canvas.width + ctx.measureText(comment).width) / (60.0*2), //2秒で消す
				"flag"	: true
			};

			if(index == -1){
				commentData.push(commentInfo);
			}else
				commentData[index] = commentInfo;

			return true;
		}

		function convert2keta(val) {
			return val >= 10? val : ("0" + val);
		}
		CommentDraw.prototype.drawTime = function(canvas, ctx){
			var objDate = new Date();
			var time = {};
			time["year"] = objDate.getFullYear();
			time["month"] = objDate.getMonth()+1;
			time["date"] = objDate.getDate();
			time["hour"] = objDate.getHours();
			time["min"] = objDate.getMinutes();
			time["sec"] = objDate.getSeconds();

			ctx.clearRect(0, 0, canvas.width, canvas.height);
	    	ctx.font= 'bold 50px sans-serif';
			ctx.fillStyle = "rgb(128, 128, 128)";
			ctx.strokeStyle = ctx.fillStyle;

			var dateText = time["month"] + "/" + convert2keta(time["date"]);
			var hourText = convert2keta(time["hour"]) + "　:　" + convert2keta(time["min"]) + "　:　" + convert2keta(time["sec"]);

			ctx.fillText(dateText, canvas.width/2 - ctx.measureText(dateText).width/2, (canvas.height - 50) / 2 + 50);

			ctx.font= 'bold 18px sans-serif';
			ctx.fillText(hourText, canvas.width/2 - ctx.measureText(hourText).width/2,  (canvas.height + 50) / 2 + 18 );
		}

		CommentDraw.prototype.draw = function(){
			self.fontSize = Math.floor(self.canvas.height / commentHeightMax);

			// if ( ! canvas || ! canvas.getContext ) { return false; }
			var ctx = self.canvas.getContext('2d');

			self.drawTime(self.canvas, ctx);

			ctx.font= 'bold ' + self.fontSize + 'px sans-serif';
			ctx.fillStyle = "rgb(255, 255, 255)";
			ctx.strokeStyle = ctx.fillStyle;

			for (var i in commentData) {
				var comment = commentData[i];
				if(comment.flag){
					ctx.fillText(comment.comment, comment.x, comment.y);

					comment.x -= comment.speed;
					if(comment.x < comment.end) comment.flag = false;
				}
			}

			setTimeout(function(){
				self.draw();
			},16);

		}
		return CommentDraw;
	})();

	var NG = new window.CommentCheck.NG();
	NG.init();
	function systemReadyCheck(){
		if(NG.isReady()){
			setTimeout(function(){
				readComment();
			},100);
		}else{
			console.log("not ready");
			setTimeout(function(){
				systemReadyCheck();
			},100);
		}
	}
	var chatData = [];

	var readComment = function(){
    	$.getJSON("./thread.php", { read_from: nowComment, channel_id: jkKey }, function(json){
			// console.log(json);
			if(json["info"]["last_no"] != -1){
				nowComment = parseInt(json["info"]["last_no"]) + 1;
			}

			//基本は0.1秒間隔で取得
			var nextTime = 100;
			if(json["chat"].length > 0){
				for (var i in json["chat"]) {
					var chat = json["chat"][i];

					if(NG.check(chat)){
						var time = chat["date"].substr(0,2) + ":" + chat["date"].substr(2,2);

						chatData.push(chat);
						addCommentTable(time, chat["comment"], chat["no"]);
						commentDraw.addComment(time, chat["comment"], chat["no"]);
					}else
						console.log(NG.getErrorMessage());
				}

				//１コメにつき125ミリ秒追加
				nextTime = 125 * json["chat"].length;

				sleepCount = 0;
				sleepMode = false;
			}else{
				sleepCount++;
				if(sleepCount == 10)
					sleepMode = true;
			}

			if(sleepMode){
				nextTime = 4750; //4.75秒
			}

			setTimeout(function(){
				readComment();
			}, nextTime)
		});
    }

	var CommentCheckNG= (function(){
		var commentData = [];
		var self;

		function Class(canvas){
			self = this;
	    	console.log(canvas.width + " " + canvas.height);
	    	setTimeout(function(){
				self.draw();
			},16);
		}

		Class.prototype.canvas = null;
		Class.prototype.addCount = 0;
		Class.prototype.fontSize = 40;
		var commentHeightMax = 10;	//10段

		Class.prototype.init = function(){
			return true;
		}

		Class.prototype.isReady = function(){
			return true;
		}

		return Class;
	})();

    var commentDraw;
	var init = function(){
    	setTimeout(function(){
			systemReadyCheck();
		},100);

		commentDraw = new CommentDraw();
		commentDraw.init($("#commentView")[0]);
	}

	global.jikkyou = {
		"postComment" : postComment,
		"init" : init,
	}
})(window);