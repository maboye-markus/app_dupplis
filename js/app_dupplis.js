$(document).ready(() => {

	let	fileToConvert = document.getElementById("fileToConvert");

	fileToConvert.addEventListener("change", () => {

		let	fileNameDiv = document.getElementById("fileName");
		let	fileNameArray = fileToConvert.value.split('\\');
		let	i = -1;
		fileNameArray.forEach(() => { ++i; });

		let	fileName = fileNameArray[i];

		fileNameDiv.style.display = "block";
		fileNameDiv.innerText = "File selected: [ " + fileName + " ]";
	});

	function	showError(str)
	{
		let	error_message = document.getElementById("error_message");

		// if (typeof(timer_error) != 'undefined')
		// 	clearTimeout(timer_error);

		if (!error_message) {
			let	elem = document.createElement("div");

			elem.id = "error_message";
			document.body.appendChild(elem);
			error_message = document.getElementById("error_message");
		};

		let	error_style = {
			'position': 'fixed',
			'right': '0px', 'bottom': '0px', 'left': '0px',
			'padding': '20px',
			'background': 'red',
			'color': '#fff',
			'text-align': 'center', 'text-transform': 'uppercase',
			'font-weight': 'bold',
			'z-index': 2147483647,
			'transition': 'all 0.5s ease',
			'cursor': 'pointer',
		};
		Object.assign(error_message.style, error_style);

		error_message.innerText = str;
		error_message.addEventListener("click", () => {
			error_message.style.bottom = "-100px";
		})
		setTimeout(() => { error_message.style.bottom = "0px"; }, 100);

		// timer_error = setTimeout(() => { $("#error-message").css('bottom', '-100px'); }, 6000);
	}

	$(document).on("submit", "form", (e) => {

		let	value = document.getElementById("defaultWebTag").value;

		if (value === undefined || value == "")
			form_error = "You need to input a webTag.";
		value = fileToConvert.value;
		if (value === undefined || value == "")
			form_error = "You need to select a file.";

		if (form_error) {
			e.preventDefault();
			showError(form_error);
			form_error = undefined;
		}

	});
});
