$(document).ready(function () {
	atualizaLista();
});

function atualizaLista()
{
	$.ajax(
		{
			url: baseUrl + '/status',
			type: 'get',
			dataType: 'json',
			data: {},
			success: function (result)
			{
				$('#nrInscricoes').html(result.inscritos + '<br />Inscritos');
				$('#nrPresencas').html(result.presentes + '<br />Presentes');
				
				setTimeout(atualizaLista, 2000);
			}
		}
	);
}