// FMAS Front end JavaScript functions
// Created 17.2.2016

var idleTime = 0;

// Sivun käynnistysfunktiot
window.onload = function(){

	$( function() { // Syntymäajat
		$( "#kalenteri" ).datepicker({ changeMonth: true, changeYear: true, yearRange: "-80:+0" });
	});

	$('.aika_laatikko').each(function(){ // Lomakkeiden kalenterit
		$(this).datepicker({ changeMonth: true, changeYear: true, yearRange: "-20:+20" });
	});

	$( function() { // Lomakkeiden kalenterit: aloitus- ja lopetuspvm:t eivät konfliktoi
	
		var dateFormat = "dd.mm.yy";
		var alku = $('.alku').each(function(){
			$(this).on( "change", function() { loppu.datepicker( "option", "minDate", getDate( this ) ); });
		});
		
		var loppu = $('.loppu').each(function(){
			$(this).on( "change", function() { alku.datepicker( "option", "maxDate", getDate( this ) ); });
		});

		function getDate( element ) {
			var date;
			try { date = $.datepicker.parseDate( dateFormat, element.value ); } catch( error ) { date = null; }
			return date;
		}
		
	} );

	// Ilmoituslaatikot
	var close = document.getElementsByClassName("closebtn"), j;
	for (j = 0; j < close.length; j++) {
		close[j].onclick = function(){
			var div = this.parentElement;
			div.style.opacity = "0";
			setTimeout(function(){ div.style.display = "none"; }, 600);
		}
	}

	window.varoita_jos_sivulta_poistutaan = false;
	
	if(document.getElementById("alert_success")!=null){
		if(document.getElementById("alert_success").style.display == "block"){
			$("#alert_success").show().delay(3000).fadeOut();
		}
	}
	
	if(document.getElementById("alert")!=null){
		if(document.getElementById("alert").style.display == "block"){
			$("#alert").show().delay(5500).fadeOut();
		}
	}
	
	if(document.getElementById('hakemus_button')!=null){
		document.getElementById('hakemus_button').onclick = function(){
			var x = document.getElementById("hakemus_menu_div");
			if (x.className.indexOf("w3-show") == -1) {
				x.className += " w3-show";
				x.previousElementSibling.className += " w3-grey";
			} else { 
				x.className = x.className.replace(" w3-show", "");
				x.previousElementSibling.className = 
				x.previousElementSibling.className.replace(" w3-grey", "");
			}
		};
	}

	if(document.getElementById('viranomaisen_ryhmakoodit') != null && typeof document.getElementById('viranomaisen_ryhmakoodit') != 'undefined'){
		document.getElementById('viranomaisen_ryhmakoodit').onchange = function(){
			if(this.value=="rooli_hakija"){
				window.location.href="index.php";
			}
			if(this.value=="rooli_eettisen_puheenjohtaja" || this.value=="rooli_eettisensihteeri" || this.value=="rooli_paattava" || this.value=="rooli_kasitteleva"){
				window.location.href="viranomainen_saapuneet_hakemukset.php?kayttajarooli=" + this.value + "";
			}
			if(this.value=="rooli_aineistonmuodostaja"){
				window.location.href="aineistonmuodostaja_saapuneet_tilaukset.php";
			}
			if(this.value=="rooli_lausunnonantaja"){
				window.location.href="lausunnonantaja_saapuneet_lausuntopyynnot.php";
			}
			if(this.value=="rooli_viranomaisen_paak"){
				window.location.href="kayttajaroolit.php?kayttajarooli=" + this.value + "";
			}
		};
	}
	
    var idleInterval = setInterval(timerIncrement, 60000); // 1 min

    // Nollataan idle timeri jos hiirtä liikutetaan / näppäintä painetaan
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
	
    $(this).keypress(function (e) {
        idleTime = 0;
    });	

	var sivun_nimi = window.location.pathname.split("/").slice(-1)[0];

	if(sivun_nimi == "kirjaudu.php"){
		document.getElementById("kirjaudu_form").addEventListener("submit", tarkistalomake);
		valitse('tun');
	}
	
	if(sivun_nimi == 'lisaa_kayttaja.php'){  
		document.getElementById('lisaa_viranomainen').onclick = function(){
			var sahkopostiosoite = document.getElementById('sahkopostiosoite').value;
			var valitut_roolit = [];
			var roolit = document.getElementsByName('rooli');
			var r = 0;
			for(var i=0; i < roolit.length; i++){
				if(roolit[i].checked){
					valitut_roolit[r] = roolit[i].value;
					r++;
				}
			}
			if(typeof document.getElementById('viranomaiset') != "undefined" && document.getElementById('viranomaiset') != null){ 
				var viranomainen = document.getElementById('viranomaiset').value;
			} else {
				var viranomainen = "ei_viranomaista";
			}
			if(sahkopostiosoite != null && viranomainen != null && valitut_roolit[0] != null){
				$.ajax({
					url: window.location,
					type: "POST",
					dataType: 'json',
					data: { tallennettavan_kayttajatunnus: sahkopostiosoite, tallennettavan_vir_koodi: viranomainen, tallennettavan_roolit: valitut_roolit},
					success: function(data) {
						for(var i=0; i < data.length; i++){
							if(data[i].Kayttaja_lisatty){
								piilotaElement("kayttajan_lisays");
								naytaElement("kayttaja_lisatty");
							} 
						}
					}
				});
			} else {
				alert("Täytä puuttuvat tiedot.");
			}
		};
	}

	if(sivun_nimi == 'kayttajaroolit.php'){ 
		role_input = document.getElementsByClassName("kayttajaroolit");
		for(k=0; k < role_input.length; k++) {	 
			role_input[k].onclick=function() { 
				var rooli = this.value;
				if(this.checked){
					var rooli_checked = "valittu"; 
				} else {
					var rooli_checked = "ei_valittu"; 
				}
				var input_id = this.id;
				var input_id_s = input_id.split("-");
				var tallennettava_rooli = input_id_s[0];
				var tallennettava_kayt = input_id_s[1];
				var tallennettava_vir = input_id_s[2];
				if(typeof input_id_s[3] != "undefined"){
					var tallennettava_vir_koodi = input_id_s[3];
					$.ajax({
						url: window.location,
						type: "POST",
						dataType: 'json',
						data: { fk_kayttaja: tallennettava_kayt, fk_viranomainen: tallennettava_vir, kayttaja_rooli: rooli, rooli_valittu: rooli_checked, viranomaisen_koodi: tallennettava_vir_koodi  },
						success: function(data) {
							$("#tallennettu_info").show().delay(3000).fadeOut();
							if(rooli_checked=="valittu"){
								document.getElementById(input_id).id = tallennettava_rooli + "-" + tallennettava_kayt + "-" + data.viranomaisenUusiID + "-" + tallennettava_vir_koodi;
							} 
							if(rooli_checked=="ei_valittu"){
								document.getElementById(input_id).id = tallennettava_rooli + "-" + tallennettava_kayt + "-0-" + tallennettava_vir_koodi;
							}
						}
					});
				} else {
					$.ajax({
						url: window.location,
						type: "POST",
						dataType: 'json',
						data: { fk_kayttaja: tallennettava_kayt, fk_viranomainen: tallennettava_vir, kayttaja_rooli: rooli, rooli_valittu: rooli_checked },
						success: function(data) {
							$("#tallennettu_info").show().delay(3000).fadeOut();
							if(rooli_checked=="valittu"){
								document.getElementById(input_id).id = tallennettava_rooli + "-" + tallennettava_kayt + "-" + data.viranomaisenUusiID;
							} 
							if(rooli_checked=="ei_valittu"){
								document.getElementById(input_id).id = tallennettava_rooli + "-" + tallennettava_kayt + "-0";
							}
						}
					});
				}
			}
		}
	}

	if(sivun_nimi == "hakemus.php" || sivun_nimi == "paatos.php" || sivun_nimi=="lausunto.php" || sivun_nimi=="metatiedot.php"){

		paivita_osiot(osiotDTO);
		form_input_elementit = document.getElementsByClassName("form_input");
		form_nappi_elementit = document.getElementsByClassName("form_nappi");

		for(c=0; c < form_input_elementit.length; c++) {
			form_input_elementit[c].onchange=function() {
				form_input_onchange(this);
			}
		}

		for(c=0; c < form_nappi_elementit.length; c++) {
			form_nappi_elementit[c].onclick=function() {
				form_nappi_onclick(this);
			}
		}

		// Näytetään merkkien lukumäärä
		$('textarea').keyup(function(){
			if($(this).attr('maxlength')){

				if(this.value.length > $(this).attr('maxlength')){
					return false;
				} 

				var merkkeja_jaljella = "Merkkejä jäljellä :";
				
				if(typeof kayttaja_kieli !== 'undefined') { 
					if(kayttaja_kieli=="en") merkkeja_jaljella = "Characters remaining :";
				}
				
				$(this).next().html(merkkeja_jaljella + " " + ($(this).attr('maxlength') - this.value.length));

			}
		});

		if(sivun_nimi=="hakemus.php"){
			
			var ain_lisatieto = document.getElementsByClassName('aineisto_lisatiedot_click');
			for (k=0; k < ain_lisatieto.length; k++) {
				ain_lisatieto[k].onclick = function(){
					var id = this.id.split("-")[1];
					if(document.getElementById("tk_muuttujat_aineisto_sisalto-" + id).style.display=="none"){
						naytaElement("tk_muuttujat_aineisto_sisalto-" + id);
						piilotaElement("aineisto_lisatiedot_expand-" + id);
						naytaElement("aineisto_lisatiedot_collapse-" + id);
					}  else {
						piilotaElement("tk_muuttujat_aineisto_sisalto-" + id);
						naytaElement("aineisto_lisatiedot_expand-" + id);
						piilotaElement("aineisto_lisatiedot_collapse-" + id);
					}
				};
			}
			
			var poim_muuttujat = document.getElementsByClassName('poimi_muuttujat_click');
			
			for (k=0; k < poim_muuttujat.length; k++) {
				poim_muuttujat[k].onclick = function(){
					var id = this.id.split("-")[1];
					if(document.getElementById("taika_muuttuja_poiminta_sisalto-" + id).style.display=="none"){
						naytaElement("taika_muuttuja_poiminta_sisalto-" + id);
						piilotaElement("poimi_muuttujat_expand-" + id);
						naytaElement("poimi_muuttujat_collapse-" + id);
					}  else {
						piilotaElement("taika_muuttuja_poiminta_sisalto-" + id);
						naytaElement("poimi_muuttujat_expand-" + id);
						piilotaElement("poimi_muuttujat_collapse-" + id);
					}
				};
			}
			
		}

		if(sivun_nimi=="lausunto.php"){
			if(document.getElementById('laus_ehto')!=null){
				document.getElementById('laus_ehto').onclick = function(){
					naytaElement("ehdollinen_puoltaminen");
					piilotaElement("johtopaatoksen_perustelut");
				};
			}
			if(document.getElementById('laus_kylla')!=null){
				document.getElementById('laus_kylla').onclick = function(){
					piilotaElement("ehdollinen_puoltaminen");
					piilotaElement("johtopaatoksen_perustelut");
				};
			}
			if(document.getElementById('laus_ei')!=null){
				document.getElementById('laus_ei').onclick = function(){
					piilotaElement("ehdollinen_puoltaminen");
					naytaElement("johtopaatoksen_perustelut");
				};
			}
		}

		if(sivun_nimi=="paatos.php"){
			if(document.getElementById('paat_tila_hyvaksytty')!=null){
				document.getElementById('paat_tila_hyvaksytty').onclick = function(){
					naytaElementClass('paatos_hyvaksytty');
					piilotaElementClass("paatos_hylatty");
					piilotaElementClass("ehdollinen_paatos");
				}
			}
			if(document.getElementById('paat_tila_ehd_hyvaksytty')!=null){
				document.getElementById('paat_tila_ehd_hyvaksytty').onclick = function(){
					naytaElementClass('ehdollinen_paatos');
				}
			}			
			if(typeof document.getElementById('paat_tila_hylatty') != 'undefined' && document.getElementById('paat_tila_hylatty') != null){
				document.getElementById('paat_tila_hylatty').onclick = function(){
					piilotaElementClass('paatos_hyvaksytty');
					naytaElementClass("paatos_hylatty");
					piilotaElementClass("ehdollinen_paatos");
				};
			}
			if(typeof document.getElementById('pj_hyvaksynta_vaaditaan') != 'undefined' && document.getElementById('pj_hyvaksynta_vaaditaan') != null){ 
				document.getElementById('pj_hyvaksynta_vaaditaan').onclick = function(){ 
					naytaElement("pj_valinta");
					naytaElement("laheta_hyv_pyynto");
					piilotaElement("laheta_eettinen_lausunto");
				}
			}
			if(typeof document.getElementById('pj_hyvaksyntaa_ei_vaadita') != 'undefined' && document.getElementById('pj_hyvaksyntaa_ei_vaadita') != null){ 
				document.getElementById('pj_hyvaksyntaa_ei_vaadita').onclick = function(){ 
					piilotaElement("pj_valinta");
					naytaElement("laheta_eettinen_lausunto");
					piilotaElement("laheta_hyv_pyynto");					
				}
			}			
		}

	}

	if(sivun_nimi == "lomake_perustiedot.php"){
		document.getElementById('lomake_sivu-lisaa').onclick = function(){
			naytaInlineElementClass("uusi_sivu_lisays");
			piilotaElementClass("lom_perustiedot");
		}
		document.getElementById('peruuta_sivun_lisays').onclick = function(){
			piilotaElementClass("uusi_sivu_lisays");
			naytaInlineElementClass("lom_perustiedot");
			if(document.getElementById('lomakkeen_tyyppi').value!="Hakemus"){
				piilotaElement("hakemuslom_lisatiedot");
			}
		}
		document.getElementById('lomakkeen_tyyppi').onchange = function(){
			if(this.value=="Hakemus"){
				naytaElement("hakemuslom_lisatiedot");
				naytaElement("lomakkeen_sivut_box");
			} else {
				piilotaElement("hakemuslom_lisatiedot");
				piilotaElement("lomakkeen_sivut_box");
			}
			if(this.value=="Päätös"){
				naytaElement("paatoslom_lisatiedot");
			} else {
				piilotaElement("paatoslom_lisatiedot");
			}
		}
		varoita_jos_formien_tietoja_muutetaan("form_lomake_perustiedot");
	}
	
	if(sivun_nimi == "lomake_suhteet.php"){ 
		document.getElementById('lisaa_uusi_riippuvuussaanto').onclick = function(){  
			piilotaElementClass("lomake_riippuvuussaanto");
			naytaElementClass("uusi_riippuvuussaanto");
		}
		document.getElementById('peruuta_saannon_lisays').onclick = function(){  
			naytaElementClass("lomake_riippuvuussaanto");
			piilotaElementClass("uusi_riippuvuussaanto");
		}
	}
	
	if(sivun_nimi == "lomake_sivu.php"){
		
		var kys_otsikko = document.getElementsByClassName('kysymys_otsikko_click');
		for (k=0; k < kys_otsikko.length; k++) {
			kys_otsikko[k].onclick = function(){
				var kyss_id = this.id.split("-")[1];
				if(document.getElementById("kysymys_sisalto-" + kyss_id).style.display=="none"){
					naytaElement("kysymys_sisalto-" + kyss_id);
					piilotaElement("kysymys_otsikko_nayta-" + kyss_id);
					naytaElement("kysymys_otsikko_piilota-" + kyss_id);
				}  else {
					piilotaElement("kysymys_sisalto-" + kyss_id);
					naytaElement("kysymys_otsikko_nayta-" + kyss_id);
					piilotaElement("kysymys_otsikko_piilota-" + kyss_id);
				}
			};
		}
		
		if(document.getElementById('uusi_liitetiedosto_pakollinen_ehd')!=null){ 
			document.getElementById('uusi_liitetiedosto_pakollinen_ehd').onclick = function(){
				naytaElement("uusi_liitetiedosto_ehd_pakollisuus");
			};
		}
		
		if(document.getElementById('uusi_liitetiedosto_pakollinen_1')!=null){ 
			document.getElementById('uusi_liitetiedosto_pakollinen_1').onclick = function(){
				piilotaElement("uusi_liitetiedosto_ehd_pakollisuus");
			};
		}
		
		if(document.getElementById('uusi_liitetiedosto_pakollinen_0')!=null){ 
			document.getElementById('uusi_liitetiedosto_pakollinen_0').onclick = function(){
				piilotaElement("uusi_liitetiedosto_ehd_pakollisuus");
			};
		}
		
		if(document.getElementById('uusi_liitetiedosto_tyypit_all')!=null){
			document.getElementById('uusi_liitetiedosto_tyypit_all').onclick = function(){
				valittuKaikki2kpl('uusi_liitetiedosto_tyypit_all','uusi_liitetiedosto_tyypit_txt','uusi_liitetiedosto_tyypit_img','uusi_liite_teksti', 'liite_kuva');
			};
		}
		
		if(document.getElementById('uusi_liitetiedosto_tyypit_txt')!=null){
			document.getElementById('uusi_liitetiedosto_tyypit_txt').onclick = function(){
				valittuKaikki1kpl('uusi_liitetiedosto_tyypit_txt','uusi_liitetiedosto_tyypit_all','uusi_liite_teksti');
			};
		}
		
		if(document.getElementById('uusi_liitetiedosto_tyypit_img')!=null){
			document.getElementById('uusi_liitetiedosto_tyypit_img').onclick = function(){
				valittuKaikki1kpl('uusi_liitetiedosto_tyypit_img','uusi_liitetiedosto_tyypit_all','liite_kuva');
			};
		}
		
		var classTeksti = document.getElementsByClassName('uusi_liite_teksti');
		for (k=0; k < classTeksti.length; k++) {
			if (classTeksti[k].checked == false) {
				classTeksti[k].onclick = function(){
					poistaValintaLiitet('uusi_liitetiedosto_tyypit_all','uusi_liitetiedosto_tyypit_txt');
				};
			}
		}

		if(document.getElementById('lisaa_uusi_liitetiedosto')!=null){
			document.getElementById('lisaa_uusi_liitetiedosto').onclick = function(){ 
				naytaElementClass("uusi_liitetiedosto");
				piilotaElementClass("lomake_liitetiedosto");
			}
		}
		
		if(document.getElementById('peruuta_liitetiedoston_lisays')!=null){
			document.getElementById('peruuta_liitetiedoston_lisays').onclick = function(){  
				piilotaElementClass("uusi_liitetiedosto");
				naytaElementClass("lomake_liitetiedosto");
			}
		}
		
		var kys_tyyppi = document.getElementsByClassName('select_kysymyksen_tyyppi');
		
		for (k=0; k < kys_tyyppi.length; k++) {
			kys_tyyppi[k].onchange = function(){
				var fk_osio_parent = this.id.split("-")[2];
				if(this.value!="lomake_tutkimuksen_nimi" || this.value!=""){
					naytaElement("kysymyksen_lisametatiedot-parent-" + fk_osio_parent);
				} 
				if(this.value=="lomake_tutkimuksen_nimi" || this.value==""){
					piilotaElement("kysymyksen_lisametatiedot-parent-" + fk_osio_parent);
				}
				if(this.value=="checkbox"){
					naytaElement("parent-" + fk_osio_parent + "-kysymys_checkbox");
					if(document.getElementById("parent-" + fk_osio_parent + "-olemassaoleva_checkbox-0")==null || document.getElementById("parent-" + fk_osio_parent + "-olemassaoleva_checkbox-0").style.display=="none"){
						naytaElement("parent-" + fk_osio_parent + "-vaihtoehto-0");
					}
				}
				if(this.value!="checkbox"){
					piilotaElement("parent-" + fk_osio_parent + "-kysymys_checkbox");
					piilotaElement("parent-" + fk_osio_parent + "-vaihtoehto-0");
				}
				if(this.value=="radio"){
					naytaElement("parent-" + fk_osio_parent + "-kysymys_radio");
					if(document.getElementById("parent-" + fk_osio_parent + "-olemassaoleva_vaihtoehto-0")==null || document.getElementById("parent-" + fk_osio_parent + "-olemassaoleva_vaihtoehto-0").style.display=="none"){
						naytaElement("parent-" + fk_osio_parent + "-radiovaihtoehto-0");
					}
				}
				if(this.value!="radio"){
					piilotaElement("parent-" + fk_osio_parent + "-kysymys_radio");
					piilotaElement("parent-" + fk_osio_parent + "-radiovaihtoehto-0");
				}
			};
		}
		
		var uusi_kys = document.getElementsByClassName('lisaa_uusi_kysymys');
		
		for (k=0; k < uusi_kys.length; k++) {
			uusi_kys[k].onclick = function(){
				var fk_osio_parent = this.id.split("-")[2];
				naytaElementClass("uusi_kysymys");
				naytaElement("parent-" + fk_osio_parent + "-uusi_kysymys");
				piilotaElementClass("lomake_sivu");
			};
		}
		
		if(document.getElementsByClassName('liitetiedosto_ehd_pakollisuus')){
			var ehd_pakollisuus = document.getElementsByClassName('liitetiedosto_ehd_pakollisuus');
			for (k=0; k < ehd_pakollisuus.length; k++) {
				ehd_pakollisuus[k].onclick = function(){
					naytaElement("liitetiedosto_ehd_pakollisuus-" + this.id.split("-")[1]);
				};
			}
		}
		
		if(document.getElementsByClassName('liitetiedosto_pakollisuus')){ 
			var liite_pakollisuus = document.getElementsByClassName('liitetiedosto_pakollisuus');
			for (k=0; k < liite_pakollisuus.length; k++) {
				liite_pakollisuus[k].onclick = function(){
					piilotaElement("liitetiedosto_ehd_pakollisuus-" + this.id.split("-")[1]);
				};
			}
		}
		
		if(document.getElementsByClassName('liitetiedosto_ei_pakollisuus')){ 
			var liite_ei_pakollisuus = document.getElementsByClassName('liitetiedosto_ei_pakollisuus');
			for (k=0; k < liite_ei_pakollisuus.length; k++) {
				liite_ei_pakollisuus[k].onclick = function(){
					piilotaElement("liitetiedosto_ehd_pakollisuus-" + this.id.split("-")[1]);
				};
			}
		}
		
		var uusi_kysymys_tyyppi = document.getElementsByClassName('uusi_kysymys_tyyppi');
		
		for (k=0; k < uusi_kysymys_tyyppi.length; k++) {
			uusi_kysymys_tyyppi[k].onclick = function(){
				var fk_osio_parent = this.id.split("-")[1];
				if(this.value=="lomake_tutkimuksen_nimi" || this.value==""){
					piilotaElement("parent-" + fk_osio_parent + "-uuden_kysymyksen_lisametatiedot");
				} else {
					naytaElement("parent-" + fk_osio_parent + "-uuden_kysymyksen_lisametatiedot");
				}
				if(this.value=="checkbox"){
					naytaElement("parent-" + fk_osio_parent + "-uusi_kysymys_checkbox");
				} else {
					piilotaElement("parent-" + fk_osio_parent + "-uusi_kysymys_checkbox");
				}
				if(this.value=="radio"){
					naytaElement("parent-" + fk_osio_parent + "-uusi_kysymys_radio");
				} else {
					piilotaElement("parent-" + fk_osio_parent + "-uusi_kysymys_radio");
				}
			};
		}
		
		var uusi_checkbox_vaihtoehto = document.getElementsByClassName('lisaa_uusi_checkbox_vaihtoehto');
		
		for (k=0; k < uusi_checkbox_vaihtoehto.length; k++) {
			uusi_checkbox_vaihtoehto[k].onclick = function(){
				var fk_osio_parent = this.id.split("-")[2];
				for(j=1; j < 20; j++){
					if(document.getElementById("parent-" + fk_osio_parent + "-checkbox-" + j).style.display=="none"){
						document.getElementById("parent-" + fk_osio_parent + "-checkbox-" + j).style.display = "block";
						break;
					}
				}
			};
		}
		
		var uusi_radio_vaihtoehto = document.getElementsByClassName('lisaa_uusi_radio_vaihtoehto');
		
		for (k=0; k < uusi_radio_vaihtoehto.length; k++) {
			uusi_radio_vaihtoehto[k].onclick = function(){
				var fk_osio_parent = this.id.split("-")[2];
				for(j=1; j < 20; j++){
					if(document.getElementById("parent-" + fk_osio_parent + "-radio-" + j).style.display=="none"){
						document.getElementById("parent-" + fk_osio_parent + "-radio-" + j).style.display = "block";
						break;
					}
				}
			};
		}
		
		var lisaa_checkbox_vaihtoehto = document.getElementsByClassName('lisaa_checkbox_vaihtoehto');
		
		for (k=0; k < lisaa_checkbox_vaihtoehto.length; k++) {
			lisaa_checkbox_vaihtoehto[k].onclick = function(){
				var fk_osio_parent = this.id.split("-")[2];
				for(j=1; j < 20; j++){
					if(document.getElementById("parent-" + fk_osio_parent + "-vaihtoehto-" + j).style.display=="none"){
						document.getElementById("parent-" + fk_osio_parent + "-vaihtoehto-" + j).style.display = "block";
						break;
					}
				}
			};
		}
		
		var lisaa_radio_vaihtoehto = document.getElementsByClassName('lisaa_radio_vaihtoehto');
		
		for (k=0; k < lisaa_radio_vaihtoehto.length; k++) {
			lisaa_radio_vaihtoehto[k].onclick = function(){
				var fk_osio_parent = this.id.split("-")[2];
				for(j=1; j < 20; j++){
					if(document.getElementById("parent-" + fk_osio_parent + "-radiovaihtoehto-" + j).style.display=="none"){
						document.getElementById("parent-" + fk_osio_parent + "-radiovaihtoehto-" + j).style.display = "block";
						break;
					}
				}
			};
		}
		
		var poista_uusi_vaihtoehto = document.getElementsByClassName('poista_uusi_vaihtoehto');
		
		for (k=0; k < poista_uusi_vaihtoehto.length; k++) {
			poista_uusi_vaihtoehto[k].onclick = function(){
				var poistettava_indx = this.id.split("-")[1];
				var poistettava_tyyppi = this.id.split("-")[2];
				document.getElementById("poista_uusi_vaihtoehto-" + poistettava_indx + "-" + poistettava_tyyppi).parentNode.style.display = "none";
			};
		}
		
		var poista_dyn_vaihtoehto = document.getElementsByClassName('poista_dyn_vaihtoehto');
		
		for (k=0; k < poista_dyn_vaihtoehto.length; k++) {
			poista_dyn_vaihtoehto[k].onclick = function(){
				document.getElementById("parent-" + this.id.split("-")[1] + "-vaihtoehto-" + this.id.split("-")[3]).style.display = "none";
			};
		}
		
		var poista_dyn_vaihtoehto = document.getElementsByClassName('poista_dyn_radiovaihtoehto');
		
		for (k=0; k < poista_dyn_vaihtoehto.length; k++) {
			poista_dyn_vaihtoehto[k].onclick = function(){
				document.getElementById("parent-" + this.id.split("-")[1] + "-radiovaihtoehto-" + this.id.split("-")[3]).style.display = "none";
			};
		}
		
		if(document.getElementById('peruuta_kysymyksen_lisays')!=null){
			document.getElementById('peruuta_kysymyksen_lisays').onclick = function(){
				piilotaElementClass("uusi_kysymys");
				piilotaElementClass("uuden_kysymyksen_lisays_kokonaisuuteen");
				naytaElementClass("lomake_sivu");
			}
		}
		
		if(document.getElementById('lisaa_uusi_kokonaisuus')!=null){
			document.getElementById('lisaa_uusi_kokonaisuus').onclick = function(){ 
				naytaElementClass("uusi_kokonaisuus");
				piilotaElementClass("lomake_sivu");
			}
		}
		
		if(document.getElementById('peruuta_kokonaisuuden_lisays')!=null){
			document.getElementById('peruuta_kokonaisuuden_lisays').onclick = function(){
				piilotaElementClass("uusi_kokonaisuus");
				naytaElementClass("lomake_sivu");
			}
		}
		
		varoita_jos_formien_tietoja_muutetaan("form_lomake_sivu");
		
	}

	if(sivun_nimi == 'lausunnonantaja_hakemus_lausunto.php'){
		
		// Onclick eventit onclick inputeille
		var laus_onclick = document.getElementsByClassName("laus_input");
		
		for(c=0; c < laus_onclick.length; c++) {			
			laus_onclick[c].onclick=function() {
				
				input_name = this.id;
				var input_names = input_name.split("-");
				var input_name = input_names[0];
				var input_nro = input_names[1];
				
				if(input_name=="lausunnon_muoto_pohja"){
					naytaElement1jaPiilotaElement2('lausuntopohja-' + input_nro,'lausunto_sanallisena-' + input_nro);
					naytaElement('lausunto_paatos-' + input_nro);
				}
				
				if(input_name=="lausunnon_muoto_sanallisena"){
					naytaElement1jaPiilotaElement2('lausunto_sanallisena-' + input_nro, 'lausuntopohja-' + input_nro);
					naytaElement('lausunto_paatos-' + input_nro);
				}
				
				if(input_name=="puoltaminen"){
					piilotaElement('div_ehdollinen_puoltaminen-' + input_nro);
				}
				
				if(input_name=="ehdollinen_puoltaminen"){
					naytaElement('div_ehdollinen_puoltaminen-' + input_nro);
				}
				
				if(input_name=="puoltaminen_ei"){
					piilotaElement('div_ehdollinen_puoltaminen-' + input_nro);
				}
				
			}
		}
		
		varoita_jos_formien_tietoja_muutetaan("form_lausunto");
		
	}

	if(sivun_nimi == 'viranomainen_saapuneet_hakemukset.php' || sivun_nimi == 'aineistonmuodostaja_saapuneet_tilaukset.php'){
		$('.kasittely').on('click', function() {
			$("#kasittelypop-" + this.id).slideFadeToggle();
		});
		$('.close').on('click', function() {
			piilotaElement("kasittelypop-" + this.id.split('-')[1]);
		});
	}

	if(sivun_nimi == 'index.php'){

		$('.kuittaus').on('click', function() {
			$("#kuittauspop-" + this.id).slideFadeToggle();
		});
		
		$('.close').on('click', function() {
			piilotaElement("kuittauspop-" + this.id);
		});

		$('#nappi_muutos').on('click', function() {
			$("#muutospop").slideFadeToggle();
		});
		
		$('.close-muutos').on('click', function() {
			piilotaElement("muutospop");
		});

		document.getElementById('input_olemassa_olevaan').onclick = function(){
			naytaElement1jaPiilotaElement2("muutoshakemus_olemassa_olevaan","muutoshakemus_aiempaan_hakemukseen");
		};

		//document.getElementById('input_aiempaan').onclick = function(){
		//	naytaElement1jaPiilotaElement2("muutoshakemus_aiempaan_hakemukseen","muutoshakemus_olemassa_olevaan");
		//};
		
		$('.toim_tayd_ask').on('click', function() {
			document.getElementById("taydennys_paatos_id").value = this.id.replace(/\D/g, "");
			$("#taydennyspop").slideFadeToggle();
		});	

		$('.close-tayd').on('click', function() {
			piilotaElement("taydennyspop");
		});		
		
	}	

	if(sivun_nimi == 'hakemus_viestit.php' || sivun_nimi == 'viranomainen_hakemus_viestit.php'){
		
		var onclick_input = document.getElementsByClassName("onclick_input");
		
		for(c=0; c < onclick_input.length; c++) {
			onclick_input[c].onclick=function() {
				input_name = this.id;
				var input_names = input_name.split("-");
				var input_name = input_names[0];
				var input_nro = input_names[1];
				if(input_name=="viesti"){
					luo_vastaus_kentta(input_nro);
				}
			}
		}
		
		if(sivun_nimi == "viranomainen_hakemus_viestit.php"){
			document.getElementById('valitse_viestin_vastaanottaja').onchange = function(){ 
				var viestin_vastaanottaja = document.getElementsByClassName("viestin_vastaanottaja lisatietopyynto_vastaanottaja");
				var nayta_lisatietopyynto = false;
				for(c=0; c < viestin_vastaanottaja.length; c++) {
					if(viestin_vastaanottaja[c].selected){
						nayta_lisatietopyynto = true;
					}
				}
				if(nayta_lisatietopyynto){
					naytaElement("lisatietopyynto");
				} else {
					piilotaElement("lisatietopyynto");
				}
			}
		}
		
	}	
		
	if(sivun_nimi == "viranomainen_hakemus_lausunto.php"){
		
		lausunto_input = document.getElementsByClassName("lausunto_linkki");
		
		for(k=0; k < lausunto_input.length; k++) {	 
			lausunto_input[k].onclick=function() { 
				ajax_kutsu("POST",sivun_nimi,"merkitse_lausunto_luetuksi=1&luettu_lausunto_id=" + this.id);
			}
		}		
		
		naytetaanko_input = document.getElementsByClassName("naytetaankoLausunto");
		
		for(k=0; k < naytetaanko_input.length; k++) {	 
			naytetaanko_input[k].onclick=function() {
				
				klikattu_className = this.className;
				lausunto_id = klikattu_className.split(' ')[1];
				
				if(this.checked){
					ajax_kutsu("POST",sivun_nimi,"lausunto_naytetaan_hakijoille=on&lausunto_id=" + lausunto_id);
				} else {
					ajax_kutsu("POST",sivun_nimi,"lausunto_naytetaan_hakijoille=0&lausunto_id=" + lausunto_id);
				}
				
			}
		}
		
		varoita_jos_formin_tietoja_muutetaan("form_lausunto");
		
	}
		
}

