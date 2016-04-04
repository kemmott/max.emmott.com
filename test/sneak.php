<?php

 /***********************************************************************
 * sneak.php - v 1.27 - 2003/02/12
 *
 * SNEAK: Snarkles.Net Encryption Assortment Kit
 * =============================================
 * Send and decode messages using a number of different methods
 *
 * Please forward any suggestions, problems, improvements, etc. to the 
 * e-mail address below. Thanks! :D
 *
 * Copyright (c) 2000 - 2004 snarkles (webgeek@snarkles.net)
 * Distributed under the GNU/GPL license (see http://www.gnu.org/copyleft/)
 ************************************************************************/
 /************************************************************************
 * Changelog
 * =========
 * v 1.27 - 2004/10/28:
 * - Added zero padding to hex conversions < 10. Thanks to bubuche93 for
 *   the heads up.
 *
 * v 1.26 - 2003/06/20:
 * - Added function strip_spaces to remove spaces prior to trying to
 *   base64 decode a string. Thanks for Jeian and Goldfish for pointing
 *   this out.
 *   
 * v 1.25 - 2003/02/12:
 * - Fixed a bug in form that initially displayed an error about an 
 *   undefined variable in the textbox if error_reporting is set to max. 
 *   Thanks to Justin Hagstrom for notifying me of the problem. :)
 *
 * v 1.24 - 2003/02/01:
 * - D'oh! Fixed a bug I introduced with the Caesar Bruteforce option that
 *   stuck that XSS vulnerability right back in there! :P
 *
 * v 1.23 - 2003/01/27:
 * - Added "Caesar Bruteforce" option which will attempt all 26 
 *   possible shifts of a Caesar (rotation) cipher.
 *
 * v 1.22 - 2003/01/26:
 * - Textbox now retains original text value, so you can try different 
 *   encryption methods on the same text without copying/pasting
 *   Thanks to barnseyboy who suggested the feature. :)
 *
 * v 1.21 - 2003/01/14:
 * - Fixed XSS vulnerability that could potentially allow people to steal
 *   cookies from sites with the script installed.
 *   Credit for spotting this vulnerability goes to JeiAr from
 *   CyberArmy Security Research (http://www.security-research.org/)
 *
 * v 1.20 - 2002/08/10:
 * - Added HTML entity encode/decode option
 * - Changed order of listings so encoding is always before decoding
 * - Fixed problem that caused special characters to be lost when 
 *   writing back to the screen
 *
 * v 1.11 - 2002/02/21:
 * - Cleaned up code some--now all chunk_splitting and str_replacing is 
 *   done within the functions, rather than during the switch statement
 * - Specified CRYPT_STD_DES in crypt() function, to fix problem with PHP 4.1.2
 *
 * v 1.10 - 2002/02/17:
 * - Added bin2hex, hex2bin, and pig latin functions
 *
 * v 1.00 - 2002/02/15:
 * - Nothing yet, but I'm sure that'll CHANGE. Ha! Get it? ;)
 *************************************************************************/
  $version = "1.27";

  // You can alter the HTML below to make this script fit more inline with 
  // the rest of your site.

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
 <head>
  <title>SNEAK: Snarkles.Net Encryption Assortment Kit</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <style type="text/css">
  <!--
    body { font-family: "arial", "helvetica", sans-serif; font-size: 10pt; }
  -->
  </style>
 </head>
 <body>

