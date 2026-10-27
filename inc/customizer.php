<?php
/**
 * Theme settings: Customizer panel "BP|DL Parfum".
 *
 * Texts, CTAs, rails, contact data and Gravity Forms IDs. Product data stays in WooCommerce.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values for every option (used by webni_option()).
 *
 * @return array
 */
function webni_option_defaults() {
	return array(
		// Hero.
		'hero_mode'         => 'animated', // animated (characters cycle over the mist) | image
		'hero_mist'         => 50,         // mist opacity in %, home + product heroes
		'hero_image'        => 0,         // attachment ID; falls back to the first perfume's image
		'hero_image_mobile' => 0,         // optional portrait attachment for phones (cover layout)
		'hero_layout'       => 'cover',   // cover | split
		'hero_image_pos'    => 'center',  // object-position for the cover layout
		'hero_eyebrow'      => 'BP|DL Parfum · Osam recepata koje je napisao AI',
		'hero_title_1'      => 'Osam karaktera.',
		'hero_title_2'      => 'Jedan je tvoj.',
		'hero_sub'          => 'Pitali smo AI da iz svega što ljudi najviše nose napiše osam parfema koji pokrivaju sve. Ne moraš da isprobavaš pedeset bočica — dovoljno je da znaš koji si karakter.',
		'hero_cycle_prefix' => 'Ti si',
		'hero_cta1'         => 'Koji si karakter?',
		'hero_cta2'         => 'Pogledaj kolekciju',
		'hero_cta2_url'     => '#kolekcija',
		// Marquee.
		'marquee_items'     => "30% parfemskog ulja\nOsam karaktera. Jedan je tvoj.\n50 ml\nNe možeš da omirišeš ekran",
		// Collection.
		'collection_eyebrow' => 'Kolekcija',
		'collection_title'   => 'Upoznaj ih.',
		// Rails.
		'rail_featured_on'    => 1,
		'rail_featured_title' => 'Istaknuti parfemi',
		'rail_new_on'         => 0,
		'rail_new_title'      => 'Novo',
		'rail_best_on'        => 0,
		'rail_best_title'     => 'Najprodavaniji',
		'rail_sale_on'        => 1,
		'rail_sale_title'     => 'Akcije',
		'rail_recommended_on'    => 0,
		'rail_recommended_title' => 'Preporučeni parfemi',
		'rail_recommended_ids'   => '',
		// Quality.
		'quality_eyebrow' => 'Zašto 30%',
		'quality_title_1' => '30% parfemskog ulja.',
		'quality_title_2' => '100% karakter.',
		'quality_text'    => 'Većina toaletnih voda ima 8–15% parfemskog ulja. Naši imaju 30%. Više ulja znači da miris ostaje na koži duže i da su dva-tri spreja ceo ritual. Zato 50 ml traje duže nego što misliš.',
		'badge1_value'    => '30%',
		'badge1_label'    => 'parfemskog ulja',
		'badge2_value'    => '3',
		'badge2_label'    => 'spreja ujutru',
		'badge3_value'    => '50 ml',
		'badge3_label'    => 'u svakoj bočici',
		'bar1_label'      => 'Toaletna voda (EDT)',
		'bar1_value'      => '8–15%',
		'bar1_pct'        => 15,
		'bar2_label'      => 'Parfemska voda (EDP)',
		'bar2_value'      => '15–20%',
		'bar2_pct'        => 20,
		'bar3_label'      => 'BP|DL Parfum',
		'bar3_value'      => '30%',
		'bar3_pct'        => 30,
		'quality_note'    => 'Udeo parfemskog ulja po kategoriji; brojke za kategorije su opšte, ne tvrdnje o pojedinim brendovima.',
		// Quiz.
		'quiz_eyebrow'    => 'Quiz',
		'quiz_title'      => 'Ne možeš da omirišeš ekran.',
		'quiz_text'       => 'Odgovori na pet pitanja i saznaj koji si karakter — pre nego što probaš.',
		'quiz_cta'        => 'Kreni',
		'quiz_note'       => '40 sekundi · bez registracije · bez pogrešnih odgovora',
		'quiz_gf_id'      => 0,
		// Gift.
		'gift_eyebrow'      => 'Poklon',
		'gift_title_1'      => 'Ne poklanjaj parfem.',
		'gift_title_2'      => 'Poklanjaj karakter.',
		'gift_text'         => 'Znaš samo kad je rođen? Dovoljno. Izaberi znak — dobijaš karakter za njega i za nju.',
		'gift_note'         => 'Horoskop je igra. Miris je stvaran.',
		'gift_card1_eyebrow' => 'Pronađi njihov miris',
		'gift_card1_title'   => 'Ne znaš šta voli? Znaš kad je rođen.',
		'gift_card1_text'    => 'Pet pitanja o osobi kojoj poklanjaš. Rezultat stiže sa karticom karaktera u paketu.',
		'gift_card1_cta'     => 'Kreni',
		'discovery_product'  => 0,
		'discovery_eyebrow'  => 'Discovery set',
		'discovery_title'    => 'Ne znaš koji je karakter? Probaj svih osam.',
		'discovery_text'     => 'Svih osam u 2 ml. Jedan dan po karakteru. Vaučer u vrednosti seta za punu bočicu.',
		'pair_product'       => 0,
		'pair_eyebrow'       => 'Par set',
		'pair_title'         => 'Secondo + Deuxième',
		'pair_line'          => 'Drugi na dva jezika. Prvi jedno drugom.',
		'pair_text'          => 'Dva parfema, jedna priča. Za dvoje koji se ne takmiče ko je prvi.',
		'pair_section_on'    => 1,
		'pair_section_cta'   => 'Uzmi par',
		'pair_section_note'  => 'Dva parfema od 50 ml u jednoj kutiji, sa dve kartice karaktera.',
		// Story.
		'story_eyebrow' => 'Priča',
		'story_title'   => 'Pitali smo AI da napiše parfem koji sadrži sve ostale.',
		'story_text_1'  => 'Dali smo mu sve što ljudi najviše nose — mirise, note, kombinacije koje se vraćaju — i tražili da to svede na najmanji izbor koji pokriva sve potrebe. Vratio je osam recepata: četiri muška, jedan unisex, tri ženska.',
		'story_text_2'  => 'Mi smo ih napravili, testirali i zadržali ono što radi. Zato ne moraš da isprobavaš pedeset bočica da bi našao svoju — osam je dovoljno. Parfem je i dalje parfem: 30% parfemskog ulja, na koži, ne na ekranu.',
		'story_note'    => 'Slova u imenu su naša porodica. Karakteri su tvoji.',
		// Newsletter.
		'newsletter_eyebrow' => 'Newsletter',
		'newsletter_title'   => 'Saznaj svoj karakter pre svih.',
		'newsletter_text'    => 'Jedan mejl nedeljno, ništa više. Prvi karakter stiže pre lansiranja.',
		'newsletter_gf_id'   => 0,
		'contact_gf_id'      => 0,
		// Shop.
		'shop_title'         => 'Parfemi',
		'shop_intro'         => 'Osam recepata, osam karaktera. Jedan je tvoj.',
		'shop_delivery_line' => 'dostava za 1–3 radna dana · pouzeće ili kartica',
		'free_shipping_text' => 'besplatna dostava od {prag} RSD', // {prag} = threshold from the WooCommerce shipping zone
		'product_how_long'   => 'Tri spreja ujutru: vrat, zglob, odeća. Ništa do večeri.',
		'product_notes_lead' => 'Recept je napisao AI: iz svega što se najčešće nosi u ovoj kategoriji, svedeno na jednu formulu. Vrh traje minute, srce sate, baza do večeri.',
		// Footer / contact.
		'footer_tagline'  => 'Osam karaktera. Jedan je tvoj.',
		'footer_note'     => 'Osam recepata koje je napisao AI. Ljudi su ih napravili i birali šta ostaje.',
		'contact_email'   => '',
		'contact_phone'   => '',
		'contact_address' => '',
		'social_instagram' => '',
		'social_tiktok'    => '',
		'social_facebook'  => '',
		'social_youtube'   => '',
		'payment_line'    => 'Visa · Mastercard · Dina · Pouzeće',
		// Merchant data (inc/merchant.php).
		'company_name'    => '',
		'company_address' => '',
		'company_mb'      => '',
		'company_pib'     => '',
		'support_hours'   => '',
		'return_address'  => '',
		'privacy_email'   => '',
		'courier'         => '',
		'dispatch_cutoff' => '',
		'court_city'      => '',
		'bank_name'       => 'Raiffeisen banka a.d. Beograd',
		'legal_updated'   => '',
		'payment_logos'   => 0,
		'copyright'       => '© {year} BP|DL Parfum',
	);
}

