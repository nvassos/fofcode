/*global document,$*/
(function () {
	'use strict';
	
	var thumbSize = 255;
	
	function resizeController() {
		var width = window.innerWidth,
			height = window.innerHeight;

		var gallery = document.getElementById('gallery');
		gallery.style.width = (window.innerWidth) + 'px';

		var galleryDivs = gallery.querySelectorAll('.image-single'),
			newThumbSize = parseInt(width / (parseInt(width / thumbSize)));
		for(var i = 0, count = galleryDivs.length; i < count; i++) {
			galleryDivs[i].parentNode.style.width = newThumbSize + 'px';
			galleryDivs[i].parentNode.style.height = newThumbSize + 'px';
			galleryDivs[i].width = newThumbSize;
			galleryDivs[i].height = newThumbSize;
		}
	}
	
	function eventController(evt) {
		var target = evt.target;

		if (target.id.indexOf('delete') !== -1) {
			var id = target.id.replace('delete', '');
			if (confirm('do you really want to delete this pic?')) {
				var ajaxRequest = new XMLHttpRequest();
				ajaxRequest.onreadystatechange = function() {
					if(ajaxRequest.readyState === 4) {
						//document.location.reload();
						var deleteElement = document.getElementById('delete' + id).parentNode;
						deleteElement.parentNode.removeChild(deleteElement);
					}
				};
				ajaxRequest.open('GET', 'delete.php?id=' + id, true);
				ajaxRequest.send();
			}
		}
	}
	
	window.onload = function() {
		resizeController();
		window.addEventListener('resize', resizeController, false);
		document.addEventListener('click', eventController, false);

		$('.image-single').unveil(200, function() {
			$(this).load(function() {
				this.style.opacity = 1;
			});
		});
	};
}());