window.onbeforeunload = function (e) {
	if(typeof window.varoita_jos_sivulta_poistutaan != 'undefined' && window.varoita_jos_sivulta_poistutaan != null){
		if(window.varoita_jos_sivulta_poistutaan==true){
			var confirmationMessage = 'It looks like you have been editing something. '+ 'If you leave before saving, your changes will be lost.';
			(e || window.event).returnValue = confirmationMessage; //Gecko + IE
			return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
		}
	}
}

// Helper funktiot alkaa
function naytaElement(Element){
	if(document.getElementById(Element)) document.getElementById(Element).style.display = 'block';  
}

function piilotaElement(Element){
	if(document.getElementById(Element)) document.getElementById(Element).style.display = 'none';  
}

function naytaElement1jaPiilotaElement2(Element1,Element2){
	document.getElementById(Element1).style.display = 'block'; 
	document.getElementById(Element2).style.display = 'none'; 
}

function piilotaElementClass(Element){
	var elementit = document.getElementsByClassName(Element),
		i = elementit.length;
	while(i--) {
		elementit[i].style.display = 'none';
	}
}

function naytaElementClass(Element){
	var elementit = document.getElementsByClassName(Element),
		i = elementit.length;
	while(i--) {
		elementit[i].style.display = 'block';
	}
}

