<?php /* regular and Google fonts array*/
	
	$mattimeo_config = $this->model_setting_setting->getSetting('mattimeotheme', $this->data['store_id']);
	
	$fonts = array(
		''                         => '- default -',
		'Arial'                    => 'Arial',
		'Verdana'                  => 'Verdana',
		'Helvetica'                => 'Helvetica',
		'Lucida Grande'            => 'Lucida Grande',
		'Trebuchet MS'             => 'Trebuchet MS',
		'Times New Roman'          => 'Times New Roman',
		'Tahoma'                   => 'Tahoma',
		'Georgia'                  => 'Georgia',
        'Calibri'                  => 'Calibri',
		
		'Abel'                     => 'Abel',
		'Abril+Fatface'            => 'Abril Fatface',
		'Aclonica'                 => 'Aclonica',
		'Acme'                     => 'Acme',
		'Actor'                    => 'Actor',
		'Adamina'                  => 'Adamina',
        'Advent+Pro'               => 'Advent Pro',
		'Aguafina+Script'          => 'Aguafina Script',
		'Aladin'                   => 'Aladin',
		'Aldrich'                  => 'Aldrich',
		'Alegreya'                 => 'Alegreya',
		'Alegreya+SC'              => 'Alegreya SC',
		'Alex+Brush'               => 'Alex Brush',
		'Alfa+Slab+One'            => 'Alfa Slab One',
		'Alice'                    => 'Alice',
		'Alike'                    => 'Alike',
		'Alike+Angular'            => 'Alike Angular',
		'Allan'                    => 'Allan',
		'Allerta'                  => 'Allerta',
		'Allerta+Stencil'          => 'Allerta Stencil',
		'Allura'                   => 'Allura',
		'Almendra'                 => 'Almendra',
		'Almendra+SC'              => 'Almendra SC',
		'Amaranth'                 => 'Amaranth',
		'Amatic+SC'                => 'Amatic SC',
		'Amethysta'                => 'Amethysta',
        'Anaheim'                  => 'Anaheim',
		'Andada'                   => 'Andada',
		'Andika'                   => 'Andika',
		'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope',
		'Anonymous+Pro'            => 'Anonymous Pro',
		'Antic'                    => 'Antic',
		'Anton'                    => 'Anton',
		'Arapey'                   => 'Arapey',
		'Architects+Daughter'      => 'Architects Daughter',
        'Archivo Narrow'           => 'Archivo Narrow',
		'Arimo'                    => 'Arimo',
		'Arizonia'                 => 'Arizonia',
		'Armata'                   => 'Armata',
		'Artifika'                 => 'Artifika',
		'Arvo'                     => 'Arvo',
		'Asul'                     => 'Asul',
		'Atomic+Age'               => 'Atomic Age',
		'Aubrey'                   => 'Aubrey',
        'Average'                  => 'Average',
		'Bad+Script'               => 'Bad Script',
		'Bangers'                  => 'Bangers',
		'Basic'                    => 'Basic',
		'Baumans'                  => 'Baumans',
		'Belgrano'                 => 'Belgrano',
		'Bentham'                  => 'Bentham',
		'Bevan'                    => 'Bevan',
		'Bigshot+One'              => 'Bigshot One',
		'Bilbo'                    => 'Bilbo',
		'Bilbo+Swash+Caps'         => 'Bilbo Swash Caps',
		'Bitter'                   => 'Bitter',
		'Black+Ops+One'            => 'Black Ops One',
		'Boogaloo'                 => 'Boogaloo',
		'Bowlby+One'               => 'Bowlby One',
		'Bowlby+One+SC'            => 'Bowlby One SC',
		'Brawler'                  => 'Brawler',
		'Bree+Serif'               => 'Bree Serif',
		'Bubblegum+Sans'           => 'Bubblegum Sans',
		'Buda'                     => 'Buda',
		'Buenard'                  => 'Buenard',
		'Cabin'                    => 'Cabin',
		'Cabin+Condensed'          => 'Cabin Condensed',
		'Caesar+Dressing'          => 'Caesar Dressing',
		'Cagliostro'               => 'Cagliostro',
		'Cambo'                    => 'Cambo',
		'Candal'                   => 'Candal',
		'Cantarell'                => 'Cantarell',
		'Cardo'                    => 'Cardo',
		'Carme'                    => 'Carme',
		'Carter+One'               => 'Carter One',
		'Ceviche+One'              => 'Ceviche One',
		'Changa+One'               => 'Changa One',
		'Chango'                   => 'Chango',
		'Chelsea+Market'           => 'Chelsea Market',
		'Cherry+Cream+Soda'        => 'Cherry Cream Soda',
		'Chewy'                    => 'Chewy',
		'Chicle'                   => 'Chicle',
		'Coda'                     => 'Coda',
		'Coda+Caption'             => 'Coda Caption',
		'Comfortaa'                => 'Comfortaa',
		'Concert+One'              => 'Concert One',
		'Condiment'                => 'Condiment',
		'Contrail+One'             => 'Contrail One',
		'Convergence'              => 'Convergence',
		'Cookie'                   => 'Cookie',
		'Copse'                    => 'Copse',
		'Corben'                   => 'Corben',
		'Cousine'                  => 'Cousine',
		'Coustard'                 => 'Coustard',
		'Covered+By+Your+Grace'    => 'Covered By Your Grace',
		'Crete+Round'              => 'Crete Round',
		'Crimson+Text'             => 'Crimson Text',
		'Crushed'                  => 'Crushed',
		'Cuprum'                   => 'Cuprum',
		'Damion'                   => 'Damion',
		'Dancing+Script'           => 'Dancing Script',
		'Days+One'                 => 'Days One',
		'Delius'                   => 'Delius',
		'Delius+Unicase'           => 'Delius Unicase',
		'Devonshire'               => 'Devonshire',
		'Didact+Gothic'            => 'Didact Gothic',
		'Dorsa'                    => 'Dorsa',
		'Dosis'                    => 'Dosis',
		'Dr+Sugiyama'              => 'Dr Sugiyama',
		'Droid+Sans'               => 'Droid Sans',
		'Droid+Sans+Mono'          => 'Droid Sans Mono',
		'Droid+Serif'              => 'Droid Serif',
		'Duru+Sans'                => 'Duru Sans',
		'Dynalight'                => 'Dynalight',
		'Eater'                    => 'Eater',
		'EB+Garamond'              => 'EB Garamond',
		'Electrolize'              => 'Electrolize',
		'Emblema+One'              => 'Emblema One',
		'Engagement'               => 'Engagement',
		'Enriqueta'                => 'Enriqueta',
		'Erica+One'                => 'Erica One',
		'Esteban'                  => 'Esteban',
		'Euphoria+Script'          => 'Euphoria Script',
		'Exo'                      => 'Exo',
		'Expletus+Sans'            => 'Expletus Sans',
		'Fanwood+Text'             => 'Fanwood Text',
        'Fauna+One'                => 'Fauna One',
		'Federant'                 => 'Federant',
		'Federo'                   => 'Federo',
		'Felipa'                   => 'Felipa',
        'Fjalla One'                   => 'Fjalla One',
		'Fjord+One'                => 'Fjord One',
		'Flamenco'                 => 'Flamenco',
		'Flavors'                  => 'Flavors',
		'Fondamento'               => 'Fondamento',
		'Fontdiner+Swanky'         => 'Fontdiner Swanky',
		'Forum'                    => 'Forum',
		'Francois+One'             => 'Francois One',
		'Fresca'                   => 'Fresca',
		'Fugaz+One'                => 'Fugaz One',
		'Gentium+Basic'            => 'Gentium Basic',
		'Gentium+Book+Basic'       => 'Gentium Book Basic',
		'Geo'                      => 'Geo',
		'Germania+One'             => 'Germania One',
        'Gilda Display'            => 'Gilda Display',
		'Give+You+Glory'           => 'Give You Glory',
		'Glegoo'                   => 'Glegoo',
		'Gloria+Hallelujah'        => 'Gloria Hallelujah',
		'Goblin+One'               => 'Goblin One',
		'Gochi+Hand'               => 'Gochi Hand',
		'Goudy+Bookletter+1911'    => 'Goudy Bookletter 1911',
		'Gravitas+One'             => 'Gravitas One',
		'Gruppo'                   => 'Gruppo',
		'Gudea'                    => 'Gudea',
		'Habibi'                   => 'Habibi',
		'Hammersmith+One'          => 'Hammersmith One',
		'Handlee'                  => 'Handlee',
		'Holtwood+One+SC'          => 'Holtwood One SC',
		'Homenaje'                 => 'Homenaje',
		'Iceberg'                  => 'Iceberg',
		'Iceland'                  => 'Iceland',
		'IM+Fell+Double+Pica'      => 'IM Fell Double Pica',
		'IM+Fell+Double+Pica+SC'   => 'IM Fell Double Pica SC',
		'IM+Fell+DW+Pica+SC'       => 'IM Fell DW Pica SC',
		'IM+Fell+French+Canon'     => 'IM Fell French Canon',
		'IM+Fell+French+Canon+SC'  => 'IM Fell French Canon SC',
		'IM+Fell+Great+Primer'     => 'IM Fell Great Primer',
		'IM+Fell+Great+Primer+SC'  => 'IM Fell Great Primer SC',
		'Inconsolata'              => 'Inconsolata',
		'Inder'                    => 'Inder',
		'Indie+Flower'             => 'Indie Flower',
		'Irish+Grover'             => 'Irish Grover',
		'Italianno'                => 'Italianno',
		'Jim+Nightshade'           => 'Jim Nightshade',
		'Jockey+One'               => 'Jockey One',
		'Josefin+Sans'             => 'Josefin Sans',
		'Josefin+Slab'             => 'Josefin Slab',
		'Judson'                   => 'Judson',
		'Julee'                    => 'Julee',
		'Junge'                    => 'Junge',
		'Jura'                     => 'Jura',
		'Just+Another+Hand'        => 'Just Another Hand',
		'Kameron'                  => 'Kameron',
		'Kaushan+Script'           => 'Kaushan Script',
		'Kelly+Slab'               => 'Kelly Slab',
		'Kenia'                    => 'Kenia',
        'Kite One'                    => 'Kite One',
		'Knewave'                  => 'Knewave',
		'Kotta+One'                => 'Kotta One',
		'Kreon'                    => 'Kreon',
		'Lancelot'                 => 'Lancelot',
		'Lato'                     => 'Lato',
		'Latoblack'                => 'Latoblack',
		'Lekton'                   => 'Lekton',
		'Lemon'                    => 'Lemon',
		'Lilita+One'               => 'Lilita One',
		'Limelight'                => 'Limelight',
		'Linden+Hill'              => 'Linden Hill',
		'Lobster'                  => 'Lobster',
		'Lobster+Two'              => 'Lobster Two',
		'Lora'                     => 'Lora',
		'Love+Ya+Like+A+Sister'    => 'Love Ya Like A Sister',
		'Luckiest+Guy'             => 'Luckiest Guy',
		'Lustria'                  => 'Lustria',
		'Macondo'                  => 'Macondo',
		'Macondo+Swash+Caps'       => 'Macondo Swash Caps',
		'Magra'                    => 'Magra',
		'Maiden+Orange'            => 'Maiden Orange',
		'Mako'                     => 'Mako',
		'Marck+Script'             => 'Marck Script',
		'Marko+One'                => 'Marko One',
		'Marmelad'                 => 'Marmelad',
		'Marvel'                   => 'Marvel',
		'Mate'                     => 'Mate',
		'Mate+SC'                  => 'Mate SC',
		'Maven+Pro'                => 'Maven Pro',
		'MedievalSharp'            => 'MedievalSharp',
		'Medula+One'               => 'Medula One',
		'Megrim'                   => 'Megrim',
		'Merienda+One'             => 'Merienda One',
		'Merriweather'             => 'Merriweather',
        'Merriweather+Sans'        => 'Merriweather Sans',
		'Metamorphous'             => 'Metamorphous',
		'Metrophobic'              => 'Metrophobic',
		'Michroma'                 => 'Michroma',
		'Miltonian+Tattoo'         => 'Miltonian Tattoo',
		'Modern+Antiqua'           => 'Modern Antiqua',
		'Molengo'                  => 'Molengo',
		'Monoton'                  => 'Monoton',
		'Montaga'                  => 'Montaga',
		'Montez'                   => 'Montez',
		'Mountains+of+Christmas'   => 'Mountains of Christmas',
		'Mr+Bedfort'               => 'Mr Bedfort',
		'Mr+Dafoe'                 => 'Mr Dafoe',
		'Mrs+Sheppards'            => 'Mrs Sheppards',
		'Muli'                     => 'Muli',
		'Neucha'                   => 'Neucha',
		'Neuton'                   => 'Neuton',
		'News+Cycle'               => 'News Cycle',
		'Niconne'                  => 'Niconne',
		'Nixie+One'                => 'Nixie One',
		'Nobile'                   => 'Nobile',
		'Norican'                  => 'Norican',
		'Nosifer'                  => 'Nosifer',
		'Nothing+You+Could+Do'     => 'Nothing You Could Do',
		'Noticia+Text'             => 'Noticia Text',
		'Nova+Cut'                 => 'Nova Cut',
		'Nova+Flat'                => 'Nova Flat',
		'Nova+Mono'                => 'Nova Mono',
		'Nova+Oval'                => 'Nova Oval',
		'Nova+Round'               => 'Nova Round',
		'Nova+Script'              => 'Nova Script',
		'Nova+Slim'                => 'Nova Slim',
		'Nova+Square'              => 'Nova Square',
		'Numans'                   => 'Numans',
		'Nunito'                   => 'Nunito',
		'Old+Standard+TT'          => 'Old Standard TT',
		'Oldenburg'                => 'Oldenburg',
		'Open+Sans'                => 'Open Sans',
		'Open+Sans+Condensed'      => 'Open Sans Condensed',
		'Orbitron'                 => 'Orbitron',
		'Original+Surfer'          => 'Original Surfer',
		'Oswald'                   => 'Oswald',
		'Overlock'                 => 'Overlock',
		'Overlock+SC'              => 'Overlock SC',
		'Ovo'                      => 'Ovo',
        'Oxygen'                   => 'Oxygen',
		'Pacifico'                 => 'Pacifico',
		'Parisienne'               => 'Parisienne',
		'Passero+One'              => 'Passero One',
		'Passion+One'              => 'Passion One',
		'Patrick+Hand'             => 'Patrick Hand',
		'Patua+One'                => 'Patua One',
        'Pathway+Gothic+One'       => 'Pathway Gothic One',
		'Paytone+One'              => 'Paytone One',
		'Permanent+Marker'         => 'Permanent Marker',
		'Petrona'                  => 'Petrona',
		'Philosopher'              => 'Philosopher',
		'Piedra'                   => 'Piedra',
		'Pinyon+Script'            => 'Pinyon Script',
		'Play'                     => 'Play',
		'Playball'                 => 'Playball',
		'Playfair+Display'         => 'Playfair Display',
		'Podkova'                  => 'Podkova',
		'Poller+One'               => 'Poller One',
		'Pompiere'                 => 'Pompiere',
		'Pontano+Sans'                 => 'Pontano Sans',
		'Prata'                    => 'Prata',
		'Prociono'                 => 'Prociono',
		'PT+Sans'                  => 'PT Sans',
		'PT+Sans+Caption'          => 'PT Sans Caption',
		'PT+Sans+Narrow'           => 'PT Sans Narrow',
		'PT+Serif'                 => 'PT Serif',
		'PT+Serif+Caption'         => 'PT Serif Caption',
		'Quantico'                 => 'Quantico',
		'Quattrocento'             => 'Quattrocento',
		'Quattrocento+Sans'        => 'Quattrocento Sans',
		'Questrial'                => 'Questrial',
		'Quicksand'                => 'Quicksand',
		'Qwigley'                  => 'Qwigley',
		'Radley'                   => 'Radley',
		'Raleway'                  => 'Raleway',
        'Rambla'                   => 'Rambla',
		'Rammetto+One'             => 'Rammetto One',
		'Rancho'                   => 'Rancho',
		'Rationale'                => 'Rationale',
		'Redressed'                => 'Redressed',
		'Reenie+Beanie'            => 'Reenie Beanie',
		'Ribeye'                   => 'Ribeye',
		'Ribeye+Marrow'            => 'Ribeye Marrow',
		'Righteous'                => 'Righteous',
        'Roboto'                   => 'Roboto',
        'Roboto+Condensed'         => 'Roboto Condensed',
		'Roboto+Slab'              => 'Roboto Slab',
		'Rochester'                => 'Rochester',
		'Rock+Salt'                => 'Rock Salt',
		'Rokkitt'                  => 'Rokkitt',
		'Ropa+Sans'                => 'Ropa Sans',
		'Rosario'                  => 'Rosario',
		'Ruda'                     => 'Ruda',
        'Rufina'                   => 'Rufina',
		'Ruluko'                   => 'Ruluko',
		'Ruslan+Display'           => 'Ruslan Display',
		'Sail'                     => 'Sail',
		'Salsa'                    => 'Salsa',
		'Sancreek'                 => 'Sancreek',
        'Sanchez'                  => 'Sanchez',
		'Sansita+One'              => 'Sansita One',
		'Satisfy'                  => 'Satisfy',
		'Shadows+Into+Light'       => 'Shadows Into Light',
		'Shanti'                   => 'Shanti',
		'Share'                    => 'Share',
		'Sigmar+One'               => 'Sigmar One',
		'Signika'                  => 'Signika',
		'Signika+Negative'         => 'Signika Negative',
        'Sintony'                 => 'Sintony',
		'Six+Caps'                 => 'Six Caps',
		'Slackey'                  => 'Slackey',
		'Smokum'                   => 'Smokum',
		'Smythe'                   => 'Smythe',
		'Sofia'                    => 'Sofia',
		'Sonsie+One'               => 'Sonsie One',
		'Sorts+Mill+Goudy'         => 'Sorts Mill Goudy',
		'Source+Sans+Pro'          => 'Source Sans Pro',
		'Special+Elite'            => 'Special Elite',
		'Spicy+Rice'               => 'Spicy Rice',
		'Spinnaker'                => 'Spinnaker',
		'Spirax'                   => 'Spirax',
		'Squada+One'               => 'Squada One',
		'Stardos+Stencil'          => 'Stardos Stencil',
		'Stint+Ultra+Condensed'    => 'Stint Ultra Condensed',
        'Strait'                   => 'Strait',
		'Stoke'                    => 'Stoke',
		'Sue+Ellen+Francisco'      => 'Sue Ellen Francisco',
		'Supermercado+One'         => 'Supermercado One',
		'Syncopate'                => 'Syncopate',
        'Tauri'                    => 'Tauri',
		'Tangerine'                => 'Tangerine',
        'Telex'                    => 'Telex',
		'Tenor+Sans'               => 'Tenor Sans',
		'Terminal+Dosis'           => 'Terminal Dosis',
		'Tienne'                   => 'Tienne',
		'Tinos'                    => 'Tinos',
		'Titan+One'                => 'Titan One',
        'Titillium Web'            => 'Titillium Web',
		'Trade+Winds'              => 'Trade Winds',
		'Trochut'                  => 'Trochut',
		'Trykker'                  => 'Trykker',
		'Tulpen+One'               => 'Tulpen One',
		'Ubuntu'                   => 'Ubuntu',
		'Ubuntu+Condensed'         => 'Ubuntu Condensed',
		'Ubuntu+Mono'              => 'Ubuntu Mono',
		'Ultra'                    => 'Ultra',
		'Uncial+Antiqua'           => 'Uncial Antiqua',
		'UnifrakturCook'           => 'UnifrakturCook',
		'UnifrakturMaguntia'       => 'UnifrakturMaguntia',
		'Unkempt'                  => 'Unkempt',
		'Unlock'                   => 'Unlock',
		'Varela'                   => 'Varela',
		'Varela+Round'             => 'Varela Round',
		'Vibur'                    => 'Vibur',
		'Vidaloka'                 => 'Vidaloka',
		'Viga'                     => 'Viga',
		'Volkhov'                  => 'Volkhov',
		'Vollkorn'                 => 'Vollkorn',
		'Voltaire'                 => 'Voltaire',
		'VT323'                    => 'VT323',
		'Waiting+for+the+Sunrise'  => 'Waiting for the Sunrise',
		'Wallpoet'                 => 'Wallpoet',
		'Walter+Turncoat'          => 'Walter Turncoat',
		'Wellfleet'                => 'Wellfleet',
		'Wire+One'                 => 'Wire One',
		'Yanone+Kaffeesatz'        => 'Yanone Kaffeesatz',
		'Yellowtail'               => 'Yellowtail',
		'Yeseva+One'               => 'Yeseva One',
		'Yesteryear'               => 'Yesteryear',
		); 
