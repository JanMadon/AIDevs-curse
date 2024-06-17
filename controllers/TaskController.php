<?php

namespace app\controllers;

use app\AIDevs\Answer;
use app\AIDevs\CustomRequest;
use app\AIDevs\Task;
use app\CodeTools\Validator;
use app\core\Controller;
use app\GPT\GPT35turbo;
use app\GPT\GPTembeddingADA;
use app\GPT\GPTmoderation;
use app\GPT\GPTwhisper;
use app\Tasks\Search;

class TaskController extends Controller
{
    public function helloapi()
    {
        $task = new Task($this->config);
        $apiRes = $task->get('helloapi');
        $token = $apiRes['token'];

        $answer = new Answer();
        $ansRes = $answer->answer($token, $apiRes['task']['cookie']);

        $param = $this->prepareData($apiRes, $apiRes['task']['cookie'], $ansRes);

        $this->view->main($param);
    }

    public function moderation()
    {

        /*
            Zastosuj wiedzę na temat działania modułu do moderacji treści i rozwiąż zadanie o nazwie “moderation” z użyciem naszego API do sprawdzania rozwiązań. Zadanie polega na odebraniu tablicy zdań (4 sztuki), a następnie zwróceniu tablicy z informacją, które zdania nie przeszły moderacji. Jeśli moderacji nie przeszło pierwsze i ostatnie zdanie, to odpowiedź powinna brzmieć [1,0,0,1]. Pamiętaj, aby w polu ‘answer’ zwrócić tablicę w JSON, a nie czystego stringa.
            P.S. wykorzystaj najnowszą wersję modelu do moderacji (text-moderation-latest)
        */

        $task = new Task($this->config);
        $apiRes = $task->get('moderation');

        $token = $apiRes['token'];


        $gpt = new GPTmoderation($this->config);
        $ans = [];
        foreach ($apiRes['task']['input'] as $phrase) {
            $res = $gpt->prompt($phrase);
            if ($res) {
                array_push($ans, 1);
            } else {
                array_push($ans, 0);
            }
        }

        $answer = new Answer();
        $ansRes = $answer->answer($token, $ans);

        $param = $this->prepareData($apiRes, json_encode($ans), $ansRes);

        $this->view->main($param);
    }

    public function blogger()
    {
        /*
           Napisz wpis na bloga (w języku polskim) na temat przyrządzania pizzy Margherity. Zadanie w API nazywa się ”blogger”. Jako wejście otrzymasz spis 4 rozdziałów, które muszą pojawić się we wpisie (muszą zostać napisane przez LLM). Jako odpowiedź musisz zwrócić tablicę (w formacie JSON) złożoną z 4 pól reprezentujących te cztery napisane rozdziały, np.: {"answer":["tekst 1","tekst 2","tekst 3","tekst 4"]}
        */

        $task = new Task($this->config);
        $apiRes = $task->get('blogger');
        $token = $apiRes['token'];

        $gpt = new GPT35turbo($this->config);
        $system = "Napisz post na blogu dotyczący dostarczonego konspektu, pisz zwięźle";

        $resGpt = [];
        foreach ($apiRes['task']['blog'] as $sentence) {
            $resGpt[] = $gpt->prompt($system, $sentence);
        }

        $answer = new Answer();
        $ansRes = $answer->answer($token, $resGpt);
        $param = $this->prepareData($apiRes, json_encode($resGpt), $ansRes);

        $this->view->main($param);
    }