function naytaInlineElementClass(Element){
	var elementit = document.getElementsByClassName(Element),
		i = elementit.length;
	while(i--) {
		elementit[i].style.display = 'inline-block';
	}
}

function vahvista_poisto() {
	return confirm("Haluatko varmasti poistaa hakemuksen?");
}

function vahvista_henkilon_poisto() {
    return confirm("Haluatko varmasti poistaa henkilön tältä hakemukselta?");
}

Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}

NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
} 

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function luo_vastaus_kentta(i){
	
	document.getElementById('nappi_vastaa_' + i).style.display = 'none'; 
	document.getElementById('vastaus_kentta_' + i).style.display = 'block'; 
	if(document.getElementById('liite_kentta_' + i)) document.getElementById('liite_kentta_' + i).style.display = 'block'; 
	document.getElementById('nappi_laheta_vastaus_' + i).style.display = 'block';
	
}

function ajax_kutsu(method,sivun_nimi,send_parametri){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			response = xhttp.responseText;
		}
	};
	xhttp.open(method, sivun_nimi, true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(send_parametri);
}

function varoita_jos_formin_tietoja_muutetaan(formin_id){
	// Rekisteröidään jos formia muutetaan
	$('#' + formin_id).change(function(){ 
		window.varoita_jos_sivulta_poistutaan = true;  
	});
	// Ei varoiteta jos tiedot tallennetaan
	$('#' + formin_id).submit(function(){
		window.varoita_jos_sivulta_poistutaan = false;
	});
}

