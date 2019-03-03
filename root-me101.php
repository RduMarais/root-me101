<?php
	
	$date='04/03';
	
?>
<html>
<head>
	<title>Root-Me101 : Tableau des Scores</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<script src="./js/bootstrap.min.js"></script>
</head>
<body>
	<h1 class="text-center">Tableau des scores</h1>
	<br><br>
	<table class="table table-striped table-hover">
		<tr>
			<th>Pseudo</th><th>nom IRL</th><th>SCORE</th><th>challenges complétés</th><th>points depuis le <?php echo($date); ?></th>
		</tr>
<?php
include('../php/simple_html_dom.php');

function get_RootMe_score($pseudo){
	$url="https://www.root-me.org/".$pseudo;

	$html=file_get_html($url);
	$score='not_found';
	foreach($html->find('ul') as $element){
		if ($element->class === 'spip') {
			$line=$element->find('li',2);
			$score=$line->first_child()->plaintext;
			#echo('score = '.$score.'<br>');
		}
	}
	return $score;
}
function get_RootMe_challs($pseudo){
	$url="https://www.root-me.org/".$pseudo.'?inc=score';

	$html=file_get_html($url);
	$challs='not_found';
	foreach($html->find('ul') as $element){
		$line1=$element->find('li',0);
		$line2=$line1->find('span',0);
		if (is_null($line2) === FALSE){
		       if($line2->class === 'color1 tl') {
			       $challs=$line2->first_child()->plaintext;
		       }
		}
	}
	return $challs;
}

function load_profiles(){
	$row = 1;
	$profiles=array();
	if (($handle = fopen("/home/rmichon/participants.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			array_push($profiles,$data);
			$row++;
		}
		fclose($handle);
	}

	return $profiles;
}
foreach(load_profiles() as $profile){
	$score = get_RootMe_score($profile[0]);
	echo('<tr><td>');
	if($profile[2] !== 'anonyme'){
		echo('<a href="https://www.root-me.org/'.$profile[0].'">'.$profile[2].'</a>');
	}
	echo('</td><td>'.$profile[1].'</td><td>'.$score.'</td><td>'.get_RootMe_challs($profile[0]).'</td><td>'.((int) $score - (int) $profile[3]).'</td></tr>');
}

?>
	</table>
	<br><br>
	<footer class="page-footer font-small text-center">
		<p class="text-center font-small">Romain du Marais - <a href="https://github.com/hackheera/root-me101">github</a></p>
	</footer>
</body>
</html>
