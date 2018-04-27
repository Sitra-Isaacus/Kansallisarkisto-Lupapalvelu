<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: language file (Suomi)
 *
 * Created: 5.10.2015
 */

	// Yleistä
	// Omat tiedot
	// Virhe
	// Tutkijan käyttöliittymä > k
	// Tutkijan käyttöliittymä > Perustiedot
	// Tutkijan käyttöliittymä > Tutkimusryhmä
	// Tutkijan käyttöliittymä > Toimitus ja elinkaari
	// Tutkijan käyttöliittymä > Viranomaiskohtaiset
	// Tutkijan käyttöliittymä > Erääntyvät käyttöluvat
	// Tutkijan käyttöliittymä > Rekisteriseloste
	// Tutkijan käyttöliittymä > Liitteet
	// Tutkijan käyttöliittymä > Hakemus
	// Tutkijan käyttöliittymä > Rekisteröidy
	// Tutkijan käyttöliittymä > Viestit
	// Tutkijan käyttöliittymä > LAUSUNNOT
	// Tutkijan käyttöliittymä > Päätökset
	// Tutkijan käyttöliittymä > Aineistotilaus
	// Tutkijan käyttöliittymä > Käyttäjän liittäminen hakemukseen
	// Muut
	// Viranomaisen käyttöliittymä
	// Aineistonmuodostajan käyttöliittymä
	// Lausunnonantajan käyttöliittymä
	// Pääkäyttäjän käyttöliittymä
	// SAAPUNEET_VIESTIT määritelty jo aiemmin
	// Ohjetekstejä

	// Koodistot
	// AINEISTO
	// AINEISTON_TIEDOSTOMUOTO
	// AINEISTON_TOIMITUS
	// AINEISTOTILAUKSEN_TILA
	// KOHDEJOUKON TILA
	// AINEISTOTILAUKSEN_TYYPPI
	// HAKEMUKSEN_TILA
	// JOUKON_TYYPPI
	// KIELI
	// KOHDEJOUKON_KERUULAAJUUS
	// KOHDEJOUKON_TIETOLÄHDE
	// KYLLÄ_EI
	// KÄYTTÄJÄRYHMÄ
	// LASKUTUS
	// LAUSUNTO
	// LIITTEET
	// MUU
	// OPINNÄYTETYÖ
	// PÄÄTÖKSEN_TILA
	// ROOLI2
	// ROOLI3
	// ROOLI4
	// ROOLI5
	// SUORAT_TUNNISTEET
	// TIETEENALA
	// TOIMENPITEET_PAATTYESSA
	// VIRANOMAINEN
	// PDF

	// Tutkijan käyttöliittymä > Aineiston määrittely > Tutkimusasetelman kuvailu
	// Tutkijan käyttöliittymä > Aineiston määrittely > Tutkimuksen KTVV määrittely
	// Tutkijan käyttöliittymä > Kohdejoukolle/Tapauksille ja verrokeille ja viitehenkilöille poimittavat muuttujat

	// Ohjeet
	// Hallinta > Lomakkeet > Uusi lomake
	// Hallinta > Lomakkeet > Uusi lomake > Liitetiedostot-sivu
	// Ehtolauseet
	// Hallinta > Lomakkeet > Uusi lomake > Kysymysten väliset suhteet
	// Toiminnot
	// Pääkäyttäjän käyttöliittymä > Lomakkeiden hallinta
	// Hallinta > Lomakkeet > Uusi lomake > Lisää uusi lomakkeen sivu
 
// Yleistä
DEFINE("LOMAKE", "Lomake"); 
DEFINE("TUTKIMUS", "Tutkimus"); 
DEFINE("VERSIO", "Versio"); 
DEFINE("HAKEMUKSEN", "Hakemuksen"); 
DEFINE("TUN_SAL_PAKK", "Tunnus ja salasana ovat pakollisia tietoja"); 
DEFINE("KIRJ_TUN_SAL", "Kirjaudu sisään tunnuksella ja salasanalla."); 
DEFINE("ET_OLE_KIRJ", "Et ole kirjautunut"); 
DEFINE("OLET_KIRJ", "olet kirjautunut"); 
DEFINE("OLET_KIRJ_ULOS", "Olet kirjautunut ulos"); 
DEFINE("KIRJ_LPAL", "Kirjaudu sisään Lupapalveluun"); 
DEFINE("REK_UUDELL", "Rekisteröidy uudelleen tai ota yhteyttä tukeen sähköpostitse: lupa.tuki (a) arkisto.fi"); 
DEFINE("KAYTTOLUPAHAKEMUS", "Käyttölupahakemus"); 
DEFINE("LUVANHAKIJA", "Luvanhakija"); 
DEFINE("HAKEMUS_PDF_OTS", "Käyttölupahakemus tietojen saamiseksi salassa pidettävistä rekistereistä ja asiakirjoista"); 
DEFINE("LAUSUNTO_PDF_OTS", "Asiantuntijan lausunto salassa pidettävien tietojen käyttölupahakemuksesta"); 
DEFINE("OSALL_ORGAN", "Osallistuvat orgaanisaatiot");  
DEFINE("LAUS_ANTAJA", "Lausunnonantaja"); 
DEFINE("KANN_OTT_HAK", "Kannanotto hakemukseen"); 
DEFINE("HAKEMUKSENNE", "Hakemuksenne");

// Sähköposti-ilmoitukset
DEFINE("EMAIL_KUTSU_OTSIKKO", "Kutsu käyttölupahakemuksen tutkimusryhmään");
DEFINE("EMAIL_KUTSU_VIESTI", "Sinut on liitetty käyttölupahakemuksen tutkimusryhmään. Klikkaamalla linkkiä vahvistat jäsenyyden");
DEFINE("REKISTEROINTI_OTSIKKO", "Uusi Lupapalvelun käyttäjä");
DEFINE("REKISTEROINTI_VIESTI_A", "Sinut on lisätty Lupapalvelun käyttäjäksi");
DEFINE("REKISTEROINTI_VIESTI_B", "Klikkaamalla linkkiä vahvistat rekisteröitymisen");
DEFINE("LAUSUNTOPYYNTO_OTSIKKO", "Sähköinen käyttölupapalvelu: Uusi lausuntopyyntö palvelussa");
DEFINE("HEI", "Hei");
DEFINE("AUTO_VIESTI", "Tämä on automaattisesti luotu viesti, johon ei voi vastata");
DEFINE("LAUSUNTOPYYNTO_VIESTI_A", "Olet saanut uuden lausuntopyynnön käyttölupapalvelussa");
DEFINE("VOIT_TARK_PROS", "Voit tarkastella tekemääsi hakemusta ja seurata prosessin etenemistä lupapalvelussa");
DEFINE("VIESTI_OTSIKKO", "Sähköinen käyttölupapalvelu: Uusi viesti palvelussa");
DEFINE("VIESTI_SISALTO_A", "Olet saanut uuden viestin Käyttölupapalvelussa");
DEFINE("PAAT_VALMISTUNUT", "päätös on valmistunut");
DEFINE("ON", "on");
DEFINE("LAHETA_HAKEMUS_OTSIKKO", "Sähköinen käyttölupapalvelu: Hakemus vastaanotettu");
DEFINE("LAHETA_HAKEMUS_VIESTI_A", "on tallennettu onnistuneesti järjestelmään");
 
// Omat tiedot 
DEFINE("OMAT_TIEDOT", "Omat tiedot"); 

// Virhe
DEFINE("VIRHE", "Virhe"); 
DEFINE("YLEINEN_VIRHEILMOITUS", "Sivulla tapahtui virhe."); 
 
// Tutkijan käyttöliittymä > etusivu
DEFINE("KAYTTOLUPAPALVELU", "Käyttölupapalvelu");
DEFINE("ETUSIVU", "Etusivu");
DEFINE("OHJE", "Ohje");
DEFINE("KIRJAUDU", "Kirjaudu");
DEFINE("KIRJAUDU_ULOS", "Kirjaudu ulos");
DEFINE("UUSI_HAKEMUS", "Uusi hakemus");
DEFINE("OMAT_HAKEMUKSET", "Omat hakemukset");
DEFINE("PAATETYT_HAKEMUKSET", "Päätetyt hakemukset");
DEFINE("SAAPUNEET_VIESTIT", "Saapuneet viestit");
DEFINE("ERAANTYVAT_KAYTTOLUVAT", "Erääntyvät käyttöluvat");
DEFINE("TUTKIMUKSEN_NIMI", "Tutkimuksen nimi");
DEFINE("TUTKIMUKSEN_NIMI_FI", "Tutkimuksen nimi suomeksi");
DEFINE("TUTKIMUKSEN_NIMI_EN", "Tutkimuksen nimi englanniksi");
DEFINE("DIAARINUMERO", "Diaarinumero");
DEFINE("HAKEMUKSEN_TILA", "Hakemuksen tila");
DEFINE("TILAN_PVM", "Tilan pvm");
DEFINE("HAKEMUS", "Hakemus");
DEFINE("KESKENERAISET_HAKEMUKSET", "Keskeneräiset hakemukset");
DEFINE("LAHETETYT_HAKEMUKSET", "Lähetetyt hakemukset");
DEFINE("PAATOS", "Päätös");
DEFINE("AINEISTOTILAUKSET", "Aineistotilaukset");
DEFINE("PERUUTA_HAKEMUKSET", "Peruuta hakemukset");
DEFINE("PERUUTA_HAKEMUS", "Peruuta hakemus");
DEFINE("MUUTOSHAKEMUS_OLEMASSA_OLEVAAN", "Sähköisessä Käyttölupapalvelussa tehtyyn hakemukseen");
DEFINE("MUUTOSHAKEMUS_AIEMPAAN_HAKEMUKSEEN", "Aiempaan paperihakemuksen");
DEFINE("VALITSE_HAKEMUS", "Valitse hakemus");
DEFINE("KIRJAUDU_SISAAN", "Kirjaudu sisään");
DEFINE("KIRJAUDU_INFO", "Kirjaudu sisään tunnuksella ja salasanalla.");
DEFINE("POISTO_VARMISTUS", "Haluatko varmasti poistaa hakemuksen?");
DEFINE("LIITE_POISTO_VARMISTUS", "Haluatko varmasti poistaa liitetiedoston?");
DEFINE("PERUUTA_VARMISTUS", "Huomasithan, että muutoksiin voit käyttää muutoshakemusta. Haluatko varmasti perua hakemuksen/hakemukset?");
DEFINE("PERUUTA_AIN_TILAUS_VARMISTUS", "Haluatko varmasti peruuttaa aineistotilauksen?");
DEFINE("UUSI_HAKEMUS_LUOTU", "Uusi hakemus luotu.");
DEFINE("HAKEMUKSEN_LUOM_EPAONNISTUI", "Hakemuksen luominen epäonnistui.");
DEFINE("HAK_POISTETTU", "Hakemus poistettu.");
DEFINE("HAK_EI_POIST", "Hakemuksen poisto epäonnistui.");
DEFINE("PAAT_HAK", "Päätetyt hakemukset");
DEFINE("TILAA_AINEISTO", "Tilaa aineistot");
DEFINE("PYYD_LISTIET", "Pyydetty lisätietoa");
DEFINE("TOIM_TAYD_ASK", "Toimita täydennysasiakirjat");
DEFINE("PAAT_LIITTEET", "Päätöksen liitteet");
DEFINE("LAH_TAYD_ASKI", "Lähetä täydennysasiakirjat");
DEFINE("TAYDENNA_HAK", "Täydennä hakemusta");
DEFINE("HAK_LAH_VARM", "Haluatko varmasti lähettää hakemuksen?");
DEFINE("HAK_PER_FAIL", "Hakemusten peruminen epäonnistui");

// Tutkijan käyttöliittymä > Perustiedot
DEFINE("HAKEMUS_TALLENNETTU", "Hakemus tallennettu");
DEFINE("LOMAKE_TALLENNETTU", "Lomake tallennettu");
DEFINE("TARKASTELET_VAIN_LUKU", "Tarkastelet hakemusta vain luku -tilassa.");
DEFINE("VOIT_MUOK", "Voit muokata hakemusta, kun");
DEFINE("ON_LOPETTANUT", "on lopettanut hakemuksen täyttämisen.");
DEFINE("TIEDOT_PAIVITETTY", "Tiedot muutettu");
DEFINE("LISATIETOJA", "Lisätietoja");
DEFINE("MUUTTUNEET_TIEDOT", "Valitun kentän aiemmat tiedot");
DEFINE("PERUSTIEDOT", "Perustiedot");
DEFINE("TALLENNA", "Tallenna");
DEFINE("TALLENNA_HAKEMUS", "Tallenna hakemus");
DEFINE("PERUUTA", "Peruuta");
DEFINE("TILA", "Tila");
DEFINE("TULOSTA_PDF", "Tallenna PDF-muodossa");
DEFINE("TALLENNA_HAKEMUS_PDF", "Lataa hakemus PDF-muodossa");
DEFINE("TUTKIMUKSEN_PERUSTIEDOT", "Tutkimuksen perustiedot");
DEFINE("MERKITYT_KENTAT", "(Tähdellä (*) merkityt kentät ovat pakollisia)");
DEFINE("TUTKIMUKSEN_KOKONAISKESTO", "Tutkimuksen arvioitu kokonaiskesto (luvan voimassaoloaika)");
DEFINE("INFO_KOKONAISKESTO", "Tutkimuksen kokonaiskestoa arvioitaessa on huomioitava se, että henkilötietoja ei saa käsitellä käyttöluvan päätyttyä. Henkilötietojen käytön perustetta ja käsittelyn tarvetta on arvioitava vähintään viiden vuoden välein (Henkilötietolaki 523/1999 12 §).");
DEFINE("KUVAA_KAYTTOTARKOITUS", "Kuvaa rekisteritietojen käyttötarkoitus");
DEFINE("INFO_KAYTTOTARKOITUS", "Tutkimusta varten tutkijoille kerätyistä henkilötiedoista muodostuu oma erillinen henkilörekisteri. Henkilötietojen käytön tarkoitus on määriteltävä tarkoin ennen henkilörekisterin perustamista esim. kuvaamalla tutkimuksen kohdejoukko ja ajanjakso. Henkilötietojen käsittelyn tarkoitus kertoo minkä tutkimuksen tekemiseksi henkilörekisteri on perustettu.");
DEFINE("ONKO_OPINNAYTETYO", "Onko tietojen käyttötarkoitus opinnäytetyö?");
DEFINE("INFO_OPINNAYTETYO", "Tutkimus ei ole opinnäytetyö, jos osana laajempaa tutkimushanketta tehdään yksi tai useampi opinnäytetyö. Opinnäytetyöntekijät ja opinnäytetyönohjaajat yksilöidään tarkemmin Hakijat-välilehdellä.");
DEFINE("JULKAISUSUUNNITELMA", "Julkaisusuunnitelma");
DEFINE("KERRO_MISSA", "Kuinka tulokset aiotaan julkaista?");
DEFINE("TIETEENALAT", "Tieteenalat");
DEFINE("VALITSE_TIETEENALAT", "Valitse halutessasi tutkimuksen tieteenalat. Tietoa käytetään ainoastaan käyttölupien tilastointia varten");
DEFINE("LASKUTUSTIEDOT", "Laskutustiedot");
DEFINE("LASKUTUS", "Laskutus");
DEFINE("VERKKOLASKU", "Verkkolasku");
DEFINE("PAPERILASKU", "Paperilasku");
DEFINE("YHTEYSHENKILO", "Yhteyshenkilö");
DEFINE("YKSIKON_NIMI", "Yksikön nimi");
DEFINE("VERKKOLASKUOSOITE", "Verkkolaskuosoite");
DEFINE("VERKKOLASKUN_VALITTAJA", "Verkkolaskun välittäjä");
DEFINE("VALITTAJATUNNUS", "Välittäjätunnus");
DEFINE("ORGANISAATION_NIMI", "Organisaation nimi");
DEFINE("ORGANISAATION_YTUNNUS", "Organisaation Y-tunnus");
DEFINE("LISATIEDOT", "Lisätiedot");
DEFINE("POISTA_HAKEMUS", "Poista hakemus");
DEFINE("ONKO_TUTK_EETTI_TOIM", "Onko tutkimus ollut eettisen toimielimen arvioitavana?");
DEFINE("ONKO_ORG_YHT_HANKE", "Onko kyseessä kahden tai useamman organisaation yhteishanke?");
DEFINE("LUETTELE_ORGANISAATIOT", "Luettele osallistuvat organisaatiot");
DEFINE("AINEISTON_TOIMITUS", "Aineiston toimitustapa ja vastaanotettavien tietojen ja näytteiden käsittely");
DEFINE("ETAKAYTTOJARJESTELMA", "Etäkäyttöjärjestelmä");
DEFINE("toimitus_fyysinen", "Fyysinen tallenne");
DEFINE("MUU_MIKA", "Muu, mikä?");
DEFINE("TUTK_TIIVISTELMA", "Julkinen kuvaus tutkimuksesta suomeksi");
DEFINE("TUTK_TIIVISTELMA_EN", "Julkinen kuvaus tutkimuksesta englanniksi");
DEFINE("TUTK_TIIVISTELMA_INFO", "Kansantajuinen tiivistelmä tutkimuksesta julkaistavaksi tuki- ja informaatioportaalissa ja aineistontoimittajien sivuilla. Kuvauksesta tulee käydä ilmi tutkimuksen tavoite, kohdejoukko (keitä tutkitaan) sekä aineistopohja (max 150 sanaa)");
DEFINE("TUTKIMUKSEN_TUNNISTE", "Tutkimuksen tunniste");
DEFINE("LYHYT_KUV_TUTK", "Lyhyt kuvaus tutkimuksesta");
DEFINE("LYHYT_KUV_TUTK_INFO", "Tutkimuksen tavoitteet, tutkimusasetelma, metodit, tieteellinen arvo, merkitys sekä tutkimuksen rahoitus");
DEFINE("KAYTETTAVAT_AINEISTOT", "Käytettävät aineistot");
DEFINE("KAYTETTAVAT_AINEISTOT_INFO", "Valitaan kaikki aineistotyypit, joita tutkimuksessa aiotaan käyttää");
DEFINE("REKISTERITIEDOT", "Rekisteritiedot");
DEFINE("REK_TSA", "Rekisteritiedot ja/tai terveyden- tai sosiaalihuollon asiakirjat");
DEFINE("REK_TSA_INFO", "THL myöntää luvat terveyden- ja sosiaalihuollon asiakirjoihin silloin, kun tutkimuksessa tarvitaan tietoja useista toimipaikoista");
DEFINE("BIOPANKKITIEDOT_NAYTTEET", "Biopankkinäytteet ja/tai –tiedot");
DEFINE("BIOPANKKITIEDOT_NAYTTEET_INFO", "X");
DEFINE("TERV_JA_SOS_ASIAKIRJAT", "Terveyden- ja/tai sosiaalihuollon asiakirjat");
DEFINE("AIK_KER_OMA_AINEISTO", "Aikaisemmin kerätty oma tutkimusaineisto");
DEFINE("MUU_AINEISTO", "Muu aineisto");
DEFINE("MAARITTELE_MUU_AINEISTO", "Määrittele muu aineisto");
DEFINE("AIN_KAYTETAAN_USEASSA_OPINN", "Tutkimusaineistoa käytetään useissa opinnäytetöissä");
DEFINE("ESIM_TOV", "Esim. tieteellinen artikkeli, opinnäytetyö, väitöskirja.");
DEFINE("LASKUTUSTIEDOT_INFO", "Lupapäätös ja aineistopoiminta ovat maksullisia. Laskun lähettämistä varten ilmoitetaan laskutettavan organisaation sähköiset laskutustiedot. Ainoastaan yksityishenkilöille voidaan lähettää paperilasku.");
DEFINE("LUOV_AIN_TOIM", "Luovutettavalle aineistolle toivottu toimitustapa");
DEFINE("LUOV_AIN_TOIM_INFO", "Jos valitaan useampi vaihtoehto, lisätietokentässä tulee kuvata, mille aineistolle toivotaan mitäkin luovutustapaa");
DEFINE("KYLLA_SET", "Kyllä, sairaanhoitopiirin eettisen toimikunnan");
DEFINE("KYLLA_MET", "Kyllä, muun eettisen toimielimen");
DEFINE("TUTKRYHM_OMA_ARVIO_EETT", "Tutkimusryhmän oma arvio tutkimuksen eettisyydestä");
DEFINE("PERUS_EHD_PROJ", "Perustelut ehdotetulle tutkimusprojektille");
DEFINE("PERUS_EHD_PROJ_INFO", "Perustele odotettavissa oleva tieteellinen hyöty, perustelut tutkimuksen tarpeellisuudelle");
DEFINE("PERUS_TIET_MENET", "Perustelu käytettävien tieteellisten menetelmien asianmukaisuudesta");
DEFINE("HYOTY_HAITTA_ARV", "Hyöty-haitta-arviointi näytteen antajan tai samaa tautiryhmää sairastavien kannalta "); 
DEFINE("ONKO_TARVE_YHT_POT", "Onko tarvetta ottaa yhteyttä potilaisiin, jotka ovat luovuttaneet näytettä ja dataa uudestaan? Jos on, miksi, milloin ja kuinka tämä toteutettaisiin?");
DEFINE("ODOT_HAITAT", "Odotettavissa olevat haitat ja riskit näytteenantajalle ja niiden todennäköisyys");
DEFINE("KUV_LOYD", "Kuvaus siitä, millaiset löydökset tässä tutkimuksessa voisivat olla sattumalöydöksiä ja miten ne ilmoitetaan biopankkiin");
DEFINE("TUTK_VAST_ARV_EETT", "Tutkimuksesta vastaavan henkilön lyhyt arviointi tutkimuksen eettisyydestä");
DEFINE("BIO_TUTKALUEET", "Biopankkien tutkimusalueet");
DEFINE("MIHIN_BIO_TUTKAL", "Mihin seuraavista biopankkien tutkimusalueista hakemus kuuluu?");
DEFINE("VAEST_TERV_ED", "Väestön terveyden edistäminen");
DEFINE("TAUT_TEK_TUNN", "Tautimekanismeihin vaikuttavien tekijöiden tunnistaminen, sairauksien ehkäisy");
DEFINE("VAEST_HYV_ED", "Väestön hyvinvointia tai terveyttä edistävien tai sairaanhoidossa käytettävien tuotteiden tai hoitokäytäntöjen kehittäminen");
DEFINE("HEMA_SAIR_EHK", "Hematologisten sairauksien (veritautien) ennaltaehkäisy, diagnostiikka, hoito ja seuranta"); 
DEFINE("BLLT_TUTK", "Biologinen, lääketieteellinen ja liikunta- ja terveystieteellinen tutkimus ja tuotekehitys");

