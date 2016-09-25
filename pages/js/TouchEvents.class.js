/**
 * Touch Events Class Created By Dmitri Russu dmitri.russu@gmail.com
 * @type {Function}
 */
var TouchEvents = (function(object){

	/**
	 * Tchouch events Class
	 * @constructor
	 */
	function TouchEvents(object) {

		if (object === undefined) {
			alert('TouchEvents Undefined object to start touch');
		}

		var myLatestTap = 0;


		/**
		 * Private Method touche callback
		 * @param event
		 * @param userCallBack
		 */
		function callBack(event, userCallBack) {
			var touches = event.changedTouches;

			for (var i=0; i < touches.length; i++) {
				userCallBack(touches[i], event);
			}
		}


		/**
		 * Start Event on Finger Touch
		 * @param userCallBack
		 * @returns {TouchEvents}
		 */
		this.tap = function(userCallBack) {
			object.addEventListener("touchstart", function(event){

				callBack(event, userCallBack);

			}, false);
			return this;
		};


		/**
		 * Event Double Tap on touch screen
		 * @param userCallBack
		 */
		this.doubleTap = function(userCallBack) {

			object.addEventListener("touchstart", function(event){
				var now = new Date().getTime();
				var timeSince = now - myLatestTap;

				if((timeSince < 600) && (timeSince > 0)){
					callBack(event, userCallBack);
				}

				myLatestTap = new Date().getTime();
			}, false);

			return this;
		};


		/**
		 * End of Finger UnTouch
		 * @param userCallBack
		 * @returns {TouchEvents}
		 */
		this.leave = function(userCallBack) {
			object.addEventListener("touchleave",  function(event){

				callBack(event, userCallBack);

			}, false);

			return this;
		};


		/**
		 * End of Finger UnTouch
		 * @param userCallBack
		 * @returns {TouchEvents}
		 */
		this.end = function(userCallBack) {
			object.addEventListener("touchend",  function(event){

				callBack(event, userCallBack);

			}, false);

			return this;
		};


		/**
		 * End of Finger UnTouch
		 * @param userCallBack
		 * @returns {TouchEvents}
		 */
		this.cancel = function(userCallBack) {
			object.addEventListener("touchcancel",  function(event){

				callBack(event, userCallBack);

			}, false);

			return this;
		};


		/**
		 * Move Event on touch
		 * @param userCallBack
		 * @returns {TouchEvents}
		 */
		this.move = function(userCallBack) {
			object.addEventListener("touchmove",  function(event){

				callBack(event, userCallBack);

			}, false);

			return this;
		};

		return this;
	}
	return new TouchEvents(object);
});
