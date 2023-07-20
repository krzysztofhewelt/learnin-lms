export default (fileId, fileType) => {
	axios
		.get('/download/' + fileId + '/' + fileType, {
			responseType: 'blob'
		})
		.then((res) => {
			let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
			let matches = filenameRegex.exec(res.headers['content-disposition']);
			let filename;

			if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');

			let fileURL = window.URL.createObjectURL(new Blob([res.data]));
			let fileLink = document.createElement('a');

			fileLink.href = fileURL;
			fileLink.setAttribute('download', filename);
			document.body.appendChild(fileLink);

			fileLink.click();
		});
};