function varoita_jos_formien_tietoja_muutetaan(formin_luokka){
	// Rekisteröidään jos formia muutetaan
	$('.' + formin_luokka).change(function(){ 
		window.varoita_jos_sivulta_poistutaan = true;  
	});
	// Ei varoiteta jos tiedot tallennetaan
	$('.' + formin_luokka).submit(function(){
		window.varoita_jos_sivulta_poistutaan = false;
	});
}

function form_input_onchange(onchange_element){

	var tallennuskohde = onchange_element.className.split(" ")[1];
	var tallennuskohde_id = onchange_element.className.split(" ")[2];					 		
	var tallennuskohde_kentta = onchange_element.className.split(" ")[3];
	var tallennuskohde_arvo = onchange_element.value;
	var tallennuskohde_nimi = onchange_element.name;
	
	if(tallennuskohde=="teksti" || tallennuskohde=="tieto" || tallennuskohde=="lohko" || tallennuskohde=="oikea_sisalto_laatikko" || tallennuskohde=="valiotsikko" || tallennuskohde=="osio") tallennuskohde_id = tallennuskohde_id.substring(1);	
	
	if(tallennuskohde=="muutoshakemus_tyyppi"){
		if(tallennuskohde_kentta=="Muu_muutoshakemuksen_tyyppi"){
			if(onchange_element.checked){
				naytaElement("Muun_muutoshakemuksen_tyypin_selite");
			} else {
				piilotaElement("Muun_muutoshakemuksen_tyypin_selite");
			}
		}
	}

	if(tallennuskohde=="haettu_muuttuja"){
		if(tallennuskohde_arvo!="valitse_kaikki" && tallennuskohde_arvo!="poista_kaikki"){					 
			piilotaElementClass("muuttuja_lisatieto-" + tallennuskohde_id);
			if(document.getElementById("muuttuja_lisatieto-" + tallennuskohde_id + "-" + tallennuskohde_arvo)) naytaElement("muuttuja_lisatieto-" + tallennuskohde_id + "-" + tallennuskohde_arvo);
		} else {
			
			if(tallennuskohde_arvo=="valitse_kaikki") onchange_element.value = "poista_kaikki";							
			if(tallennuskohde_arvo=="poista_kaikki")	onchange_element.value = "valitse_kaikki";
									
			var checkbox_muuttujat = onchange_element.parentNode.parentNode.getElementsByTagName("input");
			
			for (i = 0; i < checkbox_muuttujat.length; i++) { 
				if(tallennuskohde_arvo=="valitse_kaikki"){
					checkbox_muuttujat[i].checked = true;
				} else {
					checkbox_muuttujat[i].checked = false;
				}
			}
			
		}
	}

	if(tallennuskohde=="haettu_luvan_kohde" && (tallennuskohde_kentta=="Poiminta_ajankohdat" || tallennuskohde_kentta=="Muuttujat_lueteltuna" || tallennuskohde_kentta=="Viranomaisen_koodi" || tallennuskohde_kentta=="FK_Luvan_kohde" )){
		
		var sisar_nodet = onchange_element.parentNode.childNodes;
		var luvan_kohteen_tyyppi = "";
		var luvan_kohde_id = null;
		var katalogi_node = null;
		var muuttujat_lueteltuna_node = null;
		var poimintaajankohdat_node = null;
		var viranomaisen_valinta_node = null;
		var luvan_kohteen_valinta_node = null;
		var potilas_ask_lisatiedot_node = null;
		var biopankki_kuvaus_node = null;
		
		if(tallennuskohde_kentta=="FK_Luvan_kohde" || tallennuskohde_kentta=="Viranomaisen_koodi"){
			osio_id = onchange_element.id.split("-")[1];
			luv_kohde_id = onchange_element.id.split("-")[2];
		}
		
		for (i = 0; i < sisar_nodet.length; i++) {
			
			if(sisar_nodet[i].className && tallennuskohde_kentta=="Viranomaisen_koodi" && sisar_nodet[i].className.split(" ")[3]=="FK_Luvan_kohde"){				
				vlk_paiv = paivita_viranomaisen_luvan_kohteet(onchange_element.value, sisar_nodet[i]);				
				tallennuskohde_arvo = vlk_paiv["fk_haettu_aineisto"]; luvan_kohde_id = vlk_paiv["fk_haettu_aineisto"];
				luvan_kohteen_tyyppi = vlk_paiv["luvan_kohteen_tyyppi"];				
				tallennuskohde_kentta = "FK_Luvan_kohde"; 				
			}
			
			if(sisar_nodet[i].className && tallennuskohde_kentta=="FK_Luvan_kohde" && sisar_nodet[i].className.split(" ")[3]=="Viranomaisen_koodi"){ 
				luvan_kohteen_tyyppi = paivita_luvan_kohteen_viranomaiset(onchange_element, sisar_nodet[i]);
				luvan_kohde_id = onchange_element.value;
			}
			
			if(sisar_nodet[i].className && sisar_nodet[i].className.split(" ")[3]=="Viranomaisen_koodi") viranomaisen_valinta_node = sisar_nodet[i];
			if(sisar_nodet[i].className && sisar_nodet[i].className.split(" ")[3]=="FK_Luvan_kohde") luvan_kohteen_valinta_node = sisar_nodet[i];
			if(sisar_nodet[i].className && sisar_nodet[i].className=="tk_muuttujat_aineisto") katalogi_node = sisar_nodet[i];
			if(sisar_nodet[i].className && sisar_nodet[i].className=="muuttujat_lueteltuna") muuttujat_lueteltuna_node = sisar_nodet[i];
			if(sisar_nodet[i].className && sisar_nodet[i].className=="poimintaajankohdat") poimintaajankohdat_node = sisar_nodet[i];	
			if(sisar_nodet[i].className && sisar_nodet[i].className=="potilas_ask_lisatiedot") potilas_ask_lisatiedot_node = sisar_nodet[i];
			if(sisar_nodet[i].className && sisar_nodet[i].className=="biopankki_kuvaus") biopankki_kuvaus_node = sisar_nodet[i];
			
		}
	
		// Muuttujat määritellään lueteltuna	
		if(luvan_kohteen_tyyppi!="Taika_tilastoaineisto" && luvan_kohteen_tyyppi!="Aineistokatalogi"){
			
			if(katalogi_node) katalogi_node.style.display = "none";
			
			if(luvan_kohteen_tyyppi=="Asiakirja_soshuolto"){
								
				if(biopankki_kuvaus_node) biopankki_kuvaus_node.style.display = "none";
				if(potilas_ask_lisatiedot_node) potilas_ask_lisatiedot_node.style.display = "block";
				if(muuttujat_lueteltuna_node) muuttujat_lueteltuna_node.style.display = "none";
				if(poimintaajankohdat_node) poimintaajankohdat_node.style.display = "none";	
				
			} else if(luvan_kohteen_tyyppi=="Biopankki"){
								
				//if(biopankki_kuvaus_node) biopankki_kuvaus_node.style.display = "block";
				//if(potilas_ask_lisatiedot_node) potilas_ask_lisatiedot_node.style.display = "none";
				//if(muuttujat_lueteltuna_node) muuttujat_lueteltuna_node.style.display = "none";
				//if(poimintaajankohdat_node) poimintaajankohdat_node.style.display = "none";	
				
			} else {
								
				if(biopankki_kuvaus_node) biopankki_kuvaus_node.style.display = "none";
				if(potilas_ask_lisatiedot_node) potilas_ask_lisatiedot_node.style.display = "none";
				if(muuttujat_lueteltuna_node) muuttujat_lueteltuna_node.style.display = "block";
				if(poimintaajankohdat_node) poimintaajankohdat_node.style.display = "block";	
				
			}

		} 
						
	}

	if(tallennuskohde=="organisaatio" && (tallennuskohde_kentta=="Rekisterinpitaja" || tallennuskohde_kentta=="Rooli" || tallennuskohde_kentta=="Edustajan_email" || tallennuskohde_kentta=="Edustaja" || tallennuskohde_kentta=="Osoite" || tallennuskohde_kentta=="Nimi")){
		$(":input").prop("disabled", true);
		org_textareat = onchange_element.parentNode.parentNode.parentNode.parentNode.getElementsByTagName("textarea");
		org_input = onchange_element.parentNode.parentNode.parentNode.parentNode.getElementsByTagName("input");
	}

	if(tallennuskohde=="haetaanko_kayttolupaa" || tallennuskohde=="sitoumus"){
		if(onchange_element.checked){
			tallennuskohde_arvo = 1;
		} else {
			tallennuskohde_arvo = 0;
		}
	}

	$.ajax({
		url: window.location,
		type: "POST",
		dataType: 'json',
		data: { tallennuskohde: tallennuskohde, tallennuskohde_id: tallennuskohde_id, tallennuskohde_nimi: tallennuskohde_nimi, tallennuskohde_arvo: tallennuskohde_arvo, tallennuskohde_kentta: tallennuskohde_kentta },
		beforeSend: function(){
			if( tallennuskohde=="haettu_luvan_kohde" && (tallennuskohde_kentta=="Viranomaisen_koodi" || tallennuskohde_kentta=="FK_Luvan_kohde") ){
				if(luvan_kohteen_tyyppi=="Biopankki" || luvan_kohteen_tyyppi=="Aineistokatalogi" || luvan_kohteen_tyyppi=="Taika_tilastoaineisto"){
					$(":input").prop("disabled", true);
					naytaElement("loading_img");					
				}
				if(tallennuskohde_id==0) $(":input").prop("disabled", true);										
			} else {
				$(document.body).css({ 'cursor': 'wait' });
			}
		},
		complete: function(){			
			$(document.body).css({ 'cursor': 'default' });
			if(tallennuskohde_id==0) $(":input").prop("disabled", false);
		},
		error: function(jqXHR, textStatus, errorThrown){
			//if(document.getElementById("error_time")) document.getElementById("error_time").innerHTML = "Virhe: Tallennus epäonnistui";
			//if(document.getElementById("alert")) $("#alert").show().delay(2500).fadeOut();					
		},		
		success: function(data) {
			
			if(data.nayta_biopankkinaytteiden_kasittely!=null){
				if(data.nayta_biopankkinaytteiden_kasittely){
					naytaElementClass("biopankkinaytteiden_kasittely");
				} else {
					piilotaElementClass("biopankkinaytteiden_kasittely");
				}
			}			
			
			if((tallennuskohde=="haettu_luvan_kohde" && (tallennuskohde_kentta=="Viranomaisen_koodi" || tallennuskohde_kentta=="FK_Luvan_kohde") )){
				
				if(data.uusi_lisatty_haettu_luvan_kohde_ID){
					
					// Lisätään uusi haettu luvan kohde ID nodeen: Viranomaisen_koodi (select)
					if(viranomaisen_valinta_node) viranomaisen_valinta_node.className = viranomaisen_valinta_node.className.split(" ")[0] + " " + viranomaisen_valinta_node.className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + viranomaisen_valinta_node.className.split(" ")[3];
					
					// Lisätään uusi haettu luvan kohde ID elementtiin: FK_Luvan_kohde (select)
					if(luvan_kohteen_valinta_node) luvan_kohteen_valinta_node.className = luvan_kohteen_valinta_node.className.split(" ")[0] + " " + luvan_kohteen_valinta_node.className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + luvan_kohteen_valinta_node.className.split(" ")[3];
					
					// Etsitään elementti: Muuttujat_lueteltuna (textarea) ja lisätään siihen uusi haettu luvan kohde ID 
					if(muuttujat_lueteltuna_node){
						for (var i = 0; i < muuttujat_lueteltuna_node.childNodes.length; i++) { 
							if(typeof muuttujat_lueteltuna_node.childNodes[i].className != 'undefined' && muuttujat_lueteltuna_node.childNodes[i].className.split(" ")[3]=="Muuttujat_lueteltuna"){
								muuttujat_lueteltuna_node.childNodes[i].className = muuttujat_lueteltuna_node.childNodes[i].className.split(" ")[0] + " " + muuttujat_lueteltuna_node.childNodes[i].className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + muuttujat_lueteltuna_node.childNodes[i].className.split(" ")[3] + " " + muuttujat_lueteltuna_node.childNodes[i].className.split(" ")[4];
							}
						}
					}
					
					// Etsitään elementti: Poiminta_ajankohdat (textarea) ja lisätään siihen uusi haettu luvan kohde ID 
					if(poimintaajankohdat_node){
						for (var i = 0; i < poimintaajankohdat_node.childNodes.length; i++) { 
							if(typeof poimintaajankohdat_node.childNodes[i].className != 'undefined' && poimintaajankohdat_node.childNodes[i].className.split(" ")[3]=="Poiminta_ajankohdat"){
								poimintaajankohdat_node.childNodes[i].className = poimintaajankohdat_node.childNodes[i].className.split(" ")[0] + " " + poimintaajankohdat_node.childNodes[i].className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + poimintaajankohdat_node.childNodes[i].className.split(" ")[3] + " " + poimintaajankohdat_node.childNodes[i].className.split(" ")[4];
							}
						}
					}	

					// THL potilasasiakirjakohtaiset tekstikenttien päivitys	
					if(luvan_kohteen_tyyppi=="Asiakirja_soshuolto"){
						if(potilas_ask_lisatiedot_node){
							for (var i = 0; i < potilas_ask_lisatiedot_node.childNodes.length; i++) { 
							
								if(typeof potilas_ask_lisatiedot_node.childNodes[i].className != 'undefined' && potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[3]=="Toimintayksikot"){
									potilas_ask_lisatiedot_node.childNodes[i].className = potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[0] + " " + potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[3] + " " + potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[4];
								}	
								
								if(typeof potilas_ask_lisatiedot_node.childNodes[i].className != 'undefined' && potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[3]=="Kohdejoukon_mukaanottokriteerit"){
									potilas_ask_lisatiedot_node.childNodes[i].className = potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[0] + " " + potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[3] + " " + potilas_ask_lisatiedot_node.childNodes[i].className.split(" ")[4];
								}

								if(typeof potilas_ask_lisatiedot_node.childNodes[i].className != 'undefined' && potilas_ask_lisatiedot_node.childNodes[i].className=="t_yht_true"){
							
									if(typeof potilas_ask_lisatiedot_node.childNodes[i].childNodes[1] != 'undefined'){
										t_yht_true_radio = potilas_ask_lisatiedot_node.childNodes[i].childNodes[1];
										t_yht_true_radio.className = t_yht_true_radio.className.split(" ")[0] + " " + t_yht_true_radio.className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + t_yht_true_radio.className.split(" ")[3]; 
									}
								}
								
								if(typeof potilas_ask_lisatiedot_node.childNodes[i].className != 'undefined' && potilas_ask_lisatiedot_node.childNodes[i].className=="t_yht_false"){
									if(typeof potilas_ask_lisatiedot_node.childNodes[i].childNodes[1] != 'undefined'){
										t_yht_false_radio = potilas_ask_lisatiedot_node.childNodes[i].childNodes[1];
										t_yht_false_radio.className = t_yht_false_radio.className.split(" ")[0] + " " + t_yht_false_radio.className.split(" ")[1] + " " + data.uusi_lisatty_haettu_luvan_kohde_ID + " " + t_yht_false_radio.className.split(" ")[3]; 
									}
								}								
								
							}
						}
					}
					
					//if(luvan_kohteen_valinta_node) luvan_kohteen_valinta_node.onchange=function() { form_input_onchange(this); }	
					//if(viranomaisen_valinta_node) viranomaisen_valinta_node.onchange=function() { form_input_onchange(this); }					
					
				}
				
				if(luvan_kohteen_tyyppi=="Biopankki" || luvan_kohteen_tyyppi=="Aineistokatalogi" || luvan_kohteen_tyyppi=="Taika_tilastoaineisto"){
					
					window.location.reload();				
					// cachetus todo
					/*
					if(tallennuskohde_id==0){ 
						window.location.href = removeParam("lataa_cachesta", window.location.href.toString());				
					} else if(data.uusi_lisatty_haettu_luvan_kohde_ID || window.location.search.indexOf('lataa_cachesta=1') > -1){
						window.location.reload();
					} else {
						window.location.href = window.location.href + "&lataa_cachesta=1" + "#luv_kohde-a-" + tallennuskohde_id;
					}
					*/
				}
				
			} else {
								
				if(data.Poimitaanko_verrokeille_samat){
					if(data.Poimitaanko_verrokeille_samat==1){
						piilotaElement("haettu_kohde_verrokit_muuttujat-lohko1");
						piilotaElement("haettu_kohde_verrokit_muuttujat-lohko2");
					} else {
						naytaElement("haettu_kohde_verrokit_muuttujat-lohko1");
						naytaElement("haettu_kohde_verrokit_muuttujat-lohko2");
					}
				}
				
				if(data.Poimitaanko_viitehenkiloille_samat){
					if(data.Poimitaanko_viitehenkiloille_samat==1){
						piilotaElement("haettu_kohde_viitehenkilot_muuttujat-lohko1");
						piilotaElement("haettu_kohde_viitehenkilot_muuttujat-lohko2");
					} else {
						naytaElement("haettu_kohde_viitehenkilot_muuttujat-lohko1");
						naytaElement("haettu_kohde_viitehenkilot_muuttujat-lohko2");
					}
				}
				
				if(data.Uusi_tutkimuksen_organisaatio_id && org_textareat && org_input){
					for (i = 0; i < org_textareat.length; i++) { 
						if(org_textareat[i].className && org_textareat[i].className.split(" ")[2]==0){ 
							org_textareat[i].className = org_textareat[i].className.split(" ")[0] + " " + org_textareat[i].className.split(" ")[1] + " " + data.Uusi_tutkimuksen_organisaatio_id + " " + org_textareat[i].className.split(" ")[3] + " " + org_textareat[i].className.split(" ")[4];
						}
					}
					for (i = 0; i < org_input.length; i++) { 
						if(org_input[i].className && org_input[i].className.split(" ")[2]==0){ 
							org_input[i].className = org_input[i].className.split(" ")[0] + " " + org_input[i].className.split(" ")[1] + " " + data.Uusi_tutkimuksen_organisaatio_id + " " + org_input[i].className.split(" ")[3];
						}
					}
				}

				if(data.tallennusOnnistui){
					
					if(tallennuskohde_kentta=="FK_Lomake" || (tallennuskohde=="lausunto" && tallennuskohde_kentta=="FK_Lomake")) window.location.reload();
					if(tallennuskohde=="organisaatio") $(":input").prop("disabled", false);	 
					
					piilotaElement("success_huomio");	
					document.getElementById("success_time").innerHTML = new Date().toLocaleTimeString('en-US', { hour12: false, hour: "numeric", minute: "numeric"});					
					$("#alert_success").show().delay(2500).fadeOut();
					$("#success_save").show().delay(2500).fadeOut();
					
				} else {
					if(document.getElementById("error_time")) document.getElementById("error_time").innerHTML = "Virhe: Tallennus epäonnistui";
					if(document.getElementById("alert")) $("#alert").show().delay(2500).fadeOut();						
				}
				
				if(data.nakymatDTO){
					paivita_nakymat(data.nakymatDTO);
				}
				
				if(data.osiotDTO){
					paivita_osiot(data.osiotDTO);
				}
				
				if(data.tallennettu_sitoumusDTO){
					if(document.getElementById('sitoutuminen_hyvaksytty')){
						document.getElementById('sitoutuminen_checked').style.display = "none";
						document.getElementById('sitoutuminen_hyvaksytty').style.display = "block";
						var newParagraph = document.createElement('p');
						newParagraph.textContent = data.tallennettu_sitoumusDTO["Lisayspvm"];
						document.getElementById("dynaaminen_sitoutumispvm").appendChild(newParagraph);
					}
				}
				
			}
		}
	});

}

