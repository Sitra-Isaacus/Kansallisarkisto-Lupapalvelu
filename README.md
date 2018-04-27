# Kansallisarkisto-Lupapalvelu

Lupapalvelu on järjestelmä, jonka avulla tutkija voi hakea käyttölupaa sosiaali- ja terveysalan rekisteri- ja tilastoaineistoihin yhdellä sähköisellä hakemuksella, tarvittaessa usealta viranomaiselta samanaikaisesti. Jokainen viranomainen käsittelee tutkijan jättämän hakemuksen omien aineistojensa osalta ja tekee siitä päätöksen järjestelmään. Hakemuksen tilaa voi seurata palvelusta. Tutkija ja viranomaiset voivat hoitaa hakemusta koskevan viestinvaihdon palvelun kautta. Hakija saa tiedon päätöksestä sähköpostiinsa, päätös on luettavissa palvelusta. Käyttöluvan myöntämisen jälkeen myös aineistopyynnön voi tehdä palvelussa luvan myöntäneelle viranomaiselle. Myös mahdollisten myöhempien muutoshakemusten käsittely voidaan vastaavalla tavalla hoitaa palvelussa. Lupapalvelun yhteydessä on pilottina toteutettu eettisen arvioinnin sähköinen järjestelmä.

# Riippuvuudet

•	PHP (>=5.3.3):

  o	php
  o	php-cli
  o	php-common
  o	php-mysql
  o	php-pdo
  o	php-soap
  o	php-xml
  o	php-mbstring
  o	php-memcached (https://github.com/php-memcached-dev/php-memcached)

•	MySQL (>=5.6.39)

•	Linux-paketit (CentOS):

  o	mail
  o	sendmail
  o	sendmail-cf
  o	m4
  o	libxml2
  o	libxslt

Seuraavat kirjastot on sisällytetty git-säiliöön ja niitä ei tarvitse asentaa erikseen:

•	PHP WSDL Creator (https://github.com/piotrooo/wsdl-creator)

•	TCPDF PHP PDF Library (https://tcpdf.org/)

•	Html2pdf (https://github.com/spipu/html2pdf)

•	jQuery (>= 1.10.2)

  o	Datepicker (https://jqueryui.com/datepicker/)
  
  # Asennus
  
I.	Lataa lupapalvelun lähdekoodit asennuspalvelimelle. Jos asennat lupapalvelun hajautetusti useammalle palvelimelle (eri ohjelmistokerrokset eri palvelimelle), niin hae kansiot eri palvelimille seuraavasti:

    a)	data-kansio tietokantapalvelimelle
    b)	logic-kansio logiikkapalvelimelle
    c)	presentation-kansio käyttöliittymäpalvelimelle

II.	Siirrä data-kansion, logic-kansion ja presentation-kansion sisällöt kunkin palvelimen web-kansion alle (yleensä oletusarvoisesti /var/www/html).

III.	Kaikilla palvelimilla tulee olla määritelty sama aikavyöhyke /etc/php.ini -tiedostossa:

date.timezone = "Europe/Helsinki”

IV.	Luo lupapalvelun tietokanta suorittamalla data-kansiossa sijaitseva lupapalvelu.sql-skripti komennolla:

mysql -u root -p tietokannan_nimi < lupapalvelu.sql

V.	Muokkaa lupapalvelun konfiguraatiotiedostoja:

    a)	data/_config.php:

    define("DATA_SERVER", "192.168.104.250/lupapalvelu/data/");

    Korvaa rivin oletusarvo tietokantapalvelimesi IP-osoitteella tai DNS-nimellä sekä oikealla kansion nimellä (josta       fmas_db_api.php-tiedosto löytyy).

    define("DB_DATABASE_NAME", "lupapalvelu");
    define("DB_USER_NAME", "KÄYTTÄJÄTUNNUS");
    define("DB_PASSWORD", "SALASANA");

    Määritä riveille asettamasi tietokannan nimi (oletusarvoisesti ”lupapalvelu”), tietokannan käyttäjänimi sekä tietokannan käyttäjätunnuksen salasana.

    b)	logic/ _config.php:

    define("LOGIC_SERVER", "192.168.104.250/lupapalvelu/logic/");

    Korvaa rivin oletusarvo logiikkapalvelimesi IP-osoitteella tai DNS-nimellä sekä oikealla kansion nimellä (josta fmas_business_logic.php-tiedosto löytyy).

    define("PRESENTATION_SERVER", "192.168.104.250/lupapalvelu/presentation/");

    Korvaa rivin oletusarvo käyttöliittymäpalvelimesi IP-osoitteella tai DNS-nimellä sekä oikealla kansion nimellä (josta index.php löytyy).

    riville define("DATA_SERVER", "192.168.104.250/lupapalvelu/data/");

    Korvaa rivin oletusarvo tietokantapalvelimesi IP-osoitteella tai DNS-nimellä sekä oikealla kansion nimellä.

    c)	presentation/_soap_config.php

    define("LOGIC_SERVER", "192.168.104.250/lupapalvelu/logic/"); 

    Korvaa rivin oletusarvo (192.168.104.250/lupapalvelu/logic/) logiikkapalvelimesi IP-osoitteella tai DNS-nimellä sekä oikealla kansion nimellä.

VI.	Muokkaa palvelimen sähköpostipalvelun (Sendmail) asetuksia, jotta lupapalvelu voi lähettää sähköpostiviestejä ulkoverkkoon. Sähköpostia lähettävän logiikkakerroksen palvelimen paikallisessa palomuurissa tulee sallia liikenne portin 25 kautta.

VII.	Käynnistä web-selain ja kirjoita osoiteriville käyttöliittymäpalvelimen IP-osoite tai DNS-nimi (ja asennuskansion nimi, mikäli käyttöliittymä ei sijaitse www-kansion juuressa). Tällöin sinun pitäisi päätyä lupapalvelun kirjautumissivulle.

# Tunnetut bugit ja huomioon otettavaa

•	Pääkäyttäjän lomake-editori on beta-vaiheessa oleva osa ohjelmistoa, joka vaatii jatkokehittämistä. 
•	PDF-muotoisesta eettisestä lausuntopyynnöstä puuttuu osa lomakkeen tiedoista. 
•	Käyttöliittymän lopullinen visuaalinen ilme on suunnittelematta ja se ei mukaudu mobiililaitteille.

# Yhteystiedot 

Sitra - https://www.sitra.fi/

# Lisenssi

Käyttölupapalvelu on lisensioitu Apache-2.0 lisenssillä.
