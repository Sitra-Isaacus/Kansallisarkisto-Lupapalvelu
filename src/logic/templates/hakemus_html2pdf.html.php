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
				
                    <?php if(isset($data["FK_Lomake"]) && $data["FK_Lomake"]==1){ ?>
						<?php echo mb_strtoupper(KAYTTOLUPAHAKEMUS,"UTF-8"); ?><br>
					<?php } ?>
					
					<?php if(isset($data["FK_Lomake"]) && $data["FK_Lomake"]==27){ ?>
						EETTINEN LAUSUNTOPYYNTÖ
					<?php } ?>
					
                    <?=$data['HakemusPVM']?>
                    <br>
                    <br>
                </td>
				<td class="colontitle-date-td">
                    &nbsp;
				</td>
				<td class="colontitle-pages-td">
					[[page_cu]]/[[page_nb]]
				</td>
			</tr>
		</table>
	</page_header>

    <table class="wide-table">
        <tbody>
        <tr>
            <td class="head-col-1">
                <div style="font-weight: bold"><?php echo LUVANHAKIJA . " / " . YHTEYSHENKILO; ?></div>
                <br>
                <?=$data['Oppiarvo']?><br>
                <?=$data['Hakijan_nimi']?><br>
                <?=$data['Hakijan_osoite']?><br>
                <?=$data['Hakijan_sahkoposti']?><br>
                <?=$data['Hakijan_puhelin']?><br>
            </td>
            <td class="head-col-2">
                &nbsp;
            </td>
        </tr>
        </tbody>
    </table>

    <br>

    <div class="header-1">
        <?php echo HAKEMUS_PDF_OTS; ?>
    </div>

    <?php
    for ($i=0; $i<count($data['segments']); $i++) {

        if (count($data['segments'][$i]['blocks'])>0) {

            if (segment_has_nonempty_blocks($data['segments'][$i]['blocks'])) echo "<div class=\"header-1\">{$data['segments'][$i]['heading']}</div>\n";

            for ($j=0; $j<count($data['segments'][$i]['blocks']); $j++) {

                if (count($data['segments'][$i]["blocks"][$j]["titles"])>0) {

                    echo "<div class=\"ind header-2\">{$data['segments'][$i]['blocks'][$j]['block_title']}</div>\n";

                    echo generate_titles($data['segments'][$i]["blocks"][$j]["titles"]);

                }				

            }

        }

    }
    ?>

    <br><br>

    <?php echo print_liiteet($data['liitteet']); ?>

</page>
