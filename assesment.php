<?php 
	require('db.php');
	$no_of_questions = 3;
	$session_id      = 1;
	
	$sql = "SELECT * FROM tbl_assessment_questions WHERE session_id = ".$session_id." ORDER BY RAND() LIMIT ".$no_of_questions;
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$arr_questions = array();
	
	foreach($questions as $key => $question)
	{
		$question_id = $question['question_id'];
		$sql = "SELECT * FROM tbl_assessment_qn_options WHERE question_id = ".$question_id." ORDER BY RAND()";
		$stmt = $dbh->query($sql);
		$options = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$arr_questions[$key]['question_id'] = $question['question_id'];
		$arr_questions[$key]['statement'] = $question['statement'];
		$arr_questions[$key]['correct_option'] = $question['correct_option'];
		$arr_questions[$key]['options'] = $options;
		$arr_questions[$key]['user_ans'] = '';
		
	}	
	
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fill in the blank Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="css/style.css"></link>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
</head>
<body>

<div class="container">
  <div>
    <h2><strong>Assesment Sample</strong></h2>
	<div id="question">
	
	</div>
  </div>
  <div class="row">
		<div class="col-md-3" id="result"></div>
		
  </div>
  <br/>
  <div class="row">
	<div class="btn-pannel col-md-6">
		<button id="next" class="btn btn-primary">Next</button>
		<button id="submit" class="btn btn-primary" disabled>Submit</button>
		<button id="restart" class="btn btn-primary">Restart</button>
	</div>
  </div>
</div>
<script>
	function renderQuestion(index)
	{
		var arr_questions = JSON.parse('<?php echo json_encode($arr_questions);?>');
		data = "<p>"+arr_questions[index].statement+"</p>";
		data += '<br/>';
		$(arr_questions[index].options).each(function(key, value){
			data += "<input type='radio' name='options' value="+value.option_id+"> "+value.option_text+"</input><br/>";
		})
		$('#question').html(data);
		
	}
	$(document).ready(function(){
		var cur_question = 0;
		var arr_questions = JSON.parse('<?php echo json_encode($arr_questions);?>');
		var total_questions = arr_questions.length;
		var score = 0;
		renderQuestion(cur_question);
		
		$("#next").click(function(){
			user_ans = $('input[name=options]:checked').val();
			arr_questions[cur_question].user_ans = user_ans;
			if(user_ans == arr_questions[cur_question].correct_option)
				score++;
				
			if((cur_question+1) <= total_questions-1){
				cur_question++;
				renderQuestion(cur_question);
			}else{
				$("#next").prop('disabled',true);
				$("#submit").prop('disabled',false);
			}

		});
		
		$("#restart").click(function(){
				cur_question = 0;
				score = 0;
				renderQuestion(cur_question);
				$("#next").prop('disabled',false);
				$("#submit").prop('disabled',true);
		});
		
		$("#submit").click(function(){
			alert(score);
			console.log(arr_questions);
		});
	});
 
  </script>
</body>
</html>
