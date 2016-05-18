<?php 
	$question_id = 99;
	require('db.php');
	$sql = "SELECT * FROM tbl_questions WHERE question_id = ".$question_id;
    $stmt = $dbh->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
    $sql = "SELECT * FROM tbl_question_images WHERE question_id = ".$question_id." ORDER BY RAND()";
    $stmt = $dbh->query($sql);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$sql = "SELECT * FROM tbl_que_labelling_options WHERE question_id = ".$question_id." ORDER BY RAND()";
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
    <h2><strong>Drag and drop labelling</strong></h2>
    <p><?php echo $questions[0]['statement']; ?></p> 
  </div>
  <div class="row">
	<?php foreach($images as $key=>$image){ ?>
		<div class="group col-md-3 col-xs-3 que-image">
		<img class="img-responsive" data-correct-option="<?php echo $image['correct_option'];?>" src="http://10.240.8.24/ver-dev/epub_test/mod_elearning/drop_down_labelling_images/<?php echo $image['image_name'];?>"></img></br>
		<select style="width:100%">
		<option value="0">Select</option>
		<?php foreach($options as $key=>$option){ ?>
		<option value="<?php echo $option['option_id'];?>"><?php echo $option['option_text']; ?></option>
		<?php } ?>
		</select>
		</div>
	<?php }	?>
  </div>
  <br/>
  <div class="row">
	<div class="btn-pannel col-md-6">
		<button id="show_answer" class="btn btn-primary">Show Answers</button>
		<button id="check" class="btn btn-primary" >Check</button>
		<button id="reset" class="btn btn-primary">Reset</button>
	</div>
  </div>
</div>
<script>
  
	function createAllOptions(){
		var options = new Array();
		options	= <?php echo json_encode($options);?>;
		console.log(options);
		var images = $('.que-image');
		images.each(function(image){
			var que_image = $(this).find('img');
			var option = $(this).find('option');
			var select = $(this).find('select');
			option.each(function(index){
				$(this).remove();
			})
			select.append('<option value="0">Select</option>');
 			for(var i=0;i<parseInt(option.length)-1;i++)
			{
				console.log(parseInt(i))
				opt_id = parseInt(options[parseInt(i)].option_id);
				str = "<option value='"+opt_id+"'>"+options[i].option_text+"</option>";
				select.append(str);
			}  
		
		})
	}
	
	function showCorrectMatch(){
		var images = $('.que-image');
		images.each(function(image){
			var que_image = $(this).find('img');
			var user_option = $(this).find('select');
			user_option.val(que_image.attr("data-correct-option"));
		})
	}
	
	$(document).ready(function(){
		$('.que-image').change(function(){
			$('#check').prop('disabled',false);
		});
		
		$("#check").click(function(){
			$("#check").html('Checking....');
			var images = $('.que-image');
			images.each(function(image){
				var que_image = $(this).find('img');
				var user_option = $(this).find('select');
				$(this).removeClass("option-green");
				$(this).removeClass("option-red");

				if(que_image.attr("data-correct-option")==user_option.val())
				{
					$(this).addClass("option-green");
				}
				else
				{
					$(this).addClass("option-red");
				}
			})
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
