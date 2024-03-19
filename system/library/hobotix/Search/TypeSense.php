<?php

namespace hobotix\Search;

class TypeSense
{

    private $db		 = null;
    private $config	 = null;
    private $typeSense = null;

    public function __construct(\Registry $registry){
        $this->config 	= $registry->get('config');
        $this->db 		= $registry->get('db');

        try {
            $this->typeSense = new \Typesense\Client([
                'nodes' => [
                    [
                        'host' => $this->config->get('config_typesense_host'),
                        'port' => $this->config->get('config_typesense_port'),
                        'protocol' => $this->config->get('config_typesense_protocol'),
                    ]
                ],
                'api_key' => $this->config->get('config_typesense_api_key')
            ]);
        } catch (\Exception $e){
        }
    }

    public function do(){
        print_r($this->typeSense->health->retrieve());

        $this->typeSense->collections['products']->delete();

        $schema = [
            'name' => 'products',
            'fields' => [
                ['name' => 'product_name', 'type' => 'string', 'locale' => 'uk'],
                ['name' => 'manufacturer', 'type' => 'string'],
                ['name' => 'description', 'type' => 'string', 'locale' => 'uk'],
                ['name' => 'price', 'type' => 'float'],
                [
                    "name" => "embedding-openai",
                    "type" => "float[]",
                    "embed" => [
                        "from" => [
                            "product_name"
                        ],
                        "model_config" => [
                            "model_name"    => "openai/text-embedding-3-small",
                            /* OLD KEY IS ALREADY DELETED, SORRY, SHIT HAPPENS, LOL */
                            "api_key"       => $this->config->get('config_typesense_embedded_name_index_model_api_key')
                        ]
                    ]
                ],
                [
                    "name" => "embedding-miniLM",
                    "type" => "float[]",
                    "embed" => [
                        "from" => [
                            "product_name",
                            "manufacturer"
                        ],
                        "model_config" => [
                            "model_name" => "ts/all-MiniLM-L12-v2"
                        ]
                    ]
                ]
            ]
        ];

        try{
            $this->typeSense->collections->create($schema);
        } catch (\Typesense\Exceptions\ObjectAlreadyExists $e){
            echoLine($e->getMessage(), 'w');
        }

        print_r($this->typeSense->collections->retrieve());
    }

    public function index($json)
    {
        $this->typeSense->collections['products']->documents->create($json);
    }

    public function search($string){
        $search_parameters = [
            'q'                         => $string,
            'split_join_tokens'         => 'always',
            'typo_tokens_threshold'     => 2,
            'include_fields'            => 'product_name',
            "prefix" => false,
            'query_by'                  => 'product_name, embedding-openai',
        ];

        return $this->typeSense->collections['products']->documents->search($search_parameters);
    }
}