// Muutoshakemus tekstit 
DEFINE("TUTK_RYHM_TAYD", "Tutkijaryhmän täydentäminen");
DEFINE("LUV_KEST_JATK", "Luvan keston jatkaminen");
DEFINE("TUTK_AIN_LAAJ", "Tutkimusaineiston laajentaminen");
DEFINE("TUTK_AIN_SEUR_VUOS_JATK", "Tutkimusaineiston seurantavuosien jatkaminen");
DEFINE("MUU_SYY_MIKA", "Muu syy, mikä?");

//Tutkijan käyttöliittymä > Tutkimusaineisto 
DEFINE("KUVAILU_KUV", "Tutkimusasetelman määrittelemisellä viitataan tässä yhteydessä empiirisen aineiston rakenteeseen. Laajempi tutkimusasetelman kuvaus tulee esittää erillisessä tutkimussuunnitelmassa.");
DEFINE("POISTA_REKISTERI", "Poista rekisteri/aineisto");
DEFINE("LISAA_REKISTERI", "Lisää uusi rekisteri tai aineisto");
DEFINE("BIOPANKKINAYTTEET", "Biopankkinäytteet tai biopankkitiedot");
DEFINE("LISAA_UUSI_AINEISTO", "Lisää uusi tutkimusaineisto");
DEFINE("TUTKIMUSASETELMAN_KUV", "Tutkimusasetelman kuvailu");
DEFINE("TUTKIMUSASETELMAN", "Tutkimusasetelman");
DEFINE("KUVAILU", "Kuvailu");
DEFINE("KUVAILU2", "kuvailu");
DEFINE("TIEDONK_LAAJUUS", "Tiedonkeruun laajuus");
DEFINE("MAARITTELE_LAAJUUS", "Määrittele tietojenkeruun laajuus");
DEFINE("KUVAA_ALUE", "Mitä aluetta/alueita tutkimus koskee?");
DEFINE("KUVAA_MONIK1", "Mitä maita tutkimuksessa on mukana?");
DEFINE("KUVAA_MONIK2", "Miltä alueelta Suomesta kerätään tietoja?");
DEFINE("TUTKIMUSTYYPIN_MAARITTELY", "Tutkimustyypin määrittely");
DEFINE("ONKO_TUTKIMUS", "Onko tutkimus");
DEFINE("KUVAA_MUU_ASETELMA", "Kuvaa muu tutkimusasetelma:");
DEFINE("TUTKIMUSAIN_MAARA", "Tutkimusaineistojen määrä");
DEFINE("TUTKIMUSAIN_MAARA_KUV", "Tutkimusaineistolla tarkoitetaan käyttölupaan liittyvää yhtä tai useampaa aineistoa. Jos käyttölupahakemus koskee useampaa aineistoa, lomakkeen lopussa painikkeella \"Lisää uusi aineisto\" saa lisättyä uusia aineistonmäärityslomakkeita. Tässä kehitysversiossa ei kuitenkaan voi määritellä kuin yhden aineiston.");
DEFINE("TUTKIMUSAIN_MAARA_LKM", "Kuinka montaa tutkimusainestoa käyttölupahakemus koskee?");
DEFINE("YHTA", "Yhtä");
DEFINE("USEAMPAA", "Useampaa");
DEFINE("KOHDEJ_KUVAILU", "Kohdejoukon yksiköt");
DEFINE("KOSKEEKO_HENKILOITA_K", "Ovatko kohdejoukkona henkilöt?");
DEFINE("MISTA_KOHDEJ_KOOSTUU", "Mistä yksiköistä kohdejoukko koostuu?");
DEFINE("TJAV_KUVAILU", "Tapauksien ja verrokkien kuvailu");
DEFINE("KOSKEEKO_HENKILOITA_TJAV", "Ovatko tapauksina ja verrokkeina henkilöt?");
DEFINE("MISTA_TJAV_KOOSTUU", "Mistä yksiköistä tapaukset ja verrokit koostuvat?");
DEFINE("ERITTELE_OSAR_KOKO", "Erittele kunkin osaryhmän koko");
DEFINE("VHENK_KAYTTO", "Viitehenkilöiden käyttö");
DEFINE("KAYTETAANKO_VIITEHENKILOITA", "Käytetäänkö tutkimuksessa viitehenkilöitä?");
DEFINE("VIITEHENKILO_KUVAUS", "Viitehenkilö on kohdejoukkoon kuulumaton muu henkilö, jonka tietoja käytetään tutkimuksessa. Esimerkki: kohdejoukko muodostuu lapsista ja tutkimuksessa käytetään lisäksi äidin ja isän tietoja; tässä tapauksessa viitehenkilöitä ovat äiti ja isä.");
DEFINE("AINEISTON_KOKO", "Tutkimusaineiston koko");
DEFINE("ARVIOI_AINEISTON_KOKO", "Arvioi tutkimusaineiston koko (henkilöiden tai muiden yksiköiden lkm)");
DEFINE("ARVIOI_AINEISTON_KOKO_KUV", "Erittele kunkin osaryhmän koko");
DEFINE("MUUN_AIN_KAYTTO", "Muun aineiston käyttö");
DEFINE("MUUN_AIN_KAYTTO_KUV", "Muulla aineistolla tarkoitetaan aikaisemmin kerättyä tai samanaikaisesti muualta kerättävää aineistoa, kuten aikaisempi kliinisen tutkimuksen aineisto tai haastatteluaineisto.");
DEFINE("TUNNIST_KOHDJ_AIEMMIN_KER_AIN", "Tunnistetaanko kohdejoukko aikaisemmin kerätystä aineistosta?");
DEFINE("TUNNIST_TAP_AIEMMIN_KER_AIN", "Tunnistetaanko tapaukset aikaisemmin kerätystä aineistosta?");
DEFINE("TUNNIST_VERR_AIEMMIN_KER_AIN", "Tunnistetaanko verrokit aikaisemmin kerätystä aineistosta?");
DEFINE("TUNNIST_VIITE_AIEMMIN_KER_AIN", "Tunnistetaanko viitehenkilöt aikaisemmin kerätystä aineistosta?");
DEFINE("KUVAA_MUODOSTAMINEN", "Kuvaa kohdejoukon/viitehenkilöiden/tapausten ja verrokkien muodostaminen");
DEFINE("FYYS_LUOV", "Fyysinen luovutus (esim. näytteet)");

DEFINE("TUTK_KOHD_MAAR", "Tutkimuksen kohdejoukon määrittely");
DEFINE("TUTK_KOHD_JA_VIITE_MAAR", "Tutkimuksen kohdejoukon ja viitehenkilöiden määrittely");
DEFINE("TUTK_TAP_VER_MAAR", "Tutkimuksen tapauksien ja verrokkien määrittely");
DEFINE("TUTK_TAP_VER_VIITE_MAAR", "Tutkimuksen tapauksien ja verrokkien ja viitehenkilöiden määrittely");

DEFINE("TUTK", "Tutkimuksen ");
DEFINE("TUTK_K1", "Kohdejoukon ");
DEFINE("TUTK_TAP3", "Tapauksen ");
DEFINE("TUTK_TAP2", "tapauksen ");
DEFINE("TUTK_K2", "kohdejoukon ");
DEFINE("TUTK_TAP", "tapauksen ");
DEFINE("TUTK_TV1", "Tapauksien ja verrokkien ");
DEFINE("TUTK_VERR", "verrokkien");
DEFINE("TUTK_VERR2", "Verrokkien");
DEFINE("TUTK_TV2", "tapauksien ja verrokkien ");
DEFINE("TUTK_T2", "Tapausten ");
DEFINE("TUTK_V1", "ja viitehenkilöt ");
DEFINE("TUTK_V2", "ja viitehenkilöiden ");
DEFINE("TUTK_V3", "viitehenkiloiden");
DEFINE("TUTK_V4", "Viitehenkiloiden");
DEFINE("TUTK_V5", "Viitehenkilöiden");
DEFINE("TUTK_MAAR", "määrittely");
DEFINE("AIK_SAM_KERATTAVA_KUV", "Aikaisemman tai samanaikaisesti kerättävän aineiston kuvailu");
DEFINE("AIKAISEMPI_AIN_ON", "Aikaisempi aineisto on");
DEFINE("KUVAA", "Kuvaa ");
DEFINE("KOHDEJOUKKO2", "kohdejoukko ");
DEFINE("T_JA_V2", "tapaukset ja verrokit ");
DEFINE("KUVAA_MUOD", "muodostaminen");
DEFINE("KUVAA_TARKEMMIN1", " tarkemmin ja anna lisätietoja sen alkuperästä ja muodostamistavasta");
DEFINE("KUVAA_TARKEMMIN2", " tarkemmin ja anna lisätietoja niiden alkuperästä ja muodostamistavasta");

DEFINE("REK_MAAR1", "Rekistereistä määriteltävä");
DEFINE("REK_MAAR2", "Rekistereistä määriteltävät");
DEFINE("REK_MAAR_KUV", "Selvitä etukäteen rekisterinpitäjiltä poimittavien muuttujien saatavuus eri ajankohtina.");
DEFINE("POIMINTA_MUOD", "poiminta/muodostaminen rekistereistä");
DEFINE("REK_TILASTOAIN", "Rekisteri/tilastoaineisto");
DEFINE("REK_MAAR_VIITE", "Rekistereistä määriteltävät viitehenkilöt");
DEFINE("TAPAUKSET", "tapaukset");

DEFINE("VALITSE_REKISTERI", "Valitse rekisteri tai tilastoaineisto");
DEFINE("LUETTELE_MUUTTUJAT", "Luettele tai poimi muodostamiseen tarvittavat muuttujat");
DEFINE("MAARITTELE_POIMINTAAJANKOHDAT", "Määrittele poiminta-ajankohdat tai -jaksot");
DEFINE("POIMINTAAJANKOHDAT", "Poiminta-ajankohdat tai -jaksot");

DEFINE("VALITSE_VIR_INFO", "Valinnan voi aloittaa myös rekistereistä, jolloin viranomainen tulee automaattisesti.");
DEFINE("VALITSE_VIR_KOHD_MUUTT", "Valitse viranomainen, jonka rekisteri- tai tilastoaineistosta kohdejoukko muodostetaan");
DEFINE("KUVAA_JOUKON_MUODOSTUS_OTSIKKO", "Kohdejoukon muodostaminen edellä määriteltyjen tietojen avulla");
DEFINE("KUVAA_JOUKON_MUODOSTUS", "Kuvaile miten kohdejoukko poimitaan/muodostetaan");
DEFINE("KUKA_MUODOSTAA_JOUKON", "Kuka poimii kohdejoukon");

DEFINE("VALITSE_VIR_TAPAUS_MUUTT", "Valitse viranomainen, jonka rekisteri- tai tilastoaineistosta tapaukset muodostetaan");
DEFINE("KUVAA_TAPAUS_POIM_OTSIKKO", "Tapausten muodostaminen edellä määriteltyjen tietojen avulla");
DEFINE("KUVAA_TAPAUS_POIM", "Kuvaile miten tapaukset poimitaan/muodostetaan");
DEFINE("KUKA_MUODOSTAA_TAPAUS", "Kuka poimii tapaukset");

DEFINE("VALITSE_VIRANOMAINEN_VERROKKI", "Valitse viranomainen, jonka rekisteri- tai tilastoaineistosta verrokit muodostetaan");
DEFINE("KUVAA_VERROKKIEN_MUODOSTUS_OTSIKKO", "Verrokkien muodostaminen edellä määriteltyjen tietojen avulla");
DEFINE("KUVAA_VERROKKIEN_MUODOSTUS", "Kuvaile miten verrokit poimitaan/muodostetaan");
DEFINE("KUKA_MUODOSTAA_VERROKIT", "Kuka poimii verrokit");

DEFINE("VALITSE_VIRANOMAINEN_VIITE", "Valitse viranomainen, jonka rekisteri- tai tilastoaineistosta viitehenkilöt muodostetaan");
DEFINE("KUVAA_VIITEHENK_MUODOSTUS_OTSIKKO", "Viitehenkilöiden muodostaminen edellä määriteltyjen tietojen avulla");
DEFINE("KUVAA_VIITEHENK_MUODOSTUS", "Kuvaile miten viitehenkilöt poimitaan/muodostetaan");
DEFINE("KUKA_MUODOSTAA_VIITEHENKILOT", "Kuka poimii viitehenkilöt");

