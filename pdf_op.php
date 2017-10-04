<?php
 
  
 if(!empty($_POST['orient']))
{
	$titl=$_POST['titl'];
	$content=$_POST['content'];
	$orient=$_POST['orient'];
	set_time_limit(0);
		
	
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	
	/*//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'grlogo.png';
        $this->Image($image_file, 10, 10, 60, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		//$this->Cell(array(0,64,255), array(0,64,128));
		$this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		
	}*/

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);	
        	
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		
		// Page number
		//$this->Cell(array(0,64,255), array(0,64,128));
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		
	}
}

/**
 * Page orientation (P=portrait, L=landscape).
 */
if($orient=="P")
define ('PDF_PG_ORIENTATION', 'P');
else if($orient=="L")
define ('PDF_PG_ORIENTATION', 'L');

// create new PDF document
$pdf = new MYPDF(PDF_PG_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Results11');
$pdf->SetAuthor('Results11');
$pdf->SetTitle('PDF');
$pdf->SetSubject('PDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING.' on Sep 19, 2017', array(0,64,255), array(0,64,128));
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Condition for removing  default header/footer
if(!isset($_POST['head']))
   $pdf->setPrintHeader(false);

if(!isset($_POST['foot']))
   $pdf->setPrintFooter(false);


// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<h1>$titl</h1></br>
$content

EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.

if(isset($_POST['download']))
$pdf->Output('Creating_pdf.pdf', 'D');
else
$pdf->Output('Creating_pdf.pdf', 'I');
}


//============================================================+
// END OF FILE
//============================================================+
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Results11</title>
<!----------CSS------------------->
<link href="https://fonts.googleapis.com/css?family=Salsa" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Mate+SC" rel="stylesheet">
<link rel="stylesheet" href="style_sheet.css" />
<!------tinymce textarea and for math wiris plugin---------->
<script type="text/javascript" src="tinymce/js/jquery.min.js"></script>
<script type="text/javascript" src="tinymce/js/plugin/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="tinymce/js/plugin/tinymce/init-tinymce.js"></script>
<script type="text/javascript" src="tinymce/js/plugin/tiny_mce_wiris/integration/WIRISplugins.js"></script>
</head>

<body>

<div> <img src="images/logo.svg" alt="logo"/></div>

<div class="container" >
        <h2 >PDF Creator</h2>
        <form name="f1" action="pdf_op.php" method="post" >
              <div class="lhs">
               <label for="titl">Title</label><br/><br/>
               <label> Layout</label><br/><br/>
               <label>HTML Content</label><br/><br/><br/><br/><br/><br/><br/><br/>
               <label>Orientation</label>
              </div>
              <div class="rhs">
              <input type="text" name="titl" required="required" autocomplete="off" /><br/><br/>       
              <label><input type="checkbox" required="required" name="head"/>Header</label>
              <label><input type="checkbox"  required="required"name="foot"/>Footer</label><br/><br/>
                  <div class="txtarea"><textarea name="content" class="tinymce"></textarea></div><br/><br/><br/><br/><br/><br/><br/><br/>
              &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" name="orient" id="orient" value="P"  required="required"/>Portrait</label>
              <label><input type="radio" name="orient" id="orient" value="L" required="required"/>Landscape </label><br/><br/><br/>
                            <center>
                            <input type="reset" name="reset" value="Reset" class="btn" />
                            <input type="submit" name="submit"  value="View pdf" class="btn" />
                            <input type="submit" name="download" value="Download" class="btn" /></center>
                        
              </div>
         </form>
</div>

</body>
</html>