/**
 * Register panel, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function webni_customize_register( $wp_customize ) {
	$defaults = webni_option_defaults();

	$wp_customize->add_panel( 'webni_panel', array(
		'title'       => 'BP|DL Parfum',
		'description' => 'Tekstovi, pozivi na akciju, sekcije sa proizvodima, kontakt i Gravity Forms. Proizvodi i cene se uređuju u WooCommerce-u.',
		'priority'    => 10,
	) );

	$sections = array(
		'hero'       => 'Hero (prvi ekran)',
		'collection' => 'Kolekcija i sekcije sa proizvodima',
		'quality'    => 'Zašto 30%',
		'quiz'       => 'Quiz',
		'gift'       => 'Poklon',
		'story'      => 'Priča',
		'newsletter' => 'Newsletter i forme',
		'shop'       => 'Prodavnica i stranica proizvoda',
		'footer'     => 'Futer i kontakt',
		'merchant'   => 'Podaci o trgovcu',
	);
	$i = 0;
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( 'webni_' . $id, array( 'title' => $title, 'panel' => 'webni_panel', 'priority' => ++$i ) );
	}

	// [key, section, label, type, extra]
	$fields = array(
		array( 'hero_mode', 'hero', 'Prikaz prvog ekrana', 'select', array(
			'choices' => array(
				'animated' => 'Osam karaktera se smenjuju, magla u pozadini',
				'image'    => 'Jedna velika slika (bez smenjivanja)',
			),
		) ),
		array( 'hero_mist', 'hero', 'Jačina magle u pozadini (%) — manje = prozirnije; važi i za stranice parfema', 'number' ),
		array( 'hero_image', 'hero', 'Velika slika', 'image', array(
			'description' => 'Preporuka: horizontalna fotografija, najmanje 2000 × 1200 px, tamnija sa leve strane gde stoji tekst. Ako slika nije izabrana, koristi se slika prvog parfema pored teksta.',
		) ),
		array( 'hero_image_mobile', 'hero', 'Slika za telefon (opciono)', 'image', array(
			'description' => 'Uspravna verzija iste fotografije (npr. 1200 × 1800 px) za ekrane uže od 900 px. Ako nije izabrana, na telefonu se seče velika slika.',
		) ),
		array( 'hero_layout', 'hero', 'Raspored slike', 'select', array(
			'choices' => array(
				'cover' => 'Preko celog ekrana, tekst preko slike',
				'split' => 'Slika desno, tekst levo',
			),
		) ),
		array( 'hero_image_pos', 'hero', 'Koji deo slike ostaje vidljiv pri sečenju', 'select', array(
			'choices' => array(
				'center' => 'Sredina',
				'top'    => 'Gore',
				'bottom' => 'Dole',
				'left'   => 'Levo',
				'right'  => 'Desno',
			),
		) ),
		array( 'hero_eyebrow', 'hero', 'Nadnaslov', 'text' ),
		array( 'hero_title_1', 'hero', 'Naslov — prvi red', 'text' ),
		array( 'hero_title_2', 'hero', 'Naslov — drugi red (kurziv)', 'text' ),
		array( 'hero_sub', 'hero', 'Podnaslov', 'textarea' ),
		array( 'hero_cycle_prefix', 'hero', 'Tekst ispred karaktera koji se smenjuju', 'text' ),
		array( 'hero_cta1', 'hero', 'Dugme 1 (otvara quiz)', 'text' ),
		array( 'hero_cta2', 'hero', 'Dugme 2 — tekst', 'text' ),
		array( 'hero_cta2_url', 'hero', 'Dugme 2 — link', 'url' ),
		array( 'marquee_items', 'hero', 'Pokretna traka (jedna stavka po redu)', 'textarea' ),

		array( 'collection_eyebrow', 'collection', 'Kolekcija — nadnaslov', 'text' ),
		array( 'collection_title', 'collection', 'Kolekcija — naslov', 'text' ),
		array( 'rail_featured_on', 'collection', 'Prikaži „Istaknuti parfemi“ (proizvodi označeni kao Featured)', 'checkbox' ),
		array( 'rail_featured_title', 'collection', 'Naslov sekcije „Istaknuti“', 'text' ),
		array( 'rail_new_on', 'collection', 'Prikaži „Novo“ (najnoviji proizvodi)', 'checkbox' ),
		array( 'rail_new_title', 'collection', 'Naslov sekcije „Novo“', 'text' ),
		array( 'rail_best_on', 'collection', 'Prikaži „Najprodavaniji“', 'checkbox' ),
		array( 'rail_best_title', 'collection', 'Naslov sekcije „Najprodavaniji“', 'text' ),
		array( 'rail_sale_on', 'collection', 'Prikaži „Akcije“ (proizvodi na akciji)', 'checkbox' ),
		array( 'rail_sale_title', 'collection', 'Naslov sekcije „Akcije“', 'text' ),
		array( 'rail_recommended_on', 'collection', 'Prikaži „Preporučeni“', 'checkbox' ),
		array( 'rail_recommended_title', 'collection', 'Naslov sekcije „Preporučeni“', 'text' ),
		array( 'rail_recommended_ids', 'collection', 'Preporučeni — ID-jevi proizvoda odvojeni zarezom', 'text' ),

		array( 'quality_eyebrow', 'quality', 'Nadnaslov', 'text' ),
		array( 'quality_title_1', 'quality', 'Naslov — prvi red', 'text' ),
		array( 'quality_title_2', 'quality', 'Naslov — drugi red', 'text' ),
		array( 'quality_text', 'quality', 'Tekst', 'textarea' ),
		array( 'badge1_value', 'quality', 'Bedž 1 — vrednost', 'text' ),
		array( 'badge1_label', 'quality', 'Bedž 1 — opis', 'text' ),
		array( 'badge2_value', 'quality', 'Bedž 2 — vrednost', 'text' ),
		array( 'badge2_label', 'quality', 'Bedž 2 — opis', 'text' ),
		array( 'badge3_value', 'quality', 'Bedž 3 — vrednost', 'text' ),
		array( 'badge3_label', 'quality', 'Bedž 3 — opis', 'text' ),
		array( 'bar1_label', 'quality', 'Traka 1 — naziv', 'text' ),
		array( 'bar1_value', 'quality', 'Traka 1 — prikazana vrednost', 'text' ),
		array( 'bar1_pct', 'quality', 'Traka 1 — dužina (%)', 'number' ),
		array( 'bar2_label', 'quality', 'Traka 2 — naziv', 'text' ),
		array( 'bar2_value', 'quality', 'Traka 2 — prikazana vrednost', 'text' ),
		array( 'bar2_pct', 'quality', 'Traka 2 — dužina (%)', 'number' ),
		array( 'bar3_label', 'quality', 'Traka 3 (BP|DL) — naziv', 'text' ),
		array( 'bar3_value', 'quality', 'Traka 3 — prikazana vrednost', 'text' ),
		array( 'bar3_pct', 'quality', 'Traka 3 — dužina (%)', 'number' ),
		array( 'quality_note', 'quality', 'Napomena ispod traka', 'textarea' ),

		array( 'quiz_eyebrow', 'quiz', 'Nadnaslov', 'text' ),
		array( 'quiz_title', 'quiz', 'Naslov', 'text' ),
		array( 'quiz_text', 'quiz', 'Tekst', 'textarea' ),
		array( 'quiz_cta', 'quiz', 'Dugme', 'text' ),
		array( 'quiz_note', 'quiz', 'Napomena ispod dugmeta', 'text' ),
		array( 'quiz_gf_id', 'quiz', 'Gravity Form ID za email u quizu (0 = bez koraka sa emailom)', 'number' ),

		array( 'gift_eyebrow', 'gift', 'Nadnaslov', 'text' ),
		array( 'gift_title_1', 'gift', 'Naslov — prvi red', 'text' ),
		array( 'gift_title_2', 'gift', 'Naslov — drugi red', 'text' ),
		array( 'gift_text', 'gift', 'Tekst', 'textarea' ),
		array( 'gift_note', 'gift', 'Napomena (horoskop)', 'text' ),
		array( 'gift_card1_eyebrow', 'gift', 'Kartica 1 — nadnaslov', 'text' ),
		array( 'gift_card1_title', 'gift', 'Kartica 1 — naslov', 'text' ),
		array( 'gift_card1_text', 'gift', 'Kartica 1 — tekst', 'textarea' ),
		array( 'gift_card1_cta', 'gift', 'Kartica 1 — dugme (otvara poklon quiz)', 'text' ),
		array( 'discovery_product', 'gift', 'Discovery set — proizvod', 'product' ),
		array( 'discovery_eyebrow', 'gift', 'Discovery set — nadnaslov', 'text' ),
		array( 'discovery_title', 'gift', 'Discovery set — naslov', 'text' ),
		array( 'discovery_text', 'gift', 'Discovery set — tekst', 'textarea' ),
		array( 'pair_product', 'gift', 'Par set — proizvod', 'product' ),
		array( 'pair_eyebrow', 'gift', 'Par set — nadnaslov', 'text' ),
		array( 'pair_title', 'gift', 'Par set — naslov', 'text' ),
		array( 'pair_line', 'gift', 'Par set — linija (kurziv)', 'text' ),
		array( 'pair_text', 'gift', 'Par set — tekst', 'textarea' ),
		array( 'pair_section_on', 'gift', 'Prikaži veliku Par set sekciju na početnoj (ispod kolekcije) i na stranicama parfema iz para', 'checkbox' ),
		array( 'pair_section_cta', 'gift', 'Par set sekcija — dugme', 'text' ),
		array( 'pair_section_note', 'gift', 'Par set sekcija — napomena ispod cene', 'text' ),

		array( 'story_eyebrow', 'story', 'Nadnaslov', 'text' ),
		array( 'story_title', 'story', 'Naslov', 'text' ),
		array( 'story_text_1', 'story', 'Pasus 1', 'textarea' ),
		array( 'story_text_2', 'story', 'Pasus 2', 'textarea' ),
		array( 'story_note', 'story', 'Napomena', 'text' ),

		array( 'newsletter_gf_id', 'newsletter', 'Newsletter Gravity Form ID', 'number' ),
		array( 'newsletter_eyebrow', 'newsletter', 'Newsletter — nadnaslov', 'text' ),
		array( 'newsletter_title', 'newsletter', 'Newsletter — naslov', 'text' ),
		array( 'newsletter_text', 'newsletter', 'Newsletter — tekst', 'textarea' ),
		array( 'contact_gf_id', 'newsletter', 'Kontakt Gravity Form ID (za stranicu Kontakt, shortcode [webni_contact_form])', 'number' ),

		array( 'shop_title', 'shop', 'Naslov stranice Parfemi', 'text' ),
		array( 'shop_intro', 'shop', 'Uvod na stranici Parfemi', 'text' ),
		array( 'shop_delivery_line', 'shop', 'Linija o dostavi ispod dugmeta za kupovinu', 'text' ),
		array( 'free_shipping_text', 'shop', 'Tekst o besplatnoj dostavi ({prag} = iznos iz WooCommerce dostave)', 'text' ),
		array( 'product_how_long', 'shop', 'Kako traje (stranica proizvoda)', 'text' ),
		array( 'product_notes_lead', 'shop', 'Uvod uz note (stranica proizvoda)', 'textarea' ),

		array( 'footer_tagline', 'footer', 'Futer — slogan', 'text' ),
		array( 'footer_note', 'footer', 'Futer — kratka priča', 'textarea' ),
		array( 'contact_email', 'footer', 'Email', 'email' ),
		array( 'contact_phone', 'footer', 'Telefon', 'text' ),
		array( 'contact_address', 'footer', 'Adresa', 'text' ),
		array( 'social_instagram', 'footer', 'Instagram URL', 'url' ),
		array( 'social_tiktok', 'footer', 'TikTok URL', 'url' ),
		array( 'social_facebook', 'footer', 'Facebook URL', 'url' ),
		array( 'social_youtube', 'footer', 'YouTube URL', 'url' ),
		array( 'payment_line', 'footer', 'Načini plaćanja (tekst)', 'text' ),
		array( 'copyright', 'footer', 'Copyright ({year} = tekuća godina)', 'text' ),

		array( 'company_name', 'merchant', 'Pun naziv firme (kao u APR-u)', 'text', array(
			'description' => 'Ovi podaci se automatski prikazuju na stranicama Kontakt, Podaci o trgovcu, Uslovi kupovine, Politika privatnosti i Reklamacije, i u futeru. Email i telefon se uzimaju iz sekcije „Futer i kontakt“; cena dostave i prag za besplatnu dostavu iz WooCommerce → Podešavanja → Dostava.',
		) ),
		array( 'company_address', 'merchant', 'Adresa sedišta', 'text' ),
		array( 'company_mb', 'merchant', 'Matični broj (MB)', 'text' ),
		array( 'company_pib', 'merchant', 'PIB', 'text' ),
		array( 'support_hours', 'merchant', 'Radno vreme podrške (npr. radnim danima 9–17h)', 'text' ),
		array( 'return_address', 'merchant', 'Adresa za povrat robe (prazno = adresa sedišta)', 'text' ),
		array( 'privacy_email', 'merchant', 'Email za zaštitu podataka (prazno = kontakt email)', 'email' ),
		array( 'courier', 'merchant', 'Kurirska služba (npr. D Express)', 'text' ),
		array( 'dispatch_cutoff', 'merchant', 'Do koliko sati se porudžbina šalje istog dana (npr. 13h)', 'text' ),
		array( 'court_city', 'merchant', 'Nadležni sud — grad', 'text' ),
		array( 'bank_name', 'merchant', 'Banka za plaćanje karticama', 'text' ),
		array( 'legal_updated', 'merchant', 'Datum poslednje izmene uslova', 'text' ),
		array( 'payment_logos', 'merchant', 'Logotipi kartica i sigurnog plaćanja (slika od banke)', 'image', array(
			'description' => 'Traka sa logotipima koju dostavlja banka (Visa, Mastercard, Maestro, DinaCard, Visa Secure, Mastercard ID Check, banka). Prikazuje se u futeru i na stranici plaćanja.',
		) ),
	);

	$sanitizers = array(
		'text'     => 'sanitize_text_field',
		'textarea' => 'sanitize_textarea_field',
		'url'      => 'webni_sanitize_url_or_anchor',
		'email'    => 'sanitize_email',
		'number'   => 'absint',
		'checkbox' => 'webni_sanitize_checkbox',
		'product'  => 'absint',
		'image'    => 'absint',
		'select'   => 'webni_sanitize_select',
	);
	$products = null;

	foreach ( $fields as $f ) {
		list( $key, $section, $label, $type ) = $f;
		$extra = isset( $f[4] ) && is_array( $f[4] ) ? $f[4] : array();
		$wp_customize->add_setting( 'webni_' . $key, array(
			'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
			'sanitize_callback' => $sanitizers[ $type ],
			'transport'         => 'refresh',
		) );
		$args = array( 'label' => $label, 'section' => 'webni_' . $section, 'type' => $type );
		if ( ! empty( $extra['description'] ) ) {
			$args['description'] = $extra['description'];
		}
		if ( 'select' === $type ) {
			$args['choices'] = isset( $extra['choices'] ) ? $extra['choices'] : array();
		}
		if ( 'image' === $type ) {
			$args['mime_type'] = 'image';
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'webni_' . $key, $args ) );
			continue;
		}
		if ( 'product' === $type ) {
			if ( null === $products ) {
				$products = array( 0 => '— (nije izabrano)' );
				foreach ( get_posts( array( 'post_type' => 'product', 'posts_per_page' => 100, 'orderby' => 'title', 'order' => 'ASC' ) ) as $pp ) {
					$products[ $pp->ID ] = $pp->post_title;
				}
			}
			$args['type']    = 'select';
			$args['choices'] = $products;
		}
		if ( 'number' === $type ) {
			$args['input_attrs'] = array( 'min' => 0, 'step' => 1 );
		}
		$wp_customize->add_control( 'webni_' . $key, $args );
	}
}
add_action( 'customize_register', 'webni_customize_register' );

/**
 * Checkbox sanitizer.
 *
 * @param mixed $value Value.
 * @return int
 */
function webni_sanitize_checkbox( $value ) {
	return ( true === $value || '1' === $value || 1 === $value ) ? 1 : 0;
}

/**
 * Select sanitizer: only a value offered by the control is stored, otherwise the default.
 *
 * @param mixed                $value   Value.
 * @param WP_Customize_Setting $setting Setting.
 * @return string
 */
function webni_sanitize_select( $value, $setting ) {
	$value   = sanitize_key( (string) $value );
	$control = $setting->manager->get_control( $setting->id );
	$choices = $control && ! empty( $control->choices ) ? $control->choices : array();
	return ( $choices && ! isset( $choices[ $value ] ) ) ? (string) $setting->default : $value;
}

/**
 * URL sanitizer that also allows in-page anchors (#kolekcija).
 *
 * @param string $value Value.
 * @return string
 */
function webni_sanitize_url_or_anchor( $value ) {
	$value = trim( (string) $value );
	if ( '' !== $value && '#' === $value[0] ) {
		return '#' . sanitize_title( substr( $value, 1 ) );
	}
	return esc_url_raw( $value );
}