DEFINE("VALITSE_LUPVIR", "Valitse viranomainen");
DEFINE("VAL_REK_TAI_MUU", "Valitse rekisteri tai muu aineisto");
DEFINE("TIED_KER_BIO", "Tiedot kerätään seuraavista biopankeista");
DEFINE("TARK_KUV_NAYT", "Tarkka kuvaus näytteistä ja/tai tiedoista");
DEFINE("TARK_KUV_NAYT_INFO", "THL-biopankista tietoja pyydettäessä vaaditaan tarkka muuttujien listaus erillisenä liitteenä");
DEFINE("BIOPANK_KAS", "Biopankkinäytteiden käsittely");
DEFINE("VAAT_NAYT_KAS", "Vaatimukset näytteiden käsittelylle");
DEFINE("VAAT_NAYT_KAS_2", "Vaatimukset näytteiden käsittelylle (täytä tarvittaessa)");
DEFINE("ESJK", "Erityisvaatimukset säilytykselle ja kuljetukselle (täytä tarvittaessa)");
DEFINE("SELV_VAST_NAYT", "Selvitys vastaanotettavien näytteiden ja tietojen käsittelystä");
DEFINE("KUV_YL_PER_BIO", "Kuvaa yleiset periaatteet kuinka tutkimusryhmä suojaa tiedot ulkopuolisilta sekä miten niiden käyttöoikeudet on rajattu ");
DEFINE("TIETO_KAS_AIN_MAN", "Tieto siitä, käsitelläänkö aineistoa manuaalisesti, ja jos käsitellään, miten manuaalinen aineisto suojata");
DEFINE("LIS_TARV_ANT", "Lisätietoja tarvittaessa antaa");
DEFINE("ENNEN_LUOV_TUTK", "Ennen näytteiden luovutusta tutkimukseen, on patologin tarkistettava kudosnäytteiden laatu ja varmistuttava siitä, että näytettä on tarpeeksi käytettäväksi biopankkitutkimukseen diagnostiikkaa vaarantamatta. Kuinka tämä tehdään (valitse yksi seuraavista vaihtoehdoista)");
DEFINE("ENNEN_LUOV_TUTK_INFO", "Helsingin biopankki ei tarjoa patologipalveluja");
DEFINE("KAYT_BIO_NIM_PAT", "Käytetään biopankin nimeämää patologia");
DEFINE("YHT_SAIR_VAST_PAT", "Tehdään yhteistutkimuksena sairaustyypistä vastaavan patologin kanssa");
DEFINE("TUL_PAL_BIO", "Tulosten palauttaminen biopankkiin");
DEFINE("KUV_MIL_TUL_BIO", "Kuvaus millaisia tuloksia palautuu biopankkiin (esim. laboratorio mittauksia, omiikka data, analyysitietoja)");
DEFINE("TUTK_EI_KUDOS", "Tutkimukseen ei pyydetä kudosnäytteitä");

DEFINE("KJLLE1", "Kohdejoukolle ");
DEFINE("KJLLE2", "kohdejoukolle ");
DEFINE("TLLE1", "Tapauksille ja verrokeille ");
DEFINE("TLLE2", "tapauksille ");
DEFINE("VLLE2", "verrokeille ");
DEFINE("VHLLE2", "ja viitehenkilöille ");
DEFINE("POIM_MUUT", "poimittavat muuttujat");
DEFINE("POIM_MUUT2", "Poimittavat muuttujat");

DEFINE("LUETT_POIM_MUUTT", "Poimittavat muuttujat tai tiedot");
DEFINE("POIM_MUUTTUJA_INFO", "Jos poimittavia muuttujia ei ole mahdollista listata, kerrotaan karkeammalla tasolla, mitä tietoja halutaan. Jos halutaan antaa tarkka muuttujalistaus, sen voi antaa myös liitteellä Muuttujalistaus.");

DEFINE("MAARITTELE_KOHDEJOUKON_MUUTTUJAT", "Määrittele kohdejoukolle poimittavat tiedot ja/tai näytteet");
DEFINE("KOHDEJOUKKO_POIMITAAN", "Valitse viranomainen, jonka rekisteristä tai tilastoaineistosta kohdejoukon muuttujat poimitaan");

DEFINE("MAARITTELE_TAPAUKSEN_MUUTTUJAT", "Määrittele tapauksille poimittavat tiedot ja/tai näytteet");
DEFINE("TAPAUS_POIMITAAN", "Valitse viranomainen, jonka rekisteristä tai tilastoaineistosta tapauksien muuttujat poimitaan");

DEFINE("POIMITAANKO_SAMAT_VERROKKI", "Poimitaanko verrokeille samat tiedot ja/tai näytteet kuin tapauksille?");
DEFINE("MAARITTELE_VERROKIT_MUUTTUJAT", "Määrittele verrokeille poimittavat tiedot ja/tai näytteet");
DEFINE("VERROKIT_POIMITAAN", "Valitse viranomainen, jonka rekisteristä tai tilastoaineistosta verrokkien muuttujat poimitaan");

DEFINE("POIMITAANKO_SAMAT_VIITE", "Poimitaanko viitehenkilöille samat tiedot ja/tai näytteet kuin kohdejoukolle/tapauksille");
DEFINE("MAARITTELE_VIITEHENKILO_MUUTTUJAT", "Määrittele viitehenkilöille poimittavat tiedot ja/tai näytteet");
DEFINE("VIITEHENKILOT_POIMITAAN", "Valitse viranomainen, jonka rekisteristä tai tilastoaineistosta viitehenkilöiden muuttujat poimitaan");
DEFINE("LISATIET_AIN", "Lisätietoa aineistosta:");
DEFINE("POIMI_MUUT", "Poimi muuttujat");
DEFINE("MUUTT_LISATIED", "Muuttujan lisätiedot");
DEFINE("MUUTT_KUVAUS", "Muuttujan kuvaus");
DEFINE("LISATIETOJA_AIN", "Lisätietoja aineistosta");
DEFINE("MITTAYKSIKKO", "Mittayksikkö");
DEFINE("PAT_YHT", "Patologin yhteystiedot");
DEFINE("AIN_SUOJ", "Aineiston suojaus tutkimuksen aikana"); 
DEFINE("TUTK_SUOR_TUNN", "Tutkittavan suoran tunnistamisen mahdollistavat tunnistetiedot korvataan tutkimusnumeroilla (koodeilla) ja koodiavain hävitetään heti aineiston muodostamisen jälkeen");  
DEFINE("TUTK_SUOR_TUNN2", "Tutkittavan suoran tunnistamisen mahdollistavat tunnistetiedot korvataan tutkimusnumeroilla (koodeilla) ja koodiavainta säilytetään erillään muusta aineistosta");
DEFINE("TUTK_SUOR_TUNN3", "Tutkittavan suoran tunnistamisen mahdollistavat tiedot säilytetään aineistossa tutkimuksen ajan");
DEFINE("KOOD_HAV_VAST", "Koodiavaimen hävittämisestä vastaa:");
DEFINE("KOOD_SAIL", "Koodiavaimen säilyttäjä:"); 
DEFINE("TUTK_HAV_SAIL", "Tutkimustietojen hävittäminen tai säilyttäminen tutkimuksen päättämisen jälkeen"); 
DEFINE("TIED_HAV_KOK", "Tutkimustiedot hävitetään kokonaisuudessaan");  
DEFINE("TIED_ANONY", "Tutkimusaineisto anonymisoidaan eli muutetaan pysyvästi sellaiseen muotoon, etteivät tiedon kohteet ole siitä edes välillisesti tunnistettavissa");  
DEFINE("AIN_REK_OIK", "Tutkimusaineiston rekisterinpitäjällä on arkistolain mukainen arkistointioikeus ja se arkistoi henkilörekisterin arkistonmuodostussuunnitelmansa mukaisesti");  
DEFINE("REKPIT_KANS", "Tutkimusaineiston rekisterinpitäjä hakee Kansallisarkistolta luvan siirtää henkilörekisteri korkeakoulun tai tutkimustyötä lakisääteisenä tehtävänä suorittavan laitoksen tai viranomaisen arkistoon"); 
DEFINE("KUV_HAV", "Kuvatkaa hävittämistapa ja kuka vastaa aineiston hävittämisestä");
DEFINE("KUV_ANO", "Kuvatkaa miten anonymisointi toteutetaan ja kuka siitä vastaa"); 
DEFINE("MIHIN_AIN_SIIR", "Mihin aineisto siirretään arkistoitavaksi?");  
DEFINE("AIN_ARK_SYY", "Mistä syystä aineisto halutaan arkistoida?"); 
DEFINE("KUV_TOIM", "Kuvaile luovutettavalle aineistolle toivottu toimitustapa"); 
 
// Hakemuksen tyypit
DEFINE("HAKEMUS_TYYPPI", "Hakemuksen tyyppi");
DEFINE("uus_hak", "Uusi hakemus");
DEFINE("tayd_hak", "Täydennetty hakemus");
DEFINE("muutos_hak", "Muutoshakemus");

// Muutoshakemuksen tyypit
DEFINE("MUUTOS_TYYPPI", "Muutoshakemuksen tyyppi");
DEFINE("tutkryhm_tayd", "Tutkimusryhmän täydentäminen");
DEFINE("luvkest_jatk", "Luvan keston jatkaminen");
DEFINE("tutkain_laaj", "Tutkimusaineiston laajentaminen");
DEFINE("tutkain_servuo_laaj", "Tutkimusaineiston seurantavuosien jatkaminen");
DEFINE("muutos_muu", "Muu syy, mikä?");

// Tutkijan käyttöliittymä > Tutkimusryhmä
DEFINE("TAYTA_TIEDOT", "Tarkista, täydennä ja tallenna ensin käyttäjätiedot");
DEFINE("HAKIJAT", "Tutkimusryhmä");
DEFINE("HAKIJAN_TIEDOT", "Hakijan tiedot");
DEFINE("TALLENNA_HAKIJA", "Tallenna hakijan tiedot");
DEFINE("TUTKIMUSRYHMA_1OTSIKKO", "Tiedot tutkimuksen vastuullisesta johtajasta, yhteyshenkilöstä sekä  osallistuvista tutkijoista ja muusta henkilökunnasta");
DEFINE("TUTKIMUSRYHMA_1OTSIKKO_INFO", "Jos haetaan pelkästään biopankkiaineistojen käyttölupaa, tiedot osallistuvista tutkijoista ja muusta henkilökunnasta voidaan antaa myös erillisellä liitteellä. Tiedot vastuullisesta johtajasta ja yhteyshenkilöstä on kuitenkin aina annettava tässä");
DEFINE("INFO_NIMI", "Nimeä klikkaamalla voit tarkistaa ja täydentää tietoja.");
DEFINE("INFO_SALASSAPITOSITOUMUS", "Henkilötietojen käsittelyoikeus edellyttää salassapitositoumuksen antamista ennen hakemuksen lähettämistä. Salassapitositoumus tarvitaan ainoastaan niiltä tutkimusryhmän jäseniltä, jotka käsittelevät henkilötietoja. Biopankkiaineistoista ei anneta salassapi-tositoumuksia, vaan lupapää-töksen jälkeen (ennen  erillistä luovutuspäätöstä) tutkimukseen osallistuvien henkilöiden täytyy hyväksyä biopankin luovutusehdot.");
DEFINE("INFO_JASEN", "Merkkien selitys: &#x2718; Henkilö ei ole kirjautunut tälle hakemukselle, &#x2714; Henkilö on kirjautunut ja tarkistanut omat tietonsa tällä hakemuksella.");
DEFINE("INFO_TARK_JA_TAYD", "Tarkista ja täydennä tiedot.");
DEFINE("INFO_TARK_TAYD_JA_TALL", "Tarkista, täydennä ja tallenna tiedot");
DEFINE("INFO_LISAA_JASENET", "Lisää tutkimusryhmän jäsenet, anna tarvittavat tiedot ja kutsu täyttämään hakemusta");
DEFINE("TUTKIMUSRYHMA_2OTSIKKO", "Tutkimusryhmän muut jäsenet");
DEFINE("INFO_2OTSIKKO", "Tutkimusryhmäin muiden jäsenten on kirjauduttava hakemukselle ja tarkistettava omat tietonsa ennen hakemuksen lähettämistä.");
DEFINE("UUSI_HENKILO", "Lisää uusi henkilö");
DEFINE("ORGANISAATIO", "Organisaatio");
DEFINE("OPPIARVO", "Oppiarvo");
DEFINE("VALITSE_ROOLI", "Valitse rooli");
DEFINE("rooli_hak_yht", "Hakemuksen yhteyshenkilö");
DEFINE("rooli_muu", "Muu");
DEFINE("rooli_op_ohj", "Opinnäytetyön ohjaaja");
DEFINE("rooli_op_tek", "Opinnäytetyön tekijä");
DEFINE("rooli_rek_yht", "Tutkimusrekisterin yhteyshenkilö");
DEFINE("rooli_tutkija", "Tutkija");
DEFINE("rooli_vast", "Tutkimuksen vastuullinen johtaja");
DEFINE("rooli_vasta", "Tutkimuksesta vastaava henkilö");
DEFINE("rooli_tutk_yht", "Tutkimuksen yhteyshenkilö");

DEFINE("rooli_tutk_yht_info", "Pöytäkirjanote lähetetään yhteyshenkilölle");
DEFINE("rooli_vasta_info", "Laki lääketieteellisestä tutkimuksesta 5 §");
DEFINE("HAETAAN_LUPAA", "Tälle henkilölle haetaan rekisteritietojen käsittelyoikeutta ja hänen on annettava salassapitositoumus");
DEFINE("NIMI", "Nimi");
DEFINE("OSOITE", "Osoite");
DEFINE("ROOLI", "Rooli");
DEFINE("SALASSAPITOSITOUMUS", "Salassapitositoumus");
DEFINE("ANNA_SALASSAPITOSITOUMUS", "Anna salassapitositoumus");
DEFINE("JASEN", "Tiedot tarkistettu");
DEFINE("POISTA_HENKILO", "Poista hakija");
DEFINE("POIST_HAKIJ_VARM", "Haluatko varmasti poistaa hakijan?");
DEFINE("LISAA_JA_KUTSU", "Lisää ja kutsu henkilö");
DEFINE("SIIRRY_HAKIJALUETTELOON", "Tutkimusryhmän jäsenluettelo");
DEFINE("PUUTTUU", "Puuttuu");
DEFINE("ANNETTU", "Annettu");
DEFINE("INFO_JOKAISELLA", "Jokaisella tutkimusryhmällä täytyy olla nimettynä seuraavat roolit: Tutkimuksen vastuullinen johtaja ja hakemuksen yhteyshenkilö. Samalla henkilöllä voi olla useampia rooleja.");
DEFINE("INFO_VASTUULLINEN", "Kun tutkimuksessa käytetään henkilörekisterin tietoja, on sille nimettävä vastuullinen johtaja tai siitä vastaava ryhmä (Henkilötietolaki 523/1999 14 §). Ainoastaan tutkimuksen vastuullinen johtaja voi lähettää hakemuksen rekisteriviranomaiselle.");
DEFINE("INFO_HAK_YHTEYSHENKILO", "Hakemuksen yhteyshenkilö on henkilö, johon viranomaiset ovat yhteydessä hakemusta koskevissa asioissa.");
DEFINE("UUSI_HAKIJA", "Uusi hakija");
DEFINE("POISTETUT_HAKIJAT", "Poistetut hakijat");
DEFINE("SITOUDUN", "Sitoudun");
DEFINE("SITOUTUMINEN1", "pitämään salassa tiedot, joiden käsittelyoikeuden olen saanut käyttöluvalla ja jotka ovat lain mukaan salassa pidettäviä (julkisuuslaki 621/1999, henkilötietolaki 523/1999, tilastolaki 361/2013, laki väestötietojärjestelmästä ja Väestörekisterikeskuksen varmennepalveluista 661/2009, muu laki tai viranomaisen erikseen antama määräys).");
DEFINE("SITOUTUMINEN2", "olemaan ilmaisematta sivullisille käyttöluvan perusteella käsittelemiäni tietoja.");
DEFINE("SITOUTUMINEN3", "siihen, että en käytä käsittelemiäni tietoja omaksi tai toisen hyödyksi/vahingoksi.");
DEFINE("SITOUTUMINEN4", "jatkamaan salassapitovelvollisuutta käyttöluvan voimassaolon päätymisen jälkeen ja tiedän, että salassapidon rikkomisesta on säädetty rangaistus.");
DEFINE("HYVAKSYN_EHDOT", "Hyväksyn salassapitositoumuksen ehdot");
DEFINE("HAKIJA_EI_OHJ_OP", "Hakija ei voi olla samaan aikaan opinnäytetyön ohjaaja ja tekijä");
DEFINE("TARKASTELE_TUTKRYHMAA", "Tarkastele tutkimusryhmää");
DEFINE("SITOUMUS_HYVAKSYTTY", "Sitoumus hyväksytty");
DEFINE("LAHETA_KUTSU", "Lähetä sähköpostikutsu");
DEFINE("LAH_KUTS_VARM", "Haluatko varmasti kutsua uuden hakijan tutkimusryhmään?");
DEFINE("KUTSU_LAHETETTY", "Sähköpostikutsu lähetetty");
DEFINE("KUTSU_EPAONNISTUI", "Sähköpostikutsun lähettäminen epäonnistui: Täytä pakolliset tiedot");
DEFINE("VIRH_EMAIL", "Virheellinen sähköpostiosoite");
DEFINE("HAKIJA_POISTETTU", "Hakija poistettiin tutkimusryhmästä");
DEFINE("HAKIJAN_POISTO_EPAONN", "Hakijan poistaminen tutkimusryhmästä epäonnistui");
DEFINE("TUTKRYHMA_JASENET", "Tutkimusryhmän jäsenet");
DEFINE("MAA", "Maa");

