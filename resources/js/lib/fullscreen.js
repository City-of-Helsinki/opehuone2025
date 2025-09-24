/* eslint-disable no-console */

export const enableFullScreen = () => {
	const element = document.querySelector('#concentration');

	if (element.requestFullscreen) element.requestFullscreen();
	else if (element.mozRequestFullScreen) element.mozRequestFullScreen();
	else if (element.webkitRequestFullscreen) element.webkitRequestFullscreen();
	else if (element.msRequestFullscreen) element.msRequestFullscreen();
};

export const disableFullScreen = () => {
	if (document.exitFullscreen) document.exitFullscreen();
	else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
	else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
	else if (document.msExitFullscreen) document.msExitFullscreen();
};
