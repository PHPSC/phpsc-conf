function displayBlankForm()
{
	$('#addOrEdit #mainButton').text('Salvar');
	$('.companyData').removeClass('hide');
	$('.supporterData').removeClass('hide');
	
	$('#addOrEdit form').prop('enctype', 'multipart/form-data');
	$('#addOrEdit form').unbind('submit');
	$('#addOrEdit form input, #addOrEdit form textarea').prop('required', true);
	$('#phone, #twitterId, #fanpage').prop('required', false);
	
	$('#addOrEdit form').submit(function () {
		$(this).ajaxSubmit(
			{
				url: baseUrl + 'supporters',
				type: 'POST',
				dataType: 'json',
				error: function (xhr, status, error) {
					console.log(xhr.responseJSON.message);
				}
			}
		);
		
		return false
	});
}

function populateForm(company)
{
	$('#addOrEdit #mainButton').text('Salvar');
	$('.companyData').removeClass('hide');
	$('.companyLogo').addClass('hide');
	$('.supporterData').removeClass('hide');
	
	$('#name').val(company.name);
	$('#email').val(company.email);
	$('#phone').val(company.phone);
	$('#website').val(company.website);
	$('#twitterId').val(company.twitterId);
	$('#fanpage').val(company.fanpage);
	
	$('.companyData input').prop('disabled', true);
	
	$('#addOrEdit form').unbind('submit');
	$('#addOrEdit form').submit(function () {
		$(this).ajaxSubmit(
			{
				url: baseUrl + 'supporters',
				type: 'POST',
				dataType: 'json'
			}
		);
		
		return false
	});
}

$(document).ready(function () {
	var defaultAction = function () {
		var socialId = $('#socialId').val();
		
		if (socialId.length < 14) {
			$('#socialId').focus();
			return ;
		}
		
		$('#socialId').prop('readonly', true);
		$('.companyData').addClass('hide');
		$('.supporterData').addClass('hide');
		
		$.ajax(
			{
				url: baseUrl + 'companies',
				type: 'GET',
				dataType: 'json',
				data: {
					socialId: socialId 
				},
				success: function (response)
				{
					if (response.length == 0) {
						displayBlankForm();
						
						return ;
					}
					
					populateForm(response[0]);
				}
			}
		);

		return false;
	};
	
	$('#addOrEdit').on('hidden.bs.modal', function () {
		$('#addOrEdit form').prop('enctype', 'application/x-www-form-urlencoded');
		$('#addOrEdit form').unbind('submit');
		$('#addOrEdit form').submit(defaultAction);
		
		$('.companyData').addClass('hide');
		$('.supporterData').addClass('hide');
		$('.companyData input').val('');
		$('.supporterData textarea').val('');
		$('#addOrEdit form input').prop('required', false);
		$('#addOrEdit form textarea').prop('required', false);
		$('#socialId').prop('readonly', false).val('').prop('required', true);
		
		$('#addOrEdit #mainButton').html(
			'<span class="glyphicon glyphicon-search"></span> Buscar'
		);
	});
	
	$('#addOrEdit form').submit(defaultAction);
	
	$('#addSupporter').click(function () {
		$('#addOrEdit #title').text('Novo apoiador');
		$('#addOrEdit').modal('show');
	});
	
	$('a.btn.btn-xs.btn-info[id|="action"]').click(function () {
		$('#gridMsg').removeClass('alert-danger')
					 .addClass('alert-info');
			
		$('#gridMsg h4').html('Atenção');
		$('#gridMsg span').html('Nesta página você visualizará informações sobre os trabalhos que você submeteu.');
		
		var id = $(this).prop('id').split('-')[1];
		
		$.ajax(
			{
				url: baseUrl + 'call4papers/submissions/' + id,
				type: 'GET',
				success: function (response)
				{
					if (response.error) {
						$('#gridMsg').removeClass('alert-info')
									 .addClass('alert-danger');
						
						$('#gridMsg h4').html('Ocorreu um problema durante sua solicitação!');
						$('#gridMsg span').html(response.error);
						
						return false;
					}
					
					$('#title').val(response.title);
					$('#type').val(response.type.id);
					$('#complexity').val(response.complexity);
					$('#shortDescription').val(response.shortDescription);
					$('#longDescription').val(response.longDescription);
					$('#tags').val(response.tags.join(', '));
					
					$('#editForm').unbind('submit');
					$('#editForm').submit(function() {
						$('#editForm .modal-footer .btn').prop('disabled', true);
						
						$.ajax(
							{
								url: baseUrl + 'call4papers/submissions/' + id,
								type: 'PUT',
								data: {
									title: $('#title').val(),
									type: $('#type').val(),
									complexity: $('#complexity').val(),
									shortDescription: $('#shortDescription').val(),
									longDescription: $('#longDescription').val(),
									tags: $('#tags').val()
								},
								success: function (response)
								{
									$('#editForm .alert').removeClass('alert-success alert-danger');
									
									if (response.error) {
										$('#editForm .alert').addClass('alert-danger');
										$('#editForm .alert h4').html('Ocorreu um erro em sua solicitação');
										$('#editForm .alert span').html(response.error);
										$('#editForm .alert').fadeIn().delay(1000).fadeOut();
										$('#editForm .modal-footer .btn').prop('disabled', false);
										
										return false;
									}
									
									$('#editForm .alert').addClass('alert-success');
									$('#editForm .alert h4').html('Dados alterados com sucesso!');
									$('#editForm .alert span').html('');
									$('#editForm .alert').fadeIn();
									
									setTimeout(
										function()
										{
											window.location.reload();
										},
										1200
									);
								}
							}
						);
						
						return false;
					});
					
					$('#edit').modal('show');
				}
			}
		);
		
		return false;
	});
});