// Tutkijan käyttöliittymä > Toimitus ja elinkaari
DEFINE("TOIMITUS_JA_ELINKAARI", "Toimitus ja elinkaari");
// Tutkijan käyttöliittymä > Viranomaiskohtaiset
DEFINE("VIRANOMAISKOHTAISET", "Viranomaiskohtaiset");
DEFINE("TUTK_SID", "Tutkijan sidonnaisuudet viimeisen kolmen (3) vuoden ajalta");
DEFINE("TUTK_RAH", "Tutkimuksen rahoittajatahot");
DEFINE("KELA_LISATIEDOT", "KELA lisätiedot");
DEFINE("LISAA_VIR_KOHT_TIED", "Lisää viranomaiskohtaiset tiedot");
DEFINE("UUSI_TIETO", "Uusi tieto");
DEFINE("POISTETUT_LISATIEDOT", "Uusimmasta versiosta poistetut lisätiedot");

// Tutkijan käyttöliittymä > Erääntyvät käyttöluvat
DEFINE("ERAANTYVAT_INFO", "Alla näet tutkimukset, joiden käyttölupa on päättymässä.");
DEFINE("EI_ERAANTYVIA", "Ei erääntyviä käyttölupia.");
DEFINE("PAATTYVAT_KAYTTTOLUVAT", "Päättyvät käyttöluvat");
DEFINE("ER_KAYT_LUP_MAARA", "Erääntyvien käyttölupien määrä");
DEFINE("ER_LUVAT", "Erääntyvät luvat");

// Tutkijan käyttöliittymä > Rekisteriseloste
DEFINE("REKISTERISELOSTE", "Rekisteriseloste");

// Tutkijan käyttöliittymä > Liitteet
DEFINE("LIITTEET", "Liitteet");
DEFINE("LIITTEET_INFO", "[teksti puuttuu]");
DEFINE("PAKOLLISET_LIITTEET", "Pakolliset puuttuvat liitteet");
DEFINE("MUUT_LIITTEET", "Muut liitteet");
DEFINE("VALITSE_LIITTEEN_TYYPPI", "Valitse liitteen tyyppi");
DEFINE("VALITSE_LIITTEEN_TYYPPI_INFO", "Samantyyppisiä liitteitä voidaan tarvittaessa liittää useampia.");
DEFINE("LISAA_LIITE", "Lisää liite");
DEFINE("LISAA_LIITE_INFO", "Nimeä liitetiedostot sisältöä kuvaavalla tavalla ja liitä nimeen päivämäärä seuraavasti: ’Tiedoston nimi_vvvvkkpv’.");
DEFINE("PAIVITA_LIITE", "Päivitä liite");
DEFINE("LISATYT_LIITTEET", "Lisätyt liitteet");
DEFINE("TYYPPI", "Tyyppi");
DEFINE("LIITE", "Liite");
DEFINE("POISTA_LIITE", "Poista liite");
DEFINE("LIITTEEN_LISAAJA", "Liitteen lisääjä");
DEFINE("PVM", "Pvm");
DEFINE("TIEDOSTO_PAIVITETTY", "Uusi tiedosto");
DEFINE("LIITTEET_AIEMMISTA", "Liitteet aiemmista hakemuksen versioista");
DEFINE("LIITA", "Liitä");
DEFINE("LIITTEEN_TIEDOT", "Liitteen tiedot");
DEFINE("VALITSE_TYYPPI", "Valitse tyyppi");
DEFINE("SALLITUT_FORMAATIT", "Sallitut tiedostotyypit");
DEFINE("ESIKATSELE_JA_LAHETA", "Esikatsele ja lähetä");
DEFINE("LAHETA_HAKEMUS", "Lähetä hakemus");
DEFINE("REK_TIET_KAYT_TARK", "Rekisteritietojen käyttötarkoitus");
DEFINE("KAYT_TARK_OP", "Käyttötarkoitus opinnäytetyö");
DEFINE("TUTK_RYHMA_SELOSTE", "Henkilöt, joilla on oikeus käsitellä rekisteriaineistoja");
DEFINE("VIRANOMAINEN", "Viranomainen");
DEFINE("REKISTERI", "Rekisteri");
DEFINE("TIEDOT_AJALTA", "Tiedot ajalta");
DEFINE("KOHD_MUUTTUJAT", "Kohdejoukon muuttujat");
DEFINE("TUTK_AIN", "Tutkimusaineiston");
DEFINE("VERROKKI", "Verrokki");
DEFINE("TUTK_KK", "Tutkimuksen kokonaiskesto");
DEFINE("TUNN_AIK_AIN", "Aiempi aineisto");
DEFINE("TUN_PER", "Suorien tunnisteiden käsittely tutkimuksen aikana");
DEFINE("KOD_AV_VAST", "Koodiavaimen hävittämisestä vastaa");
DEFINE("KOD_AV_SAIL", "Koodiavaimen säilyttäjä");
DEFINE("VERKKOLASKUOSOITE_LYH", "Verkkolaskuosoite");
DEFINE("TOIM_PAATT", "Aineistolle suoritettavat toimenpiteet tutkimuksen päättyessä");
DEFINE("HAV_TAPA", "Hävittämistapa");
DEFINE("AIN_HAV_VAST", "Hävittämisestä vastaa");
DEFINE("AINE_SIIR_ARK", "Siirrettävän aineiston arkistointikohde");
DEFINE("SYY_SIIRT_AN", "Syy siirto-oikeuden anomiselle");
DEFINE("TOIMINNOT", "Toiminnot");
DEFINE("VMVML", "Valitse mille viranomaisille muutoshakemus/täydennetty hakemus lähetetään");
DEFINE("LAHETA_MUUTOSHAKEMUS", "Lähetä muutoshakemus");
DEFINE("HAK_ESIKATSELU", "Esikatselu");
DEFINE("HAK_ESIK_INFO", "Hakemuksen voi tallentaa PDF-muodossa klikkaamalla alla olevaa linkkiä.");
DEFINE("HAK_LAHETTAMINEN", "Hakemuksen lähettäminen");
DEFINE("HAK_LAHETTAMINEN_INFO", "Hakemuksen lähettäminen viranomaisille edellyttää, että kaikki pakolliset tiedot on täytetty.");
DEFINE("HAK_POISTAMINEN", "Hakemuksen poistaminen");
DEFINE("HAK_POISTO_INFO", "Hakemuksen poistaminen tapahtuu alla olevan painikkeen kautta.");
DEFINE("METATIEDOT", "Metatiedot");
DEFINE("LIITE_TALLENNETTU", "Liitetiedosto tallennettu");
DEFINE("LIITE_POISTETTU", "Liitetiedosto poistettu");
DEFINE("LIITE_POIST_FAIL", "Liitetiedoston poisto epäonnistui");

// Tutkijan käyttöliittymä > Hakemus
DEFINE("TEE_MUUTOSHAKEMUS", "Tee muutoshakemus");
DEFINE("HAK_TALLENNUS_EPAONNISTUI", "Hakemuksen tallennus epäonnistui.");
DEFINE("HAK_TIEDOT", "Hakemuksen tiedot");

// Tutkijan käyttöliittymä > Organisaatiotiedot
DEFINE("VASTUUORGANISAATIO", "Vastuuorganisaatio");
DEFINE("ORGANISAATIOTIEDOT", "Organisaatiotiedot");
DEFINE('VASTUUORG_INFO', 'Tutkimuksesta vastuussa oleva organisaatio, joka toimii myös tutkimusrekisterin rekisterinpitäjänä. Jos vastuuullisena hakijana on itsenäinen tutkija, vastaukseksi kirjoitetaan "Ei organisaatiota"');
DEFINE("ORG_VIR_ED", "Organisaation virallinen edustaja");
DEFINE("VIR_ED_SAHK", "Virallisen edustajan sähköpostiosoite");
DEFINE("TUTK_OS_ORG", "Tutkimukseen osallistuvat organisaatiot");
DEFINE("TUTK_OS_ORG_INFO", "Tutkimukseen osallistuvat organisaatiot, niiden roolit ja vastuunjako. Biopankkiaineiston käyttölupaa haettaessa kaikkien tutkimukseen osallistuvien tutkijoiden ja muun henkilökunnan tulee kuulua johonkin nimettyyn osallistuvaan organisaatioon.");
DEFINE("REKISTERINPITAJA", "Rekisterinpitäjä");
DEFINE("REKISTERINPITAJAT", "Rekisterinpitäjät");
DEFINE("REKISTERINPITAJA_INFO", "Onko organisaatio yksin tai yhdessä muiden kanssa tutkimusrekisterin rekisterinpitäjä? Vähintään yksi organisaatio on merkittävä rekisterinpitäjäksi.");
DEFINE("ROOLI_JA_VAST", "Rooli ja mahdollinen vastuualue");
DEFINE("ROOLI_JA_VAST_INFO", "Organisaation rooli ja vastuu tutkimusaineiston käsittelyn suhteen");
DEFINE("BIO_LUOV_ALL_ORG", "Biopankkiaineistojen luovutussopimuksen (MTA) allekirjoittava organisaatio");
DEFINE("Y_TUNNUS", "Y-tunnus");
DEFINE("LISAA_ORGANISAATIO", "Lisää organisaatio");
DEFINE("POISTA_ORGANISAATIO", "Poista organisaatio");
DEFINE("ONKO_MTA_AK", "Onko organisaatio biopankkisopimuksen (MTA) allekirjoittaja?");
DEFINE("ONKO_MTA_AK_INFO", "Kysymykseen täytyy vastata, jos haetaan biopankkiaineiston  käyttölupaa");

// Tutkijan käyttöliittymä > Rekisteröidy
DEFINE("REKISTEROIDY", "Rekisteröidy sähköisen lupapalvelun käyttäjäksi");
DEFINE("REKISTEROIDY_MENU", "Rekisteröidy");
DEFINE("HENKILOTIEDOT", "Henkilötiedot");
DEFINE("SUKUNIMI", "Sukunimi");
DEFINE("ETUNIMI", "Etunimi");
DEFINE("SYNTYMAAIKA", "Syntymäaika");
DEFINE("ASIOINTIKIELI", "Asiointikieli");
DEFINE("PUHELIN", "Puhelin");
DEFINE("KAYTTAJATUNNUS", "Käyttäjätunnus");
DEFINE("KAYTTAJATUNNUS2", "Käyttäjätunnus ja salasana");
DEFINE("JARJESTELMAN2", "Järjestelmän käyttäjätunnuksena toimii sähköpostiosoite.");
DEFINE("SAHKOPOSTIOSOITE", "Sähköpostiosoite");
DEFINE("SALASANA", "Salasana");
DEFINE("SALASANA_UUDELLEEN", "Salasana uudelleen");
DEFINE("PAKOLLISIA2", "Sukunimi ja etunimi ovat pakollisia tietoja.");
DEFINE("LUO_SALASANA", "Luo salasana");
DEFINE("VAHVISTA_SALASANA", "Vahvista salasana");
DEFINE("EI_KELPO_SAHKOPOSTI", "Sähköpostiosoite on virheellinen.");
DEFINE("SALASANAT_EIVAT_TASMAA", "Salasanat eivät täsmää.");
DEFINE("EI_KELPO_NIMI", "Nimi on virheellinen.");
DEFINE("SALASANA_LIIAN_LYHYT", "Salasanassa on oltava vähintään 8 merkkiä");
DEFINE("SALASANA_NUMERO_PUUTTUU", "Salasanassa on oltava vähintään yksi numero.");
DEFINE("SALASANA_ISO_KIRJAIN_PUUTTUU", "Salasanassa on oltava vähintään yksi iso kirjain.");
DEFINE("SALASANA_PIENI_KIRJAIN_PUUTTUU", "Salasanassa on oltava vähintään yksi pieni kirjain.");
DEFINE("KIRJAUTUMINEN_EPAONNISTUI", "Kirjautuminen epäonnistui.");
DEFINE("KAYTTAJA_LUOTU", "Käyttäjätunnuksen vahvistuslinkki on lähetetty sähköpostiosoitteeseesi");
DEFINE("LUOMINEN_EPAONNISTUI", "Rekisteröinti epäonnistui.");
DEFINE("VARMENT_ONNISTUI", "Käyttäjän rekisteröinti vahvistettu.");
DEFINE("VARMENT_EPAONNISTUI", "Käyttäjän rekisteröinnin vahvistaminen epäonnistui.");
DEFINE("VARMENT_EPAONNISTUI_INFO", "[teksti puuttuu]");
DEFINE("ON_REKISTEROITY", "on lisätty lupapalvelun käyttäjäksi.");
DEFINE("TAHD_PAKOLLISIA", "Tähdellä (*) merkityt kohdat ovat pakollisia täytettäviä.");
DEFINE("SALASANA_INFO", "Salasanan tulee sisältää vähintään kahdeksan (8) merkkiä, ISO kirjain, pieni kirjain ja numero.");

// Tutkijan käyttöliittymä > Viestit
DEFINE("VIESTIT_INFO", "Valitse tutkimus ja pääset lukemaan siihen saapunutta viestiä.");
DEFINE("UUDET_VIESTIT", "Uudet viestit");
DEFINE("UUDET_VIESTIT2", "Uusia viestejä");
DEFINE("LUETUT_VIESTIT", "Luetut viestit");
DEFINE("LUETUT_VIESTIT2", "Luettuja viestejä");
DEFINE("EI_VIESTEJA", "Sinulle ei ole tullut viestejä.");
DEFINE("EI_VIESTEJA_HAKEMUS", "Hakemukselle ei ole tullut viestejä.");
DEFINE("TYHJA_VIESTI", "Tyhjää viestiä ei voi lähettää");
DEFINE("AIEMP_HAK_VI", "Aiempien hakemusten viestit");
DEFINE("VIESTIT_SMALL", "viestit");
DEFINE("TAYDASK", "Täydennysasiakirjat");
DEFINE("TAYDASK_TOIM", "Täydennysasiakirjat toimitettu");
DEFINE("VIESTI_LAH", "Viesti lähetetty");
DEFINE("VIESTI_LAH_FAIL", "Viestin lähetys epäonnistui");

// Tutkijan käyttöliittymä > LAUSUNNOT
DEFINE("LAUS_INFO_HAKIJALLE", "Tutkimukseen saapuneet viranomaisen antamat lausunnot");
DEFINE("EI_LAUSUNTOJA", "Hakemukselle ei ole saapunut lausuntoja.");

// Tutkijan käyttöliittymä > Päätökset
DEFINE("TUTK_LIITTYVAT_PAAT", "Tutkimukseen liittyvät päätökset");
DEFINE("PAATOS_PVM", "Päätöksen päivämäärä");
DEFINE("KAYTTOLUPA_PAATTYY", "Käyttölupa päättyy");
DEFINE("HAKEMUKSEN_VERSIO", "Hakemuksen versio");
DEFINE("PAATOKSEN_TILA", "Päätöksen tila");
DEFINE("KAS_AIKA_ARVIO", "Käsittelyaika-arvio");
DEFINE("PAATOS_TEHDAAN", "päätös tehdään");

// Tutkijan käyttöliittymä > Aineistotilaus
DEFINE("LAHETA_AINEISTOPYYNTO", "Lähetä aineistopyyntö");
DEFINE("LAH_AINP_VARM", "Haluatko varmasti tilata aineiston?");
DEFINE("LAHETA_AINEISTOPYYNTO_INFO1", "Kuvaile mahdollisimman tarkasti vaiheittain, kuinka tutkimusaineisto tulee muodostaa:");
DEFINE("LAHETA_AINEISTOPYYNTO_INFO2", "Valitse, kenelle aineistopyyntö lähetetään. Viranomaiset ovat antaneet myönteisen päätöksen seuraaviin hakemuksiin:");
DEFINE("LAHETA_AINEISTOPYYNTO_INFO3", "Aineisto luovutetaan käyttölupapäätöksessä määritetyllä tavalla. Aineiston tietojen poiminnasta peritään maksu käyttölupapäätöksen mukaisesti.");
DEFINE("LAHETETYT_AINEISTOPYYNNOT", "Lähetetyt aineistopyynnöt");
DEFINE("MYONNETYT_KAYTTOLUVAT", "Tutkimuksen hakemukset, joihin viranomainen on antanut myönteisen päätöksen:");
DEFINE("TILAAJA", "Tilaaja");
DEFINE("LAHETETTY_PVM", "Lähetetty pvm");
DEFINE("KASITTELY_TILA", "Käsittelyn tila");
DEFINE("AINEISTOPYYNTO_INFO", "Aineistopyynnön voi lähettää jos viranomaiset antavat myönteiset päätöksen hakemuksiin:");
DEFINE("AINEISTOPYYNTO_INFO2", "Aineistopyyntöä ei voi lähettää.");
DEFINE("TEE_REKLAMAATIO", "Tee reklamaatio");
DEFINE("PERUUTA_AINEISTOTILAUS", "Peruuta aineistotilaus");
DEFINE("AINT_PERU_LAHETETTY", "Aineistotilaus peruttu");
DEFINE("AINT_PERU_EPAONNISTUI", "Aineistotilauksen peruminen epäonnistui");
DEFINE("KUITTAA_AINEISTO", "Kuittaa aineisto");
DEFINE("EI_KASITTELIJAA", "Ei käsittelijää");
DEFINE("REKLAMAATIOTILAUS", "Reklamaatiotilaus");
DEFINE("REKLAMAATIOTILAUS_INFO", "[teksti puuttuu]");
DEFINE("PALAUTETUKSI", "Palautetuksi");
DEFINE("HAVITETYKSI", "Hävitetyksi");
DEFINE("ARKISTOIDUKSI", "Arkistoiduksi");
DEFINE("KUITTAA", "Kuittaa");
DEFINE("AIN_KUITATTU", "Aineisto kuitattu");
DEFINE("KUIT_EPAONNISTUI", "Aineiston kuittaaminen epäonnistui");
DEFINE("AINEISTONMUODOSTAJA", "Aineistonmuodostaja");
DEFINE("AINEISTOTILAUKSEN_PER", "Aineistotilauksen peruuttaminen");
DEFINE("AINEISTOTILAUKSEN_PER_INFO", "[teksti puuttuu]");
DEFINE("VALITSE_VAH_YKSI_HAK", "Valitse vähintään yksi tutkimuksen hakemus.");
DEFINE("VAL_AIN_KUIT", "Valitse kuinka aineisto kuitataan.");
DEFINE("REKLAMAATIO_VARMISTUS", "Haluatko varmasti lähettää reklamaatiotilauksen?");
DEFINE("REKL_LAH", "Reklamaatiotilaus lähetetty");
DEFINE("REKL_LAH_FAIL", "Reklamaatiotilauksen lähetys epäonnistui");
DEFINE("VAL_VIR_JAL", "Valitse viranomainen, jolle aineistopyyntö lähetetään");
DEFINE("EI_TIL_AIN", "Ei tilattavia aineistoja");