?>
<div class="form-horizontal">

 <div class="row-fluid">
<div class="span3"><h4><?php echo $custom_comptext_text; ?></h4></div>

<div class="span3"><?php 
					$valueq 	= $comptext_status; $name = 'comptext_status'; $id	= 'comptext_status';
				?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
</div>
</div><hr />

 <div class="row-fluid">
					<div class="span2"><?php echo $text_parallax_full; ?></div>
					<div class="span10">
						<?php 
							$ar 	= array( 'Fixed' =>0 , 'Full' =>1 ); 
							$valueq 	= $parallax_width; $name	= 'parallax_width'; $id = 'parallax_width';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
					</div>
				</div><hr> 
                                   
  <div class="row-fluid">
					      
              <div class="span2"><?php echo $text_parallax_image; ?></div>
                    <div class="span2">
                       <div class="image">
               <img src="<?php echo $parallax_preview; ?>" alt="" id="parallax_preview">
                <?php 
				$valueq 			= isset($parallax_bg) ? $parallax_bg : '';
				$name			= 'parallax_bg';
				$id				= 'parallax_bg';
   			?>
	        		<input type="hidden" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">

                  <br>
                  <a onclick="image_upload('parallax_bg', 'parallax_preview');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#parallax_preview').attr('src', '<?php echo $no_image; ?>'); $('#parallax_bg').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
                    </div>  
               </div>     
                <hr /> 
   
      <table class="all_topmenulink">
      <tr><td>
	  <b><?php echo $tab_products_text; ?></b><br /><span class="help">(Autocomplete)</span></td>
      <td><input type="text" name="product" value="" /><br /><br />
       <div id="featured-product" class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($products as $product) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div id="featured-product<?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"><?php echo $product['name']; ?> <img src="view/image/delete.png" alt="" />
                  <input type="hidden" value="<?php echo $product['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
              <input type="hidden" name="featured_product2" value="<?php echo $featured_product2; ?>" />
      
      </td>
      <td>
            <?php echo $text_parallax_width; ?>         
            
            <input type="text" name="parall_p_width" value="<?php echo $parall_p_width; ?>" class="shortfield"> 
			<input type="text" name="parall_p_height" value="<?php echo $parall_p_height; ?>"  class="shortfield"><br /><br />
             <?php echo $text_parallax_limit2; ?>         
            <input type="text" name="parall_limit2" value="<?php echo $parall_limit2; ?>" class="shortfield"><br /><br />
             <?php echo $text_parallax_limit; ?>         
            <input type="text" name="parall_limit" value="<?php echo $parall_limit; ?>" class="shortfield"> 
           </td></tr>
            
           </table>
           
         <hr />                        
   
   
  <h5><?php echo $text_parallax_text; ?></h5> 
  <div class="row-fluid">
   
    <div class="span3">
			<select name="parall_fonts">
							<?php foreach ($fonts as $fv => $fc) { ?>
								<?php ($fv ==  $mattimeo_config['parall_fonts']) ? $currentfont = 'selected' : $currentfont=''; ?>
								<option value="<?php echo $fv; ?>" <?php echo $currentfont; ?> ><?php echo $fc; ?></option>	
							<?php } ?>
		  </select>
     </div>
      <div class="span2">
                      Size: <select name="parall_fonts_size" class="shortfield" >
							<option value="none"selected="selected"></option>
							<?php for ($i = 9; $i <= 40; $i++) { 
									($mattimeo_config['parall_fonts_size']== $i) ? $currentpat = 'selected' : $currentpat = '';
								?>
								<option value="<?php echo $i; ?>" <?php echo $currentpat; ?>><?php echo $i; ?></option>
								<?php } ?>
						</select>

     </div>
      <div class="span2">
   <?php 
							$ar 	= array( 'Normal' =>false , 'Bold' =>true ); 
							$valueq 	= $parall_fonts_weight; $name	= 'parall_fonts_weight'; $id = 'parall_fonts_weight';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
</div>
 <div class="span2">
<?php 
							$ar 	= array( 'None' =>false , 'Uppercase' =>true ); 
							$valueq 	= $parall_fonts_transf; $name	= 'parall_fonts_transf'; $id = 'parall_fonts_transf';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
</div>
   <div class="span3">
   Color: <input type="text"  name="parall_fonts_color" value="<?php echo $parall_fonts_color; ?>" class="color {required:false,hash:true} shortfield" >
    </div>
   
</div><br />
  
  <hr /> 
                 
			
				<ul class="nav nav-tabs" id="footer_comptextus">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#ft-about<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<div class="tab-content">

					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane langu" id="ft-about<?php echo $language['language_id']; ?>">

						<div class="row-fluid">
							<div class="span10">
								<textarea name="matt_array[<?php echo $language['language_id']; ?>][comptext_text]" id="matt_array-<?php echo $language['language_id']; ?>-comptext_text"><?php echo isset($matt[$language['language_id']]['comptext_text']) ? $matt[$language['language_id']]['comptext_text'] : ''; ?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				
	
			
</div>

