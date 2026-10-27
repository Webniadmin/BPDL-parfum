<?php
/**
 * Serbian (Latin) storefront strings for WooCommerce.
 *
 * The official WooCommerce "sr_RS" language pack is Cyrillic, so the site locale
 * stays en_US and the storefront strings of the "woocommerce" text domain are
 * translated here, on the fly, with a static lookup table (O(1) per string).
 *
 * The table is applied on the front end (page loads, AJAX, REST, cron) and while
 * WooCommerce renders customer emails — never on the wp-admin screens, so the
 * administration stays in English like the rest of WordPress.
 *
 * @package webni-custom-version-1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether storefront translation should run for this request.
 *
 * @return bool
 */
function webni_wc_translate_enabled() {
	static $enabled = null;
	if ( null === $enabled ) {
		$enabled = ! is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );
	}
	return $enabled || did_action( 'webni_wc_email_translate_on' ) > did_action( 'webni_wc_email_translate_off' );
}

/**
 * English → Serbian (Latin) table. Keys are the original (untranslated) strings.
 *
 * @return array
 */
function webni_wc_strings() {
	static $table = null;
	if ( null !== $table ) {
		return $table;
	}
	$table = array(
		/* Catalog */
		'Shop'                                   => 'Parfemi',
		'Products'                               => 'Proizvodi',
		'Product'                                => 'Proizvod',
		'Add to cart'                            => 'Dodaj u korpu',
		'Read more'                              => 'Saznaj više',
		'Select options'                         => 'Izaberi opcije',
		'View products'                          => 'Pogledaj proizvode',
		'Sale!'                                  => 'Akcija',
		'Out of stock'                           => 'Rasprodato',
		'In stock'                               => 'Na stanju',
		'%s in stock'                            => '%s na stanju',
		'Only %s left in stock'                  => 'Još samo %s na stanju',
		'Available on backorder'                 => 'Dostupno po narudžbini',
		'Default sorting'                        => 'Podrazumevano',
		'Sort by popularity'                     => 'Po popularnosti',
		'Sort by average rating'                 => 'Po oceni',
		'Sort by latest'                         => 'Najnovije',
		'Sort by price: low to high'             => 'Cena: od niže ka višoj',
		'Sort by price: high to low'             => 'Cena: od više ka nižoj',
		'Shop order'                             => 'Redosled',
		'Showing the single result'              => 'Prikazan je jedan proizvod',
		'Showing all %1$d result'                => 'Prikazano: %1$d proizvod',
		'Showing all %1$d results'               => 'Prikazano: %1$d proizvoda',
		'Showing %1$d&ndash;%2$d of %3$d result'  => 'Prikazano %1$d–%2$d od %3$d',
		'Showing %1$d&ndash;%2$d of %3$d results' => 'Prikazano %1$d–%2$d od %3$d',
		'No products were found matching your selection.' => 'Nema proizvoda za ovaj izbor.',
		'No products found'                      => 'Nema proizvoda',
		'Search products&hellip;'                => 'Pretraži parfeme…',
		'Search products'                        => 'Pretraži parfeme',
		'Search for:'                            => 'Pretraga:',
		'Search'                                 => 'Pretraži',
		'Search results: &ldquo;%s&rdquo;'       => 'Rezultati pretrage: „%s“',
		'Search results for &ldquo;%s&rdquo;'    => 'Rezultati pretrage za „%s“',
		'Remove item'                            => 'Ukloni',
		'Thumbnail image'                        => 'Slika',
		'%s quantity'                            => 'Količina: %s',
		'Billing %s'                             => '%s',
		'Shipping %s'                            => '%s (dostava)',
		'Coupon "%s" cannot be applied because it does not exist.' => 'Kupon „%s“ ne postoji.',
		'Coupon "%s" cannot be applied because it is not valid.' => 'Kupon „%s“ nije važeći.',
		'Category:'                              => 'Kategorija:',
		'Categories:'                            => 'Kategorije:',
		'Tag:'                                   => 'Oznaka:',
		'Tags:'                                  => 'Oznake:',
		'SKU:'                                   => 'Šifra:',
		'SKU'                                    => 'Šifra',
		'N/A'                                    => '—',
		'Description'                            => 'Opis',
		'Additional information'                 => 'Dodatne informacije',
		'Reviews'                                => 'Ocene',
		'Related products'                       => 'Probaj i',
		'You may also like&hellip;'              => 'Možda ti se dopadne i…',
		'You may be interested in&hellip;'       => 'Možda te zanima i…',
		'Quantity'                               => 'Količina',
		'Product quantity'                       => 'Količina',
		'Weight'                                 => 'Težina',
		'Dimensions'                             => 'Dimenzije',
		'Previous'                               => 'Prethodna',
		'Next'                                   => 'Sledeća',
		'Page %s'                                => 'Strana %s',
		'Free!'                                  => 'Besplatno!',
		'Free'                                   => 'Besplatno',
		'Free shipping'                          => 'Besplatna dostava',
		'via %s'                                 => '· %s',
		'Flat rate'                              => 'Dostava',
		'Local pickup'                           => 'Lično preuzimanje',
		'Home'                                   => 'Početna',
		'Uncategorized'                          => 'Bez kategorije',

		/* Add-to-cart notices */
		'%s has been added to your cart.'        => '%s je u korpi.',
		'%s have been added to your cart.'       => '%s su u korpi.',
		'&ldquo;%s&rdquo; has been added to your cart.' => '„%s“ je u korpi.',
		'View cart'                              => 'Vidi korpu',
		'Continue shopping'                      => 'Nastavi kupovinu',
		'Checkout'                               => 'Plaćanje',
		'Please choose product options&hellip;'  => 'Izaberi opcije proizvoda…',
		'Sorry, this product is unavailable. Please choose a different combination.' => 'Ovaj proizvod trenutno nije dostupan. Izaberi drugu kombinaciju.',
		'Sorry, this product cannot be purchased.' => 'Ovaj proizvod trenutno nije moguće kupiti.',
		'You cannot add &quot;%s&quot; to the cart because the product is out of stock.' => '„%s“ je rasprodat i ne može se dodati u korpu.',
		'You cannot add that amount of &quot;%1$s&quot; to the cart because there is not enough stock (%2$s remaining).' => 'Ne možeš dodati toliko „%1$s“ — na stanju je još %2$s.',
		'You cannot add that amount to the cart &mdash; we have %1$s in stock and you already have %2$s in your cart.' => 'Ne možeš dodati toliko — na stanju imamo %1$s, a u korpi već imaš %2$s.',
		'You cannot add another &quot;%s&quot; to your cart.' => 'Ne možeš dodati još jedan „%s“ u korpu.',

		/* Cart */
		'Cart'                                   => 'Korpa',
		'Price'                                  => 'Cena',
		'Subtotal'                               => 'Međuzbir',
		'Total'                                  => 'Ukupno',
		'Remove this item'                       => 'Ukloni',
		'Remove %s from cart'                    => 'Ukloni %s iz korpe',
		'Remove'                                 => 'Ukloni',
		'[Remove]'                               => '[Ukloni]',
		'Coupon:'                                => 'Kupon:',
		'Coupon code'                            => 'Kod kupona',
		'Coupon'                                 => 'Kupon',
		'Apply coupon'                           => 'Primeni kupon',
		'Have a coupon?'                         => 'Imaš kupon?',
		'Click here to enter your code'          => 'Unesi kod ovde',
		'If you have a coupon code, please apply it below.' => 'Ako imaš kod kupona, unesi ga ispod.',
		'Update cart'                            => 'Osveži korpu',
		'Cart updated.'                          => 'Korpa je osvežena.',
		'Cart totals'                            => 'Ukupno u korpi',
		'Proceed to checkout'                    => 'Nastavi na plaćanje',
		'Your cart is currently empty.'          => 'Tvoja korpa je prazna.',
		'Your cart is currently empty!'          => 'Tvoja korpa je prazna.',
		'Return to shop'                         => 'Nazad na parfeme',
		'Return to cart'                         => 'Nazad na korpu',
		'%s removed.'                            => '%s je uklonjen.',
		'%s removed. %s'                         => '%s je uklonjen. %s',
		'Undo?'                                  => 'Vrati?',
		'Undo'                                   => 'Vrati',
		'Coupon code applied successfully.'      => 'Kupon je primenjen.',
		'Coupon "%s" does not exist!'            => 'Kupon „%s“ ne postoji.',
		'Coupon &quot;%s&quot; does not exist!'  => 'Kupon „%s“ ne postoji.',
		'Please enter a coupon code.'            => 'Unesi kod kupona.',
		'Coupon has been removed.'               => 'Kupon je uklonjen.',
		'Coupon usage limit has been reached.'   => 'Kupon je iskorišćen maksimalan broj puta.',
		'This coupon has expired.'               => 'Kupon je istekao.',
		'Coupon is not valid.'                   => 'Kupon nije važeći.',
		'Sorry, it seems the coupon "%s" is invalid - it has now been removed from your order.' => 'Kupon „%s“ nije važeći i uklonjen je iz porudžbine.',
		'Sorry, it seems the coupon "%s" is not yours - it has now been removed from your order.' => 'Kupon „%s“ nije namenjen tvom nalogu i uklonjen je iz porudžbine.',
		'The minimum spend for this coupon is %s.' => 'Minimalan iznos za ovaj kupon je %s.',
		'The maximum spend for this coupon is %s.' => 'Maksimalan iznos za ovaj kupon je %s.',
		'Sorry, this coupon is not applicable to your cart contents.' => 'Ovaj kupon ne važi za sadržaj tvoje korpe.',
		'Sorry, this coupon is not applicable to selected products.' => 'Ovaj kupon ne važi za izabrane proizvode.',
		'Sorry, coupon "%s" has already been applied and cannot be used in conjunction with other coupons.' => 'Kupon „%s“ je već primenjen i ne može se kombinovati sa drugim kuponima.',
		'Discount:'                              => 'Popust:',
		'Discount'                               => 'Popust',
		'Shipping'                               => 'Dostava',
		'Shipping:'                              => 'Dostava:',
		'Shipping to %s.'                        => 'Dostava za: %s.',
		'Shipping costs are calculated during checkout.' => 'Troškovi dostave se računaju pri plaćanju.',
		'Shipping options will be updated during checkout.' => 'Opcije dostave se ažuriraju pri plaćanju.',
		'Enter your address to view shipping options.' => 'Unesi adresu da vidiš opcije dostave.',
		'Enter your full address to see shipping costs.' => 'Unesi punu adresu da vidiš troškove dostave.',
		'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.' => 'Nema dostupnih opcija dostave. Proveri adresu ili nam se javi ako treba pomoć.',
		'There are no shipping methods available. Please ensure that your address has been entered correctly, or contact us if you need any help.' => 'Nema dostupnih načina dostave. Proveri adresu ili nam se javi ako treba pomoć.',
		'Calculate shipping'                     => 'Izračunaj dostavu',
		'Change address'                         => 'Promeni adresu',
		'Update'                                 => 'Osveži',
		'(estimated for %s)'                     => '(procena za %s)',
		'Estimate for %s.'                       => 'Procena za %s.',
		'Select a country / region&hellip;'      => 'Izaberi državu…',
		'Select an option&hellip;'               => 'Izaberi…',
		'Country / Region'                       => 'Država',
		'State / County'                         => 'Okrug',
		'Town / City'                            => 'Grad',
		'Postcode / ZIP'                         => 'Poštanski broj',
		'Apartment, suite, unit, etc. (optional)' => 'Stan, sprat, ulaz (nije obavezno)',
		'Apartment, suite, unit, etc.'           => 'Stan, sprat, ulaz',
		'House number and street name'           => 'Ulica i broj',
		'Street address'                         => 'Adresa',
		'Tax'                                    => 'PDV',
		'VAT'                                    => 'PDV',
		'(includes %s)'                          => '(uključuje %s)',
		'(ex. tax)'                              => '(bez PDV-a)',
		'(incl. tax)'                            => '(sa PDV-om)',
		'%s item'                                => '%s proizvod',
		'%s items'                               => '%s proizvoda',
		'No products in the cart.'               => 'Korpa je prazna.',

		/* Checkout */
		'Billing details'                        => 'Podaci za dostavu i račun',
		'Billing address'                        => 'Adresa za račun',
		'Shipping address'                       => 'Adresa za dostavu',
		'Shipping details'                       => 'Podaci za dostavu',
		'Ship to a different address?'           => 'Dostava na drugu adresu?',
		'Order notes'                            => 'Napomena uz porudžbinu',
		'Notes about your order, e.g. special notes for delivery.' => 'Napomena za kurira, sprat, interfon, vreme isporuke…',
		'Your order'                             => 'Tvoja porudžbina',
		'Place order'                            => 'Poruči',
		'Returning customer?'                    => 'Već imaš nalog?',
		'Click here to login'                    => 'Prijavi se ovde',
		'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.' => 'Ako si već kupovao/la kod nas, prijavi se. Ako si nov/a, samo nastavi sa unosom podataka.',
		'Create an account?'                     => 'Napravi nalog?',
		'Create account password'                => 'Lozinka za nalog',
		'Account username'                       => 'Korisničko ime',
		'Account password'                       => 'Lozinka',
		'Password'                               => 'Lozinka',
		'Username or email'                      => 'Korisničko ime ili e-mail',
		'Username or email address'              => 'Korisničko ime ili e-mail adresa',
		'Remember me'                            => 'Zapamti me',
		'Login'                                  => 'Prijava',
		'Log in'                                 => 'Prijavi se',
		'Lost your password?'                    => 'Zaboravljena lozinka?',
		'First name'                             => 'Ime',
		'Last name'                              => 'Prezime',
		'Company name'                           => 'Firma',
		'Company'                                => 'Firma',
		'Phone'                                  => 'Telefon',
		'Email address'                          => 'E-mail adresa',
		'Email'                                  => 'E-mail',
		'Email:'                                 => 'E-mail:',
		'(optional)'                             => '(nije obavezno)',
		'optional'                               => 'nije obavezno',
		'required'                               => 'obavezno',
		'Required'                               => 'Obavezno',
		'Payment methods'                        => 'Načini plaćanja',
		'Payment method:'                        => 'Način plaćanja:',
		'Payment method'                         => 'Način plaćanja',
		'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.' => 'Trenutno nema dostupnih načina plaćanja. Javi nam se i dogovorićemo se.',
		'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.' => 'Za tvoju adresu trenutno nema dostupnih načina plaćanja. Javi nam se i dogovorićemo se.',
		'Please fill in your details above to see available payment methods.' => 'Popuni podatke iznad da vidiš dostupne načine plaćanja.',
		'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.' => 'Tvoj pregledač nema uključen JavaScript — klikni %1$sOsveži iznos%2$s pre nego što poručiš.',
		'Update totals'                          => 'Osveži iznos',
		'I have read and agree to the website %s' => 'Pročitao/la sam i prihvatam %s',
		'terms and conditions'                   => 'uslove korišćenja',
		'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our %s.' => 'Tvoji podaci se koriste za obradu porudžbine i dostavu, kako je opisano u našoj %s.',
		'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our %s.' => 'Tvoji podaci se koriste za vođenje naloga i obradu porudžbina, kako je opisano u našoj %s.',
		'privacy policy'                         => 'politici privatnosti',
		'Cash on delivery'                       => 'Plaćanje pouzećem',
		'Pay with cash upon delivery.'           => 'Plaćaš kuriru prilikom preuzimanja.',
		'Please read and accept the terms and conditions to proceed with your order.' => 'Prihvati uslove korišćenja da bi nastavio/la.',
		'Invalid payment method.'                => 'Nevažeći način plaćanja.',
		'No shipping method has been selected. Please select a shipping method before proceeding.' => 'Nije izabran način dostave. Izaberi ga pre nego što nastaviš.',
		'Please enter an address to continue.'   => 'Unesi adresu da bi nastavio/la.',
		'Unfortunately <strong>we do not ship %s</strong>. Please enter an alternative shipping address.' => 'Nažalost, <strong>ne dostavljamo %s</strong>. Unesi drugu adresu.',
		'%s is a required field.'                => 'Polje „%s“ je obavezno.',
		'%s is not a valid phone number.'        => '„%s“ nije ispravan broj telefona.',
		'%s is not a valid postcode / ZIP.'      => '„%s“ nije ispravan poštanski broj.',
		'%s is not a valid email address.'       => '„%s“ nije ispravna e-mail adresa.',
		'Invalid email address'                  => 'Neispravna e-mail adresa',
		'%1$s is not valid. Please enter one of the following: %2$s' => '„%1$s“ nije ispravno. Unesi nešto od sledećeg: %2$s',
		'Please provide a valid email address.'  => 'Unesi ispravnu e-mail adresu.',
		'Please enter an account password.'      => 'Unesi lozinku za nalog.',
		'Please enter a valid account username.' => 'Unesi ispravno korisničko ime.',
		'An account is already registered with your email address. <a href="#" class="showlogin">Please log in.</a>' => 'Nalog sa ovom e-mail adresom već postoji. <a href="#" class="showlogin">Prijavi se.</a>',
		'An account is already registered with your email address. Please log in or use a different email address.' => 'Nalog sa ovom e-mail adresom već postoji. Prijavi se ili iskoristi drugu adresu.',
		'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.' => 'Nemamo dovoljno „%1$s“ na stanju (dostupno: %2$s). Izvini na neprilici.',
		'Sorry, "%s" is not in stock. Please edit your cart and try again. We apologize for any inconvenience caused.' => '„%s“ više nije na stanju. Izmeni korpu i pokušaj ponovo.',
		'Sorry, your session has expired. <a href="%s" class="wc-backward">Return to shop</a>' => 'Sesija je istekla. <a href="%s" class="wc-backward">Nazad na parfeme</a>',
		'Sorry, your session has expired.'       => 'Sesija je istekla.',
		'We were unable to process your order, please try again.' => 'Nismo uspeli da obradimo porudžbinu. Pokušaj ponovo.',
		'Unable to process your order. Please try again.' => 'Nismo uspeli da obradimo porudžbinu. Pokušaj ponovo.',
		'Error processing checkout. Please try again.' => 'Greška pri plaćanju. Pokušaj ponovo.',
		'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.' => 'Nažalost, banka je odbila transakciju. Pokušaj ponovo.',
		'Your order is awaiting payment.'        => 'Tvoja porudžbina čeka plaćanje.',
		'Thank you. Your order has been received.' => 'Hvala. Tvoja porudžbina je primljena.',
		'Order number:'                          => 'Broj porudžbine:',
		'Order:'                                 => 'Porudžbina:',
		'Date:'                                  => 'Datum:',
		'Total:'                                 => 'Ukupno:',
		'Subtotal:'                              => 'Međuzbir:',
		'Order details'                          => 'Detalji porudžbine',
		'Order'                                  => 'Porudžbina',
		'Pay'                                    => 'Plati',
		'Pay for order'                          => 'Plati porudžbinu',
		'Note:'                                  => 'Napomena:',
		'Address'                                => 'Adresa',
		'Customer details'                       => 'Podaci kupca',
		'Sorry, this order is invalid and cannot be paid for.' => 'Ova porudžbina nije važeća i ne može se platiti.',
		'This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.' => 'Status ove porudžbine je „%s“ — ne može se platiti. Javi nam se ako treba pomoć.',
		'Invalid order.'                         => 'Nevažeća porudžbina.',

		/* Endpoint titles */
		'Order received'                         => 'Porudžbina primljena',
		'Edit account'                           => 'Podaci naloga',
		'Edit address'                           => 'Adrese',
		'Set a new password'                     => 'Nova lozinka',
		'Set up a new password'                  => 'Nova lozinka',

		/* My Account */
		'My account'                             => 'Moj nalog',
		'My Account'                             => 'Moj nalog',
		'Dashboard'                              => 'Kontrolna tabla',
		'Orders'                                 => 'Porudžbine',
		'Downloads'                              => 'Preuzimanja',
		'Addresses'                              => 'Adrese',
		'Account details'                        => 'Podaci naloga',
		'Logout'                                 => 'Odjava',
		'Log out'                                => 'Odjavi se',
		'Register'                               => 'Registracija',
		'Username'                               => 'Korisničko ime',
		'A link to set a new password will be sent to your email address.' => 'Link za postavljanje lozinke stiže na tvoju e-mail adresu.',
		'A password will be sent to your email address.' => 'Lozinka stiže na tvoju e-mail adresu.',
		'Hello %1$s (not %1$s? %2$s)'            => 'Zdravo, %1$s (nisi %1$s? %2$s)',
		'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)' => 'Zdravo, %1$s (nisi %1$s? <a href="%2$s">Odjavi se</a>)',
		'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.' => 'Ovde možeš da vidiš <a href="%1$s">porudžbine</a>, promeniš <a href="%2$s">adresu</a> i <a href="%3$s">podatke naloga i lozinku</a>.',
		'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.' => 'Ovde možeš da vidiš <a href="%1$s">porudžbine</a>, promeniš <a href="%2$s">adrese</a> i <a href="%3$s">podatke naloga i lozinku</a>.',
		'From your account dashboard you can view your %1$srecent orders%2$s, manage your %3$sshipping and billing addresses%4$s, and %5$sedit your password and account details%6$s.' => 'Ovde možeš da vidiš %1$sporudžbine%2$s, promeniš %3$sadrese%4$s i %5$spodatke naloga i lozinku%6$s.',
		'From your account dashboard you can view your %1$srecent orders%2$s, manage your %3$sbilling address%4$s, and %5$sedit your password and account details%6$s.' => 'Ovde možeš da vidiš %1$sporudžbine%2$s, promeniš %3$sadresu%4$s i %5$spodatke naloga i lozinku%6$s.',
		'Date'                                   => 'Datum',
		'Status'                                 => 'Status',
		'Actions'                                => 'Opcije',
		'View'                                   => 'Pogledaj',
		'Cancel'                                 => 'Otkaži',
		'Edit'                                   => 'Izmeni',
		'Add'                                    => 'Dodaj',
		'Delete'                                 => 'Obriši',
		'Download'                               => 'Preuzmi',
		'Expires'                                => 'Ističe',
		'Never'                                  => 'Nikad',
		'Downloads remaining'                    => 'Preostalo preuzimanja',
		'&infin;'                                => '∞',
		'%1$s for %2$s item'                     => '%1$s za %2$s proizvod',
		'%1$s for %2$s items'                    => '%1$s za %2$s proizvoda',
		'No order has been made yet.'            => 'Još nema porudžbina.',
		'Browse products'                        => 'Pogledaj parfeme',
		'Go shop'                                => 'Idi na parfeme',
		'Order #%s'                              => 'Porudžbina #%s',
		'Order #%1$s was placed on %2$s and is currently %3$s.' => 'Porudžbina #%1$s je napravljena %2$s i trenutno je: %3$s.',
		'Order updates'                          => 'Promene porudžbine',
		'Order again'                            => 'Poruči ponovo',
		'The cart has been filled with the items from your previous order.' => 'Korpa je napunjena proizvodima iz prethodne porudžbine.',
		'Your order was cancelled.'              => 'Porudžbina je otkazana.',
		'Your order can no longer be cancelled. Please contact us if you need assistance.' => 'Porudžbina se više ne može otkazati. Javi nam se ako treba pomoć.',
		'You have not set up this type of address yet.' => 'Još nisi uneo/la ovu adresu.',
		'The following addresses will be used on the checkout page by default.' => 'Ove adrese se koriste pri plaćanju.',
		'Save address'                           => 'Sačuvaj adresu',
		'Address changed successfully.'          => 'Adresa je sačuvana.',
		'Edit %s'                                => 'Izmeni: %s',
		'Add %s'                                 => 'Dodaj: %s',
		'Edit billing address'                   => 'Izmeni adresu za račun',
		'Edit shipping address'                  => 'Izmeni adresu za dostavu',
		'Display name'                           => 'Prikazano ime',
		'This will be how your name will be displayed in the account section and in reviews' => 'Ovako će tvoje ime biti prikazano na nalogu i u ocenama',
		'Password change'                        => 'Promena lozinke',
		'Current password (leave blank to leave unchanged)' => 'Trenutna lozinka (ostavi prazno ako je ne menjaš)',
		'New password (leave blank to leave unchanged)' => 'Nova lozinka (ostavi prazno ako je ne menjaš)',
		'Confirm new password'                   => 'Potvrdi novu lozinku',
		'Save changes'                           => 'Sačuvaj izmene',
		'Account details changed successfully.'  => 'Podaci naloga su sačuvani.',
		'Your current password is incorrect.'    => 'Trenutna lozinka nije tačna.',
		'Passwords do not match.'                => 'Lozinke se ne poklapaju.',
		'New passwords do not match.'            => 'Nove lozinke se ne poklapaju.',
		'Please fill out all password fields.'   => 'Popuni sva polja za lozinku.',
		'Please enter your current password.'    => 'Unesi trenutnu lozinku.',
		'Please re-enter your password.'         => 'Ponovo unesi lozinku.',
		'This email address is already registered.' => 'Ova e-mail adresa je već registrovana.',
		'Display name cannot be changed to email address due to privacy concern.' => 'Prikazano ime ne može biti e-mail adresa.',
		'Lost password'                          => 'Zaboravljena lozinka',
		'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.' => 'Zaboravio/la si lozinku? Unesi korisničko ime ili e-mail i stići će ti link za novu lozinku.',
		'Reset password'                         => 'Resetuj lozinku',
		'Password reset email has been sent.'    => 'Poslali smo ti e-mail za novu lozinku.',
		'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.' => 'Poslali smo ti e-mail za novu lozinku. Može potrajati nekoliko minuta — sačekaj bar 10 minuta pre novog pokušaja.',
		'Enter a new password below.'            => 'Unesi novu lozinku.',
		'New password'                           => 'Nova lozinka',
		'Re-enter new password'                  => 'Ponovi novu lozinku',
		'Save'                                   => 'Sačuvaj',
		'Your password has been reset successfully.' => 'Lozinka je promenjena.',
		'Enter a username or email address.'     => 'Unesi korisničko ime ili e-mail.',
		'Invalid username or email.'             => 'Nepostojeće korisničko ime ili e-mail.',
		'Invalid password reset link.'           => 'Link za reset lozinke nije važeći.',
		'This key is invalid or has already been used. Please reset your password again if needed.' => 'Link je nevažeći ili već iskorišćen. Zatraži novi ako treba.',
		'No downloads available yet.'            => 'Još nema preuzimanja.',
		'No saved methods found.'                => 'Nema sačuvanih načina plaćanja.',
		'Add payment method'                     => 'Dodaj način plaćanja',
		'Method'                                 => 'Način',
		'Make default'                           => 'Postavi kao podrazumevano',
		'Default'                                => 'Podrazumevano',
		'Username is required.'                  => 'Korisničko ime je obavezno.',
		'Password is required.'                  => 'Lozinka je obavezna.',
		'Error: The password you entered for the username %s is incorrect.' => 'Greška: lozinka za korisničko ime %s nije tačna.',
		'Unknown email address. Check again or try your username.' => 'Nepoznata e-mail adresa. Proveri je ili probaj korisničko ime.',
		'A user could not be found with this email address.' => 'Ne postoji nalog sa ovom e-mail adresom.',
		'Your account was created successfully. Your login details have been sent to your email address.' => 'Nalog je napravljen. Podaci za prijavu su poslati na tvoj e-mail.',
		'Your account was created successfully and a password has been sent to your email address.' => 'Nalog je napravljen, a lozinka je poslata na tvoj e-mail.',
		'Please enter a valid email address.'    => 'Unesi ispravnu e-mail adresu.',
		'Please enter a username.'               => 'Unesi korisničko ime.',
		'Please enter a password.'               => 'Unesi lozinku.',
		'Please enter a valid username.'         => 'Unesi ispravno korisničko ime.',
		'Error: Please enter a stronger password.' => 'Greška: lozinka je preslaba.',
		'Weak - Please enter a stronger password.' => 'Slaba — unesi jaču lozinku.',
		'Very weak - Please enter a stronger password.' => 'Vrlo slaba — unesi jaču lozinku.',
		'Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).' => 'Savet: lozinka treba da ima bar 12 znakova. Pomešaj velika i mala slova, brojeve i simbole.',
		'Too many failed login attempts. Please try again later.' => 'Previše neuspelih prijava. Pokušaj kasnije.',
		'Billing'                                => 'Račun',
		'Billing email'                          => 'E-mail za račun',

		/* Order statuses (visible to customers) */
		'Pending payment'                        => 'Čeka plaćanje',
		'Processing'                             => 'U obradi',
		'On hold'                                => 'Na čekanju',
		'Completed'                              => 'Završena',
		'Cancelled'                              => 'Otkazana',
		'Refunded'                               => 'Refundirana',
		'Failed'                                 => 'Neuspešna',
		'Draft'                                  => 'Nacrt',

		/* Generic notices */
		'Error'                                  => 'Greška',
		'Sorry, this product is not purchasable.' => 'Ovaj proizvod nije moguće kupiti.',
		'Invalid product.'                       => 'Nevažeći proizvod.',
		'Invalid nonce verification'             => 'Nevažeća provera zahteva',
		'Please enter a valid email address'     => 'Unesi ispravnu e-mail adresu',
		'Please enter a valid phone number.'     => 'Unesi ispravan broj telefona.',
		'Please enter a valid postcode / ZIP.'   => 'Unesi ispravan poštanski broj.',
		'Select'                                 => 'Izaberi',
		'Loading'                                => 'Učitavanje',
		'No matches found'                       => 'Nema rezultata',
		'Searching&hellip;'                      => 'Pretraga…',
		'Loading more results&hellip;'           => 'Učitavanje…',
		'Please delete some characters'          => 'Obriši nekoliko znakova',
		'Please enter %qty% or more characters'  => 'Unesi bar %qty% znaka',
		'You can only select %qty% items'        => 'Možeš izabrati najviše %qty%',
		'Yes'                                    => 'Da',
		'No'                                     => 'Ne',
		'Serbia'                                 => 'Srbija',

		/* Customer emails (templates) */
		'Thank you for your order'               => 'Hvala na porudžbini',
		'Thanks for shopping with us.'           => 'Hvala što kupuješ kod nas.',
		'Thanks for using %1$s!'                 => 'Hvala što koristiš %1$s!',
		'Thanks for reading.'                    => 'Hvala na pažnji.',
		'We look forward to fulfilling your order soon.' => 'Uskoro šaljemo tvoju porudžbinu.',
		'We look forward to seeing you soon.'    => 'Vidimo se uskoro.',
		'Just to let you know &mdash; we\'ve received your order #%s, and it is now being processed:' => 'Primili smo tvoju porudžbinu #%s i već je obrađujemo:',
		'Thanks for your order. It&rsquo;s on-hold until we confirm that payment has been received. In the meantime, here&rsquo;s a reminder of what you ordered:' => 'Hvala na porudžbini. Na čekanju je dok ne potvrdimo uplatu. Evo šta si poručio/la:',
		'Hi %s,'                                 => 'Zdravo %s,',
		'Hi there,'                              => 'Zdravo,',
		'Pay for this order'                     => 'Plati ovu porudžbinu',
		'Your order has been fulfilled'          => 'Tvoja porudžbina je poslata',
		'Here are the details of your order placed on %s:' => 'Evo detalja porudžbine od %s:',
		'We have finished processing your order.' => 'Tvoja porudžbina je završena.',
		'Your %s order has been marked complete on our side.' => 'Porudžbina na sajtu %s je označena kao završena.',
		'Your %s order has been received!'       => 'Tvoja porudžbina na sajtu %s je primljena!',
		'Thanks for creating an account on %s. Your username is %s' => 'Hvala što si napravio/la nalog na sajtu %s. Tvoje korisničko ime je %s',
		'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s' => 'Hvala što si napravio/la nalog na sajtu %1$s. Tvoje korisničko ime je %2$s. Nalogu pristupaš ovde: %3$s',
		'Thanks for creating an account on %1$s. Your username is %2$s' => 'Hvala što si napravio/la nalog na sajtu %1$s. Tvoje korisničko ime je %2$s',
		'Your password has been automatically generated: %s' => 'Lozinka je automatski generisana: %s',
		'You can access your account area to view orders, change your password, and more at: %s' => 'Svoj nalog, porudžbine i lozinku možeš videti ovde: %s',
		'[Order #%s]'                            => '[Porudžbina #%s]',
		'[Order #%1$s] (%2$s)'                   => '[Porudžbina #%1$s] (%2$s)',
		'Order #%1$s (%2$s)'                     => 'Porudžbina #%1$s (%2$s)',
		'Quantity:'                              => 'Količina:',
		'Price:'                                 => 'Cena:',
		'Refund'                                 => 'Povraćaj',
		'Note'                                   => 'Napomena',
		'Click here to reset your password'      => 'Klikni ovde da promeniš lozinku',
		'Someone has requested a new password for the following account on %s:' => 'Neko je zatražio novu lozinku za nalog na sajtu %s:',
		'If you didn\'t make this request, just ignore this email. If you\'d like to proceed:' => 'Ako nisi ti, ignoriši ovaj e-mail. Ako jesi:',
		'Password Reset Request for %s'          => 'Zahtev za promenu lozinke za %s',

		/* 1.1.8 — found by walking the shop, cart, checkout and account flows */
		'Shipment'                               => 'Dostava',
		'Shipment %d'                            => 'Dostava %d',
		'Country / region'                       => 'Država',
		'City:'                                  => 'Grad:',
		'Postcode / ZIP:'                        => 'Poštanski broj:',
		'Postcode'                               => 'Poštanski broj',
		'District'                               => 'Okrug',
		'Enter your coupon code'                 => 'Unesi kod kupona',
		'Confirm your email address'             => 'Potvrdi e-mail adresu',
		'Confirm email address'                  => 'Potvrdi e-mail adresu',
		'Confirm your email address to check for past orders and link them to your account.' => 'Potvrdi e-mail adresu da proverimo ranije porudžbine i povežemo ih sa tvojim nalogom.',
		'View order'                             => 'Pogledaj porudžbinu',
		'View order %s'                          => 'Pogledaj porudžbinu %s',
		'View order number %s'                   => 'Pogledaj porudžbinu broj %s',
		'Account pages'                          => 'Stranice naloga',
		'This will be how your name will be displayed in the account section' => 'Ovako će se tvoje ime prikazivati u nalogu',
		'Show password'                          => 'Prikaži lozinku',
		'Hide password'                          => 'Sakrij lozinku',
		'Please enter a stronger password.'      => 'Unesi jaču lozinku.',
		'Original price was: %s.'                => 'Stara cena: %s.',
		'Current price is: %s.'                  => 'Nova cena: %s.',
		'Sorted by popularity'                   => 'Sortirano po popularnosti',
		'Sorted by average rating'               => 'Sortirano po oceni',
		'Sorted by latest'                       => 'Sortirano od najnovijeg',
		'Sorted by price: low to high'           => 'Sortirano po ceni: od niže ka višoj',
		'Sorted by price: high to low'           => 'Sortirano po ceni: od više ka nižoj',
		'Please select a rating'                 => 'Izaberi ocenu',
		'1 of 5 stars'                           => '1 od 5 zvezdica',
		'2 of 5 stars'                           => '2 od 5 zvezdica',
		'3 of 5 stars'                           => '3 od 5 zvezdica',
		'4 of 5 stars'                           => '4 od 5 zvezdica',
		'5 of 5 stars'                           => '5 od 5 zvezdica',
		'View full-screen image gallery'         => 'Prikaži galeriju preko celog ekrana',
		'Loading failed'                         => 'Učitavanje nije uspelo',
		'Please enter 1 or more characters'      => 'Unesi bar 1 znak',
		'Please delete 1 character'              => 'Obriši 1 znak',
		'Please delete %qty% characters'         => 'Obriši %qty% znakova',
		'You can only select 1 item'             => 'Možeš da izabereš samo 1 stavku',
		'One result is available, press enter to select it.' => 'Dostupan je jedan rezultat, pritisni Enter da ga izabereš.',
		'%qty% results are available, use up and down arrow keys to navigate.' => 'Dostupno rezultata: %qty%. Koristi strelice gore i dole.',

		/* 1.1.8 — customer e-mails (WooCommerce 10/11 wording) */
		'Just to let you know &mdash; we’ve received your order, and it is now being processed.' => 'Porudžbina je stigla i već je obrađujemo.',
		'Here’s a reminder of what you’ve ordered:' => 'Podsetnik šta si naručio/la:',
		'Here’s a reminder of what you’ve bought:' => 'Podsetnik šta si kupio/la:',
		'We’ve received your order and it’s currently on hold until we can confirm your payment has been processed.' => 'Porudžbina je stigla i čeka dok ne potvrdimo uplatu.',
		'Thanks for your order. We’ve received it, and it’s currently on hold while we complete the next required step, such as confirming, clearing, or finalizing payment.' => 'Hvala na porudžbini. Stigla je i čeka sledeći korak, na primer potvrdu uplate.',
		'The following note has been added to your order:' => 'Uz tvoju porudžbinu je dodata napomena:',
		'As a reminder, here are your order details:' => 'Podsetnik — detalji porudžbine:',
		'Your order details are as follows:'   => 'Detalji porudžbine:',
		'Order summary'                          => 'Pregled porudžbine',
		'Thanks again! If you need any help with your order, please contact us at {store_email}.' => 'Hvala još jednom! Ako ti treba pomoć oko porudžbine, piši nam na {store_email}.',
		'Thanks again! If you need any help with your order, please contact us at %s.' => 'Hvala još jednom! Ako ti treba pomoć oko porudžbine, piši nam na %s.',
		'If you need any help with your order, please contact us at {store_email}.' => 'Ako ti treba pomoć oko porudžbine, piši nam na {store_email}.',
		'Thanks again for shopping with us. If you have any questions, reply to this email and we\'ll help out.' => 'Hvala još jednom na kupovini. Ako imaš pitanje, odgovori na ovaj e-mail i pomoći ćemo.',
		'Thanks for shopping with us'            => 'Hvala na kupovini',
		'We hope to see you again soon.'         => 'Nadamo se da se vidimo opet.',
		'Sorry, your order was unsuccessful'     => 'Porudžbina nije uspela',
		"Unfortunately, we couldn't complete your order due to an issue with your payment method." => 'Nažalost, porudžbinu nismo mogli da završimo zbog problema sa plaćanjem.',
		'We’re getting in touch to let you know that your order #%1$s has been cancelled.' => 'Javljamo ti da je porudžbina #%1$s otkazana.',
		'Your order from %s has been refunded.'  => 'Novac za porudžbinu sa sajta %s je vraćen.',
		'Your order from %s has been partially refunded.' => 'Deo novca za porudžbinu sa sajta %s je vraćen.',
		'Your order on %s has been refunded. There are more details below for your reference:' => 'Novac za porudžbinu sa sajta %s je vraćen. Detalji su ispod:',
		'Your order from {site_title} is on hold' => 'Porudžbina sa sajta {site_title} čeka potvrdu',
		'Your order from {site_title} is on its way!' => 'Porudžbina sa sajta {site_title} je krenula!',
		'Reset your password'                    => 'Promeni lozinku',
		'Reset your password for {site_title}'   => 'Promena lozinke za {site_title}',
		'If you didn’t make this request, just ignore this email. If you’d like to proceed, reset your password via the link below:' => 'Ako nisi ti, ignoriši ovaj e-mail. Ako jesi, promeni lozinku preko linka ispod:',
		'Click here to set your new password.'   => 'Klikni ovde da postaviš novu lozinku.',
		'Set your new password.'                 => 'Postavi novu lozinku.',
		'Thanks for creating an account on %s. Here’s a copy of your user details.' => 'Hvala što si napravio/la nalog na sajtu %s. Evo tvojih podataka.',
		'You can access your account area to view orders, change your password, and more via the link below:' => 'U svom nalogu možeš da vidiš porudžbine, promeniš lozinku i još toga — link je ispod:',
		'Username: <b>%s</b>'                    => 'Korisničko ime: <b>%s</b>',
		'Username: %s'                           => 'Korisničko ime: %s',
		'[{site_title}]: Your order #{order_number} has been cancelled' => '[{site_title}]: porudžbina #{order_number} je otkazana',
		'Order cancelled: #{order_number}'       => 'Porudžbina #{order_number} je otkazana',
		'Your order at {site_title} was unsuccessful' => 'Porudžbina na sajtu {site_title} nije uspela',
		'Order failed: #{order_number}'          => 'Porudžbina #{order_number} nije uspela',
		"If you'd like to continue with your purchase, please return to %s and try a different method of payment." => 'Ako želiš da nastaviš kupovinu, vrati se na %s i izaberi drugi način plaćanja.',
	);
	return apply_filters( 'webni_wc_strings', $table );
}