// Tutkijan käyttöliittymä > Käyttäjän liittäminen hakemukseen
DEFINE("LIITTAMINEN_ONNISTUI", "Käyttäjä on liitetty hakemukseen");
DEFINE("ON_LISATTY", "on onnistuneesti lisätty tutkimukseen");
DEFINE("LIITTAMINEN_EPAONNISTUI", "Käyttäjän liittäminen hakemukseen epäonnistui");
DEFINE("ON_LIITETTY", "on liitetty tutkimukseen");
DEFINE("JO_AIEMMIN", "jo aiemmin");
DEFINE("KAYT_JO_LIITETTY", "Käyttäjä on jo liitetty hakemukseen");

// Muut
DEFINE("VALITSE_VERSIO", "Valitse versio");
DEFINE("TALLENNETTU_TALL", "Tiedot tallennettu.");
DEFINE("TIEDOT_EI_TALLENNETTU", "Tietojen tallennus epäonnistui.");
DEFINE("VALITSE_KAIKKI", "Valitse kaikki");
DEFINE("MHLES", "Muutoshakemusta ei voi lähettää viranomaiselle, koska edellistä hakemusta ei ole päätetty.");

// Viranomaisen käyttöliittymä
// Menu
DEFINE("META_MUOK", "Muokkaa metatietoja");
DEFINE("META_TALL", "Tallenna metatiedot");

// Metatiedot
DEFINE("METATIEDOT_P", "metatiedot");
DEFINE("JULKISUUSLUOKKA", "Julkisuusluokka");
DEFINE("SALASSAPITOAIKA", "Salassapitoaika");
DEFINE("SALASSAPITOPERUSTE", "Salassapitoperuste");
DEFINE("SUOJAUSTASO", "Suojaustaso");
DEFINE("HENKILOTIETOJA", "Henkilötietoja");
DEFINE("SAILYTYSAJAN_PITUUS", "Säilytysajan pituus");
DEFINE("SAILYTYSAJAN_PERUSTE", "Säilytysajan peruste");
DEFINE("ASIAKIRJATYYPPI", "Asiakirjatyyppi");
DEFINE("ASIAN", "Asian");
DEFINE("PAATOKSEN", "Päätöksen");
DEFINE("LIITTEEN", "Liitteen");
DEFINE("LAUSUNNON", "Lausunnon");

// Viranomaisen kl: muut
DEFINE("PAAT_ALLEKIRJOITETTU_FAIL", "Päätöksen allekirjoitus epäonnistui");
DEFINE("PAAT_LAH_HYV_FAIL", "Päätöksen lähettäminen hyväksyttäväksi epäonnistui");
DEFINE("PAAT_ALLEKIRJOITETTU", "Päätös allekirjoitettu");
DEFINE("PAAT_LAH_HYV", "Päätös on lähetetty hyväksyttäväksi");
DEFINE("LAUS_ON_LAH", "Lausunto on lähetetty");
DEFINE("TAYTA_LUV_VOIM", "Täytä luvan voimassaoloaika");
DEFINE("VAL_HENK_KL_MYON", "Valitse henkilöt, joille käyttölupa myönnetään");
DEFINE("VAL_HENK_PH", "Valitse henkilöt, joille päätös lähetetään hyväksyttäväksi");
DEFINE("LP_LAH_FAIL", "Lausuntopyynnön lähetys epäonnistui");
DEFINE("LP_LAH", "Lausuntopyyntö lähetetty");
DEFINE("LAH_FAIL_PAK", "Lähetys epäonnistui: pakollisia tietoja puuttui");
DEFINE("VIEST_LAH_EPAONN", "Viestin lähetys epäonnistui: pakollisia tietoja puuttui");
DEFINE("TAYDPYYNT_LAH", "Täydennyspyyntö lähetetty hakemuksen yhteyshenkilölle");
DEFINE("ALKUVUOSI", "Alkuvuosi");
DEFINE("LOPPUVUOSI", "Loppuvuosi");
DEFINE("HAK_PAL_KAS_INFO", "Päättäjä on palauttanut hakemuksen käsittelyyn");
DEFINE("LATAA_HAK_PDF", "Lataa hakemus PDF-muodossa");
DEFINE("HAKEMUSTA_TAYD", "Hakija on täydentänyt hakemusta");
DEFINE("LAH_PAAT_HYV_VARM", "Haluatko varmasti lähettää päätöksen hyväksyttäväksi?");
DEFINE("PAL_HAK_KAS_VARM", "Haluatko varmasti palauttaa hakemuksen käsiteltäväksi?");
DEFINE("PAAT_VAHV_VARM", "Haluatko varmasti allekirjoittaa päätöksen?");
DEFINE("LUOVUTUSTAPA", "Luovutustapa");
DEFINE("ASIANRO", "Asianumero");
DEFINE("MUUT_HAK", "Muut hakemukset");
DEFINE("HYVAKSYTTY", "Hyväksytty");
DEFINE("EHD_HYVAKSYTTY", "Ehdollisesti hyväksytty");
DEFINE("HYLATTY", "Hylätty");
DEFINE("TUTK_HAKU", "Tutkimuksen haku");
DEFINE("SAAPUNEET_HAKEMUKSET", "Saapuneet hakemukset");
DEFINE("SAAPUNEET_LAUSUNNOT", "Saapuneet lausunnot");
DEFINE("SAAPUNEET LAUSUNTOPYYNNOT", "Saapuneet lausuntopyynnöt");
DEFINE("UUDET_HAKEMUKSET", "Uudet hakemukset");
DEFINE("AVATUT_HAKEMUKSET", "Avatut hakemukset");
DEFINE("KAYTTOLUPAPALVELUN_TUNNUS", "Käyttölupapyynnön tunnus");
DEFINE("KASITTELIJA", "Käsittelijä");
DEFINE("HAKIJAN_NIMI", "Hakijan nimi");
DEFINE("HAKIJAN_ROOLI", "Hakijan rooli");
DEFINE("TUTKIMUSNRO", "Tutkimusnumero");
DEFINE("HAKEMUKSEN_KASITTELYPVM", "Hakemuksen käsittelyvuodet");
DEFINE("HAKUTULOKSET", "Hakutulokset");
DEFINE("PAATOKSET", "Päätökset");
DEFINE("KASITTELYN_ETENEMINEN", "Käsittelyn eteneminen");
DEFINE("OTA_HAK_VIR_KAS", "Ota hakemus viranomaiskäsittelyyn");
DEFINE("HAK_PYD_TAYD", "Pyydä hakemukseen täydennystä");
DEFINE("TUL_HAK_VAR_VIR", "Tuliko hakemus väärälle viranomaiselle?");
DEFINE("LISAA_TAYD_PYYNT_ERA", "Lisää täydennyspyynnön eräpäivä");
DEFINE("PYYDA_TAYDENNYSTA", "Hakijalta pyydetään täydennystä hakemukseen viestitse");
DEFINE("LISAA_VIESTI", "Lisää viesti");
DEFINE("SIIR_TOISEL_VIR", "Siirrä hakemus toiselle viranomaiselle");
DEFINE("LAHETA", "Lähetä");
DEFINE("HANKKEEN_KUVAUSTIEDOT", "Hankkeen kuvaustiedot");
DEFINE("TUTK_ARV_KK", "Tutkimuksen arvioitu kokonaiskesto");
DEFINE("KER_TIET_KAYTT", "Kerättävien rekisteritietojen käyttötarkoitus");
DEFINE("SITOUMUS", "Sitoumus");
DEFINE("VALITUT_REKISTERIT", "Valitut rekisterit");
DEFINE("TOIVOTTU_LASKUTUSMUOTO", "Toivottu laskutusmuoto");
DEFINE("LIITTEEN_NIMI", "Liitteen nimi");
DEFINE("LIITTEEN_TYYPPI", "Liitteen tyyppi");
DEFINE("AVAA_HAKEMUS", "Tarkastele hakemusta");
DEFINE("PDF_MUODOSSA", "Lataa hakemus PDF-muodossa");
DEFINE("UUDESSA_IKKUNASSA", "Uudessa ikkunassa");
DEFINE("TARKASTELE_INFO", "Hakemuksesta voi ladata tiivistetyn version PDF-muodossa.");
DEFINE("HAKEMUS_PDF_MUODOSSA", "Hakemus PDF-muodossa");
DEFINE("YHTEENVETO_HAKEMUKSESTA", "Yhteenveto hakemuksesta");
DEFINE("YHTEENVETO_INFO", "Alle on koottu tiivistelmä hakemuksen tiedoista");
DEFINE("VIESTI", "Viesti");
DEFINE("VASTAANOTTAJA", "Vastaanottaja");
DEFINE("VAL_VAST", "Valitse vastaanottaja");
DEFINE("LAHETA_VIESTI", "Lähetä viesti");
DEFINE("TUTKIMUKSEN_VIESTIT", "Tutkimuksen viestit");
DEFINE("HAKEMUKSEN_VIESTIT", "Hakemuksen viestit");
DEFINE("AIEM_HAKEMUKSEN", "Aiemman hakemuksen");
DEFINE("VIESTIN_LAHETTAJA", "Viestin lähettäjä");
DEFINE("VIESTIN_VASTAANOTTAJA", "Viestin vastaanottaja");
DEFINE("VASTAA", "Vastaa viestiin");
DEFINE("LAHETA_VASTAUS", "Lähetä vastaus");
DEFINE("VASTAUS", "Vastaus");
DEFINE("VASTAUKSEN_LAHETTAJA", "Vastauksen lähettäjä");
DEFINE("LAUSUNNOT", "Lausunnot");
DEFINE("OTA_KASITTELYYN", "Käsittele hakemusta");
DEFINE("VAIHDA_KASITTELIJAA", "Vaihda käsittelijää");
DEFINE("PYYDA_LAUSUNTOA", "Pyydä lausuntoa");
DEFINE("VALITSE_LAUSUNNONANTAJA", "Valitse lausunnonantaja");
DEFINE("LAUSUNNONANTAJAT", "Lausunnonantajat");
DEFINE("LAUSUNNON_MAARAPAIVA", "Lausunnon määräpäivä");
DEFINE("LAUSUNTOPYYNTO", "Lausuntopyyntö");
DEFINE("VALITSE_LAUSUNTOPOHJA", "Valitse lausuntopohja");
DEFINE("PYYDA_LAUSUNTO", "Pyydä lausunto");
DEFINE("LAUSUNNOT_JA_PYYNNOT", "Lausuntopyynnöt ja lausunnot");
DEFINE("LAUS_PYYTAJA", "Lausunnon pyytäjä");
DEFINE("SAAPUNUT_LAUSUNTO", "Saapunut lausunto");
DEFINE("KENELTA_PYYD_LAUS", "Keneltä lausuntoa pyydetty");
DEFINE("PYYNTO", "Pyyntö");
DEFINE("SAAPUNEET_LAUSUNNOT_INFO", "Valitse tutkimus ja pääset lukemaan siihen saapunutta lausuntoa.");
DEFINE("EI_SAAPUNEITA_LAUSUNTOJA", "Lausuntoja ei ole saapunut.");
DEFINE("LUKEMATTOMAT_LAUSUNNOT", "Lukemattomat lausunnot");
DEFINE("LUETUT_LAUSUNNOT", "Luetut lausunnot");
DEFINE("LAUSUNTO_PYYDETTY", "Lausunto pyydetty");
DEFINE("LAUSUNTO_ANNETTU", "Lausunto annettu");
DEFINE("LAUSUNNON_ANTAJA", "Lausunnon antaja");
DEFINE("VIRANOMAISEN_KAYTTOLUPAPALVELU", "Viranomaisen käyttölupapalvelu");
DEFINE("TERVETULOA", "Tervetuloa");
DEFINE("LAUSUNNON_NAYTTAMINEN", "Lausunnon sisältö näytetään hakijoille");
DEFINE("LAUSUNTO_PAATOS", "Lausunnonantajan päätös");
DEFINE("TIETOLUPAPAATOS", "Tietolupapäätös");
DEFINE("VALITSE_PAATOS", "Valitse päätös");
DEFINE("KAYTTOTARKOITUS", "Käyttötarkoitus");
DEFINE("HENK_TIET_KAS_HENKILOT", "Henkilötietoja käsittelevät henkilöt, jotka ovat antaneet salassapitositoumukset");
DEFINE("ON_MYONTANYT_SAL", "on tänään myöntänyt salassapitositoumuksen antaneille tutkijoille");
DEFINE("LUVAN_TUTUSTUA", "luvan tutustua");
DEFINE("NIMISTA_TUTK_VARTEN", "- nimistä tutkimusta varten alla tarkasti yksilöityihin rekisteritietoihin.");
DEFINE("LUOVUTETTAVAT_TIEDOT", "Luovutettavat tiedot");
DEFINE("POIMITTAVAT_MUUTTUJAT", "Poimittavat muuttujat");
DEFINE("PAATOS_HYVAKSYTTY_TXT1", "Viranomainen on tänään myöntänyt luvan saada käyttää");
DEFINE("PAATOS_HYVAKSYTTY_TXT2", "- nimisessä tutkimuksessa tässä päätöksessä määriteltyjä tietoja.");
DEFINE("LUPA_ON_VOIMASSA", "Lupa on voimassa");
DEFINE("SAAKKA", "saakka");
DEFINE("ALLEKIRJOITA_PAATOS", "Allekirjoita päätös");
DEFINE("LAHETA_PAATOS", "Lähetä päätös");
DEFINE("PERUUTA_PAATOS", "Peruuta päätös");
DEFINE("OIKAISUVAATIMUS", "Oikaisuvaatimus ja valitusoikeus");
DEFINE("LAHETA_HYVAKSYTTAVAKSI", "Lähetä päätös hyväksyttäväksi");
DEFINE("VIRKAILIJA", "Virkailija");
DEFINE("TOIMINTO", "Toiminto");
DEFINE("KAYTTOLUPA", "Käyttölupa");
DEFINE("AINEISTOTILAUS", "Aineistotilaus");
DEFINE("KOHDEJOUKON_MUODOSTAMINEN", "Kohdejoukon muodostaminen");
DEFINE("AINT_EPAONNISTUI", "Aineistopyynnön lähettäminen epäonnistui");
DEFINE("AINT_LAHETETTY", "Aineistopyyntö lähetetty");
DEFINE("PERUMINEN_PERUSTELUT", "Perustelut päätöksen perumiselle");
DEFINE("TAKAISIN", "Takaisin");
DEFINE("HAKEMUKSET", "Hakemukset");
DEFINE("PYYDETTY", "Pyydetty");
DEFINE("VASTATTU", "Vastattu");
DEFINE("LUPAPALVELU", "Lupapalvelu");
DEFINE("KAYTTOEHDOT_MUUT_RAJOITUKSET", "Käyttöehdot ja muut rajoitukset");
DEFINE("PIKAHAKU", "Pikahaku");
DEFINE("VIR_ETUSIVU_INFO", "Alle on listattu teille saapuneet hakemukset. Hakemusta voi tarkastella valitsemalla tutkimuksen nimen.");
DEFINE("VALITSE_HAKIJAN_ROOLI", "Valitse hakijan rooli");
DEFINE("VALITSE_HAK_TILA", "Valitse hakemuksen tila");
DEFINE("VUOSI_ALKU", "Aloitusvuosi");
DEFINE("VUOSI_LOPPU", "Lopetusvuosi");
DEFINE("ETSI_HAKEMUKSIA", "Etsi hakemuksia");
DEFINE("EI_TULOKSIA", "Haulla ei löytynyt yhtään tulosta");
DEFINE("UUSI", "Uusi");
DEFINE("MUUTOS", "Muutos");
DEFINE("SELAA_LAUSUNTOA", "Selaa lausuntoa");
DEFINE("SEL_LAUSPYYNT", "Selaa lausuntopyyntöjä");
DEFINE("ANNA_LAUSUNTO", "Anna lausunto");
DEFINE("PAL_KAS", "Palauta hakemus käsittelijälle");
DEFINE("MUID_VO_H", "Rinnakkaishakemukset");
DEFINE("EI_RINN", "Ei rinnakkaishakemuksia");
DEFINE("HIST", "Hakemushistoria");
DEFINE("EI_HIST", "Ei aiempia hakemusversioita");
DEFINE("LISATOIM", "Lisätoiminnot");
DEFINE("KUVAKK", "Kuvakkeiden selitykset");
DEFINE("NAY_HIST", "Näytä hakemushistoria");
DEFINE("NAY_RINN", "Näytä rinnakkaiset hakemukset");
DEFINE("POSTINRO_JA_TP", "Postinumero ja postitoimipaikka");
DEFINE("HINTA_ARVIO", "Hinta-arvio");
DEFINE("LUVAN_LAUSUNNOT", "Luvan käsittelyssä saadut lausunnot ja muut luvat");
DEFINE("SOV_OIKEUSOHJ", "Sovelletut oikeusohjeet");
DEFINE("LUVAN_EHDOT", "Luvan ehdot");
DEFINE("VALITUSOSOITUS", "Valitusosoitus");
DEFINE("LUV_VOIMAIKA", "Luvan voimassaoloaika");
DEFINE("LUV_VOIMOLO", "Luvan voimassaolo");
DEFINE("LUPA_MYONN_EHD", "Lupa myönnetään seuraavin ehdoin");
DEFINE("MAKSU", "Maksu");
DEFINE("LUP_MAKS_VELV", "Lupahakemuksessa maksuvelvolliseksi on osoitettu:");
DEFINE("PAATOS_LISATIEDOT", "myöntää sitoumuksen tietojen salassapidosta antaneille tutkijoille luvan kohdassa ”Tutkimusaineisto” tarkoitettuihin tietoihin. Lupa myönnetään tutkimussuunnitelman edellyttämässä laajuudessa.");
DEFINE("ASTI", "asti");
DEFINE("PAATOS_OTSIKKO", "Lupa tietojen saamiseksi salassa pidettävistä rekistereistä ja asiakirjoista");
DEFINE("SALASSPIT_TUTK", "Salassapitositoumuksen antaneet tutkijat");
DEFINE("AINEISTO_LUOVUTETAAN", "Käyttölupa myönnetään lupahakemuksessa haettuihin tietoihin seuraavasti:");
DEFINE("REK_AIN_NIMI", "Rekisterin/aineiston nimi");
DEFINE("REK_AIN", "Rekisterin/aineiston");
DEFINE("LUOVUTETTAVAT_TIEDOT_P", "luovutettavat tiedot");
DEFINE("TIEDOT_AJALTA_P", "tiedot ajalta");
DEFINE("HINTA_ARVIO_TIEDOT", "Tietojen poiminnasta aiheutuvat kustannukset ovat arviolta n.");
DEFINE("HINTA_ARVIO_TIEDOT_2", "euroa (sis. alv 24 %). Arvio kattaa tiedot vuosilta");
DEFINE("HINTA_ARVIO_TIEDOT_3", "Toteutuneet kustannukset peritään myöhemmin maksuvelvolliselle erikseen lähetettävällä laskulla");
DEFINE("MAKSU_INFO", "Maksun määräämiseen voi hakea oikaisua, mikäli maksuvelvollinen kokee, että maksun määräämisessä on tapahtunut virhe. Oikaisuvaatimusohje on päätöksen liitteenä");
DEFINE("HAV_ARK_ILMO", "Hävittämistä tai arkistointia koskevan ilmoituksen tekeminen");
DEFINE("HAV_ARK_ILMO_TIEDOT", "Vastuullisen johtajan tulee huolehtia siitä, että tutkimusaineiston hävittämisestä tai arkistoinnista tutkimuksen päätyttyä tehdään ilmoitus lupajärjestelmässä");
DEFINE("HAKEMUKSEN_TUNNUS", "Hakemuksen tunnus");
DEFINE("VAPAAMUOTOINEN_PAATOS", "Vapaamuotoinen päätös");
DEFINE("HAK_HYL_PER", "Hakemus hylätään seuraavin perustein");
DEFINE("TUTK_MAKSU", "Tutkimusluvasta peritään hakijalta maksuna");
DEFINE("EUROA", "euroa");
DEFINE("MAKS_PER", "Maksu perustuu");
DEFINE("PAATTAJAT", "Päättäjät");
DEFINE("PAATOSPOHJA", "Päätöspohja");
DEFINE("PAAT_ALLEKIRJ", "Päätöksen allekirjoittaneet");
DEFINE("LIS_LIITT", "Lisätyt liitteet");
DEFINE("KOK_KAS", "Kokouskäsittely");
DEFINE("VIE_KOK", "Vie asia kokouskäsittelyyn");
DEFINE("POYTKIRJ_OTE", "Pöytäkirjanotteet");
DEFINE("VAL_PAATPOHJ", "Valitse päätöspohja");
DEFINE("LIITA_PKO", "Liitä päätös / pöytäkirjanote / ilmoitus");
DEFINE("LAH_LTP", "Lähetä hakijalle lisätietopyyntö");
DEFINE("LAH_TAYD_HAK", "Lähetä hakijalle täydennyspyyntö (hakemus)");
DEFINE("LAH_TAYD_ASK", "Lähetä hakijalle täydennyspyyntö");
DEFINE("TAYD_PYYNTO", "Täydennyspyyntö");
DEFINE("TAYD_HAK", "Täydennykset/korjaukset tehdään hakemukseen");
DEFINE("TAYD_ASK", "Täydennykset/korjaukset toimitetaan asiakirjoina");
DEFINE("TAYD_HYVPYYNT", "Täydennysten hyväksymispyyntö");
DEFINE("TAYD_VAAT_PJ_HYV", "Korjaukset/täydennykset vaativat puheenjohtajan hyväksymisen");
DEFINE("VAL_PJ", "Valitse puheenjohtaja(t), jolle/joille hyväksymispyyntö lähetetään");
DEFINE("PAAT_VAL", "Tähän päätökseen tyytymätön saa hakea siihen muutosta valittamalla hallinto-oikeuteen. Valitusosoitus on päätöksen liitteenä.");

