<?php
	
	$date='04/03';
	
?>
<html>
<head>
	<title>Root-Me101 : Tableau des Challenges</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<script src="./js/bootstrap.min.js"></script>
</head>
<body>
	<h1 class="text-center">Tableau des challenges</h1>
	<br><br>
	<table class="table table-striped table-hover">
		<tr>
			<th>Pseudo</th><th>nom IRL</th><th>App-Système</th><th>App-Script</th><th>Cracking</th><th>Cryptanalyse</th><th>Forensic</th><th>Programmation</th><th>Réaliste</th><th>Réseau</th><th>Stegano</th><th>Client</th><th>Serveur</th>
		</tr>
<?php
include('../php/simple_html_dom.php');

function get_RootMe_challs($pseudo){
	$url="https://www.root-me.org/".$pseudo.'?inc=score';

	$html=file_get_html($url);
	$challs=array();
	foreach($html->find('div',33) as $element){
		if(is_null($element->class) === FALSE && $element->class="t-body tb-padding"){
			#echo('here : '.$element->first_child());
			foreach($element->find('div') as $categorie){
				#if(is_null($categorie->class) === FALSE && $categorie->class="medium-6"){
				#	echo('categ : '.$categorie->first_child()->first_child());
				#}
				#echo(' categ:'.$categorie);
				#ici on a tout les div de niveau column
				#donc on va éviter le premier, et dans chacun récupérer juste le nombre de points
				$sscateg=$categorie->find('div');
				if(is_null($sscateg) === FALSE){
					echo(' sscateg:'.$sscateg);
					#print_r($sscateg);
					#$points = $sscateg->find('span');
					#if(is_null('span') === FALSE){
					#	echo(' pts : '.$points);
					#}
				}
			}
		}
	}
	return $challs;
	echo($challs);
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
foreach(load_profiles() as $profile){
	$score = get_RootMe_score($profile[0]);
	echo('<tr><td>');
	if($profile[2] !== 'anonyme'){
		echo('<a href="https://www.root-me.org/'.$profile[0].'">'.$profile[2].'</a>');
	}
	echo('</td><td>'.$profile[1]);
	get_RootMe_challs($profile[0]);
	#foreach(get_RootMe_challs($profile[0]) as $chall){
		#echo('</td><td>'.$chall);
		#echo('</td><td>yo');
	echo('</td></tr>');
}

?>
	</table>
	<br><br>
	<footer class="page-footer font-small text-center">
		<p class="text-center font-small">Romain du Marais - <a href="https://github.com/hackheera/root-me101">github</a></p>
	</footer>
</body>
</html>
