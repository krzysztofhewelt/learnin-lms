export const clickOutside = {
	beforeMount: function (element, binding) {
		//  check that click was outside the el and his children
		element.clickOutsideEvent = function (event) {
			// and if it did, call method provided in attribute value
			if (!(element === event.target || element.contains(event.target))) {
				binding.value(event);
			}
		};
		document.body.addEventListener('click', element.clickOutsideEvent);
	},
	unmounted: function (element) {
		document.body.removeEventListener('click', element.clickOutsideEvent);
	}
};