// Aineistonmuodostajan käyttöliittymä
DEFINE("SAAPUNEET_TILAUKSET", "Saapuneet tilaukset");
DEFINE("AINEISTONMUODOSTAJAN_PALVELU", "Aineistonmuodostajan palvelu");
DEFINE("AINEISTOPYYNNOT", "Aineistopyynnöt");
DEFINE("KOHDEJOUKON_TILA", "Kohdejoukon tila");
DEFINE("AINEISTOTILAUKSEN_TILA", "Aineistotilauksen tila");
DEFINE("TILAUSPVM", "Tilauspvm");
DEFINE("AVAA_HAK", "Avaa hakemus");
DEFINE("TILAUKSEN_KASITTELIJA", "Tilauksen käsittelijä");
DEFINE("AIN_MUOD_ETEN", "Aineistonmuodostuksen eteneminen");
DEFINE("AIN_MUOD_INFO", "Tarkista käsittelijä sekä aineistonmuodostamisesta syntyvä hinta-arvio ja valmistumisaika");
DEFINE("KOHDEJOUKKO_MUODOSTETTU", "Kohdejoukko muodostettu");
DEFINE("AINEISTO_LAHETETTY", "Aineisto lähetetty");
DEFINE("AINEISTONMUODOSTUKSEN_HINTA", "Aineistonmuodostuksen hinta");
DEFINE("TIL_YHT_TIED", "Tilaajan yhteystiedot");
DEFINE("AINT_MUOD_TALL", "Aineistonmuodostus tallennettu");
DEFINE("AINT_MUOD_FAIL", "Aineistonmuodostuksen tallennus epäonnistui");
DEFINE("VAL_KAS", "Valitse käsittelijä");
DEFINE("UUDET_TILAUKSET", "Uudet tilaukset");
DEFINE("REKLAMAATIOT", "Reklamaatiot");
DEFINE("KASITTELYT", "Käsittelyt");
DEFINE("TOIMITETUT", "Toimitetut");
DEFINE("LAIT_JOHON_LUPA_PER", "Lait, joihin lupa perustuu");
DEFINE("LUPA_MSLN", "Lupa myönnetään seuraavien lakien nojalla:");
DEFINE("AINMUOD_ETUSIVUN_OHJE", "Alle on listattu saapuneet aineistopyynnöt.");
DEFINE("OTA_KASITTELYYN2", "Ota käsittelyyn");
DEFINE("OTA_AIN_VIR_KAS", "Ota aineistotilaus käsittelyyn");
DEFINE("AINEISTON_MUODOSTUS", "Aineiston muodostus");
DEFINE("KUVAUS_AINMUOD", "Kuvaus tutkimusaineiston muodostamisesta");
DEFINE("KUITTAA_AIN_LAH", "Kuittaa aineisto toimitetuksi");
DEFINE("AIN_KUITT_VARM", "Haluatko varmasti kuitata aineiston toimitetuksi?");
DEFINE("AINT_OT_KAS", "Aineistotilaus on otettu käsittelyyn");
DEFINE("AINT_OT_EP", "Aineistotilauksen ottaminen käsittelyyn epäonnistui");
DEFINE("LIS_AINM_VA", "Lisää aineiston muodostamisen valmistumisaika");

//
// Lausunnonantajan käyttöliittymä
DEFINE("LAUSUNTO", "Lausunto");
DEFINE("VAL_LAUSUNTOPOHJA", "Valitse lausuntopohja");
DEFINE("TYHJA", "Tyhjä"); 
DEFINE("VIESTIT", "Viestit");
DEFINE("SAAPUNEET_LAUSUNTOPYYNNOT", "Saapuneet lausuntopyynnöt");
DEFINE("ANNETUT_LAUSUNNOT", "Annetut lausunnot");
DEFINE("ANNETTU_LAUSUNTO", "Annettu lausunto");
DEFINE("MAARAPAIVA", "Määräpäivä");
DEFINE("LAHETA_LAUSUNTO", "Anna lausunto");
DEFINE("LAUSUNNON_PYYTAJA", "Lausunnon pyytäjä");
DEFINE("LAUSUNNONANTAJAN_PALVELU", "Lausunnonantajan palvelu");
DEFINE("LAUSUNNONANTAJAN_ETUSIVUN_OHJE", "Voit tarkastella saapuneita lausuntopyyntöjä ja hakemuksia tai antaa lausuntoja.");
DEFINE("LAUSUNTOPYYNNOT", "Lausuntopyynnöt");
DEFINE("PYYDETTY_LAUSUNTO", "Pyydetty lausunto");
DEFINE("LISAA_LAUSUNTO", "Lisää lausunto");
DEFINE("LAHETA_LAUSUNTO2", "Lähetä lausunto");
DEFINE("TALLENNA_LAUSUNTO", "Tallenna lausunto");
DEFINE("LAUSUNTOPOHJA", "Lausuntopohja");
DEFINE("LAUSPOHJA_KYSYMYS1", "Ovatko tutkimuskysymykset mielekkäitä ja toteuttamiskelpoisia?");
DEFINE("LAUSPOHJA_KYSYMYS2", "Edellyttääkö tutkimuskysymyksiin vastaaminen yksilötason aineistoa?");
DEFINE("LAUSPOHJA_KYSYMYS3", "Ovatko tutkimusasetelma, aineisto ja menetelmät tarpeeksi hyvin kuvattu hakemuksessa?");
DEFINE("LAUSPOHJA_KYSYMYS4", "Voidaanko tutkimuksen tavoitteet saavuttaa hakemuksessa kuvatulla aineistolla ja menetelmillä?");
DEFINE("LAUSPOHJA_KYSYMYS5", "Ovatko kaikki hakemuksessa mainitut rekisterit ja niiden tiedot/muuttujat tarpeellisia tutkimuksen toteuttamiseksi?");
DEFINE("LAUSPOHJA_KYSYMYS6", "Onko tutkijan haluamia tietoja/muuttujia olemassa rekisterissä/rekistereissä oikeassa muodossa?");
DEFINE("LAUSPOHJA_KYSYMYS7", "Voisiko tutkimus hyötyä muista kuin hakemuksessa mainituista tiedoista/muuttujista?");
DEFINE("LAUSPOHJA_KYSYMYS8", "Voisiko tutkimus hyötyä muiden rekistereiden/tietolähteiden käytöstä?");
DEFINE("PERUSTELUT", "Perustelut");
DEFINE("PERUSTELUT_EETTISET", "Tutkimuksen mahdolliset eettiset ongelmat tai muut käyttöluvan myöntämiseen vaikuttavat puutteet hakemuksessa");
DEFINE("PERUSTELUT_MUUT", "Perustelut, muut huomiot ja kommentit");
DEFINE("KYLLA", "Kyllä");
DEFINE("EI", "Ei");
DEFINE("PUOLLAN", "Puollan käyttöluvan myöntämistä tutkimukselle");
DEFINE("PUOLLAN_EHDOLLISENA", "Puollan käyttöluvan myöntämistä tutkimukselle ehdollisena:");
DEFINE("PUOLLAN_EHDOLLISENA2", "Puollan käyttöluvan myöntämistä tutkimukselle, kun siinä huomioidaan seuraavat seikat/hakemusta täydennetään seuraavalla tavalla");
DEFINE("EN_PUOLLA", "En puolla käyttöluvan myöntämistä tutkimukselle");
DEFINE("LAUS_POHJ_AVULLA", "Lausuntopohjan avulla");
DEFINE("SANALLISENA", "Sanallisena");
DEFINE("SANALLINEN_KUVAUS", "Sanallinen kuvaus");
DEFINE("VIITE", "Viite");
DEFINE("LAUSUNTOPYYNTONNE", "Lausuntopyyntönne");
DEFINE("TUTKIMUKSESTA", "tutkimuksesta");
DEFINE("ANNETUT_LAUSUNNOT_INFO", "Valitse tutkimus ja pääset lukemaan siihen antamaasi lausuntoa.");
DEFINE("EI_ANNETTUJA_LAUSUNTOJA", "Lausuntoja ei ole annettu.");
DEFINE("EI_LAUS_PYYNTOJA", "Hakemukseen ei ole saapunut lausuntopyyntöjä.");
DEFINE("VAT_TUNNUS", "VAT-tunnus");
DEFINE("OVT_TUNNUS", "OVT-tunnus");
DEFINE("VERKKOLASK_OP_NIMI", "Verkkolaskuoperaattorin nimi");
DEFINE("VERKKOLASK_OP_TUNNUS", "Verkkolaskuoperaattorin tunnus");
DEFINE("ORGANISAATIO_OSOITE", "Organisaation osoite");
DEFINE("SAHKO_PAAT_HYV", "Sähköisen päätöksen hyväksyminen");
DEFINE("VPLPS", "Voidaanko päätös lähettää pelkästään sähköisesti?");
DEFINE("LAH_LAUS_VARM", "Haluatko varmasti lähettää lausunnon?");
DEFINE("LAUS_TIED_VARM", "Haluatko varmasti lähettää eettisen toimikunnan lausunnon tiedoksi?");
DEFINE("LAUS_LAH", "Lausunto lähetetty");
DEFINE("LAUS_LAH_FAIL", "Lausunnon lähetys epäonnistui");
DEFINE("VAL_LAUS_JP", "Valitse lausunnolle johtopäätös");
DEFINE("JOHTOPAATOS", "Johtopäätös");

// Pääkäyttäjän käyttöliittymä
DEFINE("PAAKAYT_LUPAPALVELU", "Pääkäyttäjän lupapalvelu");
DEFINE("LISAA_KAYTTAJA", "Lisää käyttäjä");
DEFINE("KAYTTAJAROOLIT", "Käyttäjäroolit");
DEFINE("LISAA_VIR_OM", "Liitä olemassa oleva käyttäjä viranomaiseksi");
DEFINE("LISAA_VIRANOMAINEN", "Lisää viranomainen");
DEFINE("POISTA_KAYTTAJA", "Poista käyttäjä");
DEFINE("POISTA_VIR_OM", "Poista viranomainen");
DEFINE("ROOLIEN_HALLINTA", "Roolien hallinta");
DEFINE("TIEDOT_TALLENNETTU", "Käyttäjän rooli tallennettu.");
DEFINE("KAYTTAJAN_LIITTAMINEN_VIR", "Käyttäjän liittäminen viranomaiseksi");
DEFINE("LISAA_VIROM_INFO", "Alla voit lisätä järjestelmään uuden viranomaisen. Täytä tietoihin viranomaisen käyttäjätunnus (sähköpostiosoite) ja määrittele rooli(t).");
DEFINE("ROOLIT", "Roolit");
DEFINE("KAYTTAJA_LISATTY", "Viranomaisen lisääminen onnistui.");
DEFINE("LISAA_UUSI_VIRANOM", "Lisää uusi viranomainen");
DEFINE("LAIT", "Lait");
DEFINE("VIR_KONF", "Viranomaiskohtaisten lisätietojen konfigurointi");
DEFINE("VIR_KONF_INFO", "Lisää tai poista tutkimuksen viranomaiskohtaisia kysymyksiä.");
DEFINE("VIR_KENT", "Viranomaiskohtaiset tiedot");
DEFINE("KENTTA_OTSIKKO", "Otsikko");
DEFINE("KENTTA_TYYPPI", "Tyyppi");
DEFINE("ONKO_PAKOLLINEN", "Pakollinen?");
DEFINE("ONKO_PAKOLLINEN2", "Onko kenttä pakollinen tieto?");
DEFINE("POISTA", "Poista");
DEFINE("LISAA_VK", "Lisää viranomaiskohtainen kenttä");
DEFINE("TEKSTI", "Teksti");
DEFINE("AIKA", "Aika");
DEFINE("LISAA_KENTTA", "Lisää kenttä");
DEFINE("VIR_KENTTA_LISATTY", "Viranomaiskohtainen kenttä lisätty");
DEFINE("VIR_KENTTA_EI_LISATTY", "Viranomaiskohtaisen kentän lisääminen epäonnistui.");
DEFINE("VIR_KENTTA_POISTETTU", "Viranomaiskohtainen kenttä poistettu");
DEFINE("VIR_KENTTA_EI_POISTETTU", "Viranomaiskohtaisen kentän poisto epäonnistui.");
DEFINE("PAATOS_LAKI_KONF", "Käyttölupaan perustuvien lakien konfigurointi");
DEFINE("PAATOS_LAKI_INFO", "Lisää tai poista lakeja, joihin myönnetty käyttölupa perustuu.");
DEFINE("LAKI", "Laki");
DEFINE("LISAA_LAKI", "Lisää uusi laki");
DEFINE("LISAA_LAKI_INFO", "Lisää uusi viranomaiskohtainen laki");
DEFINE("LAKI_LISATTY", "Uusi laki lisätty.");
DEFINE("LAKI_EI_LISATTY", "Lain lisääminen epäonnistui.");
DEFINE("VIR_LAKI_POISTETTU", "Viranomaiskohtainen laki poistettu.");
DEFINE("NAKYMAT", "Näkymät");
DEFINE("LOMAKKEET", "Lomakkeet");

