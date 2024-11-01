var TWLIKEGADGETPOSTS_JAPAN = function(list, box) {
	
	this._box = box;
	this._postList = [];
	this._playList = [];
	if (list.length > 0) {
		for (var i = 0;i < list.length;i ++) {
			var post = {
				"post_title": list[i]["title"],
				"post_content": list[i]["content"],
				"post_url": list[i]["url"],
				"post_time": list[i]["time"]
			};
			this._postList.push(post);
		}
		this._playList = this._postList.slice();
		TWLIKEGADGETPOSTS_JAPAN.viewPost(this._postList, this._playList, this._box);
	}
}

// 表示
TWLIKEGADGETPOSTS_JAPAN.viewPost = function(postList, playList, box) { 
	console.log($);
	console.log(jQuery);
	if (playList.length == 0) {
		box.html("");
		playList = postList.slice();
	}
	var post = playList.pop();
	jQuery("#temp1").tmpl(post).prependTo(box).css({"opacity":0})
	.animate({"opacity":1}, 2000, "easeInOutQuint", (function(post, play, box) {
			setTimeout(function() {
				TWLIKEGADGETPOSTS_JAPAN.viewPost(post, play, box);
			}, 5000);
		})(postList, playList, box)
	);
}
