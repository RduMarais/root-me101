# root-me101
score board for Root-Me

## RÉGLES
 * le GAME commence le 04/03 à minuit, se termine le 1er mai 00h00.
 * dernier (en nombre de points) paye un domac/kebab au premier (en nombre de points) (ou toute autre malbouffe).
 * avant-dernier (en nombre de points) paye un verre au 2e (en nombre de points) (ou toute autre boisson).
 * dernier (en nombre de challenges) paye un domac/kebab au premier (en nombre de challenges) (ou toute autre malbouffe).
 * avant-dernier (en nombre de challenges) paye un verre au 2e (en nombre de challenges) (ou toute autre boisson).
 * les doublons sont excusés, ie. personne ne paye deux fois un verre/domac ou se fait payer deux verres/domac.

## how-to
 * create a "participants.csv" file 
 * put its path in the function *load_profiles* (the path is absolute here because I use it in a shared envronnement).
 * the "participants.csv" file consists of one row for each challenger, each made of 4 columns : profile name, real name, alias displayed, points at the start of the challenge
 * dl the "simple_html_dom.php" code for dealing with html requests in PHP. I placed it in a php directory outside the server root.
 * change the global variable *date* for starting the challenge