/**
 * WordPress core strings that customers see on the storefront (log-in and password errors,
 * the password strength meter used by WooCommerce). Keys may carry a context as
 * "context\004text", like in .mo files.
 *
 * @return array
 */
function webni_wp_strings() {
	static $table = null;
	if ( null !== $table ) {
		return $table;
	}
	$table = array(
		'Lost your password?'                    => 'Zaboravio/la si lozinku?',
		'<strong>Error:</strong> The password you entered for the username %s is incorrect.' => '<strong>Greška:</strong> lozinka za korisničko ime %s nije tačna.',
		'<strong>Error:</strong> The password you entered for the email address %s is incorrect.' => '<strong>Greška:</strong> lozinka za e-mail adresu %s nije tačna.',
		'<strong>Error:</strong> The username <strong>%s</strong> is not registered on this site. If you are unsure of your username, try your email address instead.' => '<strong>Greška:</strong> korisničko ime <strong>%s</strong> ne postoji. Ako nisi siguran/na, probaj sa e-mail adresom.',
		'<strong>Error:</strong> Unknown email address. Check again or try your username.' => '<strong>Greška:</strong> nepoznata e-mail adresa. Proveri je ili probaj sa korisničkim imenom.',
		'<strong>Error:</strong> Invalid username, email address or incorrect password.' => '<strong>Greška:</strong> pogrešno korisničko ime, e-mail adresa ili lozinka.',
		'<strong>Error:</strong> The password field is empty.' => '<strong>Greška:</strong> unesi lozinku.',
		'<strong>Error:</strong> The username field is empty.' => '<strong>Greška:</strong> unesi korisničko ime.',
		'<strong>Error:</strong> The email field is empty.' => '<strong>Greška:</strong> unesi e-mail adresu.',
		'<strong>Error:</strong> Please enter a username or email address.' => '<strong>Greška:</strong> unesi korisničko ime ili e-mail adresu.',
		'<strong>Error:</strong> There is no account with that username or email address.' => '<strong>Greška:</strong> ne postoji nalog sa tim korisničkim imenom ili e-mail adresom.',
		'<strong>Error:</strong> The email address is not correct.' => '<strong>Greška:</strong> e-mail adresa nije ispravna.',
		'<strong>Error:</strong> Please type your email address.' => '<strong>Greška:</strong> unesi e-mail adresu.',
		'<strong>Error:</strong> The email could not be sent. Your site may not be correctly configured to send emails. <a href="%s">Get support for resetting your password</a>.' => '<strong>Greška:</strong> e-mail nije poslat. Javi nam se i pomoći ćemo ti da promeniš lozinku.',
		'Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).' => 'Savet: lozinka treba da ima bar 12 znakova. Jača je ako sadrži velika i mala slova, brojeve i znakove kao ! " ? $ % ^ &amp; ).',
		"password strength\004Password strength unknown" => 'Jačina lozinke nepoznata',
		"password strength\004Very weak"        => 'Veoma slaba',
		"password strength\004Weak"             => 'Slaba',
		"password strength\004Medium"           => 'Srednja',
		"password strength\004Strong"           => 'Jaka',
		"password mismatch\004Mismatch"         => 'Ne poklapa se',
		'Page not found'                         => 'Stranica nije pronađena',
	);
	return apply_filters( 'webni_wp_strings', $table );
}

