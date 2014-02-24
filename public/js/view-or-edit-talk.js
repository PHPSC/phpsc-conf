$(document).ready(function () {
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
					$('#tags').val(response.tags.join(', '))
						.selectize({ 
							delimiter: ',',
							addPrecedence: false,
							create: function(input) {
						        return {
						            value: input,
						            text: input
						        };
							}
						});					
					
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