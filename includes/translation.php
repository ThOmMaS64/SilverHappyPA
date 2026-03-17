<?php 

    include("db.php");

    function trad($text){

        $chosenLanguage = $_SESSION['language'] ?? 'fr';

        if($chosenLanguage == "fr" || $chosenLanguage == ""){
            return $text;
        }

        static $dictionary = null;

        if($dictionary == null){

            $dictionaryFile = __DIR__ . "/../dictionaries/" . $chosenLanguage . ".php";

            if(file_exists($dictionaryFile)){

                $dictionary = include($dictionaryFile);

            }else{
                $dictionary = [];
            }

        }

        if(isset($dictionary[$text])){
            return $dictionary[$text];
        }else{
            return tradByAPI($text);
        }
    
    }



    function tradByAPI($text){

        global $bdd;

        $chosenLanguage = $_SESSION['language'] ?? 'fr';

        if($chosenLanguage == "fr" || $chosenLanguage == ""){
            return $text;
        }

        $q = "SELECT translated_text FROM TRANSLATIONS WHERE original_text = :original_text AND target_language = :target_language";
        $req = $bdd->prepare($q);
        $req->execute([
            'original_text' => $text,
            'target_language' => $chosenLanguage
        ]);
        $found = $req->fetch(PDO::FETCH_ASSOC);

        if($found && !empty($found['translated_text'])){
            return $found['translated_text'];
        }

        $infos = [
            'q' => $text,
            'source' => 'fr',
            'target' => $chosenLanguage,
            'format' => 'text'
        ];

        $callAPI = curl_init('http://127.0.0.1:5000/translate');

        curl_setopt($callAPI, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($callAPI, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($callAPI, CURLOPT_POST, true);
        curl_setopt($callAPI, CURLOPT_POSTFIELDS, json_encode($infos));

        $result = curl_exec($callAPI);

        curl_close($callAPI);

        $translation = json_decode($result, true);

        if(isset($translation['translatedText'])){

            $q = "INSERT INTO TRANSLATIONS (original_text, translated_text, target_language) VALUES(:original_text, :translated_text, :target_language)";
            $req = $bdd->prepare($q);
            $req->execute([
                'original_text' => $text,
                'translated_text' => $translation['translatedText'],
                'target_language' => $chosenLanguage
            ]);

            return $translation['translatedText'];

        }else{

            return $text;

        }
    
    }