<?php

  // Declare some functions for encryption not included in PHP

    function asc2bin($str) {
      $text_array = explode("\r\n", chunk_split($str, 1));
      for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= substr("0000".base_convert(ord($text_array[$n]), 10, 2), -8);
      }
      $newstring = chunk_split($newstring, 8, " ");
      return $newstring;
    }

    function bin2asc($str) {
      $str = str_replace(" ", "", $str);
      $text_array = explode("\r\n", chunk_split($str, 8));
      for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= chr(base_convert($text_array[$n], 2, 10));
      }
      return $newstring;
    }

    // Made this alias because "bin2hex" would be confusing in the context of this script :P
    function asc2hex($str) {
      return chunk_split(bin2hex($str), 2, " ");
    }

    function hex2asc($str) {
      $str = str_replace(" ", "", $str);
      for ($n=0; $n<strlen($str); $n+=2) {
        $newstring .=  pack("C", hexdec(substr($str, $n, 2)));
      }
      return $newstring;
    }

    function binary2hex($str) {
      $str = str_replace(" ", "", $str);
      $text_array = explode("\r\n", chunk_split($str, 8));
      for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= str_pad(base_convert($text_array[$n], 2, 16), 2, "0", STR_PAD_LEFT);
      }
      $newstring = chunk_split($newstring, 2, " ");
      return $newstring;
    }

    function hex2binary($str) {
      $str = str_replace(" ", "", $str);
      $text_array = explode("\r\n", chunk_split($str, 2));
      for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= substr("0000".base_convert($text_array[$n], 16, 2), -8);
      }
      $newstring = chunk_split($newstring, 8, " ");
      return $newstring;
    }

    function caesarbf($str) {
	  $alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	  echo "<table width=\"85%\" cellpadding=\"2\" align=\"center\">\n";
	  for ($n = 1; $n < 26; $n++) {
	    $cipher = substr($alpha, $n, 26 - $n) . substr($alpha, 0, $n) . substr($alpha, 26+$n, 52-$n) . substr($alpha, 26, $n);
		if ($n % 2 == 0) {
		  echo '<tr bgcolor="#eeeeee">';
		} else {
		  echo '<tr bgcolor="#cccccc">';
		}
		echo "<td>ROT-$n: ". strtr($str, $alpha, $cipher) ."</td>";
	  }
	  echo "<tr>\n";
	  echo "</table>\n";
	}

    function entityenc($str) {
      $text_array = explode("\r\n", chunk_split($str, 1));
      for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= "&#" . ord($text_array[$n]) . ";";
      }
      return $newstring;
    }

    function entitydec($str) {
      $str = str_replace(';', '; ', $str);
      $text_array = explode(' ', $str);
      for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= chr(substr($text_array[$n], 2, 3));
      }
      return $newstring;
    }

    function l33t($str) {
      $from = 'ieastoIEASTO';
      $to = '134570134570';
      $newstring = strtr($str, $from, $to);
      return $newstring;
    }

    function del33t($str) {
      $from = '134570';
      $to = 'ieasto';
      $newstring = strtr($str, $from, $to);
      return $newstring;
    }

    function igpay($str) {
      $text_array = explode(" ", $str);
      for ($n = 0; $n < count($text_array); $n++) {
        $newstring .= substr($text_array[$n], 1) . substr($text_array[$n], 0, 1) . "ay ";
      }
      return $newstring;
    }

    function unigpay($str) {
      $text_array = explode(" ", $str);
      for ($n = 0; $n < count($text_array); $n++) {
        $newstring .= substr($text_array[$n], -3, 1) . substr($text_array[$n], 0, strlen($text_array[$n]) - 3) . " ";
      }
      return $newstring;
    }

    function rot13($str) {
      $from = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $to   = 'nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM';
      $newstring = strtr($str, $from, $to);
      return $newstring;
    }

    function strip_spaces($str) {
      $str = str_replace(" ", "", $str);
      return $str;
    }

  // Check to see if form has been submitted yet
  if(isset($_POST['submit'])) {

    // Yes, so make sure they filled something in
    $text = $_POST['text'];

    if($text == '') {
      die("<p>Fill in the form, dinglefritz! ;)</p>\n");
    }

    // Looks good, so clean up data
    $text = urldecode(stripslashes($text));

    // Make copy of original text for later display
    $orig_text = $text;
    $orig_text = htmlentities($orig_text);
    echo("<p>$orig_text converts to:</p>\n");

    // De/Encrypt based on selection in form
    switch ($_POST['cryptmethod']) {
     case "asc2bin":
       $text = asc2bin($text);
       break;
     case "asc2hex":
       $text = asc2hex($text);
       break;
     case "bin2asc":
       $text = bin2asc($text);
       break;
     case "hex2asc":
       $text = hex2asc($text);
       break;
     case "bin2hex":
       $text = binary2hex($text);
       break;
     case "hex2bin":
       $text = hex2binary($text);
       break;
     case "backwards":
       $text = strrev($text);
       break;
     case 'b64enc':
       $text = base64_encode($text);
       break;
     case 'b64dec':
       $text = base64_decode(strip_spaces($text));
       break;
	 case 'caesarbf':
	   $text = caesarbf($text);
	   break;
     case 'crypt':
       $text = crypt($text, 'CRYPT_STD_DES');
       break;
     case 'entityenc':
       $text = entityenc($text);
       break;
     case 'entitydec':
       $text = entitydec($text);
       break;
     case "l33t":
       $text = l33t($text);
       break;
     case "del33t":
       $text = del33t($text);
       break;
     case 'md5':
       $text = md5($text);
       break;
     case 'igpay':
       $text = igpay($text);
       break;
     case 'unigpay':
       $text = unigpay($text);
       break;
     case "rot-13":
       $text = rot13($text);
       break;
     case 'urlenc':
       $text = urlencode($text);
       break;
     case 'urldec':
       $text = urldecode($text);
       break;
     default:
       die("<p>That encryption type is not supported.</p>\n");
    } // end switch

  // Convert to HTML entities so special chars show up
  $text = htmlentities($text);

  // Display result to the screen
  echo("<p>$text</p>\n");

  } // end if

