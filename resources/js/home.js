const toggleDropdownOptionVisibility = (categoryId) => {
	$('#main-categories-dropdown').find(`option[value="${categoryId}"]`).addClass('self-hidden');
};

$(document).ready(function() {
	$(document).on('click', '#btn-add', (e) => {
		console.log('aadsa');
		e.preventDefault();

		$('#save-changes').val('add');

		$('#modalData').trigger('reset');

		$('#category-modal').modal('show');
	});

	$(document).on('click', '.toggle-edit-modal', function() {
		const categoryId = $(this).val();
		const url = `home/edit/${categoryId}`;

		$.ajax({
			url,
			method: 'GET',
			dataType: 'json',
			success: (data) => {
				$('#category-id').val(data.category.id);
				$('#category-name').val(data.category.name);
				$('#save-changes').val('update');
				$('#main-categories-dropdown').val(data.category.parent_id);

				$('#main-categories-dropdown option').each(function() {
					$(this).removeClass('self-hidden');
				});

				if (!data.category.parent_id) {
					toggleDropdownOptionVisibility(data.category.id);
				}

				// On modal close
				$('#category-modal').on('hidden.bs.modal', function() {
					toggleDropdownOptionVisibility(data.category.id);
				});

				$('#category-modal').modal({});
			}
		});
	});

	$(document).on('click', '#save-changes', function(e) {
		e.preventDefault();
		const categoryId = $('#category-id').val();
		const state = $('#save-changes').val();
		const name = $('#category-name').val();
		const parentId = $('#main-categories-dropdown').val();
		let type = 'POST';
		let url = 'home/create';
		if (state === 'update') {
			type = 'PUT';
			url = 'home/update/' + categoryId;
		}
		$.ajax({
			type,
			url,
			data: { name, parent_id: parentId },
			dataType: 'json',

			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: (data) => {
				let optionItem = `<option value="${data.category.id}">${data.category.name}</option>`;

				let item = `<tr id="category-item-${data.category.id}" data-parentId="${data.category
					.parent_id}"><td>${data.category.id}</td><td>${data.category.name}</td><td>${data.category
					.parent_id}</td>`;
				item += `<td><div class="save-changes"><button class="btn btn-info edit toggle-edit-modal" value="${data
					.category.id}">Edit</button>`;
				item += ` <button class="btn btn-danger delete delete-category" value="${data.category
					.id}">Delete</button></div></td></tr>`;

				if (state === 'add') {
					$('#category-list').append(item);
					if (!data.category.parent_id) {
						$('#main-categories-dropdown').append(optionItem);
					}
				} else {
					$(`#category-item-${categoryId}`).replaceWith(item);
					if (!data.category.parent_id) {
						console.log('here');
						$('#main-categories-dropdown').find(`option[value="${categoryId}]"`).replaceWith(optionItem);
					}
				}

				$('#modalData').trigger('reset');

				$('#category-modal').modal('hide');

				$('.categories-container .delete-all-button').removeClass('disabled');

				Swal.fire('Success.', 'Your operation was successfully completed.', 'success');
			},
			error: () => {
				Swal.fire('Error.', 'There was a problem. Please try again.', 'error');
			}
		});
	});

	$(document).on('click', '.delete-category', function(e) {
		e.preventDefault();

		const categoryId = $(this).val();

		Swal.fire({
			title: 'Are you sure?',
			text: 'You are about to delete this cateory. This will also erase all subcategories.',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it.',
			customClass: {
				content: 'swal-custom-class',
				confirmButton: 'swal-custom-button',
				cancelButton: 'swal-custom-button'
			}
		}).then(function(result) {
			console.log('result');
			if (result.value) {
				if (result) {
					$.ajax({
						type: 'DELETE',
						url: `home/delete/${categoryId}`,
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						success: () => {
							$('#main-categories-dropdown').find(`option[value="${categoryId}]"`).remove();
							console.log(categoryId);
							$(`#category-item-${categoryId}`).remove();
							$(`tr[data-parentId="${categoryId}"]`).each(function() {
								$(this).remove();
							});
							if (!$('#category-list').children().length) {
								$('.categories-container .delete-all-button').addClass('disabled');
							}
							Swal.fire('Success.', 'Your category has been deleted.', 'success');
						},
						error: () => {
							Swal.fire('Error.', 'There was a problem deleting this category.', 'error');
						}
					});
				}
			}
		});
	});

	$(document).on('click', '.delete-all-button', function(e) {
		e.preventDefault();

		Swal.fire({
			title: 'Are you sure?',
			text: 'You are about to delete all categories.',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it.',
			customClass: {
				content: 'swal-custom-class',
				confirmButton: 'swal-custom-button',
				cancelButton: 'swal-custom-button'
			}
		}).then(function(result) {
			if (result.value) {
				if (result) {
					$.ajax({
						type: 'DELETE',
						url: `home/delete-all`,
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						success: () => {
							let categoryNone = `<option value="0">None</option>`;

							$(`#category-list`).children().remove();
							$('#main-categories-dropdown').find(`option`).remove();
							$('#main-categories-dropdown').append(categoryNone);
							Swal.fire('Success.', 'Your categories have been deleted.', 'success');

							$('.categories-container .delete-all-button').addClass('disabled');
						},

						error: () => {
							Swal.fire('Error.', 'There was a problem.', 'error');
						}
					});
				}
			}
		});
	});
});
