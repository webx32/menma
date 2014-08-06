define(function(require, exports, module) {


	exports.initPage = function() {
		var cb = $(".piclist").eq(0).children('div').attr("data-src");

		var p = function(a) {
			console.log(a);
		};
		var img = new Image();
		var canvas = document.querySelector('canvas');
		canvas.style.backgroundColor = 'transparent';
		canvas.style.position = 'absolute';

		img.addEventListener('load', function(e) {
			var ctx;
			var w = img.width,
				h = img.height;
			var offsetX = canvas.offsetLeft,
				offsetY = canvas.offsetTop;
			var mousedown = false;

			function layer(ctx) {

				var img = document.getElementById("first");
				var pat = ctx.createPattern(img, "repeat");
				ctx.fillStyle = pat;
				ctx.fillRect(0, 0, w, h);
			}

			function eventDown(e) {
				e.preventDefault();
				mousedown = true;
			}

			function eventUp(e) {
				e.preventDefault();
				mousedown = false;
			}

			function eventMove(e) {
				e.preventDefault();

				if (mousedown) {
					if (e.changedTouches) {
						e = e.changedTouches[e.changedTouches.length - 1];
					}
					var x = (e.clientX + document.body.scrollLeft || e.pageX) - offsetX || 0,
						y = (e.clientY + document.body.scrollTop || e.pageY) - offsetY || 0;
					// p(x+","+y)
					with(ctx) {
						beginPath()
						arc(x, y, 40, 0, Math.PI * 2);
						fill();
					}
					
				}
			}

			canvas.width = w;
			canvas.height = h;
			canvas.style.backgroundImage = 'url(' + cb + ')';
			ctx = canvas.getContext('2d');
			ctx.fillStyle = 'transparent';
			ctx.fillRect(0, 0, w, h);
			layer(ctx);

			ctx.globalCompositeOperation = 'destination-out';
			canvas.addEventListener('touchstart', eventDown);
			canvas.addEventListener('touchend', eventUp);
			canvas.addEventListener('touchmove', eventMove);
			canvas.addEventListener('mousedown', eventDown);
			canvas.addEventListener('mouseup', eventUp);
			canvas.addEventListener('mousemove', eventMove);
		});
		img.src = 'images/1.jpg';

		$(".piclistwarp div[data-src]").each(function(index, el) {
			$(this).css('background-image', 'url(' + $(this).attr("data-src") + ')');
		});


	}
});