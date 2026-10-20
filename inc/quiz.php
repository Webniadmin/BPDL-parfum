<?php
/**
 * Quiz "Koji si karakter?" and gift flow "Pronađi njihov miris" — configuration.
 *
 * Results reference perfumes by their quiz key (product meta "_bpdl_key", default: product slug).
 * The logic itself runs in assets/js/theme.js.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Self quiz configuration.
 *
 * @return array
 */
function webni_quiz_config() {
	$config = array(
		'intro' => array(
			'eyebrow' => __( 'Koji si karakter?', 'webni-custom-version-1' ),
			'title'   => __( 'Ne možeš da omirišeš ekran.', 'webni-custom-version-1' ),
			'text'    => __( 'Osam recepata koje je napisao AI pokrivaju sve što ljudi nose. Pet pitanja, 40 sekundi — i znaš koji je tvoj. Bez pogrešnih odgovora.', 'webni-custom-version-1' ),
			'cta'     => __( 'Kreni', 'webni-custom-version-1' ),
		),
		'q0' => array( 'q' => __( 'Za koga biraš?', 'webni-custom-version-1' ), 'a' => array( array( 'self', __( 'Za sebe', 'webni-custom-version-1' ) ), array( 'gift', __( 'Za poklon', 'webni-custom-version-1' ) ) ) ),
		'q1' => array( 'q' => __( 'Za koga je parfem?', 'webni-custom-version-1' ), 'a' => array( array( 'him', __( 'Za njega', 'webni-custom-version-1' ) ), array( 'her', __( 'Za nju', 'webni-custom-version-1' ) ), array( 'any', __( 'Svejedno — miris nema pol', 'webni-custom-version-1' ) ) ) ),
		'q2' => array( 'q' => __( 'Kada ćeš ga najviše nositi?', 'webni-custom-version-1' ), 'a' => array( array( 'dan', __( 'Svaki dan', 'webni-custom-version-1' ), __( 'od jutra do kad zaspim', 'webni-custom-version-1' ) ), array( 'posao', __( 'Na poslu', 'webni-custom-version-1' ), __( 'sastanci, kancelarija, lift', 'webni-custom-version-1' ) ), array( 'izlazak', __( 'Kad izlazim', 'webni-custom-version-1' ), __( 'veče je moje', 'webni-custom-version-1' ) ) ) ),
		'q3' => array(
			'dan'     => array( 'q' => __( 'Kako želiš da mirišeš?', 'webni-custom-version-1' ), 'a' => array( array( 'sveze', __( 'Sveže', 'webni-custom-version-1' ), __( 'kao vazduh posle kiše', 'webni-custom-version-1' ) ), array( 'toplo', __( 'Toplo', 'webni-custom-version-1' ), __( 'kao omiljeni džemper', 'webni-custom-version-1' ) ) ) ),
			'posao'   => array( 'q' => __( 'Koliko da se oseti?', 'webni-custom-version-1' ), 'a' => array( array( 'oseti', __( 'Da me kolege osete', 'webni-custom-version-1' ), __( 'čim uđem', 'webni-custom-version-1' ) ), array( 'prijatno', __( 'Prijatno', 'webni-custom-version-1' ), __( 'samo kad priđu', 'webni-custom-version-1' ) ) ) ),
			'izlazak' => array( 'q' => __( 'Kakvo je veče?', 'webni-custom-version-1' ), 'a' => array( array( 'vecera', __( 'Večera i razgovor', 'webni-custom-version-1' ), __( 'do kasno', 'webni-custom-version-1' ) ), array( 'klub', __( 'Klub', 'webni-custom-version-1' ), __( 'do jutra', 'webni-custom-version-1' ) ) ) ),
		),
		'q4' => array( 'q' => __( 'Šta te najbolje opisuje?', 'webni-custom-version-1' ), 'a' => array( array( 'priroda', __( 'Priroda i tišina', 'webni-custom-version-1' ) ), array( 'grad', __( 'Grad i brzina', 'webni-custom-version-1' ) ), array( 'klasika', __( 'Klasika i red', 'webni-custom-version-1' ) ), array( 'lepo', __( 'Lepe stvari i smeh', 'webni-custom-version-1' ) ) ) ),
		'email' => array(
			'title' => __( 'Gde da pošaljemo tvoj karakter?', 'webni-custom-version-1' ),
			'text'  => __( 'Da ga ne zaboraviš — i da ti prvi stigne.', 'webni-custom-version-1' ),
			'skip'  => __( 'Preskoči, samo mi pokaži', 'webni-custom-version-1' ),
		),
		'results' => array(
			'dan-sveze'       => array( 'him' => array( 'terros', __( 'Svaki dan, sveže: Terros je jutro koje traje do večeri.', 'webni-custom-version-1' ) ), 'her' => array( 'lemorae', __( 'Svaki dan, sveže: Lemorae je sunce koje nosiš sa sobom.', 'webni-custom-version-1' ) ) ),
			'dan-toplo'       => array( 'him' => array( 'arden', __( 'Svaki dan, toplo: Arden je džemper koji ti stoji i u kancelariji.', 'webni-custom-version-1' ) ), 'her' => array( 'deuxieme', __( 'Svaki dan, toplo: Deuxième je bela košulja među parfemima, uvek prikladan, nikad dosadan.', 'webni-custom-version-1' ) ) ),
			'posao-oseti'     => array( 'him' => array( 'arden', __( 'Posao, ali da se oseti: Arden je za sastanke, ne za lift.', 'webni-custom-version-1' ) ), 'her' => array( 'deuxieme', __( 'Posao, ali da se oseti: Deuxième uđe pre tebe i ostane posle sastanka.', 'webni-custom-version-1' ) ) ),
			'posao-prijatno'  => array( 'him' => array( 'orren', __( 'Posao, diskretno: Orren se oseti tek kad ti neko priđe — i onda se pamti.', 'webni-custom-version-1' ) ), 'her' => array( 'lemorae', __( 'Posao, diskretno: Lemorae je svežina koju kolege primete tek kad priđu.', 'webni-custom-version-1' ) ) ),
			'izlazak-vecera'  => array( 'him' => array( 'ravon', __( 'Večera i razgovor: Ravon ne diže glas, ali ostane na jakni i sutradan.', 'webni-custom-version-1' ) ), 'her' => array( 'deuxieme', __( 'Večera i razgovor: Deuxième je elegancija koja se ne trudi.', 'webni-custom-version-1' ) ) ),
			'izlazak-klub'    => array( 'him' => array( 'secondo', __( 'Klub do jutra: Secondo je jedini koji izdrži do kraja noći.', 'webni-custom-version-1' ) ), 'her' => array( 'luna', __( 'Klub do jutra: Luna sija tek kad padne noć.', 'webni-custom-version-1' ) ), 'any' => array( 'ravon', __( 'Klub do jutra: Ravon ne juri — čeka, i ostane duže od svih.', 'webni-custom-version-1' ) ) ),
		),
		'styleAlt' => array( 'priroda' => array( 'terros', 'lemorae' ), 'grad' => array( 'secondo', 'luna' ), 'klasika' => array( 'orren', 'deuxieme' ), 'lepo' => array( 'ravon', 'deuxieme' ) ),
		'result' => array(
			'eyebrow'   => __( 'Karakter %1$s od %2$s', 'webni-custom-version-1' ),
			'title'     => __( 'Ti si %s.', 'webni-custom-version-1' ),
			'see'       => __( 'Vidi %s', 'webni-custom-version-1' ),
			'discovery' => __( 'Probaj u discovery setu', 'webni-custom-version-1' ),
			'notIt'     => __( 'Nije to? Probaj', 'webni-custom-version-1' ),
			'share'     => __( 'Ja sam %s. Koji si ti?', 'webni-custom-version-1' ),
			'shareBtn'  => __( 'Podeli', 'webni-custom-version-1' ),
			'again'     => __( 'Uradi ponovo', 'webni-custom-version-1' ),
			'back'      => __( 'Vrati se', 'webni-custom-version-1' ),
			'of'        => __( '%1$s od %2$s', 'webni-custom-version-1' ),
			'close'     => __( 'Zatvori', 'webni-custom-version-1' ),
		),
	);
	return apply_filters( 'webni_quiz_config', $config );
}

