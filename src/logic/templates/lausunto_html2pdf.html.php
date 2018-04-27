<?php
if (!defined('FMAS')) die('Direct access not allowed.');
?>
<style type="text/css">
    <!--
    <?php echo file_get_contents("templates/html2pdf.css"); ?>
    -->
</style>

<page backtop="30mm" backbottom="15mm" backleft="20mm" backright="20mm">
	<page_header>
        <br>
		<table class="wide-table">
			<tr>
                <td class="colontitle-indent">
                    &nbsp;
                </td>
				<td class="viranomaisen-logo-td">
					<!-- img src="templates/viranomaisen-logo.png" class="viranomaisen-logo" -->
                    &nbsp;
				</td>
				<td class="colontitle-date-td">
                    <?php echo mb_strtoupper(LAUSUNTO, "UTF-8"); ?><br>
					<?=$data['Diaarinumero']?><br>
                    <?=$data['Document_date']?><br>
                    <br>
                    <br>
				</td>
				<td class="colontitle-pages-td">
					[[page_cu]]/[[page_nb]]
				</td>
			</tr>
		</table>
	</page_header>

    <br>

    <div class="header-1">
        <?php echo mb_strtoupper(LAUSUNTO_PDF_OTS, "UTF-8"); ?>
    </div>

    <div class="header-2"><?php echo TUTKIMUKSEN_NIMI; ?>:</div>
    <div class="bl">&nbsp;</div>
        <div class="ind"><?=$data['Tutkimuksen_nimi']?></div>

    <?php
    if (is_array($data['Vastuuorganisaatiot']) && count($data['Vastuuorganisaatiot'])>0) {
        ?>
        <div class="header-2"><?php echo OSALL_ORGAN; ?>:</div>
        <div class="bl">&nbsp;</div>
        <?php
        foreach ($data['Vastuuorganisaatiot'] as $Nimi) {
            ?>
            <div class="ind"><?=$Nimi?></div>
            <?php
        }
    }
    ?>

    <div class="header-2"><?php echo LAUS_ANTAJA; ?>:</div>
        <div class="ind"><?=$data['Lausunnonantaja_nimi']?></div>
        <div class="ind"><?=constant($data['Viranomaisen_koodi'])?></div>

	<br>

        <?php

        echo generate_titles($data["titles"]);

        ?>

    <br>

    <div class="header-1">
        <?php echo mb_strtoupper(KANN_OTT_HAK, "UTF-8"); ?>
    </div>
    <br>
	
    <div class="ind">
	
        <?php if($data['laus_kylla_checked']){ ?>
			<?php echo PUOLLAN; ?>
		<?php } ?>
		
        <?php if($data['laus_ehto_checked']){ ?>
			
			<?php echo PUOLLAN_EHDOLLISENA2; ?>:
			
			<br><br>
			
			<?=$data['Ehdollinen_puoltaminen']?>
			
		<?php } ?>	

        <?php if($data['laus_ei_checked']){ ?>
		
			<?php echo EN_PUOLLA; ?>.
			
			<br><br>
			
			<?php echo PERUSTELUT; ?>:<br><br>
			<?=$data['Johtopaatoksen_perustelut']?>		
			
		<?php } ?>
		
    </div>	
	



    <br><br>

    <?php echo print_liiteet($data['liitteet']); ?>

</page>
