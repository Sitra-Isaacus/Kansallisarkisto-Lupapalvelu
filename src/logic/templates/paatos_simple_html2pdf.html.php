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
                <div style="font-weight: bold"><?=constant($data['Viranomaisen_koodi'])?> <?php echo mb_strtolower(YHTEYSHENKILO, "UTF-8"); ?> </div>
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
    <div class="bl">&nbsp;</div>

    <div class="ind">
        <?=$data['Vapaamuotoinen_paatos']?>
    </div>

    <br>
    <div class="bl">&nbsp;</div>
    <div class="ind">
        <?=constant($data['Viranomaisen_koodi'])?><br>
        <br>
        <br>
    </div>

    <div class="bl">&nbsp;</div>
    <div class="ind">
        <table style="width:100%" nobr="true">
            <tbody>
            <tr>
                <?php
                $cnt=0;
                foreach ($data['paattajat'] as $paattaja) {
                    $cnt++;
                    echo "<td style=\"width:25%\">{$paattaja}<!-- br>[titteli] --></td>";
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