/**
 * Gift flow configuration.
 *
 * @return array
 */
function webni_gift_config() {
	$config = array(
		'intro' => array(
			'eyebrow' => __( 'Pronađi njihov miris', 'webni-custom-version-1' ),
			'title'   => __( 'Ne znaš šta voli? Znaš kad je rođen.', 'webni-custom-version-1' ),
			'text'    => __( 'Pet pitanja o osobi kojoj poklanjaš. Ako ni to nije dovoljno, uvek postoji discovery set.', 'webni-custom-version-1' ),
			'cta'     => __( 'Kreni', 'webni-custom-version-1' ),
		),
		'g1' => array( 'q' => __( 'Za koga je poklon?', 'webni-custom-version-1' ), 'a' => array( array( 'him', __( 'Za njega', 'webni-custom-version-1' ) ), array( 'her', __( 'Za nju', 'webni-custom-version-1' ) ) ) ),
		'g2' => array( 'q' => __( 'Znaš li znak?', 'webni-custom-version-1' ), 'unknown' => __( 'Ne znam', 'webni-custom-version-1' ) ),
		'g3' => array( 'q' => __( 'Kakva je osoba?', 'webni-custom-version-1' ), 'a' => array( array( 'priroda', __( 'Priroda i tišina', 'webni-custom-version-1' ) ), array( 'grad', __( 'Grad i brzina', 'webni-custom-version-1' ) ), array( 'klasika', __( 'Klasika i red', 'webni-custom-version-1' ) ), array( 'lepo', __( 'Lepe stvari i smeh', 'webni-custom-version-1' ) ) ) ),
		'g4' => array( 'q' => __( 'Kad će ga nositi?', 'webni-custom-version-1' ), 'a' => array( array( 'dan', __( 'Svaki dan', 'webni-custom-version-1' ) ), array( 'posao', __( 'Na poslu', 'webni-custom-version-1' ) ), array( 'izlazak', __( 'Kad izlazi', 'webni-custom-version-1' ) ) ) ),
		'g5' => array( 'q' => __( 'Voli jake mirise?', 'webni-custom-version-1' ), 'a' => array( array( 'jako', __( 'Da, neka se oseti', 'webni-custom-version-1' ) ), array( 'diskretno', __( 'Ne, diskretno', 'webni-custom-version-1' ) ) ) ),
		'byStyle' => array( 'priroda' => array( 'terros', 'lemorae' ), 'grad' => array( 'secondo', 'luna' ), 'klasika' => array( 'arden', 'deuxieme' ), 'lepo' => array( 'ravon', 'deuxieme' ) ),
		'calm'    => array( 'secondo' => 'orren', 'ravon' => 'arden', 'luna' => 'deuxieme' ),
		'result' => array(
			'eyebrow' => __( 'Poklon', 'webni-custom-version-1' ),
			'forSign' => __( 'Za %1$s, %2$s: %3$s.', 'webni-custom-version-1' ),
			'forHim'  => __( 'Za njega: %s.', 'webni-custom-version-1' ),
			'forHer'  => __( 'Za nju: %s.', 'webni-custom-version-1' ),
			'card'    => __( 'U paketu je kartica karaktera: piše „Ti si %s“, pa poklon ima priču pre nego što se otvori.', 'webni-custom-version-1' ),
			'send'    => __( 'Pošalji poklon', 'webni-custom-version-1' ),
			'unsure'  => __( 'Nisi siguran? Discovery set.', 'webni-custom-version-1' ),
			'note'    => __( 'Horoskop je igra. Miris je stvaran.', 'webni-custom-version-1' ),
		),
	);
	return apply_filters( 'webni_gift_config', $config );
}
