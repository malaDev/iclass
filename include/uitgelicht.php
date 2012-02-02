
<div id="titletrans">Uitgelicht: <?php
//A featured Student (or Docent) is chosen from the users table.
if (isset($focus_user_firstn)) {
	echo $focus_user_firstn . ' ' . $focus_user_lastn;
}
?></div>
<?php
//The shown text depends on the value of the variable progress.
					$user_uitgelicht_query = mysql_query("SELECT uvanetid, type FROM users WHERE id = $focus_user_id");
$user_uitgelicht_result = mysql_fetch_row($user_uitgelicht_query);
$uvanetid_uitgelicht = $user_uitgelicht_result['0'];
$type_uitgelicht = $user_uitgelicht_result['1'];
$percentage_uitgelicht = percentage($uvanetid_uitgelicht);

//user's avatar shown
echo '<img src="' . lookforavatar($uvanetid_uitgelicht, "") . '"width="100" height="100" alt="avatar" /> ';
//the chosen user is either a "Docent" or a "Student".
if ($type_uitgelicht == "Docent") {
	echo " De weledele " . $focus_user_lastn . " heeft " . $percentage_uitgelicht . "% van deze cursus voltooid. ";
	if ($percentage_uitgelicht < 20) {
		echo $focus_user_firstn . "kent natuurlijk de hele cursus al, en hoeft het niet nog eens door te nemen.";
	} else if ($percentage_uitgelicht >= 20 && $percentage_uitgelicht <= 59) {
		echo "Af en toe een lecture kijken is natuurlijk handig als docent, en dat is " . $focus_user_firstn . " niet vergeten.";
	} else if ($percentage_uitgelicht >= 60 && $percentage_uitgelicht <= 99) {
		echo "Verzin een leuke tekst";
	} else {
		echo "Geweldig, zo hoort een docent zijn materiaal te kennen!";
	}
} else {

	echo "De leergierige " . $focus_user_firstn . " heeft  " . $percentage_uitgelicht . "% van deze cursus voltooid. ";
	if ($percentage_uitgelicht < 20) {
		echo $focus_user_firstn . " is net begonnen aan deze geweldige cursus, en heeft er veel zin in. 
									Vergeet niet om bekeken items af te vinken, veel plezier!";
	} else if ($percentage_uitgelicht >= 20 && $percentage_uitgelicht <= 39) {
		echo "Een goed begin is al het halve werk, maar een goed begin is niet de helft. Ga dus door!";
	} else if ($percentage_uitgelicht >= 40 && $percentage_uitgelicht <= 79) {
		echo "Veel studenten stoppen op dit punt, alleen de elite weet zijn wilskracht te verzamelen en door te stoten naar de top!";
	} else if ($percentage_uitgelicht >= 80 && $percentage_uitgelicht <= 99) {
		echo "De UvA is hier blij mee, geweldig, zo dichtbij de eindstreep dus stop vooral niet en haal je studiepunten! Dan krijgt de UvA meer geld!";
	} else {
		echo "Chapeau! Tijd voor hoge cijfers en daarna veel bier. Dat heb je wel verdient!";
	}
}
?>