function form_nappi_onclick(onclick_element){
	
	var kohde_tunniste = onclick_element.className.split(" ")[1];
	var kohde_id = onclick_element.className.split(" ")[2];
	var kohde_toiminto = onclick_element.className.split(" ")[3];
	var kohde_arvo = onclick_element.value;
	var kohde_nimi = onclick_element.name;
	
	$.ajax({
		url: window.location,
		type: "POST",
		dataType: 'json',
		data: { tallennuskohde: kohde_tunniste, tallennuskohde_id: kohde_id, tallennuskohde_nimi: kohde_nimi, tallennuskohde_arvo: kohde_arvo, tallennuskohde_kentta: kohde_toiminto },
		beforeSend: function(){
			$(":input").prop("disabled", true);
			naytaElement("loading_img");
			$(document.body).css({ 'cursor': 'wait' });
		},
		complete: function(){
			$(document.body).css({ 'cursor': 'default' });
		},
		success: function(data) {
			if(data.uusi_alustettu_haettu_luvan_kohde_ID || data.luvan_kohde_poistettu || data.Uusi_tutkimuksen_organisaatio_id || data.Organisaatio_poistettu){				
				naytaElement("loading_img");				
				window.location.reload();	
				//window.location.href = removeParam("lataa_cachesta", window.location.href.toString());
			} else {
				if(data.osiotDTO){
					paivita_osiot(data.osiotDTO);
				}
				if(data.tallennusOnnistui){
					document.getElementById("success_time").innerHTML = new Date().toLocaleTimeString('en-US', { hour12: false, hour: "numeric", minute: "numeric"});
					$("#alert_success").show().delay(3000).fadeOut();
				}
			}
		}
	});
	
}

