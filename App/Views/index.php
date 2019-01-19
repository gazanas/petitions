<html>
    <head>
    	<script
    		src='https://code.jquery.com/jquery-3.3.1.min.js'
        	integrity='sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8='
            crossorigin='anonymous'>
        </script>
		<script 
			src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js' 
			integrity='sha384-zDnhMsjVZfS3hiP7oCBRmfjkQC4fzxVxFhBx8Hkz2aZX8gEvA/jsP3eXRCvzTofP' 
			crossorigin='anonymous'>
		</script>
		<link 
        	rel='stylesheet' 
        	href='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' 
        	integrity='sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS' 
        	crossorigin='anonymous'>
		<style>
		  body {
		      background-color: rgb(65, 179, 163);
		  }
		  
		  a:hover {
		      text-decoration: none;
		  }
		  
		  p {
		      word-break: break-word;
		  }
		  
		  .add-petition a {
		      font-size: 22px;
		      font-weight: bold;
		      text-align: right;
		  }
		  
		  .petitions-container {
		      background-color: rgb(255, 255, 255);
		      margin-top: 15px;
		      padding: 5px;
		      border: 1px solid rgb(0, 0, 0);
		      border-radius: 3px;
		  }
		  
		  .thumbnail img {
		      width: 300px;
		      height: 200px;
		  }
		  
		</style>
    </head>
	<body>
		<div class='container petitions-container'>
			<div class='add-petition row col-12'>
				<a class='col-12' href='#' data-toggle='modal' data-target='#petitionModal'>+Add Petition</a>
			</div>
			<div class='modal fade' id='petitionModal' tabindex='-1' role='dialog' aria-hidden='true'>
			<div class='modal-dialog' role='document'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h5 class='modal-title' id='exampleModalLabel'>New Petition</h5>
					</div>
					<div class='modal-body'>
						<form>
							<div class='form-group'>
								<label for='petition-title' class='col-form-label'>Title:</label>
								<input type='text' name='title' class='form-control' id='petition-title'>
								<p class='title-error small text-danger'></p>
							</div>
							<div class='form-group'>
								<label for='petition-image' class='col-form-label'>Image:</label>
								<input type='text' name='image' class='form-control' id='petition-image'>
								<p class='image-error small text-danger'></p>
							</div>
							<div class='form-group'>
								<label for='petition-goal' class='col-form-label'>Goal:</label>
								<input type='text' name='goal' class='form-control' id='petition-goal'>
								<p class='goal-error small text-danger'></p>
							</div>
							<div class='form-group'>
								<label for='petition-summary' class='col-form-label'>Summary:</label>
								<textarea name='summary' class='form-control' id='petition-summary'></textarea>
								<p class='summary-error small text-danger'></p>
							</div>
						</form>
					</div>
					<div class='modal-footer'>
						<button type='button' class='create-petition btn btn-primary'>Create</button>
					</div>
				</div>
			</div>
		</div>
		</div>
		<script type='text/javascript'>
			$(document).ready(function() {
				$.ajax({
					url: '/get_petitions',
					type: 'GET',
					data: {},
					dataType: 'json',
					success: function(data, status) {
						$.each(data, function(index, value) {
							$('.petitions-container').append(`
						            <div class='petition row'>
					            	<div class='col-12'>
					                	<div class='row col-9'>
					                  		<div class='col-11'>
					                    		<h4><strong><a href='petition/`+value.id+`'>`+value.title+`</a></strong></h4>
					                  		</div>
					                	</div>
					                	<div class='row col-12'>
					                    	<a href='#' class='thumbnail'>
					                        	<img src='`+value.image+`' alt=''>
					                    	</a>
					                  		<div class='col-6'>      
					                    		<p>
					                    			`+value.summary+`
					                      		</p>
					                  		</div>
					                	</div>
					              	</div>
					            </div>
					            <hr>`);
						});
					}, error: function(xhr) {
						alert(xhr.responseText);
					}
				});
			});

			$('.create-petition').click(function() {
				$.ajax({
					url: '/create_petition',
					type: 'POST',
					data: {'title': $('#petition-title').val(), 'image': $('#petition-image').val(), 'goal': $('#petition-goal').val(), 'summary': $('#petition-summary').val()},
					dataType: 'json',
					success: function(data, status) {
						if(data.error == true)
						{
							$('.'+data.type).html(data.message);
							return;
						} else {
							location.reload();
						}
					}, error: function(xhr) {
						alert(xhr.responseText);
					}
				});
			});
		</script>
	</body>
</html>
            
            