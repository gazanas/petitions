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
		  
		  .petition-container {
		      background-color: rgb(255, 255, 255);
		      margin-top: 15px;
		      padding: 5px;
		      border: 1px solid rgb(0, 0, 0);
		      border-radius: 3px;
		  }
		  
		  .petition-container img {
		      height: 400px;
		  }
		  
		  .progress-stripped {
		      background-color: rgb(211, 211, 211);
		  }
		  
		  .progress-container {
		      width: 30%;
		  }
		  
		  .progress-bar {
		      width: 1%;
		      max-width: 100%;
		  }
		</style>
    </head>
	<body>
		<div class='container petition-container'>
			<div class='petition-info row'>
            	<div class='image container col-12'>
            		
            	</div>
            	<div class='summary container col-12'>
            		<p></p>
            	</div>
            </div>
           	<div class='modal fade' id='voteModal' tabindex='-1' role='dialog' aria-hidden='true'>
    			<div class='modal-dialog' role='document'>
    				<div class='modal-content'>
    					<div class='modal-header'>
    						<h5 class='modal-title' id='exampleModalLabel'>Sign</h5>
    					</div>
    					<div class='modal-body'>
    						<form>
    							<div class='form-group'>
    								<label for='vote-name' class='col-form-label'>Name:</label>
    								<input type='text' name='name' class='form-control' id='vote-name'>
    								<p class='name-error small text-danger'></p>
    							</div>
    							<div class='form-group'>
    								<label for='vote-email' class='col-form-label'>Email:</label>
    								<input type='text' name='email' class='form-control' id='vote-email'>
    								<p class='email-error small text-danger'></p>
    							</div>
    							<div class='form-group'>
    								<label for='vote-country' class='col-form-label'>Country:</label>
    								<select multiple class='country form-control' id='vote-country'>
    								</select>
    								<p class='country-error small text-danger'></p>
    							</div>
    						</form>
    					</div>
    					<div class='modal-footer'>
    						<button type='button' class='vote btn btn-primary'>Vote</button>
    					</div>
    				</div>
    			</div>
    		</div>
            <div class='progress-container'>
                <div class='progress progress-stripped active'>
                  <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='1' aria-valuemin='0' aria-valuemax='100'></div>
                </div>
            </div>
            <div class='row'>
                <div class='col-2 percentage'>
                	
                </div>
            </div>
            <div class='row'>
                <div class='col-2 last-vote'>
                
                </div>
                <div class='col-2 last-country'>
                
                </div>
            </div>
    		<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#voteModal'>Vote</button>
    	</div>
      
        <script>
        	var country_list = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas"
                            	,"Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands"
                            	,"Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica"
                            	,"Cote D Ivoire","Croatia","Cruise Ship","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea"
                            	,"Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana"
                            	,"Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India"
                            	,"Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kuwait","Kyrgyz Republic","Laos","Latvia"
                            	,"Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Mauritania"
                            	,"Mauritius","Mexico","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Namibia","Nepal","Netherlands","Netherlands Antilles","New Caledonia"
                            	,"New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal"
                            	,"Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Satellite","Saudi Arabia","Senegal","Serbia","Seychelles"
                            	,"Sierra Leone","Singapore","Slovakia","Slovenia","South Africa","South Korea","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","St. Lucia","Sudan"
                            	,"Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia"
                            	,"Turkey","Turkmenistan","Turks &amp; Caicos","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay"
                            	,"Uzbekistan","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];

        	var getUrlParameter = function getUrlParameter(sParam) {
        	    var sPageURL = window.location.search.substring(1),
        	        sURLVariables = sPageURL.split('&'),
        	        sParameterName,
        	        i;

        	    for (i = 0; i < sURLVariables.length; i++) {
        	        sParameterName = sURLVariables[i].split('=');

        	        if (sParameterName[0] === sParam) {
        	            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        	        }
        	    }
        	};
        
			function waitingVotes() {
				$.ajax({
					url: '/get_new_votes',
					type: 'GET',
					data: {'pid': getUrlParameter('pid')},
					dataType: 'json',
					success: function(data, status) {
						if(data.timeout != true)
						{
    						$('.progress-bar').css('width', data.votes+'%');
    						$('.percentage').html(data.votes+'% to achieve goal');
    						$('.last-vote').html(data.last);
    						$('.last-country').html(data.country);
						}
						waitingVotes();
					}
					
				});
			}
			
			$(document).ready(function() {
				$.ajax({
					url: '/get_petition',
					type: 'GET',
					data: {'pid': getUrlParameter('pid')},
					dataType: 'json',
					success: function(data, status) {
						$('.image').html(`<img src='`+data.image+`' class='col-12' alt=''>`);
						$('.summary p').html(data.summary);
					}
				});

				
				$.ajax({
					url: '/get_votes',
					type: 'GET',
					data: {'pid': getUrlParameter('pid')},
					dataType: 'json',
					success: function(data, status) {
						if(data.timeout != true)
						{
    						$('.progress-bar').css('width', data.votes+'%');
    						$('.percentage').html(data.votes+'% to achieve goal');
    						$('.last-vote').html(data.last);
    						$('.last-country').html(data.country);
						}
					}
				});

				$.each(country_list, function(index, value) {
					$('.country').append('<option value='+value+'>'+value+'</option>');
				});

				waitingVotes();
			});
        
        	$('.vote').on('click', function() {
            	$.ajax({
                	url: '/vote',
                	type: 'POST',
                	data: {'pid': getUrlParameter('pid'), 'name': $('form #vote-name').val(), 'email': $('form #vote-email').val(), 'country': $('form #vote-country option:selected').val()},
					dataType: 'json',
                	success: function(data, status) {
                    	if(data.error == true) {
                        	$('.'+data.type).html(data.message);
							return;
                        }
                    	$('#voteModal').modal('toggle');
                    }
            	});
        	});
        </script>
	</body>
</html>
            
            