//SAAPUNEET_VIESTIT määritelty jo aiemmin
DEFINE("ETSI_HAKEMUSTA", "Etsi hakemusta");
DEFINE("YHTEENVETO", "Yhteenveto");
//

// Ohjetekstejä
DEFINE("ETUSIVUN_OHJE", "Voit aloittaa uuden käyttölupahakemuksen tekemisen tai täydentää aikaisemmin tallentamaasi keskeneräistä hakemusta.");
DEFINE("PALAUTE", "Palaute");
DEFINE("YHTEYSTIEDOT", "Yhteystiedot");
DEFINE("TIETOA_WWW_SIVUISTA", "Tietoa www-sivuista");
DEFINE("INFORMAATIO_JA_TUKIPORTAALI", "Informaatio- ja tukiportaali");
//
// Koodistot
//
// AINEISTO
DEFINE("ain_lah_til", "lähetetty tilaajalle");
DEFINE("ain_pal", "palautettu/tuhottu");
DEFINE("kohortti_asetelma", "Kohorttitutkimus tai poikkileikkaustutkimus");
DEFINE("kohortti_asetelma_kuv", "Kohorttitutkimuksessa seurataan pitkähkön aikaa tiettyä ihmisryhmää, jolla on jokin yhteinen piirre. Poikkileikkaustutkimuksessa on yksi mittauskerta, joka kohdistuu useaan eri havaintoyksikköön.");
DEFINE("verrokki_asetelma", "Tapaus-verrokkitutkimus");
DEFINE("verrokki_asetelma_kuv", "Tapaus-verrokkitutkimuksessa verrataan koehenkilöitä, joilla on tutkittava sairaus tai oire (\"tapaukset\"), mahdollisimman samanlaisiin koehenkilöihin, joilla sitä ei ilmene (\"verrokit\"). Tavoitteena on tyypillisesti selvittää koehenkilöiden ominaispiirteiden tai erilaisten altistumisten yhteyttä sairauteen.");
DEFINE("muu_asetelma", "Muu asetelma, millainen?");

// AINEISTON_TIEDOSTOMUOTO
DEFINE("am_excel", "Excel-taulukko");
DEFINE("am_muu", "muu");
DEFINE("am_sas", "SAS");
DEFINE("am_teksti", "Tekstitiedosto");
// AINEISTON_TOIMITUS
DEFINE("at_eta", "etätyöpöytä");
DEFINE("at_muu", "muu");
// AINEISTOTILAUKSEN_TILA
DEFINE("aint_keskenerainen", "keskeneräinen");
DEFINE("aint_uusi", "uusi tilaus");
DEFINE("aint_kas", "käsittelyssä");
DEFINE("aint_toimitettu", "toimitettu");
DEFINE("aint_rekl", "reklamaatio");
DEFINE("aint_palautettu", "palautettu");
DEFINE("aint_havitetty", "hävitetty");
DEFINE("aint_arkistoitu", "arkistoitu");
//DEFINE("aint_kasitelty", "käsitelty");

//KOHDEJOUKON TILA
DEFINE("kohdt_puuttuu", "puuttuu");
DEFINE("kohdt_muodostetaan", "muodostetaan");
DEFINE("kohdt_valmis", "valmis");

// AINEISTOTILAUKSEN_TYYPPI
DEFINE("aint_tyyp_rekl", "reklamaatio");
DEFINE("aint_tyyp_uusi", "uusi");
// HAKEMUSVERSION TILA
DEFINE("hv_kesken", "Keskeneräinen");
DEFINE("hv_lah", "Lähetetty");
// HAKEMUKSEN_TILA
DEFINE("hak_kas", "käsittelyssä");
DEFINE("hak_kesken", "keskeneräinen");
DEFINE("hak_korvattu", "korvattu");
DEFINE("hak_lah", "lähetetty käsiteltäväksi");
DEFINE("hak_val", "valmis päätettäväksi");
DEFINE("hak_muuta", "lisätietoa pyydetty");
DEFINE("hak_paat", "päätetty");
DEFINE("hak_peruttu", "peruttu");
// JOUKON_TYYPPI
DEFINE("j_kohde", "Kohdejoukko");
DEFINE("j_verrokki", "Verrokit");
DEFINE("j_viite", "Viitehenkilöt");
DEFINE("m_kohde", "Kohdejoukolle poimittavat muuttujat");
DEFINE("m_verrokki", "Verrokeille poimittavat muuttujat");
DEFINE("m_viite", "Viitehenkilöille poimittavat muuttujat");
// KIELI
DEFINE("en", "englanti");
DEFINE("fi", "suomi");
DEFINE("sv", "ruotsi");
// KOHDEJOUKON_KERUULAAJUUS
DEFINE("klaaj_alue", "Alueellinen tutkimus");
DEFINE("klaaj_valtak", "Valtakunnallinen tutkimus");
DEFINE("klaaj_monikans", "Monikansallinen tutkimus");
DEFINE("klaaj_monik", "Monikansallinen tutkimus");
// KOHDEJOUKON_TIETOLÄHDE
DEFINE("klahde_biop", "Biopankkinäyteet tai biopankkitiedot");
DEFINE("klahde_lt", "Lääketieteellisen tutkimuksen aineisto");
DEFINE("klahde_hk", "Haastattelu- tai kyselytutkimuksen aineisto");
DEFINE("klahde_rek", "Aiemmin kerätty rekisteripohjainen aineisto");
DEFINE("klahde_muu", "Muu itse kerätty aineisto");
DEFINE("klahde_ra", "Rekisteripohjainen aineisto");
DEFINE("klahde_tayd", "Täydennetään aiemmin kerättyä aineistoa");
// KYLLÄ_EI
DEFINE("ei", "Ei");
DEFINE("kylla", "Kyllä");
// KÄYTTÄJÄRYHMÄ
DEFINE("kr_ain_muod", "aineistonmuodostaja");
DEFINE("kr_hakija", "hakija");
DEFINE("kr_laus", "lausunnonantaja");
DEFINE("kr_paak", "pääkäyttäjä");
DEFINE("kr_vir", "viranomainen");
// LASKUTUS
DEFINE("lasku_paperi", "paperilasku");
DEFINE("lasku_verkko", "verkkolasku");
// LAUSUNTO
DEFINE("laus_ehto", "Puollan käyttöluvan myöntämistä tutkimukselle ehdollisena");
DEFINE("laus_ehto_a", "(kirjoita perustelut alle avautuvaan tekstilaatikkoon).");
DEFINE("laus_ei", "En puolla käyttöluvan myöntämistä tutkimukselle");
DEFINE("laus_kylla", "Puollan käyttöluvan myöntämistä tutkimukselle");
// LIITTEET
DEFINE("liite_eettis", "Eettisen toimielimen lausunto");
DEFINE("liite_julk_suunn", "Julkaisusuunnitelma");
DEFINE("liite_kayttotapa", "Tutkimusaineiston käyttötapa");
DEFINE("liite_muu", "Muu liite");
DEFINE("liite_tayd", "Täydennysasiakirja");
DEFINE("liite_rek_sel", "Rekisteriseloste");
DEFINE("liite_rek_sel_info", "Vuoden 2017 formaatti tieteellisen tutkimuksen rekisteriselosteesta ");
DEFINE("liite_suost", "Tutkittavien suostumuslomake ja informointikirje tutkittaville");
DEFINE("liite_tutk_suunn", "Tutkimussuunnitelma");
DEFINE("liite_op_ohj_laus", "Opinnäytteen ohjaajan lausunto");
DEFINE("liite_itse_kerattu", "Mallit tutkimus- ja verokkihenkilöiden yhteydenottokirjeistä, tiedotteista ja suostumusasiakirjoista");
DEFINE("liite_laaketiet_tutk", "Eettisen toimikunnan lausunto");
DEFINE("biop_lista", "Lista kaikista tutkimukseen osallistuvista tutkijoista ja muusta henkilökunnasta");
DEFINE("biop_lista_info", "Nimi, osoite, organisaatio, tehtävä tutkimusprojektissa");
DEFINE("biop_cv", "Tutkimuksesta vastaavan henkilön CV");
DEFINE("biop_cv_info", "Tutkimuksen vastuuhenkilön ansioluettelo");
DEFINE("kl_sop_kasitt", "Sopimus hankkeen tietojen käsittelystä, hävittämisestä tai säilyttämiseen sekä tutkimusaineiston suojaukseen  liittyvistä vastuista");
DEFINE("kl_org_lupa", "Organisaation lupa tutkimuksen toteuttamiselle");
DEFINE("kl_malli_tut_verr", "Mallit tutkittavien/kohdejoukon (ja mahdollisten verrokkien ja/tai viitehenkilöiden) yhteydenottokirjeistä, tiedotteista ja suostumusasiakirjoista");
DEFINE("liite_muutt", "Muuttujalistaus");

DEFINE("PAKOLLINEN_LIITETIEDOSTO", "Pakollinen liitetiedosto");
DEFINE("LISAA_UUSI_LIITE", "Lisää uusi liite");

// MUU
DEFINE("tt_kerta", "Kertatutkimus");
DEFINE("tt_seuranta", "Seurantatutkimus");
// OPINNÄYTETYÖ
DEFINE("op_ei", "Tietojen käyttötarkoitus ei ole opinnäytetyö");
DEFINE("op_kylla", "Tietojen käyttötarkoitus on opinnäytetyö");
DEFINE("op_useissa", "Tutkimusaineistoa käytetään useissa opinnäytetöissä");
// PÄÄTÖKSEN_TILA
DEFINE("paat_tila_hylatty", "hylätty");
DEFINE("paat_tila_hyvaksytty", "hyväksytty");
DEFINE("paat_tila_ehd_hyvaksytty", "ehdollisesti hyväksytty");
DEFINE("paat_tila_peruttu", "peruttu");
DEFINE("paat_tila_korvattu", "korvattu");
DEFINE("paat_tila_rauennut", "rauennut");
DEFINE("paat_tila_kesken", "kesken");
// ROOLI2
DEFINE("rooli_hallinnollinen", "Hallinnollinen henkilö");
DEFINE("rooli_kasitteleva", "Käsittelevä viranomainen");
DEFINE("rooli_paattava", "Päättävä viranomainen");
// ROOLI3
DEFINE("rooli_lausunnonantaja", "Lausunnonantaja");
// ROOLI4
DEFINE("rooli_aineistonmuodostaja", "Aineistonmuodostaja");
// ROOLI5
DEFINE("rooli_lupapalvelun_paak", "Lupapalvelun pääkäyttäjä");
DEFINE("rooli_viranomaisen_paak", "Viranomaisen pääkäyttäjä");
DEFINE("rooli_hakija", "Hakija");
// Eettiset roolit
DEFINE("rooli_eettisensihteeri", "Eettisen toimikunnan sihteeri");
DEFINE("rooli_eettisen_puheenjohtaja", "Eettisen toimikunnan puheenjohtaja");
// SUORAT_TUNNISTEET
DEFINE("st_1", "Aineisto sisältää tutkittavan suoran tunnistamisen mahdollistavat tunnistetiedot.");
DEFINE("st_1a", "Perustele tunnistetietojen säilyttäminen aineistossa:");
DEFINE("st_2", "Tutkittavan suoran tunnistamisen mahdollistavat tunnistetiedot korvataan tutkimusnumeroilla (koodilla) ja koodiavain hävitetään heti aineiston muodostamisen jälkeen.");
DEFINE("st_2a", "Koodiavaimen hävittämisestä vastaa:");
DEFINE("st_3", "Tutkittavan suoran tunnistamisen mahdollistavat tunnistetiedot korvataan tutkimusnumeroilla (koodilla) ja koodiavainta säilytetään erillään muusta aineistosta.");
DEFINE("st_3a", "Koodiavaimen säilyttäjä:");
// TIETEENALA
DEFINE("t_agr", "Maatalous- ja metsätieteet");
DEFINE("t_bio", "Biotieteet ja ympäristötieteet");
DEFINE("t_hum", "Humanistiset tieteet");
DEFINE("t_med", "Lääke- ja terveystieteet");
DEFINE("t_nat", "Luonnontieteet");
DEFINE("t_soc", "Yhteiskuntatieteet");
DEFINE("t_tek", "Tekniikka");
DEFINE("t_muu", "Muu, mikä?");
// TOIMENPITEET_PAATTYESSA
DEFINE("tp_1", "Tutkimusaineisto hävitetään kokonaisuudessaan");
DEFINE("tp_2", " (poistettu)");
DEFINE("tp_3", "Tutkimusaineiston rekisterinpitäjällä on arkistolain mukainen arkistointioikeus ja se arkistoi henkilörekisterin oman arkistonmuodostussuunnitelman mukaisesti");
DEFINE("tp_4", "Tutkimusaineiston rekisterinpitäjällä ei ole arkistolain mukaista arkistointioikeutta, mutta se anoo Kansallisarkistolta luvan siirtää henkilörekisterin korkeakoulun taikka tutkimustyötä lakisääteisenä tehtävänä suoritettavan laitoksen ta...");
// VIRANOMAINEN
DEFINE("v_0", " Valitse viranomainen");
DEFINE("v_ETK", "Eläketurvakeskus (ETK)");
DEFINE("v_etk_lyh", "ETK");
DEFINE("v_Fimea", "Fimea, Lääkealan turvallisuus- ja kehittämiskeskus");
DEFINE("v_fimea_lyh", "Fimea");
DEFINE("v_Kela", "Kela, Kansaneläkelaitos");
DEFINE("v_Kela_lyh", "Kela");
DEFINE("v_Maistr", "Maistraatit");
DEFINE("v_maistr_lyh", "Maistraatit");
DEFINE("v_Munuais", "Munuais- ja maksaliitto ry");
DEFINE("v_munuais_lyh", "Musili");
DEFINE("v_Oikeusr", "Oikeusrekisterikeskus");
DEFINE("v_oikeusr_lyh", "Oikeusr.");
DEFINE("v_OPH", "Opetushallitus");
DEFINE("v_oph_lyh", "OPH");
DEFINE("v_PV", "Puolustusvoimat");
DEFINE("v_pv_lyh", "Mil.");
DEFINE("v_STM", "Sosiaali- ja terveysministeriö (STM)");
DEFINE("v_stm_lyh", "STM");
DEFINE("v_STUK", "Säteilyturvakeskus (STUK)");
DEFINE("v_stuk_lyh", "STUK");
DEFINE("v_TEM", "Työ- ja elinkeinoministeriö (TEM)");
DEFINE("v_tem_lyh", "TEM");
DEFINE("v_THL", "Terveyden ja hyvinvoinnin laitos (THL)");
DEFINE("v_thl_lyh", "THL");
DEFINE("v_Tike", "Tike, Maa- ja metsätalousministeriön tietopalvelukeskus");
DEFINE("v_Mavi", "Mavi, Maaseutuvirasto");
DEFINE("v_tike_lyh", "Tike");
DEFINE("v_TK", "Tilastokeskus (TK)");
DEFINE("v_tk_lyh", "TK");
DEFINE("v_Trafi", "Trafi, Liikenteen turvallisuusvirasto");
DEFINE("v_trafi_lyh", "Trafi");
DEFINE("v_TTL", "Työterveyslaitos (TTL)");
DEFINE("v_ttl_lyh", "TTL");
DEFINE("v_Valvira", "Valvira, Sosiaali- ja terveysalan lupa- ja valvontavirasto");
DEFINE("v_valvira_lyh", "Valvira");
DEFINE("v_VH", "Verohallinto");
DEFINE("v_vh_lyh", "Vero");
DEFINE("v_VK", "Valtiokonttori");
DEFINE("v_vk_lyh", "VK");
DEFINE("v_VRK", "Väestörekisterikeskus (VRK)");
DEFINE("v_vrk_lyh", "VRK");
DEFINE("v_VSSHP", "Varsinais-Suomen Sairaanhoitopiiri (VSSHP)");
DEFINE("v_BIO", "Biopankki/Biopankit");
DEFINE("v_KLV", "Käyttölupaviranomainen");
//
// PDF
DEFINE("SIVU", "Sivu");