function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

function paivita_viranomaisen_luvan_kohteet(vir_koodi, luvan_kohteet_element){
	
	var result = [];
	var fk_haettu_aineisto = null;
	var luvan_kohteen_tyyppi = null;
	result["fk_haettu_aineisto"] = null;
	result["luvan_kohteen_tyyppi"] = null;
	
	for(var i = luvan_kohteet_element.options.length-1; i >= 0; i--){ // Tyhjennä luvan_kohde-selectin optionit
		luvan_kohteet_element.remove(i);
	}
	
	if(vir_koodi=="v_0"){ // Lisää kaikki rekisterit
		for (var i = 0; i < luvan_kohteetDTO_kaikki.length; i++){ 
			var opt = document.createElement('option');
			opt.value = luvan_kohteetDTO_kaikki[i]["ID"];
			opt.innerHTML = luvan_kohteetDTO_kaikki[i]["Luvan_kohteen_nimi"];			
			luvan_kohteet_element.appendChild(opt);
			if(i==0){
				fk_haettu_aineisto = opt.value;
				luvan_kohteen_tyyppi = luvan_kohteetDTO_kaikki[i]["Luvan_kohteen_tyyppi"];
			}
		}
	} else { // Lisää viranomaisen rekisterit
		for (var i = 0; i < luvan_kohteetDTO_viranomainen[vir_koodi].length; i++){ 
			var opt = document.createElement('option');
			opt.value = luvan_kohteetDTO_viranomainen[vir_koodi][i]["ID"];
			opt.innerHTML = luvan_kohteetDTO_viranomainen[vir_koodi][i]["Luvan_kohteen_nimi"];
			luvan_kohteet_element.appendChild(opt);
			if(i==0){
				fk_haettu_aineisto = opt.value;
				luvan_kohteen_tyyppi = luvan_kohteetDTO_viranomainen[vir_koodi][i]["Luvan_kohteen_tyyppi"];
			}
		}
	}
	
	//luvan_kohteet_element.onchange=function(){ form_input_onchange(this); }
	
	result["fk_haettu_aineisto"] = fk_haettu_aineisto;
	result["luvan_kohteen_tyyppi"] = luvan_kohteen_tyyppi;
	
	return result;
	
}

