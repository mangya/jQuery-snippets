<?php 
	require('db.php');
	$question_id = 3;
	$sql = "SELECT * FROM tbl_questions WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
    $sql = "SELECT * FROM tbl_que_fill_options WHERE question_id = ".$question_id." ORDER BY RAND()";
    $stmt = $dbh->query($sql);
    $fill_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$fill_select = '<select id="fill_blank">';
	foreach($fill_options as $key=>$option) {
		 $fill_select .= '<option value='.$option['option_id'].'>'.$option['option_text'].'</option>';
	} 
	$fill_select .= "</select>";
	
	
	$question_statement = str_replace('[____]',$fill_select,$questions[0]['statement']);
	
	
	$sql = "SELECT correct_option FROM tbl_questions WHERE question_id = ".$question_id;
	$stmt = $dbh->query($sql);
	$correct_option = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fill in the blank Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="css/style.css"></link>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
  <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
</head>
<body>

<div class="container">
  <div>
    <h2><strong>Fill in the blank Sample</strong></h2>
    <p><?php echo $question_statement; ?></p> 
  </div>
  <div class="row">
		<div class="col-md-3" id="result"></div>
		
  </div>
  <br/>
  <div class="row">
	<div class="btn-pannel col-md-6">
		<button id="show_answer" class="btn btn-primary">Show Answers</button>
		<button id="check" class="btn btn-primary">Check</button>
		<button id="reset" class="btn btn-primary">Reset</button>
	</div>
  </div>
</div>
<script>
	$(document).ready(function(){
		$('#fill_blank').selectize();
		
		function createAllOptions(){
			var options = <?php echo json_encode($fill_options);?>;
			var data = '';
			options.forEach(function(option){
				data = data+'<option value="'+option.option_id+'">'+option.option_text+'</option>';
			})
			
			$('#fill_blank').html(data);
			$('#result').html('');
		}
		
		function showCorrectOrder(){
			var options = <?php echo json_encode($fill_options);?>;
			var correct_option = <?php echo $correct_option[0]['correct_option']; ?>;
			
			var data = '';
			options.forEach(function(option){
				if(option.option_id==correct_option){
					data = data+'<option value="'+option.option_id+'" selected>'+option.option_text+'</option>';
				}else{
					data = data+'<option value="'+option.option_id+'">'+option.option_text+'</option>';
				}
				
			})
			
			$('#fill_blank').html(data);
			$('#result').html('');
		}
		
		$("#check").click(function(){
			$("#check").html('Checking....');
			
			var user_answer = $("#fill_blank").val();  
			
			var action_url	= "check_answer.php";

			$.ajax({
					type:"POST",
					url:action_url,
					data:{'q_type':'4','q_id':<?php echo $question_id;?>,'user_answer':user_answer},
					async:true,
					dataType: 'json',
					success: function(result){
						if(result==1){
							$('#result').html('<button type="button" class="btn btn-success">Right</button>');
						}else
						{
							$('#result').html('<button type="button" class="btn btn-danger">Wrong</button>');
						}
					}
			});
			$("#check").html('Check');
		});
		$("#reset").click(function(){
			createAllOptions();
		});
		
		$("#show_answer").click(function(){
			showCorrectOrder();
		});
	});
 
  </script>
</body>
</html>