/**
 * gettext filter for the WordPress core ("default") domain on the storefront.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @param string $context     Context ('' for none).
 * @return string
 */
function webni_wp_gettext( $translation, $text, $domain, $context = '' ) {
	if ( 'default' !== $domain || ! webni_wc_translate_enabled() ) {
		return $translation;
	}
	$table = webni_wp_strings();
	$key   = '' !== $context ? $context . "\004" . $text : $text;
	if ( isset( $table[ $key ] ) ) {
		return $table[ $key ];
	}
	return isset( $table[ $text ] ) ? $table[ $text ] : $translation;
}
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	return webni_wp_gettext( $translation, $text, $domain );
}, 11, 3 );
add_filter( 'gettext_with_context', function ( $translation, $text, $context, $domain ) {
	return webni_wp_gettext( $translation, $text, $domain, $context );
}, 11, 4 );

/**
 * Serbian addresses need no district: hide the state field (its options are English district
 * names) in checkout, account addresses and the cart's shipping calculator.
 *
 * @param array $locale Country locale.
 * @return array
 */
function webni_wc_country_locale( $locale ) {
	$locale['RS']['state'] = array( 'required' => false, 'hidden' => true );
	return $locale;
}
add_filter( 'woocommerce_get_country_locale', 'webni_wc_country_locale' );
add_filter( 'woocommerce_shipping_calculator_enable_state', '__return_false' );

