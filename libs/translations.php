<?php
if (!defined ("FromIndex") && FromIndex !== true) { header ("HTTP/1.0 404 Not Found"); header ("Location: ../../index.php"); die (); }

if (!defined ("Language") && Language == "it-IT") {
// Following is the Italian translation
$database_noresult = 'Non c\'era risultati restituiti dalla query. Modificare i parametri, e riprovare.';
$error_validate = 'C\'è un problema con la sottomissione. Torna indietro e riprova'; // ' have to be escaped. (\')
}

if (!defined ("Language") && Language == "en-GB") {
// Following is the English (en-GB) translation
$error_noresult = 'There was no results returned by the query. Change the parameters, and try again.';
$error_validate = 'There is a problem with your submition. Go back, and try again.';

}



?>