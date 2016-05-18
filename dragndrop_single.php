<?php 
	require('db.php');
	$question_id = 102;
	
	$sql = "SELECT * FROM tbl_questions WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
/* 		echo "<pre>";
		print_r($questions);
		echo "</pre>";
		exit; */
		
    $sql = "SELECT * FROM tbl_question_segments WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
    $segments = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach($segments as $key=>$segment)
	{
		$sql = "SELECT * FROM tbl_que_dragndrop_options WHERE question_id = ".$question_id." AND segment_id=".$segment['segment_id'];
		$stmt = $dbh->query($sql);
		$segments[$key]['correct_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	$sql = "SELECT * FROM tbl_que_dragndrop_options WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
	$options = $stmt->fetchAll(PDO::FETCH_ASSOC);
	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
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
    <h2><strong>Drag and drop Single option</strong></h2>
    <p><?php echo $questions[0]['statement']; ?></p> 
  </div>
  	<?php foreach($segments as $key=>$segment){ ?>
	  <div class="row">
			<div class="group col-md-3"><?php echo $segment['segment_title'];?>
			</div>
			<div class="group col-md-7 droppable options" data-segment-id="<?php echo $segment['segment_id'];?>"></div>
	  </div>
  	<?php }	?>
  <br/>
  <br/>
  <div class="row" id="all_options">
	<div class="col-md-3"><?php foreach($options as $key=>$option){ ?>
	  <div class="options droppable all" style="position: absolute; top: 5px;right: 5px; display:block">
		<div class="draggable option" data-segment="<?php echo $option['segment_id'];?>"><?php echo $option['option_text']; ?></div>
	  </div><?php } ?>
	  &nbsp;
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

 		$('.draggable, .droppable').sortable({
			connectWith: '.options'	
			});
		
		
		$('.droppable').droppable({
			drop: function(event, ui){
				$('#check').prop('disabled',false);
				$(this).sortable('disable');
			}
		});
	});
  
	function createAllOptions(){
		var options = <?php echo json_encode($options);?>;
		var data = '';
		data = data+'<div class="col-md-3">';
			
		options.forEach(function(option){
			data = data+'<div class="options droppable all" style="position: absolute; top: 5px;right: 5px; display:block"><div class="draggable option" data-segment="'+option.segment_id+'">'+option.option_text+'</div> </div>';
		})
		data = data+'</div> ';
		$('#all_options').html(data);
		$('.draggable, .droppable').sortable({
			connectWith: '.options'	
		});
		$(".droppable").each(function() {
				$(this).sortable('enable');
		});
		
	}
	
	function showCorrectMatch(){
		var segments = <?php echo json_encode($segments);?>;
		var data='';
		$(".draggable").each(function() {
				$(this).remove();
		});
		segments.forEach(function(segment){
			segment.correct_options.forEach(function(option){
				data = data+'<div class="draggable option" data-segment="'+option.segment_id+'">'+option.option_text+'</div>'
			})
			$( "div[data-segment-id="+segment.segment_id+"]" ).html(data);
			data = '';
		})
		$('.draggable, .droppable').sortable({
			connectWith: '.options'	
		});
	}
	
	$(document).ready(function(){
		$("#check").click(function(){
			$("#check").html('Checking....');
			var score;
			score = 0;

			$(".droppable").each(function() {
				options = $(this).find('div').toArray();
				for(var i=0; i<options.length;i++){
					$(options[i]).removeClass("option-green");
					$(options[i]).removeClass("option-red");
					if($(options[i]).attr("data-segment")==$(this).attr("data-segment-id"))
					{
						score = score+1;
						$(options[i]).addClass("option-green");
					}
					else
					{
						$(options[i]).addClass("option-red");
					}
				}
			});
			$("#check").html('Check');
		});
		$("#reset").click(function(){
			$(".draggable").each(function() {
				$(this).remove();
			});
			$(".droppable").each(function() {
				$(this).html('&nbsp;');
			});
			createAllOptions();
			$('#check').prop('disabled',true);
		});
		
		$("#show_answer").click(function(){
			showCorrectMatch();
		});
	});
  </script>
</body>
</html>