/**
 * gettext filter: translate "woocommerce" strings on the storefront.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function webni_wc_gettext( $translation, $text, $domain ) {
	if ( 'woocommerce' !== $domain || ! webni_wc_translate_enabled() ) {
		return $translation;
	}
	$table = webni_wc_strings();
	return isset( $table[ $text ] ) ? $table[ $text ] : $translation;
}
add_filter( 'gettext', 'webni_wc_gettext', 10, 3 );

/**
 * gettext_with_context filter (same table, context ignored).
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $context     Context.
 * @param string $domain      Text domain.
 * @return string
 */
function webni_wc_gettext_with_context( $translation, $text, $context, $domain ) {
	return webni_wc_gettext( $translation, $text, $domain );
}
add_filter( 'gettext_with_context', 'webni_wc_gettext_with_context', 10, 4 );

/**
 * ngettext filter for plural strings.
 *
 * @param string $translation Translated text.
 * @param string $single      Singular.
 * @param string $plural      Plural.
 * @param int    $number      Count.
 * @param string $domain      Text domain.
 * @return string
 */
function webni_wc_ngettext( $translation, $single, $plural, $number, $domain ) {
	if ( 'woocommerce' !== $domain || ! webni_wc_translate_enabled() ) {
		return $translation;
	}
	$table = webni_wc_strings();
	$n     = abs( (int) $number ) % 100;
	// Serbian: 1, 21, 31… take the singular form (except 11).
	$use_single = ( 1 === $n % 10 && 11 !== $n );
	$key        = $use_single ? $single : $plural;
	return isset( $table[ $key ] ) ? $table[ $key ] : $translation;
}
add_filter( 'ngettext', 'webni_wc_ngettext', 10, 5 );

