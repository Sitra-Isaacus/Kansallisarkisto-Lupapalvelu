<?php
if (!defined('FMAS')) die('Direct access not allowed.');
//include_once("./lang_fi.php");
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
                    <?php if (isset($data['logo'])) { ?>
                        <img src="<?=$data['logo']?>" class="viranomaisen-logo">
                    <?php } ?>
                    &nbsp;
				</td>
				<td class="colontitle-date-td">
					<?php echo mb_strtoupper(PAATOS, "UTF-8"); ?><br>
					<?=$data['Diaarinumero']?><br>
                    <?=date_format(date_create($data['Paatospvm']), 'd.m.Y')?><br>
                    <br>
                    <br>
				</td>
				<td class="colontitle-pages-td">
					[[page_cu]]/[[page_nb]]
				</td>
			</tr>
		</table>
	</page_header>
	<page_footer>
		<table class="wide-table">
            <tbody>
			<tr>
				<td class="footer-td">
                    <?=constant($data['Viranomaisen_koodi'])?><br>
                    <?=$data['Viranomaisen_nimi']?>, <?=$data['Viranomaisen_puhelin']?>, <?=$data['Viranomaisen_sahkoposti']?>
                </td>
			</tr>
            </tbody>
		</table>
	</page_footer>

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
                <div style="font-weight: bold"><?=constant($data['Viranomaisen_koodi'])?> <?php echo mb_strtolower(YHTEYSHENKILO, "UTF-8"); ?></div>
                <br>
                <?=$data['Viranomaisen_nimi']?><br>
                <?=$data['Viranomaisen_sahkoposti']?><br>
                <?=$data['Viranomaisen_puhelin']?><br>
            </td>
		</tr>
		</tbody>
	</table>

    <br>
    <?php echo HAKEMUKSENNE; ?> <?=date_format(date_create($data['Paatospvm']), 'd.m.Y')?>
    <br>

    <div class="header-1">
        <?php echo PAATOS_OTSIKKO; ?>
    </div>
    <div class="bl">&nbsp;</div>

    <div class="ind header-2"><?php echo mb_strtoupper(PAATOS, "UTF-8"); ?></div>

    <?php if ($data["paat_tila_hyvaksytty"]) { ?>

        <div class="bl">&nbsp;</div>
        <div class="ind">
            <p><?=constant($data['Viranomaisen_koodi'])?> <?php echo PAATOS_LISATIEDOT; ?></p>
        </div>
        <div class="bl">&nbsp;</div>

        <div class="ind header-2"><?php echo mb_strtoupper(LUV_VOIMOLO, "UTF-8"); ?></div>
        <div class="bl">&nbsp;</div>
        <div class="ind"><?php echo LUPA_ON_VOIMASSA; ?> <?=$data['Lakkaamispvm']?> <?php echo ASTI; ?>.</div>
        <div class="bl">&nbsp;</div>

    <?php } elseif ($data["paat_tila_hylatty"]) { ?>

        <div class="ind header-2"><?php echo HAK_HYL_PER; ?>:</div>
        <p class="ind"><?=$data['Hylkayksen_perustelut']?></p>
        <div class="bl">&nbsp;</div>

    <?php } ?>

    <div class="ind header-2"><?php echo mb_strtoupper(TUTKIMUKSEN_NIMI, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Tutkimuksen_nimi']?></p>
    <div class="bl">&nbsp;</div>

    <?php if ($data['Rekisterinpitaja_nimi']) { ?>
    <div class="ind header-2"><?php echo mb_strtoupper(REKISTERINPITAJA, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind">
        <?=$data['Rekisterinpitaja_nimi']?><br>
        <?=$data['Rekisterinpitaja_osoite']?><br>
    </p>
    <div class="bl">&nbsp;</div>
    <?php } elseif (is_array($data['Rekisterinpitajat']) && count($data['Rekisterinpitajat'])>0) { ?>
        <?php for ($i=0; $i<count($data['Rekisterinpitajat']); $i++) { ?>
        <?=$data['Rekisterinpitajat'][$i]['nimi']?><br>
        <?=$data['Rekisterinpitajat'][$i]['osoite']?><br>
        <?php } ?>
    <?php } ?>

    <?php if ($data["paat_tila_hyvaksytty"]) { ?>
    <?php if (isset($data['Vastuullinen_johtaja_oppiarvo']) && $data['Vastuullinen_johtaja_oppiarvo']!='') { ?>

        <div class="ind header-2"><?php echo mb_strtoupper(rooli_vast, "UTF-8"); ?></div>
        <div class="bl">&nbsp;</div>
        <p class="ind">
            <?=$data['Vastuullinen_johtaja_oppiarvo']?><br>
            <?=$data['Vastuullinen_johtaja_nimi']?><br>
            <?=$data['Vastuullinen_johtaja_organisaatio']?><br>
        </p>
        <div class="bl">&nbsp;</div>

    <?php } ?>

    <div class="ind header-2"><?php echo mb_strtoupper(SALASSPIT_TUTK, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind">
    <?php

    foreach ($data['sitoumukset'] as $s) {
        echo $s['Oppiarvo']."<br>\n";
        echo $s['Nimi']."<br>\n";
        echo $s['Organisaatio']."<br>\n";
        echo "<br>\n";
    }

    ?>
    </p>
    <div class="bl">&nbsp;</div>

    <div class="ind header-2"><?php echo mb_strtoupper(AINEISTO, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo AINEISTO_LUOVUTETAAN; ?>:</p>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Luovutettavat_tiedot']?></p>
    <div class="bl">&nbsp;</div>

    <?php
    /*
    foreach ($data['paatetyt_aineistot'] as $paatety_aineisto) {
        foreach ($paatety_aineisto as $paatety_luvan_kohte) {
            if ($paatety_luvan_kohte['Luvan_kohteen_nimi']!='') {
                echo "<div class=\"bl\">&nbsp;</div>";
                echo "<p class=\"ind\">{$paatety_luvan_kohte['Luvan_kohteen_nimi']}, tiedot vuosilta <xxxx–xxxx></p>";
            }
        }
    }
    */
    ?>
    <div class="bl">&nbsp;</div>

    <div class="ind header-2"><?php echo mb_strtoupper(LUOVUTUSTAPA, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Luovutustapa']?></p>
    <div class="bl">&nbsp;</div>

    <div class="ind header-2"><?php echo mb_strtoupper(HINTA_ARVIO, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo HINTA_ARVIO_TIEDOT; ?> <?=$data['Hinta_arvio']?> <?php echo HINTA_ARVIO_TIEDOT_2; ?> <?=$data['Hinta_arvio_alkuvuosi']?>-<?=$data['Hinta_arvio_loppuvuosi']?>.</p>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo HINTA_ARVIO_TIEDOT_3; ?>.</p>
    <div class="bl">&nbsp;</div>

    <?php } // endif ($data["paat_tila_hyvaksytty"]) ?>

    <?php if ($data['Luvan_lausunnot']) { ?>
    <div class="ind header-2"><?php echo mb_strtoupper(LUVAN_LAUSUNNOT, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Luvan_lausunnot']?></p>
    <div class="bl">&nbsp;</div>
    <?php } ?>

    <?php if ($data['Sovelletut_oikeusohjeet']) { ?>
    <div class="ind header-2"><?php echo mb_strtoupper(SOV_OIKEUSOHJ, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Sovelletut_oikeusohjeet']?></p>
    <div class="bl">&nbsp;</div>
    <?php } ?>

    <?php if ($data["paat_tila_hyvaksytty"]) { ?>
    <div class="ind header-2"><?php echo mb_strtoupper(LUVAN_EHDOT, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo LUPA_MYONN_EHD; ?>:</p>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Luvan_ehdot']?></p>
    <div class="bl">&nbsp;</div>
    <?php } ?>

    <div class="ind header-2"><?php echo mb_strtoupper(VALITUSOSOITUS, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo PAAT_VAL; ?></p>
    <div class="bl">&nbsp;</div>
 
    <?php if ($data["paat_tila_hyvaksytty"]) { ?>

    <?php if ($data["Maksu_euroina"] && $data['Maksu_peruste']) { ?>
    <div class="ind header-2"><?php echo mb_strtoupper(MAKSU, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo TUTK_MAKSU; ?> <?=$data['Maksu_euroina']?> <?php echo EUROA; ?>.</p>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo MAKS_PER; ?></p>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?=$data['Maksu_peruste']?>.</p>
    <?php } ?>

    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo LUP_MAKS_VELV; ?></p>
    <p class="ind">
        <?=$data['Laskutustieto_1']?><br>
        <?=$data['Laskutustieto_2']?>
    </p>
    <div class="bl">&nbsp;</div>

    <p class="ind"><?php echo MAKSU_INFO; ?>.</p>
    <div class="bl">&nbsp;</div>

    <div class="ind header-2"><?php echo mb_strtoupper(HAV_ARK_ILMO, "UTF-8"); ?></div>
    <div class="bl">&nbsp;</div>
    <p class="ind"><?php echo HAV_ARK_ILMO_TIEDOT; ?>.</p>
    <div class="bl">&nbsp;</div>
    <?php } ?>

    <br>
    <div class="ind">
        <?=constant($data['Viranomaisen_koodi'])?><br>
        <br>
        <br>
        <br>
    </div>

    <div class="ind">
        <table style="width:100%" nobr="true">
            <tbody>
                <tr>
                    <?php
                    $cnt=0;
                    foreach ($data['paattajat'] as $paattaja) {
                        $cnt++;
                        echo "<td style=\"width:25%\">{$paattaja}<br><!-- [titteli] --></td>";
                        if ($cnt/3 == round($cnt/3)) echo "</tr><tr>";
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>

    <br><br>

    <?php echo print_liiteet($data['paatoksen_liitteet']); ?>

</page>
