<?php
	include('../php/simple_html_dom.php');
	$date='01/03';
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
        
        $user = "rmichon";
        $pass = "blablabla";
        $dbh = new PDO('mysql:host=localhost;dbname=rootme', $user, $pass);

        // la bdd contient une table `scores` qui contient les attributs suivants:
        // UNIQUE INT id, BOOLEAN is_anonymous, VARCHAR(255) nickname, VARCHAR(255) realname, INT initial_score, INT new_score, VARCHAR(32) challs, DATETIME last_fetch

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
	return intval($score);
}
function get_RootMe_challs($pseudo){
	$url="https://www.root-me.org/".$pseudo.'?inc=score';

	$html=file_get_html($url);
	$challs='not_found';
	foreach($html->find('ul') as $element){
		$line1=$element->find('li',0);
		$line2=$line1->find('span',0);
		if ($line2->class === 'color1 tl') {
			$challs=$line2->first_child()->plaintext;
		}
	}
	return $challs;
}

// maj
foreach($dbh->query('SELECT id, nickname from scores WHERE last_fetch < DATE_SUB(NOW(), 30 MINUTE)') as $row) {
    $score = get_RootMe_score($row['nickname']);
    $challs = get_RootMe_challs($row['nickname']);

    $req = "UPDATE scores SET new_score = :score, challs = :challs, last_fetch = :last_fetch WHERE id = :id";
    $sth = $dbh->prepare($req);
    $sth->execute(array(":score" => $score, ":challs" => $challs, ":last_fetch" => "NOW()", ":id" => $row['id']));
}

// affichage
foreach($dbh->query('SELECT * from scores') as $row) {
    echo "<tr>
        <td>" . ($row['is_anonymous'] ? "" : "<a href=\"https://www.root-me.org/" . $row['nickname'] . "\">" . $row['realname'] . "</a>") . "</td>
        <td> " . $row['new_score'] . "</td>
        <td> " . $row['challs'] . "</td>
        <td>" . ($row['new_score'] - ['initial_score']) . "</td>
        </tr>";         
        }

?>
	</table>
	<br><br>
	<footer class="page-footer font-small text-center">
		<p class="text-center font-small">Romain du Marais - <a href="https://github.com/hackheera/root-me101">github</a></p>
	</footer>
</body>
</html>