/**
 * ngettext_with_context filter (same table, context ignored).
 *
 * @param string $translation Translated text.
 * @param string $single      Singular.
 * @param string $plural      Plural.
 * @param int    $number      Count.
 * @param string $context     Context.
 * @param string $domain      Text domain.
 * @return string
 */
function webni_wc_ngettext_with_context( $translation, $single, $plural, $number, $context, $domain ) {
	return webni_wc_ngettext( $translation, $single, $plural, $number, $domain );
}
add_filter( 'ngettext_with_context', 'webni_wc_ngettext_with_context', 10, 6 );

/**
 * Enable the table while WooCommerce renders a customer email (also from wp-admin).
 */
add_action( 'woocommerce_email_header', function () {
	do_action( 'webni_wc_email_translate_on' );
}, 1 );
add_action( 'woocommerce_email_footer', function () {
	do_action( 'webni_wc_email_translate_off' );
}, 99 );

/**
 * Country names shown on the storefront (only countries a Serbian shop realistically ships to).
 *
 * @param array $countries Country list.
 * @return array
 */
function webni_wc_countries( $countries ) {
	if ( ! webni_wc_translate_enabled() ) {
		return $countries;
	}
	$sr = array(
		'RS' => 'Srbija',
		'BA' => 'Bosna i Hercegovina',
		'ME' => 'Crna Gora',
		'HR' => 'Hrvatska',
		'MK' => 'Severna Makedonija',
		'SI' => 'Slovenija',
		'HU' => 'Mađarska',
		'RO' => 'Rumunija',
		'BG' => 'Bugarska',
		'AT' => 'Austrija',
		'DE' => 'Nemačka',
		'CH' => 'Švajcarska',
		'XK' => 'Kosovo',
	);
	foreach ( $sr as $code => $name ) {
		if ( isset( $countries[ $code ] ) ) {
			$countries[ $code ] = $name;
		}
	}
	return $countries;
}
add_filter( 'woocommerce_countries', 'webni_wc_countries' );

