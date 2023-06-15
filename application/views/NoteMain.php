<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<title>Note App</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
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
					<a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" data-toggle="modal" data-target="#registerModal">Register</a>
				</li>
			<?php } else { ?>
				<!-- code for logged in users -->
				<li class="nav-item">
					<a class="nav-link" href="<?php echo site_url('Main/logout'); ?>">Logout</a>
				</li>
			<?php } ?>

		</ul>
	</div>
</nav>

<div class="container mt-5">
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					Create Note
				</div>
				<div class="card-body">
					<form>
						<div class="form-group">
							<label for="title">Title</label>
							<input type="text" class="form-control" id="title" name="title">
						</div>
						<div class="form-group">
							<label for="content">Content</label>
							<textarea class="form-control" id="content" name="content"></textarea>
						</div>
						<button type="button" class="btn btn-primary" onclick="createNote()">Create</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					Notes
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

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="loginModalLabel">Login</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="loginUsername">Username</label>
						<input type="text" class="form-control" id="loginUsername" name="username">
					</div>
					<div class="form-group">
						<label for="loginPassword">Password</label>
						<input type="password" class="form-control" id="loginPassword" name="password">
					</div>
					<button type="button" class="btn btn-primary" onclick="login()">Login</button>
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
				<h5 class="modal-title" id="registerModalLabel">Register</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="registerUsername">Username</label>
						<input type="text" class="form-control" id="registerUsername" name="username">
					</div>
					<div class="form-group">
						<label for="registerPassword">Password</label>
						<input type="password" class="form-control" id="registerPassword" name="password">
					</div>
					<button type="button" class="btn btn-primary" onclick="register()">Register</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit Note</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="title">Title</label>
						<input type="text" class="form-control" id="title" name="title">
					</div>
					<div class="form-group">
						<label for="content">Content</label>
						<textarea class="form-control" id="content" name="content"></textarea>
					</div>
					<button type="button" class="btn btn-primary" id="submitEdit">Submit Edit</button>
				</form>
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
					}, 1500);
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
					}, 1500);
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
						timer: 1500
					});
					// Clear the input fields
					$('#title').val('');
					$('#content').val('');
					setTimeout(function () {
						getNotes(0)
					}, 1500);
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
		if(typeof pageSize == 'undefined'){
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

			if (response.notes.length > 0) {
				let table = $('<table>').addClass('table');
				let thead = $('<thead>');
				let tbody = $('<tbody>');
				let trHead = $('<tr>');
				let thId = $('<th>').html('ID');
				let thTitle = $('<th>').html('Title');
				let thContent = $('<th>').html('Content');
				let thActions = $('<th>').html('Actions');
				$(trHead).append(thId, thTitle, thContent, thActions);
				$(thead).append(trHead);
				$(table).append(thead, tbody);

				response.notes.forEach(function (note) {
					let tr = $('<tr>');
					$(tr).data('note-id', note.id)
					let tdid = $('<td>').html(note.id);
					let tdTitle = $('<td>').html(note.title);
					let tdContent = $('<td>').html(note.content);
					let tdActions = $('<td>').html(`
					<button type="button" class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editModal">Edit</button>
					<button type="button" class="btn btn-danger delete-btn">Delete</button>
				`);
					$(tr).append(tdid, tdTitle, tdContent, tdActions);
					$(tbody).append(tr);
				});

				$('#notesList').append(table);

				$('.edit-btn').on('click', function() {
					const noteId = $(this).closest('tr').data('note-id');
					editNote(noteId);
				});

				$('.delete-btn').on('click', function (event) {
					console.log(event.target);
					let xx = $(event.target).parents('tr')[0]
					console.log(xx)
					const noteId = $(xx).data('note-id');
					console.log(noteId)
					deleteNote(noteId);
				});
				// Calculate the number of pages
				let totalPages = Math.ceil(response.total_notes / pageSize);

// Create pagination links
				let pagination = $('<ul>').addClass('pagination');
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

	function editNote(id) {

		// Retrieve the current title and content of the note
		$.ajax({
			url: "<?php echo site_url('Main/get_note/'); ?>" + id,
			type: "GET",
			dataType: "json",
			success: function (data) {
				console.log(data.title)
				$('#editModal #title').val(data.title);
				$('#editModal #content').val(data.content);

				// Populate the title and content input fields with the current values
				// setTimeout(function () {
				// 	console.log(data.title)
				//
				// }, 1500);

			},
			error: function () {
				Swal.fire({
					icon: 'error',
					title: 'Oops2...',
					text: 'Something went wrong!'
				});
			}
		});

		// Add an event listener to the "Submit Edit" button
		$('#submitEdit').on('click', function() {
			// Send an AJAX request to update the note
			$.ajax({
				url: "<?php echo site_url('Main/update_note'); ?>",
				type: "POST",
				data: {
					note_id: id,
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
							timer: 1500
						});
						setTimeout(function () {
							getNotes(0)
						}, 1500);
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
								timer: 1500
							});
							setTimeout(function () {
								location.reload();
							}, 1500);
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
