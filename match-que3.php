<?php 
	require('db.php');
	$sql = "SELECT * FROM tbl_questions WHERE question_id = 2";
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	
    $sql = "SELECT * FROM tbl_question_segments WHERE question_id = 2";
    $stmt = $dbh->query($sql);
    $segments = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach($segments as $key=>$segment)
	{
		$sql = "SELECT * FROM tbl_que_dragndrop_options WHERE question_id = 2 AND segment_id=".$segment['segment_id'];
		$stmt = $dbh->query($sql);
		$segments[$key]['correct_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	$sql = "SELECT * FROM tbl_que_dragndrop_options WHERE question_id = 2";
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.min.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/selectize.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
</head>
<body>

<div class="container">
  <div>
    <h2><strong>Drag and drop distinguishing</strong></h2>
    <p><?php echo $questions[0]['statement']; ?></p> 
  </div>
  <div class="row">
	<?php foreach($segments as $key=>$segment){ ?>
		<div class="group col-md-5"><?php echo $segment['segment_title'];?>
        <br>
		<ul class="droppable options" data-segment-id="<?php echo $segment['segment_id'];?>">
		</ul>
		</div>
	<?php }	?>
  </div>
  <br/>
  <br/>
  <div class="row" id="all_options">
  <?php foreach($options as $key=>$option){ ?>
  <?php if(($key%3==0)) { ?>
  <div class="col-md-3">
  <ul class="options droppable all">
  <?php } ?>
	  
	<li class="draggable option" data-segment="<?php echo $option['segment_id'];?>"><?php echo $option['option_text']; ?></li>

  <?php if(($key%3==2) || $key==(count($options)-1)) { ?>
  </ul>
  </div>
  <?php } ?>
  <?php } ?>
  </div>
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
			}
		});
	});
  
	function createAllOptions(){
		var options = <?php echo json_encode($options);?>;
		var data = '';
		options.forEach(function(option){
			if((option.option_id-1)%3==0){
				data = data+'<div class="col-md-3"><ul class="options droppable all">'
			}
			
			data = data+'<li class="draggable option" data-segment="'+option.segment_id+'">'+option.option_text+'</li>'
			if((option.option_id-1)%3==2 || (option.option_id)==options.length){
				data = data+' </ul></div>'
			}
		})
		
		$('#all_options').html(data);
		$('.draggable, .droppable').sortable({
			connectWith: '.options'	
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
				data = data+'<li class="draggable option" data-segment="'+option.segment_id+'">'+option.option_text+'</li>'
			})
			$( "ul[data-segment-id="+segment.segment_id+"]" ).html(data);
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
				options = $(this).find('li').toArray();
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
