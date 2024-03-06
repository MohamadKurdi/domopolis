<?php
// Headings
$_['lang_openbay']                = 'OpenBay Pro';
$_['lang_page_title']             = 'OpenBay Pro für eBay';
$_['lang_ebay']                   = 'eBay';
$_['lang_heading']                = 'verbundene Artikel';
$_['lang_linked_items']           = 'verbundene Artikel';
$_['lang_unlinked_items']         = 'nicht verbundene Artikel';

// Buttons
$_['lang_btn_resync']             = 'Synchronisieren';
$_['lang_btn_return']             = 'Zurück';
$_['lang_btn_save']               = 'Speichern';
$_['lang_btn_edit']               = 'Ändern';
$_['lang_btn_check_unlinked']     = 'Prüfe unverbundene Artikel';
$_['lang_btn_remove_link']        = 'Verbindung löschen';

// Errors & alerts
$_['lang_error_validation']       = 'Sie müssen sich für Ihren API Token anmelden und dieses Modul aktivieren.';
$_['lang_alert_stock_local']      = 'Ihr eBay Angebot wird mit dem lokalen Lagerbestand aktualisiert !';
$_['lang_ajax_error_listings']    = 'Es konnten keine Angebote gefunden werden oder es sind bereits alle Artikel verbunden.';
$_['lang_ajax_load_error']        = 'Es konnte keine Antwort ermittelt werden. Bitte nochmal versuchen.';
$_['lang_ajax_error_link']        = 'Die Artikelverbindung hat keinen Wert.';
$_['lang_ajax_error_link_no_sk']  = 'Eine Verbindung kann nicht für Artikel erstellt werden, die keinen Lagerbestand haben. Beenden Sie das Angebot manuell auf eBay.';
$_['lang_ajax_loaded_ok']         = 'Artikel wurden erfolgreich geladen';

// Text
$_['lang_link_desc1']             = 'Das Verbinden von Artikel erlaubt es Ihnen, den Lagerbestand Ihres eBay Angebots zu kontrollieren.';
$_['lang_link_desc2']             = 'Bei verbundenen Artikeln wird jede Änderung des Lagerbestand aus OpenCart an das eBay Angebot weitergegeben.';
$_['lang_link_desc3']             = 'Ihr Lagerbestand ist zum Verkauf verfügbarer Bestand. Ihr eBay Lagerbestand sollte damit Übereinstimmen.';
$_['lang_link_desc4']             = 'Ihr reservierter Lagerbestand sind Artikel, die verkauft, aber noch nicht bezahlt sind. Diese Artikel sollten separat gelagert werden und nicht Teil des verfügbaren Lagerbestands sein.';
$_['lang_text_linked_desc']         = 'Linked items are OpenCart items that have a link to an eBay listing.';
$_['lang_text_unlinked_desc']       = 'Unlinked items are listings on your eBay account that do not link to any of your OpenCart products.';
$_['lang_text_unlinked_info']       = 'Click the check unlinked items button to search your active eBay listings for unlinked items. This may take a long time if you have many eBay listings.';
$_['lang_text_loading_items']       = 'Loading items';

// Tables
$_['lang_column_action']            = 'Aktion';
$_['lang_column_status']            = 'Status';
$_['lang_column_variants']          = 'Varianten';
$_['lang_column_itemId']            = 'eBay item ID';
$_['lang_column_product']           = 'Produkt';
$_['lang_column_product_auto']      = 'Produkt<span class="help">(Autovervollständigung nach Bezeichnung)</span>';
$_['lang_column_stock_available']   = 'Lagerbestand<span class="help">verfügbar</span>';
$_['lang_column_listing_title']     = 'Angebotsbezeichnung<span class="help">(eBay Angebotsbezeichnung)</span>';
$_['lang_column_allocated']         = 'Reserviert<span class="help">Verkauft aber nicht bezahlt</span>';
$_['lang_column_ebay_stock']        = 'eBay Lagerbestand<span class="help">im Angebot</span>';
?>