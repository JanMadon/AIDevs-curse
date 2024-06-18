# AI-Devs 2

Aplikacja (framework) służąca do realizacji zadań kursu wykorzystującego narzędzia AI

### Zadania znajdują się w:
    ./controllers/TaskControllers.php

### Klasy odpowiedzialne za połączenie z integracjami zewnętrznymi (głównie OpenAI) znajdziesz w:
    ./GPT

### Zadania wymagające vector database każystają z 
    - https://qdrant.tech/   -> np. "search"
    - https://github.com/qdrant/qdrant?tab=readme-ov-file


W katalogu Task znajdziesz zadania wywoływane z cli

## Installation 

    1. Clone the repository to your local system:

        ```
        git clone https://github.com/JanMadon/AIDevs-curse
        ```

    2. Navigate to the project directory:

        ```
        cd AIDevs-curse
        ```

    3. Copy the .env.example file to .env and set api keys
    
        ```
        cp .env.example .env
        ```

    4. Run dev server, example:
     
        ```
        php -S localhost:8080
        ```

    5. Go to the webside and select task