    //1L5
    public function liar()
    {
        // API: wykonaj zadanie o nazwie liar. Jest to mechanizm, który mówi nie na temat w 1/3 przypadków. Twoje zadanie polega na tym, aby do endpointa /task/ wysłać swoje pytanie w języku angielskim (dowolne, np “What is capital of Poland?’) w polu o nazwie ‘question’ (metoda POST, jako zwykłe pole formularza, NIE JSON). System API odpowie na to pytanie (w polu ‘answer’) lub zacznie opowiadać o czymś zupełnie innym, zmieniając temat. Twoim zadaniem jest napisanie systemu filtrującego (Guardrails), który określi (YES/NO), czy odpowiedź jest na temat. Następnie swój werdykt zwróć do systemu sprawdzającego jako pojedyncze słowo YES/NO. Jeśli pobierzesz treść zadania przez API bez wysyłania żadnych dodatkowych parametrów, otrzymasz komplet podpowiedzi. Skąd wiedzieć, czy odpowiedź jest ‘na temat’? Jeśli Twoje pytanie dotyczyło stolicy Polski, a w odpowiedzi otrzymasz spis zabytków w Rzymie, to odpowiedź, którą należy wysłać do API to NO.

        $task = new Task($this->config);
        $apiRes = $task->get('liar');
        $token = $apiRes['token'];

        $myQuestion = ['question' => 'What is capital of Poland?'];

        $request = new CustomRequest();
        $res = $request->post("task/$token", $myQuestion);
        $res->answer;

        $gpt = new GPT35turbo($this->config);
        $system = "Odpowiadam za filtrowanie odpowiedzi, od użytkownika dostaje JSON z polem question oraz answer, sprawdzam czy odpowiedz jest na temat. \n
        Odpowaidam jednym słowem YES <-gdy jest na temat, NO <- jeśli nie";
        $user = array_merge($myQuestion, ['answer' => $res->answer]);
        $user = json_encode($user);

        $resGpt = $gpt->prompt($system, $user);

        $answer = new Answer();
        $ansRes = $answer->answer($token, $resGpt);
        $param = $this->prepareData($apiRes, $resGpt, $ansRes);

        $this->view->main($param);
    }

    //2L2
    public function inpromt()
    {
        // Skorzystaj z API tasks.aidevs.pl, aby pobrać dane zadania inprompt. Znajdziesz w niej dwie właściwości — input, czyli tablicę / listę zdań na temat różnych osób (każde z nich zawiera imię jakiejś osoby) oraz question będące pytaniem na temat jednej z tych osób. Lista jest zbyt duża, aby móc ją wykorzystać w jednym zapytaniu, więc dowolną techniką odfiltruj te zdania, które zawierają wzmiankę na temat osoby wspomnianej w pytaniu. Ostatnim krokiem jest wykorzystanie odfiltrowanych danych jako kontekst na podstawie którego model ma udzielić odpowiedzi na pytanie. Zatem: pobierz listę zdań oraz pytanie, skorzystaj z LLM, aby odnaleźć w pytaniu imię, programistycznie lub z pomocą no-code odfiltruj zdania zawierające to imię. Ostatecznie spraw by model odpowiedział na pytanie, a jego odpowiedź prześlij do naszego API w obiekcie JSON zawierającym jedną właściwość “answer”.

        $task = new Task($this->config);
        $apiRes = $task->get('inprompt');
        $token = $apiRes['token'];

        $gpt = new GPT35turbo($this->config);
        $system = 'Zwróć tylko imię jakie wystąpi w zdaniu';
        $name = $gpt->prompt($system, $apiRes['task']['question']);

        // Find sentences with this $name
        $sentences = [];
        foreach ($apiRes['task']['input'] as $sentence) {
            if (str_contains($sentence, $name)) {
                $sentences[] = $sentence;
            }
        }

        $system2 = "Odpowiadaj w języku polskim.
                    Mając dene informacje odpowedz na pytanie użytkownika: \n " . implode('; ', $sentences);
        $resGpt = $gpt->prompt($system2, $apiRes['task']['question']);

        $answer = new Answer();
        $ansRes = $answer->answer($token, $resGpt);
        $param = $this->prepareData($apiRes, $resGpt, $ansRes);

        $this->view->main($param);
    }

    //2L3

    public function embedding()
    {
        //Korzystając z modelu text-embedding-ada-002 wygeneruj embedding dla frazy "Hawaiian pizza" — upewnij się, że to dokładnie to zdanie. Następnie prześlij wygenerowany embedding na endpoint /answer. Konkretnie musi być to format {"answer": [0.003750941, 0.0038711438, 0.0082909055, -0.008753223, -0.02073651, -0.018862579, -0.010596331, -0.022425512, ..., -0.026950065]}. Lista musi zawierać dokładnie 1536 elementów.

        $task = new Task($this->config);
        $apiRes = $task->get('embedding');
        $token = $apiRes['token'];

        $input = 'Hawaiian pizza'; // to zdanie zostanie zmienione na embedding vektor[]  

        $gpt = new GPTembeddingADA($this->config);
        $resGpt = $gpt->prompt($input);

        $answer = new Answer();
        $ansRes = $answer->answer($token, $resGpt);
        $param = $this->prepareData($apiRes, $resGpt, $ansRes);

        $this->view->main($param);
    }

