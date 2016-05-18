<?php 
	require('db.php');
	$question_id = 5;
	$sql = "SELECT * FROM tbl_questions WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$question_statement = str_replace('[____]','<input type="text" value="" class="fill-blank" name="fill_blank" id="fill_blank"/>',$questions[0]['statement']);
	
    $sql = "SELECT * FROM tbl_que_fill_options WHERE question_id = ".$question_id." ORDER BY RAND()";
    $stmt = $dbh->query($sql);
    $fill_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$sql = "SELECT correct_option, tbl_que_fill_options.option_text FROM tbl_questions INNER JOIN tbl_que_fill_options ON tbl_questions.correct_option=tbl_que_fill_options.option_id WHERE tbl_questions.question_id = ".$question_id;
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
  <link rel="stylesheet" href="css/style.css"></link>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
</head>
<body>

<div class="container">
  <div class="col-md-12">
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
	
		function createAllOptions(){
			$('#fill_blank').val('');
			$('#result').html('');
		}
		
		function showCorrect(){
			var correct_option = '<?php echo $correct_option[0]['option_text']; ?>';
			
			$('#fill_blank').val(correct_option);
			$('#result').html('');
		}
		
		$("#check").click(function(){
			$("#check").html('Checking....');
			
			var user_answer = $("#fill_blank").val().toLowerCase();  
			var correct_option = '<?php echo $correct_option[0]['option_text']; ?>';
			if(user_answer==correct_option.toLowerCase()){
				$('#result').html('<button type="button" class="btn btn-success">Right</button>');
			}else
			{
				$('#result').html('<button type="button" class="btn btn-danger">Wrong</button>');
			}
			$("#check").html('Check');
		});
		$("#reset").click(function(){
			createAllOptions();
		});
		
		$("#show_answer").click(function(){
			showCorrect();
		});
	});
 
  </script>
</body>
</html>
