
function beforeAjax()
{
    Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
        Swal.fire({
            html: "<p class='mt-4'>Sedang Memproses Data</p>",
            showConfirmButton: false,
            allowEscapeKey: false,
            allowOutsideClick: false
        });
    });
}

function errorSend (jqXHR, exception)
{
	var response = "";
	if (jqXHR.status === 0) {
		response = 'Koneksi ke server gagal';
	} else if (jqXHR.status == 404) {
		response = 'File eksekusi ajax tidak ditemukan';
	} else if (jqXHR.status == 500) {
		response = 'Internal Server Error [500]';
	} else if (exception === 'parsererror') {
		response = 'Parsing data JSON gagal';
	} else if (exception === 'timeout') {
		response = 'Permintaan request waktu telah habis';
	} else if (exception === 'abort') {
		response = 'Request data dibatalkan';
	} else {
		response = 'Uncaught Error.n' + jqXHR.responseText;
	}
    Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
        Swal.fire({
            title: "Error",
            text: response,
            type: "error",
            showConfirmButton: false,
            allowEscapeKey: false,
            allowOutsideClick: false
        });
    });


}

function sendFormData(urls, datas, resultSend, bfrSend = beforeAjax())
{
	$.ajax({
		url: urls,
		type: "POST",
		dataType: "JSON",
		cache: false,
		data: datas,
		processData: false,
		contentType: false,
		beforeSend: bfrSend,
		success: resultSend,
		error: errorSend
	})
}

function GetData(urls, resultSend, bfrSend = beforeAjax())
{
	$.ajax({
		url: urls,
		type: "GET",
		cache: false,
		processData: false,
		contentType: false,
		beforeSend: bfrSend,
		success: resultSend,
		error: errorSend
	})
}