/**
 * Serbian labels for the catalog ordering dropdown.
 *
 * @param array $options Orderby options.
 * @return array
 */
function webni_wc_catalog_orderby( $options ) {
	$labels = array(
		'menu_order' => __( 'Podrazumevano', 'webni-custom-version-1' ),
		'popularity' => __( 'Najprodavaniji', 'webni-custom-version-1' ),
		'rating'     => __( 'Po oceni', 'webni-custom-version-1' ),
		'date'       => __( 'Najnovije', 'webni-custom-version-1' ),
		'price'      => __( 'Cena: od niže', 'webni-custom-version-1' ),
		'price-desc' => __( 'Cena: od više', 'webni-custom-version-1' ),
		'relevance'  => __( 'Relevantnost', 'webni-custom-version-1' ),
	);
	foreach ( $options as $k => $v ) {
		if ( isset( $labels[ $k ] ) ) {
			$options[ $k ] = $labels[ $k ];
		}
	}
	unset( $options['rating'] ); // Reviews are not used by the theme.
	return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'webni_wc_catalog_orderby' );

/**
 * Serbian "My Account" navigation labels (and order).
 *
 * @param array $items Menu items.
 * @return array
 */
function webni_wc_account_menu_items( $items ) {
	$labels = array(
		'dashboard'       => __( 'Moj nalog', 'webni-custom-version-1' ),
		'orders'          => __( 'Porudžbine', 'webni-custom-version-1' ),
		'downloads'       => __( 'Preuzimanja', 'webni-custom-version-1' ),
		'edit-address'    => __( 'Adrese', 'webni-custom-version-1' ),
		'payment-methods' => __( 'Načini plaćanja', 'webni-custom-version-1' ),
		'edit-account'    => __( 'Podaci naloga', 'webni-custom-version-1' ),
		'customer-logout' => __( 'Odjava', 'webni-custom-version-1' ),
	);
	$ordered = array();
	foreach ( $labels as $k => $label ) {
		if ( isset( $items[ $k ] ) ) {
			$ordered[ $k ] = $label;
		}
	}
	// Keep anything added by plugins, after the known items.
	foreach ( $items as $k => $v ) {
		if ( ! isset( $ordered[ $k ] ) ) {
			$ordered[ $k ] = $v;
		}
	}
	return $ordered;
}
add_filter( 'woocommerce_account_menu_items', 'webni_wc_account_menu_items' );