function paivita_luvan_kohteen_viranomaiset(luvan_kohteet_element, viranomaiset_element){
	
	var luvan_kohde_ID = luvan_kohteet_element.value;
	var vir_koodi = "";
	var luvan_kohteen_tyyppi = "";
	
	for (i=0; i < luvan_kohteetDTO_kaikki.length; i++){
		if(luvan_kohteetDTO_kaikki[i]["ID"]==luvan_kohde_ID){
			vir_koodi = luvan_kohteetDTO_kaikki[i]["Viranomaisen_koodi"];	
			luvan_kohteen_tyyppi = luvan_kohteetDTO_kaikki[i]["Luvan_kohteen_tyyppi"];	
		}
	}
	
	if(viranomaiset_element.value=="v_0" && luvan_kohde_ID!="178"){
		
		for (var i = 0; i < viranomaiset_element.options.length; i++){
			option = viranomaiset_element.options[i];
			if(option.value==vir_koodi){
				option.selected = true;
				break;
			} 
		}
		
		// Tyhjennä rekisteri-selectin optionit
		for(var i = luvan_kohteet_element.options.length-1; i >= 0; i--){
			luvan_kohteet_element.remove(i);
		}
		
		// Lisää viranomaisen rekisterit
		for (var i = 0; i < luvan_kohteetDTO_viranomainen[vir_koodi].length; i++){
			var opt = document.createElement('option');
			opt.value = luvan_kohteetDTO_viranomainen[vir_koodi][i]["ID"];
			opt.innerHTML = luvan_kohteetDTO_viranomainen[vir_koodi][i]["Luvan_kohteen_nimi"];
			// Merkataan klikattu rekisteri valituksi
			if(luvan_kohde_ID==opt.value){
				opt.selected = true;
			}
			luvan_kohteet_element.appendChild(opt);
		}
				
	}
	
	//luvan_kohteet_element.onchange=function() { form_input_onchange(this); }	
	//viranomaiset_element.onchange=function() { form_input_onchange(this); }
	
	return luvan_kohteen_tyyppi;
	
}

