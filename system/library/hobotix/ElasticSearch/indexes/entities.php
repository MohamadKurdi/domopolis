<?php

 $entitiesIndex = [
            'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
            'body' => [
                'settings' => [
                    'analysis' =>
                        [
                            'filter' => [
                                'ru_stop' => ['type' => 'stop', 'stopwords' => '_russian_'],
                                'en_stop' => ['type' => 'stop', 'stopwords' => '_english_'],
                                'ua_stop' => ['type' => 'stop', 'stopwords' => '_ukrainian_'],
                                'ru_stemmer' => ['type' => 'stemmer', 'language' => 'russian'],
                                'en_stemmer' => ['type' => 'stemmer', 'language' => 'english'],
                                'ua_stemmer' => ['type' => 'stemmer', 'language' => 'russian'],
                                'phonemas' => ['type' => 'phonetic', 'replace' => 'false', 'encoder' => 'refined_soundex']
                            ],
                            'char_filter' => [
                                'ru_en_key' => [
                                    'type' => 'mapping', 'mappings' => [
                                        'a => ф', 'b => и', 'c => с', 'd => в', 'e => у', 'f => а', 'g => п', 'h => р', 'i => ш', 'j => о', 'k => л', 'l => д', 'm => ь', 'n => т', 'o => щ', 'p => з', 'r => к', 's => ы', 't => е', 'u => г', 'v => м', 'w => ц', 'x => ч', 'y => н', 'z => я', 'A => Ф', 'B => И', 'C => С', 'D => В', 'E => У', 'F => А', 'G => П', 'H => Р', 'I => Ш', 'J => О', 'K => Л', 'L => Д', 'M => Ь', 'N => Т', 'O => Щ', 'P => З', 'R => К', 'S => Ы', 'T => Е', 'U => Г', 'V => М', 'W => Ц', 'X => Ч', 'Y => Н', 'Z => Я', '[ => х', '] => ъ', '; => ж', '< => б', '> => ю'
                                    ]
                                ]
                            ],
                            'analyzer' => [
                                'russian' => ['char_filter' => ['html_strip', 'ru_en_key'], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ru_stop', 'en_stop', 'ru_stemmer', 'en_stemmer', 'phonemas']],
                                'ukrainian' => ['char_filter' => ['html_strip', 'ru_en_key'], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ua_stop', 'en_stop', 'ua_stemmer', 'en_stemmer', 'phonemas']],
                                'integer' => ['char_filter' => ['html_strip'], 'tokenizer' => 'standard']
                            ]
                        ]
                ],
                'mappings' => [
                    'properties' => [
                        'category_id' => ['type' => 'integer', 'index' => 'true'],
                        'manufacturer_id' => ['type' => 'integer', 'index' => 'true'],
                        'collection_id' => ['type' => 'integer', 'index' => 'true'],
                        'priority' => ['type' => 'integer', 'index' => 'true'],
                        'type' => ['type' => 'text', 'index' => 'true'],
                        'name_ru' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'name_ua' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'name_uk' => ['type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true'],
                        'suggest_ru' => ['type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'russian', 'index' => 'true', 'contexts' => [['name' => 'suggest-priority', 'type' => 'category', 'path' => 'type']]],
                        'suggest_ua' => ['type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'russian', 'index' => 'true', 'contexts' => [['name' => 'suggest-priority', 'type' => 'category', 'path' => 'type']]],
                        'suggest_uk' => ['type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'ukrainian', 'index' => 'true', 'contexts' => [['name' => 'suggest-priority', 'type' => 'category', 'path' => 'type']]],
                        'names_ru' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'names_ua' => ['type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true'],
                        'names_uk' => ['type' => 'text', 'analyzer' => 'russian', 'index' => 'true'],
                        'stores' => ['type' => 'integer', 'index' => 'true']
                    ]
                ]
            ]
        ];