<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
<head>
	<title>Note App</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<style>
		/* RTL CSS Styles */
		.navbar {
			text-align: right;
		}
		.modal-title{
			text-align: right;
		}

		.modal-body {
			text-align: right;
		}
		.card-header {
			text-align: right;
		}
		.card-body{
			text-align: right;
		}

	</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="#">Note App</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav ml-auto">
			<?php if (!isset($_SESSION['user_id'])) { ?>
				<!-- code for non-logged in users -->
				<li class="nav-item">
					<a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">ورود</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" data-toggle="modal" data-target="#registerModal">ثبت نام</a>
				</li>
			<?php } else { ?>
				<!-- code for logged in users -->
				<li class="nav-item">
					<a class="nav-link" href="<?php echo site_url('Main/logout'); ?>">خروج</a>
				</li>
			<?php } ?>

		</ul>
	</div>
</nav>
<?php if (!isset($_SESSION['user_id'])) { ?>
	<!-- code for non-logged in users -->
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center mt-5">
				<h1>Note Taking App</h1>
				<p class="lead mt-3">سریع ، کارآمد ، بهینه</p>
				<a href="#" data-toggle="modal" data-target="#loginModal" class="btn btn-primary mt-4">Get Started</a>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="container mt-5">
		<div class="row">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						یادداشت جدید
					</div>
					<div class="card-body">
						<form>
							<div class="form-group">
								<label for="title" class="card-c-t">عنوان</label>
								<input type="text" class="form-control" id="title" name="title">
							</div>
							<div class="form-group">
								<label for="content">محتوای یادداشت</label>
								<textarea class="form-control" id="content" name="content"></textarea>
							</div>
							<button type="button" class="btn btn-primary" onclick="createNote()">ایجاد یادداشت</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						یادداشت ها
					</div>
					<div class="card-body">
						<ul class="list-group" id="notesList">

						</ul>
					</div>
				</div>
				<div id="pagination"></div>
			</div>
		</div>
	</div>
<?php } ?>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="loginModalLabel">ورود کاربران</h5>

			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="loginUsername">نام کاربری</label>
						<input type="text" class="form-control" id="loginUsername" name="username">
					</div>
					<div class="form-group">
						<label for="loginPassword">رمز عبور</label>
						<input type="password" class="form-control" id="loginPassword" name="password">
					</div>
					<button type="button" class="btn btn-primary" onclick="login()">ورود</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="registerModalLabel">ثبت نام</h5>

			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="registerUsername">نام کاربری</label>
						<input type="text" class="form-control" id="registerUsername" name="username">
					</div>
					<div class="form-group">
						<label for="registerPassword">رمز عبور</label>
						<input type="password" class="form-control" id="registerPassword" name="password">
					</div>
					<button type="button" class="btn btn-primary" onclick="register()">ایجاد حساب</button>
				</form>
				<a href="#" onclick="OpenLogin(); return false">قبلا ثبت نام کرده اید؟</a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">ویرایش یادداشت</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editForm">
					<input type="hidden" id="note_id" name="note_id" value="">
					<div class="form-group">
						<label for="title">عنوان</label>
						<input type="text" class="form-control" id="title" name="title">
					</div>
					<div class="form-group">
						<label for="content">محتوا</label>
						<textarea class="form-control" id="content" name="content" rows="5"></textarea>
					</div>
					<button type="button" class="btn btn-primary" id="submitEdit">ثبت تغییرات</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="viewModalLabel">اطلاعات بادداشت</h5>
			</div>
			<div class="modal-title">
				<p></p>
			</div>
			<div class="modal-body">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
				<button type="button" class="btn btn-primary edit-btn" data-note-id="">ویرایش</button>
				<button type="button" class="btn btn-danger delete-btn" data-note-id="">حذف</button>
			</div>
		</div>
	</div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
	function OpenLogin() {
		$('#registerModal').modal('hide')
		setTimeout(function () {
			$('#loginModal').modal('show');
		}, 50);
	}

	// <p>No notes found.</p>
	// <ul class="list-group">
	// 	<li class="list-group-item">
	// 		<h5></h5>
	// 		<p></p>
	// 		<button type="button" class="btn btn-primary"
	// 				onclick="editNote()">Edit
	// 		</button>
	// 		<button type="button" class="btn btn-danger"
	// 				onclick="deleteNote()">Delete
	// 		</button>
	// 	</li>
	// </ul>
	//
	// $('tr').each(function (index) {
	// 	if (response[index-1]) {
	// 		const noteId = response[index-1].id;
	// 		$(this).data('note-id', noteId);
	// 	}
	// });
	$(function () {
		getNotes(0)
	});

	function register() {
		var username = $('#registerUsername').val();
		var password = $('#registerPassword').val();
		$.ajax({
			url: "<?php echo site_url('Main/register'); ?>",
			type: "POST",
			data: {
				username: username,
				password: password
			},
			dataType: 'json',
			success: function (data) {
				if (data.code > 0) {
					Swal.fire({
						icon: 'success',
						title: 'Registration successful',
						showConfirmButton: false,
						timer: 1500
					});
					setTimeout(function () {
						location.reload();
					}, 1000);
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: data.message
					});
				}
			}
		});
	}

	function login() {
		var username = $('#loginUsername').val();
		var password = $('#loginPassword').val();
		$.ajax({
			url: "<?php echo site_url('Main/login'); ?>",
			type: "POST",
			data: {
				username: username,
				password: password
			},
			dataType: 'json',
			success: function (data) {
				if (data.code > 0) {
					Swal.fire({
						icon: 'success',
						title: 'Login successful',
						showConfirmButton: false,
						timer: 1500
					});
					setTimeout(function () {
						location.reload();
					}, 1000);
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: data.message
					});
				}
			}
		});
	}

	function createNote() {
		var title = $('#title').val();
		var content = $('#content').val();
		$.ajax({
			url: "<?php echo site_url('Main/create_note'); ?>",
			type: "POST",
			data: {
				title: title,
				content: content
			},
			dataType: 'json',
			success: function (data) {
				if (data.code > 0) {
					Swal.fire({
						icon: 'success',
						title: 'Note created successfully',
						showConfirmButton: false,
						timer: 1000
					});
					// Clear the input fields
					$('#title').val('');
					$('#content').val('');
					setTimeout(function () {
						getNotes(0)
					}, 1000);
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: data.message
					});
				}
			}
		});
	}

	function getNotes(pageIndex, pageSize) {
		let totalCount;  // Declare outside so we can access it on each page

		if (typeof pageSize == 'undefined') {
			pageSize = 5;
		}
		$('#notesList').html('');
		let q = $.ajax({
			url: '<?php echo site_url('main/get_notes') ?>',
			data: {
				page_index: pageIndex,
				page_size: pageSize,
			},
			type: 'post',
			dataType: 'json'
		});

		q.fail(function () {
			alert('ajax failed #1208')
		});

		q.done((response) => {
			totalCount = response.total_notes;  // Get total count
			let count = totalCount - (pageIndex * pageSize);  // Calculate count for current page
			if (response.notes.length > 0) {
				let table = $('<table>').addClass('table');
				let thead = $('<thead>');
				let tbody = $('<tbody>');
				let trHead = $('<tr>');
				let thCount = $('<th>').html('Count');
				let thTitle = $('<th>').html('Title');
				let thContent = $('<th>').html('Content');
				let thActions = $('<th>').html('Actions');
				$(trHead).append(thCount, thTitle, thContent, thActions);
				$(thead).append(trHead);
				$(table).append(thead, tbody);

				response.notes.forEach(function (note) {
					let tr = $('<tr>');
					$(tr).data('note-id', note.id)
					let tdCount = $('<td>').html(count);
					let tdTitle = $('<td>').html(note.title);
					let tdContent = $('<td>').html(note.content);
					let tdActions = $('<td>').html(`
                    <button type="button" class="btn btn-primary view-btn" data-toggle="modal" data-target="#viewModal" data-note-id="${note.id}">مشاهده</button>
                `);
					$(tr).append(tdCount, tdTitle, tdContent, tdActions);
					$(tbody).append(tr);
					count--;  // Decrement count for next row
				});


				$('#notesList').append(table);

				// Attach click event listener to "View" buttons
				$('.view-btn').on('click', function () {
					const noteId = $(this).data('note-id');
					viewNote(noteId);
				});

				// Create pagination links
				let totalPages = Math.ceil(response.total_notes / pageSize);
				let pagination = $('<ul>').addClass('pagination justify-content-center mt-3');
				for (let i = 0; i < totalPages; i++) {
					let link = $('<a>').attr('href', '#').addClass('page-link').html(i + 1);
					let listItem = $('<li>').addClass('page-item');
					if (i === pageIndex) {
						$(listItem).addClass('active');
					} else {
						$(link).on('click', function () {
							getNotes(i, pageSize);
						});
					}
					$(listItem).append(link);
					$(pagination).append(listItem);
				}

				$('#pagination').html('').append(pagination);

			} else {
				$('#notesList').html('<p>No notes found.</p>');
			}
		});
	}

	function viewNote(noteId) {
		let q = $.ajax({
			url: '<?php echo site_url('main/get_note_details') ?>',
			data: {
				note_id: noteId,
			},
			type: 'post',
			dataType: 'json'
		});

		q.fail(function () {
			alert('ajax failed #1208')
		});

		q.done(function (response) {
			$('#viewModal .modal-header').html('اطلاعات یادداشت');
			$('#viewModal .modal-title').html(response.title);
			$('#viewModal .modal-body p').html(response.content);
			$('#viewModal .edit-btn').attr('data-note-id', response.id);
			$('#viewModal .delete-btn').attr('data-note-id', response.id);
			$('#viewModal').modal('show');

			// Add event listener to the "Edit" button
			$('#viewModal .edit-btn').on('click', function () {
				let noteId = $(this).attr('data-note-id');
				editNote(noteId);
				$('#viewModal').modal('hide');
			});

			// Add event listener to the "Delete" button
			$('#viewModal .delete-btn').on('click', function () {
				let noteId = $(this).attr('data-note-id');
				deleteNote(noteId);
				$('#viewModal').modal('hide');
			});
		});
	}

	function editNote(id) {
		// Retrieve the current title and content of the note
		$.ajax({
			url: "<?php echo site_url('Main/get_note/'); ?>" + id,
			type: "GET",
			dataType: "json",
			success: function (data) {
				// Populate the title and content input fields with the current values
				$('#editModal #title').val(data.title);
				$('#editModal #content').val(data.content);
				$('#editModal #note_id').val(id);
				$('#editModal').modal('show');
			},
			error: function () {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Something went wrong!'
				});
			}
		});

		// Add an event listener to the "Submit Edit" button
		$('#submitEdit').on('click', function () {
			// Send an AJAX request to update the note
			$.ajax({
				url: "<?php echo site_url('Main/update_note'); ?>",
				type: "POST",
				data: {
					note_id: $('#editModal #note_id').val(),
					title: $('#editModal #title').val(),
					content: $('#editModal #content').val()
				},
				dataType: "json",
				success: function (data) {
					if (data.code === 10) {
						Swal.fire({
							icon: 'success',
							title: 'Note updated successfully',
							showConfirmButton: false,
							timer: 1000
						});
						setTimeout(function () {
							getNotes(0)
						}, 1000);
						$('#editModal').modal('hide');
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: data.message
						});
					}
				}
			});
		});
	}

	function deleteNote(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "<?php echo site_url('Main/delete_note'); ?>",
					type: "POST",
					data: {note_id: id},
					dataType: 'json',
					success: function (data) {
						if (data.code === 9) {
							Swal.fire({
								icon: 'success',
								title: 'Note deleted successfully',
								showConfirmButton: false,
								timer: 1000
							});
							setTimeout(function () {
								location.reload();
							}, 1000);
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: data.message
							});
						}
					}
				});
			}
		});
	}


</script>

</body>
</html>