// Koodistot
DEFINE("KAYTTAJARYHMA_HAKIJA", "rooli_hakija"); 

DEFINE("AINEISTO", "Tutkimusaineisto");
DEFINE("OSITTAIN", "Osittain (aiempi kohdejoukko, jota nyt täydennetään)");
DEFINE("KOHDEJOUKKO_KUVAUS", "Kohdejoukko on niiden  henkilöiden tai muiden yksiköiden joukko, joita tutkimus ensisijaisesti koskee ja joita koskevia tietoja halutaan kerätä (vrt. viitehenkilöt).");
DEFINE("KJ_TV_KUVAILU", "Kohdejoukko on niiden  henkilöiden tai muiden yksiköiden joukko, joita tutkimus ensisijaisesti koskee ja joita koskevia tietoja halutaan kerätä. Kohdejoukon yksiköt voivat olla henkilöitä, mutta myös esimerkiksi yrityksiä, alueita, organisaatioita tms.");
DEFINE("VERROKIT", "Verrokit");
DEFINE("VIITEHENKILOT", "Viitehenkilot");
DEFINE("VIITEHENKILOT2", "viitehenkilot");
// Uudet alkavat (suvin setti 18.11.2016)

// Uudet päättyvät (suvin setti 18.11.2016)

DEFINE("TIETOLAHTEET", "Tietolähteet");
DEFINE("TIETOLAHDE_OTSIKKO", "Mihin muuhun tietolähteeseen perustuu kohdejoukon (tai tapausten ja verrokkien) ja mahdollisten viitehenkilöiden tunnistaminen tai määrittely?");
DEFINE("TIETOLAHDE_OTSIKKO_2", "Mihin tietolähteeseen perustuu kohdejoukon (tai tapausten ja verrokkien) ja mahdollisten viitehenkilöiden tunnistaminen tai määrittely?");
DEFINE("TL_KOHDE", "Tietolähde, josta kohdejoukko tunnistetaan tai poimitaan");
DEFINE("TL_TAP", "Tietolähde, josta tapaukset tunnistetaan tai poimitaan");
DEFINE("TL_VERR", "Tietolähde, josta verrokit tunnistetaan tai poimitaan");
DEFINE("TL_VIITE", "Tietolähde, josta viitehenkilöt tunnistetaan tai poimitaan");
DEFINE("BIOPANKKIAINEISTOT", "Biopankkiaineistot");
DEFINE("TERV_HUOL_ASKI", "Terveydenhuollon asiakirjat");
DEFINE("BIO_MAAR_KOHD", "Biopankkitiedoista määriteltävä kohdejoukko (tai tapaukset ja verrokit)");
DEFINE("TUTK_KOHDEJOUKKO", "Tutkimuksen kohdejoukko (tai tapaukset  (ja verrokit)) poimitaan");
DEFINE("KOHD_MK", "Kohdejoukon (tai tapausten ja mahdollisten verrokkien) mukaanottokriteerit");
DEFINE("TERV_KOHDE", "Terveydenhuollon asiakirjoista määriteltävä kohdejoukko (tai tapaukset ja verrokit)");
DEFINE("TKPSTT", "Tutkimuksen kohdejoukko (tai tapaukset  (ja verrokit)) poimitaan seuraavista terveydenhuollon toimintayksiköistä:");
DEFINE("TKPSST", "Tutkimuksen kohdejoukko (tai tapaukset  (ja verrokit)) poimitaan seuraavista sosiaalihuollon toimintayksiköistä:");
DEFINE("TOIM_YHT", "Toimintayksiköihin on oltu yhteydessä");
DEFINE("KOHD_POIM", "Määrittele kohdejoukolle poimittavat tiedot ja/tai näytteet");
DEFINE("RTBA", "Rekisteri/Tilastoaineisto/Biopankkiaineisto/Asiakirjat");

// Biopankit
DEFINE("AURIA", "Auria Biopankki");
DEFINE("THL_BIO", "THL Biopankki");
DEFINE("FHRB", "Suomen Hematologinen Rekisteri ja Biopankki FHRB");
DEFINE("HKI_BIO", "Helsingin Biopankki");
DEFINE("BOREALIS", "Pohjois-Suomen Biopankki Borealis");
DEFINE("TMPR_BIO", "Tampereen Biopankki");
DEFINE("ITAS_BIO", "Itä-Suomen Biopankki");
DEFINE("KESK_BIO", "Keski-Suomen Biopankki");
DEFINE("VERIP_BIO", "Veripalvelun Biopankki");

// Uudet (Suvi 29.5.2017)

// Yleisiä
DEFINE("SUOMEKSI", "Suomeksi");
DEFINE("RUOTSIKSI", "Ruotsiksi");
DEFINE("ENGLANNIKSI", "Englanniksi");

// Ohjeet
DEFINE("OHJEET", "Ohjeet");
DEFINE("MITEN_HAEN", "Miten haen tai tallennan tietoja?");
DEFINE("KIRJ_HAKEAKSESI", "Jotta tietoja pääsee hakemaan tai tallentamaan, täytyy kirjautua. Painike löytyy oikeasta ylälaidasta.");
DEFINE("ONGELMATIL", "Ongelmatilanteet");
DEFINE("TEE_TUKI", "Tee tukipyyntö.");

// Hallinta > Lomakkeet > Uusi lomake
DEFINE("HALLINTA", "Hallinta");
DEFINE("UUSI_LOMAKE", "Uusi lomake");
DEFINE("SIVUN_TEKSTI", "Sivun teksti (näytetään sivulla ylimpänä)");
DEFINE("KYSYMYSKOK", "Kysymyskokonaisuus");
DEFINE("KYSYMYSKOK_POISTA", "Poista kysymyskokonaisuus");
DEFINE("KYSYMYSKOK_LISAA", "Lisää uusi kysymyskokonaisuus");
DEFINE("KYSYMYSKOK_TALLENNA", "Tallenna uusi kysymyskokonaisuus");
DEFINE("KYSYMYSKOK_LISAA_INFO", "Kysymyskokonaisuudet ovat lomakkeen sivun osioita ja niitä voidaan käyttää jakamaan kysymykset loogisiin kokonaisuuksiin ilman, että tarvitsee tehdä jokaiselle aihepiirille omaa lomakkeen sivua.");
DEFINE("KYSYMYSKOK_NIMI", "Kysymyskokonaisuuden nimi");
DEFINE("KYSYMYSKOK_OTS", "Kysymyskokonaisuuden otsikko");
DEFINE("KYSYMYS_LISAA", "Lisää uusi kysymys");
DEFINE("KYSYMYS_TALLENNA", "Tallenna uusi kysymys");
DEFINE("KYSYMYS_TYYPPI", "Kysymyksen tyyppi");
DEFINE("KYSYMYS_TYYPPI_INFO", 
"<ul>
<li>Lyhyt ja pitkä tekstivastaus</li>
<li>Ajanjakso</li>
<li>Valintaruutu, jossa vastaaja voi rastittaa vastaukseensa useita eri vaihtoehtoja ennalta määrätyistä vaihtoehdoista.</li>
<li>Valintapainike, jossa vastaaja voi valita vain yhden vastausvaihtoehdon ennalta määrätyistä vaihtoehdoista.</li>
<li>Tutkimuksen/hankkeen nimi, joka näkyy ???</li>
<li>Taulukko, johon voidaan määritellä vastaukset taulukkomuotoon. Esim. Nimi, osoite, puhelinnumero.</li>
</ul>");
DEFINE("K_T_LYHYT", "Lyhyt tekstivastaus");
DEFINE("K_T_PITKA", "Pitkä tekstivastaus");
DEFINE("K_T_PVM", "Päivämäärä (jakso)");
DEFINE("K_T_CHECK", "Valintaruutu (voi valita useita vaihtoehtoja)");
DEFINE("K_T_RADIO", "Valintapainike (voi valita yhden vaihtoehdon)");
DEFINE("K_T_TUTNIMI", "Tutkimuksen/hankkeen nimi");
DEFINE("K_T_TAULUKKO", "Taulukko");
DEFINE("KYSYMYS_TEKSTI", "Kysymyksen teksti");
DEFINE("KYSYMYS_PAK", "Pakollinen kysymys");
DEFINE("KYSYMYS_INFO", "Kysymyksen infoteksti");
DEFINE("VAST_TEKSTI", "Vastausvaihtoehdon teksti");
DEFINE("VAIHTOEHT_POISTA", "Poista vaihtoehto");
DEFINE("VAIHTOEHT_LISAA", "Lisää uusi vaihtoehto");
DEFINE("SIVU_PERUST", "Sivun perustiedot");
DEFINE("SIVU_NIMI", "Sivun perustiedot");
DEFINE("KYSYMYSKOK_LAJITTELU", "Sivun kysymyskokonaisuuksien lajittelu");

// Hallinta > Lomakkeet > Uusi lomake > Liitetiedostot-sivu
DEFINE("LIITE_SIVU", "Liitetiedostot-sivu");
DEFINE("LIITE_LISAA", "Lisää uusi liitetiedosto");
DEFINE("LIITE_TALLENNA", "Tallenna liitetiedosto");
DEFINE("LIITE_POISTA", "Poista liitetiedosto");
DEFINE("LAHETA_LIITE", "Lähetä liitetiedostoja");
DEFINE("LIITE_MUOKKAUS", "Liitetiedostojen muokkaus");
DEFINE("LIITETIEDOSTO_P", "liitetiedosto");
DEFINE("LIITE_NIMI", "Liitetiedoston nimi (esim. \"Rekisteriseloste\"):");
DEFINE("LIITE_NIMI2", "Liitetiedoston nimi");
DEFINE("LIITE_ASIAK_TAR", "Liitetiedoston asiakirjan tarkenne");
DEFINE("LIITE_LISATIE", "Liitetiedoston lisätiedot");
DEFINE("TIED_HYV", "Hyväksytyt tiedostotyypit");
DEFINE("KAIKKI", "Kaikki");
DEFINE("KAIKKI_TXT", "Kaikki tekstitiedostot");
DEFINE("KAIKKI_IMG", "Kaikki kuvatiedostot");
DEFINE("LIITE_PAK", "Liitetiedoston pakollisuus");
DEFINE("LIITE_PAK_EI", "Ei pakollinen liitetiedosto");
DEFINE("LIITE_PAK_KYLLA", "Pakollinen liitetiedosto");
DEFINE("LIITE_PAK_EHTO", "Ehdollisesti pakollinen liitetiedosto");
DEFINE("LIITE_PAK_KYSYMYS", "Lomakkeen vastaus, johon liitetiedosto sidotaan");

// Ehtolauseet
DEFINE("LIITE_PAK_VASTAUS", "Vastauksen ehto");
DEFINE("LIITE_PAK_V_ARVO", "Vastauksen arvo");
DEFINE("V_SAMA", "Vastaus on sama kuin");
DEFINE("V_EISAMA", "Vastaus ei ole sama kuin");
DEFINE("V_PIEN", "Vastaus on pienempi kuin");
DEFINE("V_SUUR", "Vastaus on suurempi kuin");
DEFINE("V_VALITTU", "Vastaus on valittu / vastaus ei ole tyhjä");
DEFINE("V_TYHJA", "Vastaus ei ole valittu / vastaus on tyhjä");

// Hallinta > Lomakkeet > Uusi lomake > Kysymysten väliset suhteet
DEFINE("OSIO", "Osio");
DEFINE("KYS_VAL_SUHT", "Kysymysten väliset suhteet");
DEFINE("KYS_VAL_SUHT_INFO", "Kysymysten välisillä riippuvuuksilla tai suhteilla voidaan määritellä, mitä tapahtuu, jos lomakkeen tiettyyn kysymykseen vastataan tietyllä tavalla. Esimerkiksi elämäntapatutkimuksessa voidaan piilottaa alkoholia koskevat kysymykset, jos vastaaja ilmoittaa iäkseen alle 18 vuotta.");
DEFINE("KYS_VAL_SUHT_LISAA", "Lisää uusi kysymysten välinen suhde");
DEFINE("KYS_VAL_SUHT_LISAA_INFO", 
"Alla olevassa esimerkissä piilotetaan alkoholinkäyttöä koskeva kysymys, jos vastaaja on alle 18-vuotias.
<ol>
<li><strong>Kysymyksen vastaus, johon riippuvuus sidotaan:</strong> Vastaus \"alle 18 v.\"</li>
<li><strong>Vastauksen ehto:</strong> \"Vastaus on valittu\"</li>
<li><strong>Kysymysten välisen suhteen toiminto:</strong> \"Piilota\"</li>
<li><strong>Kohde, johon luotu suhde vaikuttaa:</strong> \"Kysymys: Käytätkö alkoholia?\"</li>
</ol>");
DEFINE("KYS_VAL_SUHT_POISTA", "Poista kysymysten välinen suhde");
DEFINE("KYS_VAS_SUHDE", "Kysymyksen vastaus, johon riippuvuus sidotaan");
DEFINE("KYS_VAL_SUHT_TOIM", "Kysymysten välisen suhteen toiminto");
DEFINE("KYS_VAL_SUHT_KOHDE", "Kohde, johon luotu suhde vaikuttaa");
DEFINE("KYSYMYS", "Kysymys");
DEFINE("LAHDEKYSYMYS", "Lähdekysymys");
DEFINE("KOHDEKYSYMYS", "Kohdekysymys");
DEFINE("RIIPPUVUUSSAANTO", "Riippuvuussääntö");
DEFINE("RIIPPUVUUSSAANTO_TALL", "Tallenna uusi riippuvuussääntö");

// Toiminnot
DEFINE("K_NAYTA", "Näytä kysymys");
DEFINE("K_PIILOTA", "Piilota kysymys");
DEFINE("K_TYHJENNA", "Tyhjennä kysymys");
DEFINE("O_NAYTA", "Näytä osio");
DEFINE("O_PIILOTA", "Piilota osio");
DEFINE("O_TYHJENNA", "Tyhjennä osio");
DEFINE("NAYTA", "Näytä");
DEFINE("PIILOTA", "Piilota");
DEFINE("TYHJENNA", "Tyhjennä");

// Pääkäyttäjän käyttöliittymä > Lomakkeiden hallinta
DEFINE("LOM_HALL", "Lomakkeiden hallinta");
DEFINE("LOM_NIMI", "Lomakkeen nimi");
DEFINE("TEKIJA", "Tekijä");
DEFINE("LISAYSPVM", "Lisäyspvm");
DEFINE("LOM_UUSI", "Luo uusi lomake");
DEFINE("LOM_MUOKKAA", "Muokkaa lomaketta");
DEFINE("LOM_POISTA", "Poista lomake");
DEFINE("LOM_POISTA_ILM", "Haluatko varmasti poistaa lomakkeen?");

// Hallinta > Lomakkeet > Uusi lomake > Lisää uusi lomakkeen sivu
DEFINE("LOM_UUSI_SIVU", "Lisää uusi lomakkeen sivu");
DEFINE("LOM_UUSI_SIVU_INFO", 
	"<p>Käyttölupapalveluun luotavat lomakkeet koostuvat lomakkeen sivuista aivan kuten paperilomakkeissa. Lomakkeen sivuille määritetään:</p>
	<ol>
		<li>Järjestysluku, joka määrittää, missä järjestyksessä sivut esitetään.</li>
		<li>Sivupohja, joka määrittää, onko luotava sivu tavallinen lomakkeen sivu vai liitetiedostojen lähettämiseen tarkoitettu sivu.</li>
		<li>Sivun nimi suomeksi, englanniksi ja ruotsiksi. Sivun nimi näkyy hakijalle lomakkeen navigoinnissa.</li>
	</ol>
	<p>Voit poistua poistua uuden sivun luomisesta painamalla \"Peruuta\". Kun olet täyttänyt uuden sivun tiedot, paina \"Tallenna\".</p>");
DEFINE("LOM_UUSI_SIVU_TALLENNA", "Tallenna uusi lomakkeen sivu");
DEFINE("JARJESTYS", "Järjestys");
DEFINE("JÄRJESTYS", "Järjestys");
DEFINE("SIVUPOHJA", "Sivupohja");
DEFINE("NAME", "Name");
DEFINE("NAMN", "Namn");
DEFINE("UUSI_SIVU", "Uusi sivu");
DEFINE("LOM_PERUST", "Lomakkeen perustiedot");
DEFINE("LOM_TYYPPI", "Lomakkeen tyyppi");
DEFINE("LOM_ASIAK_TYYPPI", "Lomakkeen asiakirjatyyppi");
DEFINE("LOM_ASIAK_TYYPPI_TARK", "Lomakkeen asiakirjatyypin tarkenne");
DEFINE("HAK_LOM_LISAT", "Hakemuslomakkeen lisätiedot");
DEFINE("PAINIKE", "Painikkeen teksti (uusi hakemus)");
DEFINE("LOM_SIVUT", "Lomakkeen sivut");
DEFINE("POISTA_SIVU", "Poista sivu");
DEFINE("LISAA_SIVU", "Lisää uusi sivu");
DEFINE("LOM_TALLENNA", "Tallenna lomake");

// Security
DEFINE("ODOTA_OLE_HYVA", "Odota ole hyvä");
// shown on 'invite accepted ok' page if the invite is accepted by not-yet-registered user, and followed by REKISTEROIDY_MENU link
DEFINE("REKISTEROI_OLE_HYVA", "Please register to continue: ");


?>