?>

 <!-- begin form -->
 <center>
  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post">
   <textarea name="text" rows="5" cols="50"><?php if (isset($orig_text)) { echo($orig_text); } ?></textarea><br />
   <select name="cryptmethod">
    <option value="asc2bin">ASCII to Binary</option>
    <option value="bin2asc">Binary to ASCII</option>
    <option value="asc2hex">ASCII to Hex</option>
    <option value="hex2asc">Hex to ASCII</option>
    <option value="bin2hex">Binary to Hex</option>
    <option value="hex2bin">Hex to Binary</option>
    <option value="backwards">Backwards</option>
    <option value="b64enc">Base 64 Encode</option>
    <option value="b64dec">Base 64 Decode</option>
	<option value="caesarbf">Caesar Bruteforce</option>
    <option value="crypt">DES Crypt (one way)</option>
    <option value="entityenc">HTML Entities Encode</option>
    <option value="entitydec">HTML Entities Decode</option>
    <option value="l33t">l33t 5p34k 3nc0d3</option>
    <option value="del33t">l33t 5p34k d3c0d3</option>
    <option value="md5">MD5 Crypt (one way)</option>
    <option value="igpay">Igpay Atinlay</option>
    <option value="unigpay">Un-Pig Latin</option>
    <option value="rot-13">ROT-13</option>
    <option value="urlenc">URL Encode</option>
    <option value="urldec">URL Decode</option>
   </select><br />
   <input type="submit" name="submit" value="OK" />
   <input type="reset" value="Clear" />
  </form>
 </center>
 <!-- end form -->

 <!-- begin footer; it would be nice if you would leave this on. ;) -->
 <center>
  <p>
     <font size="1">Fine Print Shtuff:<br />
     SNEAK: Snarkles.Net Encryption Assortment Kit - Version <?php echo($version); ?><br />
     &copy; 2000, 2001, 2002, 2003 <a href="http://snarkles.net">snarkles</a><br />
     Download a copy <a href="http://snarkles.net/scripts/sneak/sneak-<?php echo($version); ?>.zip">here</a></font>
  </p>
 </center>
 <!-- end footer -->

 </body>
</html>