function paivita_osiot(osiotDTO_taulu){	

	var piilota_poim_muuttujat_biopankit = false;
	
	if(typeof osiotDTO_taulu != 'undefined' && osiotDTO_taulu != null){
		var key;
		for(key in osiotDTO_taulu) {
			
			// Osiotyyppikohtaiset funktiot alkaa
			if(osiotDTO_taulu[key]["Osio_tyyppi"]=="tutkimusryhma"){
				if(document.getElementById('haetaanko_kayttolupaa')){  
					document.getElementById('haetaanko_kayttolupaa').onclick = function(){
						if(this.checked==true ){
							if(document.getElementById('div_salassapitositoumus')) naytaElement("div_salassapitositoumus");
						} else {
							if(document.getElementById('div_salassapitositoumus')) piilotaElement("div_salassapitositoumus");
						}
					}
				}
			}
			
			if(osiotDTO_taulu[key]["Osio_tyyppi"]=="liitteet"){ 
				if(document.getElementById('valittu_liitetyyppi') && document.getElementById('valittu_liite')){ 
					document.getElementById('valittu_liite').onchange = function(){
						if(this.value!="ei_valittu"){
							piilotaElementClass("liite_lista");
							naytaElement("valittu_liitetyyppi");
							naytaElement(this.value);
							naytaElement("liitteen_lisays");
						} else {
							piilotaElement("valittu_liitetyyppi");
							piilotaElementClass("liite_lista");
							piilotaElement("liitteen_lisays");
						}
					}
				}
			}
			
			// Poimittavien muuttujien biopankkilistaus
			if(osiotDTO_taulu[key]["ID"]==982 || osiotDTO_taulu[key]["ID"]==986 || osiotDTO_taulu[key]["ID"]==990){
				if(!piilota_poim_muuttujat_biopankit){
					if(typeof osiotDTO_taulu[key]["Osio_sisaltoDTO"] != 'undefined'){					
						if(osiotDTO_taulu[key]["Osio_sisaltoDTO"] == null){
							naytaElementClass("poim_muuttujat_biopankit");
						} else {
							if(typeof osiotDTO_taulu[key]["Osio_sisaltoDTO"]["Sisalto_boolean"] != 'undefined'){
								if(osiotDTO_taulu[key]["Osio_sisaltoDTO"]["Sisalto_boolean"]!= null){
									piilota_poim_muuttujat_biopankit = true;
									piilotaElementClass("poim_muuttujat_biopankit");
								} else {
									naytaElementClass("poim_muuttujat_biopankit");
								} 
							} else {
								naytaElementClass("poim_muuttujat_biopankit");
							}					
						}					
					} else {
						naytaElementClass("poim_muuttujat_biopankit");
					}
				}	
			} 
						
			// Osiotyyppikohtaiset funktiot loppuu
			// Päivitetään osion tila
			if(osiotDTO_taulu[key]["Tila"] != null){
				
				var naytettavia = 0;
				var piilotettavia = 0;
				
				for(t=0; t < osiotDTO_taulu[key]["Tila"].length; t++){
					
					if(osiotDTO_taulu[key]["Tila"][t]=="tyhjenna"){
						
						var osio_elementit = document.getElementsByClassName("o" + osiotDTO_taulu[key]["ID"]);
						
						for(var i=0; i < osio_elementit.length; i++){
							osio_elementit[i].value="";
							if(osio_elementit[i].checked){
								osio_elementit[i].checked = false;
							}
						}
						
					}
					
					if(osiotDTO_taulu[key]["Tila"][t]=="piilota"){
						piilotettavia = piilotettavia+1;
					}
					
					if(osiotDTO_taulu[key]["Tila"][t]=="nayta"){
						naytettavia = naytettavia+1;
					} 
										
				}
				
				if(naytettavia > 0) naytaElementClass("o" + osiotDTO_taulu[key]["ID"]);
				if(piilotettavia > 0 && naytettavia==0) piilotaElementClass("o" + osiotDTO_taulu[key]["ID"]);
				
			} else { // Oletusarvoisesti elementit näytetään, ellei toisin ole säännöissä määritetty
				var osio_elementit = document.getElementsByClassName("o" + osiotDTO_taulu[key]["ID"]);
				for(var i=0; i < osio_elementit.length; i++){
					if(osio_elementit[i].style.display=="none"){
						osio_elementit[i].style.display="";
					}
				}
			}
			
		}
	}
}

function paivita_nakymat(nakymatDTO){
	if(typeof nakymatDTO != 'undefined' && nakymatDTO != null){
		var key;
		for(key in nakymatDTO) {
			if(key!= "hakemus_esikatsele_ja_laheta" && nakymatDTO[key]["Pakollisia_tietoja_puuttuu"] != null){
				if(nakymatDTO[key]["Pakollisia_tietoja_puuttuu"]==true){
					document.getElementById("pukkimerkki_" + key + "").style.display = 'none';
				} else {
					document.getElementById("pukkimerkki_" + key + "").style.display = 'inline';
				}
			}
		}
	}
}

function poistaValintaLiitet(Kaikki1, Kaikki2) {
	document.getElementById(Kaikki1).checked = false;
	document.getElementById(Kaikki2).checked = false;
}

function valittuKaikki1kpl(Master1, Master2, Element) {
	
	var valittu = document.getElementById(Master1).checked;
	var valinnat = document.getElementsByClassName(Element);
	
	if (valittu == false){
		
		valittu = false;		
		document.getElementById(Master2).checked = false;
		
		for(k=0; k < valinnat.length; k++) {
			document.getElementsByClassName(Element)[k].checked = false;
		}
		
	} else if (valittu == true){
		
		valittu = true;
		
		for(k=0; k < valinnat.length; k++) {
			document.getElementsByClassName(Element)[k].checked = true;
		}
		
	}
	
}

function valittuKaikki2kpl(Master1, Master2, Master3, Element1, Element2) {
	
	var valittu1 = document.getElementById(Master1).checked;
	var valinnat1 = document.getElementsByClassName(Element1);
	var valinnat2 = document.getElementsByClassName(Element2);
	
	if (valittu1 == true){
		
		valittu1 = true;
		document.getElementById(Master2).checked = true;
		document.getElementById(Master3).checked = true;
		
		for(k=0; k < valinnat1.length; k++) {
			document.getElementsByClassName(Element1)[k].checked = true;
		}
		
		for(k=0; k < valinnat2.length; k++) {
			document.getElementsByClassName(Element2)[k].checked = true;
		}
		
	} else if (valittu1 == false){
		
		valittu1 = false;
		document.getElementById(Master2).checked = false;
		document.getElementById(Master3).checked = false;
		
		for(k=0; k < valinnat1.length; k++) {
			document.getElementsByClassName(Element1)[k].checked = false;
		}
		
		for(k=0; k < valinnat2.length; k++) {
			document.getElementsByClassName(Element2)[k].checked = false;
		}
		
	}
}

function deselect_indx(e,indx) {
	$('.pop-' + indx).slideFadeToggle(function() {
		e.removeClass('selected');
	});    
}
	
function deselect(e) {
	$('.pop').slideFadeToggle(function() {
		e.removeClass('selected');
	});    
}
	
$.fn.slideFadeToggle = function(easing, callback) {
	return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
};

function timerIncrement() {	
    idleTime = idleTime + 1;
    if (idleTime > 30) { // 30 min
        window.location.reload();
    }
}

function tarkistalomake(){
	var t = document.getElementById('tun');
	var s = document.getElementById('pw');
	if (t.value.length == 0 || s.value.length == 0){
		alert('Tunnus ja salasana ovat pakollisia.');
		valitse('tun');
		return false;
	}
	return true;
}
