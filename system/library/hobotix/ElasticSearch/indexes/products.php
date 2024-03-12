<?php

 $productsIndex = [
            'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
            'body' => [
                'settings' => ['analysis' => ['filter' => [
                    'ru_stop' => ['type' => 'stop', 'stopwords' => '_russian_'],
                    'en_stop' => ['type' => 'stop', 'stopwords' => '_english_'],
                    'de_stop' => ['type' => 'stop', 'stopwords' => '_german_'],
                    'ua_stop' => ['type' => 'stop', 'stopwords' => '_ukrainian_'],
                    'ru_stemmer' => ['type' => 'stemmer', 'language' => 'russian'],
                    'en_stemmer' => ['type' => 'stemmer', 'language' => 'english'],
                    'de_stemmer' => ['type' => 'stemmer', 'language' => 'german'],
                    'ua_stemmer' => ['type' => 'stemmer', 'language' => 'russian'],
                    'phonemas' => ['type' => 'phonetic', 'replace' => 'false', 'encoder' => 'refined_soundex']
                ],
                    'char_filter' => ['ru_en_key' => [
                        'type' => 'mapping', 'mappings' => [
                            'a => ф', 'b => и', 'c => с', 'd => в', 'e => у', 'f => а', 'g => п', 'h => р', 'i => ш', 'j => о', 'k => л', 'l => д', 'm => ь', 'n => т', 'o => щ', 'p => з', 'r => к', 's => ы', 't => е', 'u => г', 'v => м', 'w => ц', 'x => ч', 'y => н', 'z => я', 'A => Ф', 'B => И', 'C => С', 'D => В', 'E => У', 'F => А', 'G => П', 'H => Р', 'I => Ш', 'J => О', 'K => Л', 'L => Д', 'M => Ь', 'N => Т', 'O => Щ', 'P => З', 'R => К', 'S => Ы', 'T => Е', 'U => Г', 'V => М', 'W => Ц', 'X => Ч', 'Y => Н', 'Z => Я', '[ => х', '] => ъ', '; => ж', '< => б', '> => ю'
                        ]]],
                    'analyzer' => [
                        'russian' => ['char_filter' => ['html_strip', 'ru_en_key'], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ru_stop', 'en_stop', 'ru_stemmer', 'en_stemmer', 'phonemas']],
                        'ukrainian' => ['char_filter' => ['html_strip', 'ru_en_key'], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ua_stop', 'en_stop', 'ua_stemmer', 'en_stemmer', 'phonemas']],
                        'deutch' => ['char_filter' => ['html_strip'], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'de_stop', 'de_stemmer', 'phonemas']],
                        'integer' => ['char_filter' => ['html_strip'], 'tokenizer' => 'standard'],
                        'identifier' => ['char_filter' => ['html_strip'], 'tokenizer' => 'standard', 'filter' => ['lowercase']]
                    ]]],
                'mappings' => [
                    'properties' => [
                        'category_id' => ['type' => 'integer', 'index' => 'true'],
                        'manufacturer_id' => ['type' => 'integer', 'index' => 'true'],
                        'product_id' => ['type' => 'integer', 'index' => 'true'],
                        'collection_id' => ['type' => 'integer', 'index' => 'true'],
                        'priority' => ['type' => 'integer', 'index' => 'true'],
                        'sort_order' => ['type' => 'integer', 'index' => 'true'],
                        'viewed' => ['type' => 'integer', 'index' => 'true'],
                        'price' => ['type' => 'float', 'index' => 'true'],
                        'bought_for_week' => ['type' => 'integer', 'index' => 'true'],
                        'bought_for_month' => ['type' => 'integer', 'index' => 'true'],
                        'name_ru' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'name_ua' => ['type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true'],
                        'name_uk' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'names_ru' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'names_ua' => ['type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true'],
                        'names_uk' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'names_de' => ['type' => 'text', 'analyzer' => 'deutch', 'index' => 'true'],
                        'identifier' => ['type' => 'text', 'analyzer' => 'identifier', 'index' => 'true'],
                        'stock_status_id' => ['type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']]],
                        'status' => ['type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']]],
                        'quantity' => ['type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']]],
                        'quantity_stock' => ['type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']]],
                        $this->config->get('config_warehouse_identifier') => ['type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']]],
                        $this->config->get('config_warehouse_identifier_local') => ['type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']]],
                        'stores' => ['type' => 'integer', 'index' => 'true'],
                        'categories' => ['type' => 'integer', 'index' => 'true'],
                    ]
                ]
            ]
        ];