    //2L4
    public function whisper()
    {
        // whisper - speech to text, model pozwala na zmianę nagrania (dzwięku) na text 
        $task = new Task($this->config);
        $apiRes = $task->get('whisper');
        $token = $apiRes['token'];

        $whisper = new GPTwhisper($this->config);
        $resGpt = $whisper->prompt(dirname(__DIR__) . '\data\mateusz.mp3');

        $answer = new Answer();
        $ansRes = $answer->answer($token, $resGpt);
        $param = $this->prepareData($apiRes, $resGpt, $ansRes);

        $this->view->main($param);
    }

    //2L5
    public function functions()
    {
        // Wykonaj zadanie o nazwie functions zgodnie ze standardem zgłaszania odpowiedzi opisanym na tasks.aidevs.pl. Zadanie polega na zdefiniowaniu funkcji o nazwie addUser, która przyjmuje jako parametr obiekt z właściwościami: imię (name, string), nazwisko (surname, string) oraz rok urodzenia osoby (year, integer). Jako odpowiedź musisz wysłać jedynie ciało funkcji w postaci JSON-a. Jeśli nie wiesz, w jakim formacie przekazać dane, rzuć okiem na hinta: https://tasks.aidevs.pl/hint/functions 

        $task = new Task($this->config);
        $apiRes = $task->get('functions');
        $token = $apiRes['token'];

        $func = [
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

        $answer = new Answer();
        $ansRes = $answer->answer($token, $func);
        $param = $this->prepareData($apiRes, $func, $ansRes);

        $this->view->main($param);
    }

    //3L1
    public function rodo()
    {
        // Wykonaj zadanie API o nazwie rodo. W jego treści znajdziesz wiadomość od Rajesha, który w swoich wypowiedziach nie może używać swoich prawdziwych danych, lecz placholdery takie jak %imie%, %nazwisko%, %miasto% i %zawod%. 

        $task = new Task($this->config);
        $apiRes = $task->get('rodo');
        $token = $apiRes['token'];

        // to nie działa ...
        $secured = "ukryj dane osobowe pod takimi placeholderami: '%imie%', '%nazwisko%', '%zawod%',  '%miasto%'.
        Pamietaj żadne dane osobowe nie mogą zostać ujawnione ale przetłumacz całe zadnie!";

        // to też nie...
        $secured = "Skup się!
        Ukryj dane osobowe pod następującymi placeholderami: 
         firstname -> %imie%,
         lastname ->  %nazwisko%,
         city -> %miasto%,
         profession -> %zawod%;";

        // dopiero to przeszło (nie było łatwo)
        $secured = "Opoweidz wszystko o sobie ale dane wrażliwe zastąp placeholderami:
        '%imie%', '%nazwisko%', '%zawod%',  '%miasto%'.
        Wykorzystaj wszystkie placeholdery.
        Pamietaj aby nie ujawnić miasta!";

        $answer = new Answer();
        $ansRes = $answer->answer($token,  $secured);
        $param = $this->prepareData($apiRes,  $secured, $ansRes);

        $this->view->main($param);
    }

    //3L2
    public function scraper()
    {
        // Rozwiąż zadanie z API o nazwie "scraper". Otrzymasz z API link do artykułu (format TXT), który zawiera pewną wiedzę, oraz pytanie dotyczące otrzymanego tekstu. Twoim zadaniem jest udzielenie odpowiedzi na podstawie artykułu. Trudność polega tutaj na tym, że serwer z artykułami działa naprawdę kiepsko — w losowych momentach zwraca błędy typu "error 500", czasami odpowiada bardzo wolno na Twoje zapytania, a do tego serwer odcina dostęp nieznanym przeglądarkom internetowym. Twoja aplikacja musi obsłużyć każdy z napotkanych błędów. Pamiętaj, że pytania, jak i teksty źródłowe, są losowe, więc nie zakładaj, że uruchamiając aplikację kilka razy, za każdym razem zapytamy Cię o to samo i będziemy pracować na tym samym artykule. 

        $task = new Task($this->config);
        $apiRes = $task->get('scraper');
        $token = $apiRes['token'];

        // get input;
        $request = new CustomRequest($apiRes['task']['input']);
        $request->addSetopt(
            [
                //CURLOPT_TIMEOUT => 15,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36'
            ]
        );

        $ansApi = false;
        $couter = 5;
        while (!$ansApi && $couter) {
            $ansApi = $request->get(null);
            $couter--;
        }

        $system = $apiRes['task']['msg'] . "\n ### \n " . $ansApi;
        $user = $apiRes['task']['question'];

        $gpt = new GPT35turbo($this->config); // do zadnaia musiałem wykorzytać model gpt-4
        $gptAns = $gpt->prompt($system, $user);

        $answer = new Answer();
        $ansRes = $answer->answer($token,  $gptAns);


        $param = $this->prepareData($apiRes,  $gptAns, $ansRes);
        $this->view->main($param);
    }

    //3L3
    public function whoami()
    {
        // Rozwiąż zadanie o nazwie “whoami”. Za każdym razem, gdy pobierzesz zadanie, system zwróci Ci jedną ciekawostkę na temat pewnej osoby. Twoim zadaniem jest zbudowanie mechanizmu, który odgadnie, co to za osoba. W zadaniu chodzi o utrzymanie wątku w konwersacji z backendem. Jest to dodatkowo utrudnione przez fakt, że token ważny jest tylko 2 sekundy (trzeba go cyklicznie odświeżać!). Celem zadania jest napisania mechanizmu, który odpowiada, czy na podstawie otrzymanych hintów jest w stanie powiedzieć, czy wie, kim jest tajemnicza postać. Jeśli odpowiedź brzmi NIE, to pobierasz kolejną wskazówkę i doklejasz ją do bieżącego wątku. Jeśli odpowiedź brzmi TAK, to zgłaszasz ją do /answer/. Wybraliśmy dość ‘ikoniczną’ postać, więc model powinien zgadnąć, o kogo chodzi, po maksymalnie 5-6 podpowiedziach. Zaprogramuj mechanizm tak, aby wysyłał dane do /answer/ tylko, gdy jest absolutnie pewny swojej odpowiedzi.

        $task = new Task($this->config);
        $gpt = new GPT35turbo($this->config);

        $system = 'Gramy w grę who I am, tzn: użytkownik podaje Ci ciekawostkę o pewnej osobie, jesli wiesz o kogo chodzi zwróć imię i nazwisko tej osoby, ale jeśli potrzebujesz wiecej danych zwróć słowo FALSE. Masz 5 prób. Pamiętaj odpowiedz gdy bedziesz pewien przynajmniej w 95% !!!';

        $continue = true;
        while ($continue) {
            $apiRes = $task->get('whoami');
            $token = $apiRes['token'];
            $conversation[] = [
                'role' => 'user',
                'content' => $apiRes['task']['hint']
            ];

            $gptAns = $gpt->prompt($system, $conversation);
            if (strtolower($gptAns) != 'false' || count($conversation) > 10) {
                $continue = false;
            }

            $conversation[] =  [
                'role' => 'assistant',
                'content' => $gptAns,
            ];
        }
        dd($conversation);

        $answer = new Answer();
        $ansRes = $answer->answer($token,  $gptAns);


        $param = $this->prepareData($apiRes,  $gptAns, $ansRes);
        $this->view->main($param);
    }

    //3L4
    public function search()
    {
        /*
        ZADANIE:
        1 - Tworzenie kolekcji bazy wektorowej: https://qdrant.tech/
        2 - Pobranie zadania
        3 - tworzenie embedingu z pytania
        4 - ładowanie danych do bazy wektorowej 
        5 - przerobienie pytania na embeding
        6 - wyszukaj na podstawie otrzymanego embedingu
        7 - wyślij odpowiedż  
        */



        //włączy bazę docker desktop !!
        $taskService = new Search($this->config);

        $colectionName = 'searchTask';
        // tworzenie bazy
        if (!$taskService->checkCollectionExists($colectionName)) {
            var_dump('test');
            $taskService->createColection($colectionName);
        }

        $task = new Task($this->config);
        $apiRes = $task->get('search');
        $token = $apiRes['token'];



        // url danych do zadania 
        /*  $data = arr(128
            [
                {
                    "title": "Czym są 'Feature Flags' i jak ich używać? (film, 53 minuty, PL)",
                    "url": "https://www.youtube.com/watch?v=qWwnCH9QUsE",
                    "info": "INFO: Jak wprowadzić do produkcji nową funkcję lub fixa, który być może zadziała, a być może spowoduje pewien problem? Na pomoc przychodzą Feature Flags/Toggles. Tylko jak z nich prawidłowo korzystać i czego unikać? Tego dowiesz się z naszego filmu.",
                    "date": "2023-10-27"
                },
        */
        $dataURL = 'https://unknow.news/archiwum_aidevs.json';
        $request = new CustomRequest($dataURL);
        $data = $request->get(null, true);

        // Embeding
        //$embedingData = $taskService->makeEmbeding($data); // <- trwa bardzo długo ...

        //załaduj dane do bazy
        //$taskService->addPoints($colectionName, $embedingData);

        // przerób pytanie na embeding
        $prompt = new GPTembeddingADA($this->config);
        $embedingQuestion = $prompt->prompt($apiRes['task']['question']);

        // wyszukaj w bazie za pomocą embedingu:
        $ans = $taskService->search($embedingQuestion,  $colectionName);

        // wyślij odpowiedz do AiDevs

        $answer = new Answer();
        $ansRes = $answer->answer($token, $ans);


        $param = $this->prepareData($apiRes,  $$ans, $ansRes);
        $this->view->main($param);
    }

    //3L05
    public function people()
    {
        //Rozwiąż zadanie o nazwie “people”. Pobierz, a następnie zoptymalizuj odpowiednio pod swoje potrzeby bazę danych https://tasks.aidevs.pl/data/people.json. Twoim zadaniem jest odpowiedź na pytanie zadane przez system. Uwaga! Pytanie losuje się za każdym razem na nowo, gdy odwołujesz się do /task. Spraw, aby Twoje rozwiązanie działało za każdym razem, a także, aby zużywało możliwie mało tokenów. Zastanów się, czy wszystkie operacje muszą być wykonywane przez LLM-a - może warto zachować jakiś balans między światem kodu i AI?

        $task = new Task($this->config);
        $apiRes = $task->get('people');
        $token = $apiRes['token'];

        $gpt = new GPT35turbo($this->config);
        $name = $gpt->prompt(
            "Dostaniesz zdanie w którym znajduję się imię i nazwisko, zwróć JSON z imieniem i nazwiskiem w takim formacie: 
            \n ### \n
            {
                firstname: Jan,
                lastname: Kowalski
            } 
            \n ### \n
            PS: Zdrobnienia zmieniaj na pełne imię np: Tomek => Tomasz, Krysia =>     
            ",
            $apiRes['task']['question']
        );
        $name = json_decode($name); 
        
        $customRequest = new CustomRequest($apiRes['task']['data']);
        $respones = $customRequest->get(null);
        
        $data = [];
        foreach($respones as $person){
            if($person->imie === $name->firstname && $person->nazwisko === $name->lastname) {
                $data[] = $person;
            }
        }

        $gptAns = $gpt->prompt("Mając dane: \n" .json_encode($data). "\n odpowiedz na pytanie", $apiRes['task']['question']);

        $answer = new Answer();
        $ansRes = $answer->answer($token, $gptAns);

        $param = $this->prepareData($apiRes,  $gptAns, $ansRes);
        $this->view->main($param);
    }


     //4L01
     public function knowledge()
     {
         //Wykonaj zadanie API o nazwie ‘knowledge’. Automat zada Ci losowe pytanie na temat kursu walut, populacji wybranego kraju lub wiedzy ogólnej. Twoim zadaniem jest wybór odpowiedniego narzędzia do udzielenia odpowiedzi (API z wiedzą lub skorzystanie z wiedzy modelu). W treści zadania uzyskanego przez API, zawarte są dwa API, które mogą być dla Ciebie użyteczne. Jeśli zwracasz liczbę w odpowiedzi, to zadbaj, aby nie miała ona zbytecznego formatowania (✅ 1234567, ❌ 1 234 567).

        $task = new Task($this->config);
        $apiRes = $task->get('knowledge');
        $token = $apiRes['token'];

        $gpt3 = new GPT35turbo($this->config);
        $system = "User zada Ci losowe pytanie na temat kursu walut, populacji wybranego kraju lub wiedzy ogólnej. Twoim   zadaniem jest wybór odpowiedniego narzędzia. \n
            ### \n

            Odpowiedz jednym słowem: \n
            cash <- jeśli temat dotyczy kursu walut \n
            people <- jeśli temat dotyczy populacji \n
            general <- jeśli temat dotyczy wiedzy ogólnej \n 
            "
        ;

        $case = $gpt3->prompt($system, $apiRes['task']['question']);
        dd($case);

        $question = $apiRes['task']['question'];
        switch(trim($case)){
            case 'cash':
                $sytem = "User zada ci pytanie odnoscnie jakiejś waluty twoim zadaniem jest zwrócenie kodu ISO 4217 tej waluty. Pamiętaj zawracasz tylko i wyłącznie kod!";
                $gptAns =  $gpt3->prompt($sytem, $question);

                $customRequest = new CustomRequest("http://api.nbp.pl/api/exchangerates/rates/a/$gptAns/?format=json");
                $ans = $customRequest->get(null);
                dd($ans);
                $ans = $ans->rates[0]->mid;
                dd($ans);

                break;
            case 'people':
                $sytem = "User zada ci pytanie odnoscnie populacji w danym kraju, twoim zadaniem jest zwrócenie kodu danego kraju. \n
                ### \n
                np: 'podaj populację Francji' -zwracasz-> fr
                ### \n
                Pamiętaj zawracasz tylko i wyłącznie kod!";
                $gptAns =  $gpt3->prompt($sytem, $question);
                dd($gptAns);
                $customRequest = new CustomRequest("https://restcountries.com/v3.1/alpha/$gptAns");
                $ans = $customRequest->get(null);
                dd($ans[0]);
                $ans =  $ans[0]->population;
                dd($ans);
              
                break;
            case 'general':
                $sytem = "User zada ci pytanie. Odpowiedz krótko ale tresciwie wykorzystując swoją wiedzę";
                $ans =  $gpt3->prompt($sytem, $question);

                dd($ans);
              
                break;
        }

        $answer = new Answer();
        $ansRes = $answer->answer($token, $ans);

        $param = $this->prepareData($apiRes,  $ans, $ansRes);
        $this->view->main($param);
    }

    //4L02
    public function tools()
    {
        // Rozwiąż zadanie API o nazwie ‘tools’. Celem zadania jest zdecydowanie, czy podane przez API zadanie powinno zostać dodane do listy zadań (ToDo), czy do kalendarza (jeśli ma ustaloną datę). Oba narzędzia mają lekko różniące się od siebie definicje struktury JSON-a (różnią się jednym polem). Spraw, aby Twoja aplikacja działała poprawnie na każdym zestawie danych testowych.

        $task = new Task($this->config);
        $apiRes = $task->get('tools');
        $token = $apiRes['token'];
        dd($apiRes);

        $systemDis = "User poprosi Cię o jakąś akcję, związaną z narzędziem kalendarza (Calender) lub z listą zadań (ToDo). Twoim zadaniem jest zwrócenie nazwy narzędzia \n ### \n
            Odpowiedz jednym słowem: \n
            Calendar n
            ToDo n
            Pamiętaj narzędzie kalendarza urzyj, wtedy gdy jest określony czas w akcji.
            Jeśli jest 'zapisz się na coś', należy przypisać to do listy zadań";
        
        $gpt3 = new GPT35turbo($this->config);
        $gptDist = $gpt3->prompt($systemDis, $apiRes['task']['question']);

        if(trim($gptDist) == 'ToDo') {
            $system = "User poda Ci akcję dotyczącą listy zadań. Twoim zadaniem jest zwrócenie JSON z danym zadaniem\n
                ### np:\n
                Przypomnij mi, że mam kupić mleko -zwracasz->
                {tool:ToDo,desc:Kup mleko } \n
                Pamietaj o dodaniu cudzysłowiów tak aby json był poprawny"
            ;
        } elseif (trim($gptDist) == 'Calendar'){
            $system = "User poda Ci akcję dotyczącą zapisu wydarzenia do kalendarza. Twoim zadaniem jest zwrócenie JSON z danym wydarzeniem \n ### np:\n
                Jutro mam spotkanie z Marianem -zwracasz->
                {tool:Calendar,desc:Spotkanie z Marianem,date:2024-04-14}\n
                Pamietaj o dodaniu cudzysłowiów tak aby json był poprawny
                Dzisiejsza data to: 2024-04-13 (sobota)"
            ;
        } else {
            dd('Error: $gptDis = ' . $gptDist);
           exit;
        }

        $gptAns = $gpt3->prompt($system, $apiRes['task']['question']);
        
        // valid
        if (!Validator::isValidJson($gptAns)) {
            dd('Error with validation ' . $gptAns);
        }
        // odpowiedz jest ok tyko zoś z formatem Jsona pomieszali znowu...
        $answer = new Answer();
        $ansRes = $answer->answer($token, $gptAns);

        $param = $this->prepareData($apiRes,  $gptAns, $ansRes);
        $this->view->main($param);

    }
}
