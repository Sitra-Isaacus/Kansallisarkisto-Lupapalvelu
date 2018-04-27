<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: paatos_pdf
 *
 * Created: 23.10.2017
 */

if (isset($data['pdf_content'])) {

	$pdf_content = base64_decode($data['pdf_content']);
	if (!$pdf_content || strlen($pdf_content)<20) die("Pdf generation failed");

    header("Content-type:application/pdf");
	header("Content-Disposition:inline; filename='{$data['document_filename']}.pdf'");
    //header("Content-Disposition:attachment;filename='{$data['document_filename']}.pdf'");

    echo $pdf_content;

} else {

include './ui/template/header.php';
?>
<? if(isset($_GET['virhe'])){
		$virhe = $_GET['virhe'];
	} ?>
<?php include './ui/template/vasen_menu.php'; ?>

	<div class="laatikko10">
		<h1><?php echo VIRHE; ?></h1>
		<p>
			<?php if(isset($virhe)){ ?>
				<?php echo $virhe; ?><br>
			<?php } else { ?>
				<?php echo YLEINEN_VIRHEILMOITUS; ?><br>
			<?php } ?>
		</p>
        <pre>
            <?php
            //print_r($data);
            ?>
        </pre>

	</div>

<?php
	include './ui/template/footer.php';

}
?>