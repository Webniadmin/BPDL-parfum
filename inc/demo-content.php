<?php
/**
 * Default content for the pages created on activation (Serbian Latin).
 *
 * Legal texts are drafts: values in [square brackets] must be filled in with the
 * company data and the whole text reviewed by the client's lawyer before launch.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page content by slug.
 *
 * @param string $slug Page slug.
 * @return string HTML.
 */
function webni_demo_page_content( $slug ) {
	$content = array();

	$content['pomoc'] = <<<'HTML'
<p class="lead">Sve što te zanima pre i posle porudžbine. Ako nešto nedostaje, <a href="/kontakt/">piši nam</a>.</p>

<h2>Dostava</h2>
<p>Dostavu radi kurirska služba na teritoriji Srbije, radnim danima. Porudžbine primljene radnim danom do 14h šalju se istog dana; paket obično stiže za 1–3 radna dana. Dostava je 390 RSD, a za porudžbine iznad 9.000 RSD je besplatna.</p>

<h2>Plaćanje</h2>
<p>Pouzećem — kuriru pri preuzimanju. Plaćanje karticom (Visa, Mastercard, Maestro, Dina) preko sigurne stranice banke biće dostupno uskoro.</p>

<h2>Koliko parfem traje?</h2>
<p>Svaka bočica ima 50 ml i 30% parfemskog ulja. Tri spreja ujutru — vrat, zglob, odeća — i ništa do večeri. Pri uobičajenom korišćenju bočica traje više meseci.</p>

<h2>Ne znam koji je moj karakter</h2>
<p>Uradi <a href="/#quiz">quiz „Koji si karakter?“</a> — pet pitanja, 40 sekundi. Ako i dalje nisi siguran, uzmi <a href="/parfemi/">discovery set</a>: svih osam u 2 ml, a vaučer u vrednosti seta važi za punu bočicu.</p>

<h2>Poklon</h2>
<p>Svaki paket stiže sa karticom karaktera. Ako kupuješ poklon, u napomeni uz porudžbinu napiši šta da napišemo na kartici. Ne znaš šta voli? Dovoljan je horoskopski znak — <a href="/#poklon">pogledaj ovde</a>.</p>

<h2>Reklamacije i povrat</h2>
<p>Neotvoren proizvod u neoštećenom pakovanju možeš vratiti u roku od 14 dana. Detalji i obrazac su na stranici <a href="/reklamacije-i-povrat/">Reklamacije i povrat</a>.</p>
HTML;

	$content['o-nama'] = <<<'HTML'
<p class="lead">Pitali smo AI da napiše parfem koji sadrži sve ostale.</p>
<p>Dali smo mu sve što ljudi najviše nose — mirise, note, kombinacije koje se vraćaju iz godine u godinu — i tražili da to svede na najmanji izbor koji pokriva sve potrebe. Vratio je osam recepata: četiri muška, jedan unisex, tri ženska.</p>
<p>Mi smo ih napravili, testirali i zadržali ono što radi. Zato ne moraš da isprobavaš pedeset bočica da bi našao svoju — osam je dovoljno. Parfem je i dalje parfem: 30% parfemskog ulja, na koži, ne na ekranu.</p>
<p>Slova u imenu su naša porodica. Karakteri su tvoji.</p>
<h2>Zašto 30%</h2>
<p>Većina toaletnih voda ima 8–15% parfemskog ulja. Naši imaju 30%. Više ulja znači da miris ostaje na koži duže i da su dva-tri spreja ceo ritual. Zato 50 ml traje duže nego što misliš.</p>
HTML;

	$content['kontakt'] = <<<'HTML'
<p class="lead">Piši nam — odgovaramo radnim danima, obično istog dana.</p>
[webni_contact_form]
<h2>Podaci</h2>
<p>E-mail: [email]<br>Telefon: [telefon]<br>Radno vreme podrške: [dani i sati]</p>
<p>[pun naziv firme], [adresa sedišta]</p>
HTML;

	$content['podaci-o-trgovcu'] = <<<'HTML'
<p><strong>Naziv:</strong> [pun naziv firme]<br>
<strong>Adresa sedišta:</strong> [adresa sedišta]<br>
<strong>Matični broj:</strong> [MB]<br>
<strong>PIB:</strong> [PIB]<br>
<strong>Šifra delatnosti:</strong> 47.91 — trgovina na malo posredstvom pošte ili interneta<br>
<strong>E-mail:</strong> [email]<br>
<strong>Telefon:</strong> [telefon]<br>
<strong>Radno vreme podrške:</strong> [dani i sati]</p>
<p>Sajt vodi [pun naziv firme] (dalje: Prodavac). Kupovina preko sajta je prodaja na daljinu u smislu Zakona o zaštiti potrošača.</p>
HTML;

	$content['uslovi-kupovine'] = <<<'HTML'
<p class="lead">Nacrt — pre objave pregleda pravnik. Polja u uglastim zagradama popunjava firma.</p>

<h2>Opšte</h2>
<p>Sajt [domen] vodi [pun naziv firme] (dalje: Prodavac). Kupovina preko sajta je prodaja na daljinu u smislu Zakona o zaštiti potrošača; naručivanjem kupac potvrđuje da je pročitao i prihvatio ove uslove, koji važe u verziji objavljenoj u trenutku porudžbine. Sajt se koristi dobrovoljno i na sopstvenu odgovornost.</p>

<h2>Proizvodi i cene</h2>
<p>Svi parfemi su BP|DL Parfum, zapremine 50 ml, sa 30% parfemskog ulja. Cene su u dinarima (RSD) sa uračunatim PDV-om i važe u trenutku porudžbine. Fotografije su ilustrativne; boja i izgled pakovanja mogu neznatno odstupati. Prodavac zadržava pravo da promeni cene i asortiman, što ne utiče na već potvrđene porudžbine.</p>

<h2>Porudžbina</h2>
<p>Porudžbina se šalje popunjavanjem korpe i klikom na „Poruči“; kupac odmah dobija e-mail sa brojem porudžbine, spiskom artikala, cenom, načinom plaćanja i rokom isporuke. Ugovor je zaključen kad Prodavac potvrdi porudžbinu e-mailom. Prodavac može da odbije ili otkaže porudžbinu ako artikla nema na stanju ili ako podaci kupca nisu potpuni, o čemu kupca obaveštava bez odlaganja.</p>

<h2>Načini plaćanja</h2>
<p>Pouzećem (gotovinom kuriru pri preuzimanju) ili platnom karticom (Visa, Mastercard, Maestro, Dina) preko sigurne stranice banke [naziv banke]. Podaci o kartici ni u jednom trenutku nisu dostupni Prodavcu.</p>
<h3>Zaštita poverljivih podataka o transakciji</h3>
<p>Podaci o kartici unose se na sigurnoj stranici banke i prenose šifrovano (SSL); Prodavac nema pristup broju kartice ni drugim poverljivim podacima, a sigurnost transakcije garantuje banka [naziv banke] kao procesor kartica.</p>
<h3>Povraćaj sredstava</h3>
<p>Kad se kupcu koji je platio karticom vraća novac, delimično ili u celosti i bez obzira na razlog, Prodavac povraćaj obavlja isključivo preko iste kartične šeme (Visa, Mastercard, Maestro, Dina): banka na zahtev Prodavca vraća sredstva na račun korisnika kartice.</p>
<h3>Izjava o konverziji</h3>
<p>Sva plaćanja se obavljaju u dinarima (RSD). Prikaz cena u drugim valutama je informativan, po srednjem kursu Narodne banke Srbije; iznos kojim se tereti kartica izdata u drugoj valuti dobija se konverzijom po kursu kartične organizacije ili banke izdavaoca, pa je moguća mala razlika u odnosu na cenu na sajtu.</p>

<h2>Isporuka</h2>
<p>Isporuku vrši kurirska služba [kurir] na teritoriji Republike Srbije, radnim danima. Porudžbine primljene radnim danom do [sat] šalju se istog dana; uobičajen rok isporuke je 1–3 radna dana od slanja. Cena dostave je [iznos] RSD; za porudžbine iznad [prag] RSD dostava je besplatna. Pri preuzimanju kupac proverava da li je paket spolja oštećen; ako jeste, preporučujemo da prijem odbije i odmah obavesti Prodavca.</p>

<h2>Odustanak od ugovora i reklamacije</h2>
<p>Uređeni su stranicom <a href="/reklamacije-i-povrat/">Reklamacije i povrat</a>, koja je sastavni deo ovih uslova.</p>

<h2>Intelektualna svojina</h2>
<p>Imena parfema, karakteri, tekstovi, fotografije i vizuali na sajtu su vlasništvo Prodavca i ne smeju se umnožavati ni koristiti bez pisanog odobrenja.</p>

<h2>Odgovornost i sporovi</h2>
<p>Prodavac se trudi da sajt radi bez prekida, ali ne odgovara za štetu nastalu privremenom nedostupnošću, greškama u prikazu ili radnjama trećih lica. Na ove uslove primenjuje se pravo Republike Srbije; sporove strane rešavaju mirno, a ako to ne uspe, nadležan je sud u [grad]. Potrošač može da pokrene i vansudsko rešavanje spora.</p>

<h2>Izmene</h2>
<p>Prodavac može da menja ove uslove; važeća verzija je uvek objavljena na sajtu sa datumom poslednje izmene: [datum].</p>
HTML;

	$content['politika-privatnosti'] = <<<'HTML'
<p class="lead">Nacrt — pre objave pregleda pravnik. Polja u uglastim zagradama popunjava firma.</p>

<h2>Rukovalac</h2>
<p>[Pun naziv firme], [adresa], [email za privatnost]. Podatke obrađujemo po Zakonu o zaštiti podataka o ličnosti Republike Srbije.</p>

<h2>Koje podatke prikupljamo i zašto</h2>
<table>
<thead><tr><th>Podaci</th><th>Svrha</th><th>Pravni osnov</th><th>Koliko čuvamo</th></tr></thead>
<tbody>
<tr><td>ime i prezime, adresa isporuke, e-mail, telefon</td><td>obrada i isporuka porudžbine, reklamacije</td><td>izvršenje ugovora; zakonska obaveza (računovodstvo)</td><td>10 godina za računovodstvenu dokumentaciju, inače do isteka reklamacionih rokova</td></tr>
<tr><td>e-mail i ime (newsletter)</td><td>obaveštenja o karakterima, ponudama i lansiranjima</td><td>pristanak, koji se opoziva jednim klikom u svakom mejlu</td><td>do opoziva</td></tr>
<tr><td>odgovori u quizu „Koji si karakter?“ i rezultat</td><td>preporuka parfema; ako ostaviš e-mail, slanje rezultata</td><td>pristanak (polje za e-mail može da se preskoči)</td><td>do opoziva; bez e-maila se ne čuvaju uz identitet</td></tr>
<tr><td>datum rođenja ili horoskopski znak (ako ih uneseš)</td><td>rođendanska preporuka</td><td>pristanak</td><td>do opoziva</td></tr>
<tr><td>podaci o korišćenju sajta (kolačići, IP, uređaj, stranice)</td><td>rad sajta, statistika, oglašavanje</td><td>legitimni interes (neophodni kolačići); pristanak (analitika i marketing)</td><td>prema tabeli kolačića</td></tr>
</tbody>
</table>

<h2>Kome podatke poveravamo</h2>
<p>Kurirskoj službi [kurir] radi isporuke, banci [banka] radi plaćanja karticom (Prodavac ne vidi podatke o kartici), pružaocu e-mail usluge [servis], hosting provajderu [hosting] i, uz tvoj pristanak na marketinške kolačiće, Meta Platforms i Google (Google Analytics, Google Tag Manager) i TikTok radi merenja i oglašavanja. Podatke ne prodajemo. Neki od ovih primalaca obrađuju podatke van Srbije, uz zaštitne mere koje zakon predviđa.</p>

<h2>Tvoja prava</h2>
<p>Pravo na pristup, ispravku, brisanje, ograničenje obrade, prenosivost, prigovor i opoziv pristanka u svakom trenutku, bez uticaja na obradu pre opoziva. Zahtev šalješ na [email za privatnost]; odgovaramo u roku od 30 dana. Imaš pravo i na pritužbu Povereniku za informacije od javnog značaja i zaštitu podataka o ličnosti.</p>

<h2>Deca</h2>
<p>Sajt je namenjen osobama sa navršenih 15 godina; mlađi od 15 ne mogu da daju pristanak za obradu podataka ni da naručuju bez roditelja ili staratelja.</p>

<h2>Bezbednost</h2>
<p>Sajt koristi HTTPS (SSL) šifrovanje; pristup podacima imaju samo osobe kojima su potrebni za posao.</p>

<h2>Kolačići</h2>
<p>Pri prvoj poseti biraš koji kolačići se uključuju; neophodni rade uvek, a analitički i marketinški tek posle pristanka. Izbor menjaš u svakom trenutku preko linka „Podešavanje kolačića“ u futeru.</p>
<table>
<thead><tr><th>Vrsta</th><th>Primer</th><th>Svrha</th><th>Trajanje</th></tr></thead>
<tbody>
<tr><td>neophodni</td><td>sesija, korpa, saglasnost za kolačiće</td><td>rad sajta i korpe</td><td>sesija do 12 meseci</td></tr>
<tr><td>analitički</td><td>Google Analytics 4, Microsoft Clarity</td><td>statistika posete i snimci sesija bez ličnih podataka</td><td>do 24 meseca</td></tr>
<tr><td>marketinški</td><td>Meta Pixel, TikTok Pixel, Google Ads</td><td>merenje i prilagođavanje oglasa</td><td>do 13 meseci</td></tr>
</tbody>
</table>
HTML;

	$content['reklamacije-i-povrat'] = <<<'HTML'
<p class="lead">Nacrt — pre objave pregleda pravnik. Polja u uglastim zagradama popunjava firma.</p>

<h2>Pravo na odustanak (14 dana)</h2>
<p>Kupac ima pravo da odustane od kupovine u roku od 14 dana od dana kad je paket primio, bez navođenja razloga. Odustanak se izjavljuje na obrascu ispod ili na drugi nedvosmislen način (e-mail na [email] sa brojem porudžbine); Prodavac bez odlaganja potvrđuje prijem. Robu kupac vraća najkasnije 14 dana od dana kad je poslao izjavu, na adresu [adresa za povrat], o svom trošku vraćanja. Prodavac vraća ceo plaćeni iznos najkasnije 14 dana od prijema izjave, a može da sačeka dok roba ne stigne ili dok kupac ne pošalje dokaz da ju je poslao. Novac se vraća istim putem kojim je plaćeno: kod kartice preko banke na karticu, kod pouzeća na račun naveden u obrascu.</p>

<h2>Kad odustanak nije moguć</h2>
<p>Parfem je zapečaćena roba koja se ne može vratiti iz higijenskih razloga i razloga zaštite zdravlja ako je otpečaćena posle isporuke: bočica ili discovery set koji su otvoreni ne mogu se vratiti po osnovu odustanka. Neotvoren proizvod u neoštećenom originalnom pakovanju vraća se bez problema.</p>

<h2>Saobraznost i reklamacija</h2>
<p>Prodavac odgovara za saobraznost robe dve godine od isporuke. Reklamacija zbog oštećenja, nesaobraznosti, pogrešno isporučenog artikla ili pogrešno obračunate cene izjavljuje se e-mailom na [email], telefonom na [telefon] ili poštom na [adresa], uz račun ili broj porudžbine. Prodavac vodi evidenciju reklamacija, kupcu odmah izdaje potvrdu o prijemu sa brojem reklamacije i najkasnije u roku od 8 dana pisanim ili elektronskim putem odgovara: da li reklamaciju prihvata, šta predlaže i u kom roku. Rok za rešavanje je najviše 15 dana od prijema reklamacije; produženje je moguće samo jednom, uz saglasnost kupca. Ako je reklamacija opravdana, kupac po izboru dobija zamenu, umanjenje cene ili povraćaj novca; ako je odbijena, Prodavac ga obaveštava o mogućnosti vansudskog rešavanja spora. Oštećenje u transportu prijavljuje se kuriru pri preuzimanju (zapisnik) i Prodavcu istog dana.</p>

<h2>Vansudsko rešavanje potrošačkih sporova</h2>
<p>Prodavac je po zakonu dužan da učestvuje u postupku vansudskog rešavanja potrošačkih sporova pred telom sa liste Ministarstva (<a href="https://vansudsko.mtt.gov.rs/adrbodies" rel="noopener" target="_blank">lista tela</a>). Postupak može da pokrene kupac koji je prethodno izjavio reklamaciju, najkasnije godinu dana od njenog podnošenja; rad tela je besplatan za strane.</p>

<h2>Obrazac za odustanak od ugovora</h2>
<pre>OBRAZAC ZA ODUSTANAK OD UGOVORA ZAKLJUČENOG NA DALJINU

Primalac: [pun naziv firme], [adresa], [email]

Ovim obaveštavam da odustajem od ugovora o prodaji sledeće robe:
Artikal/artikli: ______________________________
Broj porudžbine: ______________   Datum porudžbine: __________   Datum prijema: __________
Ime i prezime potrošača: ______________________________
Adresa potrošača: ______________________________
E-mail / telefon: ______________________________
Broj računa za povraćaj (samo za plaćanje pouzećem): ______________________________

Datum: __________            Potpis (samo ako se šalje poštom): ______________</pre>
HTML;

	return isset( $content[ $slug ] ) ? $content[ $slug ] : '';
}