/**
 * Serbian labels for address fields (checkout and account addresses).
 *
 * @param array $fields Address fields.
 * @return array
 */
function webni_wc_address_fields( $fields ) {
	$labels = array(
		'first_name' => array( __( 'Ime', 'webni-custom-version-1' ), '' ),
		'last_name'  => array( __( 'Prezime', 'webni-custom-version-1' ), '' ),
		'company'    => array( __( 'Firma', 'webni-custom-version-1' ), '' ),
		'country'    => array( __( 'Država', 'webni-custom-version-1' ), '' ),
		'address_1'  => array( __( 'Ulica i broj', 'webni-custom-version-1' ), __( 'npr. Bulevar kralja Aleksandra 12', 'webni-custom-version-1' ) ),
		'address_2'  => array( __( 'Stan, sprat, ulaz', 'webni-custom-version-1' ), __( 'nije obavezno', 'webni-custom-version-1' ) ),
		'city'       => array( __( 'Grad', 'webni-custom-version-1' ), '' ),
		'state'      => array( __( 'Okrug', 'webni-custom-version-1' ), '' ),
		'postcode'   => array( __( 'Poštanski broj', 'webni-custom-version-1' ), __( 'npr. 11000', 'webni-custom-version-1' ) ),
	);
	foreach ( $labels as $key => $l ) {
		if ( isset( $fields[ $key ] ) ) {
			$fields[ $key ]['label'] = $l[0];
			if ( '' !== $l[1] ) {
				$fields[ $key ]['placeholder'] = $l[1];
			}
		}
	}
	return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'webni_wc_address_fields', 20 );

/**
 * "RSD" instead of the Cyrillic symbol WooCommerce ships for the dinar.
 *
 * @param string $symbol   Symbol.
 * @param string $currency Currency code.
 * @return string
 */
function webni_wc_currency_symbol( $symbol, $currency ) {
	return 'RSD' === $currency ? 'RSD' : $symbol;
}
add_filter( 'woocommerce_currency_symbol', 'webni_wc_currency_symbol', 10, 2 );
