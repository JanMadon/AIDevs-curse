<?php

namespace app\Tasks;

use app\Answer\Answer;
use app\Prompts\GPTpromptADA;

class Search
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
        $colectionName = 'searchTask';
        // tworzenie bazy
        if (!$this->checkCollectionExists($colectionName)) {
            var_dump('test');
            $this->createColection($colectionName);
        }

        // pobieranie zadania:
        $task = new Task($this->conf);
        $response = $task->get('search');
        print_r($response);

        $curl = curl_init('https://unknow.news/archiwum_aidevs.json');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($curl), true);

        // stworz embeding
        //$embedingData = $this->makeEmbeding($data);
       
        //załaduj dane do bazy
        //$this->addPoints($colectionName, $embedingData);

        // przerób pytanie na embeding
        $prompt = new GPTpromptADA($this->conf);
        $embedingQuestion = $prompt->message($response['task']['question']);

        // wyszukaj w bazie za pomocą embedingu:
        $answer = $this->search($embedingQuestion,  $colectionName);
        var_dump($answer->payload->url);

        // wyślij odpowiedz do AiDevs
        $res = Answer::answer($response['token'], $answer->payload->url);
        print_r($res);

    }

    private function checkCollectionExists($name)
    {

        $curl = curl_init("http://localhost:6333/collections/$name");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($curl));
        print_r(curl_error($curl));
        curl_close($curl);

        return !isset($response->status->error);
    }

    private function createColection($name)
    {

        $jsonData = json_encode([
            'vectors' => [
                'size' => 1536,
                'distance' => 'Dot',
            ]
        ]);

        $curl = curl_init("http://localhost:6333/collections/$name");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"); //tak się put ustawia
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($curl);
        print_r(curl_getinfo($curl));
        curl_close($curl);

        var_dump($response);

        return $response;
    }

    private function addPoints(string $colectionName, array $data)
    {
        $payLoad = [];
        foreach ($data['embeding'] as $id => $embeding) {
            $payLoad['points'][] = [
                'id' => $id,
                'vector' => $embeding,
                "payload" => [
                    "url" => $data['meta'][$id]
                ]
            ];
        }

        $curl = curl_init("http://localhost:6333/collections/$colectionName/points?wait=true");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"); //tak się put ustawia
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payLoad));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($curl);
        print_r(curl_getinfo($curl));
        curl_close($curl);

        var_dump($response);

        return $response;
    }

    private function makeEmbeding(array $payload): array
    {
         // stworzenie embedingu
         $prompt = new GPTpromptADA($this->conf);
         $data = [];
         foreach ($payload as $index => $item) {
             $concat = $item['title'] . "\n ### \n" . $item['info'];
             $embeding = $prompt->message($concat); // [0.05,0.61,0.76,0.74 .... -n-> n=1532]
             $data['embeding'][] = $embeding;
             $data['meta'][] = $item['url'];

            print_r($index);
            //  if($index > 2 ){
            //      break;
            //  }
         }
         return $data;
    }

    private function search(array $embedingQuestion, string $colection)
    {
        
        $payLoad = json_encode([
            'vector' => $embedingQuestion,
            'limit' => 1,
            "with_payload" => true
        ]);
        
        $curl = curl_init("http://localhost:6333/collections/$colection/points/search");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payLoad);

        $answer = json_decode(curl_exec($curl));
        print_r(curl_error($curl));
        curl_close($curl);

        return  $answer->result[0];
    }
}
