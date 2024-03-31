<?php

namespace app\Tasks;

use Answer\Answer;

class Functions
{
    private array $conf;

    /**
     * @param $conf
     */
    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    public function run()
    {
        $task = new Task($this->conf);
        $response = $task->get('functions');
        var_dump($response);

        $answer = [
            'name' => 'addUser',
            'description' => 'the function can add user to system',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
                    'surname' => [
                        'type' => 'string',
                    ],
                    'year' => [
                        'type' => 'integer',
                    ]
                ]
            ]
        ];

        // answer
        $res = Answer::answer($response['token'], $answer);
        print_r($res);
    }

}