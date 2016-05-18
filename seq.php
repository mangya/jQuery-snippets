<?php 
	require('db.php');
	$question_id = 2;
	$sql = "SELECT * FROM tbl_questions WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	
    $sql = "SELECT * FROM tbl_que_seq_options WHERE question_id = ".$question_id." ORDER BY RAND()";
    $stmt = $dbh->query($sql);
    $seq_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$sql = "SELECT * FROM tbl_que_seq_options WHERE question_id = ".$question_id." ORDER BY option_order";
    $stmt = $dbh->query($sql);
    $correct_seq_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sequencing Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <link type="text/css" rel="stylesheet" href="css/style.css"></link>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
</head>
<body>

<div class="container">
  <div>
    <h2><strong>Sequencing Sample</strong></h2>
    <p><?php echo $questions[0]['statement']; ?></p> 
  </div>
  <div class="row">
		<div class="group col-md-6">
        <br>
		<ul id="sortable" class="options">
		<?php foreach($seq_options as $key=>$option) { ?>
		  <li class="option" data-order="<?php echo $option['option_id'];?>"><?php echo $option['option_text'];?></li>
		<?php } ?>
		</ul>
		</div>
  </div>
  <br/>
  <div class="row">
	<div class="btn-pannel col-md-6">
		<button id="show_answer" class="btn btn-primary">Show Answers</button>
		<button id="check" class="btn btn-primary" disabled>Check</button>
		<button id="reset" class="btn btn-primary">Reset</button>
	</div>
  </div>
</div>
<script>
	$(function() {
	$( "#sortable" ).sortable({
		sort: function(event, ui){
			$('#check').prop('disabled',false);
		}
	});
	$( "#sortable" ).disableSelection();
	});
	
	
	$(document).ready(function(){
	
		function createAllOptions(){
			var options = <?php echo json_encode($seq_options);?>;
			var data = '';
			options.forEach(function(option){
				data = data+'<li class="option" data-order='+option.option_id+'>'+option.option_text+'</li>'
			})
			
			$('#sortable').html(data);
		}
		
		function showCorrectOrder(){
			var options = <?php echo json_encode($correct_seq_options);?>;
			var data = '';
			options.forEach(function(option){
				data = data+'<li class="option" data-order='+option.option_id+'>'+option.option_text+'</li>'
			})
			
			$('#sortable').html(data);
		}
		
		$("#check").click(function(){
			$("#check").html('Checking....');
			
			var sorted = $("#sortable").sortable('toArray', {attribute: "data-order"});  
			var action_url	= "check_answer.php";

			$.ajax({
					type:"POST",
					url:action_url,
					data:{'q_type':'3','q_id':<?php echo $question_id;?>,'user_answer':sorted},
					async:true,
					dataType: 'json',
					success: function(result){
						options = $(".option").toArray();
						for(var i=0;i<result.length;i++){
							if(result[i]==1){
								$(options[i]).addClass('option-green');
							}
							else{
								$(options[i]).addClass('option-red');
							}
						}
					}
			});
			$("#check").html('Check');
		});
		$("#reset").click(function(){
			$(".option").each(function() {
				$(this).remove();
			});
			createAllOptions();
			$('#check').prop('disabled',true);
		});
		
		$("#show_answer").click(function(){
			showCorrectOrder();
		});
	});
 
  </script>
</body>
</html>
